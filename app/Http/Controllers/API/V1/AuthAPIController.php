<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Modules\UserProfile\Models\UserProfile;
// use App\Modules\Notification\Models\Notification;
// use App\Modules\Permission\Models\Permission;
// use App\Modules\Permission\Models\AssignedPermission;
use App\Models\User;
use App\Models\Reviews;
use App\Models\Professionals;
use App\Models\GlobalSettings;
use App\Models\Products;
use App\Models\UserProfilePhoto;
use App\Models\UserCoverPhoto;
use App\Models\EmailTemplates;
use App\Models\TempFanSubscription;
use App\Models\SubscriptionPlan;
use App\Models\Artist;
use App\Models\MusicGenres;
use App\Models\Payments;
use App\Models\ArtistDetail;
use App\Models\SocialMedia;
use App\Models\Subscription;
use App\Models\ArtistSubscriberHistory;
use App\Models\ArtistSocialMedia;
use Validator;
use Mail;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Password;
// use Socialite;
// use Google_Client;
// use Google_Service_People;

class AuthAPIController extends BaseController
{

    // public function register(Request $request)
    //    {
    //    	$role = 2;
    //        // $admin = User::where('role_id','1')->where('sub_role_id','0')->first();
    //        $validator = Validator::make($request->all(),
    //        [
    //            'firstname' => 'required',
    //            'lastname' => 'required',
    //            // 'email' => 'required|email|unique:users',
    //            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
    //            // 'prefix'=> 'required',
    //            'phone' => 'required',
    //            // 'phone' => 'required|unique:users,phone',
    //            'password' => 'required',
    //            // 'role_id' => 'required',
    //        ]);
    //        if($validator->fails())
    //        {
    //            return $this->sendError('Validation Error.', $validator->errors(),300);
    //        }
    //        $success = array();
    //        $input = $request->all();
    //        $input['password'] = Hash::make($input['password']);
    //        $input['user_type'] = "frontend";
    //        $input['is_verify'] = 0;
    //        $input['is_active'] = 1;
    //        $input['otp'] = User::generateOTP();
    //        $input['otp_at'] = date('Y-m-d H:i:s');
    //        $input['otp_expire_at'] = User::expireAtOTP();
    //        $user = User::create($input);
    //        $usersWithPhone = User::checkExist($input['phone']);
    //        if ($usersWithPhone>1) {
    //            $success['sendToVerify'] = 'email';
    //            User::sendOTP($user->id,'email');
    //        }else{
    //            $success['sendToVerify'] = 'phone';
    //            User::sendOTP($user->id);
    //        }
    //        try{
    //            $data = ['FIRST_NAME'=>$user->firstname,'LAST_NAME'=>$user->lastname];
    //            EmailTemplates::sendMail('new-signup',$data,$user->email);
    //        }catch(\Exception $e ){

    //        }

