<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ArtistNewsAPIController;
use App\Models\States;
use Exception;
use Validator;
use Session;
use Auth;
use Response;
use Mail;

class ArtistNewsFrontController extends Controller
{
    public function index($slug)
    {
        $artist = Artist::where('slug', $slug)->first();
        if ($artist) {
            $api = new ArtistNewsAPIController();
            $data = $api->index($artist->id);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            // pre($content);
            return view('frontend.artist.artist-news', compact('content'));
        }else{
            abort(404, 'Page not found');
        }
    }


    public function create(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistNewsAPIController();
            $data = $api->create($request);
            $data = $data->getData();
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
            // return redirect()->back()->with('message','Successfully');
        }else{
            abort(404, 'Page not found');
        }
    }

    public function edit($id)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistNewsAPIController();
            $data = $api->edit($id);
            $data = $data->getData();
            $content = $data->component;
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }
    
    public function update($id,Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistNewsAPIController();
            $request->merge(['news_id' => $id]);
            $data = $api->update($request);
            $data = $data->getData();
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
            // return redirect()->back()->with('message','Successfully');
        }else{
            abort(404, 'Page not found');
        }
    }

    public function delete(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistNewsAPIController();
            $data = $api->delete($request);
            $data = $data->getData();
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }
}
