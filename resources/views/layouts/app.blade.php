<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body>
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
@if (isAdminRoute())
	<style>body{background-color: wheat;}</style>
@endif
<div id="app">
	<nav class="navbar navbar-default n3vbar-static-top">
		<div class="container">
			<div class="navbar-header">
				<!-- Collapsed Hamburger -->
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- Branding Image -->
				@if (!isLogin())
					<a class="navbar-brand" href="{{ url('/') }}">
					{{ config('app.name', 'Laravel') }}</a>
				@elseif (isLogin() && getUserType() == 'User')
					<a class="navbar-brand" href="{{ route('home') }}">
					{{ config('app.name', 'Laravel') }}</a>
				@elseif (isLogin() && getUserType() == 'Admin')
					<a class="navbar-brand" href="{{ route('admin.home') }}">
					{{ config('app.name', 'Laravel') }}</a>
				@endif
			</div>
			<div class="collapse navbar-collapse" id="app-navbar-collapse">
				<!-- Left Side Of Navbar -->
				<ul class="nav navbar-nav">
					&nbsp;
				</ul>
				<!-- Right Side Of Navbar -->
				<ul class="nav navbar-nav navbar-right">
				<!-- Authentication Links -->
				@if (!isLogin())
					<li><a href="{{ route('login') }}">Login</a></li>
					<li><a href="{{ route('register') }}">Register</a></li>
				@elseif (!isLogin() && isAdminRoute())
					<li><a href="{{ route('admin.login') }}">Login</a></li>
				@endif
				@if (isLogin())
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
							{{ userInfo()->name }} <span class="caret"></span>
						</a>
						@endif
						@if (isLogin() && getUserType() == 'User')
						<ul class="dropdown-menu">
							<li>
								<a href="{{ route('account.detail') }}"
									onclick="event.preventDefault();
									document.getElementById('user-detail').submit();">
									ユーザー情報編集
								</a>
								<form id="user-detail" action="{{ route('account.detail') }}" method="GET" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
									Logout
								</a>
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
						@elseif (isLogin() && getUserType() == 'Admin')
						<ul class="dropdown-menu">
							<li>
								<a href="{{ route('admin.logout') }}"
									onclick="event.preventDefault();
									document.getElementById('logout-form').submit();">
									Logout
								</a>
								<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				@endif
			</div>
		</div>
	</nav>
	{{-- フラッシュメッセージの表示 --}}
	<div class="screen_wrap">
		<div class="container py-4">
			@if (Session::has('true_message'))
						<div class="alert alert-success">{{ session('true_message') }}</div>
			@elseif (Session::has('false_message'))
						<div class="alert alert-danger">{{ session('false_message') }}</div>
			@endif
			<?php
			session()->flash('message', null);
			session()->flash('is_succes', null);
			?>
		</div>
	</div>
	@yield('content')
<!-- Scripts -->
	<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
