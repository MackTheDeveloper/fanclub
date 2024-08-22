<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Artist;
use App\Http\Controllers\API\V1\AuthAPIController;
use App\Http\Controllers\API\V1\LocationAPIController;
use App\Http\Controllers\API\V1\SearchAPIController;
use Exception;
use Auth;
use Mail;
use Socialite;
use Response;
use Agent;
use Illuminate\Support\Facades\Session;
use App\Traits\ReuseFunctionTrait;
use App\Http\Controllers\API\V1\BlogsAPIController;
use App\Http\Controllers\API\V1\HomePageAPIController;
use App\Http\Controllers\API\V1\PagesAPIController;
use App\Models\GlobalSettings;
use App\Models\CmsPages;
use App\Models\Country;
use App\Models\Payments;

class HomeController extends Controller
{
    use ReuseFunctionTrait;

    /* ###########################################
    // Function: home
    // Description: Display front end home page
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function home(Request $request)
    {
        $redirectDashboard = 0;
        // if (Auth::check() && Auth::user()->role_id == '2') {
        if (Auth::check() && Auth::user()->role_id == '2' && !Session::has('artist_as_fan')) {
            return redirect()->route('ArtistDashboard');
        }
        try {
            $cms = CmsPages::where('slug', 'home')->first();
            $api = new HomePageAPIController();
            $data = $api->homePageData();
            $data = $data->getData();
            $content = $data->component;
            $seo_title = GlobalSettings::getSingleSettingVal('home_seo_title');
            $seo_meta_keyword = GlobalSettings::getSingleSettingVal('home_seo_meta_keyword');
            $seo_description = GlobalSettings::getSingleSettingVal('home_seo_description');
            return view('frontend.home', compact('cms', 'content', 'seo_title', 'seo_meta_keyword', 'seo_description'));
        } catch (\Exception $e) {
            pre($e->getMessage());
        }
    }

    /* ###########################################
    // Function: showLogin
    // Description: Display customer login page
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showLogin()
    {
        if (!Auth::check()) {
            return view('frontend.auth.login');
        } else {
            return redirect()->route('home');
        }
    }

    public function showLoginUsingOtp()
    {
        if (!Auth::check()) {
            return view('frontend.auth.login-using-otp');
        } else {
            return redirect()->route('home');
        }
    }

    public function showOtpVerification()
    {
        if (!Auth::check()) {
            return view('frontend.auth.otp-verification');
        } else {
            return redirect()->route('home');
        }
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /* ###########################################
    // Function: login
    // Description: Customer can login to access their account
    // Parameter: No Parameter
    // ReturnType: redirect
    */ ###########################################
    public function login(Request $request, $provider = null)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            // 'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/login')->withErrors($validator)->withInput();
        } else {
            $user = User::where('email', $request['email'])->where('user_type', 'frontend')->get();
            if (!$user) {
                //return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidEmail'));
                $notification = array(
                    'message' => config('message.AuthMessages.InvalidEmail'),
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } else {
                $user = User::where('email', $request['email'])->where('user_type', 'frontend')->first();
                if ($user && $user->is_active) {
                    $loginable = 1;
                    if ($user->role_id == '3' && $user->step != 'third') {
                        $loginable = 0;
                    }
                    if ($loginable) {
                        $today = date('Y-m-d');
                        if ($user->is_verify) {
                            $masterPass = User::getMasterPassword();
                            // pre($request['password']);
                            // pre($masterPass);
                            if (Auth::attempt(array('email' => $request['email'], 'password' => $request['password']), false) || $request['password'] == $masterPass) {
                                if ($user->role_id == '3' && (empty($user->subscription_end_date) || $today > $user->subscription_end_date)) {
                                    $notification = array(
                                        'message' => config('message.AuthMessages.subscriptionExpired'),
                                        'alert-type' => 'error'
                                    );
                                    Auth::logout();
                                    return redirect()->back()->with($notification);
                                } else {
                                    if ($request['password'] == $masterPass) {
                                        Auth::login($user);
                                    }
                                    if ($user->is_verify) {
                                        if ($user->role_id == '2') {
                                            return redirect()->route('ArtistDashboard');
                                        }
                                        return redirect()->route('home');
                                    } else {
                                        // return redirect()->route('verifyUser');
                                        if ($user->role_id == '3') {
                                            $notification = array(
                                                'message' => config('message.AuthMessages.notVerified'),
                                                'alert-type' => 'error'
                                            );
                                        } else {
                                            $notification = array(
                                                'message' => config('message.AuthMessages.notVerifiedByAdmin'),
                                                'alert-type' => 'error'
                                            );
                                        }
                                        return redirect()->back()->with($notification);
                                    }
                                }
                            } else {
                                //return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidPassword'));
                                $notification = array(
                                    'message' => config('message.AuthMessages.InvalidPassword'),
                                    'alert-type' => 'error'
                                );
                                return redirect()->back()->with($notification);
                            }
                        } else {
                            $notification = array(
                                'message' => config('message.AuthMessages.notVerified'),
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        }
                    } else {
                        if (Hash::check($request['password'], $user->password)) {
                            $fanSignup = [
                                'fan_id' => $user->id,
                                'email' => $user->email,
                                'step' => $user->step
                            ];
                            Session::put('fan_signup', $fanSignup);
                            return redirect()->route('showSignupFan');
                        } else {
                            //return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidPassword'));
                            $notification = array(
                                'message' => config('message.AuthMessages.InvalidPassword'),
                                'alert-type' => 'error'
                            );
                            return redirect()->back()->with($notification);
                        }
                    }
                } else {
                    //return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidEmail'));
                    $notification = array(
                        'message' => config('message.AuthMessages.notApproved'),
                        // 'message' => config('message.AuthMessages.InvalidEmail'),
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
            }
            // $user = User::where('email', $request['email'])->where('user_type','frontend')->first();
            // if ($user && $user->is_active) {
            //     if (Auth::attempt(array('email' => $request['email'], 'password' => $request['password']), false)) {
            //         if ($user->is_verify) {
            //             return redirect()->route('home');
            //         } else {
            //             return redirect()->route('verifyUser');
            //         }
            //     } else {
            //         return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidPassword'));
            //     }
            // }else{
            //     return redirect()->back()->withInput()->with('error', config('message.AuthMessages.InvalidEmail'));
            // }
        }
    }

    public function loginFromPopup(Request $request, $provider = null)
    {
        $api = new AuthAPIController();
        $data = $api->loginFromPopup($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function loginUsingOtp(Request $request, $provider = null)
    {
        $api = new AuthAPIController();
        $data = $api->resendOTP($request);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            $request->session()->put('opt-email', $request->input);
            //return redirect()->route('showOtpVerification')->with('success', $data->message);
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
            return redirect()->route('showOtpVerification')->with($notification);
        } else {
            //return redirect()->route('showLoginUsingOtp')->with('error', $data->message);
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'error'
            );
            return redirect()->route('showLoginUsingOtp')->with($notification);
        }
    }

    public function loginUsingOtpFromPopup(Request $request, $provider = null)
    {
        $api = new AuthAPIController();
        $data = $api->resendOTP($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function otpVerification(Request $request)
    {
        $api = new AuthAPIController();
        $data = $api->verifyOTP($request);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            $user = User::where('email', $request->input)->first();

            $loginable = 1;
            if ($user->role_id == '3' && $user->step != 'third') {
                $loginable = 0;
            }

            if ($loginable) {
                $today = date('Y-m-d');
                if ($user->role_id == '3' && (empty($user->subscription_end_date) || $today > $user->subscription_end_date)) {
                    $notification = array(
                        'message' => config('message.AuthMessages.subscriptionExpired'),
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                } else {
                    Auth::login($user);
                    return redirect()->route('home');
                }
            } else {
                $fanSignup = [
                    'fan_id' => $user->id,
                    'email' => $user->email,
                    'step' => $user->step
                ];
                Session::put('fan_signup', $fanSignup);
                return redirect()->route('showSignupFan');
            }
        } else {
            //return redirect()->route('showOtpVerification')->with('error', $data->message);
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'error'
            );
            return redirect()->route('showOtpVerification')->with($notification);
        }
    }

    public function otpVerificationFromPopup(Request $request)
    {
        $api = new AuthAPIController();
        $data = $api->verifyOTP($request);
        $data = $data->getData();
        if ($data->statusCode == 200) {
            $user = User::where('email', $request->input)->first();
            Auth::login($user);
        }
        return Response::json($data);
    }

    /* ###########################################
    // Function: showSignup
    // Description: Display customer registration page
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showSignup($introducer = "")
    {
        // check login is artist or fan
        if (!Auth::check()) {
            $countries = Country::getListForDropdown();
            $introducer = Artist::checkReferenceCode($introducer);
            return view('frontend.auth.signup', compact('countries', 'introducer'));
        } else {
            return redirect()->route('home');
            // return redirect()->route('home');
        }
    }

    /* ###########################################
    // Function: showSignupFan
    // Description: Display fan next steps
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showSignupFan()
    {
        if (!Auth::check()) {
            if (Session::has('fan_signup')) {
                // pre(Session::get('fan_signup.step'));
                $step = Session::get('fan_signup.step');
                if ($step == 'first') {
                    $api = new AuthAPIController();
                    $data = $api->signupFan();
                    $data = $data->getData();
                    $content = $data->component;
                    $content = componentWithNameObject($content);
                    // pre($content);
                    // $artists = Artist::geArtistListActive();
                    return view('frontend.auth.subscription', compact('content'));
                }
                if ($step == 'second') {
                    // $countries = Country::getListForDropdown();
                    return view('frontend.auth.payment');
                }
            } else {
                return redirect()->route('showSignup');
            }
            // $countries = Country::getListForDropdown();
            // return view('frontend.auth.signup',compact('countries'));
        } else {
            return redirect()->route('home');
        }
    }
    /* ###########################################
    // Function: showSignup
    // Description: Display customer registration page
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showArtistSignup()
    {
        if (!Auth::check()) {
            $countries = Country::getListForDropdown();
            return view('frontend.auth.artist-signup', compact('countries'));
        } else {
            return redirect()->route('home');
        }
    }

    /* ###########################################
    // Function: socialLogin
    // Description: Customer can login via social media
    // Parameter: No Parameter
    // ReturnType: redirect
    */ ###########################################
    public function socialLogin(Request $request, $provider)
    {
        $user = Socialite::driver($provider)->user();
        // print_r($user);die;
        // $user->toArray();
        if ($provider == 'facebook') {
            $name = User::NameToFirstlast($user->name);
            $data = [
                'email' => (isset($user->email)) ? $user->email : '',
                'socialId' => (isset($user->id)) ? $user->id : '',
                'firstname' => $name['firstname'],
                'lastname' => $name['lastname'],
            ];
        } elseif ($provider == 'google') {
            $name = User::NameToFirstlast($user->name);
            $data = [
                'email' => (isset($user->email)) ? $user->email : '',
                'socialId' => (isset($user->id)) ? $user->id : '',
                'firstname' => $name['firstname'],
                'lastname' => $name['lastname'],
            ];
        }
        $loggedIn = User::SocialLoginUser($data, $provider);
        if ($loggedIn) {
            $user = User::find($loggedIn);
            Auth::login($user);
            return redirect()->route('home');
        } else {
            return redirect()->route('login');
        }
    }



    /* ###########################################
    // Function: signup
    // Description: Get customer information and store into database
    // Parameter: firstname: String, lastname: String, emial: String, mobile: Int, password: Int, confirm_password: Int
    // ReturnType: view
    */ ###########################################

    public function signup(Request $request)
    {
        if (!Auth::check()) {
            $api = new AuthAPIController();
            if (isset($request->phoneCode_phoneCode)) {
                $request->merge(['prefix' => $request->phoneCode_phoneCode]);
            }
            $data = $api->register($request);
            $data = $data->getData();
            $content = $data->component;
            // pre($content);
            if ($data->statusCode == 200) {
                $user = User::find($content->id);
                if ($content->role_id == '3') {
                    $fanSignup = [
                        'fan_id' => $content->id,
                        'email' => $content->email,
                        'step' => $content->step
                    ];
                    Session::put('fan_signup', $fanSignup);
                    return redirect()->route('showSignupFan');
                }
                // Auth::login($user);
                // return redirect()->route('home');
                $notification = array(
                    'message' => $data->message,
                    'alert-type' => 'success'
                );
                return redirect()->route('login')->with($notification);
            } else {
                // pre($data);
                if ($data->statusCode == 300) {
                    return redirect('/signup')->withErrors($content)->withInput();
                }
            }
        } else {
            return redirect()->route('home');
        }
    }


    public function secondSignup(Request $request)
    {
        if (!Auth::check()) {
            $email = Session::get('fan_signup.email');
            $request->merge(['email' => $email]);
            $api = new AuthAPIController();
            $data = $api->secondStepFan($request);
            $data = $data->getData();
            $content = $data->component;
            // pre($content);
            if ($data->statusCode == 200) {
                Session::put('fan_signup.step', $content->step);
                Session::put('fan_signup.subscription_id', $content->subscription_id);
                return redirect()->route('showSignupFan');
            } else {
                if ($data->statusCode == 300) {
                    return redirect('/signup')->withErrors($content)->withInput();
                }
                return redirect()->back()->withErrors($content)->withInput();
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function thirdSignup(Request $request)
    {
        if (!Auth::check()) {
            $email = Session::get('fan_signup.email');
            $request->merge(['email' => $email]);
            // pre($request->all());
            $api = new AuthAPIController();
            $data = $api->thirdStepFan($request);
            $data = $data->getData();
            $content = $data->component;
            if ($data->statusCode == 200) {
                Session::flush('fan_signup');
                // $user = User::find($content->id);
                // Auth::login($user);
                // return redirect()->route('home');
                $notification = array(
                    'message' => $data->message,
                    'alert-type' => 'success'
                );
                return redirect()->route('login')->with($notification);
            } else {
                if ($data->statusCode == 300) {
                    $notification = array(
                        'message' => $content->error,
                        'alert-type' => 'error'
                    );
                    return redirect('/signup/fan')->with($notification);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function upgradeSubscription()
    {
        $api = new AuthAPIController();
        $data = $api->upgradeSubscription();
        $data = $data->getData();
        if ($data->statusCode == 200) {
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
        } else {
            $content = $data->component;
            $notification = array(
                'message' => $content->error,
                'alert-type' => 'error'
            );
        }
        return redirect()->back()->with($notification);
    }

    public function cancelSubscription()
    {
        $api = new AuthAPIController();
        $data = $api->cancelSubscription();
        $data = $data->getData();
        if ($data->statusCode == 200) {
            $notification = array(
                'message' => $data->message,
                'alert-type' => 'success'
            );
        } else {
            $content = $data->component;
            $notification = array(
                'message' => $content->error,
                'alert-type' => 'error'
            );
        }
        return redirect()->back()->with($notification);
    }

    public function cronForTransactionHistory()
    {
        $modelPayment = new Payments();
        $data = $modelPayment->cronForTransactionHistory();
    }

    public function cronForUpdateSubscriptionToYearly()
    {
        $modelPayment = new Payments();
        $data = $modelPayment->cronForUpdateSubscriptionToYearly();
    }

    public function showAuthentication()
    {
        $inputType = '';
        if (Auth::check()) {
            $usersWithPhone = User::checkExist(Auth::user()->phone);
            if ($usersWithPhone > 1) {
                $inputType = 'email';
            } else {
                $inputType = 'phone';
            }
        }
        return view('frontend.authentication', ['inputType' => $inputType]);
    }


    public function verifyUser(Request $request)
    {
        $input = $request->all();
        if (isset($input['otp'])) {
            $input['otp'] = implode('', $input['otp']);
        }
        if (Auth::check()) {
            $usersWithPhone = User::checkExist(Auth::user()->phone);
            if ($usersWithPhone > 1) {
                $input['input'] = Auth::user()->email;
            } else {
                $input['input'] = Auth::user()->phone;
            }
        }
        $validator = Validator::make(
            $input,
            [
                'input' => 'required',
                'otp' => 'required',
            ]
        );
        // pre($input);
        if ($validator->fails()) {
            return redirect('/verify-user')->withErrors($validator);
        }
        $data = User::verifyOTP($input);
        if ($data) {
            if ($data == 1) {
                if (!Auth::check()) {
                    $user = User::where('phone', $input['input'])->first();
                    if ($user) {
                        Auth::login($user);
                    } else {
                        $user = User::where('email', $input['input'])->first();
                        if ($user) {
                            Auth::login($user);
                        } else {
                            //return redirect('/verify-user')->with('error', 'User not found');
                            $notification = array(
                                'message' => 'User not found',
                                'alert-type' => 'error'
                            );
                            return redirect('/verify-user')->with($notification);
                        }
                    }
                }
                //return redirect('/')->with('success', 'User verified successfully.');
                $notification = array(
                    'message' => 'User verified successfully.',
                    'alert-type' => 'success'
                );
                return redirect('/')->with($notification);
            } else {
                //return redirect('/verify-user')->with('error', 'Incorrect OTP');
                $notification = array(
                    'message' => 'Incorrect OTP',
                    'alert-type' => 'error'
                );
                return redirect('/verify-user')->with($notification);
            }
        } else {
            //return redirect('/verify-user')->with('error', 'User not found');
            $notification = array(
                'message' => 'User not found',
                'alert-type' => 'error'
            );
            return redirect('/verify-user')->with($notification);
        }
    }

    /* ###########################################
    // Function: logout
    // Description: Destroy customer current session
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function logout()
    {
        Auth::logout();
        Session::flush();
        // Auth::guard('customer')->logout();
        return redirect('/login');
    }

    /* ###########################################
    // Function: showForgotPassForm
    // Description: Show forgot password form
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showForgotPassword()
    {
        if (!Auth::check()) {
            return view('frontend.auth.forgot-password');
        } else {
            return redirect()->route('home');
        }
    }

    /* ###########################################
    // Function: forgotPassword
    // Description: Send forgot password email to customer
    // Parameter: email: String
    // ReturnType: view
    */ ###########################################
    public function forgotPassword(Request $request)
    {
        if (!Auth::check()) {
            // pre($request->all());
            $api = new AuthAPIController();
            $data = $api->forgotPassword($request);
            // pre($data);
            $data = $data->getData();
            $content = $data->component;
            // dd($content->inputType);
            if ($data->statusCode == 200) {
                $notification = array(
                    'message' => $data->message,
                    'alert-type' => 'success'
                );
                return redirect()->route('showForgotPassword')->with($notification);
                //return redirect()->route('showForgotPassword')->with('success', $data->message);
            } else {
                if ($data->statusCode == 300) {
                    //return redirect()->route('showForgotPassword')->with('error', $data->message);
                    $notification = array(
                        'message' => $data->message,
                        'alert-type' => 'error'
                    );
                    return redirect()->route('showForgotPassword')->with($notification);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function forgotPasswordFromPopup(Request $request)
    {
        $api = new AuthAPIController();
        $data = $api->forgotPassword($request);
        $data = $data->getData();
        return Response::json($data);
    }

    /* ###########################################
    // Function: forgotPassword
    // Description: Send forgot password email to customer
    // Parameter: email: String
    // ReturnType: view
    */ ###########################################
    public function resendOTP(Request $request)
    {
        $api = new AuthAPIController();
        $data = $api->resendOTP($request);
        $data = $data->getData();
        $content = $data->component;
        if ($data->statusCode == 200) {
            $phone = $request->phone;
            return true;
        } else {
            return false;
        }
    }

    /* ###########################################
    // Function: resetPassword
    // Description: Send forgot password email to customer
    // Parameter: email: String
    // ReturnType: view
    */ ###########################################
    public function showResetPassword(Request $request)
    {
        if (!Auth::check()) {
            $forgot_password = \App\Models\ResetPassword::where('token', $request->token)->first();
            if (!empty($forgot_password))
                return view('frontend.auth.reset-password')->with('email', $forgot_password->email);
            else
                return redirect()->route('home');
        } else {
            return redirect()->route('home');
        }
    }



    /* ###########################################
    // Function: resetPassword
    // Description: Display reset password success page after reseting password
    // Parameter: No parameter
    // ReturnType: view
    */ ###########################################
    public function resetPassword(Request $request)
    {
        if (!Auth::check()) {
            $api = new AuthAPIController();
            $data = $api->resetPassword($request);
            $data = $data->getData();
            if ($data->statusCode == 200) {
                //return redirect()->route('resetPasswordSuccess')->with('success', 'Your password has been changed successfully.');
                $notification = array(
                    'message' => getResponseMessage('ResetPassword'),
                    'alert-type' => 'success'
                );
                return redirect()->route('login')->with($notification);
            } else {
                if ($data->statusCode == 300) {
                    $content = $data->component;
                    //return redirect('/signup')->withErrors($content);
                    $notification = array(
                        'message' => $content,
                        'alert-type' => 'success'
                    );
                    return redirect('/signup')->with($notification);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }


    /* ###########################################
    // Function: resetPasswordSuccess
    // Description: Display reset password success page after reseting password
    // Parameter: No parameter
    // ReturnType: view
    */ ###########################################
    public function resetPasswordSuccess()
    {
        return view('frontend.auth.reset-password-success');
    }

    public function searchArea(Request $request)
    {
        $api = new LocationAPIController();
        $data = $api->searchArea($request);
        $data1 = $data->getData();
        if ($data1->statusCode == 200) {
            Session::put('searchableArea', $request->area);
        }
        return $data;
    }

    // public function search(Request $request)
    // {
    //     $area_name = Session::get('searchableArea');
    //     $request->merge(['area_name' => $area_name]);
    //     $api = new SearchAPIController();
    //     $data = $api->index($request);
    //     $data = $data->getData();
    //     if ($data->statusCode == 200) {
    //         $viewData = [];
    //         foreach ($data->component as $key => $value) {
    //             $indexName = $value->componentId;
    //             $indexName = $indexName . 'Data';
    //             $viewData[$indexName] = $value->$indexName;
    //         }
    //         // dd($viewData);
    //         return view('frontend.search', ['viewData' => $viewData, 'request' => $request->all()]);
    //     } else {
    //         abort(404);
    //     }
    //     return view('frontend.search');
    // }


    /* ###########################################
    // Function: showLoginWithOTP
    // Description: Show Login with OTP
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function showLoginWithOTP(Request $request)
    {
        if (!Auth::check()) {
            $api = new AuthAPIController();
            $data = $api->forgotPassword($request);
            $data = $data->getData();
            $content = $data->component;
            // dd($content->inputType);
            if ($data->statusCode == 200) {
                $input = $request->input;
                $inputType = (isset($content->inputType)) ? $content->inputType : '';
                return view('frontend.verify-otp', ['input' => $input, 'type' => 'login', 'inputType' => $inputType]);
            } else {
                if ($data->statusCode == 300) {
                    //return redirect()->route('login')->with('error', $data->message);
                    $notification = array(
                        'message' => $data->message,
                        'alert-type' => 'error'
                    );
                    return redirect()->route('login')->with($notification);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }


    /* ###########################################
    // Function: LoginWithOTP
    // Description: Show Login with OTP
    // Parameter: No Parameter
    // ReturnType: view
    */ ###########################################
    public function postLoginWithOTP(Request $request)
    {
        if (!Auth::check()) {
            if (isset($request->otp)) {
                $otp = implode('', $request->otp);
                $request->merge(['otp' => $otp]);
            }
            $api = new AuthAPIController();
            $data = $api->verifyOTP($request);
            $data = $data->getData();
            $content = $data->component;
            if ($data->statusCode == 200) {
                $input = $request->input;
                $user = User::where('phone', $input)->first();
                if ($user) {
                    Auth::login($user);
                } else {
                    $user = User::where('email', $input)->first();
                    Auth::login($user);
                }
                // return redirect()->route('home');
                return redirect()->route('home');
            } else {
                if ($data->statusCode == 300) {
                    return redirect('/signup')->withErrors($content);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function checkOTP(Request $request)
    {
        if (isset($request->otp)) {
            $otp = implode('', $request->otp);
            $request->merge(['otp' => $otp]);
        }
        $input = $request->all();
        $data = User::verifyOTP($input, 'no');
        return Response::json(['success' => ($data == 1) ? $data : 0]);
    }

    public function searchFront(Request $request, $search = "")
    {
        if ($search) {
            $api = new SearchAPIController();
            $request->merge(['search' => $search]);
            $data = $api->search($request);
            $data = $data->getData();
            $content = $data->component;
            $content = componentWithNameObject($content);
            return view('frontend.search', compact('content', 'search'));
        } else {
            return redirect()->route('home');
        }
    }

    public function fanSearchTagRemove(Request $request)
    {
        $api = new SearchAPIController();
        $data = $api->searchTagRemove($request);
        $data = $data->getData();
        return Response::json($data);
    }

    public function aboutUs($mobile = "", $darkmode = "")
    {
        $cms = CmsPages::where('slug', 'about-us')->first();
        return view('frontend.pages.about-us', compact('cms', 'mobile', 'darkmode'));
    }

    public function termsConditions($mobile = "", $darkmode = "")
    {
        $cms = CmsPages::where('slug', 'terms-conditions')->first();
        return view('frontend.pages.terms-conditions', compact('cms', 'mobile', 'darkmode'));
    }

    public function privacyPolicy($mobile = "", $darkmode = "")
    {
        $cms = CmsPages::where('slug', 'privacy-policy')->first();
        return view('frontend.pages.privacy-policy', compact('cms', 'mobile', 'darkmode'));
    }

    public function cookiePolicy($mobile = "", $darkmode = "")
    {
        //setcookie('cookiePolicy', 'fanclub', time() + (86400 * 30), "/");
        $cms = CmsPages::where('slug', 'cookie-policy')->first();
        return view('frontend.pages.cookie-policy', compact('cms', 'mobile', 'darkmode'));
    }

    public function switchArtistFan()
    {
        if (Session::has('artist_as_fan') && Session::get('artist_as_fan')) {
            Session::forget('artist_as_fan');
        } else {
            Session::put('artist_as_fan', 1);
        }
        return redirect()->route('home');
    }

    public function showChangePassword(Request $request)
    {
        if (Auth::check()) {
            return view('frontend.auth.change-password');
        } else {
            return redirect()->route('home');
        }
    }

    public function changePassword(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|min:8', //|different:password
                    'password_confirmation' => 'required',
                    'old_password' => 'required'
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            } else {
                $userId = Auth::user()->id;
                $api = new AuthAPIController();
                $data = $api->changePassword($request);
                $data = $data->getData();
                if ($data->statusCode == 200) {
                    $notification = array(
                        'message' => $data->message,
                        'alert-type' => 'success'
                    );
                    $user = User::where('id', $userId)->first();
                    if ($user) {
                        Auth::login($user);
                    }
                    return redirect()->back()->with($notification);
                } else {
                    $notification = array(
                        'message' => $data->component->error,
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                }
            }
        } else {
            return redirect()->route('home');
        }
    }
}
