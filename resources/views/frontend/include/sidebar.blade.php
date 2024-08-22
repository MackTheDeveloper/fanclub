@php
$sidebarMenuData = app('App\Models\HomePageComponent')->getSidebarMenuData();
$authCheck = Auth::check();
$authRoleMain = $authCheck ? Auth::user()->role_id : 0;
$authRole = getAuthProps();
@endphp
<div class="sideMenu">
    @if (!$authCheck)
        <div class="side-menu-profile">
            <img src="{{ asset('public/assets/frontend/img/guest-user.svg') }}" class="user">
            <a href="{{ url('login') }}">
                <p class="s1">Login / Sign Up</p>
            </a>
            <img src="{{ asset('public/assets/frontend/img/close.svg') }}" class="closeIcons">
        </div>
    @else
        <div class="side-menu-profile">
            <img src="{{ app('App\Models\UserProfilePhoto')->getProfilePhoto(Auth::user()->id) }}"
                class="user">
            <p class="s1">Hello,
                {{ $authCheck ? Auth::user()->firstname . ' ' . Auth::user()->lastname : '' }}</p>
            <img src="{{ asset('public/assets/frontend/img/close.svg') }}" class="closeIcons">
        </div>
    @endif

    <div class="first-menu">
        <ul>
            @if (($authCheck && $authRole == '3') || !$authCheck)
                <li><a href="{{ url('/') }}">
                        <img src="{{ asset('public/assets/frontend/img/s1.svg') }}">
                        <p class="s2">Home</p>
                    </a></li>
            @endif
            @if ($authCheck && $authRole == '3')
                <li><a href="{{ url('my-favourite') }}">
                        <img src="{{ asset('public/assets/frontend/img/s2.svg') }}">
                        <p class="s2">My Collection</p>
                    </a></li>
            @endif
            @if ($authCheck && $authRole == '2')
                <li><a href="{{ route('ArtistDashboard') }}">
                        <img src="{{ asset('public/assets/frontend/img/s1.svg') }}">
                        <p class="s2">Dashboard</p>
                    </a></li>
            @endif
            <li class="change-mode">
                <a href="javascript:void(0)">
                    <img src="{{ asset('public/assets/frontend/img/s3.svg') }}">
                    <p class="s2">Dark Mode</p>
                    <div class="button r" id="button-1">
                        <input type="checkbox" id="dark-thene-checkbox" class="checkbox dark-thene-checkbox">
                        <div class="knobs"></div>
                        <div class="layer"></div>
                    </div>
                </a>
            </li>
            @if ($authCheck && $authRole == '2')
                <li class="change-mode allow-message">
                    <a href="javascript:void(0)">
                        <img src="{{ asset('public/assets/frontend/img/message.svg') }}">
                        <p class="s2">Let fans send messages</p>
                        <div class="button r" id="button-1">
                            <input type="checkbox"
                                {{ Auth::user() && Auth::user()->allow_message == '1' ? 'checked' : '' }}
                                id="allow-message-checkbox" class="checkbox allow-message-checkbox">
                            <div class="knobs"></div>
                            <div class="layer"></div>
                        </div>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <div class="first-hr"></div>

    <div class="second-menu">
        <h6>Quick Access</h6>
        <ul>
            @if ($authCheck && $authRoleMain == '3')
                <li>
                    <a href="{{ route('mySubscription') }}">
                        <img src="{{ asset('public/assets/frontend/img/s4.svg') }}">
                        <p class="s2">Your Subscriptions</p>
                    </a>
                </li>
            @endif
            @if ($authCheck)
                <li>
                    <a
                        href="{{ $authRole == '3' ? route('myReviewsFan') : route('artistSongListForReview') }}">
                        <img src="{{ asset('public/assets/frontend/img/s5.svg') }}">
                        <p class="s2">My Reviews</p>
                    </a>
                </li>
            @endif
            @if ($authCheck && $authRole == '2')
                <li>
                    <a href="{{ route('songList') }}">
                        <img src="{{ asset('public/assets/frontend/img/ds1.svg') }}">
                        <p class="s2">My Songs</p>
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ route('forumsList') }}">
                    <img src="{{ asset('public/assets/frontend/img/s6.svg') }}">
                    <p class="s2">Forum</p>
                </a>
            </li>
            @if ($authCheck)
                <li>
                    <a href="{{ route('chatModule') }}">
                        <img src="{{ asset('public/assets/frontend/img/message.svg') }}">
                        <p class="s2">Message</p>
                        @if (getCountChatUnread())
                            <div class="msg-count">{{ getCountChatUnread() }}</div>
                        @endif
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ url('about-us') }}">
                    <img src="{{ asset('public/assets/frontend/img/s7.svg') }}">
                    <p class="s2">About fanclub</p>
                </a>
            </li>
            <li>
                <a href="{{ route('faq') }}">
                    <img src="{{ asset('public/assets/frontend/img/s8.svg') }}">
                    <p class="s2">FAQs</p>
                </a>
            </li>
        </ul>
    </div>

    @if ($authCheck)
        <div class="first-hr profile-hr"></div>

        <div class="profile-menu">
            <h6>Profile</h6>
            <ul>
                <li>
                    <a href="{{ $authCheck && $authRole == '3' ? route('editProfileFan') : route('ArtistProfile') }}">
                        <img src="{{ asset('public/assets/frontend/img/p1.svg') }}">
                        <p class="s2">Profile</p>
                    </a>
                </li>
                {{-- <li>
                    <a href="">
                        <img src="{{ asset('public/assets/frontend/img/p2.svg') }}">
                        <p class="s2">Your Subscriptions</p>
                    </a>
                </li>
                <li>
                    <a href="">
                        <img src="{{ asset('public/assets/frontend/img/p3.svg') }}">
                        <p class="s2">My Reviews</p>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ url('logout') }}">
                        <img src="{{ asset('public/assets/frontend/img/p4.svg') }}">
                        <p class="s2">Log Out</p>
                    </a>
                </li>
            </ul>
        </div>
    @endif

    <div class="how-can-block">
        <div class="how-can-hr">
            <a href="{{ url('contact-us') }}">
                <p>How can we help you?</p>
                <img src="{{ asset('public/assets/frontend/img/right-arrow.png') }}">
            </a>
        </div>
    </div>

    @if (($authCheck && $authRole == '3') || !$authCheck)
        <div class="third-menu">
            <h6>Quick Access</h6>
            <ul class="sidebarScroll">
                @if (!$authCheck)
                    <li><a href="{{ route('showSignup') }}">New to fanclub</a></li>
                @endif

                @if ($sidebarMenuData)
                    @foreach ($sidebarMenuData as $key => $row)

                        @if ($authCheck)
                            <li><a href="{{ url($row['value']) }}">{{ $row['key'] }}</a></li>
                        @else
                            <li><a href="{{ Route::current() && Route::current()->getName() == 'home' ? 'javascript:void(0)' : url('/#' . $row['value']) }}"
                                    data="#{{ $row['value'] }}" class="tab-link">{{ $row['key'] }}</a></li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    @endif

    <div class="terms-wrapper">
        <div class="terms-block">
            <a href="{{ url('terms-conditions') }}">
                <p class="caption">Terms & Conditions</p>
            </a>
            <div class="dot"></div>
            <a href="{{ url('privacy-policy') }}">
                <p class="caption">Privacy Policy</p>
            </a>
        </div>
    </div>
    <div class="install-app">
        <span>Install App</span>
        <a href=""><img src="{{ asset('public/assets/frontend/img/Apple.svg') }}"></a>
        <a href=""><img src="{{ asset('public/assets/frontend/img/Android.svg') }}"></a>
    </div>
</div>
