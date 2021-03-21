<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CartRequest;
use Illuminate\Support\Facades\DB;
use App\Cart;
use App\Item;

class CartController extends Controller
{
	private $cart;
	private $item;

	public function __construct(Cart $cart, Item $item) {
		$this->cart = $cart;
		$this->item = $item;
	}
	public function item() {
		return $this->belongsTo('App\Item', 'item_id');
	}
	public function index() {
		$carts = $this->cart->where('user_id', userinfo()->id)->get();
		$total = $this->cart->total($carts);
		return view('cart/index', compact('carts', 'total'));
	}
	public function insert(CartRequest $req) {
		$item_id = decryptOrNull($req->id);
		if (empty($item_id)) {
			return redirect(route('item.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		$item = $this->item->findOrFail($item_id);
		if (empty($item)) {
			return redirect(route('cart.index'))->with('false_message', 'そのような商品はカートに入っていません。');
		}
		$cart_in_qty = $req->quantity;
		if ($cart_in_qty <= $item->quantity) {
			DB::transaction(function() use($item, $item_id, $cart_in_qty) {
				//recordがなければquantity=0でinsert
				$cart = $this->cart->firstOrCreate(['user_id' => userInfo()->id, 'item_id' => $item_id], ['quantity' => 0]);
				//quantityの増減
				$cart->increment('quantity', $cart_in_qty);
				$item->decrement('quantity', $cart_in_qty);
			});
			return redirect(route('cart.index'))->with('true_message', '商品をカートに入れました。');
		} else {
			return redirect(route('item.detail', ['id' => $req->id]))->with('false_message', '在庫が足りません。');
		}
	}
	public function delete(Request $req) {
		$cart_id = decryptOrNull($req->cart_id);
		if (empty($cart_id)) {
			return redirect(route('cart.index'))->with('false_message', 'アクセスIDが不正です。');
		}

		$cart = $this->cart->findOrFail($cart_id);
		$item = $this->item->findOrFail($cart->item_id);
		DB::transaction(function() use($cart, $item) {
			$this->cart->find($cart->id)->delete();
			$item->increment('quantity', $cart->quantity);
		});
		return redirect(route('cart.index'))->with('true_message', '商品をカートから削除しました。');
	}
}
