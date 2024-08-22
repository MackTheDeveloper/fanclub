<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Songs;
use App\Models\Artist;
use App\Models\FanFavouriteSongs;



class FanPlaylistSongs extends Model
{
    use SoftDeletes;
    protected $table = 'fan_playlist_songs';

    protected $fillable = ['playlist_id', 'song_id', 'sort_order'];

    public function playlist()
    {
        return $this->belongsTo(FanPlaylist::class);
    }

    public function song()
    {
        return $this->hasOne(Songs::class, 'id', 'song_id');
    }

    public static function getList($id)
    {
        return self::selectRaw('fan_playlist_songs.*,songs.name')->leftjoin('songs', 'songs.id', 'fan_playlist_songs.song_id')->where('playlist_id', $id)->whereNull('songs.deleted_at')->get();
    }

    public static function getPlaylistIcon($playlistId)
    {
        $return = url('public/assets/frontend/img/' . config('app.default_image'));
        $data = self::has('song')->where('playlist_id', $playlistId)->first();
        if ($data) {
            $return = $data->song->icon;
        }
        return $return;
    }

    public static function getListApi($id)
    {
        $return = [];
        $data = self::has('song')->where('playlist_id', $id)->orderBy('sort_order', 'asc')->get()->toArray();
        $return = self::formatedList($data);
        return $return;
    }

