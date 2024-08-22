<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class Faqs extends Model
{
    use SoftDeletes;

    protected $table = 'faq';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','type','question','answer','status','tags'
    ];

    public static function getListByType($type=""){
        $return = [];
        $data = self::where('status','1');
        if ($type) {
            $data->where('type',$type);
        }
        $data = $data->get();
        if ($data) {
            $return = self::formatSearch($data);
        }
        return $return;
    }

    public static function formatSearch($faqs){
        $return = [];
        foreach ($faqs as $key => $value) {
            $return[] = [
                "question" => $value['question'],
                "userType" => $value['type'],
                "answer" => $value['answer'],
                "tagNames" => FaqTags::getTags($value['tags']),
                "tags" => $value['tags'],
            ];
        }
        return $return;
    }

}
