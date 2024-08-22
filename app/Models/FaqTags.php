<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class FaqTags extends Model
{
    use SoftDeletes;

    protected $table = 'faq_tags';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','tag_name','status'
    ];

    public static function getTags($ids)
    {   
        $return = '';
        if ($ids) {
            $ids = explode(',', $ids);
            $data = self::whereIn('id',$ids)->where('status',1)->pluck('tag_name')->toArray();
            $return =  $data ? implode(',',$data) :'';
        }
        return $return;
    }

    public static function getTagList()
    {   
        $return = [];
        $data = self::where('status',1)->selectRaw('tag_name,id')->get()->toArray();
        if ($data) {
            $return[] = [
                "Id"=>"0",
                "name"=>"All",
                "isSelected"=>"1",
                "userType"=>"fan,artist"
            ];
            foreach ($data as $key => $value) {
                $return[] = [
                    "Id"=>strval($value['id']),
                    "name"=>$value['tag_name'],
                    "isSelected"=>"0",
                    "userType"=>"fan,artist"
                ];
            }
        }
        // pre($return);
        return $return;
    }

}
