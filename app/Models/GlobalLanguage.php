<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalLanguage extends Model
{
    use HasFactory;

    public $table = 'global_language';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language_id',       
    ];


    public function language()
    {
        return $this->hasOne('App\Models\WorldLanguage', 'id', 'language_id');
    }
}
