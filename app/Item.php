<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $fillable = ['name', 'content', 'price', 'quantity'];
	protected $table = 'items';

	public function allData() {
		$allData = $this->all();
		dd($allData);
	}
}
