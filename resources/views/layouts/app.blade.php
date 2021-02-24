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

<!-- Styles -->
<nav class="navbar navbar-default navbar-static-top">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
h1 { font-size: 17px; }
h2 { font-size: 15px; }
.screen_wrap { padding: 0px 15px 15px; }
td, th {
padding: 5px 10px;
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
label {
/* To make sure that all labels have the same size and are properly aligned */
display: inline-block;
width: 90px;
text-align: right;
}
input, textarea {
/* To make sure that all text fields have the same font settings By default, textareas have a monospace font */
font: 1em sans-serif;
/* To give the same size to all text fields */
width: 300px;
box-sizing: border-box; /* To harmonize the look & feel of text field border */
border: 1px solid #999;
}
input:focus, textarea:focus {
/* To give a little highlight on active elements */
border-color: #000;
}
table {
border-spacing: 10px;
margin-left: 2em;
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
@if (isAdminRoute())
<style>body{background-color: wheat;}</style>
@endif
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
</a>
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
@endif
</div>
</nav>

<div class="screen_wrap">
<div class="container py-4">
{{-- フラッシュメッセージの表示 --}}
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
<main class="mt-4">
@yield('content')
</main>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
