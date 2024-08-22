<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistLikes extends Model
{
    protected $table = 'artist_likes';

    protected $fillable = ['artist_id','liker_id'];


    public static function checkArtistLiked($artistId){
        $authId = User::getLoggedInId();
        $data = self::where('artist_id',$artistId)->where('liker_id',$authId)->first();
        if ($data) {
            return 1;
        }
        return 0;
    }

}
