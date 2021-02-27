<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $fillable = ['name', 'content', 'price', 'quantity'];
	protected $table = 'items';

	public function edit($req) {
		$item = $this->findOrFail(decrypt($req->id));
		$item->fill(['name' => $req->input('name')]);
		$item->fill(['content' => $req->input('content')]);
		$item->fill(['price' => $req->input('price')]);
		$item->fill(['quantity' => $req->input('quantity')]);
		$item->save();
	}
}
