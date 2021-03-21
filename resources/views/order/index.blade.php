@endphp
@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	@if (!empty($orders))
		<div class="panel-heading">{{ $order->user_name }}様のご注文履歴</div>
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:15%;text-align:center">注文日</th>
							<th style="width:15%;text-align:center">金額</th>
							<th style="width:15%;text-align:center">お届先住所</th>
						</tr>
						@foreach ($orders as $order)
							<tr>
								@php
								//aリンクで詳細画面 -> キャンセルボタン
								@endphp
								<td style="text-align:right">{{ $order['created_at'] }}</td>
								<td style="text-align:right">{{ $order['amount'] }}</td>
								<td style="text-align:right">{{ $order['user_address'] }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	@else
		<div class="panel-heading">ご注文履歴はありません。</div>
			<div class="panel-body"></div>
		</div>
	@endif
</div>
</div>
</div>
</div>
@endsection
