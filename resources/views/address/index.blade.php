@extends('layouts.app')
@section('content')
@foreach($addresses as $address)
	<table>
		<tr>
			<th>{{ '郵便番号' }}</th>
			<th>{{ '都道府県' }}</th>
			<th>{{ '市' }}</th>
			<th>{{ '番地' }}</th>
			<th>{{ '電話番号' }}</th>
		</tr>
		<tr>
			<td>{{ $address->zip}}</td>
			<td>{{ $address->state}}</td>
			<td>{{ $address->city}}</td>
			<td>{{ $address->street}}</td>
			<td>{{ $address->tel}}</td>
		</tr>
	</table>
@endforeach
<form method="post" action="{{ route('address.add') }}">
   {{ csrf_field() }}
	   <p>郵便番号(ハイフンなし半角７桁)</p>
	   <p><input type="text" name="zip" value=""></p>
	   <p>都道府県</td></tr>
	   <p><input type="text" name="state" value=""></p>
	   <p>市区町村</td></tr>
	   <p><input type="text" name="city" value=""></p>
	   <p>それ以下の住所</td></tr>
	   <p><input type="text" name="street" value=""></p>
	   <p>電話番号(ハイフンなし半角)</td></tr>
	   <p><input type="text" name="tel" value=""></p>
   <input type="submit" value="登録" style="width:100px;height:30px">
</form>
@endsection
