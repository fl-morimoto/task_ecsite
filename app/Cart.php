<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\facades\Auth;
use App\Item;

class Cart extends Model
{
	use SoftDeletes;
	protected $fillable = ['user_id', 'item_id', 'quantity'];
	protected $table = 'carts';

	public function item() {
		return $this->belongsTo('App\Item', 'item_id');
	}
    //
	public function subTotal() {
		$result = $this->item->price * $this->quantity;
		return $result;
	}
	public function addDb(int $item_id, $add_qty) {
		$item = (new Item)->findOrFail($item_id);
		$qty = $item->quantity;
		if ($qty <= 0 || $qty < $add_qty) {
			return false;
		}
		$cart = $this->firstOrCreate(['user_id' => Auth::id(), 'item_id' => $item_id],
									['quantity' => 0]);
		$cart->increment('quantity', $add_qty);
		$item->decrement('quantity', $add_qty);
		session()->forget('id');
		return true;
	}
}
