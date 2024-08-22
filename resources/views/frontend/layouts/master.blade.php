<!DOCTYPE html>
<html>
@include('frontend.include.head')

<body id="body">
	@php($themeType = Auth::check() ? Auth::user()->theme : (!empty($darkmode)?'dark':''))
	<script type="text/javascript">
		var thhemeType = "{{$themeType}}";
		if (!thhemeType) {
			thhemeType = localStorage.getItem('fanclubtheme');
		}
		if (thhemeType == 'dark') {
			var element = document.getElementById("body");
			element.classList.add("dark-theme");
		}
	</script>
	<!--------------------------
	    HEADER START
	--------------------------->
	@include('frontend.include.header')

	<!--------------------------
	    HEADER END
	--------------------------->

	<div class="ajax-alert">

	</div>


	<!--------------------------
	    	CONTENT START
	--------------------------->
	@yield('content')
	<!--------------------------
	    	CONTENT END
	--------------------------->

	<!--------------------------
	    	FOOTER START
	--------------------------->
	@if(Request::route()->getName() != 'myMusicPlayer')
	@include('frontend.include.footer')
	@endif
	<!--------------------------
	    	FOOTER END
	--------------------------->
	<div class="cookie-alert" id="cookie-alert">
		<img class="close-cookie-alert" src="{{asset('public/assets/frontend/img/close.svg')}}" alt="" />
		<p>We and our partners use cookies to personalise your experience, for measurement and analytics purposes. By using our website and services, you agree to our use of cookies as described in our <a href="{{route('cookiePolicy')}}"> Cookie Policy.</a></p>
	</div>
</body>
@include('frontend.include.bottom')
@if(Session::has('message'))
<script>
	var type = "{{ Session::get('alert-type', 'info') }}";
	switch (type) {
		case 'info':
			toastr.info("{{ Session::get('message') }}");
			break;

		case 'warning':
			toastr.warning("{{ Session::get('message') }}");
			break;

		case 'success':
			toastr.success("{{ Session::get('message') }}");
			break;

		case 'error':
			toastr.error("{{ Session::get('message') }}");
			break;
	}
</script>
@endif

</html>