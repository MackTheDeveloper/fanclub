@section('title','Reset Password')
@extends('frontend.layouts.master')
@section('content')
<!--------------------------
        RESET PASSWORD START
--------------------------->

<div class="reset-password">
    <h4>Reset Password</h4>
    <p>Please enter a new password</p>
    <form method="POST" id="resetPassword" action="{{ route('resetPassword') }}">
        @csrf
        <input name="input" type="hidden" value="{{$email}}">
        <div>
            <div class="inputs-group">
                <input type="password" name="password" id="password">
                <span>New Password*</span>
                <!-- <label class="error">Error</label> -->
            </div>
            <div class="inputs-group">
                <input type="password" name="password_confirmation" id="password_confirmation">
                <span>Confirm Password*</span>
                <!-- <label class="error">Error</label> -->
            </div>
        </div>
        <button type="submit" class="fill-btn">Reset</button>
    </form>
</div>

<!--------------------------
        RESET PASSWORD END
--------------------------->
@endsection
@section('footscript')
<script type="text/javascript">
    $("#resetPassword").validate( {
        ignore: [],
        rules: {
            password: {
                required:true,
                minlength:8,
            },
            password_confirmation: {
                required:true,
                minlength:8,
                equalTo : "#password"
            }
        },
        messages:{
            password: {
                required:"Password is required"
            },
            password_confirmation: {
                required:"Confirm Password is required"
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