<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
	use SoftDeletes;
	protected $fillable = ['order_id', 'item_id', 'item_name', 'item_price'];
	protected $table = 'order_details';
	protected $dates = ['deleted_at'];
}
