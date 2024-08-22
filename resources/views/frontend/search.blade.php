@section('title', 'Search : ' . $search)
@section('metaKeywords', '')
@section('metaDescription', '')
@extends('frontend.layouts.master')
@section('content')
    @php($shownBlocks = 0)
    @if (Auth::check())
        @php($authenticateClass = '')
    @else
        @php($authenticateClass = ' loginBeforeGo')
    @endif
    <!--------------------------
        HOME START
    --------------------------->
    @if (Auth::check())
        <div class="recent-search ">
            <div class="container-fluid">
                <h5>Recent Searches</h5>
                @if (count($content['recentSearch']->recentSearchData->list))
                    <div class="search-wrapper">
                        @foreach ($content['recentSearch']->recentSearchData->list as $key => $row)
                            <div class="searching-data">
                                <a href="javascript:void(0);" id="searchtag"
                                    data-id="{{ $row->keyword }}"><span>{{ $row->keyword }}</span></a>
                                <a href="javascript:void(0);" id="removerecent" data-id="{{ $row->id }}"><img
                                        src="{{ asset('public/assets/frontend/img/close-small.svg') }}" alt="" /></a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if (count($content['fanclubPlaylist']->fanclubPlaylistData->list))
        <div class="fanclub-playlist">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>fanclub Playlists</h5>
                    <!-- <a href="" class="a">See All</a> -->
                    <a href="{{ url('fanclub-plyalist' . '/' . $search) }}"
                        class="a">See All</a>
                </div>
            </div>
            <div class="square-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach ($content['fanclubPlaylist']->fanclubPlaylistData->list as $key => $row)
                        <a href="{{ url('songs/' . $row->groupSlug) }}"
                            class="item">
                            <img src="{{ $row->groupIcon }}" alt="" />
                            <p class="s1">{{ $row->groupName }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @php($shownBlocks++)
    @endif
    @if (Auth::check())
        @if (count($content['myCollection']->myCollectionData->list))
            <div class="my-collection" id="my-collection">
                <div class="slider-header">
                    <h5>My Collection</h5>
                    <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('my-favourite' . '/' . $search) }}"
                        class="a{{ $authenticateClass }}">See All</a>
                </div>
                <div class="collection-wrapper">
                    <div class="owl-carousel owl-theme">
                        @php($i = 1)
                        @foreach ($content['myCollection']->myCollectionData->list as $key => $row)
                            @if ($i % 2 == 1)
                                <div class="item">
                            @endif
                            <div role="button" class="collection-box playSingleSongInPlayer"
                                data-song-id={{ $row->songId }}>
                                <img class="c-img" src="{{ $row->songIcon }}" />
                                <div class="collection-data">
                                    <span>{{ $row->songName }}</span>
                                    <p class="caption">{{ $row->artistName }}</p>
                                </div>
                                @include('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                                'popupDownload'],'songId' => $row->songId,"refresh"=>true])
                            </div>
                            @if ($i % 2 == 0 || count($content['myCollection']->myCollectionData->list) == $i)
                    </div>
        @endif
        @php($i++)
    @endforeach
    </div>
    </div>
    </div>
    @php($shownBlocks++)
    @endif

    @if (count($content['myPlaylist']->myPlaylistData->list))
        <div class="my-playlist">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>My Playlists</h5>
                    <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('myplaylist' . '/' . $search) }}"
                        class="a{{ $authenticateClass }}">See All</a>
                </div>
            </div>
            <div class="square-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach ($content['myPlaylist']->myPlaylistData->list as $key => $row)
                        <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('my-playlist' . '/' . $row->playlistSlug) }}"
                            class="item{{ $authenticateClass }}">
                            <img src="{{ $row->playListIcon }}" />
                            <p class="s1">{{ $row->playlistName }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @php($shownBlocks++)
    @endif

    @if (count($content['myArtist']->myArtistData->list))
        <div class="my-artist">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>My Artists</h5>
                    <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('my-artists' . '/' . $search) }}"
                        class="a{{ $authenticateClass }}">See All</a>
                </div>
            </div>
            <div class="rounded-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach ($content['myArtist']->myArtistData->list as $key => $row)
                        <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('artist' . '/' . $row->slug) }}"
                            class="item{{ $authenticateClass }}">
                            <img src="{{ $row->profilePic }}">
                            <p class="s1">{{ $row->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @php($shownBlocks++)
    @endif
    @endif

    @if (count($content['songs']->songsData->list))
        <div class="my-collection" id="my-collection">
            <div class="slider-header">
                <h5>Songs</h5>
                <a href="{{ url('all-songs' . '/' . $search) }}"
                    class="a">See All</a>
            </div>
            <div class="collection-wrapper">
                <div class="owl-carousel owl-theme">
                    @php($i = 1)
                    @foreach ($content['songs']->songsData->list as $key => $row)
                        @if ($i % 2 == 1)
                            <div class="item">
                        @endif
                        <div class="collection-box">
                            <img class="c-img" src="{{ $row->icon }}" />
                        <div role="button" class="collection-data{{ $authenticateClass }} playSingleSongInPlayer"
                                data-song-id={{ $row->songId }}>
                            <div class="collection-data">
                                <span>{{ $row->name }}</span>
                                <p class="caption">{{ $row->artistName }}</p>
                            </div>
                        </div>
                            @include('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                            'popupDownload'],'songId' => $row->songId,"refresh"=>true])
                        </div>
                        @if ($i % 2 == 0 || count($content['songs']->songsData->list) == $i)
                </div>
    @endif
    @php($i++)
    @endforeach
    </div>
    </div>
    </div>
    @php($shownBlocks++)
    @endif

    @if (count($content['artist']->artistData->list))
        <div class="my-artist">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>Artists</h5>
                    <a href="{{ url('all-artists' . '/' . $search) }}"
                        class="a">See All</a>
                </div>
            </div>
            <div class="rounded-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach ($content['artist']->artistData->list as $key => $row)
                        <a href="{{ url('artist' . '/' . $row->slug) }}"
                            class="item">
                            <img src="{{ $row->profilePic }}">
                            <p class="s1">{{ $row->name }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @php($shownBlocks++)
    @endif
    {{-- @if (count($content['genre']->genreData->list))
        <div class="genres">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>Genres</h5>
                    <!-- <a href="" class="a d-none">See All</a> -->
                </div>
            </div>
            <div class="double-img-carousel">
                <div class="owl-carousel owl-theme">
                    @php($i = 1)
                    @foreach ($content['genre']->genreData->list as $key2 => $row2)
                        <?php if ($i % 2 == 1) { ?>
                        <div class="item">
                            <?php } ?>
                            <a href="{{ $authenticateClass ? 'javascript:void(0)' : url('genre' . '/' . $row2->slug) }}"
                                class="img-content{{ $authenticateClass }}">
                                <img style="width: 190px;height: 95px;" src="{{ $row2->image }}">
                                <div class="img-content-overlay">
                                    <p class="s1">{{ $row2->name }}</p>
                                </div>
                            </a>
                            <?php if ($i % 2 == 0 || count($content['genre']->genreData->list) == $i) { ?>
                        </div>
                        <?php } ?>
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
        @php($shownBlocks++)
    @endif --}}

    @if (!$shownBlocks)
        <div class="no-data-found">
            <div class="container-fluid">
                <h6 class="text-center">{{ $content['noResultMsg']->noResultMsgData }}</h6>
            </div>
        </div>
    @endif

    @include('frontend.components.playlist.add.add-to-my-playlist')
    @include('frontend.components.music-player.form-for-single-song')

    <!--------------------------
        HOME END
    --------------------------->
@endsection

@section('footscript')
<script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.rounded-img-carousel .owl-carousel, .square-img-carousel .owl-carousel, .double-img-carousel .owl-carousel')
                .owlCarousel({
                    margin: 16,
                    rewindNav: false,
                    dots: false,
                    nav: true,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        576: {
                            items: 2,
                            margin: 24
                        },
                        768: {
                            items: 4,
                            margin: 24
                        },
                        1200: {
                            items: 6,
                            margin: 24
                        }
                    }
                })
        })

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
                        items: 1
                    },
                    576: {
                        items: 3
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
    </script>
@endsection
