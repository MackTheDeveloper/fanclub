
<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
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
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading text-secondary" >Menu</li>
                @if(Auth::guard('admin')->check())
                <li class="{{ request()->is('securefcbcontrol/dashboard') ? 'mm-active' : '' }}">
                    <a href="{{url('/securefcbcontrol/dashboard')}}">
                        <i class="active_icon metismenu-icon fa fa-rocket"></i>
                        Dashboard
                    </a>
                </li>
                @endif

                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_role_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_user_listing') === true))
                <li>
                    <a href="#">
                        <i class="active_icon metismenu-icon fa-2x fa fa-users"></i>
                        Users
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_role_listing') === true )
                        <li class="{{ (request()->is('securefcbcontrol/user/role/list') || request()->is('securefcbcontrol/user/role/edit/*') || request()->is('securefcbcontrol/user/role/add')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/user/role/list')}}">
                                <i class="metismenu-icon"></i>
                                Roles
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_user_listing') === true )
                        <li class="{{ ( request()->is('securefcbcontrol/user/list') || request()->is('securefcbcontrol/user/add') || request()->is('securefcbcontrol/user/edit/*') ) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/user/list')}}">
                                <i class="metismenu-icon">
                                </i>
                                Users
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_fans_listing') === true)
                <li class="{{ (request()->is('securefcbcontrol/fans/index') || request()->is('securefcbcontrol/fans/edit/*')) ? 'mm-active' : '' }}">
                    <a href="{{url('/securefcbcontrol/fans/index')}}">
                        <i class="active_icon metismenu-icon fa-2x fa fa-user"></i>
                        Fans
                    </a>
                </li>
                @endif

                @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_artist_listing') === true)
                <li class="{{ (request()->is('securefcbcontrol/artists/index') || request()->is('securefcbcontrol/artists/edit/*') || request()->is('securefcbcontrol/artist/add')) ? 'mm-active' : '' }}">
                    <a href="{{url('securefcbcontrol/artists/index')}}">
                        <i class="active_icon metismenu-icon fa-2x fa fa-user-circle"></i>
                        Artists
                    </a>
                </li>
                @endif

                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_categories_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_comment_listing') === true))
                <li>
                    <a href="#">
                        @if (request()->is('securefcbcontrol/music-categories/index') || request()->is('securefcbcontrol/music-genres/index') || request()->is('securefcbcontrol/music-languages/index') || request()->is('securefcbcontrol/songs/index')  || request()->is('securefcbcontrol/reviews-ratings/index') || request()->is('securefcbcontrol/playlists/index'))
                        <i class="active_icon metismenu-icon fa fa-music fa-beat"></i>
                        @else
                        <i class="active_icon metismenu-icon fa fa-music"></i>
                        @endif
                        Music Management
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_listing') === true )
                        <li class="{{ request()->is('securefcbcontrol/songs/index') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/songs/edit/*') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/songs/add') ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/songs/index')}}">
                                <i class="metismenu-icon"></i>
                                Songs
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_category_listing') === true )
                        <li class="{{ request()->is('securefcbcontrol/songs-category/index') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/songs-category/edit/*') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/songs-category/add') ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/songs-category/index')}}">
                                <i class="metismenu-icon"></i>
                                Songs Categories
                            </a>
                        </li>
                        @endif --}}
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_categories_listing') === true )
                        <li class="{{ (request()->is('securefcbcontrol/music-categories/index') || request()->is('securefcbcontrol/music-categories/edit/*') || request()->is('securefcbcontrol/music-categories/add')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/music-categories/index')}}">
                                <i class="metismenu-icon"></i>
                                Categories
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_genres_listing') === true )
                        <li class="{{ (request()->is('securefcbcontrol/music-genres/index') || request()->is('securefcbcontrol/music-genres/edit/*') || request()->is('securefcbcontrol/music-genres/add')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/music-genres/index')}}">
                                <i class="metismenu-icon"></i>
                                Genres
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_music_languages_listing') === true )
                        <li class="{{ (request()->is('securefcbcontrol/music-languages/index') || request()->is('securefcbcontrol/music-languages/edit/*') || request()->is('securefcbcontrol/music-languages/add')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/music-languages/index')}}">
                                <i class="metismenu-icon"></i>
                                Languages
                            </a>
                        </li>
                        @endif

                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_songs_listing') === true)
                        <li class="{{ request()->is('securefcbcontrol/songs/index') || request()->is('securefcbcontrol/songs/edit/*') || request()->is('securefcbcontrol/songs/add') ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/songs/index')}}">
                                <i class="metismenu-icon"></i>
                                Songs
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_reviewandlisting_listing') === true)
                            <li class="{{ (request()->is('securefcbcontrol/reviews-ratings/index') || request()->is('securefcbcontrol/songs/edit/*') || request()->is('securefcbcontrol/songs/add')) ? 'mm-active' : '' }}">
                                <a href="{{url('securefcbcontrol/reviews-ratings/index')}}">
                                    <i class="metismenu-icon"></i>
                                    Reviews & Ratings
                                </a>
                            </li>
                        @endif
                        {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_playlist_listing') === true)
                        <li class="{{ (request()->is('securefcbcontrol/playlists/index') || request()->is('securefcbcontrol/playlists/edit/*') || request()->is('securefcbcontrol/playlists/add')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/playlists/index')}}">
                                <i class="metismenu-icon"></i>
                                fanclub Playlists
                            </a>
                        </li>
                        @endif --}}
                        {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_comment_listing') === true )
                        <li class="{{ request()->is('securefcbcontrol/blog-comment/index') ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/blog-comment/index')}}">
                                <i class="metismenu-icon"></i>
                                Songs Comments
                            </a>
                        </li>
                        @endif --}}
                    </ul>
                </li>
                @endif

                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_subscription_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_blog_comment_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_transaction_listing') === true))
                    <li>
                        <a href="#">
                            @if (request()->is('securefcbcontrol/subscriptions/index') || request()->is('securefcbcontrol/subscription-plan/index') || request()->is('securefcbcontrol/transaction/index'))
                            <b><i class="active_icon metismenu-icon fa-3x fas fa-hand-holding-usd fa-flip"></i></b>
                            @else
                            <b><i class="active_icon metismenu-icon fa-3x fas fa-hand-holding-usd"></i></b>
                            @endif
                            Sales
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_subscription_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/subscriptions/index') || request()->is('securefcbcontrol/subscriptions/edit/*') || request()->is('securefcbcontrol/subscriptions/add')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/subscriptions/index')}}">
                                        <i class="metismenu-icon"></i>
                                        Subscriptions
                                    </a>
                                </li>
                            @endif
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_transaction_listing') === true )
                            <li class="{{ (request()->is('securefcbcontrol/subscription-plan/index') || request()->is('securefcbcontrol/subscription-plan/edit/*') )}}">
                                <a href="{{url('securefcbcontrol/subscription-plan/index')}}">
                                    <i class="metismenu-icon"></i>
                                    Subscription Plans
                                </a>
                            </li>
                            @endif
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_subscription_plan_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/transaction/index') || request()->is('securefcbcontrol/transaction/edit/*') || request()->is('securefcbcontrol/transaction/add')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/transaction/index')}}">
                                        <i class="metismenu-icon"></i>
                                        Transactions
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>
                @endif
                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_app_listing') === true))
                    <li>
                        <a href="#">
                            @if (request()->is('securefcbcontrol/how-it-works/index') || request()->is('securefcbcontrol/how-it-works-app/index'))
                                <i class="active_icon metismenu-icon fa fa-question-circle fa-spin"></i>
                            @else
                                <i class="metismenu-icon fa fa-question-circle"></i>
                            @endif
                            How It Works
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/how-it-works/index') || request()->is('securefcbcontrol/how-it-works/edit/*') || request()->is('securefcbcontrol/how-it-works/add')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/how-it-works/index')}}">
                                        <i class="metismenu-icon"></i>
                                        How It Works Web
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <ul>
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_how_it_works_app_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/how-it-works-app/index') || request()->is('securefcbcontrol/how-it-works-app/edit/*') || request()->is('securefcbcontrol/how-it-works-app/add')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/how-it-works-app/index')}}">
                                        <i class="metismenu-icon"></i>
                                        How It Works App
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_listing') === true))
                    <li>
                        <a href="#">
                            @if (request()->is('securefcbcontrol/faq/index') ||request()->is('securefcbcontrol/faq-tags/index') || request()->is('securefcbcontrol/faq/edit/*') || request()->is('securefcbcontrol/faq/add'))
                            <i class="active_icon metismenu-icon fa fa-solid fa-sync fa-spin"></i>
                            @else
                            <i class="active_icon metismenu-icon fas fa-sync"></i>
                            @endif
                            FAQ
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            @if(whoCanCheck(config('app.arrWhoCanCheck'),'admin_faq_tags_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/faq-tags/index') || request()->is('securefcbcontrol/faq-tags/edit/*') || request()->is('securefcbcontrol/faq-tags/add')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/faq-tags/index')}}">
                                        <i class="metismenu-icon"></i>
                                        FAQ Tags
                                    </a>
                                </li>
                            @endif
                        </ul>
                        <ul>
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_faq_listing') === true )
                                <li class="{{ (request()->is('securefcbcontrol/faq/index') || request()->is('securefcbcontrol/faq/index/*') || request()->is('securefcbcontrol/faq/index')) ? 'mm-active' : '' }}">
                                    <a href="{{url('securefcbcontrol/faq/index')}}">
                                        <i class="metismenu-icon"></i>
                                        FAQ Questions
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_general_setting_update') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_cms_page_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_email_templates_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_cms_page_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_emojis_comments_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_landing_interest_listing') === true))
                <li>
                    <a href="#">
                        @if ((request()->is('securefcbcontrol/settings')) || request()->is('securefcbcontrol/security-questions/index') || request()->is('securefcbcontrol/cms-page/list') || (request()->is('securefcbcontrol/email-templates/index')) || (request()->is('securefcbcontrol/emojis-and-comments/index')) || (request()->is('securefcbcontrol/footer-link/index')) || (request()->is('securefcbcontrol/emojis-comments/index')) || (request()->is('securefcbcontrol/landing-interest/index')))
                        <i class="active_icon metismenu-icon fa fa-solid fa-cog fa-spin fa-spin-reverse"></i>
                        @else
                        <i class="active_icon metismenu-icon fa fa-cog"></i>
                        @endif
                        Global Settings
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                       @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_general_setting_update') === true )
                        <li class="{{ (request()->is('securefcbcontrol/settings') || request()->is('securefcbcontrol/faq/addFaq') || request()->is('securefcbcontrol/faq/editFaq/*')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/settings')}}">
                                <i class="metismenu-icon">
                                </i>
                                General Settings
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_cms_page_listing') === true )
                        <li class="{{ request()->is('securefcbcontrol/cms-page/list') ? 'mm-active' : '' }}">
                            <a href="{{url('/securefcbcontrol/cms-page/list')}}">
                                <i class="active_icon metismenu-icon pe-7s-wallet"></i>
                                CMS Pages
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_email_templates_listing') === true )
                        <li class="{{ (request()->is('securefcbcontrol/email-templates/index') || request()->is('securefcbcontrol/email-templates/add') || request()->is('securefcbcontrol/email-templates/edit/*')) ? 'mm-active' : '' }}">
                            <a href="{{url('securefcbcontrol/email-templates/index')}}">
                                <i class="active_icon metismenu-icon pe-7s-mail"></i>
                                Email Templates
                            </a>
                        </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_cms_page_listing') === true )
                            <li class="{{ request()->is('securefcbcontrol/footer-link/index') ? 'mm-active' : '' }}">
                                <a href="{{url('/securefcbcontrol/footer-link/index')}}">
                                    <i class="active_icon metismenu-icon pe-7s-wallet"></i>
                                    Footer
                                </a>
                            </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_emojis_comments_listing') === true )
                            <li class="{{ request()->is('securefcbcontrol/emojis-and-comments/index') ? 'mm-active' : '' }}">
                                <a href="{{url('securefcbcontrol/emojis-and-comments/index')}}">
                                    <i class="active_icon metismenu-icon pe-7s-wallet"></i> Emojis & Comments
                                </a>
                            </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_security_question_listing') === true )
                            <li class="{{ request()->is('securefcbcontrol/security-questions/index') ? 'mm-active' : '' }}">
                                <a href="{{url('securefcbcontrol/security-questions/index')}}">
                                    <i class="active_icon metismenu-icon pe-7s-wallet"></i> Security Questions
                                </a>
                            </li>
                        @endif
                        @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_landing_interest_listing') === true )
                            <li class="{{ request()->is('securefcbcontrol/landing-interest/index') ? 'mm-active' : '' }}">
                                <a href="{{url('securefcbcontrol/landing-interest/index')}}">
                                    <i class="active_icon metismenu-icon pe-7s-wallet"></i> Customer Interests
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if((whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_banner_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_cms_page_listing') === true || whoCanCheck(config('app.arrWhoCanCheck'), 'admin_email_templates_listing') === true))
                    <li>
                        <a href="#">
                            @if ((request()->is('securefcbcontrol/homepagebanner/index')) || request()->is('securefcbcontrol/homepage-component/index') || (request()->is('securefcbcontrol/dynamic-groups/index')))
                            <i class="active_icon metismenu-icon fa fa-home fa-flip"></i>
                            @else
                            <i class="active_icon metismenu-icon fa fa-home"></i>
                            @endif
                            Homepage Management
                            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                        </a>
                        <ul>
                            {{-- @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_general_setting_update') === true )
                            <li class="{{ request()->is('securefcbcontrol/settings') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/faq/addFaq') ? 'mm-active' : '' }} {{ request()->is('securefcbcontrol/faq/editFaq/*') ? 'mm-active' : '' }}">
                                <a href="{{url('securefcbcontrol/settings')}}">
                                    <i class="metismenu-icon">
                                    </i>
                                    General Settings
                                </a>
                            </li>
                            @endif --}}
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_banner_listing') === true )
                                <li class="{{ request()->is('securefcbcontrol/homepagebanner/index') ? 'mm-active' : '' }}">
                                    <a href="{{url('/securefcbcontrol/homepagebanner/index')}}">
                                        <i class="active_icon metismenu-icon pe-7s-wallet"></i>
                                        Home Page Banners
                                    </a>
                                </li>
                            @endif
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_homepage_component_listing') === true )
                                <li class="{{ request()->is('securefcbcontrol/homepage-component/index') ? 'mm-active' : '' }}">
                                    <a href="{{url('/securefcbcontrol/homepage-component/index')}}">
                                        <i class="active_icon metismenu-icon pe-7s-wallet"></i>
                                        Home Page Components
                                    </a>
                                </li>
                            @endif
                            @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_dynamic_group_listing') === true )
                                <li class="{{ request()->is('securefcbcontrol/dynamic-groups/index') ? 'mm-active' : '' }}">
                                    <a href="{{url('/securefcbcontrol/dynamic-groups/index')}}">
                                        <i class="active_icon metismenu-icon pe-7s-wallet"></i>
                                        Dynamic Groups
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_forum_listing') === true)
                <li class="{{ request()->is('securefcbcontrol/forums/index') ? 'mm-active' : '' }}">
                    <a href="{{url('/securefcbcontrol/forums/index')}}">
                        @if ((request()->is('securefcbcontrol/forums/index')) || request()->is('securefcbcontrol/forums/addForum') || request()->is('securefcbcontrol/forums/editForum/*'))
                        <i class="active_icon metismenu-icon fas fa-comments fa-beat"></i>
                        @else
                        <i class="active_icon metismenu-icon fas fa-comments"></i>
                        @endif
                        Forums
                    </a>
                </li>
                @endif
                @if(whoCanCheck(config('app.arrWhoCanCheck'), 'admin_contact_us_listing') === true )
                <li class="{{ request()->is('securefcbcontrol/contactUs') ? 'mm-active' : '' }}">
                    <a href="{{url('/securefcbcontrol/contactUs')}}">
                        @if (request()->is('securefcbcontrol/contactUs'))
                        <i class="active_icon metismenu-icon fas fa-headset fa-beat"></i>
                        @else
                        <i class="active_icon metismenu-icon fas fa-headset "></i>
                        @endif
                        Contact Enquiries
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
