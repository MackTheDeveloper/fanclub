@section('title', $content['artistImage']->artistImageData->name)
@extends('frontend.layouts.master')
@section('content')
@php
$authCheck = Auth::check();
$authRoleMain = $authCheck ? Auth::user()->role_id : 0;
$authRole = getAuthProps();
@endphp
    <!--------------------------
                    ARTIST DETAIL START
            --------------------------->

    <div class="upcoming-event artist-details">
        <div class="header-gradient">
            <div class="container">
                <div class="header-content">
                    <div class="breadCrums">
                        <ul>
                            <li><a href="{{ url('/') }}">fanclub</a></li>
                            <li><a href="{{ route('allArtists') }}">Artists</a></li>
                            <li>{{ $content['artistImage']->artistImageData->name }}</li>
                        </ul>
                    </div>
                    <div class="header-img-data">
                        <img src="{{ $content['artistImage']->artistImageData->image }}" alt="" class="big-img" />
                        <div class="data-wrapper">
                            <h3>{{ $content['artistImage']->artistImageData->name }}</h3>
                            <div class="btn-block">
                                @if ($authCheck && $authRole==3 && $id!=Auth::user()->id)
                                <a href="javascript:void(0)" class="fill-btn sendMessageNow">Send Message</a>
                                <label class="heart">
                                    @php($disabled = $authCheck ? '' : "disabled='disabled'")
                                    @php($checked = $content['artistImage']->artistImageData->isFav ? 'checked' : '')

                                    <input {{ $checked }} type="checkbox" {{ $disabled }} class="artistLikeDislike"
                                        value="yes" name="heart" data-id="{{ $id }}">
                                    <span class="heart-checkmark"></span>
                                </label>
                                @else
                                    <a href="{{route('showSignup', $content['artistDetail']->artistDetailData->artistReferSignUp)}}" class="fill-btn subscribeNow">Subscribe</a>
                                @endif
                                {{-- <a href="" class="more-btn">
                                <img src="{{asset('public/assets/frontend/img/More.svg')}}" alt="" />
                            </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($content['banner']->bannerData->list) && count($content['banner']->bannerData->list)>3)
        <div class="container">
            <div class="header-carousel artist-c">
                <div class="owl-carousel owl-theme custom-cara">
                    @foreach ($content['banner']->bannerData->list as $item)
                    <a href="javascript:void(0)" class="item">
                        <img src="{{ $item->file }}">
                    </a>
                    @endforeach
                    {{-- <a href="" class="item">
                        <img src="{{ asset('public/assets/frontend/img/MainBanner.png') }}">
                    </a>
                    <a href="" class="item">
                        <img src="{{ asset('public/assets/frontend/img/MainBanner.png') }}">
                    </a>
                    <a href="" class="item">
                        <img src="{{ asset('public/assets/frontend/img/MainBanner.png') }}">
                    </a>
                    <a href="" class="item">
                        <img src="{{ asset('public/assets/frontend/img/MainBanner.png') }}">
                    </a>
                    <a href="" class="item">
                        <img src="{{ asset('public/assets/frontend/img/MainBanner.png') }}">
                    </a> --}}
                </div>
            </div>
        </div>
        @endif

        <div class="container">
            <div class="about">
                <div class="about-content-header">
                    <h5>About</h5>
                </div>
                <p class="blur-color toggle-content toggle-apply">{!! nl2br(e($content['artistDetail']->artistDetailData->aboutFullDesc)) !!}</p>
                <a href="javascript:void(0)" class="a toggle-about">Read More</a>
            </div>
            {{-- <div class="about">
            <h5>About</h5>
            <span>{!! $content['artistDetail']->artistDetailData->aboutFullDesc !!}</span>
        </div> --}}
            <div class="news">
                <div class="flex-beetwen">
                    <h5>News</h5>
                    @if ($content['news']->newsData->list)
                        <a class="a"
                            href="{{ route('artistNewsList', $content['artistDetail']->artistDetailData->slug) }}">See
                            All</a>
                    @endif
                </div>
                {{-- <h5>News</h5> --}}
                <div class="row">
                    @if ($content['news']->newsData->list)
                        @foreach ($content['news']->newsData->list as $key => $row)
                            <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                <div class="news-box-border">
                                    <div class="news-box">
                                        <h6 class='news-title'>{{ $row->name }}</h6>
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
                            <p>{{ $content['news']->newsData->artistNewsNotFoundMsg }}</p>
                        </div>
                    @endif
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
                                    <div class="date-box">
                                        <input type="hidden" name="dateValue" value="{{ $row->date }}">
                                        <p class="s1">{{ date('d', strtotime($row->date)) }}</p>
                                        <span>{{ date('M', strtotime($row->date)) }}</span>
                                    </div>
                                    <div class="title-content">
                                        <p class="s2">{{ $row->name }}</p>
                                        <span class="t-content">{{ $row->description }}</span>
                                        <div class="time-location">
                                            <a class="location" href="{{$row->location_url?:'javascript:void(0)'}}" {{$row->location_url?'target="_blank"':''}}>
                                                <img src="{{ asset('public/assets/frontend/img/location.svg') }}"
                                                    alt="" />
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
                            <p>{{ $content['upcomingEvent']->upcomingEventData->artistEventNotFoundMsg }}</p>
                        </div>
                    @endif
                </div>
            </div>
            {{-- @if ($authCheck && $authRole==3) --}}
                <div class="search-section">
                    <div class="search-box">
                        <button><img src="{{ asset('public/assets/frontend/img/search.svg') }}" alt="" /></button>
                        <input type="text" name="searchSong" class="searchSong" placeholder="Search Songs" />
                    </div>
                </div>
                <div class="songs-data">
                    <div class="row-5 filteredSongList">
                        @foreach ($content['artistSongList']->artistSongListData->list as $key => $row)
                            @include('frontend.components.song-grid',['songId' => $row->id,'icon' => $row->icon,'name' =>
                            $row->name,'artistName' =>
                            $row->artist,'noViews' => $row->noViews,'noLikes' => $row->noLikes,'hideLikeViews' => 1])
                        @endforeach
                    </div>
                </div>
            {{-- @endif --}}
        </div>
    </div>
    <div class="modal fade newsPopup" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">About Me</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <span class="blur-color">20 Sep, 2021</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade newsPopup" id="upcomingEventModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ asset('public/assets/frontend/img/cancel-popup.svg') }}"></img></span>
                    </button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer text-left">
                    <div class="time-location-popup">
                        <a class="location" href="javascript:void(0)">
                            <img src="{{ asset('public/assets/frontend/img/location-black.svg') }}" alt="" />
                            <span>Unknown</span>
                        </a>
                        <div class="time">
                            <img src="{{ asset('public/assets/frontend/img/time-black.svg') }}" alt="" />
                            <span>Undefined</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade addNewsPopup sendMsgPopup" id="newChatInitiate" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Message to
                        {{ $content['artistImage']->artistImageData->name }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img
                                src="{{ url('public/assets/frontend/img/cancel-popup.png') }}" /></span>
                    </button>
                </div>
                <form id="chatInitiate" class="modal-body" method="POST" action="{{ route('intiateChat') }}">
                    @if ($content['artistDetail']->artistDetailData->allowMessage == 1)
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $id }}">
                        <div class="inputs-group">
                            <textarea name="message" id="message"></textarea>
                            <span>Message*</span>
                        </div>
                        <div class="m-footer">
                            <button type="button" class="border-btn" data-bs-dismiss="modal">Cancel</button>
                            <button class="fill-btn">Send</button>
                        </div>
                    @else
                        <h6>{{ $content['artistDetail']->artistDetailData->messageToArtistNotAllowed }}</h6>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <!--------------------------
                    ARTIST DETAIL END
            --------------------------->
    @include('frontend.components.music-player.form-for-single-song')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
    <script type="text/javascript">
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
            if (location_url!='javascript:void(0)') {
                $('#upcomingEventModal .modal-footer .location').attr('href',location_url).attr('target','_blank');
            }
            $('#upcomingEventModal .modal-footer .time span').text(date + ' ' + time);
            $('#upcomingEventModal .modal-header h5').text(title);
        });
        $(document).on('click', '.sendMessageNow', function() {
            $('#newChatInitiate').modal('show');
        });

        // $(document).on('submit','#chatInitiate',function(e){
        //     e.preventDefault();
        // });

        $("#chatInitiate").validate({
            ignore: [],
            rules: {
                message: "required",
            },
            messages: {
                message: "Message is required",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        toastr.clear();
                        toastr.options.closeButton = true;
                        toastr.success(response.message);
                        $('#newChatInitiate').modal('hide');
                        window.location.href =
                            "{{ route('chatModule', $content['artistDetail']->artistDetailData->slug) }}"
                    }
                });
                return false;
                // if (grecaptcha.getResponse()) {
                //         // 2) finally sending form data
                //         form.submit();
                // }else{
                //         // 1) Before sending we must validate captcha
                //     grecaptcha.reset();
                //     grecaptcha.execute();
                // }           
            }
        });

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
        })
        $(document).on('change keyup', '.searchSong', function() {
            var value = $(this).val();
            var artistId = "{{ $id }}";
            $.ajax({
                url: "{{ route('filterSongs') }}",
                method: 'post',
                data: 'search=' + value + '&filter[artist_id]=' + artistId +
                    '&_token={{ csrf_token() }}',
                success: function(response) {
                    $('.filteredSongList').html(response);
                }
            })
        });
    </script>
@endsection
