<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\ReviewAPIController;
use App\Http\Controllers\API\V1\SongAPIController;
use App\Models\Songs;
use App\Models\Country;
use App\Models\States;
use Exception;
use Validator;
use Session;
use Auth;
use Response;
use Mail;

class ArtistFrontController extends Controller
{
    public function index($slug)
    {
        $artist = Artist::where('slug', $slug)->first();
        if ($artist) {
            $id = $artist->id;
            $api = new ArtistAPIController();
            $data = $api->index($artist->id);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            return view('frontend.artist.artist-detail', compact('content','id'));
        }else{
            abort(404, 'Page not found');
        }
    }
    public function filterSongs(Request $request)
    {
        $api = new SongAPIController();
        $data = $api->filteredList($request);
        $data = $data->getData();
        $content = $data->component;
        return view('frontend.artist.filtered-songs', compact('content'));
    }

    public function artistProfile()
    {
        // $api = new ArtistAPIController();
        // $data = $api->detail();
        // $data = $data->getData();
        // $content = $data->component;
        // $profileData = $content->profileData;
        // pre($profileData);
        $authId = User::getLoggedInId();
        $artistData = Artist::getArtistDetailAPI($authId);
        $api = new ArtistAPIController();
        $data = $api->index($authId);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        // pre($content);
        // pre($content['news']->newsData->list);
        if ($data->statusCode == 200) {
            return view('frontend.artist.artist-profile', compact('content'));
        } else {
            if ($data->statusCode == 300) {
                return redirect('/home')->withErrors($content)->withInput();
            }
        }
    }

    public function artistDetailUpdate(Request $request)
    {
        $api = new ArtistAPIController();
        $data = $api->updateDetails($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function editProfile()
    {
        $api = new ArtistAPIController();
        $data = $api->detail();
        $data = $data->getData();
        $content = $data->component;
        $profileData = $content->profileData;
        $profilePhoto = UserProfilePhoto::getProfilePhoto(User::getLoggedInId());
        // $profilePhoto = UserProfilePhoto::getProfilePhoto(User::getLoggedInId(),'round_192_192.png');
        $countries = Country::getListForDropdown();
        $states = States::getListForDropdown();
        if ($data->statusCode == 200) {
            // pre($profileData);
            return view('frontend.auth.editArtistProfile', compact('profileData', 'profilePhoto', 'countries','states'));
        } else {
            if ($data->statusCode == 300) {
                return redirect('/home')->withErrors($content)->withInput();
            }
        }
    }

    public function updateProfile(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'firstname' => 'required',
            // 'lastname' => 'required',
            'phone' => 'required',
            'country' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();;
        }
        $api = new ArtistAPIController();
        if (isset($request->phoneCode_phoneCode)) {
            $request->merge(['prefix' => $request->phoneCode_phoneCode]);
        }
        $data = $api->update($request);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            \Illuminate\Support\Facades\Session::flash('message', getResponseMessage('ProfileUpdate'));
            return redirect()->route('editProfileArtist');
        } else {
            if ($data->statusCode == 300) {
                $content = $data->component;
                return redirect('home')->withErrors($content)->withInput();
            }
        }
    }

    // public function eventList($slug)
    // {
    //     $artist = Artist::where('slug', $slug)->first();
    //     if ($artist) {
    //         $api = new ArtistAPIController();
    //         $data = $api->getEventListByArtist($artist->id);
    //         $data = $data->getData();
    //         $content = $data->component;
    //         $profilePhoto = UserProfilePhoto::getProfilePhoto($artist->id);
    //         $detail = Artist::getSingleDetail($artist->id);
    //         // pre($content->artistEventData);
    //         return view('frontend.artist.upcoming-events', compact('content', 'detail', 'profilePhoto'));
    //     }
    // }

