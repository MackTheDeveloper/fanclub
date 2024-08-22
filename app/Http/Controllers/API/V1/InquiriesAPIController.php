<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Inquiries;
use Validator;
use Mail;
use Hash;

class InquiriesAPIController extends BaseController
{

	public function index()
    {
        $user_id = Auth::user()->id;
    	$data = Inquiries::userWiseData($user_id);
        return $this->sendResponse($data, 'Inquiries listed successfully.');
    }
    

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'product_id' => 'required',
            // 'message' => 'required',
            // 'contact_time' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $input['inq_number'] = Inquiries::generateInqNumber();
            $user_id = Auth::user()->id;
            $data = Inquiries::addNew($input,$user_id);
            if ($data['success']) {
                return $this->sendResponse($data['data'], 'Inquiry added successfully.');
            }else{
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }

}