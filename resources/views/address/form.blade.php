<div style=" margin: 20px 0px 0px 0px">
	@if (!empty($address->id))
	<p><a href="{{ route('address.index') }}">住所一覧ページへ</a></p>
	<p>お届け先の編集</p>
	<form method="post" action="{{ route('address.update') }}">
	@else
	<form method="post" action="{{ route('address.insert') }}">
	@endif
		{{ csrf_field() }}
		<input type="hidden" name="id" value="{{ encrypt($address->id) }}">
		<p>郵便番号(ハイフンなし半角７桁)</p>
		<p><input type="text" name="zip" value="{{ $address->zip }}"></p>
		<p>都道府県</td></tr>
		<p><select name="state">
		@foreach (config('pref') as $index => $name)
			<option value="{{ $index }}" @if ($address->state == $name) selected @endif>{{ $name }}</option>
		@endforeach
		</select>
		<p>市区町村</td></tr>
		<p><input type="text" name="city" value="{{ $address->city }}"></p>
		<p>それ以下の住所</td></tr>
		<p><input type="text" name="street" value="{{ $address->street }}"></p>
		<p>電話番号(ハイフンなし半角)</td></tr>
		<p><input type="text" name="tel" value="{{ $address->tel }}"></p>
		<input type="submit" value="登録" style="width:100px;height:30px">
	</form>
</div>
