<?php

namespace App\Models;

use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class FanFavouriteSongs extends Model
{
    protected $table = 'fan_favourite_songs';

    protected $fillable = ['fan_id', 'song_id'];

    public function song()
    {
        return $this->hasOne(Songs::class, 'id', 'song_id');
    }

    public function fan()
    {
        return $this->hasOne(Fan::class, 'id', 'fan_id');
    }

    public static function getListApi($id, $limit = "")
    {
        $return = [];
        // $data = self::has('song')->where('fan_id', $id);
        // if ($limit) {
        //     $data->limit($limit);
        // }
        // $data = $data->get()->sortBy('song.name', SORT_NATURAL | SORT_FLAG_CASE, false);
        $data = self::selectRaw('songs.*,fan_favourite_songs.created_at as like_created_at')->join('songs','songs.id', 'fan_favourite_songs.song_id')->where('fan_id', $id)->whereNull('songs.deleted_at')->orderBy('songs.name');
        if ($limit) {
            // pre($limit);
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedList($data);
        // pre($return);
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
                "songId" => $value['id'],
                "download" => Songs::getDownloadUrl($value['id']),
                "songSlug" => $value['slug'],
                "songName" => $value['name'],
                "songUrl" => $value['file'],
                // "songIcon" => $value['icon'],
                "songIcon" => Songs::getIconById($value['id']),
                "artistName" => Artist::getArtistNameById($value['artist_id']),
                // "artistName" => $value['artist']['firstname'] . ' ' . $value['artist']['lastname'],
                "songVideoDownload" => route('getSongDownload', [$value['slug']]),
                "createdAt" => getFormatedDate($value['like_created_at']),
            ];
        }
        return $return;
    }
    public static function getListApiNew($id, $limit = "", $search = "")
    {
        $return = [];
        //$data = self::has('song')->where('fan_id',$id);
        // if (!empty($search)) {
        //     $data = self::whereHas('song', function ($query) use ($search) {
        //         $query->where('songs.name', 'like', '%' . $search . '%');
        //     })->where('fan_id', $id);
        // } else {
        //     $data = self::has('song')->where('fan_id', $id);
        // }
        // if ($limit) {
        //     $data->limit($limit);
        // }
        // $data = $data->get()->sortBy('song.name', SORT_NATURAL | SORT_FLAG_CASE, false); //pre($data);
        $data = self::has('song')->has('song.artist')->selectRaw('songs.*,songs.id as songs_id,fan_favourite_songs.created_at as like_created_at')->join('songs', 'songs.id', 'fan_favourite_songs.song_id')->where('fan_id', $id)->orderBy('songs.name');
        if ($limit) {
            // pre($limit);
            $data->limit($limit);
        }
        if (!empty($search)) {
            $data->where('songs.name', 'like', '%' . $search . '%');
        }
        $data = $data->get();
        $return = self::formatedListNew($data);
        return $return;
    }

    public static function formatedListNew($data)
    {
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $return = [];
        foreach ($data as $key => $value) {
            // pre($value['song_id']);
            // $songData = Songs::where('id', $value['song_id'])->first();
            $artistData = Artist::where('id', $value->artist_id)->first();
            if ($artistData) {
                $artistFullname = $artistData->firstname . ' ' . $artistData->lastname;
                $return[] = [
                    "songId" => $value->songs_id? $value->songs_id: $value->id,
                    "navigate" => $navigate,
                    "page" => Songs::getPageById(5),
                    "download" => Songs::getDownloadUrl($value->id),
                    "navigateType" => "7",
                    "songName" => $value->name,
                    "duration" => Songs::formatDuration($value->duration),
                    "artistName" => $artistFullname,
                    "songIcon" => $value->icon,
                    "songLike" => FanFavouriteSongs::checkSongLiked($value->id),
                    "songVideoDownload" => route('getSongDownload', [$value->slug]),
                    "createdAt" => getFormatedDate($value['created_at']),
                ];
            }
        }
        return $return;
    }

    public static function searchAPIFanFavouriteSong($search)
    {
        $data = [];
        $authId = User::getLoggedInId();
        if ($authId) {
            $songdata = FanFavouriteSongs::selectRaw('*,songs.id as songs_id')->leftjoin('songs', 'songs.id', 'fan_favourite_songs.song_id')->where('fan_favourite_songs.fan_id', $authId)->whereNull('songs.deleted_at')
            ->leftjoin('users', 'users.id', 'songs.artist_id')
            ->where('users.is_active', '1')
            ->where('users.is_verify', '1')
            ->whereNull('users.deleted_at');
            if ($search) {
                $songdata->where(function ($query) use ($search) {
                    $query->Where('songs.name', 'like', '%' . $search . '%');
                });
            }
            $songdata = $songdata->orderBy('fan_favourite_songs.created_at', 'DESC')->get();
            if ($songdata) {
                $data = self::formatedListNew($songdata);
            }
        }
        $return = ['songDetail' => $data];
        return $return;
    }
    public static function filterSearch($songdata)
    {
        $return = [];
        foreach ($songdata as $key => $value) {

            $return[] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "page" => Songs::getPageById(5),
                "download" => Songs::getDownloadUrl($value['id']),
                "icon" => Songs::getIconById($value['id']),
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public static function checkSongLiked($songId)
    {
        $authId = User::getLoggedInId();
        $data = self::where('song_id', $songId)->where('fan_id', $authId)->first();
        if ($data) {
            return 1;
        }
        return 0;
    }

    public static function countSongLiked($id)
    {
        $data = self::where('fan_id', $id)->count();
        return $data;
    }

    public static function getFanSongsForMusicPlayer($id, $param = '', $search = '')
    {
        $return = [];
        
        if (!empty($search)) {
            $data = self::whereHas('song', function ($query) use ($search) {
                $query->where('songs.name', 'like', '%' . $search . '%');
            })->has('song.artist')->where('fan_id', $id)->orderBy('songs.name')
                ->leftjoin('songs', 'songs.id', 'fan_favourite_songs.song_id')->orderBy('songs.name');
        }else{
            $data = self::has('song')->has('song.artist')->where('fan_id', $id)->leftjoin('songs', 'songs.id', 'fan_favourite_songs.song_id')->orderBy('songs.name');
        }

        if ($param) {
            $data = $data->limit(1)->get()->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, $param);
        } else {
            $data = $data->get()->sortBy('song.name', SORT_NATURAL | SORT_FLAG_CASE, false)->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, '');
        }
        return $return;
    }
}
