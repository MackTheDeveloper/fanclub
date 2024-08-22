<script src="{{ asset('public/assets/frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/owl.carousel.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/toastr.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src='https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('public/assets/frontend/js/script.js') }}?r=20220316"></script>
<script src="{{ asset('public/assets/frontend/js/developer.js')}}?r=20220316"></script>
<script src="{{ asset('public/assets/frontend/js/blockUI.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/additional-methods.min.js') }}"></script>
<!-- <script src="{{ asset('public/assets/js/vendors/form-components/form-validation.js') }}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('public/assets/frontend/js/cropper.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/choices.min.js') }}"></script>
<script src="{{ asset('public/assets/frontend/js/bootstrap-tagsinput.min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Image Modal -->
<script type="text/javascript">

    $(document).on('click','.loginBeforeGo',function(){
        $('#signUpModal').modal('show');
        return false;
    })

    $(document).ready(function(e) {

        /* $('.loginBeforeGo').click(function() {
            $('#signUpModal').modal('show');
            return false;
        }) */

        if ($('body').hasClass('dark-theme')) {
            $('input.dark-thene-checkbox').prop('checked', true);
        }
    });
    $(document).on('submit', '#searchFront,#searchFront2', function(e) {
        e.preventDefault();
        var search = $(this).find('input[name="search"]').val();
        window.location.href = "{{ url('search') }}/" + search;
    });



    $(document).on('change', '.artistLikeDislike', function() {
        // artistLikeDislike
        var artistId = $(this).attr('data-id');
        if (artistId) {
            $.ajax({
                url: "{{ route('artistLikeDislike') }}",
                method: 'post',
                data: 'artist_id=' + artistId + '&_token={{ csrf_token() }}',
                success: function(response) {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.success(response.message);
                }
            })
        }
    });
    $(document).on('click', '#removerecent', function() {
        // artistLikeDislike
        var fansearchId = $(this).attr('data-id');
        if (fansearchId) {
            $.ajax({
                url: "{{ route('fanSearchTagRemove') }}",
                method: 'post',
                data: 'fansearchId=' + fansearchId + '&_token={{ csrf_token() }}',
                success: function(response) {
                    console.log(response);
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.options.timeOut = 0;
                    toastr.success(response.message);
                    $('.search-wrapper').empty();
                    $.each(response.component.recentSearchListData.recentSearchdata, function(k,
                        v) {
                        $(".search-wrapper").append(
                            '<div class="searching-data"><a href="javascript:void(0);" id="searchtag" data-id="' +
                            v.keyword + '"><span>' + v.keyword +
                            '</span></a><a href="#" id="removerecent" data-id="' + v
                            .id +
                            '"><img src="{{ asset('public/assets/frontend/img/close-small.svg') }}" alt="" /></a></div>'
                        );
                    });
                }
            })
        }
    });
    $(document).on('click', '#searchtag', function() {
        // artistLikeDislike
        var searchTag = $(this).attr('data-id');
        if (searchTag) {
            $('#searchFront input[name="search"]').value = searchTag;
            window.location.href = "{{ url('search') }}/" + searchTag;
        }
    });
    $(document).on('change', '.songLikeDislike', function() {
        // songLikeDislike
        var songId = $(this).attr('data-id');
        if (songId) {
            $.ajax({
                url: "{{ route('songLikeDislike') }}",
                method: 'post',
                data: 'song_id=' + songId + '&_token={{ csrf_token() }}',
                success: function(response) {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.success(response.message);
                }

            })
        }
    });


    $(document).on('change', '.groupLikeDislike', function() {
        // groupLikeDislike
        var groupId = $(this).attr('data-id');
        if (groupId) {
            $.ajax({
                url: "{{ route('groupLikeDislike') }}",
                method: 'post',
                data: 'group_id=' + groupId + '&_token={{ csrf_token() }}',
                success: function(response) {
                    toastr.clear();
                    toastr.options.closeButton = true;
                    toastr.success(response.message);
                }
            })
        }
    });

    $(document).on('change', '.dark-thene-checkbox', function() {

        var thhemeType = "light";
        if ($(this).prop('checked')) {
            $('body').addClass('dark-theme');
            thhemeType = "dark";
            $('input.dark-thene-checkbox').prop('checked', true);
        } else {
            $('body').removeClass('dark-theme');
            thhemeType = "light";
            $('input.dark-thene-checkbox').prop('checked', false);
        }
        darkLightToggle(thhemeType);
    })

    function darkLightToggle(thhemeType) {
        localStorage.setItem('fanclubtheme', thhemeType);
        $.ajax({
            url: "{{ route('themeToggle') }}",
            method: 'post',
            data: 'theme=' + thhemeType + '&_token={{ csrf_token() }}',
            success: function(response) {
                // $('.filteredSongList').html(response);
            }
        })
    }

    $(document).on('change', '.allow-message-checkbox', function() {
			var allowMessage = '';
			if ($(this).prop('checked')) {
				allowMessage = 1;
			}else{
				allowMessage = 0;
			}
        $.ajax({
            url: "{{ route('allowMessage') }}",
            method: 'post',
            data: 'allowMessage=' + allowMessage + '&_token={{ csrf_token() }}',
            success: function(response) {
                toastr.clear();
                toastr.options.closeButton = true;
                toastr.success(response.message);
            }
        })
    })

    $("#loginFormFromPopup").validate({
            ignore: [],
            rules: {
                email: "required",
                password: "required",
            },
            messages: {
                email: "Email is required",
                password: "Password is required"
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            window.location = '{{ url('/') }}';
                        } else if (response.statusCode ==
                            '201') { // Redirect to the artist dashboard
                            window.location = '{{ route('ArtistDashboard') }}';
                        } else if (response.statusCode ==
                            '202') { // Redirect to the signup form for fan
                            window.location = '{{ route('showSignupFan') }}';
                        } else {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });

        $("#loginWithOtpFormFromPopup").validate({
            ignore: [],
            rules: {
                input: "required",
            },
            messages: {
                input: "Email is required",
            },
            submitHandler: function(form) {
                $('.loader-bg').removeClass('d-none');
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('.loader-bg').addClass('d-none');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.message);

                            $('#loginWithOtpModal').modal('hide');
                            $('#loginWithOtpVerificationModal').modal('show');
                            $('#loginWithOtpVerificationModal .opt-email-popup-text').text(response
                                .component.input);
                            $('#loginWithOtpVerificationModal .opt-email-popup-value').val(response
                                .component.input);
                        } else {
                            $('.loader-bg').addClass('d-none');

                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });
        
        $(document).on('submit','#resendOtpFormFromPopup',function(e){
            e.preventDefault();
            $('.loader-bg').removeClass('d-none');
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.statusCode == '200') {
                        $('.loader-bg').addClass('d-none');
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.message);

                        $('#loginWithOtpModal').modal('hide');
                        $('#loginWithOtpVerificationModal').modal('show');
                        $('#loginWithOtpVerificationModal .opt-email-popup-text').text(response
                            .component.input);
                        $('#loginWithOtpVerificationModal .opt-email-popup-value').val(response
                            .component.input);
                    } else {
                        $('.loader-bg').addClass('d-none');

                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.error(response.component.error);
                    }
                }
            });
        })

        $("#loginWithOtpVerificationFormFromPopup").validate({
            rules: {
                "digit-1": "required",
                "digit-2": "required",
                "digit-3": "required",
                "digit-4": "required"
            },
            messages: {
                "digit-1" : {
                    required: "OTP is required"
                },
                "digit-2" : {
                    required: "OTP is required"
                },"digit-3" : {
                    required: "OTP is required"
                },"digit-4" : {
                    required: "OTP is required"
                }
            },
            errorPlacement: function ( error, element ) {
                $('.otp-error').text('');
                error.appendTo('.otp-error');
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            window.location = '{{ url('/') }}';
                        } else {
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });

        $("#forgotPasswordFormFromPopup").validate({
            ignore: [],
            rules: {
                email: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: "Email is required"
                }
            },
            submitHandler: function(form) {
                $('.loader-bg').removeClass('d-none');
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('.loader-bg').addClass('d-none');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.message);

                            $('#forgotPasswordModal').modal('hide');
                        } else {
                            $('.loader-bg').addClass('d-none');

                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.error(response.component.error);
                        }
                    }
                });
            }
        });

</script>
@yield('footscript')
