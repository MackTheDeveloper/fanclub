<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactUs;
use App\Models\GlobalSettings;
use Validator;
use Mail;
use Hash;

class ContactUsAPIController extends BaseController
{
    // public function index(Request $request){
    //     $data = ContactUs::userAndStatusWiseData();
    //     return $this->sendResponse($data, 'Reviews listed successfully.');
    // }
    

    public function index(){
        $data = [
            "componentId" => "contactus",
            "sequenceId" => "1",
            "isActive" => "1",
            "title" => "Have a query? contact us here.",
            "subtitle" => "Want to leave feedback? We'd love to hear from you.",
            "socialMedia" => [
                [
                    "name"=>"facebook",
                    "url" => GlobalSettings::getSingleSettingVal('fb_link')
                ],
                [
                    "name" => "instagram",
                    "url" => GlobalSettings::getSingleSettingVal('insta_link')
                ],
            ],
        ];
        return $this->sendResponse($data, 'Contact us page fetched successfully.');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'first_name' => 'required',
            // 'last_name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $data = ContactUs::addNew($input);
            if ($data['success']) {
                $msg = getResponseMessage('ContactUs');
                return $this->sendResponse($data['data'], $msg);
                // return $this->sendResponse($data['data'], 'Thank you for submitting inquiry. We will get back to you soon.');
            }else{
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }

}