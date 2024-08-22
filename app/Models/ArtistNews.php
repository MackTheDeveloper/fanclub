<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtistNews extends Model
{
    use SoftDeletes;
    protected $table = 'artist_news';

    protected $fillable = ['artist_id','name','date','description','status','created_at','updated_at','deleted_at'];

    public static function getList($id)
    {
        $data = self::where('artist_news.artist_id',$id)->whereNull('deleted_at')->get();
        return $data;
    }

    public static function getNewsByArtist($id,$limits=0, $page = "1", $filter = []){
        $data = self::whereNull('deleted_at')
            ->where('artist_id',$id)
            ->where('status','1')
            ->orderBy('date','desc');

        $limit = 0;
        if ($limits) {
            // echo $limits;die;
            $data->limit($limits);
        }else{
            if ($page) {
                $limit = 10;
                $offset = ($page - 1) * $limit;
                $data->offset($offset);
                $data->limit($limit);
            }
        }
        $data = $data->get();
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "id" => $value['id'],
                "artistId" => $value['artist_id'],
                "name" => $value['name'],
                "description" => $value['description'],
                "date" => getFormatedDate($value['date']),
                "readMore" => "Read More"
            ];
        }

        if (!$limits) {
            $return = ['data' => $return, 'page' => $page, 'limit' => $limit];
        }
        return $return;
    }

    public static function getNewsById($id){
        $data = self::find($id);
        $return = [];
        if ($data) {
            $return = [
                "id" => $data['id'],
                "name" => $data['name'],
                "description" => $data['description'],
                "date" => getFormatedDate($data['date']),
            ];
        }
        return $return;
    }

    public static function addNew($data){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['artist_id'] = $authId;
        if (!isset($data['date'])) {
            $data['date'] = date('Y-m-d');
        }
        $allowed = ['artist_id','name','description','date','status'];
        $data = array_intersect_key($data, array_flip($allowed));
        try{
            $create = new ArtistNews();
            foreach ($data as $key => $value) {
                $create->$key = $value;
            }
            $create->save();
            $return = $create;
        }catch(\Exception $e){
            $return = $e->getMessage();
            $success = false;
        }
        return ['data'=>$return,'success'=>$success];
    }
    public static function updateData($data,$event_id){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['artist_id'] = $authId;
        $allowed = ['artist_id','name','description','date','status'];
        $data = array_intersect_key($data, array_flip($allowed));
        $update = ArtistNews::find($event_id);
        if ($update) {
            try{
                foreach ($data as $key => $value) {
                    $update->$key = $value;
                }
                $update->update();
                $return = $update;
            }catch(\Exception $e){
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data'=>$return,'success'=>$success];
    }
}
