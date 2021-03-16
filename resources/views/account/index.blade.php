@extends('layouts.app')
@section('content')
<div class="container">
<div class="row">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
	<div class="panel-heading">会員一覧ページ</div>
		<div class="panel-body">
		<table class="table">
		<tbody>
			<tr style="background-color:#e3f0fb">
			<th style="width: 100px">会員ID</th>
			<th style="width: 150px">名前</th>
			<th style="width: 150px">email</th>
			@foreach ($users as $user)
				<tr style="background-color:#f5f5f5">
					<td style="text-align:left">{{ $user->id }}</td>
					<td style="text-align:left"><a href="{{ route('admin.detailForAdmin', ['id' => encrypt($user->id)]) }}">{{ $user->name }}</td>
					<td style="text-align:left">{{ $user->email }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
