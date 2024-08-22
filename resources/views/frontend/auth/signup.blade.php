@section('title', 'Create Account')
@extends('frontend.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/jquery.ccpicker.css') }}">
@endsection
@section('content')
    @php($defaultPhnPrefix = '+353')
    <!--------------------------
                    SIGN UP START
            --------------------------->
    <div class="create-account">
        <h4>Create Account</h4>
        <p>Join our community of music fans now and start to build your fanclub collection</p>
        <form id="signupForm" method="POST" action="{{ url('/signup') }}">
            @csrf
            <input name="role_id" type="hidden" value="3">
            <div>
                @if($introducer)
                    <div class="inputs-group">
                        <input type="text" value="Fan" readonly>
                        <input type="hidden" name="role_id" value="3">
                        <input type="hidden" name="introducer_id" value="{{$introducer}}">
                        <span id="contact_name_hide">Register as*</span>
                    </div>
                @else
                    <div class="label-select pt-16">
                        <span>Register as*</span>
                        <select name="role_id" id="role">
                            <option>Select...</option>
                            <option value="3" selected>Fan</option>
                            <option value="2">Artist</option>
                        </select>
                        <!-- <label class="error">Error</label> -->
                    </div>
                @endif
                <div class="artist-introduce d-none artist-role">
                    <span>Are you band or solo artist?</span>
                    <div class="radio-group">
                        <label class="rd">Band
                            <input type="radio" value="other" checked="checked" name="gender">
                            <span class="rd-checkmark"></span>
                        </label>
                        <label class="rd">Solo Male
                            <input type="radio" value="male" name="gender">
                            <span class="rd-checkmark"></span>
                        </label>
                        <label class="rd">Solo Female
                            <input type="radio" value="female" name="gender">
                            <span class="rd-checkmark"></span>
                        </label>
                    </div>
                </div>
                <div class="inputs-group">
                    <input type="text" name="firstname">
                    <span id="contact_name_hide">Your Name*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                {{-- <div class="inputs-group">
          <input type="text" name="lastname">
          <span>Last Name*</span>
          <!-- <label class="error">Error</label> -->
      </div> --}}
                <div class="inputs-group">
                    <input type="email" name="email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">
                    <span id="contact_email_hide">Email Address*</span>
                    <!-- <label class="error">Error</label> -->
                    @if ($errors->has('email'))
                        <div><label class="error">{{ $errors->first('email') }}</label></div>
                    @endif
                </div>
                <div class="number-wrapper">
                    <div class="input">
                        <input type="text" id="phoneField1" name="phoneCode" class="phone-field" />
                    </div>
                    <div class="number-group">
                        <input type="number" name="phone">
                        <span>Phone Number</span>
                        <!-- <label class="error">Error</label> -->
                    </div>
                </div>
                <div class="label-select">
                    <span>Country of Residence*</span>
                    <select class="select select_country" name="country" id="country">
                        <option value="">Select</option>
                        @foreach ($countries as $key => $row)
                            <option value="{{ $row['value'] }}">{{ $row['value'] }}</option>
                        @endforeach
                    </select>
                    {{-- {!! Form::select('country', $countries->prepend('Country Of Residence*', ''), '', array("class" => "select select_country")) !!} --}}
                    <!-- <label class="error">Error</label> -->
                </div>
                {{-- <div class="label-select selectDiv">
        <select class="select select_state" name="state" id="state">
          <option>Select...</option>
        </select>
        <!-- <label class="error">Error</label> -->
      </div> --}}
                {{-- <div class="inputs-group show_states_input">
                    <input type="text" name="state" value="">
                    <span>State</span>
                    <!-- <label class="error">Error</label> -->
                </div> --}}
                <div class="d-none label-select show_states_select">
                    <span>State*</span>
                    <select class="select select_state" name="state" id="state">
                        <option>Select...</option>
                    </select>
                </div>
                <div class="inputs-group">
                    <input type="password" id="password" name="password">
                    <span>Password*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="password" name="conform-password">
                    <span>Confirm Password*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
            </div>
            <div class="social-handler d-none artist-role">
                <h6>Social Media Handles</h6>
                <div class="social-user-box">
                    <div class="social-name-img">
                        <img src="{{ asset('public/assets/frontend/img/s-insta.png') }}" class="insta-img" alt="" />
                        <span>Instagram</span>
                    </div>
                    <input placeholder="@username" name="instagram" id="instagram" value="" />
                </div>
                <div class="social-user-box">
                    <div class="social-name-img">
                        <img src="{{ asset('public/assets/frontend/img/s-fb.png') }}" class="fb-img" alt="" />
                        <span>Facebook</span>
                    </div>
                    <input placeholder="@username" name="facebook" id="facebook" value="" />
                </div>
                <div class="social-user-box">
                    <div class="social-name-img">
                        <img src="{{ asset('public/assets/frontend/img/s-tw.png') }}" class="tw-img" alt="" />
                        <span>Twitter</span>
                    </div>
                    <input placeholder="@username" name="twitter" id="twitter" value="" />
                </div>
            </div>
            <div class="agree-to">
                <span>By proceeding you agree to fanclub's <a target="_blank" href="{{ url('terms-conditions') }}"> Terms
                        &
                        Conditions </a> and <a target="_blank" href="{{ url('privacy-policy') }}"> Privacy Policy
                    </a></span>
            </div>
            <button type="submit" class="fill-btn g-recaptcha" data-sitekey="{{ env('INVISIBLE_RECAPTCHA_SITEKEY') }}"
                data-callback='submitForm'>Continue</button>
            <div class="login-link">
                <span>Have an Account?<a href="{{ url('login') }}">Sign In</a></span>
            </div>
        </form>
    </div>

    <!--------------------------
                    SIGN UP END
            --------------------------->
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/jquery.ccpicker.js') }}"
        data-json-path="{{ asset('public/assets/frontend/data.json') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script type="text/javascript">
        $('#phoneField1').CcPicker();
        $("#phoneField1").CcPicker("setCountryByPhoneCode", "{{ $defaultPhnPrefix }}");
        $("#signupForm").validate({
            ignore: [],
            rules: {
                firstname: "required",
                lastname: "required",
                country: "required",
                state: "required",
                email: {
                    required: true,
                    email: true,
                },
                phone: {
                    required: true,
                    minlength: 7,
                    maxlength: 15
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                "conform-password": {
                    required: true,
                    minlength: 8,
                    equalTo: "#password"
                },
            },
            messages: {
                firstname: "First Name is required",
                lastname: "Last Name is required",
                email: "Please insert a valid email address",
                state: "Please Enter State",
                phone: {
                    required: "Phone Number is required",
                    minlength: 'Please enter a min 7 digit phone number',
                    maxlength: 'Please enter a max 10 digit phone number'
                },
                password: {
                    required: "Password is required"
                },
                "conform-password": {
                    required: "Confirm Password is required",
                    equalTo: "Please enter same as Password"
                }
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else if (element.prop("name") === "phone") {
                    error.appendTo('.number-wrapper');
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                if (grecaptcha.getResponse()) {
                    // 2) finally sending form data
                    form.submit();
                } else {
                    // 1) Before sending we must validate captcha
                    grecaptcha.reset();
                    grecaptcha.execute();
                }
            }
        });
        $(document).on('change', '#country', function() {
            stateTextDropdown();
            var countryArr = ['United States','Canada'];
            var value = $(this).val();
            if (jQuery.inArray(value, countryArr) !== -1) {
            // if (value == 'United States' || value == 'Canada') {
                var token = "{{ csrf_token() }}";
                $.ajax({
                    url: "{{ route('stateList') }}",
                    method: 'post',
                    data:{country:value,_token:token},
                    // data: 'country="'+value+'"&_token={{ csrf_token() }}',
                    // dataType: 'json',
                    success: function(response) {
                        $('.select_state').empty();
                        $.each(response.component.stateListData.countries, function(k, v) {
                            $(".select_state").append('<option value="' + v.key + '">' + v.key +
                                '</option>');
                        });
                    }
                });
            }
        });
        $(document).on('change', '#role', function() {
            var value = $(this).val();
            if (value == '3') {
                $('.artist-role').addClass('d-none');
                $('#contact_name_hide').text('Your Name*');
                $('#contact_email_hide').text('Email Address*');
                $('.create-account h4').text('Create Account');
                $('.create-account p').text('Join our community of music fans now and start to build your fanclub collection.');
                // change text under span
            } else {
                $('.artist-role').removeClass('d-none');
                $('#contact_name_hide').text('Contact Name*');
                $('#contact_email_hide').text('Contact Email Address*');
                $('.create-account h4').text('Register as an Artist');
                $('.create-account p').text('Register and start sharing your music');
            }
        });
        // $(document).on('change','select.select_country',function(){

        // })
        function stateTextDropdown() {
            var countryArr = ['United States','Canada'];
            var val = $('select.select_country').val();
            if (jQuery.inArray(val, countryArr) !== -1) {
                $('.show_states_select').removeClass('d-none').find('select').removeAttr('disabled');
                // $('.show_states_input').addClass('d-none').find('input').attr('disabled', 'disabled');
            } else {
                $('.show_states_select').addClass('d-none').find('select').attr('disabled', 'disabled');
                // $('.show_states_input').removeClass('d-none').find('input').removeAttr('disabled');
            }
        }

        // $(document).on('change','#role',function(){
        //   var value = $(this).val();
        //   if(value=='3'){
        //     window.location.assign('{{ route('showSignup') }}');
        //   }
        //   else{
        //     window.location.assign('{{ route('showArtistSignup') }}');
        //   }

        // });

        function submitForm() {
            $("#signupForm").submit();
            return true;
        }
    </script>
@endsection
