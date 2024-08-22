<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class TempFanSubscription extends Model
{
    // use SoftDeletes;

    protected $table = 'temp_fan_subscription';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'artist_introduce','fan_id','artist_id','subscription_id','status'
    ];
}
