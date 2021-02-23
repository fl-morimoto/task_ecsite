@extends('layouts.app')

@section('content')
<label>
<h1>商品詳細</h1>
</label>
@if (!empty($item))
<table>
<tr>
<td width="100">{{ '商品名: ' }}</td>
<td width="100">{{ $item->name }}</td>
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
</table>
<label><p></p>
@if (0 < $item->quantity)
<p>在庫あり</p>
@else
<p>在庫なし</p>
@endif
@if (isLogin() && getUserType() == 'User')
<p><a href="{{ route('item.index')}}">商品一覧へ</a></p>
@elseif (isLogin() && getUserType() == 'Admin')
<p><a href="{{ route('admin.item.index') }}">商品一覧へ</a></p>
<p></p>
<p><a href="{{ route('admin.item.form', ['id' => $item->id]) }}">商品編集へ</a></p>
@endif
@else
<p>その商品は存在しません。</p>
</label>
@endif
@endsection


