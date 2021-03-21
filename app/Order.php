<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use SoftDeletes;
	protected $fillable = ['amount', 'user_id', 'user_name', 'user_address'];
	protected $dates = ['deleted_at'];
    //
}
