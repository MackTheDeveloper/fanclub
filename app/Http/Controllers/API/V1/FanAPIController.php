<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\DynamicGroups;
use Illuminate\Support\Facades\Auth;
use App\Models\Fan;
use App\Models\Reviews;
use App\Models\User;
use App\Models\FanPlaylist;
use App\Models\FanPlaylistSongs;
use App\Models\FanFavouriteSongs;
use App\Models\FanFavouriteArtists;
use App\Models\UserProfilePhoto;
use App\Models\FanFavouriteGroups;
use App\Models\RecentPlayed;
use App\Models\Songs;
use App\Models\SubscriptionPlan;
use Validator;
use Mail;
use Hash;

class FanAPIController extends BaseController
{

    public function index()
    {
        $user_id = Auth::user()->id;
        // $data = Reviews::userWiseData($user_id);
        $data = [];
        $return = [
            "componentId" => "myReviews",
            "sequenceId" => "1",
            "isActive" => "1",
            "myReviewsData" => $data
        ];
        return $this->sendResponse($return, 'Reviews listed successfully.');
    }


    public function detail()
    {
        $authId = User::getLoggedInId();
        $data = Fan::getDetailApi($authId);
        $return = [
            "componentId" => "profile",
            "sequenceId" => "1",
            "isActive" => "1",
            "profileData" => $data
        ];
        return $this->sendResponse($return, 'your profile retrived successfully.');
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $authId = User::getLoggedInId();
        if ($request->hasFile('profile_pic')) {
            //$fileObject = $request->file('profile_pic');
            //UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject, $authId);
            if (isset($input['hiddenPreviewImg'])) {
                $fileObject = $request->file('profile_pic');
                UserProfilePhoto::uploadAndSaveProfileViaCropped($fileObject, $authId, $input);
                unset($input['hiddenPreviewImg']);
            } else {
                $fileObject = $request->file('profile_pic');
                UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject, $authId);
            }
        } else {
            if (isset($input['imageEncoded'])) {
                UserProfilePhoto::uploadAndSaveProfilePhotoApi($input['imageEncoded'], $authId);
                unset($input['imageEncoded']);
            }
        }
        $data = Fan::updateExist($input);
        if ($data['success']) {
            return $this->sendResponse([], 'Your profile details has been updated successfully.');
        } else {
            return $this->sendError($data['data'], 'Something went wrong.');
        }
    }

    public function playlistindex()
    {
        $authId = User::getLoggedInId();
        $data = FanPlaylist::getListApi($authId);
        $return = [
            "componentId" => "playlist",
            "sequenceId" => "1",
            "isActive" => "1",
            "playlistData" => $data
        ];
        return $this->sendResponse($return, 'Playlist listed successfully.');
    }


    public function playlistsongs($playlistId)
    {
        // $user_id = Auth::user()->id;
        $data = FanPlaylistSongs::getListApi($playlistId);
        $totalSongs = strval(count($data));
        // $totalSongs = count($data) . ' ' . "Songs";
        $playlistData = FanPlaylist::where('id', $playlistId)->first();
        $playlistImage = FanPlaylistSongs::getPlaylistIcon($playlistId);
        // $return = [
        //     [
        //         "componentId" => "playlistTopComponent",
        //         "sequenceId" => "1",
        //         "isActive" => "1",
        //         "playlistTopComponentData" =>
        //         [
        //             "image" => $playlistImage,
        //             "name" => $playlistData['playlist_name'],
        //             "desc" => isset($totalSongs) ? $totalSongs : "",
        //             "isFav" => "1"
        //         ],

        //     ],
        //     [
        //         "componentId" => "playlistSongs",
        //         "sequenceId" => "1",
        //         "isActive" => "1",
        //         "playlistSongsData" => [
        //             "list" => $data
        //         ]

        //     ]

        // ];
        $return = [
            "componentId" => "playlist",
            "sequenceId" => "1",
            "isActive" => "1",
            "playlistData" => [
                "playListId" => $playlistId,
                "image" => $playlistImage,
                "name" => $playlistData['playlist_name'],
                "slug" => $playlistData['slug'],
                "page" => Songs::getPageById(1),
                "desc" => isset($totalSongs) ? $totalSongs : "",
                "isFav" => "1",
                "songList" => $data
            ]

        ];
        return $this->sendResponse($return, 'Playlist Songs listed successfully.');
    }

    public function playlistSongAdd(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'song_id' => 'required',
                'playlist_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            // $user_id = Auth::user()->id;
            $input = $request->all();
            $data = FanPlaylistSongs::addSongToPlaylist($input);
            if ($data) {
                return $this->sendResponse([], getResponseMessage('SongAddedIntoCustomPlaylist'));
            } else {
                return $this->sendError([], 'Something went wrong.');
            }
        }
    }

    public function playlistCreateSongAdd(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'playlist_name' => 'required'
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            // $user_id = Auth::user()->id;
            $input = $request->all();
            $data = FanPlaylistSongs::createPlaylistAddSongs($input);
            if ($data) {
                return $this->sendResponse([], getResponseMessage('PlaylistCreatedAndAddedSong', $data['data']['playlistName']));
            } else {
                return $this->sendError([], 'Something went wrong.');
            }
        }
    }

    public function playlistSongRemove($playlistSongId)
    {
        // $user_id = Auth::user()->id;
        $data = FanPlaylistSongs::has('song')->where('id', $playlistSongId)->first();
        if ($data) {
            $data->delete();
            return $this->sendResponse([], getResponseMessage('RemoveSongFromCustomPlaylist', $data->song->name));
        } else {
            return $this->sendError([], 'Something went wrong.');
        }
    }

    public function playlistRemove($fanPlaylistId)
    {
        // $user_id = Auth::user()->id;
        $data = FanPlaylist::where('id', $fanPlaylistId)->first();
        if ($data) {
            $data->delete();
            return $this->sendResponse([], getResponseMessage('DeleteCustomPlaylist'));
        } else {
            return $this->sendError([], 'Something went wrong.');
        }
    }

    public function favouriteSongs()
    {
        $authId = User::getLoggedInId();
        $data = FanFavouriteSongs::getListApi($authId);
        $return = [
            "componentId" => "favSongs",
            "sequenceId" => "1",
            "isActive" => "1",
            "favSongsData" => $data
        ];
        return $this->sendResponse($return, 'Favourite Songs listed successfully.');
    }

    public function favouriteSongAction(Request $request)
    {
        $songId = $request->song_id;
        if ($songId) {
            $authId = User::getLoggedInId();
            $data = FanFavouriteSongs::where('fan_id', $authId)->where('song_id', $songId)->first();
            if ($data) {
                $data->delete();
                return $this->sendResponse([], 'Song Removed from your favourites successfully.');
            } else {
                FanFavouriteSongs::create([
                    'fan_id' => $authId,
                    'song_id' => $songId
                ]);
                return $this->sendResponse([], 'Song added to your favourites successfully.');
            }
        } else {
            return $this->sendError([], 'Missing Song for make it favourite.');
        }
    }


    // public function favouriteArtists()
    // {
    //     $authId = User::getLoggedInId();
    //     $data = FanFavouriteArtists::getListApi($authId);
    //     $return = [
    //         "componentId" => "favArtists",
    //         "sequenceId" => "1",
    //         "isActive" => "1",
    //         "favArtistsData" => $data
    //     ];
    //     return $this->sendResponse($return, 'Favourite Artist listed successfully.');
    // }

    public function favouriteArtistAction(Request $request)
    {
        $artistId = $request->artist_id;
        if ($artistId) {
            $authId = User::getLoggedInId();
            $data = FanFavouriteArtists::where('fan_id', $authId)->where('artist_id', $artistId)->first();
            if ($data) {
                $data->delete();
                return $this->sendResponse([], 'Artist Removed from your favourites successfully.');
            } else {
                FanFavouriteArtists::create([
                    'fan_id' => $authId,
                    'artist_id' => $artistId
                ]);
                return $this->sendResponse([], 'Artist added to your favourites successfully.');
            }
        } else {
            return $this->sendError([], 'Missing Artist for make it favourite.');
        }
    }

    public function playlistSongsNew($playlistId)
    {
        // $user_id = Auth::user()->id;
        $fanPlaylistData = FanPlaylist::getListById($playlistId);
        $data = FanPlaylistSongs::getListApiNew($playlistId);
        $return = [
            "componentId" => "playlistSongs",
            "sequenceId" => "1",
            "page" => Songs::getPageById(1),
            "isActive" => "1",
            "fanPlaylistData" => $fanPlaylistData,
            "playlistSongsData" => $data
        ];
        // pre($return);
        return $this->sendResponse($return, 'Playlist Songs listed successfully.');
    }

    public function favouriteSongsNew($search = "")
    {
        //patch by nivedita for search page see all//
        $authId = User::getLoggedInId();
        $data = FanFavouriteSongs::getListApiNew($authId, '', $search);
        $return = [
            "componentId" => "favSongs",
            "sequenceId" => "1",
            "page" => Songs::getPageById(3),
            "isActive" => "1",
            "favSongsData" => $data
        ];
        return $this->sendResponse($return, 'Favourite Songs listed successfully.');
    }

    public function recentSongsNew($search = "")
    {
        //patch by nivedita for search page see all//
        $authId = User::getLoggedInId();
        $data = RecentPlayed::getListApiNew($authId, '', $search);
        $return = [
            "componentId" => "favSongs",
            "sequenceId" => "1",
            "page" => Songs::getPageById(4),
            "isActive" => "1",
            "favSongsData" => $data
        ];
        return $this->sendResponse($return, 'Favourite Songs listed successfully.');
    }

    public function favouritePlaylist(Request $request)
    {
        $authId = User::getLoggedInId();
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $filter = isset($input['filter']) ? $input['filter'] : [];
        $filter['fan_id'] = $authId;
        $data = FanFavouriteGroups::getListApi($page, $search, $filter);
        $return[] = [
            "componentId" => "favPlaylist",
            "title" => "Favourite Playlists",
            "sequenceId" => "1",
            "page" => Songs::getPageById(2),
            "isActive" => "1",
            "pageSize" => $data['limit'],
            "pageNo" => (string) $data['page'],
            "favPlaylistData" => [
                "groupDetail" => $data['data']
            ]
        ];
        return $this->sendResponse($return, 'Favourite Playlists listed successfully.');
    }

    public function dynamicGroup($search = "")
    {
        $authId = User::getLoggedInId();
        $return = [
            "componentId" => "favPlaylist",
            "title" => "Favourite Playlists",
            "sequenceId" => "1",
            "page" => Songs::getPageById(2),
            "isActive" => "1",
            "favPlaylistData" => FanFavouriteGroups::getListApi($authId, '', $search),
        ];
        return $this->sendResponse($return, 'Favourite Playlists listed successfully.');
    }

    public function myPlaylist($search = "")
    {
        $authId = User::getLoggedInId();
        $return = [
            "componentId" => "myPlaylist",
            "title" => "My Playlists",
            "sequenceId" => "1",
            "page" => Songs::getPageById(1),
            "isActive" => "1",
            "playlistData" => FanPlaylist::getListApi($authId, '', $search),
        ];
        return $this->sendResponse($return, 'Fan Playlist listed successfully.');
    }

    public function updateFanPlaylist(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'playlist_name' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            // $user_id = Auth::user()->id;
            $input = $request->all();
            $data = FanPlaylist::updateFanPlaylist($input);
            if ($data) {
                return $this->sendResponse(['data' => $data['data']], getResponseMessage('UpdateCustomPlaylistName'));
            } else {
                return $this->sendError([], 'Something went wrong.');
            }
        }
    }

    public function getMySubscriptonData()
    {
        $authId = User::getLoggedInId();
        $benefits = [
            "componentId" => "benefits",
            "title" => "Benefits",
            "sequenceId" => "1",
            "isActive" => "1",
            "benefitsData" => SubscriptionPlan::getBenifits(),
        ];
        $mySubscription = [
            "componentId" => "mySubscription",
            "title" => "Current Subscription",
            "sequenceId" => "2",
            "isActive" => "1",
            "mySubscriptionData" => User::getMySubscriptonData($authId),
        ];

        $isYearlySubscribed = User::getAttrById($authId, 'has_yearly_subscription');
        $hasYearlySubscription = [
            "componentId" => "hasYearlySubscription",
            "title" => "Upcoming Subscription",
            "sequenceId" => "3",
            "isActive" => ($isYearlySubscribed == '1') ? "1" : "0",
            "hasYearlySubscriptionData" => User::hasYearlySubscription($authId),
        ];

        $isActiveAnual = User::getAttrById($authId, 'current_subscription');
        $annualSubscription = [
            "componentId" => "annualSubscription",
            "title" => "Annual Subscription",
            "description" => "Would you like to upgrade your plan?",
            "sequenceId" => "4",
            "isActive" => ($isActiveAnual == "1") ? "1" : "0",
            "annualSubscriptionData" => SubscriptionPlan::getAnnualSubscriptionData(),
        ];

        $subscriptionCancelled = User::getAttrById($authId, 'subscription_cancelled');

        $showUpgrade = $subscriptionCancelled ? "0" : (($isActiveAnual == '2')?"0":"1");
        $upgradeButton = [
            "componentId" => "upgradeButton",
            "title" => "Upgrade Now",
            "type" => "button",
            "navigateTo" => "subscription-upgrade",
            "sequenceId" => "5",
            "isActive" => ($showUpgrade == "1") ? "1" : "0"
        ];

        $showCancel = $subscriptionCancelled?"0":"1";
        $cancelButton = [
            "componentId" => "cancelButton",
            "title" => "Cancel Subscription",
            "type" => "button",
            "navigateTo" => "subscription-cancel",
            "sequenceId" => "6",
            "isActive" => ($showCancel == "1") ? "1" : "0"
        ];
        // subscription_cancelled
        $return = [$benefits, $mySubscription, $hasYearlySubscription, $annualSubscription, $upgradeButton, $cancelButton];
        // pre($return);
        return $this->sendResponse($return, 'my subscription listed successfully.');
    }

    /* public function getFanPlaylistSongsForMusicPlayer(Request $request)
    {
        $authId = User::getLoggedInId();
        $slug = $request->slug;
        $playlistData = FanPlaylist::where('slug', $slug)->where('user_id', $authId)->first();
        if ($playlistData) {
            $playlistId = $playlistData->id;
            $firstSongData = FanPlaylistSongs::getFanPlaylistSongsForMusicPlayer($playlistId,'first');
            $data = FanPlaylistSongs::getFanPlaylistSongsForMusicPlayer($playlistId,'');
            $return = [
                "componentId" => "FanPlaylistSongsForMusicPlayer",
                "sequenceId" => "1",
                "isActive" => "1",
                "firstSong" => $firstSongData,
                "allSongsData" => $data
            ];
            return $this->sendResponse($return, 'Songs listed successfully.');
        }else{
            return $this->sendError([], 'No songs found.');
        }
    } */

    public function downloadAll(Request $request)
    {
        $authId = User::getLoggedInId();
        $slug = $request->slug;
        $page = $request->page;

        // Called from the playlist page
        $songId = "";
        if ($page == 'playlist') {
            $playlistData = FanPlaylist::where('slug', $slug)->where('user_id', $authId)->first();
            if ($playlistData) {
                $playlistId = $playlistData->id;
                $allSongs = FanPlaylistSongs::getFanSongsForMusicPlayer($playlistId, '');
            }
        } else if ($page == 'dynamic-group') { // Called from the Favourite Playlists(dynamic group)
            $dynamicGroup = DynamicGroups::where('slug', $slug)->first();
            if ($dynamicGroup) {
                $allSongs = DynamicGroups::getFanSongsForMusicPlayer($dynamicGroup->id, '');
            }
        }

        $allSongsComponent = [
            "componentId" => "allSongs",
            "sequenceId" => "1",
            "isActive" => "1",
            "allSongsData" => $allSongs,
        ];
        $result[] = $allSongsComponent;
        $return = $result;
        return $this->sendResponse($return, 'data retrived successfully.');
    }

    public function myMusicPlayerData(Request $request)
    {
        $authId = User::getLoggedInId();
        $slug = $request->slug;
        $page = $request->page;


        //pre($result,1);

        // Called from the playlist page
        $songId = "";
        if ($page == 'playlist') {
            $playlistData = FanPlaylist::where('slug', $slug)->where('user_id', $authId)->first();
            if ($playlistData) {
                $playlistId = $playlistData->id;

                $playerSongData = FanPlaylistSongs::getFanSongsForMusicPlayer($playlistId, 'first');
                $playerSongComponent = [
                    "componentId" => "playerSong",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "title" => "Playing from Playlist",
                    "playerSongData" => $playerSongData,
                ];
                $result[] = $playerSongComponent;
                $songId = $playerSongComponent['playerSongData']['data']['songId'];
                $generalReview = $playerSongComponent['playerSongData']['generalReviews'];

                $allSongsData = FanPlaylistSongs::getFanSongsForMusicPlayer($playlistId, '');
                $queueComponent = [
                    "componentId" => "queueSongs",
                    "sequenceId" => "3",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "15",
                    "navigateTo" => "my-music-player",
                    "queueSongsData" => $allSongsData,
                ];
                $result[] = $queueComponent;

                $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($playerSongComponent['playerSongData']['data']['songId']);
                $songReviewsData['generalReviews'] = $generalReview;
                $songReviewsComponent = [
                    "componentId" => "songReviews",
                    "sequenceId" => "4",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "20",
                    "navigateTo" => "pass review List endpoint",
                    "songReviewsData" => $songReviewsData,
                ];
                $result[] = $songReviewsComponent;
            }
        } else if ($page == 'dynamic-group') { // Called from the Favourite Playlists(dynamic group)
            $dynamicGroup = DynamicGroups::where('slug', $slug)->first();
            if ($dynamicGroup) {

                $playerSongData = DynamicGroups::getFanSongsForMusicPlayer($dynamicGroup->id, 'first');
                $playerSongComponent = [
                    "componentId" => "playerSong",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "title" => "Playing from Dynamic Group",
                    "playerSongData" => $playerSongData,
                ];
                $result[] = $playerSongComponent;
                $songId = $playerSongComponent['playerSongData']['data']['songId'];
                $generalReview = $playerSongComponent['playerSongData']['generalReviews'];

                $allSongsData = DynamicGroups::getFanSongsForMusicPlayer($dynamicGroup->id, '');
                $queueComponent = [
                    "componentId" => "queueSongs",
                    "sequenceId" => "3",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "15",
                    "navigateTo" => "my-music-player",
                    "queueSongsData" => $allSongsData,
                ];
                $result[] = $queueComponent;

                $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($playerSongComponent['playerSongData']['data']['songId']);
                $songReviewsData['generalReviews'] = $generalReview;
                $songReviewsComponent = [
                    "componentId" => "songReviews",
                    "sequenceId" => "4",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "20",
                    "navigateTo" => "pass review List endpoint",
                    "songReviewsData" => $songReviewsData,
                ];
                $result[] = $songReviewsComponent;
            }
        } else if ($page == 'my-favourite') { // Called from the My Favourite
            $search = $request->search;
            $authId = User::getLoggedInId();
            $fanFavouriteSongsData = FanFavouriteSongs::where('fan_id', $authId)->first();
            if ($fanFavouriteSongsData) {
                $playerSongData = FanFavouriteSongs::getFanSongsForMusicPlayer($authId, 'first', $search);
                $playerSongComponent = [
                    "componentId" => "playerSong",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "title" => "Playing from My Favourite",
                    "playerSongData" => $playerSongData,
                ];
                $result[] = $playerSongComponent;
                $songId = $playerSongComponent['playerSongData']['data']['songId'];
                $generalReview = $playerSongComponent['playerSongData']['generalReviews'];

                $allSongsData = FanFavouriteSongs::getFanSongsForMusicPlayer($authId, '', $search);
                $queueComponent = [
                    "componentId" => "queueSongs",
                    "sequenceId" => "3",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "15",
                    "navigateTo" => "my-music-player",
                    "queueSongsData" => $allSongsData,
                ];
                $result[] = $queueComponent;

                $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($playerSongComponent['playerSongData']['data']['songId']);
                $songReviewsData['generalReviews'] = $generalReview;
                $songReviewsComponent = [
                    "componentId" => "songReviews",
                    "sequenceId" => "4",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "20",
                    "navigateTo" => "pass review List endpoint",
                    "songReviewsData" => $songReviewsData,
                ];
                $result[] = $songReviewsComponent;
            }
        } else if ($page == 'recent-played') { // Called from the My Favourite
            $search = $request->search;
            $authId = User::getLoggedInId();
            $fanFavouriteSongsData = RecentPlayed::where('fan_id', $authId)->first();
            if ($fanFavouriteSongsData) {
                $playerSongData = RecentPlayed::getFanSongsForMusicPlayer($authId, 'first', $search);
                $playerSongComponent = [
                    "componentId" => "playerSong",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "title" => "Playing from Recently Played",
                    "playerSongData" => $playerSongData,
                ];
                $result[] = $playerSongComponent;
                $songId = $playerSongComponent['playerSongData']['data']['songId'];
                $generalReview = $playerSongComponent['playerSongData']['generalReviews'];

                $allSongsData = RecentPlayed::getFanSongsForMusicPlayer($authId, '', $search);
                $queueComponent = [
                    "componentId" => "queueSongs",
                    "sequenceId" => "3",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "15",
                    "navigateTo" => "my-music-player",
                    "queueSongsData" => $allSongsData,
                ];
                $result[] = $queueComponent;

                $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($playerSongComponent['playerSongData']['data']['songId']);
                $songReviewsData['generalReviews'] = $generalReview;
                $songReviewsComponent = [
                    "componentId" => "songReviews",
                    "sequenceId" => "4",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "20",
                    "navigateTo" => "pass review List endpoint",
                    "songReviewsData" => $songReviewsData,
                ];
                $result[] = $songReviewsComponent;
            }
        } else if ($page == 'single-song-in-player') { // Called from the My Favourite
            $songId = $request->songId;
            $songData = Songs::where('id', $songId)->first();
            if ($songData) {
                $playerSongData = Songs::getFanSongsForMusicPlayer($songId, 'first');
                $playerSongComponent = [
                    "componentId" => "playerSong",
                    "sequenceId" => "1",
                    "isActive" => "1",
                    "title" => "Playing a single selected song",
                    "playerSongData" => $playerSongData,
                ];
                $result[] = $playerSongComponent;
                $generalReview = $playerSongComponent['playerSongData']['generalReviews'];
                $artistId = $songData->artist_id;
                $allSongsData = Songs::getFanSuggestedSongsForMusicPlayer($artistId, $songId);
                // $allSongsData = Songs::getFanSongsForMusicPlayer($songId, '');
                $queueComponent = [
                    "componentId" => "queueSongs",
                    "sequenceId" => "3",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "15",
                    "navigateTo" => "my-music-player",
                    "queueSongsData" => $allSongsData,
                ];
                $result[] = $queueComponent;

                $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($songId);
                $songReviewsData['generalReviews'] = $generalReview;
                $songReviewsComponent = [
                    "componentId" => "songReviews",
                    "sequenceId" => "4",
                    "isActive" => "1",
                    "navigate" => "1",
                    "navigateType" => "20",
                    "navigateTo" => "pass review List endpoint",
                    "songReviewsData" => $songReviewsData,
                ];
                $result[] = $songReviewsComponent;

                // $artistId = $songData->artist_id;
                // $suggestedSongsData = Songs::getFanSuggestedSongsForMusicPlayer($artistId);
                // $suggestedSongsComponent = [
                //     "componentId" => "suggestedSongs",
                //     "sequenceId" => "4",
                //     "isActive" => "1",
                //     "suggestedSongsData" => $suggestedSongsData,
                // ];
                // $result[] = $suggestedSongsComponent;
            }
        }
        $playerAudioComp = [
            "componentId" => "playAudio",
            "sequenceId" => "2",
            "isActive" => "1",
            "playAudioData" => [
                "title" => "Play Audio Only",
                "subText" => "All songs will be played in audio only format",
            ],
        ];
        $result[] = $playerAudioComp;

        $playerData = Songs::getVideoQuality($songId);
        $playerComponent = [
            "componentId" => "player",
            "sequenceId" => "5",
            "isActive" => "1",
            "playerData" => $playerData,
        ];
        $result[] = $playerComponent;
        $return = $result;
        // pre($return);
        return $this->sendResponse($return, 'Music player data retrived successfully.');
    }

    public function getMusicPlayerData($songId)
    {
        $playerData = Songs::getVideoQuality($songId);
        $playerComponent = [
            "componentId" => "player",
            "sequenceId" => "1",
            "isActive" => "1",
            "playerData" => $playerData,
        ];
        $result[] = $playerComponent;

        $playerSongData = FanPlaylistSongs::getMusicPlayerSongData($songId);
        $playerSongComponent = [
            "componentId" => "playerSong",
            "sequenceId" => "2",
            "isActive" => "1",
            "playerSongData" => $playerSongData,
        ];
        $result[] = $playerSongComponent;
        $return = $result;
        return $this->sendResponse($return, 'Music player data retrived successfully.');
    }

    public function getMusicPlayerSongData($songId)
    {
        $playerSongData = FanPlaylistSongs::getMusicPlayerSongData($songId);
        $playerSongComponent = [
            "componentId" => "playerSong",
            "sequenceId" => "1",
            "isActive" => "1",
            "playerSongData" => $playerSongData,
        ];
        $result[] = $playerSongComponent;
        $return = $result;
        return $this->sendResponse($return, 'Music player songs data retrived successfully.');
    }

    public function getMusicPlayerReviewData($songId)
    {
        $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($songId);
        $songReviewsComponent = [
            "componentId" => "songReviews",
            "sequenceId" => "4",
            "isActive" => "1",
            "isActive" => "1",
            "navigate" => "1",
            "navigateType" => "20",
            "navigateTo" => "pass review List endpoint",
            "songReviewsData" => $songReviewsData,
        ];
        $result[] = $songReviewsComponent;
        $return = $result;
        return $this->sendResponse($return, 'Reviews retrived successfully.');
    }

    public function getDataForMyMusicPlayer(Request $request)
    {
        $songId = $request->songId;

        $playerSongData = FanPlaylistSongs::getMusicPlayerSongData($songId);
        $playerSongComponent = [
            "componentId" => "playerSong",
            "sequenceId" => "2",
            "isActive" => "1",
            "playerSongData" => $playerSongData,
        ];
        $result[] = $playerSongComponent;
        $generalReview = [];
        if (!empty($playerSongData['generalReviews'])) {
            $generalReview = $playerSongData['generalReviews'];
        }

        $songReviewsData = Reviews::getSongAllReviewsDataForMusicPlayer($songId);
        $songReviewsData['generalReviews'] = $generalReview;
        $songReviewsComponent = [
            "componentId" => "songReviews",
            "sequenceId" => "4",
            "isActive" => "1",
            "songReviewsData" => $songReviewsData,
        ];
        $result[] = $songReviewsComponent;

        $playerData = Songs::getVideoQuality($songId);
        $playerComponent = [
            "componentId" => "player",
            "sequenceId" => "1",
            "isActive" => "1",
            "playerData" => $playerData,
        ];
        $result[] = $playerComponent;

        $return = $result;
        return $this->sendResponse($return, 'Music player data retrived successfully.');
    }

    public function favouriteCollections()
    {
        // favouriteCollections
        $authId = User::getLoggedInId();
        $data = Fan::myCollectionApp($authId);
        $collectionCategory = [
            "componentId" => "collectionCategory",
            "sequenceId" => "1",
            "isActive" => "1",
            "collectionCategoryData" => [
                "list" => [
                    ["Id" => "1", "keyword" => "Playlists"],
                    ["Id" => "2", "keyword" => "Artists"]
                ]
            ]
        ];

        $myCollection = [
            "componentId" => "myCollection",
            "sequenceId" => "1",
            "isActive" => "1",
            "myCollectionData" => [
                "list" => $data
            ]
        ];

        $return = [$collectionCategory, $myCollection];
        return $this->sendResponse($return, 'My Collection resulted successfully.');
    }
}
