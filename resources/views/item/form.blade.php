@extends('layouts.app')
@section('content')
<?php $is_validation_error = $errors->any() ?>
@if ($is_validation_error)
<div class="alert alert-danger">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
<?php
$name = old('name');
$content = old('content');
$price = old('price');
$quantity = old('quantity');
?>
@endif
<?php //dd(session('id')); ?>
<?php $is_create = (session('id') == ''); ?>
@if ($is_create)
<?php $address = 'admin.item.create'; ?>
<h1>{{ '新規登録' }}</h1>
@else
<?php $address = 'admin.item.update'; ?>
<h1>{{ '商品編集' }}</h1>
@endisset
<body>
<form method="post" action="{{ route($address) }}">
{{ csrf_field() }}
<p><label>商品名:</label> <input type="text" name="name" value="{{ $name }}"></p>
<p><label>説明:</label> <textarea name="content">{{ $content }}</textarea></p>
@if ($is_create)
<p><label>値段:</label> <input type"text" name="price" value="{{ $price }}"></p>
@else
<p><input type="hidden" name="price" value="{{ $price }}"></p>
@endif
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
