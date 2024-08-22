<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class FanPlaylist extends Model
{
    use SoftDeletes;
    protected $table = 'fan_playlist';

    protected $fillable = ['user_id', 'playlist_name', 'status', 'slug'];


    public static function getList($id)
    {
        return self::withCount('songs')->where('user_id', $id)->get();
    }

    public static function getListById($id)
    {
        $return = [];
        $data = self::where('id', $id)->get();
        foreach ($data as $key => $value) {
            $return = [
                "playlistId" => $value['id'],
                "playListIcon" => FanPlaylistSongs::getPlaylistIcon($value['id']),
                "playlistName" => $value['playlist_name'],
                "playlistSlug" => $value['slug'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function getListApi($id, $limit = "", $search = "")
    {
        $return = [];
        $data = self::withCount('songs')->where('user_id', $id)->where('status','1')->orderBy('created_at','desc');
        if (!empty($search)) {
            $data->where('playlist_name', 'like', '%' . $search . '%');
        }
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedList($data);
        return $return;
    }

    public function songs()
    {
        return $this->hasMany(FanPlaylistSongs::class, 'playlist_id', 'id');
    }


    public static function createPlaylist($data)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            $data['user_id'] = $authId;
            $allowed = ['user_id', 'playlist_name', 'status'];
            $data = array_intersect_key($data, array_flip($allowed));
            $data['slug'] = getSlug($data['playlist_name'], "", 'fan_playlist', 'slug');
            // $exist = FanPlaylistS::where('song_id',$data['song_id'])->where('playlist_id',$data['playlist_id'])->first();
            // if (!$exist) {
            //     $data['sort_order'] = $data['sort_order']?:self::nextSortOrder($playlistId);
            //     self::create($data);
            // }
            $inserted = self::create($data);
            return $inserted->id;
        } catch (Exception $e) {
            return false;
        }
    }

    // public static nextPlaylistName($playlistId){
    //     $return = self::selectRaw('sort_order')->where('playlist_id',$playlistId)->orderBy('sort_order','desc')->first();
    //     return $return?$return->sort_order+1:1;
    // }

    public static function formatedList($data)
    {
        $authId = User::getLoggedInId();
        $navigate = $authId?"1":"0";
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "playlistId" => $value['id'],
                // "navigate" => $navigate,
                "navigate" => "1",
                "page" => Songs::getPageById(1),
                "navigateType" => "9",
                "navigateTo" => "fan-playlist-songs/".$value['id'],
                "playListIcon" => FanPlaylistSongs::getPlaylistIcon($value['id']),
                "playlistName" => $value['playlist_name'],
                "playlistSlug" => $value['slug'],
                "noOfSongs" => $value['songs_count'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public static function searchAPIFanPlaylist($search)
    {
        $data = [];
        $authId = User::getLoggedInId();
        $playlistdata = self::withCount('songs')->where('user_id', $authId)->whereNull('deleted_at');
        //$playlistdata->where('user_id', $authId)->whereNull('deleted_at');
        if ($search) {
            $playlistdata->where(function ($query) use ($search) {
                $query->Where('playlist_name', 'like', '%' . $search . '%');
            });
        }
        $playlistdata = $playlistdata->orderBy('created_at', 'DESC')->get()->toArray();
        if ($playlistdata) {
            $data = self::formatedList($playlistdata);
        }
        $return = ['playlistDetail' => $data];
        return $return;
    }
    public static function filterSearch($playlistdata)
    {
        $return = [];
        foreach ($playlistdata as $key => $value) {
            $return[] = [
                "name" => $value['playlist_name'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function updateFanPlaylist($data)
    {
        $return = [];
        $success = false;
        $exist = FanPlaylist::where('id', $data['id'])->first();
        if ($exist) {
            try {
                $authId = User::getLoggedInId();
                $data['user_id'] = $authId;
                $allowed = ['user_id', 'playlist_name', 'status'];
                $data = array_intersect_key($data, array_flip($allowed));
                $data['slug'] = getSlug($data['playlist_name'], "", 'fan_playlist', 'slug');
                foreach ($data as $key => $value) {
                    $exist->$key = $value;
                }
                $exist->save();
                $return = $exist;
                $success = true;
            } catch (Exception $e) {
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function getSongsCount($id){
        $data = FanPlaylistSongs::where('playlist_id',$id)->has('song')->get()->toArray();
        return count($data);
    }

    public static function getGroupIcon($id)
    {
        $return = url('public/assets/frontend/img/' . config('app.default_image'));
        $data = FanPlaylistSongs::where('playlist_id', $id)->has('song')->first();
        if ($data) {
            $return = $data->song->icon;
        }
        return $return;
    }
}
