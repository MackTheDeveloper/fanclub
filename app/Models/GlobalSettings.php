<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\User;

class GlobalSettings extends Model
{
    use SoftDeletes;

    protected $table = 'global_settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'setting_key','setting_value'
    ];


    public static function getSingleSettingVal($key){
        $return = "";
        $data = GlobalSettings::where('setting_key',$key)->first();
        if ($data) {
            $return = $data->setting_value;
        }
        return $return;
    }

    public static function getAllValWithKey(){
        $return = [];
        $data = GlobalSettings::get();
        foreach ($data as $key => $value) {
            $return[$value->setting_key] = $value->setting_value;
        }
        return $return;
    }

}
