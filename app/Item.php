<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $fillable = ['name', 'content', 'price', 'quantity'];
	protected $table = 'items';

	public function create($req) {
		$name = $req->input('name');
		$content = $req->input('content');
		$price = $req->input('price');
		$quantity = $req->input('quantity');
		i//dd(compact('name', 'content', 'price', 'quantity'));
		$this->create(compact('name', 'content', 'price', 'quantity'));
	}
	public function updateDb($id, $request) {
		$item = $this->findOrFail($id);
		$item->fill(['name' => $request->input('name')]);
		$item->fill(['content' => $request->input('content')]);
		$item->fill(['quantity' => $request->input('quantity')]);
		$item->save();
	}
}
