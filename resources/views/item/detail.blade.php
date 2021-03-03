@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">商品詳細</div>

	<div class="panel-body">
		<div class="row">
			<div style="background-color:#e3f0fb; col-md-4 col-sm-4">{{ '商品名: ' }}</div>
			<div style="background-color:#f5f5f5; col-sm-1">{{ $item->name }}</div>

		</div>






	</div>
</div>
</div>
</div>
</div>

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
@if (!empty($item))
	<table>
		<caption>商品詳細</caption>
		<tr>
			<td style="background-color:#e3f0fb; min-width: 200px">{{ '商品名: ' }}</td>
			<td style="background-color:#f5f5f5; min-width: 300px">{{ $item->name }}</td>
		</tr>
		<tr>
			<td style="background-color:#e3f0fb">{{ '商品説明: ' }}</td>
			<td style="background-color:#f5f5f5">{{ $item->content }}</td>
		</tr>
		<tr>
			<td style="background-color:#e3f0fb">{{ '価格: ' }}</td>
			<td style="background-color:#f5f5f5">{{ $item->price . ' 円'}}</td>
		</tr>
		<tr>
			<td style="background-color:#e3f0fb">{{ '在庫数: ' }}</td>
			<td style="background-color:#f5f5f5">{{ $item->quantity . ' 個'}}</td>
		</tr>
	</table>
	@if (isLogin() && getUserType() == 'User')
		@if (0 < $item->quantity)
			<tr>
				<form method="post" action="{{ route('cart.insert') }}">
					{{ csrf_field() }}
					<input type="hidden" name="id" value="{{ encrypt($item->id) }}">
					<td style="padding: 20px 0px 20px 8px">購入数:
						<input style="width:50px;" type="text" name="quantity" value="">
					</td>
					<td><button type="submit">カートに入れる</button></td>
				</form>
			</tr>
		@else
			<tr>
				<td style="font-size: 14px">在庫無し</td>
			</tr>
		@endif
	@elseif (getUserType() == 'Guest')
		<p>ログインしてください</p>
	@endif
	<p></p>
@else
	<table>
	<caption>その商品は存在しません。</caption>
	</table>
@endif
@endsection

