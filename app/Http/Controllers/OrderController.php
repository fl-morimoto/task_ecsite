<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\BalanceTransaction;
use App\Cart;
use App\Address;
use App\Order;
use App\OrderDetail;
use App\Payment;
use App\PaymentStatuses;
use App\Item;
use App\User;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class OrderController extends Controller
{
	private $cart;
	private $address;
	private $item;
	private $order;
	private $order_detail;
	private $payment;
	private $payment_statuses;
	private $user;

	public function __construct(
		Payment $payment,
		PaymentStatuses $payment_statuses,
		Order $order,
		Address $address,
		Item $item,
		User $user,
		OrderDetail $order_detail,
		Cart $cart)
	{
		$this->cart = $cart;
		$this->address = $address;
		$this->user = $user;
		$this->item = $item;
		$this->order = $order;
		$this->order_detail = $order_detail;
		$this->payment = $payment;
		$this->payment_statuses = $payment_statuses;
	}
	private function sendMail($payment_status, $order) {
		$body = $order->user_name . '様' . "\n" . "\n" .
			'ご注文の商品が' . $payment_status->status . 'となりましたのでご連絡致します。' . "\n" .
			'この度はご注文ありがとうございました。' . "\n" . "\n" .
			'from ecsite.';
		Mail::raw($body, function ($m) use (&$order) {
			$m->from('hello@app.com', 'from :ecsite - user info');
			$m->to($order->email, $order->user_name)->subject('laravel-ecsite注文番号' . $order->id);
		});
	}
	public function changeStatus(Request $req) {
		$status_id = decryptOrNull($req->status);
		if (empty($status_id)) {
			return redirect(route('admin.order.index'))->with('false_message', 'その操作は無効です。');
		}
		$order = $this->order
			->select('orders.*', 'users.email')
			->join('users', 'orders.user_id', '=', 'users.id')
			->where('orders.id', '=', $req->order_id)
			->first();
		$payment = $this->payment->where('order_id', $req->order_id)->first();
		if ($payment->payment_status_id == config('status.CANCELED')) {
			return redirect(route('admin.order.detail', ['id' => encrypt($req->order_id)]))->with('false_message', 'キャンセル済みのステータスを変更することはできません。');
		} elseif ($status_id <= $payment->payment_status_id) {
			return redirect(route('admin.order.detail', ['id' => encrypt($req->order_id)]))->with('false_message', 'ステータスは上位方向にのみ変更することができます。');
		}
		$payment->fill([
			'payment_status_id' => $status_id,
		]);
		DB::beginTransaction();
		try {
			$payment->save();
			$payment_status = $this->payment_statuses->where('id', $payment->payment_status_id)->first();
			$this->sendMail($payment_status, $order);
			if ($status_id == config('status.CANCELED')) {
				$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
				$stripe->refunds->create(array(
					'charge' => $req->stripe_code,
				));
			}
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			return redirect(route('admin.order.detail', ['id' => encrypt($req->order_id)]))->with('false_message', $e . 'が原因でステータスの更新は失敗しました。');

		}
		return redirect(route('admin.order.detail', ['id' => encrypt($req->order_id)]))->with('true_message', 'ステータスを更新しました。');
	}
	public function indexForAdmin(Request $req) {
		$status_ope = $this->order->statusOpe($req);
		$username_ope = $this->order->usernameOpe($req);
		$username_sql_value = $this->order->search_username($req);
		$search['username'] = $this->order->username($req);
		$search['status_id'] = $this->order->status($req);
		$search['amount_from'] = $this->order->search_amount_from($req);
		$search['amount_to'] = $this->order->search_amount_to($req);
		$amount_from = $this->order->amount_from($req);
		$amount_to = $this->order->amount_to($req);
		$search['date_from'] = $this->order->search_date_from($req);
		$search['date_to'] = $this->order->search_date_to($req);
		$date_from = $this->order->date_from($req);
		$date_to = $this->order->date_to($req);
		$orders = $this->order
			->select('orders.*', 'payments.stripe_code', 'payment_statuses.status', 'payments.payment_status_id')
			->join('payments', 'orders.id', '=', 'payments.order_id')
			->join('payment_statuses', 'payments.payment_status_id', '=', 'payment_statuses.id')
			->where('payments.payment_status_id', $status_ope, $search['status_id'])
			->where('orders.amount', '>=', $amount_from)
			->where('orders.amount', '<=', $amount_to)
			->where('orders.created_at', '>=', $date_from)
			->where('orders.created_at', '<=', $date_to)
			->where('orders.user_name', $username_ope, $username_sql_value)
			->orderBy('created_at', 'desc')
			->sortable()
			->paginate(15);
		return view('order.indexForAdmin', compact('orders', 'search'));
	}
	public function index(Request $req) {
		if (!empty($req->is_cancel)) {
			$ope = '=';
			$status = config('status.CANCELED');
		} else {
			$ope = '<>';
			$status = false;
		}
		$orders = $this->order
			->select('orders.*', 'payments.stripe_code', 'payment_statuses.status', 'payments.payment_status_id')
			->join('payments', 'orders.id', '=', 'payments.order_id')
			->join('payment_statuses', 'payments.payment_status_id', '=', 'payment_statuses.id')
			->where('user_id', '=', userInfo()->id)
			->where('payments.payment_status_id', $ope, config('status.CANCELED'))
			->orderBy('created_at', 'desc')
			->paginate(15);
		return view('order.index', compact('orders', 'status'));
	}
	public function detail(Request $req) {
		$order_id = decryptOrNull($req->id);
		if (empty($order_id)) {
			if (getUserType() == 'User') {
				$redirect_route = 'order.index';
			} elseif (getUserType() == 'Admin') {
				$redirect_route = 'admin.order.index';
			}
			return redirect(route($redirect_route))->with('false_message', 'その操作は無効です。');
		}
		$details = $this->order_detail
			->where('order_details.order_id', '=', $order_id)
			->get();
		$order = $this->order
			->select('orders.*', 'payments.stripe_code', 'payment_statuses.status', 'payments.payment_status_id')
			->join('payments', 'orders.id', '=', 'payments.order_id')
			->join('payment_statuses', 'payments.payment_status_id', '=', 'payment_statuses.id')
			->where('orders.id', '=', $order_id)
			->first();
		$total = $this->order_detail->total($details);
		$statuses = $this->payment_statuses->all();
		return view('order.detail', compact('order', 'details', 'total', 'statuses'));
	}
	public function confirm(Request $req) {
		if (!empty($req->carts)) {
			$user = userinfo();
			$carts = $this->cart->where('user_id', $user->id)->get();
			$total = $this->cart->total($carts);
		}
		$address = $this->address->where('id', $req->address)->first();
		session(['address' => encrypt($address->id)]);//encrypt
		return view('order.confirm', compact('address', 'carts', 'total'), ['order' => $this->order]);
	}
	public function cancel(Request $req) {
		$is_canceld = $this->payment->where('stripe_code', $req->stripe_code)->first()->payment_status_id == config('status.CANCELED');
		if ($is_canceld) {
			return redirect(route('order.index'))->with('false_message', 'その注文は既にキャンセル済みです。');
		}
		DB::beginTransaction();
		try {
			$stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
			$stripe->refunds->create(array(
				'charge' => $req->stripe_code,
			));
			$this->payment->where('stripe_code', $req->stripe_code)
				->update(['payment_status_id' => config('status.CANCELED')]);
			$order_id = $this->payment->where('stripe_code', $req->stripe_code)->first()->order_id;
			$order_details = $this->order_detail->where('order_id', $order_id)->get();
			foreach ($order_details as $detail) {
				$item = $this->item->where('id', $detail->item_id)->first();
				$item->increment('quantity', $detail->item_quantity);
			}
			DB::commit();
			return redirect(route('order.index'))->with('true_message', '指定の注文をキャンセルしました。');
		} catch (Exception $e) {
			DB::rollback();
			return redirect(route('order.index'))->with('false_message', 'なんらかの原因でキャンセルが失敗しました。');
		}
	}
	public function charge(Request $req) {
		//カート情報の読込
		$address_id = decryptOrNull(session('address'));
		if (empty($address_id)) {
			return redirect(route('cart.index'))->with('false_message', 'その操作は無効です。');
		}
		$user = userinfo();
		$carts_arr = [];
		$carts = $this->cart->where('user_id', $user->id)->get();
		$address = $this->address->where('id', $address_id)->first();
		DB::beginTransaction();
		//オーダーのDB処理
		$this->order->fill([
			'amount' => $this->cart->total($carts),
				'user_id' => $user->id,
				'user_name' => $user->name,
				'user_zip' => $address->zip,
				'user_state' => $address->state,
				'user_city' => $address->city,
				'user_street' => $address->street,
				'user_tel' => $address->tel,
			]);
		$this->order->save();
		$order = $this->order;
		session(['address' => null]);
		foreach ($carts as $cart) {
			$arr = [
				'order_id' => $order->id,
				'item_id' => $cart->item->id,
				'item_name' => $cart->item->name,
				'item_price' => $cart->item->price,
				'item_quantity' => $cart->quantity,
			];
			array_push($carts_arr, $arr);
			$cart->delete();
		}
		DB::table('order_details')->insert($carts_arr);
		//stripe決済処理
		$status_msg = '';
		try {
			Stripe::setApiKey(config('services.stripe.secret'));
			$customer = Customer::create(array(
				'email' => $req->stripeEmail,
				'source' => $req->stripeToken
			));
			$charge = Charge::create(array(
				'customer' => $customer->id,
				'amount' => $this->cart->total($carts),
				'currency' => 'jpy'
			));
			$fee = BalanceTransaction::all(['source' => $charge])->data[0]->fee;
			$this->payment->fill([
				'order_id' => $order->id,
				'payment_status_id' => config('status.COLLECTING_ITEMS'),
				'stripe_code' => $charge->id,
				'user_id' => $user->id,
				'stripe_fee' => $fee,
			]);
			$this->payment->save();
			DB::commit();
		} catch (\Stripe\Exception\CardException $e) {
			$status_msg = 'カードエラー';
			DB::rollback();
		} catch (\Stripe\Exception\RateLimitException $e) {
			$status_msg = 'リクエストエラー';
			DB::rollback();
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			$status_msg = '無効なパラメータエラー';
			DB::rollback();
		} catch (\Stripe\Exception\AuthenticationException $e) {
			$status_msg = 'リクエストの認証エラー';
			DB::rollback();
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			$status_msg = '決済APIの接続エラー';
			DB::rollback();
		} catch (\Stripe\Exception\ApiErrorException $e) {
			$status_msg = 'なんらかのエラー';
			DB::rollback();
		} catch (Exception $e) {
			$status_msg = 'なんらかのエラー';
			DB::rollback();
		}
		return view('order.charge', compact('status_msg', 'carts_arr', 'order'));
	}
}
