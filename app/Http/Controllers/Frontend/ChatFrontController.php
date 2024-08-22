<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ChatAPIController;
use App\Models\States;
use Exception;
use Validator;
use Session;
use Auth;
use Response;
use Mail;

class ChatFrontController extends Controller
{
    public function index($slug="")
    {
        $artistId = 0;
        if ($slug) {
            $artist = Artist::where('slug', $slug)->first();
            if ($artist) {
                $artistId = $artist->id;
            }
        }
        return view('frontend.chat.chat',compact('artistId'));
    }


    public function intiateChat(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->intiateChat($request);
            $data = $data->getData();
            // return redirect()->back()->with('message','Successfully');
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }


    public function listPersons(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->listPersons($request);
            $data = $data->getData();
            $content = $data->component;
            $selected = $request->personId;
            if ($data->statusCode==200) {
                return view('frontend.chat.persons', compact('content','selected'));
            }else{
                $msg1 = "Please initiate your first message from artist profile page.";
                $msg2 = "There are no messages from your fan(s).";
                $msg = (Auth::user()->role_id=="2")?$msg2:$msg1;
                if ($request->search) {
                    $role = (Auth::user()->role_id=="2")?"fans":"artists";
                    $msg = "No ".$role." found with your search criteria.";
                }
                return view('frontend.chat.persons-not-found', compact('msg'));
            }
        }else{
            abort(404, 'Page not found');
        }
    }

    public function getPersonChat(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->getPersonChat($request);
            $data = $data->getData();
            $content = $data->component;
            if ($data->statusCode==200) {
                return view('frontend.chat.person-chats', compact('content'));
            }
            // return view('frontend.chat.person-chats', compact('content'));
        }else{
            abort(404, 'Page not found');
        }
    }

    public function refreshPersonChat(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->getPersonChat($request);
            $data = $data->getData();
            $content = $data->component;
            if ($data->statusCode==200) {
                return view('frontend.chat.person-chats', compact('content'));
            }
            // return view('frontend.chat.person-chats', compact('content'));
        }else{
            abort(404, 'Page not found');
        }
    }

    public function clearChat(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->clearChat($request);
            $data = $data->getData();
            // return redirect()->back()->with('message','Successfully');
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }


    public function readChat(Request $request)
    {
        $authId = User::getLoggedInId();
        if ($authId) {
            $api = new ChatAPIController();
            $data = $api->readChat($request);
            $data = $data->getData();
            // return redirect()->back()->with('message','Successfully');
            return Response::json($data);
        }else{
            abort(404, 'Page not found');
        }
    }
}
