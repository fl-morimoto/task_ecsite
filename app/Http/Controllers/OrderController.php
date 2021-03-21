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
use App\Payment;

class OrderController extends Controller
{
	private $cart;
	private $order;
	private $address;
	private $payment;

	public function __construct(Payment $payment,
								Order $order,
								Cart $cart)
   	{
		$this->cart = $cart;
		$this->order = $order;
		$this->payment = $payment;
	}
	public function index() {
		//インナージョインでorder & payments
		$payments = $this->payments->where(['user_id' => userInfo()->id, 'O'])->get();
		dd($orders);
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
			$arr = ['order_id' => $order->id,
					'item_id' => $cart->item->id,
					'item_name' => $cart->item->name,
					'item_price' => $cart->item->price,
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
				'payment_status_id' => config('status.SUCCESS'),
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
