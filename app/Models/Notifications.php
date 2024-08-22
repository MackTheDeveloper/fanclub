<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Storage;

class Notifications extends Model
{
    use SoftDeletes;
    protected $table = 'notifications';

    protected $fillable = ['type', 'related_id', 'user_id', 'description'];

    public static function createNotification($data){
        $insert = new Notifications();
        if (!empty($data['message'])) {
            $data['description'] = $data['message'];
        }
        $allowed = ['type', 'related_id', 'user_id', 'description'];
        $data = array_intersect_key($data, array_flip($allowed));
        foreach ($data as $key => $value) {
            $insert->$key = $value;
        }
        $insert->save();
    }

    public static function songAddedtoFavArtist($songId)
    {
        $insert = [];
        $songData = Songs::where('id', $songId)->with('artist')->first();
        if ($songData) {
            $artistName = $songData->artist->firstname;
            $songName = $songData->name;
            $insert['message'] = $artistName.' added new song '. $songName.' in his library.';
            
            // Get Fans id
            $artistId = $songData->artist_id;
            $fans = FanFavouriteArtists::where('artist_id', $artistId)->pluck('fan_id');
            if ($fans) {
                $fanId = implode(',', $fans);
                $insert['user_id'] = $fanId;
            }

            self::createNotification($insert);
        }
    }


    public static function publishStatus($songId,$type)
    {
        $insert = [];
        $songData = TmpSongs::where('id', $songId)->with('artist')->first();
        if ($songData) {
            // $artistName = $songData->artist->firstname;
            $songName = $songData->name;
            if ($type == 'success') {
                $insert['message'] = 'Your song ' . $songName . ' is published successfully.';
            } else {
                $insert['message'] = 'Your song ' . $songName . ' is having error in transcoding.';
            }
            $artistId = $songData->artist_id;
            $insert['user_id'] = $artistId;
            self::createNotification($insert);
        }
    }

    public static function songAddReview($songId, $authId)
    {
        $insert = [];
        $songData = Songs::where('id',$songId)->with('artist')->first();
        $user = User::find($authId);
        if ($songData) {
            // $artistName = $songData->artist->firstname;
            $songName = $songData->name;
            $artistId = $songData->artist_id;

            $insert['message'] = $user->firstname.' added rateing on  ' . $songName . '.';            
            $insert['user_id'] = $artistId;
            self::createNotification($insert);
        }
    }

    public static function notificationByUser($limit=""){
        $return = [];
        $userId = User::getLoggedInId();
        $data = self::whereRaw("FIND_IN_SET(?, user_id) > 0", [$userId])->orderBy('created_at','desc');
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedList($data);
        return $return;
    }

    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "type" => $value->type,
                "relatedId" => $value->related_id,
                "description" => $value->description,
                "createdAt" => !empty($value->created_at) ? getFormatedDate($value->created_at) : "",
                "viewCreatedAt" => getFormatedDateForWeb($value->created_at),
            ];
        }
        return $return;
    }
}
