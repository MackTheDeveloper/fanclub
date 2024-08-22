<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Image;
use File;

class FanSearches extends Model
{
    use SoftDeletes;

    protected $table = 'fan_searches';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','fan_id','keyword','status'
    ];

    public static function apiAddFanSearch($search){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $exist = self::where('keyword',$search)->where('fan_id',$authId)->where('status',1)->first();
        if (!$exist) {
            try{
                $create = new FanSearches();
                $create->fan_id=$authId;
                $create->keyword=$search;
                $create->save();
                // self::makeAvgProductReview($product_id);
                $return = $create;
            }catch(\Exception $e){
                $return = $e->getMessage();
                $success = false;
            }
        }else{
            $return = 0;
            $success = false;
        }
        return ['data'=>$return,'success'=>$success];
    }
    public static function apiGetRecentSearches()
    {
        $data = [];
        $authId = User::getLoggedInId();
        $data = FanSearches::where('fan_id',$authId)->where('status',1)->whereNull('deleted_at');
        $data = $data->orderBy('created_at','DESC')->limit(8)->get()->toArray();
        if ($data) {
            $data = self::getFormatedData($data);
        }
        $return = ['recentSearches' => $data];
        return $return;
    }
    public static function getFormatedData($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
              "id" => $value['id'],
              "keyword" => $value['keyword'],
              "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
}
