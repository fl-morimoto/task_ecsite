@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/reviewstar.css') }}">
@php
if (!empty($item->image_name)) {
	$image_name = $item->image_name;
} else {
	$image_name = 'noPhoto.png';
}
@endphp
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">商品詳細
	@if ((isLogin() && getUserType() == 'User') || getUserType() == 'Guest')
		<a style="margin:0px 0px 0px 30px" href="{{ route('item.index')}}">商品一覧へ</a>
	@elseif (isLogin() && getUserType() == 'Admin')
		<a style="margin:0px 0px 0px 30px" href="{{ route('admin.item.index') }}">商品一覧へ</a>
		<a style="margin:0px 0px 0px 25px" href="{{ route('admin.item.form', ['id' => encrypt($item->id)]) }}">商品編集へ</a>
	@endif
		<div class="panel-body">
			@if (!empty($item))
					<img src="{{ asset('storage/upload/' . $image_name)}}" class="img-responsive img-fluid" style="float:right;width:250px;margin:0px 0px 0px 20px">
					<table class="table-striped table-condensed" style="d-flex">
						<tbody style="font-size: 18px">
							<tr>
								<td style="width:20%">{{ '商品名: ' }}</td>
								<td style="width:50%">{{ $item->name }}</td>
							</tr>
							<tr>
								<td>{{ '商品説明: ' }}</td>
								<td>{{ $item->content }}</td>
							</tr>
							<tr>
								<td>{{ '価格: ' }}</td>
								<td>{{ $item->price . ' 円'}}</td>
							</tr>
							<tr>
								<td>{{ '在庫数: ' }}</td>
								<td>{{ $item->quantity . ' 個'}}</td>
							</tr>
						</tbody>
					</table>
				@if (isLogin() && getUserType() == 'User')
					<div class="d-flex flex-column" style="float:left;margin:50px 0px 0px 0px">
						@if (0 < $item->quantity)
							<form method="post" action="{{ route('cart.insert') }}">
								{{ csrf_field() }}
								<input type="hidden" name="id" value="{{ encrypt($item->id) }}">
								<div style="padding: 20px 0px 20px 8px">購入数:
									<input style="width:50px;" type="text" name="quantity" value="">
									<button type="submit">カートに入れる</button>
								</div>
							</form>
						@else
							<div style="font-size: 14px">在庫無し</div>
						@endif
					</div>
				@elseif (getUserType() == 'Guest')
					<div style="font-weight:bold;margin:20px 0px 0px 5px">ログインしてください</div>
				@endif
				<p></p>
			@else
				<table>
					<caption>その商品は存在しません。</caption>
				</table>
			@endif
		</div>
	</div>
</div>
</div>
</div>
</div>
<!-- レビュ -->
@if (0 < $reviews->count())
	<div class="container">
	<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8" style="padding:15px;margin:-15px 0px -10px 0px">
	<div class="panel panel-default">
		<div style="font-size:16px" class="panel-heading">商品レビュー（最高評価：５）
			<label>&nbsp;&nbsp;&nbsp;平均評価 &nbsp;&nbsp;{{ $avg_point }}</label>
			@if (getUserType() == 'User')
			<a style="float:right" href="{{ route('review.form', ['item_id' => encrypt($item->id)]) }}">この商品のレビューをする</a>
			@endif
			<div class="panel-body">
				<table class="table-striped table-condensed" style="font-size:16px">
					<tr>
						<th style="width: 20%;text-align: center">日付</th>
						<th style="width: 12%;text-align: center">評価値</th>
						<th style="width: 10%;text-align: center">名前</th>
						<th style="width: 58%;text-align: center">コメント</th>
					</tr>
					@foreach ($reviews as $review)
					<tr>
						<td style="text-align:center">{{ date('Y-m-d', strtotime($review->created_at)) }}</td>
						<td style="text-align:center">{{ $review->review_point }}</td>
						<td style="text-align:right">{{ $review->name }}&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td style="text-align:left">{{ $review->comment }}</td>
					</tr>
					@endforeach
				</table>
				<div style="text-align:center">{{ $reviews->appends(['id' => encrypt($item->id)])->links() }}</div>
			</div>
		</div>
	</div>
	</div>
	<div class="col-md-2"></div>
	</div>
	</div>
@else
	<div class="container">
	<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8" style="padding:15px;margin:-15px 0px -10px 0px">
	<div class="panel panel-default">
		<div style="font-size:16px" class="panel-body">商品レビューはありません。&nbsp;&nbsp;&nbsp;
		@if (getUserType() == 'User')
		<a style="float:right" href="{{ route('review.form', ['item_id' => encrypt($item->id)]) }}">この商品のレビューをする</a>
		@endif
		</div>
	</div>
	</div>
	<div class="col-md-2"></div>
	</div>
	</div>

@endif
@endsection

