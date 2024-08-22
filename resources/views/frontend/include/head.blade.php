<head>
	@include('frontend.include.meta_header')

	<link rel="icon" type="image/x-icon" href="{{asset('public/assets/frontend/img/favicon.png')}}">

	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/owl.theme.default.min.css')}}">
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick.css'>
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css'>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@yield('styles')
	{{-- <link rel="stylesheet" href='https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css'> --}}
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/developer.css')}}?r=20220824">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/cropper.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/choices.min.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap-tagsinput.css')}}">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/color.css')}}?r=20220405">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/style.css')}}?r=20220405">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/style2.css')}}?r=20220411">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/style3.css')}}?r=20220316">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/responsive.css')}}?r=20220405">
	<link rel="stylesheet" href="{{asset('public/assets/frontend/css/responsive2.css')}}?r=20220316">
	
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-41Y1LNMKRV"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
	
		gtag('config', 'G-41Y1LNMKRV');
	</script>
</head>