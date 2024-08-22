<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Fan;
use App\Models\User;
use App\Models\HomePageComponent;
use App\Models\HomePageBanner;
use App\Models\HowItWorksApp;
use App\Models\DynamicGroupItems;
use App\Models\FanFavouriteArtists;
use App\Models\FanFavouriteGroups;
use App\Models\FanFavouriteSongs;
use App\Models\FanPlaylist;
use App\Models\HowItWorks;
use App\Models\RecentPlayed;
use Validator;
use Mail;
use Hash;

class HomePageAPIController extends BaseController
{
    public function homePageData()
    {
        $authId = User::getLoggedInId();

        //home page banner data
        $HomePageBanner = HomePageBanner::getListApi();
        $HomePageBannerComponent = [
            "componentId" => "HomePageBannerComponent",
            "sequenceId" => "1",
            "isActive" => "1",
            "isApp" => "1",
            "HomePageBannerData" => $HomePageBanner
        ];
        $result[] = $HomePageBannerComponent;
        
        //End of home page banner data
        //home page how it works data
        // $HowItWorksApp = HowItWorksApp::getListApi();
        // $HowItWorksAppComponent = [
        //     "componentId" => "HowItWorksAppComponent",
        //     "sequenceId" => "3",
        //     "isActive" => "1",
        //     "isApp" => "1",
        //     //"HowItWorksAppData" => $HowItWorksApp
        //     "HowItWorksAppData" => [
        //         "label" => [
        //             'mainLabel' => 'How It Works',
        //             'mainDescription' => 'Joining fanclub is as simple as 1, 2, 3! Follow these easy steps to unlock a treasure trove of music and to discover a host of new artists. With access to all artists, you can create the perfect soundtrack to your life. Created by music fans for fans, we believe that artists should be rewarded for the joy they bring.',
        //             'fanLabel' => 'Fan',
        //             'fanDescription' => 'Join the fanclub community today and start building your perfect music collection of exclusive performances from your favourite artists.',
        //             'artistLabel' => 'Artist',
        //             'artistDescription' => 'Want to be compensated fairly for your music? Join fanclub today and start building your fan base. Share your story, your music and your gig guide.  Increase your audience and earn $$$.',
        //         ],
        //         "list" => $HowItWorksApp
        //     ],
        // ];
        // $result[] = $HowItWorksAppComponent;
        //End of home page how it works data
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $navigateGuest = "1";
        if (!$authId) {
            //home page how it works web data
            $HowItWorksWeb = HowItWorks::getListApi();
            $HowItWorksWebComponent = [
                "componentId" => "HowItWorksWebComponent",
                "componentSlug" => "how-it-works",
                "sequenceId" => "2",
                "isActive" => "1",
                "isApp" => "0",
                "HowItWorksWebData" => [
                    "label" => [
                        'mainLabel' => 'How It Works',
                        'mainDescription' => 'Joining fanclub is as simple as 1, 2, 3! Follow these easy steps to unlock a treasure trove of music and to discover a host of new artists. With access to all artists, you can create the perfect soundtrack to your life. Created by music fans for fans, we believe that artists should be rewarded for the joy they bring.',
                        'fanLabel' => 'Fan',
                        'fanDescription' => 'Join the fanclub community today and start building your perfect music collection of exclusive performances from your favourite artists.',
                        'artistLabel' => 'Artist',
                        'artistDescription' => 'Want to be compensated fairly for your music? Join fanclub today and start building your fan base. Share your story, your music and your gig guide.  Increase your audience and earn $$$.',

                    ],
                    "list" => $HowItWorksWeb
                ],
                //"HowItWorksWebData" => $HowItWorksWeb
            ];
            $result[] = $HowItWorksWebComponent;
            //End of home page how it works web data

            $whyChoose = [
                "componentId" => "whyChoose",
                "componentSlug" => "whyChoose",
                "title" => "Favourite Artists",
                "sequenceId" => "3",
                "isActive" => "1",
                "isApp" => "1",
                "whyChooseData" => [
                    "title"=> "Why Choose fanclub",
                    "description"=> "A sensational new media platform for music fans worldwide to enjoy a wide range of exclusive music content from their favourite musicians and a whole host of new musicians, not discovered yet.<br> <br> Receive exciting news from your artist, enjoy unique versions of your favourite songs, access to soundchecks and exciting new footage from your artist’s gigs along with their up to date event guides./n /n In addition to these benefits you also have the opportunity to post reviews directly to your artist and chat with other like-minded fans worldwide, in our fanclub chatroom/forum. This great value is available to you for only US$49.99 for an annual subscription",
                    "bullets"=>[
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Exclusive Content"
                        ],
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Access to All Artists"
                        ],
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Unlimited Downloads"
                        ],
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Quality Music and Video Streaming"
                        ],
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Artist News & Event Calendars"
                        ],
                        [
                            "img"=> url('public/assets/frontend/img/music.png'),
                            "subTitle"=> "Create Fully Customisable Playlists"
                        ],
                    ]
                ],
            ];
            $result[] = $whyChoose;
        }

        
        if ($authId) {
            $recentPlayed = RecentPlayed::getListApi($authId, 25);
            $fanFavouriteSongs = FanFavouriteSongs::getListApi($authId, 25);
            $myCollections = [
                "componentId" => "myCollections",
                "componentSlug" => "recently-played",
                "title" => "My Collection",
                "sequenceId" => "4",
                "isActive" => "1",
                "isApp" => "1",
                "myCollectionsData" => [
                    [
                        "sequenceId" => "1",
                        "isActive" => (count($recentPlayed)) ? "1" : "0",
                        "title" => "Recently Played",
                        "componentSlug" => "recently-played",
                        "navigate" => $navigateGuest,
                        "navigateType" => "6",
                        "navigateTo" => "fan-recent-songs",
                        "list" => $recentPlayed
                    ],
                    [
                        "sequenceId" => "2",
                        "isActive" => (count($fanFavouriteSongs)) ? "1" : "0",
                        "title" => "My Collection",
                        "componentSlug" => "my-collection",
                        "navigate" => $navigateGuest,
                        "navigateType" => "6",
                        "navigateTo" => "fan-favourite-songs",
                        "list" => $fanFavouriteSongs
                    ]
                ],
            ];
            $result[] = $myCollections;

            $myRecent = [
                "componentId" => "myRecent",
                "componentSlug" => "recently-played",
                "sequenceId" => "5",
                "isActive" => "0",
                "isApp" => "0",
                "title" => "Recently Played",
                "navigate" => $navigateGuest,
                "navigateType" => "6",
                "navigateTo" => "fan-recent-songs",
                "myRecentData" => $recentPlayed
            ];
            $result[] = $myRecent;

            $fanPlaylist = FanPlaylist::getListApi($authId);
            $myPlaylist = [
                "componentId" => "myPlaylist",
                "componentSlug" => "my-playlists",
                "title" => "My Playlists",
                "sequenceId" => "6",
                "imageShape" => "square",
                "navigate" => $navigateGuest,
                "navigateType" => "8",
                "navigateTo" => "fan-playlist",
                "isActive" => (count($fanPlaylist))?"1":"0",
                "isApp" => "1",
                "myPlaylistData" => $fanPlaylist,
            ];
            $result[] = $myPlaylist;

            $fanFavouriteArtists = FanFavouriteArtists::getListApi("1", "", ['fan_id' => $authId], 10);
            $favArtist = [
                "componentId" => "favArtist",
                "componentSlug" => "my-artists",
                "imageShape" => "circle",
                "title" => "My Artists",
                "sequenceId" => "7",
                "navigate" => $navigateGuest,
                "navigateType" => "10",
                "navigateTo" => "fan-favourite-artists",
                "isActive" => (count($fanFavouriteArtists))?"1":"0",
                "isApp" => "1",
                "favArtistData" => $fanFavouriteArtists,
            ];
            $result[] = $favArtist;

            $myCollectionsWeb = [
                "componentId" => "myCollectionsWeb",
                "sequenceId" => "8",
                "isActive" => "0",
                "isApp" => "0",
                "title" => "My Collection",
                "navigate" => $navigateGuest,
                "navigateType" => "6",
                "navigateTo" => "fan-favourite-songs",
                "myCollectionsWebData" => $fanFavouriteSongs
            ];
            $result[] = $myCollectionsWeb;

            $fanFavouriteGroups = FanFavouriteGroups::getListApi("1", "", ['fan_id' => $authId], 10);
            $favPlaylist = [
                "componentId" => "favPlaylist",
                "componentSlug" => "fav-playlists",
                "title" => "fanclub Playlists",
                "imageShape" => "square",
                "sequenceId" => "9",
                "navigate" => $navigateGuest,
                "navigateType" => "4",
                "navigateTo" => "fan-favourite-playlist",
                "isActive" => (count($fanFavouriteGroups))?"1":"0",
                "isApp" => "1",
                "favPlaylistData" => $fanFavouriteGroups,
            ];
            $result[] = $favPlaylist;
            
            // $myCollections = [
            //     "componentId" => "myCollections",
            //     "title" => "My Collection",
            //     "sequenceId" => "6",
            //     "isActive" => "1",
            //     "isApp" => "1",
            //     "myCollectionsData" => FanFavouriteSongs::getListApi($authId, 10),
            // ];
            // $result[] = $myCollections;
        }

        // Home page header menu
        if ($authId) {
            // pre(count($myCollections['myCollectionsData'][0]['list']));
            $homePageHeaderMenuData = HomePageComponent::getHomePageHeaderMenuData(count($myPlaylist['myPlaylistData']), count($favPlaylist['favPlaylistData']), count($myCollections['myCollectionsData'][1]['list']), count($favArtist['favArtistData']), count($myCollections['myCollectionsData'][0]['list']));
        } else {
            $homePageHeaderMenuData = HomePageComponent::getHomePageHeaderMenuData();
        }
        $HomePageHeaderMenu = [
            "componentId" => "HomePageHeaderMenu",
            "sequenceId" => "0",
            "isActive" => "1",
            "isApp" => "1",
            "HomePageHeaderMenuData" => $homePageHeaderMenuData
        ];
        $result[] = $HomePageHeaderMenu;
        
        //home page component data
        $HomePageComponentList = HomePageComponent::getListApi(11);
        $HomePageComponent = [
            "componentId" => "HomePageComponent",
            "sequenceId" => "10",
            "isActive" => "1",
            "isApp" => "1",
            "HomePageComponentData" => $HomePageComponentList
        ];
        $result[] = $HomePageComponent;
        //End of home page component data
        
        $return = $result;

        // pre($return);

        return $this->sendResponse($return, 'Home Page Data listed successfully.');
    }

    //To be call on view all
    public function getViewAll(Request $request)
    {
        $authId = User::getLoggedInId();
        $input = $request->all();
        $type = $input['type'];
        $related_id = $input['related_id'];
        $viewAll = DynamicGroupItems::getviewAll($type, $related_id);
        if ($viewAll) {
            $return = [
                "componentId" => "groupList",
                "sequenceId" => "1",
                "isActive" => "1",
                "groupListData" => $viewAll
            ];
        }
        return $this->sendResponse($return, 'View all Listed Successfully.');
    }
}
