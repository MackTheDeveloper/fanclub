<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class FanFavouriteGroups extends Model
{
    protected $table = 'fan_favourite_groups';

    protected $fillable = ['fan_id','group_id'];

    public function group(){
        return $this->hasOne(DynamicGroups::class, 'id', 'group_id');
    }

    public function fan(){
        return $this->hasOne(Fan::class, 'id', 'fan_id');
    }

    // public static function getListApi($id,$limit="",$search=""){
    //     $return = [];
    //     $data = self::has('group')->where('fan_id',$id);
    //     if(!empty($search)){
    //       $data = self::leftjoin('dynamic_groups','dynamic_groups.id','fan_favourite_groups.group_id');
    //       $data->where('dynamic_groups.name', 'like', '%' . $search . '%');
    //     }
    //     if ($limit) {
    //         $data->limit($limit);
    //     }
    //     $data = $data->get();
    //     $return = self::formatedList($data);
    //     return $return;
    // }

    public static function getListApi($page = "1", $search="", $filter=[],$limits="")
    {
        $return = [];
        $data = self::has('group');
        if (!empty($search)) {
            $data->whereHas('group', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
            // $data = self::leftjoin('dynamic_groups', 'dynamic_groups.id', 'fan_favourite_groups.group_id');
            // $data->where('dynamic_groups.name', 'like', '%' . $search . '%');
        }
        if ($limits) {
            $data->limit($limits);
        }else{
            if ($page) {
                $limit = 10;
                $offset = ($page - 1) * $limit;
                $data->offset($offset);
                $data->limit($limit);
            }
        }


        if ($filter) {
            foreach ($filter as $key => $value) {
                $data->where($key, $value);
            }
        }


        // $data = $data->get();
        // $return = self::formatedList($data);
        // return $return;
        $data = $data->get();
        $return = self::formatedList($data);
        if ($limits) {
            return $return;
        } else {
            return ['data' => $return, 'page' => $page, 'limit' => $limit];
        }
    }

    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "groupId" => $value['group']['id'],
                "navigate" => "1",
                "navigateType" => "5",
                "navigateTo" => "fanclub-group/".$value['group']['id'],
                "groupName" => $value['group']['name'],
                "groupSlug" => $value['group']['slug'],
                "groupIcon" => DynamicGroupItems::getGroupIcon($value['group']['id'], $value['group']['view_all']),
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function checkGroupLiked($songId){
        $authId = User::getLoggedInId();
        $return = 0;
        if ($authId) {
            $data = self::where('group_id',$songId)->where('fan_id',$authId)->get()->toArray();
            if ($data) {
                $return = 1;
            }
        }
        return $return;
    }
}
