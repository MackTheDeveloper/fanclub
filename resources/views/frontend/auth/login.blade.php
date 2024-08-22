@section('title','Sign In')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        SIGN IN START
--------------------------->

<div class="signin-page">
    <h4>Sign In</h4>
    <p>Already have an account? Welcome back!</p>
    <form id="loginForm" method="POST" action="{{url('/login')}}">
        @csrf
        <div>
            <div class="inputs-group">
                <input type="text" class="email" name="email" id="email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" >
                <span>Email Address*</span>
                @if ($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                @endif
                <!-- <label class="error">Error</label> -->
            </div>
            <div class="inputs-group">
                <input type="password" name="password" id="password">
                <span>Password*</span>
                <!-- <label class="error">Error</label> -->
            </div>
        </div>
        <div class="forgot-psw">
            <a href="{{ route('showForgotPassword') }}"><span>Forgot Password?</span></a>
        </div>
        <div class="otp">
            <span>Or Log In with<a href="{{url('login-using-otp')}}">One Time Password (OTP)</a></span>
        </div>
        <button type="submit" class="fill-btn">Log In</button>
        <div class="create-link">
            <span>New to fanclub?<a href="{{url('signup')}}"> Create Account</a></span>
        </div>
    </form>
</div>

<!--------------------------
        SIGN IN END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
    $("#loginForm").validate({
        ignore: [],
        rules: {
            email: {
                    required: true,
                    email: true,
                },
            password: "required",
        },
        messages: {
            email: "Please insert a valid email address.",
            password: "Password is required"
        },
        errorPlacement: function(error, element) {
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
    });

    $(document).on('change keyup keydown', 'input[name="email"]', function() {
        $('input[name="input"]').val($(this).val())
        var valueInt = $.isNumeric($(this).val());
        if (valueInt) {
            if (!$('.hasEmailPhone').hasClass('hasNumber')) {
                $('.hasEmailPhone').addClass('hasNumber');
            }
        } else {
            $('.hasEmailPhone').removeClass('hasNumber');
        }
    })
</script>
@endsection
