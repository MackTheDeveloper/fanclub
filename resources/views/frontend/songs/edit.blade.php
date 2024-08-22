@section('title', 'Update Song Details')
@extends('frontend.layouts.master')
@section('content')
<!-- My Reviews Songs Page starts here -->
<div class="uploadsongs-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12 uploadsong-heading">
                <h5>Update Song Details</h5>
                {{-- <span class="blur-color">Complete the steps below to upload your song and share your music with the
                    fanclub community.</span> --}}
            </div>
            <form id="song-upload-form" method="POST" enctype="multipart/form-data" action="{{ route('SongUpdate') }}" style="width: 100%">
                @csrf
                <input type="hidden" value="{{$content->id}}" name="song_id">
                <div class="col-md-12">
                    <div class="info-performanceform">
                        <div class="performace-forminner">

                            <div class="row">
                                <div class="col-sm-12 col-md-6 mb-24">
                                    <div class="reviewthumbnail-upload">
                                        <input type="hidden" class="hiddenPreviewImg" name="hiddenPreviewImg" value="" />
                                        <img class="open-icon-select" src="{{ $content->icon?:asset('public/assets/frontend/img/all-upload.svg') }}" alt="" />
                                        <input type="file" name="song_icon" id="imageUpload1" class="drop-zone__input image d-none">
                                        <div class="thumbnail-drop-heads">
                                            <p class="s1">Thumbnail</p>
                                            <span class="blur-color">Set thumbnail for your song <br>
                                                jpg or jpeg . Max 2MB<br>
                                                Image size should be {{config('app.songIconDimention.width')}} X {{config('app.songIconDimention.height')}} px
                                            </span>
                                        </div>
                                    </div>
                                    <div class="thumbnail-error" id="song_icon_err">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="perform-fields">
                                        <div class="inputs-group">
                                            <input type="text" name="name" value="{{$content->name}}">
                                            <span>Song Name*</span>
                                        </div>
                                        <div class="multi-select-group">
                                            <p>Select Categories*</p>
                                            <select name="categories[]" id="choices-multiple-remove-button" multiple>
                                                @php($selected = explode(',',$content->categories))
                                                {{-- <option value="">Select Genre*</option> --}}

                                                @foreach ($musicCategories as $key => $row)
                                                <option {{in_array($key,$selected)?"selected":""}} value="{{ $key }}">{{ $row }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="multi-select-group">
                                    <p>Select Genre*</p>
                                    <select name="genre[]" id="choices-multiple-remove-button" multiple>
                                        @foreach ($musicGenres as $key => $row)
                                        <option value="{{ $key }}">{{ $row }}</option>
                                        @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="tags-input">
                                        <input type="text" name="tag" data-role="tagsinput" class="inputMulti" value="{{$content->tag}}" />
                                        <p>Add Tags</p>
                                    </div>

                                    {{-- <div class="inputs-group">
                                                <input type="text" name="tag">
                                                <span>Tag</span>
                                            </div> --}}
                                    <button type="submit" class="fill-btn">Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
</div>
</div>

@include('frontend.components.artist-profile.crop-image-modal')
@endsection
@section('footscript')
<script type="text/javascript">
    $(document).on('click', '.open-icon-select', function() {
        $('input[name="song_icon"]').trigger('click');
    });
    $(document).on("change keyup focusout", ".bootstrap-tagsinput input", function() {
        var inputVal = $('.inputMulti').val();
        if (inputVal) {
            $('.tags-input').addClass("focus");
        } else {
            $('.tags-input').removeClass("focus");
        }
    });
    $(document).on("change", "#choices-multiple-remove-button", function() {
        var validateIcon = $('#song-upload-form').validate().element($('select#choices-multiple-remove-button'));
        if (!validateIcon)
            return false;
    });

    $(document).ready(function() {
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
        });

        if ($('.bootstrap-tagsinput').children().length > 0) {
            $(".tags-input").addClass("focus")
        }

        $("#song-upload-form").validate({
            onfocusout: function(element) {
                var $element = $(element);
                $element.valid();
            },
            ignore: [],
            rules: {
                name: "required",
                'categories[]': "required",
                hiddenPreviewImg : {
                    required: function(element){
                        return $('input[name="song_icon"]').val()!="";
                    }
                },
                song_icon: {
                    // required: false,
                    // filesize: 10,
                    extension: "jpg|jpeg|png"
                },
                song_file: {
                    required: true,
                    // filesize: 100,
                    extension: "mp4|mkv|webm"
                }
            },
            messages: {
                name: "Please enter the name",
                "categories[]": "Please select categories",
                song_icon: {
                    required: "Please upload icon",
                    extension: "Please upload icon in these format only (jpg, jpeg, png)"
                },
                hiddenPreviewImg: {
                    required: "Please select and crop image",
                },
                song_file: {
                    required: "Please upload file",
                    extension: "Please upload file in these format only (mp4, mkv)"
                }
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    if (element.prop("name") == 'song_icon') {
                        error.appendTo('#song_icon_err');
                    }else if (element.prop("name") == 'hiddenPreviewImg') {
                        error.appendTo('#song_icon_err');
                    } else if (element.prop("name") == 'song_file') {
                        error.appendTo('#song_file_err');
                    } else {
                        error.insertAfter(element);
                    }
                }
            },
        });
    });




    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'File size must be less than {0} MB');

    var $modal = $('#modal-crop-image');
    var image = document.getElementById('image');
    var cropper;

    $("body").on("change", ".image", function(e) {
        var validateIcon = $('#song-upload-form').validate().element(':input[name="song_icon"]');
        if (!validateIcon)
            return false;
        var ext = $(this).val().substring($(this).val().lastIndexOf('.') + 1).toLowerCase();
        /*  if (ext != 'png' && ext != 'jpg' && ext != 'jpeg') {
             $(this).val('');
             alert('Please select valid file (png,jpg,jpeg)');
             return false;
         } */

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
                //$('.drop-zone__thumb').css('display', 'block');
                //$('.drop-zone__thumb').css('background-image', 'url(' + base64data + ')');
                $('.open-icon-select').attr('src', base64data);
                $('.hiddenPreviewImg').val(base64data);
                //console.log(base64data);
                $modal.modal('hide');
            }
        });
    })

    /**
     * Updates the thumbnail on a drop zone element.
     *
     * @param {HTMLElement} dropZoneElement
     * @param {File} file
     */
    
    // $('form').ajaxForm({
    //     beforeSubmit: function() {
    //         return $("#song-upload-form").valid();
    //     },
    //     beforeSend: function() {
    //         $('.upload-progress').removeClass('d-none');
    //         $('form button[type="submit"]').prop('disabled', true)
    //         var percentage = '0';
    //         $('.up-width').css('width', percentage + '%');
    //         $('.up-width').attr('data-percent', percentage);
    //         $('.upper-progress-text').text(percentage + '% uploaded...');
    //     },
    //     uploadProgress: function(event, position, total, percentComplete) {
    //         var percentage = percentComplete;
    //         $('.up-width').css('width', percentage + '%');
    //         $('.up-width').attr('data-percent', percentage);
    //         $('.upper-progress-text').text(percentage + '% uploaded...');
    //         if (percentage == '100') {
    //             setTimeout(function() {
    //                 $('.upper-progress-text').text('Almost Done...');
    //             }, 2000)
    //         }
    //         // $('.progress .progress-bar').css("width", percentage+'%', function() {
    //         //   return $(this).attr("aria-valuenow", percentage) + "%";
    //         // })
    //     },
    //     complete: function(response) {
    //         $('form button[type="submit"]').prop('disabled', false)
    //         $('.upper-progress-text').text('Uploaded. Please wait..');
    //         // console.log(response);
    //         // console.log('File has uploaded');
    //         // toastr.clear();
    //         // toastr.options.closeButton = true;
    //         // toastr.success(response.message);
    //     },
    //     success: function(response) {
    //         $('.upper-progress-text').text('Uploaded. Please wait..');
    //         $('form button[type="submit"]').prop('disabled', false)
    //         // console.log(response);
    //         // console.log('File has uploaded');
    //         toastr.clear();
    //         toastr.options.closeButton = true;
    //         toastr.success(response.message);
    //         setTimeout(function() {
    //             window.location.reload()
    //         }, 2000)
    //     }
    // });
</script>
@endsection