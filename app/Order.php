<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	use SoftDeletes;
	protected $fillable = ['amount', 'fee', 'user_id', 'user_name', 'user_address'];
	protected $table = 'users';
	protected $dates = ['deleted_at'];
    //
}
