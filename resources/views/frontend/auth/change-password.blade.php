@extends('frontend.layouts.master')
@section('title','Security Question')
{{--@section('metaTitle',$cms->seo_title)--}}
{{--@section('metaKeywords',$cms->seo_meta_keyword)--}}
{{--@section('metaDescription',$cms->seo_description)--}}
@section('content')
<!--------------------------
        CONTACT US START
--------------------------->

<div class="contact-us">
  <h4>Change Password</h4>
  <p>Enter below required details to change password.</p>
  <form method="POST" action="{{route('changePassword')}}" id="changePassword">
    @csrf
    <div>
      <div class="inputs-group">
        <input type="password" name="old_password" value="" class="answers">
        <span>Old Paasword</span>
      </div>
      <label class="error">{{$errors->first('old_password')}}</label>
      <div class="inputs-group">
        <input type="password" name="password" id="password" value="" class="answers">
        <span>New Paasword</span>
      </div>
      <label class="error">{{$errors->first('password')}}</label>
      <div class="inputs-group">
        <input type="password" name="password_confirmation" value="" class="answers">
        <span>Re-type Paasword</span>
      </div>
      <label class="error">{{$errors->first('password_confirmation')}}</label>
    </div>
    <button class="fill-btn">Submit</button>
  </form>
</div>

<!--------------------------
        CONTACT US END
--------------------------->
@endsection
@section('footscript')
<script>
  $(document).ready(function() {
    $("#changePassword").validate({
      rules: {
        "old_password": {
          required: true
        },
        "password": {
          required: true
        },
        "password_confirmation": {
          required: true,
          equalTo: "#password"
        },
      },
      messages: {
        "old_password": {
          required: 'Please enter your old password'
        },
        "password": {
          required: 'Please enter your new password'
        },
        "password_confirmation": {
          required: 'Please enter your new password again',
          equalTo: 'Password and confirm password should be same'
        },
      },
      errorPlacement: function(error, element) {
        error.insertAfter(element);
      },
    });
  });
</script>
@endsection