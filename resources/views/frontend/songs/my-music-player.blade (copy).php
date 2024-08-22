@section('title', 'Music Player')
@extends('frontend.layouts.master')
@section('content')

    <div class="my-playlist-album">
        <div class="container-fluid">
            <div class="row playlist-wrapper">
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-7 h-100">
                    <div class="song-album">
                        <div class="song-album-wrapper">
                            <div class="video-thumbnail">
                                <div class="video-header">
                                    <div class="audio-toggle-sec">
                                        <p class="s1">
                                            <hideData>Play</hideData> Audio Only
                                        </p>
                                        <div class="button r" id="button-1">
                                            <input type="checkbox" class="checkbox" id="switch">
                                            <div class="knobs"></div>
                                            <div class="layer"></div>
                                        </div>
                                    </div>
                                    <a href="" class="download-video">
                                        <img src="{{ asset('public/assets/frontend/img/download-video.svg') }}" alt="" />
                                    </a>
                                    <label class="heart video-likes">
                                        <input type="checkbox" data-id={{ $content->firstSong->data->songId }}
                                            class="songLikeDislike"
                                            {{ $content->firstSong->data->songLike == 1 ? 'checked' : '' }} value="yes"
                                            name="heart">
                                        <span class="heart-checkmark"></span>
                                    </label>
                                </div>
                                <video id='video'>
                                    <source src="{{ $content->firstSong->data->songVideo }}" type='video/mp4'>
                                </video>
                                <audio class="hidden" id="audio" src="{{ $content->firstSong->data->songVideo }}">
                                </audio>
                                <img src="{{ $content->firstSong->data->songIcon }}" class="video-placeholder" alt="" />
                                <div class="video-controller">
                                    <a href="javascript:void(0)" class="control-btn play-btn">
                                        <img src="{{ asset('public/assets/frontend/img/video-play.svg') }}" alt=""
                                            class="video-play" />
                                        <img src="{{ asset('public/assets/frontend/img/video-pause.svg') }}" alt=""
                                            class="video-pause" />
                                    </a>
                                    <div class="video-progress-wrapper">
                                        <div class="video-progress"></div>
                                    </div>
                                    <a href="javascript:void(0)" class="control-btn sound-btn">
                                        <div class="sound-btn-click">
                                            <img src="{{ asset('public/assets/frontend/img/video-sound.svg') }}"
                                                class="full-volume" alt="" />
                                            <img src="{{ asset('public/assets/frontend/img/half-volume.svg') }}"
                                                class="half-volume" alt="" />
                                            <img src="{{ asset('public/assets/frontend/img/mute-icon.svg') }}"
                                                class="mute-volume" alt="" />
                                        </div>
                                        <div class="sound-box-wrapper">
                                            <div class="sound-box">
                                                <div class="volume-progress"></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="dropdown c-dropdown settingDropdown">
                                        <button class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <img src="{{ asset('public/assets/frontend/img/video-setting.svg') }}" alt=""
                                                class="c-icon" />
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="video-main-menu">
                                                <a class="dropdown-item play-speed-link">
                                                    <div class="video-menu-img">
                                                        <img src="{{ asset('public/assets/frontend/img/Playspeed.svg') }}" alt="" />
                                                        <span>Playback Speed</span>
                                                    </div>
                                                    <div class="video-menu-content">
                                                        <span>Normal</span>
                                                        <img src="{{ asset('public/assets/frontend/img/white-right-arrow.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                                <a class="dropdown-item quality-link">
                                                    <div class="video-menu-img">
                                                        <img src="{{ asset('public/assets/frontend/img/quality.svg') }}"
                                                            alt="" />
                                                        <span>Quality</span>
                                                    </div>
                                                    <div class="video-menu-content">
                                                        <span>Auto 720p</span>
                                                        <img src="{{ asset('public/assets/frontend/img/white-right-arrow.svg') }}"
                                                            alt="" />
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="play-speed-content">
                                                <div class="inner-content-header">
                                                    <img src="{{ asset('public/assets/frontend/img/left-white-arrow.svg') }}"
                                                        alt="" class="back-to-menu">
                                                    <span>Playback Speed</span>
                                                </div>
                                                <div class="video-content-scroll">
                                                    <label class="rightCk back-to-menu"><span>0.25</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>0.5</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>0.75</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>Normal</span>
                                                        <input type="radio" value="yes" name="speed" checked>
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>1.25</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>1.5</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>1.75</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>2</span>
                                                        <input type="radio" value="yes" name="speed">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="quality-content">
                                                <div class="inner-content-header">
                                                    <img src="{{ asset('public/assets/frontend/img/left-white-arrow.svg') }}"
                                                        alt="" class="back-to-menu">
                                                    <span>Quality</span>
                                                </div>
                                                <div class="video-content-scroll">
                                                    <label class="rightCk back-to-menu"><span>720p</span>
                                                        <input type="radio" value="yes" name="quality">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>480p</span>
                                                        <input type="radio" value="yes" name="quality" checked>
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>360p</span>
                                                        <input type="radio" value="yes" name="quality">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>240p</span>
                                                        <input type="radio" value="yes" name="quality">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>144p</span>
                                                        <input type="radio" value="yes" name="quality">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                    <label class="rightCk back-to-menu"><span>Auto</span>
                                                        <input type="radio" value="yes" name="quality">
                                                        <span class="right-checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="control-btn" id="pipButton">
                                        <img src="{{ asset('public/assets/frontend/img/full-screen.svg') }}" alt="" />
                                    </a>
                                    <a href="javascript:void(0)" class="control-btn fullscreen">
                                        <img src="{{ asset('public/assets/frontend/img/screen-size.svg') }}" alt=""
                                            class="full-screen-icon" />
                                        <img src="{{ asset('public/assets/frontend/img/Exit-Full-Screen.svg') }}" alt=""
                                            class="exit-screen-icon" />
                                    </a>
                                </div>
                            </div>
                            <div class="video-bottom-content">
                                <h5>{{ $content->firstSong->data->songName }}</h5>
                                <div class="default-star-box">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $content->firstSong->generalReviews->averageRating)
                                            <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                        @else
                                            <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                                class="unfill-star" alt="">
                                        @endif
                                    @endfor
                                    {{-- <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                    <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                    <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                    <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}" alt="">
                                    <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                        class="unfill-star" alt=""> --}}
                                    <span>{{ (int) $content->firstSong->generalReviews->averageRating }}&nbsp;({{ $content->firstSong->generalReviews->totalReviews }}
                                        Reviews)</span>
                                </div>
                            </div>
                            <div class="video-artist-blog">
                                <img src="{{ $content->firstSong->data->artistIcon }}" alt="" />
                                <p class="s1">{{ $content->firstSong->data->artistName }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-5 h-100">
                    <div class="comment-list-wrapper h-100">
                        <div class="lr-carousel h-100">
                            <div class="only-list-wrapper h-100">
                                <div class="song-list">
                                    <div class="song-list-header">
                                        <h6>Next in Queue</h6>
                                    </div>
                                    <div class="song-list-wrapper">
                                        <ul id="sortable">
                                            @foreach ($content->allSongsData as $item)
                                                <li>
                                                    <a href="javascript:void(0)" class="drag-handle">
                                                        <img src="{{ asset('public/assets/frontend/img/movers.svg') }}"
                                                            alt="">
                                                    </a>
                                                    <a href="javascript:void(0)" class="song-list-img">
                                                        <img src="{{ $item->songIcon }}">
                                                    </a>
                                                    <div class="queue-name-detail">
                                                        <p class="s1">{{ $item->songName }}</p>
                                                        <span>{{ $item->artistName }}</span>
                                                    </div>
                                                    <div class="song-btn-block">
                                                        <label class="heart">
                                                            <input type="checkbox" data-id={{ $item->songId }}
                                                                class="songLikeDislike"
                                                                {{ $item->songLike == 1 ? 'checked' : '' }} value="yes"
                                                                name="heart">
                                                            <span class="heart-checkmark"></span>
                                                        </label>
                                                        @include('frontend.components.action-popup',['menus' =>
                                                        ['popupAddToPlaylist','popupDownload'],'songId' =>
                                                        $item->songId])
                                                    </div>
                                                    <span class="last-duration">{{ $item->duration }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="only-review-wrapper h-100">
                                <div class="review-list">
                                    <div class="review-list-header">
                                        <h6>Reviews</h6>
                                        <img src="{{ asset('public/assets/frontend/img/close.svg') }}"
                                            class="close-review-btn">
                                    </div>
                                    <div class="review-list-wrapper">
                                        <div class="review-and-addreview">
                                            <div class="big-review-box">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $content->firstSong->generalReviews->averageRating)
                                                        <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                                            class="unfill-star" alt="">
                                                    @endif
                                                @endfor
                                                {{-- <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                    alt="">
                                                <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                    alt="">
                                                <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                    alt="">
                                                <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                    alt="">
                                                <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                                    class="unfill-star" alt=""> --}}
                                                <span>{{ (int) $content->firstSong->generalReviews->averageRating }}&nbsp;({{ $content->firstSong->generalReviews->totalReviews }}
                                                    Reviews)</span>
                                            </div>
                                            

                                                @include('frontend.components.reviews.action-add-review',['menus' =>
                                                ['popupAddReview'],'songId' => $content->firstSong->data->songId,'artistId' =>
                                                $content->firstSong->data->artistId])
                                        </div>
                                        <div class="video-review-scroll">
                                            @foreach ($content->firstSong->allReviews as $item)
                                                <div class="video-reviews">
                                                    <div class="small-review-box">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $item->ratings)
                                                                <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                                    alt="">
                                                            @else
                                                                <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                                                    class="unfill-star" alt="">
                                                            @endif
                                                        @endfor
                                                        {{-- <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                            alt="">
                                                        <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                            alt="">
                                                        <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                            alt="">
                                                        <img src="{{ asset('public/assets/frontend/img/filled-star.svg') }}"
                                                            alt="">
                                                        <img src="{{ asset('public/assets/frontend/img/border-star.svg') }}"
                                                            class="unfill-star" alt=""> --}}
                                                    </div>
                                                    <span
                                                        class="blur-color toggle-current-ellips ellips-add">{{ $item->reviews }}</span>
                                                    <div class="client-date">
                                                        <p class="caption blur-color">— {{ $item->userName }}</p>
                                                        <p class="caption blur-color">{{ $item->createdAt }}</p>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    @include('frontend.components.reviews.add.add-review')
    @include('frontend.components.playlist.add.add-to-my-playlist')
@endsection
@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/music-controller.js') }}"></script>
    <script>
        $("#sortable").sortable({
            handle: ".drag-handle",
            containment: ".song-list"
        });

        $(document).ready(function() {
            $(".default-star-box").click(function() {
                $(".only-list-wrapper").addClass("leftList");
                $(".only-review-wrapper").addClass("upReview")
            })
            $(".close-review-btn").click(function() {
                $(".only-list-wrapper").removeClass("leftList");
                $(".only-review-wrapper").removeClass("upReview")
            })
        })


        $(document).ready(function() {
            $(".toggle-current-ellips").click(function() {
                $(this).toggleClass("ellips-add");
            })
        })
    </script>
@endsection
