<h1>商品一覧</h1>
<table border="1">
<tr>
<th width=100>商品名</th>
<th width=100>値段</th>
<th width=100>在庫数</th>
<th width=100>在庫</th>
</tr>
@foreach ($items as $item)
<tr>
<td align="right"><a href="{{ route('item.detail', ['id' => $item->id]) }}">{{ $item->name }}</td>
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
