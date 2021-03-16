<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	use SoftDeletes;
	protected $fillable = ['order_id', 'payment_status_id', 'stripe_code', 'stripe_fee'];
	protected $table = ['orders', 'payment_statuses'];
	protected $dates = ['deleted_at'];
}
