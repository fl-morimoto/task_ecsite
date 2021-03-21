<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
	use SoftDeletes;
	protected $fillable = ['name', 'content', 'price', 'quantity', 'image_name'];
	protected $table = 'items';

}
