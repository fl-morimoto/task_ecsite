@extends('layouts.app')
@section('content')
<body>
<table>
@if ($is_edit)
   <?php
   $id = $adds->id;
   $zip = $adds->zip;
   $state = $adds->state;
   $city = $adds->city;
   $street = $adds->street;
   $tel = $adds->tel;
   $route_address = route('address.update');
   ?>
@else
   <?php
   $zip = old('zip');
   $state = old('state');
   $city = old('city');
   $street = old('street');
   $tel = old('tel');
   $route_address = route('address.add');
   ?>
   @if (0 < $adds->count())
       <h1>お届け先一覧</h1>
       <table>
           <tr style="background-color:#e3f0fb">
               <th>選択</th>
               <th>郵便番号</th>
               <th>都道府県</th>
               <th>市区町村</th>
               <th>それ以下の住所</th>
               <th>電話番号</th>
               <th>編集</th>
               <th>削除</th>
           </tr>
           @foreach ($adds as $address)
               <tr style="background-color:#f5f5f5">
               <td ><input style="width:15px;" align="center" type="checkbox" name="chk" value="{{ $address->id }}"></td>
               <td>{{ $address->zip }}</td>
               <td>{{ $address->state }}</td>
               <td>{{ $address->city }}</td>
               <td>{{ $address->street }}</td>
               <td>{{ $address->tel }}</td>
               <td><form style="width:50px" method="post" action="{{ route('address.edit') }}">
                   {{ csrf_field() }}
                   <input type="hidden" name="address_id" value="{{ $address->id }}">
                   <button style="width:50px" type="submit">編集</button>
               </form></td>
               <td><form method="post" action="{{ route('address.delete') }}">
                   {{ csrf_field() }}
                   <input style="width:50px" type="hidden" name="address_id" value="{{ $address->id }}">
                   <button style="width:50px" type="submit">削除</button>
               </form></td>
               </tr>
           @endforeach
       </table>
       <br>
       <!-- 住所追加フォーム -->
       <hr>
       <h1>お届け先の追加</h1>
   @else
       <h1>登録住所が見つかりません、住所の登録をお願いします。</h1>
       <h1>お届け先の登録</h1>
   @endif
@endif
<form method="post" action="{{ $route_address }}">
   {{ csrf_field() }}
   <table>
   <tr><td>郵便番号(ハイフンなし半角７桁)</td></tr>
   <tr><td><input type="text" name="zip" value="{{ $zip }}" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this, '', 'state', 'city', 'street', 'tel');"></td></tr>
   <tr><td>都道府県</td></tr>
   <tr><td><input type="text" name="state" value="{{ $state }}"></td></tr>
   <tr><td>市区町村</td></tr>
   <tr><td><input type="text" name="city" value="{{ $city }}"></td></tr>
   <tr><td>それ以下の住所</td></tr>
   <tr><td><input type="text" name="street" value="{{ $street }}"></td></tr>
   <tr><td>電話番号(ハイフンなし半角)</td></tr>
   <tr><td><input type="text" name="tel" value="{{ $tel }}"></td></tr>
   </table><br>
   <input type="submit" value="登録" style="width:100px;height:30px">
   @if ($is_edit)
       <input type="hidden" name="id" value="{{ $id }}">
   @endif
</form>
</body>
@endsection
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

