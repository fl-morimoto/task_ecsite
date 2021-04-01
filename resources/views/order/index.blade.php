@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">{{ userInfo()->name }}様の ご注文履歴:
		@if ($status == config('status.CANCELED'))
			<a style="margin:0px 0px 0px 30px" href="{{ route('order.index') }}">発送前／済み</a>
		@else
			<a style="margin:0px 0px 0px 30px" href="{{ route('order.index', ['is_cancel' => true]) }}">キャンセル済み</a>
		@endif
		<a style="margin:0px 0px 0px 30px" href="{{ route('item.index') }}">商品一覧へ</a></div>
		@if (0 < $orders->count())
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:25%;text-align:center">注文日</th>
							<th style="width:15%;text-align:center">金額</th>
							<th style="width:15%;text-align:center">状態</th>
							<th style="width:25%;text-align:center">詳細</th>
						</tr>
						@foreach ($orders as $order)
							<tr>
								<td>{{ date('Y年m月d日', strtotime($order->created_at)) }}</td>
								<td style="text-align:right">{{ number_format($order->amount) }}円</td>
								<td style="text-align:right">{{ $order->status }}</td>
								<td style="text-align:right"><a href="{{ route('order.detail', ['id' => encrypt($order->id)]) }}">注文の詳細</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			@if ($status == config('status.CANCELED'))
				<?php $msg = 'キャンセル済'; ?>
			@else
				<?php $msg = 'ご購入済'; ?>
			@endif
			<div class="panel-heading">{{ $msg }}の履歴はありません。
				<div class="panel-body"></div>
			</div>
		@endif
	</div>
</div>
</div>
</div>
</div>
@endsection
