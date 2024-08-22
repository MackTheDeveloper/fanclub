<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\PasswordBroker;
use Hash;
use DB;
use Carbon\Carbon;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'introducer_id', 'firstname', 'lastname', 'phone', 'email', 'password', 'user_type', 'ip_address', 'os_name', 'browser_name', 'browser_version', 'area', 'city', 'handle', 'design_preferences', 'current_home', 'future_stay', 'otp_at', 'otp', 'is_active', 'is_verify', 'is_subscribed', 'is_professional', 'alternate_phone_number', 'role_id', 'country', 'slug', 'step', 'state', 'gender', 'pnp_user_name', 'subscription_cancelled', 'current_subscription', 'subscription_start_date', 'subscription_end_date', 'subscription_expire_at', 'allow_message', 'current_subscription_id', 'has_yearly_subscription', 'prefix'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // /**
    //  * The accessors to append to the model's array form.
    //  *
    //  * @var array
    //  */
    // protected $appends = [
    //     'profile_photo_url',
    // ];

    public function designs()
    {
        return $this->hasMany(UserDesign::class);
    }

    public function Reviews()
    {
        return $this->hasMany(Reviews::class, 'user_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }

    public static function changePassword($request, $authId)
    {
        $user = User::find($authId);
        $password = Hash::make($request->password);
        if ($user && Hash::check($request->old_password, Auth::user()->password)) {
            $updateUser = User::where('id', $authId)->first();
            $updateUser->password = $password;
            $updateUser->save();
            // Auth::login($updateUser);
            return 1;
        } else {
            return 0;
        }
    }

    public static function getProfileData($id)
    {
        $user = User::find($id, ['firstname', 'lastname', 'phone', 'email', 'otp']);
        return ($user) ?: [];
    }

    public static function updateProfileData($data)
    {
        $return = '';
        $success = true;
        $phoneChange = false;
        $authId = Auth::user()->id;
        $allowed = ['firstname', 'lastname', 'phone', 'area', 'city', 'handle', 'design_preferences', 'current_home', 'future_stay'];
        $data = array_intersect_key($data, array_flip($allowed));
        $user = User::find($authId);
        if ($user) {
            // if (isset($data['handle']) && $user->is_professional=='0') {
            //     unset($data['handle']);
            // }
            try {
                foreach ($data as $key => $value) {
                    if ($key == 'phone') {
                        if ($user->$key != $value) {
                            $phoneChange = true;
                            $user->otp = self::generateOTP();
                            $user->otp_at = date('Y-m-d H:i:s');
                            $user->otp_expire_at = self::expireAtOTP();
                            $user->is_verify = '0';
                            self::sendOTP($user->id);
                        }
                    }
                    $user->$key = $value;
                }
                $user->save();
                $return = $user;
                $return->profile_photo_path = UserProfilePhoto::getProfilePhoto($authId);
                $return['phoneChange'] = $phoneChange;
            } catch (\Exception $e) {
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data' => $return, 'success' => $success];
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public static function generateOTP()
    {
        return rand(1000, 9999);
        // return 1234;
    }
    public static function expireAtOTP()
    {
        $mins = GlobalSettings::getSingleSettingVal('otp_validity_min');
        $mins = ($mins) ?: 15;
        $extra = "+" . $mins . " minute";
        $extra .= ($mins > 1) ? "s" : "";
        return date("Y-m-d h:i:s", strtotime($extra));
    }

    public static function sendOTP($id, $type = 'phone')
    {
        // if ($type=='phone') {
        //     try {
        //         $userData = self::getProfileData($id);
        //         $key = GlobalSettings::getSingleSettingVal('sms_api_key');

        //         // Account details
        //         $apiKey = urlencode($key);

        //         // To Get Sender Name
        //         $data = array('apikey' => $apiKey);
        //         $ch = curl_init('https://api.textlocal.in/get_sender_names/');
        //         curl_setopt($ch, CURLOPT_POST, true);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //         $response = curl_exec($ch);
        //         curl_close($ch);
        //         $result = json_decode($response);
        //         // pre($result);
        //         if (isset($result->default_sender_name)) {
        //             $senderName = $result->default_sender_name;
        //             // Message details
        //             $mins = GlobalSettings::getSingleSettingVal('otp_validity_min');
        //             $mins = ($mins)?:15;
        //             $extra = $mins." min";
        //             $extra.= ($mins>1)?"s":"";

        //             $numbers = array('91'.$userData->phone);
        //             $sender = urlencode($senderName);
        //             $message = rawurlencode($userData->otp.' is your One Time Password (OTP) for logging into the Decorato app. The OTP is valid for '.$extra.'. Do not share it with anyone.');
        //             // $message = rawurlencode('DECORATO : Your code is:'. $userData->otp);

        //             $numbers = implode(',', $numbers);

        //             // Prepare data for POST request
        //             $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        //             // Send the POST request with cURL
        //             $ch = curl_init('https://api.textlocal.in/send/');
        //             curl_setopt($ch, CURLOPT_POST, true);
        //             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //             $response = curl_exec($ch);
        //             curl_close($ch);
        //             // pre($response);
        //             // Process your response here
        //             // echo $response;
        //         }
        //     } catch (Exception $e) {

        //     }
        //     return 1;
        // }else{

        try {
            $mins = GlobalSettings::getSingleSettingVal('otp_validity_min');
            $mins = ($mins) ?: 15;
            $extra = $mins . " min";
            $extra .= ($mins > 1) ? "s" : "";
            $userData = self::getProfileData($id);
            $data = ['FIRST_NAME' => $userData->firstname, 'LAST_NAME' => $userData->lastname, 'OTP' => $userData->otp, 'OTP_VALID' => $extra];
            EmailTemplates::sendMail('otp-for-verification', $data, $userData->email);
        } catch (Exception $e) {
        }

        return 1;
        // }
    }

    public static function verifyOTP($data, $empty = '')
    {
        $return = 0;
        $user = User::where('phone', $data['input'])->where('otp', $data['otp'])->where('user_type', 'frontend')->first();
        if ($user) {
            $user->is_verify = 1;
            $user->otp = '';
            if (!$empty) {
                $user->save();
            }
            $return = 1;
        } else {
            $user = User::where('email', $data['input'])->where('otp', $data['otp'])->where('user_type', 'frontend')->first();
            if ($user) {
                $user->is_verify = 1;
                $user->otp = '';
                if (!$empty) {
                    $user->save();
                }
                $return = 1;
            } else {
                $return = 0;
            }
        }
        return $return;
    }

    public static function verifyOTPNew($data, $empty = '')
    {
        $return = 0;
        // $user = User::where('phone',$data['input'])->where('user_type','frontend')->first();
        // if (!$user) {
        $user = User::where('email', $data['input'])->where('user_type', 'frontend')->where('is_active', 1)->first();
        // }
        if ($user) {
            if ($user->otp == $data['otp']) {
                $currentTime = strtotime(date('Y-m-d h:i:s'));
                $expireTime = strtotime($user->otp_expire_at);
                if ($expireTime >= $currentTime) {
                    // $user->is_verify = 1;
                    $user->otp = '';
                    if (!$empty) {
                        $user->save();
                    }
                    $return = 1;
                } else {
                    $return = 3;
                }
            } else {
                $return = 2;
            }
        }
        return $return;
    }

    // public static function SocialLoginUser($data,$provider){
    //     $return = 0;
    //     $findSocial = UserSocialLogin::leftjoin('users','users.id','user_social_login.user_id')->where('provider',$provider)
    //         ->where('is_deleted',0)
    //         ->whereNull('deleted_at')
    //         ->where('social_id', $data['socialId'])->first();
    //     // pre($findSocial);
    //     if ($findSocial) {
    //         $return = $findSocial->user_id;
    //     }else{
    //         if ($data['email']) {
    //             $existUser = User::where('email',$data['email'])->first();
    //             if ($existUser) {
    //                 $newLogin = new UserSocialLogin;
    //                 $newLogin->user_id = $existUser->id;
    //                 $newLogin->provider = $provider;
    //                 $newLogin->social_id = $data['socialId'];
    //                 $newLogin->save();

    //                 $return = $existUser->id;
    //             }else{
    //                 // $name =
    //                 $newUser = new User;
    //                 $newUser->email = $data['email'];
    //                 $newUser->firstname = (isset($data['firstname']))?$data['firstname']:'';
    //                 $newUser->lastname = (isset($data['lastname']))?$data['lastname']:'';
    //                 $newUser->user_type = 'frontend';
    //                 $newUser->is_verify = 1;
    //                 $newUser->is_active = 1;
    //                 // $newUser->save();
    //                 if ($newUser->save()) {
    //                     $newLogin = new UserSocialLogin;
    //                     $newLogin->user_id = $newUser->id;
    //                     $newLogin->provider = $provider;
    //                     $newLogin->social_id = $data['socialId'];
    //                     $newLogin->save();
    //                     // return user ID
    //                     $return = $newUser->id;
    //                 }


    //             }

    //         }else{
    //             $newUser = new User;
    //             // $newUser->email = (isset($data->email))?$data->email:'';
    //             $newUser->firstname = (isset($data['firstname']))?$data['firstname']:'';
    //             $newUser->lastname = (isset($data['lastname']))?$data['lastname']:'';
    //             $newUser->user_type = 'frontend';
    //             $newUser->is_verify = 1;
    //             $newUser->is_active = 1;
    //             // $newUser->save();
    //             if ($newUser->save()) {
    //                 $newLogin = new UserSocialLogin;
    //                 $newLogin->user_id = $newUser->id;
    //                 $newLogin->provider = $provider;
    //                 $newLogin->social_id = $data['socialId'];
    //                 $newLogin->save();

    //                 $return = $newUser->id;
    //             }
    //         }
    //     }
    //     return $return;
    // }

    public static function NameToFirstlast($name = '')
    {
        $return = ['firstname' => '', 'lastname' => ''];
        $name = explode(' ', $name);
        if (count($name) > 1) {
            $return['firstname'] = $name[0];
            $return['lastname'] = $name[1];
        } else {
            $return['firstname'] = $name[0];
            $return['lastname'] = '';
        }
        return $return;
    }


    public static function getLoggedInId($field="")
    {
        $userId = (Auth::check()) ? Auth::user()->id : 0; //check in web
        if ($field && $userId) {
            $userId = Auth::user()->$field;
        }
        if (!$userId) {
            //check in API
            $userId = (auth('api')->user()) ? auth('api')->user()->id : 0;

            if ($field && $userId) {
                $userId = auth('api')->user()->$field;
            }
        }
        return $userId;
    }

    public static function checkExist($phone)
    {
        $data = self::where('phone', $phone)->get();
        return count($data);
    }

    public static function sendPasswordResetMail($email)
    {
        $user = self::where('email', $email)->first();
        if ($user) {
            $password_broker = app(PasswordBroker::class);
            $token = $password_broker->createToken($user);
            DB::table('password_resets')->insert(['email' => $user->email, 'token' => $token, 'created_at' => new Carbon]);
            // url('password/reset', $this->token)
            return $token;
        }
    }

    public static function getNextUniqueId($roleId)
    {
        $financialYear = date('Y');
        $prefix = ($roleId == 2) ? 'A' : 'F';
        $prefix .= date('y');
        $number = "1";
        $user = self::where('role_id', $roleId)->where('user_type', "frontend")->whereNotNull("unique_id")->orderBy("unique_id", "DESC")->whereYear('created_at', $financialYear)->first();
        if ($user) {
            $uniqueId = $user->unique_id;
            if ($uniqueId) {
                $number = substr($uniqueId, 3);
                $number = intval($number);
                $number += 1;
            }
        }
        $return = $prefix . sprintf("%04d", $number);
        return $return;
    }

    public static function getNameByIdForChat($id)
    {
        $fullname = 'Anonymous';
        $return = self::where('id', $id)->first();
        if ($return) {
            $fullname = $return->firstname . " " . $return->lastname;
        }
        return $fullname;
    }

    public static function checkUserExist($id)
    {
        $return = self::where('id', $id)->first();
        if ($return) {
            return true;
        }
        return false;
    }

    public static function getAttrById($id,$field)
    {
        $return = $id;
        $data = self::where('id', $id)->first();
        if ($data) {
            $return = $data->$field;
        }
        return $return;
    }

    public static function getEmailById($id)
    {
        $email = '';
        $return = self::where('id', $id)->first();
        if ($return) {
            $email = $return->email;
        }
        return $email;
    }

    public static function getMySubscriptonData($id)
    {
        $return = array();
        $dataUser = self::selectRaw('users.id,users.subscription_start_date,users.subscription_end_date,subscription__plans.subscription_name,subscription__plans.price,users.current_subscription,users.has_yearly_subscription,subscription__plans.type')->where('users.id', $id)->join('subscription__plans', 'subscription__plans.id', '=', 'users.current_subscription')->first();
        if ($dataUser) {
            $return = [
                "userId" => $dataUser['id'],
                "subscriptionStartDate" => $dataUser['subscription_start_date'],
                "subscriptionEndDate" => getFormatedDate($dataUser['subscription_end_date']),
                "subscriptionName" => $dataUser['subscription_name'],
                "subscriptionPrice" => $dataUser['price'],
                // "currentSubscription" => $dataUser['current_subscription'],
                "currentSubscriptionType" => $dataUser['type'],
                "currentSubscriptionText" => ($dataUser['type']=="1")? "Monthly":"Yearly",
            ];
        }
        return $return;
    }

    public static function hasYearlySubscription($id)
    {
        $return = array();
        $dataYearly = Subscription::selectRaw('subscriptions.amount,subscriptions.start_date,subscriptions.end_date,subscription__plans.subscription_name')->join('subscription__plans', 'subscription__plans.id', '=', 'subscriptions.subscription_plan')->where('is_pending', 1)->where('customer_id', $id)->first();
        if ($dataYearly) {
            $return = [
                "yearlySubscriptionStartDate" => getFormatedDate($dataYearly['start_date']),
                "yearlySubscriptionEndDate" => getFormatedDate($dataYearly['end_date']),
                "amount" => $dataYearly['amount'],
                "subscriptionName" => $dataYearly['subscription_name'],
            ];
        }
        return $return;
    }
    public static function getMasterPassword(){
        $return = "f@nclub#2022Mast3r";
        return $return;
    }
}
