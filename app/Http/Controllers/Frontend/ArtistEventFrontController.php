<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ArtistNewsAPIController;
use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\ArtistEventAPIController;
use App\Models\States;
use Exception;
use Validator;
use Session;
use Auth;
use Response;
use Mail;

class ArtistEventFrontController extends Controller
{
    public function index($slug)
    {
        $artist = Artist::where('slug', $slug)->first();
        if ($artist) {
            $api = new ArtistAPIController();
            $data = $api->getEventListByArtist($artist->id);
            $data = $data->getData();
            $content = $data->component;
            $profilePhoto = UserProfilePhoto::getProfilePhoto($artist->id);
            $detail = Artist::getSingleDetail($artist->id);
            // pre($content->artistEventData);
            return view('frontend.artist.upcoming-events', compact('content', 'detail', 'profilePhoto'));
        }
    }


    public function create(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            return view('frontend.artist.event-form');
        }else{
            abort(404, 'Page not found');
        }
    }

    public function store(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            // pre($request->all());
            $api = new ArtistEventAPIController();
            $data = $api->create($request);
            $data = $data->getData();
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
            return redirect()->route('ArtistProfile')->with($notification);
        }else{
            abort(404, 'Page not found');
        }
    }

    public function edit($id)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistEventAPIController();
            $data = $api->edit($id);
            $data = $data->getData();
            $content = $data->component;
            // return Response::json($data);
            // pre($content);
            return view('frontend.artist.event-form',compact('content'));
        }else{
            abort(404, 'Page not found');
        }
    }
    
    public function update($id,Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistEventAPIController();
            $request->merge(['event_id' => $id]);
            // pre($request->all());
            $data = $api->update($request);
            $data = $data->getData();
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
            return redirect()->route('ArtistProfile')->with($notification);
        }else{
            abort(404, 'Page not found');
        }
    }

    public function delete(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistEventAPIController();
            $data = $api->delete($request);
            $data = $data->getData();
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }
}
