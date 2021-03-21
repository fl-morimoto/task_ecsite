<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentStatuses extends Model
{
	use SoftDeletes;
	protected $fillable = ['status'];
}
