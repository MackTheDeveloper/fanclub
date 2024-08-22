<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlists extends Model
{
    use HasFactory;
    protected $table = 'playlists';
    protected $fillable = ['name','dynamic_group_id','status', 'sort_order', 'deleted_at', 'created_at', 'updated_at'];

    public function dynamicgroup(){
        return $this->hasOne(DynamicGroups::class,'id', 'dynamic_group_id');
    }

    public static function getSortOrder(){
      $status='1';
      $return = self::selectRaw('sort_order')->where('status',$status)->whereNull('deleted_at')->orderBy('sort_order','desc')->first();
        return $return?$return->sort_order+1:1;
    }
    public static function getListApi(){
        $return = [];
        $data = self::select('playlists.*','dynamic_groups.name as dgName')->leftjoin('dynamic_groups','dynamic_groups.id','playlists.dynamic_group_id')->whereNull('playlists.deleted_at')->get();
        $return = self::formatedList($data);
        return $return;
    }
    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "playlistId" => $value['id'],
                "playlistName" => $value['name'],
                "dynamicGroupName" => $value['dgName'],
                "sortOrder" => $value['sort_order'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public static function getDetailApi($id){
        $return = [];
        $data = self::select('playlists.*','dynamic_groups.name as dgName','dynamic_groups.type')->leftjoin('dynamic_groups','dynamic_groups.id','playlists.dynamic_group_id')->where('playlists.id',$id)->whereNull('playlists.deleted_at')->first();
        $return = self::formatedDetails($data);
        return $return;
    }
    public static function formatedDetails($data)
    {
      $dynamicGroupItems=DynamicGroupItems::getDynamicGroupItems($data['dynamic_group_id'],$data['type']);
      $type=$data['type'];
      switch ($type) {
      case '1':
        $dynamicGroupType='Artists';
        break;
      case '2':
        $dynamicGroupType='Songs';
        break;
      case '3':
        $dynamicGroupType='Music Genres';
        break;
      case '4':
        $dynamicGroupType='Music Categories';
        break;
      case '5':
        $dynamicGroupType='Music Languages';
        break;
    }
            $return = [
                "playlistId" => $data['id'],
                "playlistName" => $data['name'],
                "dynamicGroupName" => $data['dgName'],
                "dynamicGroupType" => $dynamicGroupType,
                "dynamicGroupItems" => $dynamicGroupItems,
                "sortOrder" => $data['sort_order'],
                "createdAt" => getFormatedDate($data['created_at']),
            ];

        return $return;
    }
}
