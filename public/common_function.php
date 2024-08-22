<?php

use App\Models\Chats;
use App\Models\Notifications;

function pre($array, $no = '')
{
    echo '<pre>';
    print_r($array);
    if (!$no)
        exit;
}

// MASTER ARRAY OF LANGUAGE

//$langArray = ['en'=>'English','ja'=>'Japanese'];
//pre($langArray);

function getFormatedDate($dateVal, $format = '')
{
    $return = $dateVal;
    if ($dateVal) {
        if ($format) {
            $return = date($format, strtotime($dateVal));
        } else {
            if (strlen($dateVal) > 12) {
                $return = date('jS M Y h:i:s A', strtotime($dateVal));
            } else {
                $return = date('jS M Y', strtotime($dateVal));
            }
        }
    }else{
        $return = "N/A";
    }
    return $return;
}

function getFormatedDateForWeb($dateVal)
{

    $date = Carbon\Carbon::parse($dateVal);
    $now = Carbon\Carbon::now();

    $diff = $date->diff($now);
    // pre($diff);
    if ($diff->y) {
        $year = $diff->y;
        return $year . ' year' . ($year > 1 ? 's' : '') . ' ago';
    } else if ($diff->m) {
        $month = $diff->m;
        return $month . ' month' . ($month > 1 ? 's' : '') . ' ago';
    } else if ($diff->d) {
        $day = $diff->d;
        return $day . ' day' . ($day > 1 ? 's' : '') . ' ago';
    } else if ($diff->h) {
        $hour = $diff->h;
        return $hour . ' hour' . ($hour > 1 ? 's' : '') . ' ago';
    } else if ($diff->i) {
        $minute = $diff->i;
        return $minute . ' minute' . ($minute > 1 ? 's' : '') . ' ago';
    } else {
        return 'just now';
    }
}


function getDayforChatState($dateVal)
{
    $return = 'Today';
    $date = Carbon\Carbon::parse($dateVal);
    $now = Carbon\Carbon::now();

    $diff = $date->diff($now);
    if ($diff->d) {
        $return = ($diff->d == 1) ? 'Yesterday' : date('M d, Y', strtotime($dateVal));
    }
    return $return;
}

function getDefaultFormat($type = 'datetime')
{
    $datetime = '%j%S %M %Y %h:%i:%s %A';
    $datetime = '%j%S %M %Y';
    return ($type == 'date') ? $date : $datetime;
}
function stringSlugify($string, $delimiter = "-")
{
    $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $string))))), $delimiter));
    return $slug;
}

function getSlug($string, $loop = "", $table, $field,$id=0)
{
    $slug = stringSlugify($string . $loop);
    $exist = DB::table($table)->where($field, $slug);
    if ($id) {
        $exist->where('id',"!=", $id);
    }
    $exist = $exist->first();
    // pre($exist,1);
    if ($exist) {
        $loop = ($loop) ? $loop + 1 : 2;
        return getSlug($string, $loop, $table, $field);
    } else {
        return $slug;
    }
}

function componentWithNameObject($content)
{
    $return = [];
    foreach ($content as $key => $value) {
        $key = checkDuplicateKey($return, $value->componentId);
        $return[$key] = $value;
    }
    return $return;
}

function checkDuplicateKey($array, $key, $i = 0)
{
    $return = $key;
    if ($i) {
        $return = $return . $i;
    }
    if (isset($array[$return])) {
        $i++;
        $return = checkDuplicateKey($array, $key, $i);
    }
    return $return;
}

function getResponseMessage($msgKey, $data = [])
{
    $msg = config('message.frontendMessages.' . $msgKey);
    if ($data) {
        if (gettype($data)== 'array') {
            $msg = str_replace(['{PARAM}'], $data, $msg);
        }else{
            $msg = str_replace('{PARAM}', $data, $msg);
        }
    } else {
        $msg = str_replace('{PARAM}', '', $msg);
    }
    return $msg;
}

function getCountChatUnread()
{
    $chatsUnread = Chats::listChatPersonsUnread();
    return $chatsUnread;
}


function getNotifications()
{
    $data = Notifications::notificationByUser(10);
    return $data;
}

function getCountOfTinifyOptimization()
{
    $getCount = DB::table('tinify_image_count')
        ->select('count')
        ->first();
    return $getCount->count;
}

function getBrowser()
{
    $return = "";
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE) {
        // $return = 'Internet explorer';
        $return = 'IE';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) {
        // $return = 'Internet explorer';
        $return = 'IE';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) {
        // $return = 'Mozilla Firefox';
        $return = 'Firefox';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) {
        // $return = 'Google Chrome';
        $return = 'Chrome';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE) {
        // $return = "Opera Mini";
        $return = "Opera";
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) {
        // $return = "Opera";
        $return = "Opera";
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) {
        // $return = "Safari";
        $return = "Safari";
    } else {
        // $return = 'Something else';
        $return = 'UK';
    }

    return $return;
}

function getSupportedMime($browser)
{
    $return = "mp4";
    $webmSupport = ['Firefox'];
    // $webmSupport = ['Firefox', 'Safari'];
    if (in_array($browser, $webmSupport)) {
        $return = "webm";
    }
    return $return;
}

function getAuthProps()
{
    $authCheck = Auth::check();
    $authRole = $authCheck ? Auth::user()->role_id : 0;
    if (Session::has('artist_as_fan') && Session::get('artist_as_fan')) {
        $authRole = '3';
    }
    return $authRole;
}

function getcookie($val)
{
    return isset($_COOKIE[$val]) ? $_COOKIE[$val] : '';
}

function alphaNumericOnly($string){
    $string = str_replace("$", "", $string);
    // $string = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $string);
    // $string = preg_replace("/[^A-Za-z0-9 ]/", "", $string);
    return $string;
}