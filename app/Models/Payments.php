<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Payments extends Model
{
    // use SoftDeletes;

    protected $table = 'payments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fan_id', 'subscription_id', 'transaction_id', 'billing_address_1', 'billing_address_2', 'country', 'state', 'city', 'zipcode', 'status'
    ];

    public static function initiateSubscription($selectedSubscriptionId, $cardAndOtherDetails, $user)
    {
        // Get Subscription Plan Data
        // pre("--------------------------------------", 1);
        $subscriptionPlanData = SubscriptionPlan::getDetails($selectedSubscriptionId);
        $country = Country::where('name', $cardAndOtherDetails['country'])->first();
        //pre($subscriptionPlanData);

        $publisher_name = config('app.pnpSubscription.publisherName');
        $publisher_password = config('app.pnpSubscription.publisherPassword');
        $card_number = $cardAndOtherDetails['card_number'];
        $card_cvv = $cardAndOtherDetails['card_cvv'];
        $card_exp = $cardAndOtherDetails['card_exp'];
        $card_amount = $subscriptionPlanData->price;
        $card_name = $cardAndOtherDetails['card_name'];
        $email = $user->email;
        $ipaddress = $_SERVER['REMOTE_ADDR'];

        $card_address1 = $cardAndOtherDetails['billing_address_1'];
        $card_address2 = $cardAndOtherDetails['billing_address_2'];
        $card_zip = $cardAndOtherDetails['zipcode'];
        $card_city = $cardAndOtherDetails['city'];
        $card_state = "";
        //$card_country = $country->sortname; // 2 digits 
        $card_country = 'US';

        $admin_email = config('app.pnpSubscription.adminEmail');

        $pnp_post_values = "";
        if ($pnp_post_values == "") {
            $pnp_post_values .= "publisher-name=" . $publisher_name . "&";
            $pnp_post_values .= "publisher-password=" . $publisher_password . "&";
            $pnp_post_values .= "card-number=" . $card_number . "&";
            $pnp_post_values .= "card-exp=" . $card_exp . "&";
            $pnp_post_values .= "card-name=" . $card_name . "&";
            $pnp_post_values .= "email=" . $email . "&";
            $pnp_post_values .= "ipaddress=" . $ipaddress . "&";
            // billing address info
            $pnp_post_values .= "card-address1=" . $card_address1 . "&";
            $pnp_post_values .= "card-address2=" . $card_address2 . "&";
            $pnp_post_values .= "card-zip=" . $card_zip . "&";
            $pnp_post_values .= "card-city=" . $card_city . "&";
            $pnp_post_values .= "card-state=" . $card_state . "&";
            $pnp_post_values .= "card-country=" . $card_country . "&";
            $pnp_post_values .= "notify-email=" . $admin_email . "&";
        }

        $pnp_transaction_array = self::authCard($pnp_post_values, $card_cvv, $card_amount);
        // pre($pnp_transaction_array, 1);
        // If success on auth card
        if ($pnp_transaction_array['FinalStatus'] == "success") {

            // get username
            $username = self::checkGetUsername();
            //pre($username);

            // Get Order ID
            $orderId = $pnp_transaction_array['orderID'];

            // Now create recurring profile and set necessary data
            $todayDate = date('Ymd');
            $enddate = $subscriptionPlanData->duration == '1 Month' ? date('Ymd', strtotime("+1 months", strtotime($todayDate))) : date('Ymd', strtotime("+12 months", strtotime($todayDate))); //"20211219"; // YYYYMMDD format...This will be next billing date + 2 days based on Monthly or Yearly subcription
            $startdate = $todayDate; //"20211119"; // YYYYMMDD format...todays date
            $mode = "add_member";
            $status = "active";
            $billcycle = $subscriptionPlanData->duration == '1 Month' ?  "1" : "12"; // number of months as for billing cycle or 12 for yearly...
            $recFee = $subscriptionPlanData->price; // billing amount

            //date('Y-m-d',strtotime($startdate));
            //date('Y-m-d',strtotime($enddate));

            // create profile
            $pnp_post_values .= "orderID=" . $orderId . "&";
            $pnp_post_values .= "enddate=" . $enddate . "&";
            $pnp_post_values .= "startdate=" . $startdate . "&";
            $pnp_post_values .= "mode=" . $mode . "&";
            $pnp_post_values .= "recfee=" . $recFee . "&";
            $pnp_post_values .= "status=" . $status . "&";
            $pnp_post_values .= "username=" . $username . "&";
            $pnp_post_values .= "billcycle=" . $billcycle . "&";

            // Create Subscription Profile
            $arrResponse = self::createProfile($pnp_post_values);
            // pre($arrResponse, 1);

            // If success on auth card
            $returnArray = array();
            if ($arrResponse['FinalStatus'] == "success") {
                /* $pnp_post_values_member = "";
                $pnp_post_values_member .= "publisher-name=" . $publisher_name . "&";
                $pnp_post_values_member .= "publisher-password=" . $publisher_password . "&";
                $pnp_post_values_member .= "mode=bill_member&";
                $pnp_post_values_member .= "username=" . $username . "&";
                $pnp_post_values_member .= "card-amount=" . $subscriptionPlanData->price . "&";
                $pnp_post_values_member .= "sndemailflg=1&";

                $arrResponseMember = self::billMember($pnp_post_values_member);
                $orderId = "";
                if ($arrResponseMember['FinalStatus'] == "success") {
                    $orderId = $arrResponseMember['orderID'];
                } */
                // Store user_name and order_id on fan's record level

                //-- START Update Subscription Start and End data on FAN level 
                $subscriptionStartDate = date('Y-m-d', strtotime($startdate));
                $subscriptionEndDate = date('Y-m-d', strtotime($enddate));

                User::where('id', $user->id)->update(['subscription_start_date' => $subscriptionStartDate, 'subscription_end_date' => $subscriptionEndDate, 'current_subscription' => $subscriptionPlanData->id, 'pnp_user_name' => $username]);

                $subscriptionCreate = Subscription::create([
                    'customer_id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'subscription_plan' => $subscriptionPlanData->id,
                    'amount' => $subscriptionPlanData->price,
                    'start_date' => $subscriptionStartDate,
                    'end_date' => $subscriptionEndDate,
                    'status' => 1,
                ]);

                $transactionCreate = Transactions::create([
                    'subscription_id' => $subscriptionCreate->id,
                    'customer_id' => $user->id,
                    'name' => $user->firstname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'amount' => $subscriptionPlanData->price,
                    'payment_id' => $orderId ?: $orderId,
                    'plan' => $subscriptionPlanData->type == 1 ? 'monthly' : 'yearly',
                    /* 'card_name' => $arrResponse['card-name'],
                    'card_number' => $arrResponse['receiptcc'],
                    'card_expiry' => $arrResponse['card-exp'], */
                    'ip_address' => $arrResponse['ipaddress'],
                    'status' => 1,
                ]);
                //-- END Update Subscription Start and End data on FAN level 

                $paymentMessage = config('message.paymentStatus.' . $arrResponse['FinalStatus']) ? config('message.paymentStatus.' . $arrResponse['FinalStatus']) : $arrResponse['auth-msg'];
                $returnArray = ['status' => 'success', 'message' => $paymentMessage, 'subscriptionId' => $subscriptionCreate->id];
            } else {
                $paymentMessage = config('message.paymentStatus.' . $arrResponse['FinalStatus']) ? config('message.paymentStatus.' . $arrResponse['FinalStatus']) : $arrResponse['MErrMsg'];
                //pre($arrResponse);
                $returnArray = ['status' => 'failed', 'message' => $paymentMessage];
            }
        } else {
            $paymentMessage = config('message.paymentStatus.' . $pnp_transaction_array['FinalStatus']) ? config('message.paymentStatus.' . $pnp_transaction_array['FinalStatus']) : $pnp_transaction_array['MErrMsg'];
            //pre($pnp_transaction_array);
            // get error message and show to customer
            $returnArray = ['status' => 'failed', 'message' => $paymentMessage];
        }
        return $returnArray;
    }

    public static function upgradeSubscription($user)
    {
        $yearlySubscriptionData = SubscriptionPlan::where('type', 2)->first();
        $subscriptionPlanData = SubscriptionPlan::getDetails($yearlySubscriptionData->id);
        $publisher_name = config('app.pnpSubscription.publisherName');
        $publisher_password = config('app.pnpSubscription.publisherPassword');
        //$username = "PNP000001_12-12-2020_122452"; // this is username stored in our db for each fan profile
        $username = $user->pnp_user_name;
        $yearly_amount = $subscriptionPlanData->price; // yearly amount

        $pnp_post_values = "";

        // First bill customer with same card with yearly charge amount
        if ($pnp_post_values == "") {
            $pnp_post_values .= "publisher-name=" . $publisher_name . "&";
            $pnp_post_values .= "publisher-password=" . $publisher_password . "&";
            $pnp_post_values .= "username=" . $username . "&";
        }

        if (1) {
            // Store order id / txn id with response in payment history for history purpose.
            // After sucess charging yearly amount lets change profile of user with yearly end date
            //$end_date = "20221124"; // end date with next year date + 2 days buffer
            $recfee = $yearly_amount;
            $billcycle = "12"; // yearly bill cycle

            $pnp_post_values .= "mode=update_member&";
            //$pnp_post_values .= "enddate=" . $end_date . "&";  
            $pnp_post_values .= "recfee=" . $recfee . "&";
            $pnp_post_values .= "billcycle=" . $billcycle . "&";

            // response
            $arrResponse = self::updateMemberProfile($pnp_post_values);
            // pre($arrResponse, 1);

            // If success on auth card
            $returnArray = array();
            if ($arrResponse['FinalStatus'] == "success") {
                // store next due date in database as per end date and show success message.  

                //-- START Update Subscription Start and End data on FAN level 
                //$order_id = $arrResponse['orderID'];

                /* $todayDate = date('Ymd');
                $startdate = $todayDate;
                $enddate = date('Ymd', strtotime("+12 months", strtotime($todayDate)));
                $subscriptionStartDate = date('Y-m-d', strtotime($startdate));
                $subscriptionEndDate = date('Y-m-d', strtotime($enddate)); */

                $subscriptionStartEndDates = self::getStartDateForYearlySubscription($user);
                $subscriptionStartDate = $subscriptionStartEndDates['startDate'];
                $subscriptionEndDate = $subscriptionStartEndDates['endDate'];

                //User::where('id', $user->id)->update(['subscription_start_date' => $subscriptionStartDate, 'subscription_end_date' => $subscriptionEndDate, 'current_subscription' => $subscriptionPlanData->id, 'pnp_user_name' => $username]);
                User::where('id', $user->id)->update(['has_yearly_subscription' => 1]);

                $subscriptionCreate = Subscription::create([
                    'customer_id' => $user->id,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'subscription_plan' => $subscriptionPlanData->id,
                    'amount' => $subscriptionPlanData->price,
                    'start_date' => $subscriptionStartDate,
                    'end_date' => $subscriptionEndDate,
                    'is_pending' => 1,
                    'status' => 1,
                ]);

                /* $transactionCreate = Transactions::create([
                    'subscription_id' => $subscriptionCreate->id,
                    'customer_id' => $user->id,
                    'name' => $user->firstname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'amount' => $subscriptionPlanData->price,
                    'payment_id' => $order_id,
                    'card_name' => $arrResponse['card-name'],
                    'card_number' => $arrResponse['receiptcc'],
                    'card_expiry' => $arrResponse['card-exp'],
                    'ip_address' => $arrResponse['ipaddress'],
                    'status' => 1,
                ]); */
                //-- END Update Subscription Start and End data on FAN level 

                $returnArray = ['status' => 'success', 'message' => config('message.frontendMessages.SubscriptionUpgraded')];
            } else {
                //pre($arrResponse);
                // show error message to customer
                $returnArray = ['status' => 'failed', 'message' => config('message.frontendMessages.SubscriptionUpgradFailed') . ' : ' . $arrResponse['auth-msg']];
            }
        }
        return $returnArray;
    }

    public static function cancelSubscription($user)
    {
        $publisher_name = config('app.pnpSubscription.publisherName');
        $publisher_password = config('app.pnpSubscription.publisherPassword');
        //$username = "PNP000001_12-12-2020_122452"; // this is username stored in our db for each fan profile
        $username = $user->pnp_user_name;

        $pnp_post_values = "";

        if ($pnp_post_values == "") {
            $pnp_post_values .= "publisher-name=" . $publisher_name . "&";
            $pnp_post_values .= "publisher-password=" . $publisher_password . "&";
            // $pnp_post_values .= "mode=cancel_member&";
            // $pnp_post_values .= "username=" . $username . "&";
            $pnp_post_values .= "mode=update_member&";
            $pnp_post_values .= "username=" . $username . "&";
            $pnp_post_values .= "status=cancelled&";
        }

        $pnp_transaction_array = self::cancelSubscriptionFan($pnp_post_values);
        // pre($pnp_transaction_array, 1);

        // If success on auth card
        if ($pnp_transaction_array['FinalStatus'] == "success") {
            $subscriptionEndDate = $user->subscription_end_date;
            //User::where('id', $user->id)->update(['subscription_cancelled' => 1, 'subscription_start_date' => null, 'subscription_end_date' => null, 'current_subscription' => 0]);
            User::where('id', $user->id)->update(['subscription_cancelled' => 1, 'subscription_expire_at' => $subscriptionEndDate]);
            // Set next payment date as per start date of subscription and this will be used for login validation to allow user to use our system till this date.
            $returnArray = ['status' => 'success', 'message' => getResponseMessage('SubscriptionCancelled', getFormatedDate($subscriptionEndDate))];
        } else {
            //pre($pnp_transaction_array);
            //$returnArray = ['status' => 'failed', 'message' => $pnp_transaction_array['auth-msg']];
            $returnArray = ['status' => 'failed', 'message' => config('message.frontendMessages.SubscriptionCancelFailed') . ' : ' . $pnp_transaction_array['auth-msg']];
        }
        return $returnArray;
    }

    public static function authCard($pnp_post_values, $card_cvv, $card_amount)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // Check for authorization
        $pnp_post_values .= "mode=auth&";
        $pnp_post_values .= "authtype=authonly&";
        $pnp_post_values .= "card-amount=" . $card_amount . "&";
        $pnp_post_values .= "card-cvv=" . $card_cvv . "&";

        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        return $pnp_transaction_array;
    }

    public static function checkGetUsername()
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // Create Unique Username for Plug'n Play Profile
        $user_name = self::getNewUserName();

        $publisher_name = config('app.pnpSubscription.publisherName');
        $publisher_password = config('app.pnpSubscription.publisherPassword');

        $pnp_post_values = "publisher-name=" . $publisher_name . "&";
        $pnp_post_values .= "publisher-password=" . $publisher_password . "&";
        $pnp_post_values .= "mode=passwrdtest&";
        $pnp_post_values .= "username=" . $user_name . "&";

        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        if ($pnp_transaction_array['FinalStatus'] == "success") {
            return $user_name;
        } else {
            self::checkGetUsername();
        }
    }

    public static function getNewUserName()
    {
        // Create Unique Username for Plug'n Play Profile
        $user_name = 'PNP000001_' . date('d-m-Y_His');
        return $user_name;
    }

    // Function for creating profile
    public static function createProfile($pnp_post_values)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        return $pnp_transaction_array;
    }

    public static function billMember($pnp_post_values)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        return $pnp_transaction_array;
    }

    public static function updateMemberProfile($pnp_post_values)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);
        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        return $pnp_transaction_array;
    }

    public static function cancelSubscriptionFan($pnp_post_values)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');
        // init curl handle
        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);
        #curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  // Upon problem, uncomment for additional Windows 2003 compatibility

        // perform ssl post
        $pnp_result_page = curl_exec($pnp_ch);

        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }

        return $pnp_transaction_array;
    }

    public static function getStartDateForYearlySubscription($user)
    {
        $subscriptionEndDate = $user->subscription_end_date;
        $today = date('Y-m-d');

        $subscriptionEndDate = strtotime($subscriptionEndDate);
        $today = strtotime($today);

        $dates = array();
        if ($subscriptionEndDate > $today) {
            $dates['startDate'] =  date('Y-m-d', strtotime("+1 day", $subscriptionEndDate));
            $dates['endDate'] = date('Y-m-d', strtotime("+12 months", strtotime($dates['startDate'])));
        }

        return $dates;
    }

    public static function cronForTransactionHistory()
    {
        $publisher_name = config('app.pnpSubscription.publisherName');
        $publisher_password = config('app.pnpSubscription.publisherPassword');

        $fromDate = date('Y-m-d', strtotime("-2 day", strtotime(date('Y-m-d'))));
        $toDate = date('Y-m-d', strtotime("+2 day", strtotime(date('Y-m-d'))));

        $dataUser = User::selectRaw('*')
            ->whereNotNull('pnp_user_name')
            ->where('subscription_cancelled', '!=', 1)
            ->whereBetween(DB::raw("date_format(subscription_end_date,'%Y-%m-%d')"), [$fromDate, $toDate])
            ->get();

        foreach ($dataUser as $key => $value) {
            $username = $value['pnp_user_name']; // this is an array of all username stored in our db for each fan profile
            $startdate = $fromDate;
            $enddate = $toDate;

            $pnp_post_values = "";

            if ($pnp_post_values == "") {
                $pnp_post_values .= "publisher-name=" . $publisher_name . "&";
                $pnp_post_values .= "publisher-password=" . $publisher_password . "&";
                $pnp_post_values .= "mode=query_billing&";
                $pnp_post_values .= "username=" . $username . "&";
                $pnp_post_values .= "startdate=" . $startdate . "&";
                $pnp_post_values .= "enddate=" . $enddate . "&";
            }

            $pnp_transaction_array = self::fetch_user_billing($pnp_post_values);
            if ($pnp_transaction_array['FinalStatus'] == "success") {
                $checkTransaction = Transactions::where('payment_id', $pnp_transaction_array['orderID'])->first();
                if (empty($checkTransaction->toArray())) {
                    $getLastSubscription = Subscription::where('id', $value->current_subscription_id)->first();
                    $startDateOfSubscrtion = $getLastSubscription->end_date;
                    $endDateOfSubscrtion = date('Y-m-d', strtotime("+1 year", strtotime($startDateOfSubscrtion)));
                    $subscriptionCreate = Subscription::create([
                        'customer_id' => $value->id,
                        'email' => $value->email,
                        'phone' => $value->phone,
                        'subscription_plan' => $value->current_subscription,
                        'amount' => $pnp_transaction_array['card-amount'],
                        'start_date' => $startDateOfSubscrtion,
                        'end_date' => $endDateOfSubscrtion,
                        'status' => 1,
                    ]);

                    $transactionCreate = Transactions::create([
                        'subscription_id' => $subscriptionCreate->id,
                        'customer_id' => $value->id,
                        'name' => $value->firstname,
                        'email' => $value->email,
                        'phone' => $value->phone,
                        'amount' => $pnp_transaction_array['card-amount'],
                        'payment_id' => $pnp_transaction_array['orderID'],
                        'plan' => 'yearly',
                        'status' => 1,
                    ]);

                    User::where('id', $value->id)->update(['subscription_start_date' => $startDateOfSubscrtion, 'subscription_end_date' => $endDateOfSubscrtion, 'current_subscription_id' => $subscriptionCreate->id]);
                }
            }
        }
    }

    public static function fetch_user_billing($pnp_post_values)
    {
        $pnp_post_url = config('app.pnpSubscription.postUrl');

        $pnp_ch = curl_init($pnp_post_url);
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $pnp_post_values);

        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);

        // decode the result page and put it into transaction_array
        $pnp_transaction_array = array();
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        foreach ($pnp_temp_array as $entry) {
            list($name, $value) = explode('=', $entry);
            $pnp_transaction_array[$name] = $value;
        }
        return $pnp_transaction_array;
    }

    public static function cronForUpdateSubscriptionToYearly()
    {
        $dataUser = User::selectRaw('id,subscription_cancelled,subscription_expire_at')->whereNotNull('pnp_user_name')->get()->toArray();
        foreach ($dataUser as $key => $value) {
            if ($value['subscription_cancelled'] == 1 && $value['subscription_expire_at'] == date("Y-m-d")) {
                $user = User::find($value['id']);
                $user->step = 'second';
                $user->save();
            } else {
                $checkSubscription = Subscription::where('customer_id', $value['id'])->where('is_pending', 1)->where('subscription_plan', 2)->whereDate('start_date', Carbon::today())->first();
                if (!empty($checkSubscription)) {
                    $checkSubscription->is_pending = 0;
                    $checkSubscription->save();

                    $user = User::find($value['id']);
                    $user->current_subscription_id = $checkSubscription->id;
                    $user->current_subscription = 2;
                    $user->subscription_start_date = $checkSubscription->start_date;
                    $user->subscription_end_date = $checkSubscription->end_date;
                    $user->has_yearly_subscription = null;
                    $user->save();
                }
            }
        }
    }
}
