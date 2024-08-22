@section('title', 'Upcoming Events')
@extends('frontend.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('public/assets/frontend/css/bootstrap-datepicker.min.css')}}">
@endsection
@section('content')
    <!--------------------------
                UPCOMING EVENTS START
        --------------------------->
    <div class="container">
        <div class="create-new-events-page">
            <h5>{{ isset($content) ? 'Edit' : 'Create New' }} Event</h5>
            <span class="blur-color">Fill out the details and let your Fans know about the
                {{ isset($content) ? '' : 'new' }} event</span>
            <div class="PGP-wrapper">
                @if (isset($content))
                    <form class="row" method="POST" id="event-form"
                        action="{{ route('artistEventUpdate', $content->id) }}" enctype="multipart/form-data">
                    @else
                        <form class="row" method="POST" id="event-form" action="{{ route('artistEventStore') }}"
                            enctype="multipart/form-data">
                @endif
                @csrf
                <input type="hidden" value="{{ isset($content->id) ? false : true }}" name="eventId">
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="control-group file-upload" id="file-upload1">
                        <input type="hidden" class="hiddenPreviewImg" name="hiddenPreviewImg" value="" />
                        <div class="image-box text-center">
                            <img src="" alt="" class="input-img-sec">
                            <img src="{{ asset('public/assets/frontend/img/cne-img.svg') }}" alt=""
                                class="height-img" />
                        </div>
                        <div class="controls" style="display: none;">
                            <input type="file" name="banner_image" id="imageUpload1" class="imageFile" />
                        </div>
                    </div>
                    <div class="banner-error"></div>
                </div>
                <div class="col-12 col-sm-12 col-md-6">
                    <div class="CNE-fields">
                        <p class="s1">Cover Photo</p>
                        <span class="blur-color">Set cover photo for your event <br>jpg or jpeg . Max 2MB<br>Image size
                            should be {{ config('app.artistEvent.width') }} X {{ config('app.artistEvent.height') }}
                            px</span>

                        <div>
                            <div class="inputs-group">
                                <input type="text" name="name"
                                    value="{{ isset($content->name) ? $content->name : '' }}" />
                                <span>Event Title*</span>
                            </div>
                            <div class="inputs-group">
                                <textarea
                                    name="description">{{ isset($content->description) ? $content->description : '' }}</textarea>
                                <span>Event Description*</span>
                            </div>
                            <div class="inputs-group location-field">
                                <a href="javascript:void(0)"><img
                                        src="{{ asset('public/assets/frontend/img/location-red.svg') }}" alt="" /></a>
                                <input type="text" name="location"
                                    value="{{ isset($content->location) ? $content->location : '' }}" />
                                <span>Event Location*</span>
                            </div>
                            <div class="inputs-group location-field">
                                <a href="javascript:void(0)"><img
                                        src="{{ asset('public/assets/frontend/img/link.svg') }}" alt="" /></a>
                                <input type="text" name="location_url"
                                    value="{{ isset($content->location_url) ? $content->location_url : '' }}" />
                                <span>Event Location URL</span>
                            </div>
                            <div class="inputs-group">
                                <label for="datepicker" class="date-icon">
                                    <img src="{{ asset('public/assets/frontend/img/date-red.svg') }}" alt="" />
                                </label>
                                <input type="text" id="datepicker" name="date"
                                    value="{{ isset($content->date_form) ? $content->date_form : date('Y-m-d') }}">
                                <span>Date of Event*</span>
                                <!-- <label class="error">Error</label> -->
                            </div>
                            <div class="date-group">
                                <label for="timepicker" class="date-icon">
                                    <img src="{{ asset('public/assets/frontend/img/time-icon.svg') }}" alt="" />
                                </label>
                                <input type="text" id="timepicker" name="time" class="timepicker"
                                    value="{{ isset($content->time) ? date('H:i',strtotime($content->time)) : date('H:i A') }}">
                                <span>Select Time*</span>
                                <!-- <div id="timepicker"></div> -->
                            </div>
                        </div>
                        @if (isset($content))
                            <button type="submit" class="fill-btn">Update Event</button>
                        @else
                            <button type="submit" class="fill-btn">Create Event</button>
                        @endif
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    @include('frontend.components.artist-profile.crop-image-modal')
    <!--------------------------
                UPCOMING EVENTS END
        --------------------------->
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/bootstrap-datepicker.min.js') }}"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
    <script
        src='https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js'>
    </script>
    @if (isset($content->banner))
        <script type="text/javascript">
            var imageVal = "{{ $content->banner }}";
            if (imageVal) {
                $('.image-box').css('background-image', 'url(' + imageVal + ')');
                // $('.input-img-sec').attr("src", imageVal).show().siblings("p").hide().parent().css("background", "transparent");
            }
        </script>
    @endif
    <script>
        $(function() {
            // $('#timepicker').data("DateTimePicker").show();
            $("#timepicker").datetimepicker({
                // use24hours: true,
                format: 'HH:mm',
                // keepOpen: true,
                // inline: true,
                // debug: true,
                icons: {
                    up: "fa fa-chevron-up",
                    down: "fa fa-chevron-down"
                }
            });

            $('#event-form').validate({
                ignore: [],
                rules: {
                    banner_image: {
                        required: function() {
                            if ($('input[name="eventId"]').val()) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    // banner_image:{required:'input[name="eventId"]'val()},
                    name: "required",
                    description: "required",
                    location: "required",
                    location_url: {
                        url: true
                    },
                    date: "required",
                    time: "required"
                },
                messages: {
                    banner_image: "Please select banner image",
                    name: "Please enter event title",
                    description: "Please enter description",
                    location: "Please enter location",
                    location_url: {
                        url: 'Please enter valid url'
                    },
                    date: "Please enter date of event",
                    time: "Please enter time of event"
                },
                errorPlacement: function(error, element) {
                    if (element.prop("type") === "file") {
                        error.appendTo('.banner-error');
                    } else {
                        error.appendTo(element.parent());
                    }
                }
            })
        });




        // INCLUDE JQUERY & JQUERY UI 1.12.1
        $(function() {
            $("#datepicker").datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
        });

        $(".image-box").click(function(event) {
            $('.imageFile').trigger('click')
        });

        var $modal = $('#modal-crop-image');
        var image = document.getElementById('image');
        // alert(image);
        var cropper;
        $(document).on('change', '.imageFile', function(e) {
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
            console.log(image);
            cropper = new Cropper(image, {
                aspectRatio: 350 / 248,
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
                // data: {
                //     width: 100,
                //     height: 400,
                // },
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
                    $('.image-box').css('background-image', 'url(' + base64data + ')');
                    $('.hiddenPreviewImg').val(base64data);
                    //console.log(base64data);
                    $modal.modal('hide');
                }
            });
        });
        $(document).on('blur', 'input[name="location_url"]', function() {
            var url = $(this).val();
            if (!~url.indexOf("http")) {
                url = "https://" + url;
            }
            $(this).val(url);
            $(this).valid();
        })
    </script>
@endsection
