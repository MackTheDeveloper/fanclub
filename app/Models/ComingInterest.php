<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Mail;

class ComingInterest extends Model
{
    use SoftDeletes;
    protected $table = 'coming_interest';

    protected $fillable = ['name', 'email', 'role'];



    public static function insertInterest($data){
        $return = [];
        $success = true;
        try {
            $allowed = ['name','email','role'];
            $data = array_intersect_key($data, array_flip($allowed));
            self::create($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
}
