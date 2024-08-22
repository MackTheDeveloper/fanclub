<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\ReviewUploads;
use App\Models\DynamicGroups;
use App\Models\FanFavouriteSongs;
use App\Models\FanFavouriteArtists;
use App\Models\FanFavouriteGroups;
use App\Models\FanPlaylist;
use App\Models\FanSearches;
use App\Models\Artist;
use App\Models\Songs;
use App\Models\MusicGenres;
use App\Models\User;
use Validator;
use Mail;
use Hash;

class SearchAPIController extends BaseController
{

    public function search(Request $request)
    {
        $search = $request->search;
        $fanclubPlaylistData = DynamicGroups::searchAPIDynamicGroups($search);
        $myCollectionData = FanFavouriteSongs::searchAPIFanFavouriteSong($search);
        $myPlaylistData = FanPlaylist::searchAPIFanPlaylist($search);
        $myArtistData = FanFavouriteArtists::searchAPIFanFavoriteArtist($search);
        // $artistData = DynamicGroups::searchAPIDynamicGroups($search);
        $artistData = Artist::searchAPIArtist($search);
        $songsData = Songs::searchAPISongs($search);
        //$genreData = MusicGenres::searchAPIGenres($search);
        $genreData = array();
        
        $authId = User::getLoggedInId();
        if($authId){
            if(!empty($fanclubPlaylistData) || !empty($myCollectionData) || !empty($myPlaylistData) || !empty($myArtistData) || !empty($artistData) || !empty($genreData) || !empty($songsData) ){
                $data = FanSearches::apiAddFanSearch($search);
            }
        }
        $noMsgActive = "0";
        if(empty($fanclubPlaylistData) && empty($myCollectionData) && empty($myPlaylistData) && empty($myArtistData) && empty($artistData) && empty($genreData) && empty($songsData) ){
            $noMsgActive = "1";
        }

        $recentSearchdata = FanSearches::apiGetRecentSearches();

        $navigate = $authId?"1":"0";
        $navigateGuest = "1";

        $recentSearch = [
            "componentId" => "recentSearch",
            "title" => "Recent Searches",
            "navigate" => $navigateGuest,
            "navigateType" => "0",
            "sequenceId" => "1",
            "isActive" => (!empty($recentSearchdata['recentSearches']))?"1":"0",
            "recentSearchData" => [
                "list" => $recentSearchdata['recentSearches']
            ]
        ];

        $fanclubPlaylist = [
            "componentId" => "fanclubPlaylist",
            "title" => "fanclub Playlists",
            "navigate" => $navigateGuest,
            "navigateType" => "4",
            "navigateTo" => "fan-group/".$search,
            "sequenceId" => "2",
            "isActive" => (!empty($fanclubPlaylistData['groupDetail']))?"1":"0",
            "fanclubPlaylistData" => [
                "list" => $fanclubPlaylistData['groupDetail']
            ]
        ];

        $myCollection = [
            "componentId" => "myCollection",
            "title" => "My Collection",
            "navigate" => $navigateGuest,
            "navigateType" => "6",
            "navigateTo" => "fan-favourite-songs/".$search,
            "sequenceId" => "3",
            "isActive" => (!empty($myCollectionData['songDetail']))?"1":"0",
            "myCollectionData" => [
                "list" => $myCollectionData['songDetail']
            ]
        ];

        $myPlaylist = [
            "componentId" => "myPlaylist",
            "title" => "My Playlists",
            "navigate" => $navigateGuest,
            "navigateType" => "8",
            "navigateTo" => "fan-playlist/".$search,
            "sequenceId" => "4",
            "isActive" => (!empty($myPlaylistData['playlistDetail']))?"1":"0",
            "myPlaylistData" => [
                "list" => $myPlaylistData['playlistDetail']
            ]
        ];

        $myArtist = [
            "componentId" => "myArtist",
            "title" => "My Artists",
            "navigate" => $navigateGuest,
            "navigateType" => "10",
            "navigateTo" => "fan-favourite-artists/".$search,
            "sequenceId" => "5",
            "isActive" => (!empty($myArtistData['artistDetail']))?"1":"0",
            "myArtistData" => [
                "list" => $myArtistData['artistDetail']
            ]
        ];

        

        $artist = [
            "componentId" => "artist",
            "title" => "Artists",
            "navigate" => $navigateGuest,
            "navigateType" => "12",
            "navigateTo" => "all-artists/".$search,
            "sequenceId" => "6",
            "isActive" => (!empty($artistData['artistDetail']))?"1":"0",
            "artistData" => [
                "list" => $artistData['artistDetail']
            ]
        ];

        $songs = [
            "componentId" => "songs",
            "title" => "Songs",
            "navigate" => $navigateGuest,
            "navigateType" => "14",
            "navigateTo" => "all-songs/".$search,
            "sequenceId" => "7",
            "isActive" => (!empty($songsData['songsDetails']))?"1":"0",
            "songsData" => [
                "list" => $songsData['songsDetails']
            ]
        ];

        
        /* $genre = [
            "componentId" => "genre",
            "title" => "Genre",
            "navigate" => "0",
            "navigateType" => "16",
            "sequenceId" => "8",
            "isActive" => (!empty($genreData) && !empty($genreData['genresDetails']))?"1":"0",
            "genreData" => [
                "list" => $genreData['genresDetails']
            ]
        ]; */

        $noResultMsg = [
            "componentId" => "noResultMsg",
            "navigate" => "0",
            "navigateType" => "0",
            "sequenceId" => "1",
            "isActive" => $noMsgActive,
            "noResultMsgData" => getResponseMessage('SearchNoResults')
        ];

        $component = [$fanclubPlaylist,$myCollection,$myPlaylist,$myArtist,$recentSearch,$artist,$songs,$noResultMsg];
        if (!$authId) {
            $component = [$fanclubPlaylist, $recentSearch, $artist, $songs, $noResultMsg];
        }
        // $component =
        // [
        //     "componentId" => "searchList",
        //     "sequenceId" => "1",
        //     "isActive" => "1",
        //     "searchListData" => [
        //         "fanclubPlaylistData" => $fanclubPlaylistData['groupDetail'],
        //         "myCollectionData" => $myCollectionData['songDetail'],
        //         "myPlaylistData" => $myPlaylistData['playlistDetail'],
        //         "myArtistData" => $myArtistData['artistDetail'],
        //         "recentSearchdata" => $recentSearchdata['recentSearches'],
        //         "artistData" => $artistData['artistDetail'],
        //         "songsData" => $songsData['songsDetails'],
        //         "genreData" => $genreData['genresDetails'],
        //         "noResultMsg" => getResponseMessage('SearchNoResults'),
        //     ],
        // ];
        return $this->sendResponse($component, 'Search resulted successfully.');
    }

    public function searchTagRemove(Request $request)
    {
        $input = $request->all();
        $fansearchId = $input['fansearchId'];
        $authId = User::getLoggedInId();
        $searchDetails = FanSearches::where('fan_id',$authId)->where('id',$fansearchId)->first();
        if ($searchDetails) {
            $searchDetails->status = 0;
            $searchDetails->update();
        }
        $recentSearchdata = FanSearches::apiGetRecentSearches();
        $component =
        [
            "componentId" => "recentSearchList",
            "sequenceId" => "1",
            "isActive" => "1",
            "recentSearchListData" => [
                "recentSearchdata" => $recentSearchdata['recentSearches'],
            ],
        ];
        return $this->sendResponse($component, getResponseMessage('searchTagRemoved'));
    }
}
