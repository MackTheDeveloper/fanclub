<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class ArtistSubscriberHistory extends Model
{
    // use SoftDeletes;

    protected $table = 'artist_subscriber_history';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fan_id','artist_id','subscription_id'
    ];


    public static function currentMonthSubscriber($id){
        $return = 0;
        $month = date('m');
        $year = date('Y');
        $data = self::selectRaw('count(*) as counts')->where('artist_id',$id)->whereMonth('created_at',$month)->whereYear('created_at',$year)->first();
        if ($data) {
            $return = $data->counts;
        }
        return $return;
    }

    public static function currentYearSubscriber($id){
        $return = 0;
        $year = date('Y');
        $data = self::selectRaw('count(*) as counts')->where('artist_id',$id)->whereYear('created_at',$year)->first();
        if ($data) {
            $return = $data->counts;
        }
        return $return;
    }

    public static function currentSubscriber($id){
        $return = 0;
        $data = self::selectRaw('count(*) as counts')->where('artist_id',$id)->first();
        if ($data) {
            $return = $data->counts;
        }
        return $return;
    }
}
