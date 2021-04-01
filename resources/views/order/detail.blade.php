@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="panel panel-default">
	@if (!empty($details))
		<div class="panel-heading">注文日: {{ date('Y年m月d日', strtotime($order->created_at)) }}
			@if ($order->payment_status_id == config('status.CANCELED'))
			<span style="font-weight:bold;margin:0px 0px 0px 30px">この注文はキャンセル済みです。</span>
			</div>
			@endif
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:10%;text-align:center">注文ID</th>
							<th style="width:30%;text-align:center">商品名</th>
							<th style="width:15%;text-align:center">個数</th>
							<th style="width:20%;text-align:center">単価</th>
							<th style="width:20%;text-align:center">金額</th>
						</tr>
						@foreach ($details as $detail)
							<tr>
								<td style="text-align:right">{{ $detail->order_id }}</td>
								<td style="text-align:right">{{ $detail->item_name }}</td>
								<td style="text-align:right">{{ $detail->item_quantity }}</td>
								<td style="text-align:right">{{ $detail->item_price }}</td>
								<td style="text-align:right">{{ number_format($detail->item_quantity * $detail->item_price) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div class="panel-footer" style="font-size: 16px;margin: 0px 0px 20px 0px">
					<label style="text-align:center;margin:0px 0px 0px 30px">総額</label>
					<label style="margin:0px 0px 0px 10px">￥ {{ number_format($total) }} 円（税込） </label>
				</div>
			</div>
		</div>
	@else
		<div class="panel-heading">まだご注文はありません。</div>
			<div class="panel-body"></div>
		</div>
	@endif
</div>
</div>
</div>
</div>
<div class="container">
<div class="row">
<div class="col-md-1"></div>
<div class="col-md-10" style="padding:15px;margin:-15px 0px -10px 0px">
<div class="panel panel-default">
	@if (getUserType() == 'Admin')
	<div class="panel-heading">お届先住所・氏名</div>
	@elseif (getUserType() == 'User')
	<div class="panel-heading">お届先住所</div>
	@endif
		<div class="panel-body">
			<p style="font-size:17px;margin:0px 0px 0px 45px">〒{{ $order->fullAddress() }}</p>
			@if (getUserType() == 'Admin')
			<p style="font-size:17px;margin:0px 0px 0px 45px">{{ $order->user_name }}様</p>
			@endif
		</div>
	</div>
</div>
</div>
<div class="col-md-1"></div>
</div>
</div>
@if (getUserType() == 'Admin')
<div class="container">
<div class="row">
<div class="col-md-1"></div>
<div class="col-md-10" style="padding:15px;margin:-15px 0px -10px 0px">
<div class="panel panel-default">
	<div class="panel-heading">
		<form action="{{ route('admin.order.changeStatus') }}" method="POST">現在のステータス：&nbsp;
			{{ csrf_field() }}
			<select style="font-size:16px" name="status">
			@foreach ($statuses as $index => $status)
			<?php $real_index = $index + 1; ?>
<option value="{{ encrypt($real_index) }}" @if ($order->payment_status_id == $real_index) selected @endif>{{ $status->status }}</option>
			@endforeach
			</select>
			<input type="hidden" name="order_id" value="{{ $order->id }}">
			<input type="hidden" name="stripe_code" value="{{ $order->stripe_code }}">
			<input type="submit" value="ステータスを更新する">
		</form>
	</div>
</div>
</div>
<div class="col-md-1"></div>
</div>
</div>
@elseif (getUserType() == 'User' && $order->payment_status_id < config('status.SHIPPED'))
	<div style="margin-bottom:50px">
		<form action="{{ route('order.cancel') }}" method="POST" class="text-center mt-5">
			{{ csrf_field() }}
			<input type="hidden" name="stripe_code" value="{{ $order->stripe_code }}">
			<input type="submit" value="購入のキャンセル">
		</form>
	</div>
@endif
@endsection
