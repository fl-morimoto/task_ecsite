<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Order;

class HomeController extends Controller
{
	private $order;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Order $order)
	{
		$this->middleware('auth');
		$this->order = $order;
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$user = userInfo();
		$daily_sales = $this->order->dailySales(Carbon::today());
		return view('home', compact('user', 'daily_sales'));
	}
}
