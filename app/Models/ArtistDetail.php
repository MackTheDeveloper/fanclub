<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class ArtistDetail extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable;
    use SoftDeletes;
    protected $table = 'artist_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //no_subscription (type big_int)
    protected $fillable = [
        'user_id', 'bio', 'event', 'news_detail', 'interest', 'num_likes', 'num_views', 'no_subscription'
    ];
    public static function getArtistDetail($id)
    {
        return self::where('user_id',$id)->first();
    }

    public static function updateExist($data){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $allowed = ['bio', 'news_detail', 'interest'];
        $data = array_intersect_key($data, array_flip($allowed));
        $exist = self::where('user_id',$authId)->first();
        if ($exist) {
            try{
                foreach ($data as $key => $value) {
                    $exist->$key = $value;
                }
                $exist->save();
                $return = $exist;
            }catch(\Exception $e){
                $return = $e->getMessage();
                $success = false;
            }
        }else{
            $newDetail = new ArtistDetail;
            $newDetail->user_id = $authId;
            try{
                foreach ($data as $key => $value) {
                    $newDetail->$key = $value;
                }
                $newDetail->save();
                $return = $newDetail;
            }catch(\Exception $e){
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data'=>$return,'success'=>$success];
    }
}
