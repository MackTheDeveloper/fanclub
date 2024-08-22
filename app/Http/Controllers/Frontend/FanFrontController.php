<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\UserProfilePhoto;
use App\Models\FanPlaylist;
use App\Models\FanPlaylistSongs;
use App\Models\FanFavouriteSongs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Country;
use App\Http\Controllers\API\V1\AuthAPIController;
use App\Http\Controllers\API\V1\LocationAPIController;
use App\Http\Controllers\API\V1\SearchAPIController;
use Exception;
use Auth;
use Mail;
use Socialite;
use Response;
use Agent;
use ZipArchive;
use Illuminate\Support\Facades\Session;
use App\Traits\ReuseFunctionTrait;
use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\API\V1\PagesAPIController;
use App\Models\GlobalSettings;
use App\Models\CmsPages;
use App\Models\Fan;
use App\Models\RecentPlayed;
use App\Models\Songs;
use App\Models\States;
use App\Models\TmpSongs;
use Illuminate\Support\Facades\Storage;

class FanFrontController extends Controller
{
    use ReuseFunctionTrait;

    public function editProfile()
    {
        $api = new FanAPIController();
        $data = $api->detail();
        $data = $data->getData();
        $content = $data->component;
        // pre($content);
        $profileData = $content->profileData;
        $countries = Country::getListForDropdown();
        $states = States::getListForDropdown();
        if ($data->statusCode == 200) {
            return view('frontend.auth.editFanProfile', compact('profileData', 'countries', 'states'));
        } else {
            if ($data->statusCode == 300) {
                return redirect('/home')->withErrors($content)->withInput();
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            // 'lastname' => 'required',
            'phone' => 'required',
            'country' => 'required'
        ]);
        if ($validator->fails()) {
            // pre($validator->errors());
            return redirect()->back()->withErrors($validator->errors())->withInput();;
        }
        $api = new FanAPIController();
        if (isset($request->phoneCode_phoneCode)) {
            $request->merge(['prefix' => $request->phoneCode_phoneCode]);
        }
        $data = $api->update($request);
        $data = $data->getData();
        // pre($data);
        if ($data->statusCode == 200) {
            Session::flash('message', getResponseMessage('ProfileUpdate'));
            return redirect()->route('editProfileFan');
        } else {
            if ($data->statusCode == 300) {
                $content = $data->component;
                return redirect('home')->withErrors($content)->withInput();
            }
        }
    }
    public function myPlaylist(Request $request)
    {
        $authId = User::getLoggedInId();
        $playlistData = FanPlaylist::where('slug', $request->slug)->where('user_id', $authId)->first();
        if ($playlistData) {
            $playlistName = $playlistData->playlist_name;
            $fanPlaylistId = $playlistData->id;
            $api = new FanAPIController();
            $data = $api->playlistSongsNew($playlistData->id);
            $data = $data->getData();
            $content = $data->component;
            $total = count($content->playlistSongsData);
            if ($data->statusCode == 200) {
                return view('frontend.auth.myCollections', compact('content', 'total', 'playlistName', 'fanPlaylistId'));
            } else {
                if ($data->statusCode == 300) {
                    return redirect('home')->withErrors($content)->withInput();
                }
            }
        } else {
            return redirect('/');
        }
    }
    
    public function myFavourite(Request $request, $search = "")
    {
        $authId = User::getLoggedInId();
        $playlistData = FanFavouriteSongs::where('fan_id', $authId)->first();
        if ($playlistData) {
            //patch by nivedita for search page see all//
            $api = new FanAPIController();
            $data = $api->favouriteSongsNew($search);
            $data = $data->getData();
            $content = $data->component;
            $total = count($content->favSongsData);
            if ($data->statusCode == 200) {
                $title = "My Collection";
                $page = "my-favourite";
                return view('frontend.auth.myPlaylist', compact('content', 'total', 'search','title', 'page'));
            } else {
                if ($data->statusCode == 300) {
                    return redirect('home')->withErrors($content)->withInput();
                }
            }
        } else {
            return redirect('/');
        }
    }
    
