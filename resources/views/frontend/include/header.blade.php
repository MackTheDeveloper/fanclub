@php
use App\Models\UserProfilePhoto;
use App\Models\LocationGroup;
$authCheck = Auth::check();
$authRoleMain = $authCheck ? Auth::user()->role_id : 0;
$authRole = getAuthProps();
@endphp
<div class="loader-bg d-none">
    <img src="{{ asset('public/assets/frontend/img/loader.svg') }}" alt="" />
</div>
<!--------------------------
    WEB HEADER START
--------------------------->
@if (empty($mobile))
    <div class="top-navbar">
        <div class="container-fluid">
            <nav class="navbar navbar-expand">
                <img src="{{ asset('public/assets/frontend/img/menu.svg') }}" class="menu-icon" alt="menu">
                <a class="navbar-brand"
                    href="{{ $authCheck && $authRole == '2' ? route('ArtistDashboard') : url('/') }}">
                    <img src="{{ asset('public/assets/frontend/img/Logo.svg') }}" class="black-img">
                    <img src="{{ asset('public/assets/frontend/img/Logo-white.svg') }}" class="white-img">
                </a>
                @if (($authCheck && $authRole == '3') || !$authCheck)
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <form id="searchFront" method="GET" action="{{ route('searchFront', '1') }}"
                                autocomplete="off">
                                <div class="header-search">
                                    <button>
                                        <img src="{{ asset('public/assets/frontend/img/search.svg') }}">
                                    </button>
                                    <input type="text" placeholder="Search Artists, Songs or Playlists" name="search"
                                        value="{{ isset($search) ? $search : '' }}">
                                </div>
                            </form>
                        </li>
                    </ul>
                @endif
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <div class="for-artist">
                            <span class="s2">For Artist</span>
                            <a class="border-btn" href="{{ url('login') }}">Sign Up / Sign In</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown icon-dropdown music-language" style="display: none">
                        <a class="nav-link dropdown-toggle" id="navbardrop" data-toggle="dropdown">
                            <img src="{{ asset('public/assets/frontend/img/globe.svg') }}">
                        </a>
                        <div class="dropdown-menu">
                            <div class="music-header-data">
                                <h6>Music Language</h6>
                                <ul>
                                    <li>
                                        <img src="{{ asset('public/assets/frontend/img/l1.svg') }}">
                                        <span class="s2">English</span>
                                        <label class="m-ck">
                                            <input type="checkbox" checked="checked">
                                            <span class="m-ck-checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <img src="{{ asset('public/assets/frontend/img/l2.svg') }}">
                                        <span class="s2">Spanish</span>
                                        <label class="m-ck">
                                            <input type="checkbox">
                                            <span class="m-ck-checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <img src="{{ asset('public/assets/frontend/img/l3.svg') }}">
                                        <span class="s2">German</span>
                                        <label class="m-ck">
                                            <input type="checkbox">
                                            <span class="m-ck-checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <img src="{{ asset('public/assets/frontend/img/l4.svg') }}">
                                        <span class="s2">French</span>
                                        <label class="m-ck">
                                            <input type="checkbox">
                                            <span class="m-ck-checkmark"></span>
                                        </label>
                                    </li>
                                    <li>
                                        <img src="{{ asset('public/assets/frontend/img/l5.svg') }}">
                                        <span class="s2">Japanese</span>
                                        <label class="m-ck">
                                            <input type="checkbox">
                                            <span class="m-ck-checkmark"></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="music-btns">
                                <button class="border-btn">Cancel</button>
                                <button class="fill-btn">Update</button>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown icon-dropdown">
                        <a class="nav-link">
                            <label class="ck theme-icon">
                                <input type="checkbox" class="dark-thene-checkbox" name="theme">
                                <span class="ck-checkmark"></span>
                            </label>
                        </a>
                    </li>
                    <li class="nav-item dropdown icon-dropdown notification d-none">
                        <a class="nav-link dropdown-toggle" id="navbardrop" data-toggle="dropdown">
                            <img src="{{ asset('public/assets/frontend/img/Notifications.svg') }}"
                                class="whiteImg">
                            <img src="{{ asset('public/assets/frontend/img/Notifications-dark.svg') }}"
                                class="blackImg">
                        </a>
                        <div class="dropdown-menu">
                            <h6>Notification</h6>
                            <ul>
                                @foreach (getNotifications() as $item)
                                    <li>
                                        <div class="n-data">
                                            <span>{{ $item['description'] }}</span>
                                            <p class="caption">{{ $item['viewCreatedAt'] }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="v-line-wrapper">
                        <div class="v-line"></div>
                    </li>

                    @if (!$authCheck)
                        <li class="nav-item">
                            <div class="for-fan">
                                <a class="border-btn" href="{{ url('signup') }}">Sign Up</a>
                                <a class="fill-btn" href="{{ url('login') }}">Sign In</a>
                            </div>
                        </li>
                    @else
                        <li class="nav-item dropdown my-profile">
                            <a class="nav-link dropdown-toggle" id="navbardrop" data-toggle="dropdown">
                                <img src="{{ app('App\Models\UserProfilePhoto')->getProfilePhoto(Auth::user()->id) }}"
                                    class="user-icon">
                                <p class="s1">Hello,
                                    {{ $authCheck ? Auth::user()->firstname . ' ' . Auth::user()->lastname : '' }}
                                </p>
                                <img class="down-arrow"
                                    src="{{ asset('public/assets/frontend/img/chevron-down.svg') }}">
                            </a>
                            <div class="dropdown-menu">
                                <ul>
                                    @if ($authCheck && $authRole == '2')
                                        <li>
                                            <a href="{{ route('ArtistDashboard') }}">
                                                <img src="{{ asset('public/assets/frontend/img/s2.svg') }}">
                                                <p class="s2">Dashboard</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a
                                            href="{{ $authCheck && $authRole == '3' ? route('editProfileFan') : route('ArtistProfile') }}">
                                                <img src="{{ asset('public/assets/frontend/img/p1.svg') }}">
                                                <p class="s2">Profile</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('songList') }}">
                                                <img src="{{ asset('public/assets/frontend/img/ds1.svg') }}">
                                                <p class="s2">My Songs</p>
                                            </a>
                                        </li>
                                        
                                    @elseif ($authCheck && $authRole == '3')
                                        <li>
                                            <a
                                            href="{{ $authCheck && $authRoleMain == '3' ? route('editProfileFan') : route('ArtistProfile') }}">
                                                <img src="{{ asset('public/assets/frontend/img/p1.svg') }}">
                                                <p class="s2">Profile</p>
                                            </a>
                                        </li>
                                        @if ($authRoleMain == '3')
                                            <li>
                                                <a href="{{ route('mySubscription') }}">
                                                    <img src="{{ asset('public/assets/frontend/img/p2.svg') }}">
                                                    <p class="s2">Your Subscriptions</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                    @if ($authRoleMain == '2')
                                        <li>
                                            <a href="{{ route('switchArtistFan') }}">
                                                <img src="{{ asset('public/assets/frontend/img') }}/{{$authRole=='3'?"Back-to-Artist.svg":"View-as-fan.svg"}}">
                                                <p class="s2">{{$authRole=='3'?"Back to Artist":"View as fan"}}</p>
                                            </a>
                                        </li>
                                    @endif

                                    <li>
                                        <a
                                        href="{{ $authRole == '3' ? route('myReviewsFan') : route('artistSongListForReview') }}">
                                            <img src="{{ asset('public/assets/frontend/img/p3.svg') }}">
                                            <p class="s2">My Reviews</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('logout') }}">
                                            <img src="{{ asset('public/assets/frontend/img/p4.svg') }}">
                                            <p class="s2">Log Out</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </nav>
            @if (($authCheck && $authRole == '3') || !$authCheck)
                <form id="searchFront2" method="GET" action="{{ route('searchFront', '1') }}" autocomplete="off">
                    <div class="header-search mobile-search">
                        <button>
                            <img src="{{ asset('public/assets/frontend/img/search.svg') }}">
                        </button>
                        <input type="text" placeholder="Search Artists, Songs or Playlists" name="search"
                            value="{{ isset($search) ? $search : '' }}">
                    </div>
                </form>
            @endif
        </div>
    </div>

    @include('frontend.include.sidebar')
@endif
<div class="backBg"></div>
<!--------------------------
    WEB HEADER END
--------------------------->
