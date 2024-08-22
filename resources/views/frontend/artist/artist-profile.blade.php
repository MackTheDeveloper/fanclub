@section('title', 'Artist Profile')
@extends('frontend.layouts.master')
@section('content')
    <!--------------------------
            UPCOMING EVENTS START
    --------------------------->

    <div class="banner-headeroverlay bottom-padding">
        <div class="sub-banneroverlay">
            <img src="{{ asset('public/assets/frontend/img/g-banner.png') }}" alt="" />
        </div>
        <div class="baneroverlay-content">
            <div class="container">
                <div class="avatar-overlay">
                    <div class="avatarover-preview">
                        <div style="background-image: url({{ $content['artistImage']->artistImageData->image }});">
                        </div>
                    </div>
                </div>
                <div class="avtars-content">
                    <h4>{{ $content['artistImage']->artistImageData->name }}</h4>
                    <button class="border-btn edit-profile-btn">Edit Profile</button>
                    <div class="avtars-likesong">
                        <div class="avtars-likehead">
                            <img src="{{ asset('public/assets/frontend/img/blankheart.svg') }}" />
                            <div>
                                <h6>{{ $content['artistDetail']->artistDetailData->numLikes }}</h6>
                                <span>Likes</span>
                            </div>
                        </div>
                        <span class="horiavtarline"></span>
                        <div class="avtars-sonhead">
                            <img src="{{ asset('public/assets/frontend/img/musics.svg') }}" />
                            <div>
                                <h6>{{ $content['artistDetail']->artistDetailData->numSongs }}</h6>
                                <span>Songs</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="artist-profile-slider artist-details">
        <div class="container">
            @if(count($content['banner']->bannerData->list)<=3)
                <div class="artist-profile-banner">
                    <div class="row">
                        @foreach ($content['banner']->bannerData->list as $item)
                        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="this-img-wrapper">
                                <a class="deleteBanner" data-id="{{ $item->key }}" href="javascript:void(0)">
                                    <img src="{{ asset('public/assets/frontend/img/delete.svg') }}" alt="" />
                                </a>
                                <img src="{{ $item->file }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="header-carousel artist-c">
                    <div class="owl-carousel owl-theme custom-cara">
                        @foreach ($content['banner']->bannerData->list as $item)
                        <div class="item">
                            <a class="deleteBanner" data-id="{{ $item->key }}" href="javascript:void(0)">
                                <img src="{{ asset('public/assets/frontend/img/delete.svg') }}" alt="" />
                            </a>
                            <img src="{{ $item->file }}">
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="text-center">
                <button class="fill-btn create-banner add-banner-btn">Add Banner</button>
            </div>
        </div>
    </div>
    <div class="my-profile-artists">
        <div class="container">
            {{-- <div class="my-collection-section">
                <h5>My Collection</h5>
                <div class="collection-imgs">
                    @foreach ($content['recent']->recentData->list as $item)
                        <div class="c-img-block open-video-player" data-slug="{{$item->slug}}">
                            <img src="{{$item->icon}}" class="collection-store-img" alt="" />
                            <img src="{{asset('public/assets/frontend/img/play-icons.png')}}" class="play-icons" alt="" />
                        </div>                    
                    @endforeach
                    <div class="thumbnail-dropzone c-img-thumb">
                        <a class="drop-zone" href="{{ route('SongUploadView') }}"></a>
                    </div>
                </div>
            </div> --}}

            <div class="about">
                <div class="about-content-header">
                    <h5>About</h5>
                    <a href="javascript:void(0)" class="a openBioPopup" data-toggle="modal"
                        data-target="#exampleModalCenter">Edit</a>
                </div>
                <p class="blur-color about-us-content toggle-content toggle-apply">{!! nl2br(e($content['artistDetail']->artistDetailData->aboutFullDesc)) !!}</p>
                <a href="javascript:void(0)" class="a toggle-about">Read More</a>
            </div>
            <div class="news">
                <div class="flex-beetwen">
                    <h5>News</h5>
                    {{-- <a class="a" href="news.html">See All</a> --}}
                    @if ($content['news']->newsData->list)
                        <a class="a"
                            href="{{ route('artistNewsList', $content['artistDetail']->artistDetailData->slug) }}">See
                            All</a>
                    @endif
                </div>
                <div class="row">
                    @if ($content['news']->newsData->list)
                        @foreach ($content['news']->newsData->list as $key => $row)
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="news-box-border">
                                    <div class="news-box">
                                        <div class="news-edit-sec">
                                            <h6 class='news-title-icon'>{{ $row->name }}</h6>
                                            <div class="dropdown c-dropdown edit-news-dropdown">
                                                <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                                    <img src="{{ asset('public/assets/frontend/img/menu-dot.svg') }}"
                                                        class="c-icon" />
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editNews" data-id="{{ $row->id }}"
                                                        href="javascript:void(0)" data-toggle="modal"
                                                        data-target="#editReviewPopup">
                                                        <img src="{{ asset('public/assets/frontend/img/edit.svg') }}"
                                                            alt="" />
                                                        <span>Edit</span>
                                                    </a>
                                                    <a class="dropdown-item deleteNews" data-id="{{ $row->id }}"
                                                        href="javascript:void(0)">
                                                        <img src="{{ asset('public/assets/frontend/img/delete.svg') }}"
                                                            alt="" />
                                                        <span>Delete</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <h6>{{$row->name}}</h6> --}}
                                        <p class="blur-color">{{ $row->description }}</p>
                                        <a href="javascript:void(0)" class="a showMore" data-toggle="modal"
                                            data-target="#newsModal">Read More</a>
                                        <span class="date">{{ $row->date }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 col-sm-12">
                            <p>No News Found.</p>
                        </div>
                    @endif
                </div>
                <div class="text-center">
                    <button class="fill-btn create-news-btn create-news">Create News</button>
                </div>
            </div>
            <div class="event-data">
                <div class="flex-beetwen">
                    <h5>Upcoming Events</h5>
                    @if ($content['upcomingEvent']->upcomingEventData->list)
                        <a class="a"
                            href="{{ route('artistEventList', $content['artistDetail']->artistDetailData->slug) }}">See
                            All</a>
                    @endif
                </div>
                <div class="row">
                    @if ($content['upcomingEvent']->upcomingEventData->list)
                        @foreach ($content['upcomingEvent']->upcomingEventData->list as $key => $row)
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="event-box">
                                    <img src="{{ $row->banner }}" alt="" />
                                    <div class="date-box left-date-box">
                                        <input type="hidden" name="dateValue" value="{{ $row->date }}">
                                        <p class="s1">{{ date('d', strtotime($row->date)) }}</p>
                                        <span>{{ date('M', strtotime($row->date)) }}</span>
                                    </div>
                                    <div class="dropdown c-dropdown round-drop-news">
                                        <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <img src="{{ asset('public/assets/frontend/img/menu-dot.svg') }}"
                                                class="c-icon" />
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('artistEventEdit', $row->id) }}">
                                                <img src="{{ asset('public/assets/frontend/img/edit.svg') }}" alt="" />
                                                <span>Edit</span>
                                            </a>
                                            <a class="dropdown-item deleteEvent" data-id="{{ $row->id }}"
                                                href="javascript:void(0)">
                                                <img src="{{ asset('public/assets/frontend/img/delete.svg') }}" alt="" />
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="title-content">
                                        <p class="s2">{{ $row->name }}</p>
                                        <span class="t-content">{{ $row->description }}</span>
                                        <div class="time-location">
                                            <a class="location" href="{{ $row->location_url ?: 'javascript:void(0)' }}"
                                                {{ $row->location_url ? 'target="_blank"' : '' }}>
                                                <img src="{{ asset('public/assets/frontend/img/location.svg') }}" alt="" />
                                                {{ $row->location }}
                                            </a>
                                            <div class="time">
                                                <img src="{{ asset('public/assets/frontend/img/time.svg') }}" alt="" />
                                                {{ $row->time }}
                                            </div>
                                            <a href="javascript:void(0)" class="a showMoreEvent" data-toggle="modal"
                                                data-target="#upcomingEventModal">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 col-sm-12">
                            <p>No Events Found.</p>
                        </div>
                    @endif
                </div>
                <div class="text-center">
                    <a href="{{ route('artistEventCreate') }}" class="fill-btn create-news-btn">Create Event</a>
                </div>
            </div>
        </div>
    </div>

    <!-- About me Popup -->
    <!-- Button trigger modal -->
    
    @include('frontend.components.artist-profile.about-modal')
    @include('frontend.components.artist-profile.view-news-modal')
    @include('frontend.components.artist-profile.view-event-modal')
    @include('frontend.components.artist-profile.add-news-modal')
    @include('frontend.components.artist-profile.delete-news-modal')
    @include('frontend.components.artist-profile.delete-event-modal')
    @include('frontend.components.artist-profile.add-banner-modal')
    @include('frontend.components.artist-profile.delete-banner-modal')
    @include('frontend.components.artist-profile.crop-image-modal')
