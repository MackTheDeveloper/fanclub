<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SongComments extends Model
{
    use SoftDeletes;
    protected $table = 'song_comments';

    protected $fillable = ['song_id','user_id','comment','status'];



    public function song(){
        return $this->hasOne(Songs::class,'id', 'song_id');
    }

    public function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }

    public static function addCommentSong($data){
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId && isset($data['song_id'])) {
                $exist = SongComments::where('user_id', $authId)->where('song_id',$data['song_id'])->first()->toArray();
                   // echo "<pre>";print_r($exist);echo "</pre>";die();
                    if ($exist)
                    {
                        $exist['user_id'] = $authId;
                        $exist['song_id'] = $data['song_id'];
                        $exist['comment'] = $data['comment'];
                        self::create($exist);
                    }
                }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
