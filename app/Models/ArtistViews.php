<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtistViews extends Model
{
    protected $table = 'artist_views';

    protected $fillable = ['artist_id','viewer_id'];

}
