<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
	use SoftDeletes;
	protected $fillable = ['order_id', 'item_id', 'item_name', 'item_price'];
	protected $table = ['carts', 'items'];
	protected $dates = ['deleted_at'];
}
