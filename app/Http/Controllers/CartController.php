<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use App\Cart;
use App\Item;

class CartController extends Controller
{
	private $cart;
	private $item;

	public function __construct() {
		$this->cart = new Cart;
		$this->item = new Item;
	}
	public function item() {
		return $this->belongsTo('App\Item', 'item_id');
	}
    //
	public function index() {
		$carts = Cart::where('user_id', userinfo()->id)->get();
		return view('cart/index', compact('carts'));
	}
	public function add(CartRequest $req) {
		$item_id = decrypt($req->id);
		$item = Item::findOrFail($item_id);
		$stock_qty = $item->quantity;
		$add_qty = $req->quantity;
		if ($add_qty <= $stock_qty) {
			//レコードがなければ個数0でinsert
			$cart = Cart::firstOrCreate(['user_id' => userInfo()->id, 'item_id' => $item_id], ['quantity' => 0]);
			//カート個数の増減
			$cart->increment('quantity', $add_qty);
			$item->decrement('quantity', $add_qty);
		}
	}
	public function add_app() {
		$item_id = session('id');
		if (isset($item_id)) {
			if ((new Cart)->addDb($item_id, 1)) {
				set_message('商品をカートに入れました');
			} else {
				set_message('在庫が足りません', false);
			}
		} else {
			set_message('リロードはできません', false);
		}
		session()->forget('id');
		return $this->index();
	}
	public function addDb_app(int $item_id, $add_qty) {
		//$item = (new Item)->findOrFail($item_id);
		$qty = $item->quantity;
		if ($qty <= 0 || $qty < $add_qty) {
			return false;
		}
		$cart = $this->firstOrCreate(['user_id' => Auth::id(), 'item_id' => $item_id], ['quantity' => 0]);
		$cart->increment('quantity', $add_qty);
		$item->decrement('quantity', $add_qty);
		session()->forget('id');
		return true;
	}
}
