<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use SoftDeletes;
	protected $fillable = ['name', 'content', 'price', 'quantity', 'image_name'];
	protected $table = 'items';

}
