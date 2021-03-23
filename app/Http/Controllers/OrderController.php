<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\BalanceTransaction;
use App\Cart;
use App\Order;
use App\OrderDetail;
use App\Payment;
use App\Item;

class OrderController extends Controller
{
	private $cart;
	private $item;
	private $order;
	private $order_detail;
	private $address;
	private $payment;

	public function __construct(
		Payment $payment,
		Order $order,
		Item $item,
		OrderDetail $order_detail,
		Cart $cart)
	{
		$this->cart = $cart;
		$this->item = $item;
		$this->order = $order;
		$this->order_detail = $order_detail;
		$this->payment = $payment;
	}
	public function index(Request $req) {
		//$status = config('status.DELIVERED');
		//if (!empty($req->status)) {
		//	$status = $req->status;
		//}
		$status = '';
		$ope = '<>';
		if ($req->status == config('status.CANCELED')) {
			$ope = '=';
			$status = config('status.CANCELED');
		}
		$orders = DB::table('orders')
			->join('payments', 'orders.id', '=', 'payments.order_id')
			->join('payment_statuses', 'payments.payment_status_id', '=', 'payment_statuses.id')
			->select('orders.*', 'payments.stripe_code', 'payment_statuses.status', 'payments.payment_status_id')
			->where('user_id', '=', userInfo()->id)
			->where('payments.payment_status_id', $ope, config('status.CANCELED'))
			//->where('payments.payment_status_id', '=', $status)
			->get();
		return view('order.index', compact('orders', 'status'));
	}
	public function detail(Request $req) {
		$order_id = decryptOrNull($req->id);
		if (empty($order_id)) {
			return redirect(route('cart.index'))->with('false_message', 'その操作は無効です。');
		}
		$details = DB::table('order_details')
			->where('order_details.order_id', '=', $order_id)
			->get();
		$order = DB::table('orders')
			->join('payments', 'orders.id', '=', 'payments.order_id')
			->join('payment_statuses', 'payments.payment_status_id', '=', 'payment_statuses.id')
			->where('orders.id', '=', $order_id)
			->select('orders.*', 'payments.stripe_code', 'payment_statuses.status', 'payments.payment_status_id')
			->first();
		$total = $this->order_detail->total($details);
		return view('order.detail', compact('order', 'details', 'total'));
	}
	public function confirm(Request $req) {
		if (!empty($req->carts)) {
			$user = userinfo();
			$carts = $this->cart->where('user_id', $user->id)->get();
			$total = $this->cart->total($carts);
		}
		$address = $req->address;
		session(['address' => $address]);
		return view('order.confirm', compact('address', 'carts', 'total'), ['order' => $this->order]);
	}
	public function cancel(Request $req) {
		$is_canceld = $this->payment->where('stripe_code', $req->stripe_code)->first()->payment_status_id == config('status.CANCELED');
		if ($is_canceld) {
			return redirect(route('order.index'))->with('false_message', 'その注文は既にキャンセル済みです。');
		}
		DB::beginTransaction();
		try {
			$stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
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
		if (empty(session('address'))) {
			return redirect(route('cart.index'))->with('false_message', 'その操作は無効です。');
		}
		$user = userinfo();
		$carts_arr = [];
		$carts = $this->cart->where('user_id', $user->id)->get();
		DB::beginTransaction();
		//オーダーのDB処理
		$this->order->fill([
			'amount' => $this->cart->total($carts),
				'user_id' => $user->id,
				'user_name' => $user->name,
				'user_address' => session('address'),
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
			Stripe::setApiKey(env('STRIPE_SECRET'));
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
