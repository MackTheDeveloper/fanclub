<?php 

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComingInterest;
use Exception;
use Validator;
use Session;
use Auth;
use Mail;

class LandingController extends Controller
{
	public function index(){

		return view('frontend.landing');
	}

	public function submitForm(Request $request){
		$input = $request->all();
		// pre($input);
        $validator = Validator::make(
            $input,
            [
                'role' => 'required',
                'name' => 'required',
                'email' => 'required',
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'role' => 'Please select your interest',
                'name' => 'Please enter name',
                'email' => 'Please enter email',
            ]
        );
        if ($validator->fails()) {
        	Session::flash('error', 'Please fill all the information');
			return redirect()->route('landing')->withErrors($validator->errors())->withInput();
        } else {
            ComingInterest::insertInterest($input);
        	return redirect()->route('landing')->with('success','Your Interest submitted successfully');
		}
	}
}