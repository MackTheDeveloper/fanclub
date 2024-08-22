<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class Interest extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable;
    use SoftDeletes;
    protected $table = 'interest';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'topic', 'status'
    ];
    
    public static function getInteres($ids)
    {   
        $return = '';
        if ($ids) {
            $ids = explode(',', $ids);
            $data = self::whereIn('id',$ids)->where('status',1)->pluck('topic')->toArray();
            $return =  $data ? implode(',',$data) :'';
        }
        return $return;
    }
}
