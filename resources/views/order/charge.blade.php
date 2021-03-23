@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	@if (empty($status_msg))
		<div class="panel-heading">{{ $order->user_name }}様、ありがとうございます！
			<a style="margin:0px 0px 0px 30px" href="{{ route('item.index') }}">商品一覧へ</a></div>
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:15%;text-align:center">ご購入商品名</th>
						</tr>
						@foreach ($carts_arr as $cart)
							<tr>
								<td style="text-align:right">{{ $cart['item_name'] }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="container">
		<div class="row">
		<div class="col-md-8" style="padding:15px;margin:-15px 0px -10px -15px">
		<div class="panel panel-default">
			<div class="panel-heading">選択されたお届先住所</div>
				<div class="panel-body">
					<p style="font-weight:bold;margin:0px 0px 0px 45px">〒{{ $order->user_address }}</p>
				</div>
			</div>
		</div>
		</div>
		<div class="col-md-2"></div>
		</div>
		</div>
	@else
		<div class="panel-heading">ご注文の失敗
			<a style="margin:0px 0px 0px 30px" href="{{ route('item.index') }}">商品一覧へ</a></div>
			<div class="panel-body">
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 20px">
					<p>（{{ $status_msg }}）のためお取引を完了することができませんでした。</p>
				</div>
			</div>
		</div>
	@endif
</div>
</div>
</div>
</div>
@endsection
