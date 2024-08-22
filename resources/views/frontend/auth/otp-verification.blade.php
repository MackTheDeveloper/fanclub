@section('title', 'Sign In')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
            SIGN IN START
    --------------------------->

    <div class="otp-page">
        <h4>OTP Verification</h4>
        <p>We have sent the OTP to your email address</p>
        <div class="email-edit">
            <p class="s1">{{ Session::get('opt-email') }}</p>
            <a href="{{ url('login-using-otp') }}"><img src={{ url('public/assets/img/edit.svg') }} alt="" /></a>
        </div>
        <form id="otpVerificationForm" method="POST" action="{{ url('/otp-verification') }}">
            @csrf
            <div class="otp-input">
                <input type="text" id="digit-1" name="digit-1" data-next="digit-2" oninput='digitValidate(this)' />
                <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1"
                    oninput='digitValidate(this)' />
                <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2"
                    oninput='digitValidate(this)' />
                <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3"
                    oninput='digitValidate(this)' />
            </div>

            <input type="hidden" name="otp" id="otp-input" value="" />
            <input type="hidden" name="input" id="email" value="{{ Session::get('opt-email') }}" />

            <button type="submit" class="fill-btn">Submit</button>
        </form>
        <div class="resend-otp">
            <form action="{{ url('/login-using-otp') }}" id="resendOtpForm" method="post">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="input" id="email" value="{{ Session::get('opt-email') }}" />
                <span>
                    <a href="#" onclick="document.getElementById('resendOtpForm').submit();">Resend OTP</a>
                </span>
            </form>
        </div>
    </div>

    <!--------------------------
            SIGN IN END
    --------------------------->
@endsection
@section('footscript')
    <script type="text/javascript">

    </script>
@endsection
