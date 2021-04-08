<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use SoftDeletes;

	protected $fillable = ['user_id', 'order_id', 'type_id', 'email', 'name', 'title', 'content'];
	protected $tabel = 'Questions';
}
