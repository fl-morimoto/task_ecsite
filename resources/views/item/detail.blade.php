<h1>商品詳細</h1>
<p width="200">{{ $item->name }}</p>
<p width="200">{{ $item->price }}</p>
<p width="200">{{ $item->quantity }}</p>
@if (0 < $item->quantity)
<p>在庫あり</p>
@else
<p>在庫なし</p>
@endif
<p><a href="{{ route('item.index')}}">商品一覧へ</p>