    public function recentPlayed(Request $request, $search = "")
    {
        $authId = User::getLoggedInId();
        $playlistData = RecentPlayed::where('fan_id', $authId)->first();
        if ($playlistData) {
            //patch by nivedita for search page see all//
            $api = new FanAPIController();
            $data = $api->recentSongsNew($search);
            $data = $data->getData();
            $content = $data->component;
            $total = count($content->favSongsData);
            if ($data->statusCode == 200) {
                $title = "Recently Played";
                $page = "recent-played";
                return view('frontend.auth.myPlaylist', compact('content', 'total', 'search','title', 'page'));
            } else {
                if ($data->statusCode == 300) {
                    return redirect('home')->withErrors($content)->withInput();
                }
            }
        } else {
            return redirect('/');
        }
    }

    public function favouritePlaylist(Request $request, $search = '')
    {
        $api = new FanAPIController();
        //patch by nivedita for search page see all//
        $search = isset($request->search) ? $request->search : $search;
        $request->merge(['search' => $search]);
        $data = $api->favouritePlaylist($request);
        $data = $data->getData();
        $content = $data->component[0];
        // pre($content);
        return view('frontend.auth.favourite-playlist', compact('content'));
    }
    // added by Nivedita for search page my playlist view all//
    public function myPlaylistIndex(Request $request, $search = '')
    {
        $api = new FanAPIController();
        //patch by nivedita for search page see all//
        if (!empty($search))
            $search = $search;
        else
            $search = '';
        //patch by nivedita for search page see all//
        $data = $api->myPlaylist($search);
        $data = $data->getData();
        $content = $data->component;
        return view('frontend.auth.my-favourite-playlist', compact('content'));
    }

    /**
     * Show add to playlist popup.
     *
     * @param  int $songId
     * @return \Illuminate\View\View
     */
    public function showAddToPlaylist($songId)
    {
        $api = new FanAPIController();
        $data = $api->playlistindex();
        $data = $data->getData();
        return view('frontend.components.playlist.add.add-to-my-playlist-body', ['songId' => $songId, 'playListData' => $data->component->playlistData]);
    }

    /**
     * Store song into the playlist.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addToPlaylist(Request $request)
    {
        $api = new FanAPIController();
        $data = $api->playlistSongAdd($request);
        $data = $data->getData();
        return Response::json($data);
    }

    /**
     * Create playlist and add song into the playlist.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createPlaylistAndAddSong(Request $request)
    {
        $api = new FanAPIController();
        $data = $api->playlistCreateSongAdd($request);
        $data = $data->getData();
        return Response::json($data);
    }

    /**
     * Show popup for remove song from playlist
     *
     * @param  string $slug 
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function showRemoveFromPlaylist($slug, $id)
    {
        return view('frontend.components.playlist.remove.remove-from-playlist-body', ['slug' => $slug, 'id' => $id]);
    }

    /**
     * Remove song from the playlist
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function removeFromPlaylist(Request $request)
    {
        $api = new FanAPIController();
        if ($request->slug == 'removeSongFromPlaylist')
            $data = $api->playlistSongRemove($request->id);
        else if ($request->slug == 'removePlaylist')
            $data = $api->playlistRemove($request->id);
        $data = $data->getData();
        return Response::json($data);
    }

    /**
     * Show edit popup for edit fan playlist
     *
     * @param  int $fanPlaylistId
     * @return \Illuminate\View\View
     */
    public function showEditFanPlaylist($fanPlaylistId)
    {
        $fanPlaylistData = FanPlaylist::find($fanPlaylistId);
        return view('frontend.components.playlist.edit.edit-fan-playlist-body', ['fanPlaylistData' => $fanPlaylistData]);
    }

    /**
     * Update fan playlist data: playlist name
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateFanPlaylist(Request $request)
    {
        $api = new FanAPIController();
        $data = $api->updateFanPlaylist($request);
        $data = $data->getData();
        $notification = array(
            'message' => $data->message,
            'alert-type' => 'success'
        );
        return redirect()->route('my-playlist', $data->component->data->slug)->with($notification);
        //return Response::json($data);
    }

    /**
     * Show the subscription of the fan.
     *
     * @param  
     * @return \Illuminate\View\View
     */
    public function mySubscription()
    {
        $api = new FanAPIController();
        $data = $api->getMySubscriptonData();
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        // pre($content);
        return view('frontend.auth.my-subscription', compact('content'));
    }

