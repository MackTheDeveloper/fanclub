@section('title', 'Fan Edit Profile')
@extends('frontend.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/jquery.ccpicker.css') }}">
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
    <style type="text/css">
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }

    </style>
    <!--------------------------
                                            EDIT PROFILE IN START
                                        --------------------------->

    <div class="edit-profile">
        <div class="sub-banner">
            <img src="{{ asset('public/assets/frontend/img/g-banner.png') }}" alt="" />
        </div>

        <form action="{{ url('updateFanProfile') }}" enctype="multipart/form-data" method="POST" id="updateFan">
            <div class="profile-content">
                <input type="hidden" class="hiddenPreviewImg" name="hiddenPreviewImg" value="" />
                <div class="avatar-upload">
                    <div class="avatar-edit">
                        <input type='file' id="imageUpload1" name="profile_pic" accept=".png, .jpg, .jpeg"
                            class="image" />
                        <label for="imageUpload1"></label>
                    </div>
                    <div class="avatar-preview">
                        <div id="imagePreview" class="previewImg"
                            style="background-image: url({{ $profileData->profilePhoto }});">
                        </div>
                    </div>
                </div>
                <h4>{{ $profileData->firstName }} {{ $profileData->lastName }}</h4>
                @if ($profileData->introducer)
                    <p class="blur-color">Introducer - {{ $profileData->introducer }}</p>
                @endif
                @csrf
                <div>
                    <div class="inputs-group">
                        <input type="text" name="firstname" value="{{ $profileData->firstName }}">
                        <span>Your firstname*</span>
                        <!-- <label class="error">Error</label> -->
                    </div>
                    {{-- <div class="inputs-group">
                        <input type="text" name="lastname" value="{{ $profileData->lastName }}">
                        <span>Your lastname*</span>
                        <!-- <label class="error">Error</label> -->
                    </div> --}}
                    <div class="inputs-group">
                        <input readonly type="email" name="email" value={{ $profileData->email }}>
                        <span>Email Address*</span>
                        <!-- <label class="error">Error</label> -->
                    </div>
                    <div class="label-select">
                        <span>Country of Residence*</span>
                        <select name="country" class="select_country" id="country">
                            <option value="">Select</option>
                            @foreach ($countries as $key => $row)
                                <option value="{{ $row['value'] }}" @if ($profileData->country == $row['value']) selected="selected" @endif>{{ $row['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div
                        class="label-select label-select show_states_select {{ (in_array($profileData->country ,['United States','Canada'])) ? '' : 'd-none' }}">
                        <span>State*</span>
                        <select class="select select_state" name="state" id="state"
                            {{ (in_array($profileData->country ,['United States','Canada'])) ? '' : 'disabled' }}>
                            <option>Select...</option>field1
                            @foreach ($states as $key => $row)
                                <option value="{{ $row['key'] }}" @if ($profileData->state == $row['key']) selected="selected" @endif>{{ $row['value'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div
                        class="inputs-group show_states_input {{ (in_array($profileData->country ,['United States','Canada'])) ? 'd-none' : '' }}">
                        <input type="text" name="state" {{ (in_array($profileData->country ,['United States','Canada'])) ? 'disabled' : '' }}
                            value="{{ $profileData->state }}">
                        <span>State*</span>
                    </div> --}}

                    <div class="number-wrapper">
                        <div class="input">
                            <input type="text" id="phoneField1" name="phoneCode" class="phone-field" />
                        </div>
                        <div class="number-group">
                            <input type="number" name="phone" value="{{ $profileData->phone }}">
                            <span>Phone Number</span>
                            <!-- <label class="error">Error</label> -->
                        </div>
                    </div>
                    <div class="inputs-group">
                        <label for="datepicker" class="date-icon">
                            <img src="{{ asset('public/assets/frontend/img/date-icon.svg') }}" alt="" />
                        </label>
                        <input type="text" id="datepicker" name="dob" value={{ $profileData->dob }}>
                        <span>Date of Birth</span>
                        <!-- <label class="error">Error</label> -->
                    </div>
                    <div class="gender">
                        <p>Gender*</p>
                        @foreach ($profileData->gender as $key => $row)
                            <label class="rd">{{ $row->value }}
                                <input type="radio" {{ $row->selected == '1' ? 'checked' : '' }} class="introduce-radio"
                                    value="{{ $row->key }}" name="gender">
                                <span class="rd-checkmark"></span>
                            </label>
                        @endforeach
                    </div>
                    <div class="still-need-help">
                        <a href="{{ route('showSecurityQuestion') }}"><span>Manage your security questions</span></a>
                    </div>
                    <div class="still-need-help">
                        <a href="{{ route('showChangePassword') }}"><span>Change Your Password</span></a>
                    </div>
                </div>
                <button type="submit" class="fill-btn">Save Changes</button>
            </div>
        </form>
    </div>

    @include('frontend.components.artist-profile.crop-image-modal')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/jquery.ccpicker.js') }}"
        data-json-path="{{ asset('public/assets/frontend/data.json') }}"></script>
    <script src="{{ asset('public/assets/frontend/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        var prefix = "{{ $profileData->prefix }}";
        $(document).ready(function() {
            $("#phoneField1").CcPicker();
            if (prefix) {
                $("#phoneField1").CcPicker("setCountryByPhoneCode", "{{ $profileData->prefix }}");
            }
            $("#datepicker").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            
            getCountryStateDrop()
        });
        $(document).on('change', '#country', function() {
            stateTextDropdown();
            var value = $(this).val();
            countryStates(value);
        });

        function getCountryStateDrop(){
            var countryArr = ['United States','Canada'];
            var countryVal = $('#country').val();
            if (jQuery.inArray(countryVal, countryArr) !== -1) {
                var selected = "{{$profileData->state}}";
                countryStates(countryVal,selected)
            }
        }

        function countryStates(value,selected=""){
            var countryArr = ['United States','Canada'];
            // if (value == 'United States') {
            if (jQuery.inArray(value, countryArr) !== -1) {
                var token = "{{ csrf_token() }}";
                $.ajax({
                    url:"{{ route('stateList') }}",
                    method:'post',
                    // data:'country="231"&_token={{ csrf_token() }}',
                    data:{country:value,_token:token},
                    dataType : 'json',
                    success:function(response){
                        $('.select_state').empty();
                        $.each(response.component.stateListData.countries,function(k,v)
                        {
                            var selectedIs = (v.key==selected)?"selected":"";
                            var append = '<option '+selectedIs+' value="'+v.key+'">'+v.key+'</option>';
                            $(".select_state").append(append);
                        });
                    }
                });
            }
        }

        function stateTextDropdown() {
            var countryArr = ['United States','Canada'];
            var value = $('select.select_country').val();
            if (jQuery.inArray(value, countryArr) !== -1) {
            // if ($('select.select_country').val() == 'United States') {
                $('.show_states_select').removeClass('d-none').find('select').removeAttr('disabled');
                // $('.show_states_input').addClass('d-none').find('input').attr('disabled', 'disabled');
            } else {
                $('.show_states_select').addClass('d-none').find('select').attr('disabled', 'disabled');
                // $('.show_states_input').removeClass('d-none').find('input').removeAttr('disabled');
            }
        }

        var $modal = $('#modal-crop-image');
        var image = document.getElementById('image');
        var cropper;

        $("body").on("change", ".image", function(e) {
            var ext = $(this).val().substring($(this).val().lastIndexOf('.') + 1).toLowerCase();
            if (ext != 'png' && ext != 'jpg' && ext != 'jpeg') {
                $(this).val('');
                alert('Please select valid file (png,jpg,jpeg)');
                return false;
            }

            var files = e.target.files;
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                //autoCropArea: 0,
                responsive: true,
                dragMode: 'none',
                strict: true,
                guides: false,
                rounded: true,
                highlight: true,
                viewMode: 3,
                preview: '.preview',
                movable: false,
                resizable: false,
                cropBoxResizable: true,
                /* data: {
                    width: 400,
                    height: 400,
                }, */
                dragCrop: false,
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                /* width: 1000,
                height: 1000, */
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $('.previewImg').css('background-image', 'url(' + base64data + ')');
                    $('.hiddenPreviewImg').val(base64data);
                    //console.log(base64data);
                    $modal.modal('hide');
                }
            });
        })
        $(document).ready(function() {
            $("#updateFan").validate({
                rules: {
                    "firstname": {
                        required: true
                    },
                    "state": {
                        required: true
                    },
                    "country": {
                        required: true
                    },
                    "dob": {
                        required: true
                    },
                    "phone": {
                        required: true,
                        minlength: 7,
                        maxlength: 15
                    },
                    "email": {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    "firstname": {
                        required: 'Please enter your firstname'
                    },
                    "state": {
                        required: 'Please enter a state'
                    },
                    "country": {
                        required: 'Please select a country'
                    },
                    "email": {
                        required: 'Please enter a valid email address'
                    },
                    "phone": {
                        required: 'Please enter your phone',
                        minlength: 'Please enter a min 7 digit phone number',
                        maxlength: 'Please enter a max 10 digit phone number'
                    },
                    "dob": {
                        required: 'Please enter your date of birth'
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
            });
        });
    </script>
@endsection
