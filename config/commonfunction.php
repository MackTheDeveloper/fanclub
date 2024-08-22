<?php

// use DB;

// function globalDateFormat(dateVal,default='Y-m-d H:i:s'){
//     return "string";
// }
// function pre($array, $no = '')
// {
//     echo '<pre>';
//     print_r($array);
//     if (!$no)
//         exit;
// }

// // MASTER ARRAY OF LANGUAGE

// //$langArray = ['en'=>'English','ja'=>'Japanese'];
// //pre($langArray);

// function getFormatedDate($dateVal, $format = '')
// {
//     $return = $dateVal;
//     if ($format) {
//         $return = date($format, strtotime($dateVal));
//     } else {
//         if (strlen($dateVal) > 12) {
//             $return = date('jS M Y h:i:s A', strtotime($dateVal));
//         } else {
//             $return = date('jS M Y', strtotime($dateVal));
//         }
//     }
//     return $return;
// }

// function getFormatedDateForWeb($dateVal)
// {

//     $date = Carbon\Carbon::parse($dateVal);
//     $now = Carbon\Carbon::now();

//     $diff = $date->diff($now);
//     if ($diff->y) {
//         $year = $diff->y;
//         return $year . ' year' . ($year > 1 ? 's' : '') . ' ago';
//     } else if ($diff->m) {
//         $month = $diff->m;
//         return $month . ' month' . ($month > 1 ? 's' : '') . ' ago';
//     } else if ($diff->d) {
//         $day = $diff->d;
//         return $day . ' day' . ($day > 1 ? 's' : '') . ' ago';
//     } else if ($diff->h) {
//         $hour = $diff->h;
//         return $hour . ' hour' . ($hour > 1 ? 's' : '') . ' ago';
//     }
// }

// function getDefaultFormat($type = 'datetime')
// {
//     $datetime = '%j%S %M %Y %h:%i:%s %A';
//     $datetime = '%j%S %M %Y';
//     return ($type == 'date') ? $date : $datetime;
// }
// function stringSlugify($string,$delimiter = "-"){
// 	$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
// 	return $slug;
// }

// function getSlug($string,$loop="",$table,$field){
// 	$slug = stringSlugify($string.$loop);
// 	$exist = DB::table($table)->where($field,$slug)->first();
// 	if ($exist) {
// 		$loop = ($loop)?$loop+1:2;
// 		return getSlug($string,$loop,$table,$field);
// 	}else{
// 		return $slug;
// 	}
// }
