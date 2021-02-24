<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Item;

class ItemController extends Controller
{
	public function index()
	{
		$items = Item::all();
		return view('item/index', compact('items'));
	}
	public function detail(Request $req)
	{
		$item = Item::find($req->id);
		session()->put('user_item_id', $item->id);
		return view('item/detail', compact('item'));
	}
	public function form(Request $req)
   	{
		if (!empty($req->id)) {
			//編集 -> edit
			$item = Item::find($req->id);
			session()->put('admin_item_id', $item->id);
			$name = $item->name;
			$content = $item->content;
			$price = $item->price;
			$quantity = $item->quantity;
			return view('item.form', compact('id', 'name', 'content', 'price', 'quantity'));
		} else {
			//追加 -> create
			$name = '';
			$content = '';
			$price = '';
			$quantity = '';
			return view('item.form', compact('name', 'content', 'price', 'quantity'));
		}
	}
	public function create(ItemRequest $req)
   	{
		(new Item)->fill($req->all())->save();
		session()->put('admin_item_id', null);
		return redirect(route('admin.item.index'))->with('true_message', '商品を追加しました。');
	}
	public function edit(ItemRequest $req)
	{
		(new Item)->edit($req);
		session()->put('admin_item_id', null);
		return redirect(route('admin.item.index'))->with('true_message', '商品を編集しました。');
	}
}
