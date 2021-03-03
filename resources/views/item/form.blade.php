@extends('layouts.app')
@section('content')
<?php
//前処理
$is_edit = false;
?>
@if (!$errors->any())
	<?php
	if (!empty($item->id)) {
		//編集
		$is_edit = true;
		$id = $item->id;
	}
	//追加
	$name = $item->name;
	$content = $item->content;
	$price = $item->price;
	$quantity = $item->quantity;
	?>
@else
	<div class="alert alert-danger">
		@foreach ($errors->all() as $error)
			<ul>
				<li>{{ $error }}</li>
			</ul>
		@endforeach
	</div>
	<?php
	//バリデーションエラー時の編集
	$id = old('id');
	$name = old('name');
	$content = old('content');
	$price = old('price');
	$quantity = old('quantity');
	$is_edit = true;
	?>
@endif
@if ($is_edit)
	<?php $address = 'admin.item.update'; ?>
	<h1>{{ '商品編集' }}</h1>
@else
	<?php $address = 'admin.item.insert'; ?>
	<h1>{{ '新規登録' }}</h1>
@endisset
<body>
	<form method="post" action="{{ route($address) }}">
		{{ csrf_field() }}
		@if ($is_edit)
		<p><input type="hidden" name="id" value="{{ encrypt($id) }}"></p>
		@endif
		<p><label>商品名:</label> <input type="text" name="name" value="{{ $name }}"></p>
		<p><label>説明:</label> <textarea name="content">{{ $content }}</textarea></p>
		<p><label>値段:</label> <input type="text" name="price" value="{{ $price }}"></p>
		<p><label>在庫:</label> <input type="text" name="quantity" value="{{ $quantity }}"></p>
		<p><button type="submit">登録</button></p>
	</form>
	@if (isLogin() && getUserType() == 'Admin')
		<p><a href="{{ route('admin.item.index') }}">商品一覧へ戻る</a></p>
	@else
		<p><a href="{{ route('item.index') }}">商品一覧へ戻る</a></p>
	@endif
</body>
@endsection