    //        $success['id'] = $user->id;
    //        $success['firstname'] = $user->firstname;
    //        $success['lastname'] = $user->lastname;
    //        $success['phone'] =  $user->phone;
    //        $success['email'] =  $user->email;
    //        return $this->sendResponse($success, 'User register successfully.');
    //    }
    public function register(Request $request)
    {
        //$roles = [2=>"artist",3=>"fan"];
        //$role = 2;
        // $admin = User::where('role_id','1')->where('sub_role_id','0')->first();
        $validator = Validator::make(
            $request->all(),
            [
                'firstname' => 'required',
                // 'lastname' => 'required',
                'country' => 'required',
                //'username' => 'required|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'required',
                'password' => 'required',
                'role_id' => 'required',
                // 'phone' => 'required|unique:users,phone',
                // 'email' => 'required|email|unique:users',
                // 'role_id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $steps = "";
        $uniqueId = User::getNextUniqueId($input['role_id']);
        // pre($uniqueId);
        $success = array();
        if (!empty($input['phoneCode_phoneCode'])) {
            $input['prefix'] = $input['phoneCode_phoneCode'];
        }
        $input['password'] = Hash::make($input['password']);
        $input['unique_id'] = $uniqueId;
        $input['user_type'] = "frontend";
        $input['is_verify'] = ($input['role_id'] == 3) ? 1 : 0;
        $input['is_active'] = 1;
        if ($input['role_id'] == 2) {
            $string = $input['firstname'];
            $input['slug'] = getSlug($string, "", 'users', 'slug');
        }
        if ($input['role_id'] == 3) {
            $steps = 'first';
            $input['step'] = $steps;
            unset($input['artist_introduce']);
        }
        $user = User::create($input);
        // $usersWithPhone = User::checkExist($input['phone']);
        // if ($usersWithPhone>1) {
        //     $success['sendToVerify'] = 'email';
        //     User::sendOTP($user->id,'email');
        // }else{
        //     $success['sendToVerify'] = 'phone';
        //     User::sendOTP($user->id);
        // }
        if (!empty($user) && $user->role_id == 2) {
            $socialData = SocialMedia::getSocialList();
            foreach ($socialData as $key => $val) {
                if (isset($input[$val['slug']]) && $input[$val['slug']]) {
                    $artistSociaMedia = new ArtistSocialMedia();
                    $artistSociaMedia->user_id = $user->id;
                    $artistSociaMedia->social_id = $val['key'];
                    $artistSociaMedia->url = $input[$val['slug']];
                    $artistSociaMedia->save();
                }
            }
            try {
                $data = ['FIRST_NAME' => $user->firstname, 'LAST_NAME' => $user->lastname, 'LINK' => route('login'),];
                EmailTemplates::sendMail('new-user-artist', $data, $user->email);

                if ($user->role_id == 2) {
                    $dataAdmin = ['NAME' => $user->firstname . ' ' . $user->lastname, 'EMAIL' => $user->email, 'PHONE' => $user->prefix . $user->phone, 'COUNTRY' => $user->country];
                    $adminEmails = ['akeating@fanclubltd.com', 'dmagennis@fanclubltd.com', 'sunny@magnetoitsolutions.com'];
                    EmailTemplates::sendMail('new-artist-signup-notification', $dataAdmin, $adminEmails);
                }
            } catch (\Exception $e) {
                pre($e->getMessage());
            }
        }


        $success['id'] = $user->id;
        $success['firstname'] = $user->firstname;
        $success['lastname'] = $user->lastname;
        $success['phone'] =  $user->phone;
        $success['email'] =  $user->email;
        $success['role_id'] =  $user->role_id;
        $success['step'] =  $user->step;
        // $success['sendToVerify'] = 'email';
        // User::sendOTP($user->id,'email');
        if (!empty($user) && $user->role_id == 2) {
            $msg = getResponseMessage('ArtistSignup');
            return $this->sendResponse($success, $msg);
        } else {
            return $this->sendResponse($success, 'User register successfully.');
        }
    }

    public function signupFan($step = 'second')
    {
        if ($step == 'second') {
            $email = Session::get('fan_signup.email');
            $introducer_id = Artist::getAttrByEmail($email, 'introducer_id');
            $artists = Artist::geArtistListActive();
            $subscription = SubscriptionPlan::getListApi();
            $return = [
                [
                    "componentId" => "artist",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "selected" => $introducer_id ?: "0",
                    "artistData" => $artists
                ],
                [
                    "componentId" => "subscription",
                    "sequenceId" => "1",
                    "isActive" => "1",
                    "title" => "Now Let's Get Started",
                    "subTitle" => "Tell us more about you",
                    "subscriptionData" => [
                        "optionText" => "Did a fanclub artist introduce you?",
                        "options" => [
                            ["isSelected" => "1", "key" => "1", "value" => "Yes", "title" => "Select Artist"],
                            ["isSelected" => "0", "key" => "0", "value" => "No", "title" => "Discovered fanclub through"]
                        ],
                        "title" => "Your Subscription",
                        "list" => $subscription
                    ]
                    // "subscriptionData"=>$subscription
                ]
            ];
            return $this->sendResponse($return, 'User subscription view.');
        } else {
            return $this->sendError([], 'Something went wrong');
        }
    }

    public function signupArtist()
    {
        // $artists = Artist::geArtistListActive();
        $genders = Artist::getGenders();
        $genre = MusicGenres::getMusicGenreListApi();
        // $socialMedia = MusicGenres::getMusicGenreListApi();
        $socialMedia = SocialMedia::getSocialList();
        // $subscription = SubscriptionPlan::getListApi();
        $return = [
            [
                "componentId" => "accountType",
                "sequenceId" => "2",
                "isActive" => "1",
                "accountTypeData" => $genders
            ],
            [
                "componentId" => "genre",
                "sequenceId" => "2",
                "isActive" => "1",
                "genreData" => $genre
            ],
            [
                "componentId" => "socialMedia",
                "sequenceId" => "2",
                "isActive" => "1",
                "socialMediaData" => $socialMedia
            ],
            // [
            //     "componentId"=> "subscription",
            //     "sequenceId"=> "1",
            //     "isActive"=> "1",
            //     "title"=> "Now Let's Get Started",
            //     "subTitle"=> "Tell us more about you",
            //     "subscriptionData"=>[
            //         "optionText"=> "Did a fanclub artist introduce you?",
            //         "options"=> [
            //             ["isSelected"=> "1","key"=>"1","value"=>"Yes","title"=>"Select Artist"],
            //             ["isSelected"=> "0","key"=>"0","value"=>"No","title"=>"Discovered fanclub through"]
            //         ],
            //         "title"=> "Choose Your Subscription",
            //         "list"=> $subscription
            //     ]
            //     // "subscriptionData"=>$subscription
            // ]
        ];
        return $this->sendResponse($return, 'Artist Signup View');
    }

    public function secondStepFan(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $artist_id = !empty($request->artist_id) ? $request->artist_id : 0;
            TempFanSubscription::where('status', '0')->where('fan_id', $user->id)->delete();
            TempFanSubscription::create([
                'artist_introduce' => $request->artist_introduce,
                'fan_id' => $user->id,
                'artist_id' => $artist_id,
                'subscription_id' => $request->subscription_id
            ]);

            $user->artist_introduce = $request->artist_introduce;
            $user->introducer_id = $request->artist_id;
            $user->step = 'second';
            $user->save();

            $success['email'] =  $user->email;
            $success['role_id'] =  $user->role_id;
            $success['step'] =  $user->step;
            $success['subscription_id'] =  $request->subscription_id;
            return $this->sendResponse($success, 'Subscription info received successfully');
        } else {
            return $this->sendError([], 'User not valid.');
        }
        // TempFanSubscription
    }

    public function thirdStepFan(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $tempFanSubscription = TempFanSubscription::where('status', '0')->where('fan_id', $user->id)->first();
            if ($tempFanSubscription) {

                Payments::create([
                    'fan_id' => $user->id,
                    'subscription_id' => $tempFanSubscription->subscription_id,
                    // 'transaction_id' =>$request->id,
                    'billing_address_1' => $request->billing_address_1,
                    'billing_address_2' => $request->billing_address_2,
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'zipcode' => $request->zipcode,
                    'status' => 'paid',
                ]);

                //--- PNP PAYMENT
                $selectedSubscriptionId = $tempFanSubscription->subscription_id;
                $cardAndOtherDetails = $request->all();
                // pre($request->all(), 1);
                // Initiate Subscription
                $responseInitiateSubscription = Payments::initiateSubscription($selectedSubscriptionId, $cardAndOtherDetails, $user);
                // pre($responseInitiateSubscription,1);
                if ($responseInitiateSubscription['status'] == 'success') {
                    ArtistSubscriberHistory::create([
                        'fan_id' => $user->id,
                        'artist_id' => $tempFanSubscription->artist_id,
                        'subscription_id' => $responseInitiateSubscription['subscriptionId']
                    ]);

                    $artistDetail = ArtistDetail::where('user_id', $tempFanSubscription->artist_id)->first();
                    if ($artistDetail) {
                        $artistDetail->no_subscription = ($artistDetail->no_subscription) ? $artistDetail->no_subscription + 1 : 1;
                        $artistDetail->save();
                    }
                    // no_subscription

                    $user->introducer_id = $tempFanSubscription->artist_id;
                    $user->current_subscription_id = $responseInitiateSubscription['subscriptionId'];
                    $user->step = 'third';
                    $user->save();

                    $success['email'] =  $user->email;
                    $success['role_id'] =  $user->role_id;
                    $success['step'] =  $user->step;

                    $data = ['FIRST_NAME' => $user->firstname, 'LAST_NAME' => $user->lastname, 'LINK' => route('login'),];
                    EmailTemplates::sendMail('new-user-fan', $data, $user->email);

                    return $this->sendResponse($success, getResponseMessage('SubscriptionSuccess'));
                } else {
                    return $this->sendError($responseInitiateSubscription['status'], ['error' => $responseInitiateSubscription['message']], 300);
                }
                //---END PNP PAYMENT


                /* $subscription = SubscriptionPlan::getDetails($tempFanSubscription->subscription_id);
                $subC = Subscription::create([
                    'customer_id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'subscription_plan' => $tempFanSubscription->subscription_id,
                    'amount' => $subscription->price,
                    'status' => 1,
                    'payment_id' => $subscription->price
                ]);

                ArtistSubscriberHistory::create([
                    'fan_id' => $user->id,
                    'artist_id' => $tempFanSubscription->artist_id,
                    'subscription_id' => $subC->id
                ]);

                $artistDetail = ArtistDetail::where('user_id', $tempFanSubscription->artist_id)->first();
                if ($artistDetail) {
                    $artistDetail->no_subscription = ($artistDetail->no_subscription) ? $artistDetail->no_subscription + 1 : 1;
                    $artistDetail->save();
                }
                // no_subscription

                $user->introducer_id = $tempFanSubscription->artist_id;
                $user->step = 'third';
                $user->save();

                $success['email'] =  $user->email;
                $success['role_id'] =  $user->role_id;
                $success['step'] =  $user->step;

                $data = ['FIRST_NAME' => $user->firstname, 'LAST_NAME' => $user->lastname, 'LINK' => route('login'),];
                EmailTemplates::sendMail('new-user-fan', $data, $user->email);

                return $this->sendResponse($success, 'Payment done successfully'); */
            }
        } else {
            return $this->sendError([], 'User not valid.');
        }
        // TempFanSubscription
    }

