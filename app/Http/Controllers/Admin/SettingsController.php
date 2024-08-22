<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Faqs;
use App\Models\GlobalLanguage;
use App\Models\Country;
use App\Models\FooterLinks;
use App\Models\FooterDetails;
use App\Models\ApiKeys;
use App\Models\GlobalSettings;
use Validator;
use Carbon\Carbon;
use DataTables;
use DB;
use Session;
use Image;
use File;
class SettingsController extends Controller
{
    // Settings
    public function getSetting(){
        $data = GlobalSettings::getAllValWithKey();

        return view('admin.settings.general-settings.settings');
    }

    public function setSetting(Request $request){
        $data = $request->all();
        $settings = $data['settings'];
        
        foreach ($settings as $key => $value) {

            if ($key == 'home_page_registration_banner' && $request->hasFile('settings.home_page_registration_banner')) {
                $photo = $request->file('settings.home_page_registration_banner');
                $ext = $photo->extension();
                $filename = rand() . '_' . time() . '.' . $ext;
                $filePath = public_path() . '/assets/images/homepage_register_banner';
                if (!File::exists($filePath)) {
                    File::makeDirectory($filePath);
                }

                $img = Image::make($photo->path());
                $width = config('app.homePageRegistrationBanner.width');
                $height = config('app.homePageRegistrationBanner.height');
                if ($img->width() == $width && $img->height() == $height) {
                    $photo->move($filePath . '/', $filename);
                } else {
                    $img->resize($width, $height)->save($filePath . '/' . $filename);
                }
                $value = $filename;
            }
            
            $setting = GlobalSettings::firstOrNew(['setting_key' => $key]);
            $setting->setting_value = $value;
            $setting->save();
        }
        $notification = array(
            'message' => 'Settings updated successfully!',
            'alert-type' => 'success'
        );
        return redirect(config('app.adminPrefix').'/settings')->with($notification);
    }
}
