<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\ArtistBannerAPIController;
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

class ArtistBannerFrontController extends Controller
{
    public function create(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistBannerAPIController();
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
    
    public function delete(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ArtistBannerAPIController();
            $data = $api->delete($request);
            $data = $data->getData();
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }
}
