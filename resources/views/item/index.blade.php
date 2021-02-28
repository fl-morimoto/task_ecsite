@extends('layouts.app')
@section('content')
	<div style="font-size: 16px;margin: 0px 0px 0px 20px">
		@if (isLogin() && getUserType() == 'Admin')
			<a href="{{ route('admin.item.form') }}">商品追加ページへ</a>
		@elseif (isLogin() && getUserType() == 'User')
			<a href="{{ route('cart.index') }}">カート一覧ページへ</a>
		@endif
	</div>
	<table>
		<caption>商品一覧</caption>
		<tr style="background-color:#e3f0fb">
		<th style="width: 200px">商品名</th>
		<th style="width: 150px">値段</th>
		<th style="width: 150px">在庫数</th>
		<th style="width: 150px">在庫</th>
		@foreach ($items as $item)
			@if (0 < $item->quantity)
				<?php
				$bgcolor = '#f5f5f5';
				$stock_str = "あり";
				?>
			@else
				<?php
				$bgcolor = '#dddddd';
				$stock_str = "なし";
				?>
			@endif
			<tr style="background-color:{{ $bgcolor }}">
				@if (getUserType() == 'Admin')
					<td style="font-weight:bold; text-align:left; text-indent: 1em">
						<a href="{{ route('admin.item.detail', ['id' => encrypt($item->id)]) }}">{{ $item->name }}</a>
					</td>
				@else
					<td style="font-weight:bold; text-align:left; text-indent: 1em">
						<a href="{{ route('item.detail', ['id' => encrypt($item->id)]) }}">{{ $item->name }}</a>
					</td>
				@endif
				<td style="text-align:right">{{ $item->price }}</td>
				<td style="text-align:right">{{ $item->quantity }}</td>
				<td style="text-align:right">{{ $stock_str }}</td>
			</tr>
		@endforeach
	</table>
@endsection
