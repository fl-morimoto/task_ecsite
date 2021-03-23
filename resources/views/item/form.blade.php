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
	//追加、編集共通
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
	$image = old('image');
	$is_edit = true;
	?>
@endif
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
		<div class="panel-heading">
			@if ($is_edit)
				<?php $address = 'admin.item.update'; ?>
				商品編集
			@else
				<?php $address = 'admin.item.insert'; ?>
				新規登録
			@endisset
			@if (isLogin() && getUserType() == 'Admin')
				<a style="margin:0px 0px 0px 30px" href="{{ route('admin.item.index') }}">商品一覧へ戻る</a>
			@else
				<a style="margin:0px 0px 0px 30px" href="{{ route('item.index') }}">商品一覧へ戻る</a>
			@endif
		</div>
		<div class="panel-body">
			<tbody>
				@if ($is_edit)
				<form method="post" action="{{ route($address) }}" enctype="multipart/form-data">
				@else
				<form method="post" action="{{ route($address) }}">
				@endif
					{{ csrf_field() }}
					<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
						<label for="name" class="col-md-3 control-label">商品名</label>
						<div class="col-md-7">
							<input id="name" type="text" class="form-control" name="name" value="{{ $name }}" autofocus>
							@if ($errors->has('name'))
								<span class="help-block">
								<strong>{{ $errors->first('name') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
						<label for="content" class="col-md-3 control-label">説明</label>
						<div class="col-md-7">
							<input id="content" type="text" class="form-control" name="content" value="{{ $content }}">
							@if ($errors->has('content'))
								<span class="help-block">
								<strong>{{ $errors->first('content') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
						<label for="price" class="col-md-3 control-label">値段</label>
						<div class="col-md-7">
							<input id="price" type="text" class="form-control" name="price" value="{{ $price }}">
							@if ($errors->has('price'))
								<span class="help-block">
								<strong>{{ $errors->first('price') }}</strong>
								</span>
							@endif
						</div>
					</div>
					<div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }}">
						<label for="quantity" class="col-md-3 control-label">在庫</label>
						<div class="col-md-7">
							<input id="quantity" type="text" class="form-control" name="quantity" value="{{ $quantity }}">
							@if ($errors->has('quantity'))
								<span class="help-block">
								<strong>{{ $errors->first('quantity') }}</strong>
								</span>
							@endif
						</div>
					</div>
					@if ($is_edit)
					<p><input type="hidden" name="id" value="{{ encrypt($id) }}"></p>
					<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
						<label for="image" class="col-md-3 control-label">商品画像</label>
						<div class="col-md-7">
							<input id="image" type="file" class="form-control" name="image">
							@if ($errors->has('quantity'))
								<span class="help-block">
								<strong>{{ $errors->first('image') }}</strong>
								</span>
							@endif
						</div>
					</div>
					@endif
					<div class="col-md-3">
						<p><button type="submit">登録</button></p>
					</div>
				</form>
			</tbody>
		</div>
	</div>
</div>
</div>
</div>
</div>
@endsection
