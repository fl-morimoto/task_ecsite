<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
	public function index()
	{
		$items = Item::all();
		return view('item_index', compact('items'));
	}
	public function detail(Request $req)
	{
		$item = Item::find($req->id);
		return view('item_detail', compact('item'));
	}
	public function create(Request $req)
	{
		dd('createメソッドへようこそ');

	}
	public function edit(Request $req)
	{
		dd('editメソッドへようこそ');
	}
}
