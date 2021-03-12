<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	protected $fillable = ['name', 'content', 'price', 'quantity'];
	protected $table = 'items';

	public function execUpdate($req) {
		$item = $this->findOrFail(decryptOrNull($req->id));
		if (empty($item)) {
			return redirect(route('admin.item.index'))->with('false_message', 'アクセスIDが不正です。');
		}
		$item->fill(['name' => $req->input('name')]);
		$item->fill(['content' => $req->input('content')]);
		$item->fill(['price' => $req->input('price')]);
		$item->fill(['quantity' => $req->input('quantity')]);
		$item->save();
	}
}
