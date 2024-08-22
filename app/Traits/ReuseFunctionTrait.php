<?php
/**
 * Created by PhpStorm.
 * User: Rima Panchal
 * Date: 3/19/2015
 * Time: 12:38 PM
 */
namespace App\Traits;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use App\Models\Locale;
use App\Models\LocaleDetails;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateDetails;
use DB;

trait ReuseFunctionTrait
{

    /**
     * Generate a random string
     * @param $col
     * @param null $title
     * @return string
     */
    public static function generateRandomString($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }

    /**
     * Display short description
     * @param $fullDescription
     * @return string
     */
    public static function shortDescription($fullDescription, $initialCount = 125)
    {
        $shortDescription = "";
        $fullDescription = trim(strip_tags($fullDescription));
        if ($fullDescription) {

            if (strlen($fullDescription) > $initialCount) {
                $shortDescription = substr($fullDescription, 0, $initialCount) . "...";
                return $shortDescription;
            } else {
                return $fullDescription;
            }
        }
    }

    /** Developed by : Pallavi */
    public function getLocaleDetailsForLang($request)
    {
        // echo "in trait"; die;
        $codes = $request->codes;
        // $request->language_id = 1;                               
        // DB::enableQueryLog();
        $locals = Locale::select('id','code');
                                foreach ($codes as $code) 
                                {
                                    $locals->orWhere('code',$code);
                                }
        $locals = $locals->get();
        // dd(DB::getQueryLog());
        // DB::enableQueryLog();

        foreach($locals as $key => $locale)
        {
            $localsDetails[] = LocaleDetails::select('id','locale_id','value')
                                    ->where('language_id',$request->language_id)
                                    ->where('locale_id',$locale->id)
                                    ->first();
            $localsDetails[$key]['code'] = $locale->code;
        }
        // dd(DB::getQueryLog());
        // dd($localsDetails);
        return $localsDetails;

    }

    public function replaceHtmlContent($data,$html_value)
    {        
        $html = $html_value;             
        foreach ($data as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        return $html;        
    }
}