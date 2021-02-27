<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Item;

class ItemController extends Controller
{
	private $item;

	public function __construct(Item $item) {
		$this->item = new Item;
	}
	public function index()
	{
		$items = Item::all();
		return view('item/index', compact('items'));
	}
	public function detail(Request $req)
	{
		$item = Item::find(decrypt($req->id));
		return view('item/detail', compact('item'));
	}
	public function form(Request $req)
   	{
		if (!empty($req->id)) {
			$item = Item::find(decrypt($req->id));
		} else {
			$item = new Item;
		}
		return view('item.form', compact('item'));
	}
	public function create(ItemRequest $req)
   	{
		$this->item->fill($req->all())->save();
		return redirect(route('admin.item.index'))->with('true_message', '商品を追加しました。');
	}
	public function edit(ItemRequest $req)
	{
		$this->item->edit($req);
		return redirect(route('admin.item.index'))->with('true_message', '商品を編集しました。');
	}
}
