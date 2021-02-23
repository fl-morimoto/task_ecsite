@extends('layouts.app')

@section('content')
<label><h1>商品一覧</h1></label>
<table border="1">
<tr>
<th width=150>商品名</th>
<th width=100>値段</th>
<th width=100>在庫数</th>
<th width=100>在庫</th>
</tr>
@foreach ($items as $item)
<tr>
@if (getUserType() == 'Admin')
<td align="right"><a href="{{ route('admin.item.detail', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
@else
<td align="right"><a href="{{ route('item.detail', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
@endif
<td align="right">{{ $item->price }}</td>
<td align="right">{{ $item->quantity }}</td>
@if (0 < $item->quantity)
<td align="right">在庫あり</td>
@else
<td align="right">在庫なし</td>
@endif
</tr>
@endforeach
</table>
@if (isLogin() && getUserType() == 'Admin')
<p></p>
<p><a href="{{ route('admin.item.form') }}">商品追加ページ</a></p>
@endif
@endsection