    public static function addSongToPlaylist($data)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            $allowed = ['playlist_id', 'song_id', 'sort_order'];
            $data = array_intersect_key($data, array_flip($allowed));
            $exist = FanPlaylistSongs::where('song_id', $data['song_id'])->where('playlist_id', $data['playlist_id'])->first();
            if (!$exist) {
                $data['sort_order'] = isset($data['sort_order']) ? $data['sort_order'] : self::nextSortOrder($data['playlist_id']);
                self::create($data);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function createPlaylistAddSongs($data)
    {
        $return = [];
        $success = true;
        try {
            $playlistId = FanPlaylist::createPlaylist($data);
            if ($playlistId && isset($data['songs'])) {
                $allowed = ['playlist_id', 'song_id', 'sort_order'];
                foreach ($data['songs'] as $key => $value) {
                    $insert = ['playlist_id' => $playlistId, 'song_id' => $value];
                    $insert = array_intersect_key($insert, array_flip($allowed));
                    $exist = FanPlaylistSongs::where('song_id', $insert['song_id'])->where('playlist_id', $insert['playlist_id'])->first();
                    if (!$exist) {
                        $insert['sort_order'] = isset($insert['sort_order']) ? $insert['sort_order'] : self::nextSortOrder($insert['playlist_id']);
                        self::create($insert);
                    }
                }
                $return = ['playlistId' => $playlistId, 'playlistName' => $data['playlist_name']];
                $success = true;
            }
            $return['playlistName'] = $data['playlist_name'];
        } catch (Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function nextSortOrder($playlistId)
    {
        $return = self::selectRaw('sort_order')->where('playlist_id', $playlistId)->orderBy('sort_order', 'desc')->first();
        return $return ? $return->sort_order + 1 : 1;
    }

    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $songData = Songs::where('id', $value['song_id'])->first();
            $authId = User::getLoggedInId();
            $isFav = FanFavouriteSongs::where('fan_id', $authId)->where('song_id', $value['song_id'])->first();
            $return[] = [
                "playListsongId" => $value['id'],
                "songId" => $value['song_id'],
                "page" => Songs::getPageById(5),
                "download" => Songs::getDownloadUrl($value['song_id']),
                "songName" => $songData['name'],
                "songUrl" => $songData['file'],
                "songIcon" => $songData['icon'],
                "sortOrder" => $value['sort_order'],
                "createdAt" => getFormatedDate($value['created_at']),
                "desc" => Artist::getNameById($songData->artist_id),
                "time" => $songData['duration'],
                "isFav" => isset($isFav) ? 1 : 0
            ];
        }
        return $return;
    }
    public static function getListApiNew($id)
    {
        $return = [];
        $data = self::has('song')->where('playlist_id', $id)->orderBy('sort_order', 'asc')->get()->toArray();
        $return = self::formatedListNew($data);
        return $return;
    }
    public static function formatedListNew($data)
    {
        $return = [];
        $total = count($data);
        foreach ($data as $key => $value) {
            $songData = Songs::where('id', $value['song_id'])->first();
            $artistData = Artist::where('id', $songData->artist_id)->first();
            $artistFullname = $artistData->firstname . ' ' . $artistData->lastname;
            $return[] = [
                "playListsongIdPk" => $value['id'],
                "playListsongId" => $value['playlist_id'],
                "songId" => $value['song_id'],
                "songName" => $songData->name,
                "duration" => $songData->duration,
                "artistName" => $artistFullname,
                "songIcon" => $songData->icon,
                "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                "sortOrder" => $value['sort_order'],
                "createdAt" => getFormatedDate($value['created_at']),
                "songLike" => FanFavouriteSongs::checkSongLiked($value['song_id'])
            ];
        }
        return $return;
    }

    public static function getFanSongsForMusicPlayer($id, $param = '')
    {
        $return = [];
        $data = self::has('song')->where('playlist_id', $id)->orderBy('sort_order', 'asc');
        if ($param)
            $data = $data->limit(1)->get()->toArray();
        else
            $data = $data->get()->toArray();

        $return = self::formatedListForMusicPlayer($data, $param);
        return $return;
    }
    
    public static function formatedListForMusicPlayer($data, $param)
    {
        $return = [];
        if ($param) {
            foreach ($data as $key => $value) {
                $songData = Songs::where('id', $value['song_id'])->first();
                $artistData = Artist::where('id', $songData->artist_id)->first();
                if ($artistData) {
                    $artistFullname = $artistData->firstname;
                    $maxResultion = SongVariants::getMaxResultion($value['song_id']);
                    $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($value['song_id'],$maxResultion);
                    $return['data'] = [
                        "songId" => $value['song_id'],
                        "page" => Songs::getPageById(5),
                        "songName" => $songData->name,
                        "songSlug" => $songData->slug,
                        "baseUrl" => url('song-access'),
                        "duration" => $songData->duration,
                        "artistId" => $songData->artist_id,
                        "artistName" => $artistFullname,
                        "artistSlug" => $artistData->slug,
                        "artistIcon" => UserProfilePhoto::getProfilePhoto($songData->artist_id),
                        "songIcon" => $songData->icon,
                        "songPlaceholder" => url('public/assets/frontend/img/video-placeholder2.png'),
                        //"songVideo" => $songData->file,
                        "songVideo" => Songs::getSongUrlAccess($value['song_id']),
                        "currentResolution" => $maxResultion,
                        "songAudio" => Songs::getSongUrlAccess($value['song_id'],'mp3'),
                        "s3AudioUrl" => SongVariants::getAudioURL($value['song_id']),
                        "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                        "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                        "s3VideoUrl" => $maxResultionSongUrl,
                        "songLike" => FanFavouriteSongs::checkSongLiked($value['song_id']),
                        "activePlayingClass" => 'activePlaying'
                    ];
                }
                $return['generalReviews'] = Reviews::getFirstSongReviewDataForMusicPlayer($value['song_id']);
                $return['allReviews'] = Reviews::getSongAllReviewsDataForMusicPlayer($value['song_id']);
            }
        } else {
            $i = 1;
            foreach ($data as $key => $value) {
                $songData = Songs::where('id', $value['song_id'])->first();
                $artistData = Artist::where('id', $songData->artist_id)->first();
                if ($artistData) {
                    $artistFullname = $artistData->firstname;
                    $maxResultion = SongVariants::getMaxResultion($value['song_id']);
                    $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($value['song_id'],$maxResultion);
                    $return['list'][] = [
                        "songId" => $value['song_id'],
                        "page" => Songs::getPageById(5),
                        "songName" => $songData->name,
                        "songSlug" => $songData->slug,
                        "duration" => $songData->duration,
                        "artistName" => $artistFullname,
                        "artistSlug" => $artistData->slug,
                        "artistIcon" => UserProfilePhoto::getProfilePhoto($songData->artist_id),
                        "songIcon" => $songData->icon,
                        "songPlaceholder" => url('public/assets/frontend/img/video-placeholder2.png'),
                        //"songVideo" => $songData->file,
                        "songVideo" => Songs::getSongUrlAccess($value['song_id']),
                        "songAudio" => Songs::getSongUrlAccess($value['song_id'], 'mp3'),
                        "s3AudioUrl" => SongVariants::getAudioURL($value['song_id']),
                        "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                        "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                        "s3VideoUrl" => $maxResultionSongUrl,
                        "songLike" => FanFavouriteSongs::checkSongLiked($value['song_id']),
                        "activePlayingClass" => $i == 1 ? 'activePlaying' : ''
                    ];
                    $i++;
                }
            }
        }

        return $return;
    }

    public static function getMusicPlayerSongData($songId)
    {
        $return = [];
        // $data = Songs::selectRaw('songs.id as song_id')->where('id', $songId)->get()->toArray();;
        $data[] = ['song_id'=> $songId];
        $return = self::formatedListForMusicPlayer($data, 'first');
        // $return = self::formatedListForMusicPlayer($data, 'first');
        return $return;
    }
}
