<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongViews extends Model
{
    protected $table = 'song_views';

    protected $fillable = ['song_id','viewer_id','view_duration'];

}
