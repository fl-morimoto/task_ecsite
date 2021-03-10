@php
//dd($address);
@endphp
@if (!empty($address->id))
<div style="font-size: 16px;margin: 0px 0px 20px 20px">
	<p><a href="{{ route('address.index') }}">住所一覧ページへ</a></p>
</div>
@endif
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading"style="background-color:#f5f5f5">
	@if (!empty($address->id))
		お届先住所の編集
	@else
		お届先住所の追加
	@endif
	</div>
	<div class="panel-body">
		@if (!empty($address->id))
		<form method="post" action="{{ route('address.update') }}">
		@else
		<form method="post" action="{{ route('address.insert') }}">
		@endif
			{{ csrf_field() }}
			<input type="hidden" name="id" value="{{ encrypt($address->id) }}">
			<p>郵便番号(ハイフンなし半角７桁)</p>
			<p><input type="text" name="zip" value="{{ $address->zip }}"></p>
			<p>都道府県</p>
			<p><select name="state">
			@foreach (config('pref') as $index => $name)
				<option value="{{ $index }}" @if (config('pref.' . $address->state) == $name) selected @endif>{{ $name }}</option>
			@endforeach
			</select></p>
			<p>市区町村</p>
			<p><input type="text" name="city" value="{{ $address->city }}"></p>
			<p>それ以下の住所</p>
			<p><input type="text" name="street" value="{{ $address->street }}"></p>
			<p>電話番号(ハイフンなし半角)</p>
			<p><input type="text" name="tel" value="{{ $address->tel }}"></p>
			<p><input type="submit" value="登録" style="width:100px;height:30px"></p>
		</form>
	</div>
</div>
</div>
</div>
</div>
