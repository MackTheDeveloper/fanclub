@extends('frontend.layouts.master')
@section('title',$cms->name)
@section('metaTitle',$cms->seo_title)
@section('metaKeywords',$cms->seo_meta_keyword)
@section('metaDescription',$cms->seo_description)
@section('content')
<!--------------------------
        CONTACT US START
--------------------------->

<div class="contact-us">
    <h4>Have a query? contact us here.</h4>
    <p>Want to leave feedback? We'd love to hear from you.</p>
    <form method="POST" action="{{url('contact-us')}}" id="contactUsForm">
        @csrf
        {{-- {!! app('captcha')->render(); !!} --}}
        <div>
            <div class="inputs-group">
                <input type="text" name="first_name" value="{{$userData->firstname}}">
                <span>Your Name*</span>
                <!-- <label class="error">Error</label> -->
            </div>
            {{-- <div class="inputs-group">
                <input type="text" name="last_name" value="{{$userData->lastname}}">
                <span>Last Name*</span>
                <!-- <label class="error">Error</label> -->
            </div> --}}
            <div class="inputs-group">
                <input type="email" name="email" value="{{$userData->email}}" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">
                <span>Email Address*</span>
                @if ($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                @endif
                <!-- <label class="error">Error</label> -->
            </div>
            {{-- <div class="inputs-group d-none">
                <input type="number" name="phone" value="{{$userData->phone}}">
                <span>Phone Number</span>
                <!-- <label class="error">Error</label> -->
            </div> --}}
            <div class="inputs-group">
                <input name="message" type="text" >
                <span>Message*</span>
                <!-- <label class="error">Error</label> -->
            </div>
            {{-- <div class="textarea-group inputs-group">
                <textarea name="message" placeholder="Write your message here"></textarea>

                <!-- <label class="error">Error</label> -->
            </div> --}}
        </div>
        <button type="submit" class="fill-btn g-recaptcha" data-sitekey="{{ env('INVISIBLE_RECAPTCHA_SITEKEY') }}" data-callback='submitForm'>Submit</button>
    </form>
    <!-- <div class="follow-us">
        <h5>Follow Us</h5>
        <div class="contact-social">
            <a href=""><img src="{{asset('public/assets/frontend/img/fb-color.png')}}" alt="" /></a>
            <a href=""><img src="{{asset('public/assets/frontend/img/tw-color.png')}}" alt="" /></a>
            <a href=""><img src="{{asset('public/assets/frontend/img/insta-color.png')}}" alt="" /></a>
            <a href=""><img src="{{asset('public/assets/frontend/img/yt-color.png')}}" alt="" /></a>
        </div>
    </div> -->
</div>

<!--------------------------
        CONTACT US END
--------------------------->
@endsection
@section('footscript')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script type="text/javascript">
    $("#contactUsForm").validate({
        ignore: [],
        rules: {
            first_name: {
                required: true,
            },
            email: {
                    required: true,
                    email: true,
                },
            message: {
                required: true,
            },
            password: "required",
        },
        messages: {
            first_name: {
                required: "Please enter your first name",
            },
            email: "Please insert a valid email address.",
            message: "Please enter your message",
            password: "Password is required",
        },

        errorPlacement: function(error, element) {
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form) {
            if (grecaptcha.getResponse()) {
                    // 2) finally sending form data
                    form.submit();
            }else{
                    // 1) Before sending we must validate captcha
                grecaptcha.reset();
                grecaptcha.execute();
            }
        }
    });

    function submitForm() {
        $("#contactUsForm").submit();
        return true;
    }
</script>
@endsection
