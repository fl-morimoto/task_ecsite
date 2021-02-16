<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ItemController extends Controller
{
	public function index()
	{
		$items = (new Item)->allGet();
		dd($items);
		$var = 'hello world !!';
		return view('index', compact('var'));
	}
}
