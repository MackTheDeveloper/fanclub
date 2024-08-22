<div class="app-header header-shadow bg-secondary bg-gradient header-text-light">
    <div class="app-header__logo">
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic {{ Session::get('toggleSidebar') ? 'is-active' : '' }}" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-secondary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="header-logo">
            <a href="">
                <img src="{{url('public/images/Logo.svg')}}">
                <!-- <img src="{{url('public/images/demo.png')}}" width="80px" height="50px"> -->
            </a>
        </div>
        <?php
        $currentRoute = Route::currentRouteName();
        if ($currentRoute == 'artistListing') {
            $chooseButton = 'Artists';
            $classBtn = "first-btn";
        } else if ($currentRoute == 'fanListing') {
            $chooseButton = 'Fans';
            $classBtn = "second-btn";
        } else if ($currentRoute == 'songsList') {
            $chooseButton = 'Songs';
            $classBtn = "third-btn";
        } else if ($currentRoute == 'subscriptionsListing') {
            $chooseButton = 'Subscriptions';
            $classBtn = "four-btn";
        } else if ($currentRoute == 'transactionListing') {
            $chooseButton = 'Transactions';
            $classBtn = "five-btn";
        } else if ($currentRoute == 'cmsPageListing') {
            $chooseButton = 'CMS';
            $classBtn = "six-btn";
        } else if ($currentRoute == 'forumsListing') {
            $chooseButton = 'Forums';
            $classBtn = "seven-btn";
        } else {
            $chooseButton = '';
            $classBtn = "";
        }

        if (!empty($chooseButton))
            $placeHolder = Config::get('app.searchPlaceHolders')[$chooseButton];
        else
            $placeHolder = 'Please Select...';
        ?>
        <div class="header-search-container">
            <div class="header-search" id="headerSearch">
                <button class="choose-btn {{$chooseButton ? $classBtn : 'd-none'}}">{{$chooseButton}}</button>
                <form action="" id="searchableFormListing" method="post" style="width: 100%">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="search" id="searchableValue" value="" />
                    <input type="text" placeholder="{{$placeHolder}}" name="search" id="showDropdown" style="width: 100%">


                    <button type="submit" style="background: none;border: none;cursor: pointer">
                        <span class="search-btn" style="pointer-events: auto">
                            <span class="search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path fill="#758CA3" fill-rule="evenodd" d="M6.52346564,10.8907479 L2.20710678,15.2071068 C1.81658249,15.5976311 1.18341751,15.5976311 0.792893219,15.2071068 C0.402368927,14.8165825 0.402368927,14.1834175 0.792893219,13.7928932 L5.10925208,9.47653436 C4.41079097,8.49571549 4,7.29583043 4,6 C4,2.6862915 6.6862915,0 10,0 C13.3137085,0 16,2.6862915 16,6 C16,9.3137085 13.3137085,12 10,12 C8.70416957,12 7.50428451,11.589209 6.52346564,10.8907479 Z M10,10 C12.209139,10 14,8.209139 14,6 C14,3.790861 12.209139,2 10,2 C7.790861,2 6,3.790861 6,6 C6,8.209139 7.790861,10 10,10 Z"></path>
                                </svg>
                            </span>
                        </span>
                    </button>



                </form>
            </div>
            <div class="search-dropdown">
                <div class="btn-and-tips">
                    <div class="search-btn-header">
                        <label class="ck-btn first-btn" id="first-btn">
                            <input type="radio" {{ $currentRoute == 'artistListing' ? 'checked' : '' }} name="radio" value="Artists">
                            <span class="checkmark">Artists</span>
                        </label>
                        <label class="ck-btn second-btn" id="second-btn">
                            <input type="radio" {{ $currentRoute == 'fanListing' ? 'checked' : '' }} name="radio" value="Fans">
                            <span class="checkmark">Fans</span>
                        </label>
                        <label class="ck-btn third-btn" id="third-btn">
                            <input type="radio" {{ $currentRoute == 'songsList' ? 'checked' : '' }} name="radio" value="Songs">
                            <span class="checkmark">Songs</span>
                        </label>
                        <label class="ck-btn four-btn" id="four-btn">
                            <input type="radio" {{ $currentRoute == 'subscriptionsListing' ? 'checked' : '' }} name="radio" value="Subscriptions">
                            <span class="checkmark">Subscriptions</span>
                        </label>
                        <label class="ck-btn five-btn" id="five-btn">
                            <input type="radio" {{ $currentRoute == 'transactionListing' ? 'checked' : '' }} name="radio" value="Transactions">
                            <span class="checkmark">Transactions</span>
                        </label>
                        <label class="ck-btn six-btn" id="six-btn">
                            <input type="radio" {{ $currentRoute == 'cmsPageListing' ? 'checked' : '' }} name="radio" value="CMS">
                            <span class="checkmark">CMS</span>
                        </label>
                        <label class="ck-btn seven-btn" id="seven-btn">
                            <input type="radio" {{ $currentRoute == 'forumsListing' ? 'checked' : '' }} name="radio" value="Forums">
                            <span class="checkmark">Forums</span>
                        </label>
                    </div>
                    {{-- <p class="tip">Tip: Press the "f" key to get to the search bar faster.</p> --}}
                </div>

                <div class="not-found d-none">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path fill="#758CA3" fill-rule="evenodd" d="M6.52346564,10.8907479 L2.20710678,15.2071068 C1.81658249,15.5976311 1.18341751,15.5976311 0.792893219,15.2071068 C0.402368927,14.8165825 0.402368927,14.1834175 0.792893219,13.7928932 L5.10925208,9.47653436 C4.41079097,8.49571549 4,7.29583043 4,6 C4,2.6862915 6.6862915,0 10,0 C13.3137085,0 16,2.6862915 16,6 C16,9.3137085 13.3137085,12 10,12 C8.70416957,12 7.50428451,11.589209 6.52346564,10.8907479 Z M10,10 C12.209139,10 14,8.209139 14,6 C14,3.790861 12.209139,2 10,2 C7.790861,2 6,3.790861 6,6 C6,8.209139 7.790861,10 10,10 Z"></path>
                        </svg>
                    </span>
                    <p>No results found for <span class="serachValueNotFound"></span>.</p>
                </div>

                <div class="searching-data d-none">
                    <div class="results-column-header">
                        <a href=""></a>
                    </div>
                    <form action="" id="searchableForm" method="post">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="search" id="searchableValue" value="" />
                    </form>
                    <ul>
                        <li>
                            <a href="#">
                                <span></span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="app-header-right">

            <div class="header-btn-lg">
                <div class="widget-content">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">

                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">

                                    <div class="scroll-area-xs">
                                        <div class="scrollbar-container">
                                            <ul class="nav flex-column">

                                                <li class="nav-item">
                                                    <a href="{{url(config('app.adminPrefix').'/profile')}}" class="nav-link">My Profile</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{url(config('app.adminPrefix').'/change/password')}}" class="nav-link">Change Password
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{url(config('app.adminPrefix').'/logout')}}" class="nav-link">Logout

                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">

                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">
                                    {{ Session::get('username') }}
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>

                                </div>
                                <div class="widget-subheading">
                                    <!-- VP People Manager -->
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .header-search input { padding-top: 5px; }
</style>