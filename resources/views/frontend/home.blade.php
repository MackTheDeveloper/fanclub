{{-- @section('title', $cms ? $cms->name : 'Home page')
@section('metaKeywords', $cms ? $cms->seo_meta_keyword : '')
@section('metaDescription', $cms ? $cms->seo_description : '') --}}
@section('title', $seo_title)
@section('metaTitle', $seo_title)
@section('metaKeywords', $seo_meta_keyword)
@section('metaDescription', $seo_description)
@extends('frontend.layouts.master')
@section('content')

    @if (Auth::check())
        @php($authenticateClass = '')
    @else
        @php($authenticateClass = ' loginBeforeGo')
    @endif


    <!--------------------------
                    HOME START
                --------------------------->
    <div class="tab-wrapper">
        <div class="container-fluid">
            <div class="tab-section">
                <ul>
                    @if ($content)
                        @foreach ($content as $keyHomePageHeaderMenu => $rowHomePageHeaderMenu)
                            @if ($rowHomePageHeaderMenu->componentId == 'HomePageHeaderMenu')
                                <li><a data="#home" class="tab-link active">Home</a></li>
                                @foreach ($rowHomePageHeaderMenu->HomePageHeaderMenuData as $keyHomePageHeaderMenuData => $rowHomePageHeaderMenuData)
                                    {{-- @if(empty($rowHomePageHeaderMenuData->isWeb) || $rowHomePageHeaderMenuData->isWeb!='0') --}}
                                    <li><a data="#{{ $rowHomePageHeaderMenuData->value }}"
                                            class="tab-link">{{ $rowHomePageHeaderMenuData->key }}</a></li>
                                    {{-- @endif --}}
                                @endforeach
                            @endif
                        @endforeach
                    @endif

                    <!-- <li><a href="" class="tab-link">How It Works</a></li>
                        <li><a href="" class="tab-link">Best of fanclub</a></li>
                        <li><a href="" class="tab-link">Trending Now</a></li>
                        <li><a href="" class="tab-link">New to fanclub</a></li>
                        <li><a href="" class="tab-link">Playlists</a></li>
                        <li><a href="" class="tab-link">Featured Artists</a></li> -->
                </ul>
            </div>
        </div>
    </div>

    <div class="">
        <div class="header-carousel section" id="home">
            <div class="owl-carousel owl-theme custom-cara">
                @if ($content)
                    @foreach ($content as $key => $row)
                        @if ($row->componentId == 'HomePageBannerComponent')

                            @foreach ($row->HomePageBannerData as $keyHomePageBannerData => $valueHomePageBannerData)
                                <a href="{{ $valueHomePageBannerData->bannerUrl }}"
                                    class="item">
                                    <img src="{{ $valueHomePageBannerData->Image }}">
                                </a>

                            @endforeach
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        @if ($content)
            @foreach ($content as $keyHowItWorksWebComponent => $rowHowItWorksWebComponent)
                @if ($rowHowItWorksWebComponent->componentId == 'HowItWorksWebComponent')
                    <div class="how-it-work" id="how-it-works">
                        <div class="container-fluid">
                            <div class="text-center">
                                <h4>{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->mainLabel }}</h4>
                                <span
                                    class="blur-color mb-21">{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->mainDescription }}</span>
                            </div>
                            <div class="hiw-wrapper">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6 custome-column">
                                        <div class="hiw-fan-box">
                                        <h5>{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->fanLabel }}</h5>
                                        <span
                                            class="blur-color">{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->fanDescription }}</span>
                                        <!-- <img src="assets/img/Fan.png"> -->
                                        <div class="hiw-box">

                                            @if ($content)
                                                @foreach ($content as $key => $row)
                                                    @if ($row->componentId == 'HowItWorksWebComponent')
                                                        @php($i = 1)
                                                        @foreach ($rowHowItWorksWebComponent->HowItWorksWebData->list as $keyHowItWorksWebData => $valueHowItWorksWebData)
                                                            @if ($valueHowItWorksWebData->Type == 'fan')

                                                                @php($renderFile = '')
                                                                @if ($i % 2 == 0)
                                                                    @php($renderFile = 'left-content--right-image')
                                                                @else
                                                                    @php($renderFile = 'left-image-content-right')
                                                                @endif

                                                                @include('frontend.how-it-works-web.'.$renderFile,['valueHowItWorksWebData'
                                                                => $valueHowItWorksWebData,'i' => $i])

                                                                @php($i++)
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6 custome-column">
                                    <div class="hiw-artist-box">
                                        <h5>{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->artistLabel }}</h5>
                                        <span
                                            class="blur-color">{{ $rowHowItWorksWebComponent->HowItWorksWebData->label->artistDescription }}</span>
                                        <div class="hiw-box">
                                            @if ($content)
                                                @foreach ($content as $key => $row)
                                                    @if ($row->componentId == 'HowItWorksWebComponent')
                                                        @php($i = 1)
                                                        @foreach ($row->HowItWorksWebData->list as $keyHowItWorksWebData => $valueHowItWorksWebData)
                                                            @if ($valueHowItWorksWebData->Type == 'artist')

                                                                @php($renderFile = '')
                                                                @if ($i % 2 == 0)
                                                                    @php($renderFile = 'left-image-content-right')
                                                                @else
                                                                    @php($renderFile = 'left-content--right-image')
                                                                @endif

                                                                @include('frontend.how-it-works-web.'.$renderFile,['valueHowItWorksWebData'
                                                                => $valueHowItWorksWebData,'i' => $i])

                                                                @php($i++)
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        </div>
                                        <!-- <img src="assets/img/Artist.png"> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        @if ($content)
            @php($classAdded = 0)
            @foreach ($content as $keyLoginSections => $rowLoginSections)

                @if ($rowLoginSections->componentId == 'myPlaylist' && !empty($rowLoginSections->myPlaylistData))
                    <div class="new-on-fanclub section" id="my-playlists">
                        <div class="container-fluid">
                            <div class="slider-header">
                                <h5>My Playlists</h5>
                                <a href="{{ url('myplaylist') }}" class="a">See All</a>
                            </div>
                        </div>
                        <div class="square-img-carousel">
                            <div class="owl-carousel owl-theme">
                                @foreach ($rowLoginSections->myPlaylistData as $keyMyPlaylistData => $rowMyPlaylistData)
                                    <a href="{{ url('my-playlist/' . $rowMyPlaylistData->playlistSlug) }}"
                                        class="item">
                                        <img src="{{ $rowMyPlaylistData->playListIcon }}">
                                        <p class="s1">{{ $rowMyPlaylistData->playlistName }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @php($classAdded++)
                @endif
                @if ($rowLoginSections->componentId == 'favPlaylist' && !empty($rowLoginSections->favPlaylistData))
                    <div class="new-on-fanclub section {{ $classAdded ? '' : 'pt-76' }}" id="fav-playlists">
                        <div class="container-fluid">
                            <div class="slider-header">
                                <h5>{{ $rowLoginSections->title }}</h5>
                                {{-- <h5>Favourite Playlists</h5> --}}
                                <a href="{{ url('favourite-playlist') }}" class="a">See
                                    All</a>
                            </div>
                        </div>
                        <div class="square-img-carousel">
                            <div class="owl-carousel owl-theme">
                                @foreach ($rowLoginSections->favPlaylistData as $keyFavPlaylistData => $rowFavPlaylistData)
                                    <a href="{{ url('songs/' . $rowFavPlaylistData->groupSlug) }}"
                                        class="item">
                                        <img src="{{ $rowFavPlaylistData->groupIcon }}">
                                        <p class="s1">{{ $rowFavPlaylistData->groupName }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @php($classAdded++)
                @endif
                @if ($rowLoginSections->componentId == 'myRecent' && !empty($rowLoginSections->myRecentData))
                    <div class="my-collection section {{ $classAdded ? '' : 'pt-76' }}" id="{{ 'recently-played' }}">
                        <div class="slider-header">
                            <h5>Recently Played</h5>
                            @if ($authenticateClass)
                                <a href="javascript:void(0)" class="a{{ $authenticateClass }}">See All</a>
                            @else
                                <a href="{{ route('recentPlayed') }}" class="a">See All</a>
                            @endif
                        </div>
                        <div class="collection-wrapper">
                            <div class="owl-carousel owl-theme">
                                @php($i = 1)
                                @foreach ($rowLoginSections->myRecentData as $keyMyCollectionsData => $rowMyCollectionsData)
                                    @if ($i % 2 == 1)
                                        <div class="item">
                                    @endif
                                    <div class="collection-box">
                                        <img class="c-img" src="{{ $rowMyCollectionsData->songIcon }}" />
                                        <div role="button" class="collection-data playSingleSongInPlayer"
                                            data-song-id={{ $rowMyCollectionsData->songId }}>
                                            <span>{{ $rowMyCollectionsData->songName }}</span>
                                            <p class="caption">{{ $rowMyCollectionsData->artistName }}</p>
                                        </div>
                                        @include('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                                        'popupDownload'],'songId' => $rowMyCollectionsData->songId, 'allData' =>
                                        $rowMyCollectionsData, "refresh"=>true])
                                    </div>
                                    @if ($i % 2 == 0 || count($rowLoginSections->myRecentData) == $i)
                            </div>
                @endif
                @php($i++)
            @endforeach
    </div>
    </div>
    </div>
    @php($classAdded++)
    @endif
    @if ($rowLoginSections->componentId == 'myCollectionsWeb' && !empty($rowLoginSections->myCollectionsWebData))
        <div class="my-collection section {{ $classAdded ? '' : 'pt-76' }}" id="{{ 'my-collection' }}">
            <div class="slider-header">
                <h5>My Collection</h5>
                @if ($authenticateClass)
                    <a href="javascript:void(0)" class="a{{ $authenticateClass }}">See All</a>
                @else
                    <a href="{{ route('my-favourite') }}" class="a">See All</a>
                @endif
            </div>
            <div class="collection-wrapper">
                <div class="owl-carousel owl-theme">
                    @php($i = 1)
                    @foreach ($rowLoginSections->myCollectionsWebData as $keyMyCollectionsData => $rowMyCollectionsData)
                        @if ($i % 2 == 1)
                            <div class="item">
                        @endif
                        <div class="collection-box">
                            <img class="c-img" src="{{ $rowMyCollectionsData->songIcon }}" />
                            <div role="button" class="collection-data playSingleSongInPlayer"
                                data-song-id={{ $rowMyCollectionsData->songId }}>
                                <span>{{ $rowMyCollectionsData->songName }}</span>
                                <p class="caption">{{ $rowMyCollectionsData->artistName }}</p>
                            </div>
                            @include('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                            'popupDownload'],'songId' => $rowMyCollectionsData->songId, 'allData' => $rowMyCollectionsData,
                            "refresh"=>true])
                        </div>
                        @if ($i % 2 == 0 || count($rowLoginSections->myCollectionsWebData) == $i)
                </div>
    @endif
    @php($i++)
    @endforeach
    </div>
    </div>
    </div>
    @php($classAdded++)
    @endif
    {{-- @if ($rowLoginSections->componentId == 'myCollections' && !empty($rowLoginSections->myCollectionsData))
                    @foreach ($rowLoginSections->myCollectionsData as $key => $singleData)
                        @if (!empty($singleData->list))
                            <div class="my-collection {{ $classAdded ? '' : 'pt-76' }}" id="{{($key==0)?'recently-played':'my-collection'}}">
                                <div class="slider-header">
                                    <h5>{{($key=='0')?"Recently Played":"My Collection"}}</h5>
                                    @if ($authenticateClass)
                                        <a href="javascript:void(0)" class="a{{ $authenticateClass }}">See All</a>
                                    @else
                                        <a href="{{($key=='0')?route('recentPlayed'):route('my-favourite')}}" class="a">See All</a>
                                    @endif
                                </div>
                                <div class="collection-wrapper">
                                    <div class="owl-carousel owl-theme">
                                        @php($i = 1)
                                        @foreach ($singleData->list as $keyMyCollectionsData => $rowMyCollectionsData)
                                            @if ($i % 2 == 1)
                                                <div class="item">
                                            @endif
                                                    <div class="collection-box">
                                                        <img class="c-img" src="{{ $rowMyCollectionsData->songIcon }}" />
                                                        <div role="button"  class="collection-data playSingleSongInPlayer" data-song-id={{ $rowMyCollectionsData->songId }}>
                                                            <span>{{ $rowMyCollectionsData->songName }}</span>
                                                            <p class="caption">{{ $rowMyCollectionsData->artistName }}</p>
                                                        </div>
                                                        @include('frontend.components.action-popup',['menus' => ['popupAddToPlaylist',
                                                        'popupDownload'],'songId' => $rowMyCollectionsData->songId, 'allData' => $rowMyCollectionsData, "refresh"=>true])
                                                    </div>
                                            @if ($i % 2 == 0 || count($singleData->list) == $i)
                                                </div>
                                            @endif
                                            @php($i++)
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @php($classAdded++)
                        @endif
                    @endforeach
                @endif --}}
    @if ($rowLoginSections->componentId == 'favArtist' && !empty($rowLoginSections->favArtistData))
        <div class="my-artist section" id="my-artists">
            <div class="container-fluid">
                <div class="slider-header">
                    <h5>My Artists</h5>
                    <a href="{{ url('my-artists') }}" class="a">See All</a>
                </div>
            </div>
            <div class="rounded-img-carousel">
                <div class="owl-carousel owl-theme">
                    @foreach ($rowLoginSections->favArtistData as $keyFavArtistData => $rowFavArtistData)
                        <a href="{{ url('artist/' . $rowFavArtistData->artistSlug) }}"
                            class="item">
                            <img src="{{ $rowFavArtistData->artistProfilePic }}">
                            <p class="s1">{{ $rowFavArtistData->artistFullName }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @php($classAdded++)
    @endif
    @endforeach
    @endif


    @if ($content)
        @foreach ($content as $key => $row)
            @if ($row->componentId == 'HomePageComponent')
                @foreach ($row->HomePageComponentData as $key1 => $row1)
                    @if ($row1->componentData->componentType == '1')
                        <div class="section" id="{{ $row1->componentData->componentSlug }}">
                            {!! $row1->componentData->componentText !!}
                        </div>
                    @elseif($row1->componentData->componentType == '2')
                        @include('frontend.homepage-component.banner',['bannerData' => $row1->componentData])
                    @elseif($row1->componentData->componentType == '3')
                        @if (!empty($row1->componentData->componentDynamicGroup->data))

                            @include('frontend.homepage-component.'.strtolower($row1->componentData->componentDynamicGroup->commonDetails->ImageShape),['data'
                            => $row1->componentData])

                        @endif
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
    </div>
    @include('frontend.components.playlist.add.add-to-my-playlist')
    @include('frontend.components.music-player.form-for-single-song')
    <!--------------------------
                    HOME END
                --------------------------->
@endsection

@section('footscript')
    <script src="{{ asset('public/assets/frontend/js/redirect-video-player.js') }}?r=090820200" data-base-url="{{ url('/') }}">
    </script>
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

        $("#loginFormFromPopup_bk").validate({
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

        $("#loginWithOtpFormFromPopup_bk").validate({
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
        
        $(document).on('submit','#resendOtpFormFromPopup_bk',function(e){
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

        $("#loginWithOtpVerificationFormFromPopup_bk").validate({
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

        $("#forgotPasswordFormFromPopup_bk").validate({
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


        $(function() {

            var link = $('.tab-section a.tab-link');
            var link2 = $('.sidebarScroll a.tab-link');

            link.each(function(){
                var id = $(this).attr('data');
                if(!$(id).length)
                {
                    $(this).closest('li').hide();
                }
            });

            link.on('click', function(e) {
                var target = $($(this).attr('data'));
                $('html, body').animate({
                    scrollTop: target.offset().top - 150
                }, 600);
                $(this).addClass('active');
                e.preventDefault();
            });
            link2.each(function(){
                var id = $(this).attr('data');
                if(!$(id).length)
                {
                    $(this).closest('li').hide();
                }
            });

            link2.on('click', function(e) {
                var target = $($(this).attr('data'));
                $('html, body').animate({
                    scrollTop: target.offset().top - 150
                }, 600);
                $(this).addClass('active');
                e.preventDefault();
            });

            $(window).on('scroll', function() {
                scrNav();
            });

            function scrNav() {
                var sTop = $(window).scrollTop();
                $('.section').each(function() {
                    var id = $(this).attr('id'),
                        offset = $(this).offset().top - 190,
                        height = $(this).height();
                    if (sTop >= offset && sTop < offset + height) {
                        link.removeClass('active');
                        $('.tab-section').find('[data="#' + id + '"]').addClass('active');
                    }
                });
            }
            scrNav();
        });
    </script>

@endsection
