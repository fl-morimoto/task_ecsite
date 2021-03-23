<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
	use SoftDeletes;
	protected $fillable = ['order_id', 'item_id', 'item_name', 'item_price', 'item_quantity'];
	protected $table = 'order_details';
	protected $dates = ['deleted_at'];

	public function subTotal() {
		$result = $this->item_price * $this->item_quantity;
		return $result;
	}
	public function total($details) {
		$total = 0;
		foreach ($details as $detail) {
			$total += $detail->item_price * $detail->item_quantity;
		}
		return $total;
	}
}
