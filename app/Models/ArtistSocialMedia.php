<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class ArtistSocialMedia extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable;
    use SoftDeletes;
    protected $table = 'artist_social_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'social_id', 'url', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];
    public static function getSocialMedia($id)
    {
        $data = self::where('user_id',$id)->where('status',1)->pluck('url')->toArray();
        return $data ? implode(',',$data) :'';
    }
}
