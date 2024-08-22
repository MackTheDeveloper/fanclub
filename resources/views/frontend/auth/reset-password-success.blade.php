@section('title','Reset Password')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        RESET PASSWORD SUCCESS START
--------------------------->

<div class="password-successfull">
    <div class="container">
        <img src="{{ asset('public/assets/frontend/img/Sucess-badge.svg') }}" alt=""/>
        <h4>Password Reset Successful!</h4>
        <p>Your password is reset successfully you can now log in with the new password</p>
        <a href="{{ route('login') }}" class="fill-btn">Return to Log In</a>
    </div>
</div>

<!--------------------------
        RESET PASSWORD SUCCESS END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
</script>
@endsection