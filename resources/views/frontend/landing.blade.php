<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Coming Soon</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{ asset('public/assets/frontend/img/favicon.png') }}" type="image/x-icon" />
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/responsive.css')}}">
	<script src="{{asset('public/assets/frontend/js/jquery.min.js')}}"></script>
	<script src="{{asset('public/assets/frontend/js/popper.min.js')}}"></script>
	<script src="{{asset('public/assets/frontend/js/bootstrap.min.js')}}"></script>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="coming-soon">
	<div class="container">
		<div class="logo">
			<img src="{{asset('public/assets/frontend/img/Logo.svg')}}" alt="">
		</div>
		<div class="row">
			<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 order-md-1 order-sm-2 order-2">
				<div class="coming-content">
					<div class="conent-icons">
						<h4>- Coming Soon</h4>
						<a href="javascript:void(0)"><img src="{{asset('public/assets/frontend/img/Web.svg')}}" class="banner-img"></a>
						<a href="javascript:void(0)"><img src="{{asset('public/assets/frontend/img/Apple.svg')}}" class="banner-img"></a>
						<a href="javascript:void(0)"><img src="{{asset('public/assets/frontend/img/Android.svg')}}" class="banner-img"></a>
					</div>
					<h1>Are you interested?</h1>
					<p>Be the first to know when we launch. <br> Sign up for updates using the form below</p>
					{{ Form::open(array('url' =>route('submitComingInterest'),'class'=>'coming-form','id'=>'coming-form','enctype'=>"multipart/form-data",'autocomplete'=>'off')) }}
						{!! app('captcha')->render(); !!}
						@if (session('success'))
						    <div class="alert alert-success">
						        {{ session('success') }}
						    </div>
						@endif
						@if (session('error'))
						    <div class="alert alert-danger">
						        {{ session('error') }}
						    </div>
						@endif
						<div class="select-with-label">
							<label>What describes you?</label>
							{{ Form::select('role', [''=>'Select...', 'artist'=>'Artist', 'fan'=>'Fan'], old('role'), ['class' => 'select']) }}
						</div>
						<input type="text" placeholder="Your Name*" class="input" name="name" value="{{ old('name') }}">
						<input type="text" placeholder="Your Email Address*" class="input mb-24" name="email" value="{{ old('email') }}">
						<button type="submit" class="register-btn">Register Your Interest</button>
					{{ Form::close()}}
					
				</div>
			</div>
			<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 order-md-2 order-sm-1 order-1">
				<div class="coming-banner-outer">
					<div class="coming-banner-inner">
						<img src="{{asset('public/assets/frontend/img/banner-shadow.png')}}" class="back-shadow">
						<img src="{{asset('public/assets/frontend/img/Image2.png')}}" class="banner-img">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
	 setTimeout(function(){
	 	$('.alert').fadeOut();
	 }, 3000);
</script>
</html>