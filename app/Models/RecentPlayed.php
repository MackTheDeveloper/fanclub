<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class RecentPlayed extends Model
{
    // use SoftDeletes;
    protected $table = 'recent_played';

    protected $fillable = ['fan_id', 'song_id'];

    public function song()
    {
        return $this->hasOne(Songs::class, 'id', 'song_id');
    }

    // public static function getList($id)
    // {
    //     $return = [];
    //     $data = self::where('fan_id',$id)->get();
    //     foreach ($data as $key => $value) {
    //         $return[] = ['key'=>$value->id,'file'=>$value->song_id];
    //     }
    //     return $return;
    // }
    
    public static function addNew($data){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['fan_id'] = $authId;
        $allowed = ['fan_id', 'song_id'];
        $data = array_intersect_key($data, array_flip($allowed));
        try{
            self::where('fan_id',$data['fan_id'])->where('song_id', $data['song_id'])->delete();
            $create = new RecentPlayed();
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


    public static function getListApi($id, $limit = "")
    {
        $return = [];
        $data = self::has('song')->has('song.artist')->where('fan_id', $id)->orderBy('created_at','desc');
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedList($data);
        return $return;
    }

    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "navigate" => "1",
                "navigateType" => "15",
                "navigateTo" => "",
                "page" => Songs::getPageById(5),
                "download" => Songs::getDownloadUrl($value['song']['id']),
                "songId" => $value['song']['id'],
                "songSlug" => $value['song']['slug'],
                "songName" => $value['song']['name'],
                "songUrl" => $value['song']['file'],
                "songIcon" => $value['song']['icon'],
                "artistName" => $value['song']['artist']['firstname'] . ' ' . $value['song']['artist']['lastname'],
                "songVideoDownload" => route('getSongDownload', [$value['song']['slug']]),
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function getListApiNew($id, $limit = "", $search = "")
    {
        $return = [];
        //$data = self::has('song')->where('fan_id',$id);
        if (!empty($search)) {
            $data = self::whereHas('song', function ($query) use ($search) {
                $query->where('songs.name', 'like', '%' . $search . '%');
            })->where('fan_id', $id);
        } else {
            $data = self::has('song')->where('fan_id', $id);
        }
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->orderBy('created_at','DESC')->get(); //pre($data);
        $return = self::formatedListNew($data);
        return $return;
    }

    public static function getFanSongsForMusicPlayer($id, $param = '', $search = '')
    {
        $return = [];

        if (!empty($search)) {
            $data = self::whereHas('song', function ($query) use ($search) {
                $query->where('songs.name', 'like', '%' . $search . '%');
            })->where('fan_id', $id);
        } else {
            $data = self::has('song')->where('fan_id', $id);
        }
        $data->orderBy('recent_played.created_at','DESC');
        if ($param) {
            $data = $data->limit(1)->get()->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, $param);
        } else {
            $data = $data->get()->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, '');
        }
        return $return;
    }

    public static function formatedListNew($data)
    {
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $return = [];
        foreach ($data as $key => $value) {
            $songData = Songs::where('id', $value['song_id'])->first();
            $artistData = Artist::where('id', $songData->artist_id)->first();
            if($artistData){
                $artistFullname = $artistData->firstname . ' ' . $artistData->lastname;
                $return[] = [
                    "songId" => $value['song_id'],
                    "navigate" => $navigate,
                    "download" => Songs::getDownloadUrl($value['song_id']),
                    "page" => Songs::getPageById(5),
                    "navigateType" => "7",
                    "songName" => $songData->name,
                    "duration" => $songData->duration,
                    "artistName" => $artistFullname,
                    "songIcon" => $songData->icon,
                    "songLike" => FanFavouriteSongs::checkSongLiked($songData->id),
                    "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                    "createdAt" => getFormatedDate($value['created_at']),
                ];
            }
        }
        return $return;
    }
}
