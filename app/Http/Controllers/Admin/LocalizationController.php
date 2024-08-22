<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use DataTables;
use DB;
use Auth;

class LocalizationController extends Controller
{
    /* ###########################################
    // Function: addLocale
    // Description: Add locales
    // Parameter: code: String, Title: String
    // ReturnType: view
    */ ###########################################
    public function listLocales(Request $request)
    {
        if($request->ajax())
        {
            $id = Auth::guard('admin')->user()->id;

            $locale = \App\Models\Locale::select('id','code','title','is_active',DB::raw("date_format(created_at,'%Y-%m-%d %h:%i:%s') as lc_created_at"),'updated_at')->where('is_active', 0)->get();
            return Datatables::of($locale)->make(true);
        }
        return view('admin.localization.list');
    }

    /* ###########################################
    // Function: showLocalizaionForm
    // Description: Show localization insert form
    // Parameter: No parameter
    // ReturnType: view
    */ ###########################################
    public function showLocaleForm()
    {
        $languages = \App\Models\GlobalLanguage::select('world_languages.alpha2','world_languages.langEN','global_language.id')
        ->leftJoin('world_languages','world_languages.id','=','global_language.language_id')
        ->get();
        return view('admin.localization.add',compact('languages'));
    }

    /* ###########################################
    // Function: addLocale
    // Description: Add locales
    // Parameter: code: String, Title: String
    // ReturnType: view
    */ ###########################################
    public function addLocale(Request $request)
    {
        try {
            $global_language = \App\Models\GlobalLanguage::select('world_languages.alpha2','world_languages.id','global_language.id')
            ->leftJoin('world_languages','world_languages.id','=','global_language.language_id')
            ->get();
            $pluck = [];
            foreach($global_language as $key => $val)
            {
                if($val->alpha2)
                {
                    array_push($pluck, $val->alpha2);
                }
            }
            $locale = \App\Models\Locale::where('code', $request->code)->first();
            if($locale)
            {
                return redirect()->back()->with('msg', "Code is already used please try another code")->with('alert-class', false);
            }
            $locale = new \App\Models\Locale;
            $locale->code = $request->code;
            $locale->title = $request->title;
            $locale->save();
            $counter = 0;
            foreach($request->test as $key => $val)
            {
                $counter++;
                if($val == "")
                {
                    return redirect()->back()->with('msg', "Please fill the value for Tab ".strtoupper($key))->with('alert-class', false);
                }
                $localeDetails = new \App\Models\LocaleDetails;
                $localeDetails->locale_id = $locale->id;
                if(in_array($key, $pluck))
                {
                    $global_language = \App\Models\GlobalLanguage::select('world_languages.alpha2','global_language.id')
                    ->leftJoin('world_languages','world_languages.id','=','global_language.language_id')
                    ->where('world_languages.alpha2', $key)
                    ->first();
                    $localeDetails->language_id = $global_language->id;
                }
                $localeDetails->value = $val;
                $localeDetails->save();
            }
            $notification = array(
                'message' => 'Locales inserted Successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/locale')->with($notification);
            // return redirect()->back()->with('msg', "Locales inserted Successfully!")->with('alert-class', true);
        } catch (Exception $e) {
            return view('errors.500');
        }
    }

    /* ###########################################
    // Function: editLocale
    // Description: Edit locales
    // Parameter: id: Int
    // ReturnType: view
    */ ###########################################
    public function editLocale($locale_id)
    {
        try {
            $locale = \App\Models\Locale::where('locale.id',$locale_id)
            ->first();
            $distinct_localeDetails = \App\Models\LocaleDetails::select('locale_id')
            ->where('locale_id',$locale->id)->distinct('locale_id')->first();
            $localeDetails = \App\Models\LocaleDetails::select('locale_details.id','locale_details.language_id','locale_details.value','world_languages.alpha2','world_languages.langEN')
            ->leftJoin('global_language','global_language.id','=','locale_details.language_id')
            ->leftJoin('world_languages','world_languages.id','=','global_language.language_id')
            ->where('locale_details.locale_id',$locale->id)
            ->get();
            return view('admin.localization.edit',compact('locale','distinct_localeDetails','localeDetails'));
        } catch (Exception $e) {
            return view('errors.500');
        }

    }

    /* ###########################################
    // Function: updateLocale
    // Description: Update existing locales
    // Parameter: id: Int
    // ReturnType: view
    */ ###########################################
    public function updateLocale(Request $request)
    {
        try {
            $locale = \App\Models\Locale::where('id', $request->locale_id)->first();
            $locale->title = $request->title;
            $locale->save();
            foreach($request->test as $key => $val)
            {
                if($key)
                {
                    $arr = explode(',', $key);
                    $localeDetails = \App\Models\LocaleDetails::where('id',$arr[1])
                    ->where('language_id', $arr[2])
                    ->where('locale_id', $locale->id)
                    ->first();
                    $localeDetails->value = $val;
                    $localeDetails->save();
                }
            }
            $notification = array(
                'message' => 'Locales updated Successfully!',
                'alert-type' => 'success'
            );
            return redirect(config('app.adminPrefix').'/locale')->with($notification);
            // return redirect()->back()->with('msg', "Locales updated Successfully!")->with('alert-class', true);
        } catch (Exception $e) {
            return view('errors.500');
        }
    }

    public function deleteLocale(Request $request)
    {
        if($request->ajax())
        {
            $locale = \App\Models\Locale::where('id', $request->locale_id)->first();
            if($locale)
            {
                $locale->is_active = 1;
                $locale->save();
                $result['status'] = 'true';
                $result['msg'] = 'Locale Deleted Successfully!';
                return $result;
            }
            else
            {
                $result['status'] = 'false';
                $result['msg'] = 'Something went wrong!';
                return $result;
            }
        }
    }

    public function filterLocale(Request $request)
    {
        if($request->ajax())
        {
            $id = Auth::guard('admin')->user()->id;

            $query = DB::table('locale')->select('id','code','title','is_active',DB::raw("date_format(created_at,'%Y-%m-%d %h:%i:%s') as lc_created_at"),'updated_at');
            if (isset($request->code)){
                $code = $request->code;
                $query->where(function($query2) use ($code)
                {
                    $query2->where('code', 'LIKE', '%'.$code.'%');
                });
            }
            if(isset($request->title)){
                $title = $request->title;
                $query->where('title', 'LIKE', '%'.$title.'%');
            }
            $result = $query->get();
            return Datatables::of($result)->make(true);
        }
        return view('admin.localization.list');
    }
}
