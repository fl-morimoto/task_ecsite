@extends('layouts.app')

@section('content')
<?php
if (!empty($item->image_name)) {
	$image_name = $item->image_name;
} else {
	$image_name = 'noPhoto.png';
}

?>
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<div style="font-size: 16px;margin: 0px 0px 0px 20px">
	@if ((isLogin() && getUserType() == 'User') || getUserType() == 'Guest')
		<p><a href="{{ route('item.index')}}">商品一覧へ</a></p>
	@elseif (isLogin() && getUserType() == 'Admin')
		<p><a href="{{ route('admin.item.index') }}">商品一覧へ</a></p>
		<p></p>
		<p><a href="{{ route('admin.item.form', ['id' => encrypt($item->id)]) }}">商品編集へ</a></p>
	@endif
</div>
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">商品詳細</div>
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
					<div>ログインしてください</div>
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
@endsection

