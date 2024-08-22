<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeys extends Model
{
    protected $table = 'api_keys';

    protected $fillable = ['api_key'];
}
