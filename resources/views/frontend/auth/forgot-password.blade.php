@section('title','Reset Password')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        FORGOT PASSWORD START
--------------------------->

<div class="forgot-password">
		<h4>Forgot Password</h4>
		<p>Keep calm, we’ve got you covered.  Just enter your email address below to reset your password</p>
		<form method="POST" id="forgotPassword" action="{{ route('forgotPassword') }}">
            @csrf
			<div>
				<div class="inputs-group">
					<input type="email" class="email" name="email" id="email">
						<span>Email Address*</span>
						<!-- <label class="error">Error</label> -->
				</div>
			</div>
			<div class="still-need-help">
				<a href="{{route('showSecurityQuestionCheck')}}"><span>Still Need Help?</span></a>
			</div>
			<button type="submit" class="fill-btn">Submit</button>
		</form>
	</div>

<!--------------------------
        FORGOT PASSWORD END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
    $("#forgotPassword").validate( {
        ignore: [],
        rules: {
            email: {
                required:true
            }
        },
        messages:{
            email: {
                required:"Email is required"
            }
        },
        errorPlacement: function ( error, element ) {
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
    });
</script>
@endsection
