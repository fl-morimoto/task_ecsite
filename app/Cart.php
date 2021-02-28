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
	protected $dates = ['deleted_at'];

	public function item() {
		return $this->belongsTo('App\Item', 'item_id');
	}
    //
	public function subTotal() {
		$result = $this->item->price * $this->quantity;
		return $result;
	}
	public function total() {
		$cart = $this->cart->all();
		dd(cart);
	}
}
