<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use App\Models\User;
use App\Models\CmsPages;
use App\Http\Controllers\API\V1\ContactUsAPIController;
use Exception;
use Auth;
use Mail;
// use Illuminate\Support\Facades\Session;

class ContactUsController extends Controller
{
    /* ###########################################
    // Function: showContactUs
    // Description: Show contact us page   
    // ReturnType: view
    */ ###########################################
    public function showContactUs(){
        $cms = CmsPages::where('slug','contact-us')->first();
        $userData = new User();
        if(Auth::check())
        {
            $userData = User::find(Auth::id());
        }
        return view('frontend.pages.contact-us',compact('cms','userData'));
    }

    /* ###########################################
    // Function: store
    // Description: Submit contact us page   
    // ReturnType: view
    */ ###########################################
    public function store(Request $request){

        $api = new ContactUsAPIController();
        unset($request['_token']);
        unset($request['g-recaptcha-response']);
        // pre($request->all());
        $data = $api->create($request);
        $data = $data->getData();
        $content = $data->component;
        if ($data->statusCode==200) {
                //return redirect()->back()->with('success',$data->message);
                $notification = array(
                    'message' => $data->message,
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
        }else{
            if ($data->statusCode==300){
                return redirect()->back()->withErrors($content)->withInput();
            }else{
                return redirect()->back()->withInput()->with('error',$data->component);
            }
        }
    }
}