    public function artistLikeDislike(Request $request)
    {
        $api = new ArtistAPIController();
        $data = $api->ArtistsIncreaseLike($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function songLikeDislike(Request $request)
    {
        $authId = User::getLoggedInId();
        // $filter['artist_id'] = $authId;
        // $request->merge(['filter' => $filter]);
        $api = new ArtistAPIController();
        $data = $api->SongsIncreaseLike($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function groupLikeDislike(Request $request)
    {
        $authId = User::getLoggedInId();
        // $filter['artist_id'] = $authId;
        // $request->merge(['filter' => $filter]);
        $api = new ArtistAPIController();
        $data = $api->GroupIncreaseLike($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function artistSongListForReview(Request $request)
    {
        $authId = User::getLoggedInId();
        $filter['artist_id'] = $authId;
        $request->merge(['filter' => $filter]);
        $api = new SongAPIController();
        $data = $api->filteredReviewList($request);
        $data = $data->getData();
        $content = $data->component;
        // pre($content);
        return view('frontend.artist.artist-song-list-review', compact('content'));
    }

    public function artistSongFilter(Request $request)
    {
        $api = new SongAPIController();
        $authId = User::getLoggedInId();
        $filter['artist_id'] = $authId;
        $request->merge(['filter' => $filter]);
        $data = $api->filteredList($request);
        $data = $data->getData();
        $content = $data->component;
        return view('frontend.artist.filtered-songs', compact('content'));
    }

    public function artistReviewSongFilter(Request $request)
    {
        $api = new SongAPIController();
        $authId = User::getLoggedInId();
        $filter['artist_id'] = $authId;
        $request->merge(['filter' => $filter]);
        $data = $api->filteredReviewList($request);
        $data = $data->getData();
        $content = $data->component;
        return view('frontend.artist.filtered-songs', compact('content'));
    }

    public function rejectReview(Request $request)
    {
        $api = new ReviewAPIController();
        $data = $api->rejectReview($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function artistSongReview($id, Request $request)
    {
        if (Songs::checkValidArtist($id)) {
            $api = new ReviewAPIController();
            $data = $api->indexReviewSongs($id, $request);
            $data = $data->getData();
            $content = $data->component;
            return view('frontend.artist.artist-song-review', compact('content'));
        } else {
            return redirect()->route('home');
        }
    }

    public function dashboard(){
        $api = new ArtistAPIController();
        $data = $api->dashboard();
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        // pre($content);
        return view('frontend.artist.dashboard',compact('content'));
    }

    public function songList(Request $request)
    {
        $authId = User::getLoggedInId();
        $api = new SongAPIController();
        $data = $api->artistSongs($request);
        $data = $data->getData();
        $content = $data->component;
        // pre($content);
        $content = $content[1];
        return view('frontend.artist.artist-song-list',compact('content','authId'));
    }

    public function allArtists(Request $request,$search="")
    {
        $api = new ArtistAPIController();
        //patch by nivedita for search page see all//
        $search = isset($request->search)? $request->search:$search;
        $request->merge(['search' => $search]);
        $request->merge(['limit' => 12]);
        $data = $api->allArtists($request);
        $data = $data->getData();
        $content = $data->component;
        $title = 'All Artist';
        $seo_title = 'All Artist';
        $seo_meta_keyword = 'All Artist';
        $seo_description = 'All Artist';
        // return view('frontend.artist.artist-all',compact('content', 'title', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        if ($request->ajax()) {
            return view('frontend.artist.ajax-all-artist', compact('content'));
        } else {
            return view('frontend.artist.artist-all', compact('content', 'title', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        }
    }

    public function myArtists(Request $request,$search="")
    {
        $api = new ArtistAPIController();
        //patch by nivedita for search page see all//
        $search = isset($request->search) ? $request->search : $search;
        $request->merge(['search' => $search]);
        // pre($request->all());
        $data = $api->myArtists($request);
        $data = $data->getData();
        $content = $data->component;
        $title = $seo_title = 'My Artists';
        $seo_meta_keyword = $seo_description = 'My Artists';
        // return view('frontend.artist.artist-all',compact('content','title', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        if ($request->ajax()) {
            return view('frontend.artist.ajax-all-artist', compact('content'));
        } else {
            return view('frontend.artist.artist-all', compact('content', 'title', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        }
    }
    public function loadmore(Request $request)
    {
      $id=$request->SongId;
      if (Songs::checkValidArtist($id)) {
          $api = new ReviewAPIController();
          $data = $api->indexReviewSongs($id, $request);
          $data = $data->getData();
          $content = $data->component;
          return view('frontend.artist.review-load-more', compact('content'));
      } else {
          return redirect()->route('home');
      }
    }

}
