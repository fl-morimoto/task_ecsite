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
		return view('item/detail', compact('item'));
	}
	public function form(Request $request) {
		$name = $request->input('name');
		$content = $request->input('content');
		$price = $request->input('price');
		$quantity = $request->input('quantity');
		return view('item.form', compact('name', 'content', 'price', 'quantity'));
	}
	public function create(ItemRequest $req)
   	{
		(new Item)->create($req);
		return redirect(route('admin.item.index'))->with('true_message', '商品を追加しました。');
	}
	public function edit(Request $req)
	{
		$id = $req->input('id');
		dd('getパラは -> ' . $id);
	}
}