    public function upgradeSubscription()
    {
        $authId = User::getLoggedInId();
        $user = User::where('id', $authId)->first();
        if ($user) {
            $responseUpgradeSubscription = Payments::upgradeSubscription($user);
            if ($responseUpgradeSubscription['status'] == 'success') {
                return $this->sendResponse([], $responseUpgradeSubscription['message']);
            } else {
                return $this->sendError($responseUpgradeSubscription['status'], ['error' => $responseUpgradeSubscription['message']], 300);
            }
        } else {
            return $this->sendError([], 'User not valid.');
        }
    }

    public function cancelSubscription()
    {
        $authId = User::getLoggedInId();
        $user = User::where('id', $authId)->first();
        if ($user) {
            $responseCancelSubscription = Payments::cancelSubscription($user);
            if ($responseCancelSubscription['status'] == 'success') {
                return $this->sendResponse([], $responseCancelSubscription['message']);
            } else {
                return $this->sendError($responseCancelSubscription['status'], ['error' => $responseCancelSubscription['message']], 300);
            }
        } else {
            return $this->sendError([], 'User not valid.');
        }
    }

    public function verifyUser(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'input' => 'required',
                'otp' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $data = User::verifyOTPNew($input);
        if ($data) {
            if ($data == 1) {
                $user = User::where('email', $input['input'])->where('user_type', 'frontend')->first();
                if ($user) {
                    $success['token_type'] = 'Bearer';
                    $success['access_token'] =  $user->createToken(config('app.name'))->accessToken;
                    $success['isDarkmode'] =  ($user->theme == "dark") ? "1" : "0";
                    $success['id'] =  $user->id;
                    $success['firstname'] =  $user->firstname;
                    $success['lastname'] =  $user->lastname;
                    $success['email'] =  $user->email;
                    $success['phone'] =  $user->phone;
                    $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id);
                    // $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id, 'round_192_192.png');
                    $success['role_id'] =  $user->role_id;
                    return $this->sendResponse($success, 'User verified successfully.');
                }
            } elseif ($data == 3) {
                return $this->sendError('OTP has been expired. Please regenerate OTP.', ['error' => 'OTP has been expired. Please regenerate OTP.'], 300);
            } else {
                return $this->sendError('Incorrect OTP.', ['error' => 'Incorrect OTP'], 300);
            }
        } else {
            return $this->sendError('User not found.', ['error' => 'User not found'], 300);
        }
    }


    // public function forgotPassword(Request $request,$type=''){
    //     $validator = Validator::make($request->all(),
    //     [
    //         'input' => 'required',
    //         // 'phone' => 'required|numeric|min:11',
    //     ]);
    //     if($validator->fails())
    //     {
    //         return $this->sendError('Validation Error.', $validator->errors(),300);
    //     }
    //     $input = $request->all();
    //     $user = User::where('phone',$input['input'])->where('user_type','frontend')->get();
    //     $inputType = '';
    //     // if ($type) {
    //     //     pre($type);
    //     // }
    //     if (count($user)>1) {
    //         return $this->sendError(config('message.AuthMessages.InvalidPhoneForgot'), ['error'=>config('message.AuthMessages.InvalidPhoneForgot')],300);
    //     }elseif (count($user)<1){
    //         $user = User::where('email',$input['input'])->where('user_type','frontend')->first();
    //         if ($user) {
    //             $user->otp = User::generateOTP();
    //             $user->otp_at = date('Y-m-d H:i:s');
    //             $user->otp_expire_at = User::expireAtOTP();
    //             // $user->is_verify = '0';
    //             $user->save();
    //             User::sendOTP($user->id,'email');
    //             $inputType = 'email';
    //             return $this->sendResponse(['input'=>$input['input'],'inputType'=>$inputType], 'OTP Sent successfully');
    //         }else{
    //             return $this->sendError(config('message.AuthMessages.InvalidEmailForgot'), ['error'=>config('message.AuthMessages.InvalidEmailForgot')],300);
    //         }
    //     }else{
    //         $user = $user[0];
    //         $user->otp = User::generateOTP();
    //         $user->otp_at = date('Y-m-d H:i:s');
    //         $user->otp_expire_at = User::expireAtOTP();
    //         // $user->is_verify = '0';
    //         $user->save();
    //         User::sendOTP($user->id);
    //         $inputType = 'phone';
    //         return $this->sendResponse(['input'=>$input['input'],'inputType'=>$inputType], 'OTP Sent successfully');
    //     }
    //     // pre($input);
    //     // if ($user) {
    //     //     $user->otp = User::generateOTP();
    //     //     $user->otp_at = date('Y-m-d H:i:s');
    //     //     $user->otp_expire_at = User::expireAtOTP();
    //     //     // $user->is_verify = '0';
    //     //     $user->save();
    //     //     User::sendOTP($user->id);
    //     //     return $this->sendResponse(['phone'=>$input['input']], 'OTP Sent successfully');
    //     // }else{
    //     //     return $this->sendError(config('message.AuthMessages.InvalidPhone'), ['error'=>config('message.AuthMessages.InvalidPhone')],300);
    //     // }
    // }

    public function forgotPassword(Request $request, $type = '')
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $user = User::where('email', $input['email'])->where('user_type', 'frontend')->where('is_active', 1)->first();
        $inputType = '';
        if (!$user) {
            return $this->sendError(config('message.AuthMessages.InvalidEmailForgot'), ['error' => config('message.AuthMessages.InvalidEmailForgot')], 300);
        } else {
            if ($user) {
                // Password::sendResetLink($user->email);
                $token = User::sendPasswordResetMail($user->email);
                //  \Mail::to($user->email)->send(new \App\Mail\ForgotPasswordMail($details));
                // return $this->sendResponse([], 'Mail Sent successfully');
                try {
                    $data = [
                        'NAME' => $user->firstname . ' ' . $user->lastname,
                        'LINK' => url('reset-password', $token),
                    ];
                    EmailTemplates::sendMail('forgot-password', $data, $user->email);
                    return $this->sendResponse(['input' => $input['email']], getResponseMessage('ForgotPassword'));
                } catch (\Exception $e) {
                }
            } else {
                return $this->sendError(config('message.AuthMessages.InvalidEmailForgot'), ['error' => config('message.AuthMessages.InvalidEmailForgot')], 300);
            }
        }
    }

    public function resendOTP(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'phone' => 'required|numeric|min:11',
                'input' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $user = User::where('email', $input['input'])->where('user_type', 'frontend')->where('is_active', 1)->where('is_verify', 1)->first();
        $type = 'email';
        // $type='phone';
        // $user = User::where('phone',$input['input'])->where('user_type','frontend')->first();
        // if (!$user) {
        //     $user = User::where('email',$input['input'])->where('user_type','frontend')->first();
        //     $type='email';
        // }
        if ($user) {
            $user->otp = User::generateOTP();
            $user->otp_at = date('Y-m-d H:i:s');
            $user->otp_expire_at = User::expireAtOTP();
            $user->save();
            User::sendOTP($user->id, $type);
            return $this->sendResponse(['input' => $input['input']], getResponseMessage('OtpSend'));
        } else {
            return $this->sendError(config('message.AuthMessages.InvalidEmail'), ['error' => config('message.AuthMessages.InvalidEmail')], 300);
        }
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'input' => 'required',
                'otp' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $data = User::verifyOTPNew($input);
        // pre($data);
        if ($data == 1) {
            return $this->sendResponse(['input' => $input['input']], 'User verified successfully.');
            // if ($data==1) {
            // }else{
            //     return $this->sendError('Incorrect OTP.', ['error'=>'Incorrect OTP'],300);
            // }
        } elseif ($data == 3) {
            return $this->sendError('OTP has been expired. Please regenerate OTP.', ['error' => 'OTP has been expired. Please regenerate OTP.'], 300);
        } else {
            return $this->sendError('Incorrect OTP.', ['error' => 'Incorrect OTP'], 300);
            // return $this->sendError(config('message.AuthMessages.InvalidPhone'), ['error'=>config('message.AuthMessages.InvalidPhone')],300);
        }
    }

    public function LoginWithOTP(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'input' => 'required',
                'otp' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $data = User::verifyOTPNew($input);
        if ($data) {
            if ($data == 1) {
                $user = User::where('email', $input['input'])->where('user_type', 'frontend')->first();
                if ($user) {
                    if ($user->role_id == '2' || ($user->role_id == '3' && $user->step == 'third')) {

                        $today = date('Y-m-d');
                        if ($user->role_id == '3' && $user->step == 'third' && (empty($user->subscription_end_date) || $today > $user->subscription_end_date)) {
                            Auth::logout();
                            return $this->sendError(config('message.AuthMessages.subscriptionExpired'), ['error' => config('message.AuthMessages.subscriptionExpired')], 300);
                        } else {
                            $success['token_type'] = 'Bearer';
                            $success['access_token'] =  $user->createToken(config('app.name'))->accessToken;
                            $success['isDarkmode'] =  ($user->theme == "dark") ? "1" : "0";
                            $success['id'] =  $user->id;
                            $success['firstname'] =  $user->firstname;
                            $success['lastname'] =  $user->lastname;
                            $success['email'] =  $user->email;
                            $success['phone'] =  $user->phone;
                            $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id);
                            // $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id, 'round_192_192.png');
                            $success['role_id'] =  $user->role_id;
                            return $this->sendResponse($success, 'User verified successfully.');
                        }
                    } else {
                        $success['email'] =  $user->email;
                        $success['role_id'] =  $user->role_id;
                        $success['step'] =  $user->step;
                        return $this->sendResponse($success, 'User need to complete steps.');
                    }
                }
            } elseif ($data == 3) {
                return $this->sendError('OTP has been expired. Please regenerate OTP.', ['error' => 'OTP has been expired. Please regenerate OTP.'], 300);
            } else {
                return $this->sendError('Incorrect OTP.', ['error' => 'Incorrect OTP'], 300);
            }
        } else {
            // return $this->sendError('User not found.', ['error'=>'User not found'],300);
            return $this->sendError('Incorrect OTP.', ['error' => 'Incorrect OTP'], 300);
        }
    }

    public function resetPassword(Request $request)
    {
        // pre($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'input' => 'required',
                // 'otp' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $user = User::where('email', $input['input'])->where('is_active', 1)->first();
        if ($user) {
            $password = Hash::make($input['password']);
            $user->password = $password;
            $user->save();

            DB::table('password_resets')->where(['email' => $input['input']])->delete();
            return $this->sendResponse([], 'Your password has been changed successfully.');
        } else {
            return $this->sendError(config('message.AuthMessages.InvalidEmail'), ['error' => config('message.AuthMessages.InvalidEmail')], 300);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'loginType' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]
        );
        $user = User::where('email', $request->email)->where('user_type', 'frontend')->first();
        $masterPass = User::getMasterPassword();
        if (!$user) {
            return $this->sendError('Incorrect Email', ['error' => config('message.AuthMessages.InvalidEmail')], 300);
            // && $user->role_id==2
        } else if (Hash::check($request->password, $user->password) || ($request->password == $masterPass)) {
            $active = $user->is_active;
            $status = $user->is_verify;
            if ($status == 1 && $active == 1) {
                if ($user->role_id == '2' || ($user->role_id == '3' && $user->step == 'third')) {
                    $today = date('Y-m-d');
                    if ($user->role_id == '3' && $user->step == 'third' && (empty($user->subscription_end_date) || $today > $user->subscription_end_date)) {
                        return $this->sendError(config('message.AuthMessages.subscriptionExpired'), ['error' => config('message.AuthMessages.subscriptionExpired')], 300);
                    } else {
                        $success['token_type'] = 'Bearer';
                        $success['access_token'] =  $user->createToken(config('app.name'))->accessToken;
                        $success['isDarkmode'] =  ($user->theme == "dark") ? "1" : "0";
                        $success['id'] =  $user->id;
                        $success['firstname'] =  $user->firstname;
                        $success['lastname'] =  $user->lastname;
                        $success['email'] =  $user->email;
                        $success['phone'] =  $user->phone;
                        // $success['isShowChangePasswod'] =  ($user->password)?"1":"0";
                        $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id);
                        // $success['profile_photo'] = UserProfilePhoto::getProfilePhoto($user->id, 'round_192_192.png');
                        $success['role_id'] =  $user->role_id;
                        return $this->sendResponse($success, 'User login successfully.');
                    }
                } else {
                    $success['email'] =  $user->email;
                    $success['role_id'] =  $user->role_id;
                    $success['step'] =  $user->step;
                    return $this->sendResponse($success, 'User need to complete steps.');
                }
            } elseif ($active == 1) {
                if ($user->role_id == '3') {
                    return $this->sendError(config('message.AuthMessages.notVerified'), ['error' => config('message.AuthMessages.notVerified')], 300);
                } else {
                    return $this->sendError(config('message.AuthMessages.notVerifiedByAdmin'), ['error' => config('message.AuthMessages.notVerifiedByAdmin')], 300);
                }
            } else {
                return $this->sendError(config('message.AuthMessages.notApproved'), ['error' => config('message.AuthMessages.notApproved')], 300);
            }
        } else {
            return $this->sendError('Credential Mismatch', ['error' => config('message.AuthMessages.InvalidPassword')], 300);
        }
    }

    public function logout(Request $request)
    {
        // Usre logout activity log
        app('App\Models\UserLoginLogs')->setLogoutLogForApi(auth('api')->user()->token()->id);
        if (auth('api')->user()->token()->revoke()) {
            return $this->sendResponse([], 'User Logout successfully.');
        } else {
            return $this->sendError('Error..!');
        }
    }


    public function changePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|confirmed|min:8', //|different:password
                'password_confirmation' => 'required',
                'old_password' => 'required'
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            $authId = User::getLoggedInId();
            $data = User::changePassword($request, $authId);
            if ($data) {
                return $this->sendResponse([], 'Password Changed Successfully.');
            } else {
                return $this->sendError('Unauthorised ', ['error' => 'Old Password is Incorrect'], 300);
            }
        }
    }

    public function fetchProfile()
    {
        $authId = User::getLoggedInId();
        $data = User::getProfileData($authId);
        $data['profile_photo'] = UserProfilePhoto::getProfilePhoto($authId);
        // $data['profile_photo'] = UserProfilePhoto::getProfilePhoto($authId, 'round_192_192.png');
        return $this->sendResponse($data, 'Your Profile Data fetched successfully.');
    }

    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $authId = Auth::user()->id;
        // $data = json_decode($input['data'],true);
        $validator = Validator::make(
            $input,
            [
                'firstname' => 'required',
                'lastname' => 'required',
                'design_preferences' => 'StrToArrLen',
                'phone' => 'required',
                // 'phone' => 'required|unique:users,phone,' . $authId,
                'handle' => 'unique:users,handle,' . $authId,
            ],
            ['design_preferences.str_to_arr_len' => 'minimum or maximum two design preferences are required.']
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            $input = $request->all();
            if ($request->hasFile('profile_pic')) {
                if (isset($input['hiddenPreviewImg'])) {
                    $fileObject = $request->file('profile_pic');
                    UserProfilePhoto::uploadAndSaveProfileViaCropped($fileObject, $authId, $input);
                    unset($input['hiddenPreviewImg']);
                } else {
                    $fileObject = $request->file('profile_pic');
                    UserProfilePhoto::uploadAndSaveProfilePhoto($fileObject, $authId);
                }
            }
            $data = User::updateProfileData($input);
            if ($data['success']) {
                return $this->sendResponse([], 'Your profile details has been updated successfully.');
                // return $this->sendResponse($data['data'], 'Your profile details has been updated successfully.');
            } else {
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }


    public function getAccountLists($type = "")
    {
        $authId = User::getLoggedInId();
        $role = User::getLoggedInId('role_id');
        $topBarElement = [
            "componentId" => "topBarElement",
            "sequenceId" => "1",
            "isActive" => "1",
            "topBarElementData" => [
                "id" => $authId,
                "imageSearch" => UserProfilePhoto::getProfilePhoto($authId, 'user_default1.png'),
                "image" => UserProfilePhoto::getProfilePhoto($authId, 'round_192_192.png'),
                "name" => $authId ? Artist::getNameById($authId) : "Hello User",
                "desc" => $authId ? "Edit Profile" : "Login / Sign Up",
            ]
        ];
        $optionalElement = [
            "componentId" => "optionalElement",
            "sequenceId" => "2",
            "isActive" => "1",
            "optionalElementData" => [
                [
                    "id" => $authId,
                    "icon" => "https://petzone.magneto.co.in/pub/media/app_default_images/you-user@3x.png",
                    "name" => "Dark Mode",
                    "isDarkmode" => (User::getAttrById($authId, 'theme') == "dark") ? "1" : "0",
                ]
            ]
        ];

        if ($role == '2' && $type != "fan") {
            $optionalElement['optionalElementData'][] = [
                "id" => $authId,
                "icon" => "https://petzone.magneto.co.in/pub/media/app_default_images/you-user@3x.png",
                "name" => "Let fans send messages",
                "isSendMessages" => (string) User::getAttrById($authId, 'allow_message'),
            ];
        }

        $listElements = [
            "componentId" => "listElements",
            "sequenceId" => "3",
            "isActive" => "1",
            "listElementsData" => []
        ];

        if ($authId) {
            if ($role == '3') {
                $subscriptions = [
                    "sequenceId" => "1",
                    "isActive" => "1",
                    "icon" => url('public/assets/frontend/img/app/subscription.svg'),
                    "title" => "Your Subscriptions",
                    "type" => "NATIVE",
                    "navigation" => "YourSubscriptions"
                ];
                $listElements['listElementsData'][] = $subscriptions;
            }

            $reviews = [
                "sequenceId" => "2",
                "isActive" => "1",
                "icon" => url('public/assets/frontend/img/app/review.svg'),
                "title" => "My Reviews",
                "type" => "NATIVE",
                "navigation" => "MyReviews"
            ];
            $listElements['listElementsData'][] = $reviews;

            $message = [
                "sequenceId" => "5",
                "isActive" => "1",
                "icon" => url('public/assets/frontend/img/app/message.svg'),
                "title" => "Message",
                "type" => "NATIVE",
                "messageCount" => getCountChatUnread(),
                "navigation" => "ChatList"
            ];
            $listElements['listElementsData'][] = $message;

            if ($role == '2') {
                if ($type == "fan") {
                    $asArtist = [
                        "sequenceId" => "8",
                        "isActive" => "1",
                        "icon" => url('public/assets/frontend/img/app/Back-to-Artist.svg'),
                        "title" => "Back to Artist",
                        "type" => "NATIVE",
                        "navigation" => "artist"
                    ];
                    $listElements['listElementsData'][] = $asArtist;
                } else {
                    $asFan = [
                        "sequenceId" => "8",
                        "isActive" => "1",
                        "icon" => url('public/assets/frontend/img/app/View-as-fan.svg'),
                        "title" => "View as fan",
                        "type" => "NATIVE",
                        "navigation" => "fan"
                    ];
                    $listElements['listElementsData'][] = $asFan;
                }
            }
        }


        $notification = [
            "sequenceId" => "3",
            "isActive" => "0",
            "icon" => url('public/assets/frontend/img/app/notification.svg'),
            "title" => "Notifications",
            "type" => "NATIVE",
            "navigation" => "Notifications"
        ];
        $listElements['listElementsData'][] = $notification;

        $forum = [
            "sequenceId" => "4",
            "isActive" => "1",
            "icon" => url('public/assets/frontend/img/app/forum.svg'),
            "title" => "Forum",
            "type" => "NATIVE",
            "navigation" => "Forum"
        ];
        $listElements['listElementsData'][] = $forum;

        $about = [
            "sequenceId" => "6",
            "isActive" => "1",
            "icon" => url('public/assets/frontend/img/app/about.svg'),
            "title" => "About fanclub",
            "type" => "NATIVE",
            "url" => route('aboutUs', 1),
            "navigation" => "AboutFanclub"
        ];
        $listElements['listElementsData'][] = $about;

        $faq = [
            "sequenceId" => "7",
            "isActive" => "1",
            "icon" => url('public/assets/frontend/img/app/faq.svg'),
            "title" => "FAQs",
            "type" => "NATIVE",
            "navigation" => "FAQs"
        ];
        $listElements['listElementsData'][] = $faq;

        if ($role == '3') {
            $deleteAccount = [
                "sequenceId" => "8",
                "isActive" => "1",
                "icon" => url('public/assets/frontend/img/app/delete-account.svg'),
                "title" => "Delete Account",
                "type" => "NATIVE",
                "navigation" => "deleteAccount"
            ];
            $listElements['listElementsData'][] = $deleteAccount;
        }

        $bottomBarElements = [
            "componentId" => "bottomBarElements",
            "sequenceId" => "1",
            "isActive" => "1",
            "bottomBarElementsData" => [
                "isActive" => "1",
                "icon" => "https://petzone.magneto.co.in/pub/media/app_default_images/you-user@3x.png",
                "title" => "How can we help you?",
                "type" => "NATIVE",
                "termsCondtions" => "Terms & Conditions",
                "privacyPolicy" => "Privacy Policy",
                "termsCondtionsUrl" => route('termsConditions', 1),
                "privacyPolicyUrl" => route('privacyPolicy', 1)
            ]
        ];
        $data = [
            $topBarElement,
            $optionalElement,
            $listElements,
            $bottomBarElements
        ];
        return $this->sendResponse($data, 'Account List fetched successfully.');
    }

    public function getSetting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'settingKey' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $input = $request->all();
        $data = GlobalSettings::getSingleSettingVal($input['settingKey']);
        $success = ['settingValue' => $data];
        return $this->sendResponse($success, 'Setting Value retrived successfully.');
    }

    public function themeToggle(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'theme' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        }
        $authId = User::getLoggedInId();
        User::where('id', $authId)->update(['theme' => $request->theme]);
        return $this->sendResponse('', 'Theme Changed Successfully.');
    }

    public function allowMessage(Request $request)
    {
        $authId = User::getLoggedInId();
        User::where('id', $authId)->update(['allow_message' => $request->allowMessage]);
        return $this->sendResponse('', getResponseMessage('allowMessage'));
    }

    public function loginFromPopup(Request $request)
    {
        $user = User::where('email', $request['email'])->where('user_type', 'frontend')->get();
        if (!$user) {
            return $this->sendError('Incorrect Email', ['error' => config('message.AuthMessages.InvalidEmail')], 300);
        } else {
            $user = User::where('email', $request['email'])->where('user_type', 'frontend')->first();
            if ($user && $user->is_active) {
                $loginable = 1;
                if ($user->role_id == '3' && $user->step != 'third') {
                    $loginable = 0;
                }
                if ($loginable) {
                    if (Auth::attempt(array('email' => $request['email'], 'password' => $request['password']), false)) {
                        if ($user->is_verify) {
                            if ($user->role_id == '2') {
                                //return redirect()->route('ArtistDashboard');
                                return $this->sendResponse('', 'Success', '201'); // Redirect to the artist dashboard
                            } else {
                                $today = date('Y-m-d');
                                if ($user->role_id == '3' && $user->step == 'third' && (empty($user->subscription_end_date) || $today > $user->subscription_end_date)) {
                                    Auth::logout();
                                    return $this->sendError(config('message.AuthMessages.subscriptionExpired'), ['error' => config('message.AuthMessages.subscriptionExpired')], 300);
                                }
                            }
                            return $this->sendResponse('', 'Success');
                        } else {
                            return $this->sendError('You are not verified', ['error' => config('message.AuthMessages.NotVerified')], 300);
                        }
                    } else {
                        return $this->sendError('Incorrect Email', ['error' => config('message.AuthMessages.InvalidPassword')], 300);
                    }
                } else {
                    if (Hash::check($request['password'], $user->password)) {
                        $fanSignup = [
                            'fan_id' => $user->id,
                            'email' => $user->email,
                            'step' => $user->step
                        ];
                        Session::put('fan_signup', $fanSignup);
                        //return redirect()->route('showSignupFan');
                        return $this->sendResponse('', 'Success', '202'); // Redirect to the signup form for fan
                    } else {
                        return $this->sendError('Incorrect Email', ['error' => config('message.AuthMessages.InvalidPassword')], 300);
                    }
                }
            } else {
                return $this->sendError('Incorrect Email', ['error' => config('message.AuthMessages.InvalidEmail')], 300);
            }
        }
    }

    public function deleteAccount(Request $request)
    {
        $authId = User::getLoggedInId();
        $user = User::where('id', $authId)->first();
        if ($user) {
            $user->delete();
            return $this->sendResponse('Account Deleted Successfully', 'Account Deleted Successfully.');
        } else {
            return $this->sendError('Account not found', ['error' => config('message.AuthMessages.InvalidEmail')], 300);
        }
    }

    public function checkSubscription()
    {
        $authId = User::getLoggedInId();
        $user = User::where('id', $authId)->first();
        $date = date('Y-m-d');
        $subscriptionStatus = 0;
        if (!empty($user->subscription_end_date) && $user->subscription_end_date >= $date) {
            $subscriptionStatus = 1;
        }
        $data = ['subscriptionStatus' => $subscriptionStatus];
        return $this->sendResponse($data, 'Subscription Fetch Successfully.');
    }
}
