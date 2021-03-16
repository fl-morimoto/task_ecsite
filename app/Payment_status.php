<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_status extends Model
{
	use SoftDeletes;
	protected $fillable = ['status'];
}
