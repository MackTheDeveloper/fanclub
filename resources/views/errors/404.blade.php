@section('title','404')
@extends('errors.layout')
@section('content')
<div class="error-404">
    <div class="container">
      	<div class="width-539">
        	<img src="{{ asset('public/assets/frontend/img/error404.png') }}">
        	<h6>The page you're trying to reach cannot be found.</h6>
        	<a href="{{ url('/') }}" class="fill-btn">Return to Homepage</a>
      	</div>
    </div>
</div>
@endsection