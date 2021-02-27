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
<style>
	table {
		border-collapse: collapse;
		border: 0px solid;
		margin: 0px auto 20px auto;
	}
	caption {
		font-size: 20px;
		color: #303030
	}
	tr {
		border-color: #e0e0e0;
		border-style: solid;
		border-width: 1px 0px;
	}
	th {
		text-align: center;
		padding: 8px;
	}
	td {
		padding: 8px;
	}
	h1 {
		margin: 0px 0px 0px 40px;
		font-size: 20px;
		font-weight: bold;
		text-indent: 1em;
	}
	h2 {
		margin: 0px auto 0px 40px;
		font-size: 17px;
		font-weight: bold;
		text-indent: 1em;
	}
	input, textarea {
	/* To make sure that all text fields have the same font settings By default, textareas have a monospace font */
	font: 1em sans-serif;
	/* To give the same size to all text fields */
	width: 300px;
	box-sizing: border-box; /* To harmonize the look & feel of text field border */
	border: 1px solid #999;
	}
	form {
	/* Just to center the form on the page */
	margin: 0 left;
	width: 400px;
	/* To see the outline of the form
	padding: 1em;
	border: 1px solid #CCC;
	border-radius: 1em;*/
	}
	form div + div {
	margin-top: 1em;
	}
	input:focus, textarea:focus {
	/* To give a little highlight on active elements */
	border-color: #000;
	}
	textarea {
	/* To properly align multiline text fields with their labels */
	vertical-align: top;
	/* To give enough room to type some text */
	height: 5em;
	}
	.button {
	/* To position the buttons to the same position of the text fields */
	padding-left: 90px;
	/* same size as the label elements */
	}
	button {
	/* This extra margin represent roughly the same space as the space between the labels and their text fields */
	margin-left: .5em;
	}
</style>
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
