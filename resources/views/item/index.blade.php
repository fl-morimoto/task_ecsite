@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">商品一覧
		@if (isLogin() && getUserType() == 'Admin')
			<a style="margin:0px 0px 0px 30px" href="{{ route('admin.item.form') }}">商品追加ページへ</a>
			<button type="button" id="csv-item-button" style="float:right" onclick="return check()">一覧のCSVダウンロード</button>
		@elseif (isLogin() && getUserType() == 'User')
			<a style="margin:0px 0px 0px 30px" href="{{ route('cart.index') }}">カート一覧ページへ</a>
		@endif
	</div>
	<div class="panel-body">
		<table class="table-striped table-condensed" style="font-size:16px">
			<tbody>
				<tr style="background-color:#e3f0fb">
				<th style="width: 100px;text-align: center">商品ID</th>
				<th style="width: 200px;text-align: center">商品名</th>
				<th style="width: 150px;text-align: center">価格</th>
				<th style="width: 150px;text-align: center">在庫数</th>
				<th style="width: 150px;text-align: center">在庫</th>
				@if (getUserType() == 'Admin')
				<th style="width: 150px;text-align: center">編集</th>
				@endif
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
						<td style="text-align:center">{{ number_format($item->id) }}</td>
						@if (!empty($item->image_name))
						<td style="font-weight:bold; text-align:left; text-indent: 1em">
						@else
						<td style="font-weight:normal; text-align:left; text-indent: 1em">
						@endif
						@if (getUserType() == 'Admin')
							<a href="{{ route('admin.item.detail', ['id' => encrypt($item->id)]) }}">{{ $item->name }}</a>
						@else
							<a href="{{ route('item.detail', ['id' => encrypt($item->id)]) }}">{{ $item->name }}</a>
						@endif
						</td>
						<td style="text-align:right">{{ number_format($item->price) }}</td>
						<td style="text-align:right">{{ $item->quantity }}</td>
						<td style="text-align:right">{{ $stock_str }}</td>
						@if (getUserType() == 'Admin')
						<td style="font-weight:normal; text-align:right; text-indent: 1em">
							<button type="button" onclick="location.href='{{ route('admin.item.form', ['id' => encrypt($item->id)]) }}'">編集</a></button>
						</td>
						@endif
					</tr>
				@endforeach
			</tbody>
		</table>
		<div style="text-align:center">{{ $items->links() }}</div>
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
