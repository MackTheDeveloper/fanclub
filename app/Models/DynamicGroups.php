<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class DynamicGroups extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable;
    use SoftDeletes;
    protected $table = 'dynamic_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'status', 'view_all', 'image_shape', 'allow_max', 'seo_title', 'seo_meta_keyword', 'seo_description'
    ];


    public static function searchAPIDynamicGroups($search = "")
    {
        $data = [];
        $groupdata = DynamicGroups::where('dynamic_groups.type', '2')->whereNull('dynamic_groups.deleted_at');
        if ($search) {
            $groupdata->where(function ($query) use ($search) {
                $query->where('dynamic_groups.name', 'like', '%' . $search . '%');
            });
        }
        $groupdata = $groupdata->orderBy('dynamic_groups.created_at', 'DESC')->get()->toArray();
        if ($groupdata) {
            $data = self::filterSearch($groupdata);
        }
        $return = ['groupDetail' => $data];
        return $return;
    }
    public static function filterSearch($groupdata)
    {
        $return = [];
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        foreach ($groupdata as $key => $value) {
            // pre($value);
            $return[] = [
                "groupId" => (string) $value['id'],
                "navigate" => "1",
                // "navigate" => $navigate,
                "navigateType" => "5",
                "page" => Songs::getPageById(2),
                "navigateTo" => "fanclub-group/" . $value['id'],
                "groupName" => $value['name'],
                "groupIcon" => DynamicGroupItems::getGroupIcon($value['id'], $value['view_all']),
                "groupSlug" => $value['slug'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public function groupItem()
    {
        return $this->hasMany(DynamicGroupItems::class, 'group_id', 'id');
    }

    public static function getDetailApi($id, $limit = "")
    {
        $return = [];
        $groupData = self::find($id);
        // pre($groupData);
        if ($groupData->view_all == 0) // Selected
        {
            $data = self::has('groupItem')->has('groupItem.song')->has('groupItem.song.artist')->where('id', $id);
            if ($limit) {
                $data->limit($limit);
            }
            $data = $data->first();
            // pre($data->groupItem);
            if (!empty($data))
                $return = self::formatedList($data);
        } elseif ($groupData->view_all == 2) // Latest Performances
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->whereNull('songs.deleted_at')->orderBy('songs.created_at', 'DESC');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForOtherViewAllTypes($dynamicGroupData, $songsData);
        } elseif ($groupData->view_all == 3) // Trending Now
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*')->join('song_views', 'song_views.song_id', 'songs.id')->whereBetween('song_views.created_at', [\DB::raw('adddate(now(),-14)'), \DB::raw('now()')])->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForOtherViewAllTypes($dynamicGroupData, $songsData);
        } elseif ($groupData->view_all == 4) // Top Songs
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*')->join('song_views', 'song_views.song_id', 'songs.id')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForOtherViewAllTypes($dynamicGroupData, $songsData);
        }

        return $return;
    }

    public static function formatedListForOtherViewAllTypes($dynamicGroupData, $songsData)
    {
        $return = [
            "groupId" => $dynamicGroupData->id,
            "groupName" => $dynamicGroupData->name,
            "groupSlug" => $dynamicGroupData->slug,
            "seo_title" => $dynamicGroupData->seo_title,
            "seo_meta_keyword" => $dynamicGroupData->seo_meta_keyword,
            "seo_description" => $dynamicGroupData->seo_description,
            "page" => Songs::getPageById(2),
            // "groupIcon" => DynamicGroupItems::getGroupIcon($dynamicGroupData->id),
            "countTrack" => $dynamicGroupData->allow_max,
            "groupLike" => FanFavouriteGroups::checkGroupLiked($dynamicGroupData->id)
        ];
        $list = [];
        $groupIcon = "";
        foreach ($songsData as $key => $value) {
            $groupIcon = $groupIcon?: $value->icon;
            $list[] = [
                "songId" => $value->id,
                "songName" => $value->name,
                "page" => Songs::getPageById(5),
                "download" => Songs::getDownloadUrl($value->id),
                "artistName" => $value->artist->firstname . ' ' . $value->artist->lastname,
                "songIcon" => $value->icon,
                "songDuration" => $value->duration,
                "songSlug" => $value->slug,
                "songLike" => FanFavouriteSongs::checkSongLiked($value->id),
                "songVideoDownload" => route('getSongDownload', [$value->slug]),
            ];
        }
        $return['songList'] = $list;
        $countTrack = count($list);
        $return['countTrack'] = ($countTrack< $dynamicGroupData->allow_max) ? $countTrack: $dynamicGroupData->allow_max;
        $return['groupIcon'] = $groupIcon;
        return $return;
    }

    public static function formatedList($data)
    {
        $return = [
            "groupId" => $data['id'],
            "groupName" => $data['name'],
            "groupSlug" => $data['slug'],
            "seo_title" => $data['seo_title'],
            "seo_meta_keyword" => $data['seo_meta_keyword'],
            "seo_description" => $data['seo_description'],
            "page" => Songs::getPageById(2),
            "groupIcon" => DynamicGroupItems::getGroupIcon($data['id']),
            "countTrack" => count($data->groupItem),
            "groupLike" => FanFavouriteGroups::checkGroupLiked($data['id'])
        ];
        $list = [];
        foreach ($data->groupItem as $key => $value) {
            if ($value->song) {
                $list[] = [
                    "songId" => $value->song->id,
                    "songName" => $value->song->name,
                    "page" => Songs::getPageById(5),
                    "download" => Songs::getDownloadUrl($value->song->id),
                    "artistName" => $value->song->artist->firstname . ' ' . $value->song->artist->lastname,
                    "songIcon" => $value->song->icon,
                    "songDuration" => $value->song->duration,
                    "songSlug" => $value->song->slug,
                    "songLike" => FanFavouriteSongs::checkSongLiked($value->song->id),
                    "songVideoDownload" => route('getSongDownload', [$value->song->slug]),
                ];
            }
        }
        $return['songList'] = $list;
        return $return;
    }

    public static function getDynamicGroupDetail($id)
    {
        return self::has('groupItem')->where('id', $id)->first();
    }

    public static function getAttrById($id, $attr = "")
    {
        $return = $id;
        $data = self::where('id', $id)->first();
        if ($data && isset($data->$attr)) {
            $return = $data->$attr;
        }
        return $return;
    }

    public static function getDataForFooter($ids)
    {
        $data = self::selectRaw('id,name,slug')->whereNull('deleted_at')->whereIn('id', explode(',', $ids))->get();
        $return = array();
        foreach ($data as $k => $v) {
            $url = self::getDyamicGroupDataFromType($v->id, 'getUrl');
            if ($url) {
                $return[$v->name] = $url;
            }
        }
        return $return;
    }

    public static function getDyamicGroupDataFromType($id, $slug)
    {
        $data = self::where('id', $id)->first();
        if ($slug == 'getUrl') {
            if ($data->type == '1') //Artist
            {
                $url = url('artists/' . $data['slug']);
            } else if ($data->type == '2') // Song
            {
                if ($data->view_all) {
                    $url = url('songs/' . $data['slug']);
                }elseif(!empty($data->groupItem[0]->song)){
                    // pre($data->toArray(),1);
                    $url = url('songs/' . $data['slug']);
                }else{
                    $url = '';
                }                
            } else if ($data->type == '3') //Genre
            {
                $url = '';
            } else if ($data->type == '4') {
                $url = '';
            } else if ($data->type == '5') {
                $url = '';
            }
            return $url;
        }
    }

    public static function getFanSongsForMusicPlayer($id, $param = '')
    {
        $return = [];

        $groupData = self::find($id);
        if ($groupData->view_all == 0) // Selected
        {
            $data = self::has('groupItem')->where('id', $id);
            $data = $data->first();

            $return = self::formatedListForMusicPlayer($data, $param);
        } elseif ($groupData->view_all == 2) // Latest Performances
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->whereNull('songs.deleted_at')->orderBy('songs.created_at', 'DESC');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            if ($param)
                $songsData = $songsData->first();
            else
                $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForMusicPlayerForOtherViewAllTypes($songsData, $param);
        } elseif ($groupData->view_all == 3) // Trending Now
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*')->join('song_views', 'song_views.song_id', 'songs.id')->whereBetween('song_views.created_at', [\DB::raw('adddate(now(),-14)'), \DB::raw('now()')])->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            if ($param)
                $songsData = $songsData->first();
            else
                $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForMusicPlayerForOtherViewAllTypes($songsData, $param);
        } elseif ($groupData->view_all == 4) // Top Songs
        {
            $dynamicGroupData = self::find($id);
            $songsData = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*')->join('song_views', 'song_views.song_id', 'songs.id')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id');
            $limit = $groupData->allow_max;
            if ($limit) {
                $songsData->limit($limit);
            }
            if ($param)
                $songsData = $songsData->first();
            else
                $songsData = $songsData->get();
            if (!empty($songsData))
                $return = self::formatedListForMusicPlayerForOtherViewAllTypes($songsData, $param);
        }
        return $return;
    }

    public static function formatedListForMusicPlayerForOtherViewAllTypes($data, $param)
    {
        $return = [];
        if ($param) {
            //foreach ($data->groupItem as $key => $value) {
            $songId = $data->id;
            $songData = Songs::where('id', $songId)->first();
            $artistData = Artist::where('id', $songData->artist_id)->first();
            if($artistData){
                $artistFullname = $artistData->firstname;
                $maxResultion = SongVariants::getMaxResultion($songId);
                $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($songId, $maxResultion);
                $return['data'] = [
                    "songId" => $songId,
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
                    // "songVideo" => route('getSongAccess', [$songData->slug, $maxResultion]),
                    // "songAudio" => route('getSongAccess', [$songData->slug, 'mp3']),
                    "songVideo" => Songs::getSongUrlAccess($songId),
                    "currentResolution" => $maxResultion,
                    "songAudio" => Songs::getSongUrlAccess($songId, 'mp3'),
                    "s3AudioUrl" => SongVariants::getAudioURL($songId),
                    "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                    "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                    "s3VideoUrl" => $maxResultionSongUrl,
                    "songLike" => FanFavouriteSongs::checkSongLiked($songId),
                    "activePlayingClass" => 'activePlaying'
                ];
                $return['generalReviews'] = Reviews::getFirstSongReviewDataForMusicPlayer($songId);
                $return['allReviews'] = Reviews::getSongAllReviewsDataForMusicPlayer($songId);
            }
            //}
        } else {
            $i = 1;
            foreach ($data as $key => $value) {
                $songData = Songs::where('id', $value->id)->first();
                $artistData = Artist::where('id', $songData->artist_id)->first();
                if ($artistData) {
                    $artistFullname = $artistData->firstname;
                    $maxResultion = SongVariants::getMaxResultion($value->id);
                    $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($value->id, $maxResultion);
                    $return['list'][] = [
                        "songId" => $value->id,
                        "songName" => $songData->name,
                        "songSlug" => $songData->slug,
                        "duration" => $songData->duration,
                        "artistName" => $artistFullname,
                        "artistSlug" => $artistData->slug,
                        "artistIcon" => UserProfilePhoto::getProfilePhoto($songData->artist_id),
                        "songIcon" => $songData->icon,
                        "songPlaceholder" => url('public/assets/frontend/img/video-placeholder2.png'),
                        //"songVideo" => $songData->file,
                        // "songVideo" => route('getSongAccess', [$songData->slug, $maxResultion]),
                        // "songAudio" => route('getSongAccess', [$songData->slug, 'mp3']),
                        "songVideo" => Songs::getSongUrlAccess($value->id),
                        "songAudio" => Songs::getSongUrlAccess($value->id, 'mp3'),
                        "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                        "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                        "s3VideoUrl" => $maxResultionSongUrl,
                        "s3AudioUrl" => SongVariants::getAudioURL($value->id),
                        "songLike" => FanFavouriteSongs::checkSongLiked($value->id),
                        "activePlayingClass" => $i == 1 ? 'activePlaying' : ''
                    ];
                    $i++;
                }
            }
        }

        return $return;
    }

    public static function formatedListForMusicPlayer($data, $param)
    {
        $return = [];
        if ($param) {
            //foreach ($data->groupItem as $key => $value) {
            $songId = $data->groupItem->first()->item_id;
            $songData = Songs::where('id', $songId)->first();
            $artistData = Artist::where('id', $songData->artist_id)->first();
            $artistFullname = $artistData->firstname;
            $maxResultion = SongVariants::getMaxResultion($songId);
            $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($songId, $maxResultion);
            $return['data'] = [
                "songId" => $songId,
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
                "songVideo" => Songs::getSongUrlAccess($songId),
                "songAudio" => Songs::getSongUrlAccess($songId, 'mp3'),
                "s3AudioUrl" => SongVariants::getAudioURL($songId),
                "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                "s3VideoUrl" => $maxResultionSongUrl,
                "songLike" => FanFavouriteSongs::checkSongLiked($songId),
                "activePlayingClass" => 'activePlaying'
            ];
            $return['generalReviews'] = Reviews::getFirstSongReviewDataForMusicPlayer($songId);
            $return['allReviews'] = Reviews::getSongAllReviewsDataForMusicPlayer($songId);
            //}
        } else {
            $i = 1;
            foreach ($data->groupItem as $key => $value) {
                if (!empty($value->song->id)) {
                    $songData = Songs::where('id', $value->song->id)->first();
                    $artistData = Artist::where('id', $songData->artist_id)->first();
                    $artistFullname = $artistData->firstname;
                    $maxResultion = SongVariants::getMaxResultion($value->song->id);
                    $maxResultionSongUrl = SongVariants::getUrlOfMaxResultion($value->song->id, $maxResultion);
                    $return['list'][] = [
                        "songId" => $value->song->id,
                        "songName" => $songData->name,
                        "songSlug" => $songData->slug,
                        "duration" => $songData->duration,
                        "artistName" => $artistFullname,
                        "artistSlug" => $artistData->slug,
                        "artistIcon" => UserProfilePhoto::getProfilePhoto($songData->artist_id),
                        "songIcon" => $songData->icon,
                        "songPlaceholder" => url('public/assets/frontend/img/video-placeholder2.png'),
                        //"songVideo" => $songData->file,
                        "songVideo" => route('getSongAccess', [$songData->slug, $maxResultion]),
                        "songAudio" => route('getSongAccess', [$songData->slug, 'mp3']),
                        "songVideoDownload" => route('getSongDownload', [$songData->slug]),
                        "songAudioDownload" => route('getSongDownload', [$songData->slug]),
                        "s3VideoUrl" => $maxResultionSongUrl,
                        "s3AudioUrl" => SongVariants::getAudioURL($value->song->id),
                        "songLike" => FanFavouriteSongs::checkSongLiked($value->song->id),
                        "activePlayingClass" => $i == 1 ? 'activePlaying' : ''
                    ];
                }
                $i++;
            }
        }

        return $return;
    }


    public static function getDyamicGroupNavigate($id)
    {
        $url = "";
        $data = self::where('id', $id)->first();
        if ($data) {
            if ($data->type == '1') //Artist
            {
                if ($data->view_all=="1") {
                    $url = 'all-artists';
                }else{
                    $url = 'artists/' . $id;
                }
            } else if ($data->type == '2') // Song
            {
                $url = 'fanclub-group/' . $id;
            } else if ($data->type == '3') //Genre
            {
                $url = '';
            } else if ($data->type == '4') {
                $url = '';
            } else if ($data->type == '5') {
                $url = '';
            }
        }
        return $url;
    }

    public static function getNavigateType($id)
    {
        $return = "";
        $data = self::where('id', $id)->first();
        if ($data) {
            if ($data->type == '1') //Artist
            {
                $return = '12';
            } else if ($data->type == '2') // Song
            {
                $return = '14';
            } else if ($data->type == '3') //Genre
            {
                $return = '0';
            } else if ($data->type == '4') {
                $return = '0';
            } else if ($data->type == '5') {
                $return = '0';
            }
        }
        return $return;
    }

    public static function getNavigateFlag($id)
    {
        $return = "";
        $data = self::where('id', $id)->first();
        if ($data) {
            if ($data->type == '1') //Artist
            {
                $return = '1';
            } else if ($data->type == '2') // Song
            {
                $return = '1';
            } else if ($data->type == '3') //Genre
            {
                $return = '0';
            } else if ($data->type == '4') {
                $return = '0';
            } else if ($data->type == '5') {
                $return = '0';
            }
        }
        return $return;
    }

    public static function getAttrImageShape($id)
    {
        $return = "";
        $data = self::where('id', $id)->first();
        if ($data) {
            $return = $data->image_shape;
            switch ($return) {
                case '1':
                    $return = 'Square';
                    break;
                case '2':
                    $return = 'Circle';
                    break;
                case '3':
                    $return = 'Rectangle';
                    break;
            }
        }
        return $return;
    }
}
