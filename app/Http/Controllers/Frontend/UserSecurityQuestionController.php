<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Artist;
use App\Models\SecurityQuestions;
use App\Models\UserSecurityQuestions;
use App\Http\Controllers\API\V1\SecurityQuestionsAPIController;
use App\Models\GlobalSettings;
use Exception;
use Auth;
use Response;
use Session;
use Mail;
// use Illuminate\Support\Facades\Session;

class UserSecurityQuestionController extends Controller
{
    /* ###########################################
    // Function: showSecurityQuestion
    // Description: Show security question page
    // ReturnType: view
    */ ###########################################
    public function showSecurityQuestion()
    {
        // Get user id
        $authId = User::getLoggedInId();
        if ($authId) {
            // Get security question
            $api = new SecurityQuestionsAPIController();
            $data = $api->getSecurityQuestions();
            $data = $data->getData();
            $content = $data->component;
            if ($data->statusCode == 200) {
                return view('frontend.pages.security-questions', compact('content'));
            } else {
                return redirect()->back()->withErrors($content)->withInput();
            }
            // $securityQuestion = SecurityQuestions::getSecurityQuestionsList($authId);
            // return view('frontend.pages.security-questions', compact('securityQuestion'));
        } else {
            abort(404, 'Page not found');
        }
    }

    /* ###########################################
    // Function: store
    // Description: Submit security question page
    // ReturnType: view
    */ ###########################################
    public function store(Request $request)
    {

        $api = new SecurityQuestionsAPIController();
        unset($request['_token']);
        // pre($request->all());
        $data = $api->create($request);
        $data = $data->getData();
        //$content = $data->component;
        return Response::json($data);
        /* if ($data->statusCode == 200) {
            return redirect()->back()->with('message', $data->message);
        } else if ($data->statusCode == 301) {
            return Response::json($data);
        } else {
            if ($data->statusCode == 300) {
                return redirect()->back()->withErrors($content)->withInput();
            } else {
                return redirect()->back()->withInput()->with('error', $data->component);
            }
        } */
    }

    /* ###########################################
    // Function: showSecurityQuestionCheck
    // Description: Show security question check page
    // ReturnType: view
    */ ###########################################
    public function showSecurityQuestionCheck(Request $request)
    {
        $api = new SecurityQuestionsAPIController();
        // pre(Session::get('data'));
        $data = $api->getSecurityQuestionsList();
        $data = $data->getData();
        $content = $data->component;
        $securityQuestion = SecurityQuestions::where('status', '1')->get();
        // pre($content);die;
        // $securityQuestion = SecurityQuestions::where('status', '1')->get();
        return view('frontend.pages.check-security-questions', compact('content'));
    }

    /* ###########################################
    // Function: check
    // Description: Submit check security question page
    // ReturnType: view
    */ ###########################################
    public function check(Request $request)
    {

        $api = new SecurityQuestionsAPIController();
        unset($request['_token']);
        // pre($request->all());
        $data = $api->check($request);
        $data = $data->getData();
        $content = $data->component;
        if ($data->statusCode == 200) {
            return redirect()->route('showSecurityQuestionCheck')->with(['data' => $data]);
            // return view('frontend.pages.check-security-questions', compact('securityQuestion', 'data'));
        } else {
            if ($data->statusCode == 300) {
                return redirect()->back()->withErrors($content)->withInput();
            } else {
                $notification = array(
                    'message' => $data->message,
                    'alert-type' => 'error'
                );
                return redirect()->route('showSecurityQuestionCheck')->with($notification);
            }
        }
    }
}