    /**
     * Show the music player
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    /* public function myMusicPlayer(Request $request)
    {
        if (\Request::isMethod('post')) {
            if ($request->page == 'playlist') {
                $api = new FanAPIController();
                $data = $api->getFanPlaylistSongsForMusicPlayer($request);
                $data = $data->getData();
                $content = $data->component;
                //pre($content);
                return view('frontend.songs.my-music-player', compact('content'));
            }
        } else {
            return view('frontend.songs.my-music-player');
        }
    } */

    public function myMusicPlayer(Request $request)
    {
        if (\Request::isMethod('post')) {

            $api = new FanAPIController();
            $data = $api->myMusicPlayerData($request);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            //pre($content);
            $page = $request->page;
            $supportMime = getSupportedMime(getBrowser());
            return view('frontend.songs.my-music-player', compact('content','page', 'supportMime'));

            /*  if ($request->page == 'playlist') {
                $api = new FanAPIController();
                $data = $api->getFanPlaylistSongsForMusicPlayer($request);
                $data = $data->getData();
                pre($data);
                $content = $data->component;
                pre($content);
                return view('frontend.songs.my-music-player', compact('content'));
            } */
        } else {
            return view('frontend.songs.my-music-player');
        }
    }

    /**
     * Download all the songs
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadAll(Request $request)
    {
        if (\Request::isMethod('post')) {

            $api = new FanAPIController();
            $data = $api->downloadAll($request);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            //pre($content['allSongs']->allSongsData);

            $zipname = public_path('/assets/d-zip/tmp-z-' . rand() . '-' . time() . '.zip');
            $zip = new ZipArchive;
            $zip->open($zipname, ZipArchive::CREATE);
            $addedSongs = 0;
            // pre($content['allSongs']->allSongsData);
            foreach ($content['allSongs']->allSongsData->list as $filename) {
                $fileUrl = $filename->s3VideoUrl;
                if (empty($fileUrl)) {
                    $fileUrl = Songs::getOriginalSongUrl($filename->songId);
                }
                if (!empty($fileUrl)) {
                    $exists = Storage::disk('s3')->has(TmpSongs::getFileNameByUrl($fileUrl));
                    if ($exists) {
                        $extension = pathinfo($fileUrl, PATHINFO_EXTENSION);
                        $file = file_get_contents($fileUrl);
                        $zip->addFromString(basename($filename->songName) . '.' . $extension, $file);
                        $addedSongs++;
                    }
                }
            }
            $zip->close();
            if($addedSongs){
                $path = $zipname;
                return response()->download($path);
            }else{
                return redirect()->back();
            }
        }
    }

    /**
     * Get music player data
     *
     * @param  int $songId
     * @return \Illuminate\View\View
     */
    public function getMusicPlayerData(Request $request)
    {
        $songId = $request->songId;
        $api = new FanAPIController();

        $dataPlayer = $api->getMusicPlayerData($songId);
        $dataPlayer = $dataPlayer->getData();
        $contentPlayer = $dataPlayer->component;
        $contentPlayer = componentWithNameObject($contentPlayer);

        return Response::json($contentPlayer);

        /* return view('frontend.components.music-player.player', ['player' =>
        $contentPlayer['player'],'playerSong' => $contentPlayer['playerSong']]); */
    }

    /**
     * Get song data for music player
     *
     * @param  int $songId
     * @return \Illuminate\View\View
     */
    public function getMusicPlayerSongData(Request $request)
    {
        $songId = $request->songId;
        $api = new FanAPIController();
        $data = $api->getMusicPlayerSongData($songId);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        return view('frontend.components.music-player.player-song', ['playerSong' => $content['playerSong']]);
    }

    /**
     * Get review data for music player
     *
     * @param  int $songId
     * @return \Illuminate\View\View
     */
    public function getMusicPlayerReviewData(Request $request)
    {
        $songId = $request->songId;
        $api = new FanAPIController();

        $dataReviews = $api->getMusicPlayerReviewData($songId);
        $dataReviews = $dataReviews->getData();
        $contentReviews = $dataReviews->component;
        $contentReviews = componentWithNameObject($contentReviews);

        $dataSongs = $api->getMusicPlayerSongData($songId);
        $dataSongs = $dataSongs->getData();
        $contentSongs = $dataSongs->component;
        $contentSongs = componentWithNameObject($contentSongs);

        return view('frontend.components.music-player.reviews', ['songReviews' => $contentReviews['songReviews'], 'playerSong' => $contentSongs['playerSong']]);
    }
}