</div>
@include('frontend.components.artist-profile.artist-player')
<!--------------------------
        UPCOMING EVENTS END
--------------------------->
@endsection
@section('footscript')
    <script>
        $(document).on('click', '.showMore', function() {
            var content = $(this).parent().find('p.blur-color').text();
            var date = $(this).parent().find('span.date').text();
            var title = $(this).parent().find('h6').text();
            $('#newsModal .modal-body p').html(nl2br(content));
            $('#newsModal .modal-footer span.blur-color').text(date);
            $('#newsModal .modal-header h5').text(title);
        });
        $(document).on('click', '.showMoreEvent', function() {
            var content = $(this).closest('.event-box').find('.t-content').text();
            var date = $(this).closest('.event-box').find('.date-box input').val();
            var time = $(this).closest('.event-box').find('.time-location .time').text().trim();
            var location = $(this).closest('.event-box').find('.time-location .location').text().trim();
            var location_url = $(this).closest('.event-box').find('.time-location .location').attr('href');
            var title = $(this).closest('.event-box').find('.title-content .s2').text();
            $('#upcomingEventModal .modal-body p').html(nl2br(content));
            $('#upcomingEventModal .modal-footer .location span').text(location);
            if (location_url != 'javascript:void(0)') {
                $('#upcomingEventModal .modal-footer .location').attr('href', location_url).attr('target',
                '_blank');
            }
            $('#upcomingEventModal .modal-footer .time span').text(date + ' ' + time);
            $('#upcomingEventModal .modal-header h5').text(title);
        });
        $(document).on('click', '.edit-profile-btn', function() {
            window.location.href = '{{ route('editProfileArtist') }}'
        });

        $(document).on('click', '.submitBio', function() {
            // $('#exampleModalCenter').modal('hide')
            $('.loader-bg').removeClass('d-none');
            var bio = $('#bio').val();
            var token = "{{ csrf_token() }}";
            $.ajax({
                url: "{{ route('ArtistDetailUpdate') }}",
                method: 'post',
                data: {
                    bio: bio,
                    _token: token
                },
                success: function(response) {
                    if (response.statusCode == '200') {
                        $('.about-us-content').html(bio.replace(/(\r\n|\n\r|\r|\n)/g, "<br>"));
                        $('#exampleModalCenter').modal('hide')
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        $('.loader-bg').addClass('d-none');
                    }
                }
            });
        });

        // News Module
        $(document).on('click', '.create-news', function() {
            $('#addNewsModal input[name="name"]').val('')
            $('#addNewsModal textarea[name="description"]').val('')
            $('#addNewsModal #exampleModalLongTitle').text('Add News');
            $('#addNewsModal form').attr('action', "{{ route('artistNewsCreate') }}");
            $('#addNewsModal').modal('show');
        });

        $("#news-add").validate({
            ignore: [],
            rules: {
                name: "required",
                description: "required",
            },
            messages: {
                name: "Please enter a title",
                description: "Please enter a description",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $(document).on('click', '.editNews', function() {
            var newsId = $(this).data('id');
            $('.loader-bg').removeClass('d-none');
            $.ajax({
                url: "{{ url('artist/news/edit') }}/" + newsId,
                // method:'post',
                // data:{bio:bio,_token:token},
                success: function(response) {
                    if (response.statusCode == '200') {
                        $('#addNewsModal input[name="name"]').val(response.component.name).addClass(
                            'has-value');
                        $('#addNewsModal textarea[name="description"]').val(response.component
                            .description).addClass('has-value');
                        $('#addNewsModal #exampleModalLongTitle').text('Edit News');
                        $('#addNewsModal form').attr('action', "{{ url('/artist/news/edit') }}/" +
                            response.component.id);
                        // $('.changeOnUpdateBio').html(bio);
                        $('#addNewsModal').modal('show');
                        $('.loader-bg').addClass('d-none');
                    }
                }
            });
        });

        $(document).on('click', '.deleteNews', function() {
            var newsId = $(this).data('id');
            // newsId
            $('#deleteNewsModal #newsId').val(newsId);
            $('#deleteNewsModal').modal('show');
        })

        $(document).on('click', '.deleteNewsConfirm', function() {
            // $('#exampleModalCenter').modal('hide')
            $('.loader-bg').removeClass('d-none');
            var newsId = $('#deleteNewsModal #newsId').val();;
            var token = "{{ csrf_token() }}";
            if (newsId) {
                $.ajax({
                    url: "{{ route('artistNewsDelete') }}",
                    method: 'post',
                    data: {
                        id: newsId,
                        _token: token
                    },
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('#deleteNewsModal').modal('hide');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            $('.loader-bg').addClass('d-none');
                        }
                    }
                });
            } else {

            }
        });

        // Event Module
        $(document).on('click', '.deleteEvent', function() {
            var eventId = $(this).data('id');
            // eventId
            $('#deleteEventModal #eventId').val(eventId);
            $('#deleteEventModal').modal('show');
        })

        $(document).on('click', '.deleteEventConfirm', function() {
            // $('#exampleModalCenter').modal('hide')
            $('.loader-bg').removeClass('d-none');
            var eventId = $('#deleteEventModal #eventId').val();;
            var token = "{{ csrf_token() }}";
            if (eventId) {
                $.ajax({
                    url: "{{ route('artistEventDelete') }}",
                    method: 'post',
                    data: {
                        id: eventId,
                        _token: token
                    },
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('#deleteEventModal').modal('hide');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            $('.loader-bg').addClass('d-none');
                        }
                    }
                });
            } else {

            }
        });

        // open video player
        $(document).on('click','.open-video-player',function(){
            var dataSlug = $(this).data('slug');
            var songAccess = "{{url('song-access')}}/"+dataSlug
            $('#popup-video source').attr('src',songAccess);
            $('#popup-video')[0].load();
            $('#playVideoModal').modal('show');
        });

        $('#playVideoModal').on('hidden.bs.modal', function () {
            document.getElementById('popup-video').pause();
        })

        // Banner Module
        $(document).on('click', '.create-banner', function() {
            // $('#addBannerModal input[name="name"]').val('')
            $('#addBannerModal #exampleModalLongTitle').text('Add Banner');
            $('#addBannerModal').modal('show');
        });

        $(document).on('click', '.deleteBanner', function() {
            var bannerId = $(this).data('id');
            // bannerId
            $('#deleteBannerModal #bannerId').val(bannerId);
            $('#deleteBannerModal').modal('show');
        })

        $(document).on('click', '.deleteBannerConfirm', function() {
            // $('#exampleModalCenter').modal('hide')
            $('.loader-bg').removeClass('d-none');
            var bannerId = $('#deleteBannerModal #bannerId').val();;
            var token = "{{ csrf_token() }}";
            if (bannerId) {
                $.ajax({
                    url: "{{ route('artistBannerDelete') }}",
                    method: 'post',
                    data: {
                        id: bannerId,
                        _token: token
                    },
                    success: function(response) {
                        if (response.statusCode == '200') {
                            $('#deleteBannerModal').modal('hide');
                            toastr.clear();
                            toastr.options.closeButton = true;
                            toastr.success(response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                            $('.loader-bg').addClass('d-none');
                        }
                    }
                });
            } else {

            }
        });


        // owl carousal
        $(document).ready(function() {
            $('.header-carousel .owl-carousel').owlCarousel({
                margin: 0,
                dots: true,
                nav: true,
                center: true,
                loop: true,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 3,
                        dots: false,
                    },
                    576: {
                        items: 3,
                        dots: false,
                    },
                    768: {
                        items: 3
                    },
                    1200: {
                        items: 3
                    }
                }
            })
        });

        var $modal = $('#modal-crop-image');
        var image = document.getElementById('image');
        var cropper;
        $(document).on('click','.image-box',function (e) {
            $('.imageFile').trigger('click')
        });
        $(document).on('change','.imageFile',function (e) {
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
                aspectRatio: 16/9,
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

    </script>
@endsection
