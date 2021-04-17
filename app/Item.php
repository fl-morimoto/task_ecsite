<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class Item extends Model
{
	use SoftDeletes;
	protected $fillable = ['name', 'content', 'price', 'quantity', 'image_name'];
	protected $table = 'items';

	public function validateCsvRecoerd($item) {
		return \Validator::make($item, [
			'id' => 'nullable|integer|digits_between:1,10|min:1',
			'name' => [
				'required',
				'string',
				'max:191',
				Rule::unique('items')->ignore($item['id']),
			],
			'content' => 'required|string|max:191',
			'price' => 'required|integer|digits_between:1,10|min:1',
			'quantity' => 'required|integer|digits_between:1,10|min:0',
        ]);
	}
	public function validateCsvFile(Request $req) {
		return \Validator::make($req->all(), [
			'csvfile' => 'required|file|mimetypes:text/plain|mimes:csv,txt',
		]);
	}
	public function makeItemArray($line) {
		return [
			'id' => (function () use ($line) {
				if ($line[0] == 'null' || empty($line[0])) {
					return null;
				} else {
					return $line[0];
				}
			})(),
			'name' => mb_convert_encoding($line[1], 'UTF-8', 'auto'),
			'content' => mb_convert_encoding($line[2], 'UTF-8', 'auto'),
			'price' => $line[3],
			'quantity' => $line[4],
		];
	}
	public function itemCreateOrUpdate($items) {
		foreach ($items as $item) {
			if (!empty($item['id'])) {
				$exists_item = $this
					->where('id', $item['id'])
					->where('name', $item['name'])
					->first();
			}
			if (!empty($exists_item)) {
				//update
				$exists_item->fill([
					'content' => $item['content'],
					'price' => $item['price'],
					'quantity' => $item['quantity'],
				])->save();
			} else {
				//create
				(new Item)->fill([
					'name' => $item['name'],
					'content' => $item['content'],
					'price' => $item['price'],
					'quantity' => $item['quantity'],
				])->save();
			}
		}
	}
}
