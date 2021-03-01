@extends('layouts.app')

@section('content')
<form method="post" action="{{ $route_address }}">
   {{ csrf_field() }}
   <table>
   <tr><td>郵便番号(ハイフンなし半角７桁)</td></tr>
   <tr><td><input type="text" name="zip" value="" maxlength="8"></td></tr>
   <tr><td>都道府県</td></tr>
   <tr><td><input type="text" name="state" value=""></td></tr>
   <tr><td>市区町村</td></tr>
   <tr><td><input type="text" name="city" value=""></td></tr>
   <tr><td>それ以下の住所</td></tr>
   <tr><td><input type="text" name="street" value=""></td></tr>
   <tr><td>電話番号(ハイフンなし半角)</td></tr>
   <tr><td><input type="text" name="tel" value=""></td></tr>
   </table><br>
   <input type="submit" value="登録" style="width:100px;height:30px">
</form>
@endsection
