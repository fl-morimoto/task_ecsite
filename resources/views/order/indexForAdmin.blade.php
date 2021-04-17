@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
@include('order.search', ['serach' => $search])
<div class="col-md-8">
<div class="panel panel-default">
	<div class="panel-heading">ご注文履歴:
		<a style="margin:0px 0px 0px 30px" href="{{ route('admin.item.index') }}">管理者商品一覧へ</a>
		<form style="float:right" id="csv-order-button" action="{{ route('admin.order.download') }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="username" value="{{ !empty($search['username']) ? $search['username'] : "" }}">
			<input type="hidden" id="datepicker" name="date_from" value="{{ !empty($search['date_from']) ? $search['date_from'] : null }}">
			<input type="hidden" id="datepicker" name="date_to" value="{{ !empty($search['date_to']) ? $search['date_to'] : null }}">
			<input type="hidden" name="amount_from" value="{{ !empty($search['amount_from']) ? $search['amount_from'] : null }}">
			<input type="hidden" name="amount_to" value="{{ !empty($search['amount_to']) ? $search['amount_to'] : null }}">
			<input type="hidden" name="status_id" value="{{ !empty($search['status_id']) ? $search['status_id'] : null }}">
			<input style="float:right" type="submit" onclick="return check()" value="一覧のCSVダウンロード">
		</form>
		@if (0 < $orders->count())
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tbody>
						<tr style="background-color:#f5f5f5">
							<th style="width:25%;text-align:center">@sortablelink('created_at', '注文日')</th>
							<th style="width:15%;text-align:center">@sortablelink('amount', '金額')</th>
							<th style="width:20%;text-align:center">@sortablelink('user_name', '購入者名')</th>
							<th style="width:15%;text-align:center">状態</th>
							<th style="width:15%;text-align:center">詳細</th>
						</tr>
						@foreach ($orders as $order)
							<tr>
								<td>{{ date('Y年m月d日', strtotime($order->created_at)) }}</td>
								<td style="text-align:right">{{ number_format($order->amount) }}円</td>
								<td style="text-align:right">{{ $order->user_name }}</td>
								<td style="text-align:right">{{ $order->status }}</td>
								<td style="text-align:right"><a href="{{ route('admin.order.detail', ['id' => encrypt($order->id)]) }}">注文の詳細</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div style="text-align:center">{{ $orders->appends($search)->links() }}</div>
			</div>
		@else
			@if (getUserType() == 'User')
				@if ($status == config('status.CANCELED'))
					<?php $msg = 'キャンセル済'; ?>
				@else
					<?php $msg = 'ご購入済'; ?>
				@endif
				<div class="panel-heading">{{ $msg }}の履歴はありません。
					<div class="panel-body"></div>
				</div>
			@endif
			@if (getUserType() == 'Admin')
				<div class="panel-heading">購入履歴は見つかりません。
					<div class="panel-body"></div>
				</div>
			@endif
		@endif
	</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
	function check() {
		var checked = confirm("ダウンロードしますか？")
		if (checked == true) {
			return location.href='{{ route('admin.item.download') }}';
		} else {
			return false;
		}
	}
</script>
@endsection
