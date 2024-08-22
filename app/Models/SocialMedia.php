<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class SocialMedia extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable;
    use SoftDeletes;
    protected $table = 'social_media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'status', 'created_at', 'updated_at', 'deleted_at', 'icon'
    ];

    public static function getSocialList()
    {
        $return = [];
        $data = self::where('status', '1')->get();
        foreach ($data as $key => $value) {
            $return[] = [
                'key' => strval($value->id),
                'slug' => stringSlugify($value->name),
                'value' => $value->name,
                'icon' => !empty($value->icon) ? url('public/assets/frontend/img/'.$value->icon) : ''
            ];
        }

        return $return;
    }
}
