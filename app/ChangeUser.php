<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeUser extends Model
{
	use SoftDeletes;
	protected $fillable =
		[
			'user_id',
			'new_name',
			'new_email',
			'new_password',
			'update_token',
		];
	protected $table = 'change_users';
	protected $dates = ['deleted_at'];
}
