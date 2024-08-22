<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use Validator;
use Carbon\Carbon;
use DB;
use DataTables;
use App\Models\ContactUsReply;
use App\Models\ContactUs;
use App\Models\EmailTemplates;

class ContactUsController extends Controller
{
	public function getContactUs()
	{
		$baseUrl = $this->getBaseUrl();
		return view('admin.contactUs.contactUs', compact('baseUrl'));
	}

	public function getContactUsData(Request $request)
	{
		$contactUsInquiry = ContactUs::select('id','first_name as name','email','message','ip_address', "created_at",'is_replied');
		if ($request['startDate'] != "" || $request['endDate'] != "") {
			$startDate = date('Y-m-d',strtotime($request['startDate']));
			$endDate = date('Y-m-d',strtotime($request['endDate']));
			// dd(date('Y-m-d',strtotime($request['endDate'])));
			$contactUsInquiry->whereBetween('created_at',[$startDate, $endDate]);
		}
		if ($request['name'] != "") {
			$contactUsInquiry->where('first_name','like','%'. $request['name'] .'%');
		}
		if ($request['email'] != "") {
			$contactUsInquiry->where('email','like','%'. $request['email'] .'%');
		}
		$contactUsInquiry = $contactUsInquiry->get();
		return DataTables::of($contactUsInquiry)->make();
	}

	public function getContactUsInquiry(Request $request)
	{
		if ($request['activity'] == "contactUsReplyModal") {
			$contactUsInquiry = ContactUs::find($request['inquiryId']);
		}else if ($request['activity'] == "contactUsMessageModal") {
			$contactUsInquiry = ContactUs::with('contactUsReply')->find($request['inquiryId']);
		}
		return $contactUsInquiry;
	}

	public function postContactUsReply(Request $request)
	{
		$data = $request->all();
        $contactUsReply = new ContactUsReply;
		$contactUsReply->contact_us_id = $request['inquiryId'];
		$contactUsReply->reply = $request['replyMessage'];
		$contactUsReply->created_by = Auth::guard('admin')->user()->id;

		if ($contactUsReply->save()) {

			$contactUsInquiry = ContactUs::find($request['inquiryId']);
			$contactUsInquiry->is_replied = 1;
			$contactUsInquiry->save();

			$data = [
				'FIRST_NAME'=>$contactUsInquiry->first_name,
				'LAST_NAME'=>$contactUsInquiry->last_name,
				'INQUIRY_REPLY'=> $contactUsReply->reply
			];
			EmailTemplates::sendMail('contact-us-reply',$data,$contactUsInquiry->email);

			return array(
	            'success' => true,
	            'message' => trans('Sent Successfully!')
	        );
		}

		return array(
	            'success' => false,
	            'message' => trans('messages.success.product.otherInfo_add')
	        );
	}

	public function postDeleteInquiry(Request $request)
	{
		// return $request['inquiryIdForDelete'];
		$contactUsInquiry = ContactUs::find($request['inquiryIdForDelete']);
		if ($contactUsInquiry->delete()) {
			return array(
	            'success' => true,
	            'message' => "Inquiry deleted Successfully"
	        );
		}

	}
}
