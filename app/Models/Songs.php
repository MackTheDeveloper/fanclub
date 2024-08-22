<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Storage;
use DB;

class Songs extends Model
{
    use SoftDeletes;
    protected $table = 'songs';

    protected $fillable = ['name', 'artist_id', 'file_type', 'file', 'icon', 'duration', 'release_date', 'num_likes', 'num_views', 'num_downloads', 'categories', 'languages', 'genre', 'status', 'tag',  'thumbnail', 'num_streams'];

    public function reviews()
    {
        // return $this->belongsTo(Reviews::class);
        return $this->hasMany(Reviews::class, 'song_id', 'id');
    }

    public function artist()
    {
        return $this->hasOne(User::class, 'id', 'artist_id');
    }

    public function activeArtist()
    {
        return $this->hasOne(User::class, 'id', 'artist_id')->where('is_verify',1)->where('is_active', 1);
    }

    public function getDurationAttribute($duration)
    {
        $return = TmpSongs::getSecToTime($duration);
        $return .= (strlen($return) > '5') ? " hour" : " min";
        return $return;
    }

    public static function getArtist($id)
    {
        $data = self::find($id);
        return $data ? $data->artist_id : '';
    }

    public static function getArtistAll()
    {
        $data = self::selectRaw('artist_id,CONCAT(firstname," ",lastname) as fullName')->leftjoin('users', 'users.id', 'songs.artist_id')->where('status', '1')->pluck('fullName', 'artist_id');
        return $data ? $data : [];
    }

    public static function getCountByArtist($id)
    {
        return self::where('artist_id', $id)->where('status', '1')->count();
    }

    public static function getSongs($id)
    {
        return self::where('artist_id', $id)->get();
    }

    public static function getOriginalSongUrl($id)
    {
        $return = "";
        $data = self::where('id', $id)->first();
        if ($data) {
            $return = $data->file;
        }
        return $return;
    }


    public static function getArtistSongViews($id)
    {
        $return = '0';
        $data = self::selectRaw('SUM(num_views) as views')->where('artist_id', $id)->first();
        if ($data) {
            $return = $data->views;
        }
        return $return;
    }

    public static function getArtistSongStreams($id)
    {
        $return = '0';
        $data = self::selectRaw('SUM(num_streams) as views')->where('artist_id', $id)->first();
        if ($data) {
            $return = $data->views;
        }
        return $return;
    }

    public static function getSongsByArtist($id, $limit = '', $filter = [])
    {
        $data = self::where('artist_id', $id)->where('status', '1')->whereNull('deleted_at');
        if ($limit != '') {
            $data->limit($limit);
        }
        if (isset($filter['orderBy'])) {
            $orderBy = explode('-', $filter['orderBy']);
            $field = $orderBy[0];
            $order = $orderBy[1];
            $data->orderBy($field, $order);
        } else {
            $data->orderBy('created_at', 'desc');
        }
        // $data->$orderBy;
        $data = $data->get();
        $return = [];
        foreach ($data as $key => $value) {
            // pre($value->artist);
            $return[] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "icon" => $value['icon'],
                "slug" => $value['slug'],
                "page" => self::getPageById(5),
                "download" => self::getDownloadUrl($value['id']),
                "artistId" => $value->artist->id,
                "artist" => $value->artist->firstname . ' ' . $value->artist->lastname,
                // "file" => $value['file'],
                "fileType" => $value['file_type'],
                "isVideo" => "1",
                "noLikes" => $value['num_likes'],
                "noViews" => $value['num_streams'],
                "navigate" => "1",
                "navigateType" => "15",
                "navigateTo" => "",
                "noDownloads" => $value['num_downloads'],
                "releaseDate" => getFormatedDate($value['release_date']),
            ];
        }
        return $return;
    }

    public static function getSongsList()
    {
        $return = self::selectRaw('id,name')->where('status', '1')->get();
        return $return;
    }

    public function getIconAttribute($icon)
    {
        $return = url('public/assets/frontend/img/placeholder/square_192_192.jpg');
        if (filter_var($icon, FILTER_VALIDATE_URL)) {
            $return = $icon;
        } else {
            $path = public_path() . '/assets/images/album/' . $icon;
            if (file_exists($path) && $icon) {
                $return = url('/public/assets/images/album/' . $icon);
            }
        }
        return $return;
    }

    public static function getIcon($icon)
    {
        $return = url('public/assets/frontend/img/placeholder/square_192_192.jpg');
        if (filter_var($icon, FILTER_VALIDATE_URL)) {
            $return = $icon;
        } else {
            $path = public_path() . '/assets/images/album/' . $icon;
            if (file_exists($path) && $icon) {
                $return = url('/public/assets/images/album/' . $icon);
            }
        }
        return $return;
    }

    public function  getCategoriesDataAttribute()
    {
        $return = "";
        $categories = $this->categories;
        if ($categories) {
            $categories = explode(',', $categories);
            $return = MusicCategories::whereIn('id', $categories)->pluck('name')->toArray();
            $return = implode(', ', $return);
        }
        return $return;
    }

    public function getLanguagesDataAttribute()
    {
        $return = "";
        $languages = $this->languages;
        if ($languages) {
            $languages = explode(',', $languages);
            $return = MusicLanguages::whereIn('id', $languages)->pluck('name')->toArray();
            $return = implode(', ', $return);
        }
        return $return;
    }

    public function getGenresDataAttribute()
    {
        $return = "";
        $genres = $this->genre;
        if ($genres) {
            $genres = explode(',', $genres);
            $return = MusicGenres::whereIn('id', $genres)->pluck('name')->toArray();
            $return = implode(', ', $return);
        }
        return $return;
    }

    public static function getSongAndArtistWithID($id)
    {
        return self::selectRaw('songs.*,users.firstname,users.lastname')
            ->where('songs.id', $id)->whereNull('songs.deleted_at')->whereNull('users.deleted_at')
            ->leftjoin('users', 'songs.artist_id', 'users.id')->first();
    }

    public static function searchAPISongs($search = "", $page = "")
    {
        $data = [];
        $limit = 0;
        $songsdata = self::has('activeArtist')->selectRaw('songs.*')->whereNull('songs.deleted_at');
        if ($search) {
            $songsdata->where(function ($query) use ($search) {
                $query->where('songs.name', 'like', '%' . $search . '%');
            });
        }
        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $songsdata->offset($offset);
            $songsdata->limit($limit);
        }
        $songsdata = $songsdata->get();
        if ($songsdata) {
            $data = self::filterSearch($songsdata);
        }
        $return = ['songsDetails' => $data, 'page' => $page, 'limit' => $limit];
        //return ['data' => $return, 'page' => $page, 'limit' => $limit];
        return $return;
    }

    public static function filterSearch($songsdata)
    {
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $return = [];
        foreach ($songsdata as $key => $value) {
            $return[] = [
                "navigate" => $navigate,
                "navigateType" => "15",
                "songId" => $value['id'],
                "page" => self::getPageById(5),
                "name" => $value['name'],
                "slug" => $value['slug'],
                "download" => self::getDownloadUrl($value['id']),
                "artistName" => $value->artist->firstname . ' ' . $value->artist->lastname,
                "icon" => Songs::getSongPhoto($value['id']),
                "fileType" => $value['file_type'],
                "duration" => $value['duration'],
                "category" => $value['categories_data'],
                "genre" => $value['genres_data'],
                "language" => $value['languages_data'],
                "status" => $value['status'],
                "releaseDate" => getFormatedDate($value['release_date']),
                "noLikes" => $value['num_likes'],
                "noViews" => $value['num_streams'],
                "noDownloads" => $value['num_downloads'],
            ];
        }
        return $return;
    }

    public static function getSongsAPIDetails($id)
    {
        $songsDetailData = self::getSongAndArtistWithID($id);
        if ($songsDetailData) {
            $data = self::formatedSongsDetail($songsDetailData);
        }
        $return = ['songsDetails' => $data];
        return $return;
    }

    public static function formatedSongsDetail($songsDetailData)
    {
        $return = [
            "name" => $songsDetailData['name'],
            "slug" => $songsDetailData['slug'],
            "artistName" => $songsDetailData->artist->firstname . ' ' . $songsDetailData->artist->lastname,
            "artistEmail" => $songsDetailData->artist->email,
            "artistPhone" => $songsDetailData->artist->phone,
            "artistAddress" => $songsDetailData->artist->address,
            "artistCountry" => $songsDetailData->artist->country,
            "fileType" => $songsDetailData['file_type'],
            "duration" => $songsDetailData['duration'],
            "icon" => $songsDetailData['icon'],
            "category" => $songsDetailData['categories_data'],
            "genre" => $songsDetailData['genres_data'],
            "language" => $songsDetailData['languages_data'],
            "status" => $songsDetailData['status'],
            "releaseDate" => getFormatedDate($songsDetailData['release_date']),
            "noLikes" => $songsDetailData['num_likes'],
            "noViews" => $songsDetailData['num_views'],
            "noDownloads" => $songsDetailData['num_downloads'],
        ];
        return $return;
    }

    public static function getFilteredList($page = "1", $search = "", $filter = [])
    {
        $limit = 0;
        $orderByLatest = 1;
        $returnData = [];
        $data = self::whereNull('deleted_at');
        if ($search) {
            // $data->where(function ($query) use ($search) {
            //     $query->where('users.firstname', 'like', '%' . $search . '%')
            //         ->orWhere('users.lastname', 'like', '%' . $search . '%')
            //         ->orWhere('forum_comments.comment', 'like', '%' . $search . '%');
            // });
            $data->where('name', 'like', '%' . $search . '%');
        }
        if (!empty($filter)) {
            $filterArr = ['categories', 'languages', 'genre'];
            foreach ($filter as $key => $value) {
                if (in_array($key, $filterArr) && $value) {
                    $data->whereRaw("FIND_IN_SET(" . $value . "," . $key . ") > 0");
                } elseif ($key == 'sort') {
                    if ($value == 'latest') {
                        $orderByLatest = 0;
                        $data->orderBy("created_at", "DESC");
                    } elseif ($value == 'old') {
                        $orderByLatest = 0;
                        $data->orderBy("created_at", "ASC");
                    } elseif ($value == 'name_asc') {
                        $orderByLatest = 0;
                        $data->orderBy("name", "ASC");
                    } elseif ($value == 'name_desc') {
                        $orderByLatest = 0;
                        $data->orderBy("name", "DESC");
                    }
                } else {
                    $data->where($key, $value);
                }
            }
        }
        if ($orderByLatest) {
            $data->orderBy('created_at', "DESC");
        }

        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get();
        if ($data) {
            $returnData = self::formatFilteredList($data);
            // pre($returnData);
        }
        return ['data' => $returnData, 'page' => $page, 'limit' => $limit];
    }

    public static function getFilteredReviewList($page = "1", $search = "", $filter = [])
    {
        $limit = 0;
        $orderByLatest = 1;
        $returnData = [];
        $data = self::whereNull('deleted_at')->has('reviews');
        if ($search) {
            $data->where('name', 'like', '%' . $search . '%');
        }
        if (!empty($filter)) {
            $filterArr = ['categories', 'languages', 'genre'];
            foreach ($filter as $key => $value) {
                if (in_array($key, $filterArr) && $value) {
                    $data->whereRaw("FIND_IN_SET(" . $value . "," . $key . ") > 0");
                } elseif ($key == 'sort') {
                    if ($value == 'latest') {
                        $orderByLatest = 0;
                        $data->orderBy("created_at", "DESC");
                    } elseif ($value == 'old') {
                        $orderByLatest = 0;
                        $data->orderBy("created_at", "ASC");
                    } elseif ($value == 'name_asc') {
                        $orderByLatest = 0;
                        $data->orderBy("name", "ASC");
                    } elseif ($value == 'name_desc') {
                        $orderByLatest = 0;
                        $data->orderBy("name", "DESC");
                    }
                } else {
                    $data->where($key, $value);
                }
            }
        }
        if ($orderByLatest) {
            $data->orderBy('created_at', "DESC");
        }

        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get();
        if ($data) {
            $returnData = self::formatFilteredList($data);
            // pre($returnData);
        }
        return ['data' => $returnData, 'page' => $page, 'limit' => $limit];
    }

    public static function formatFilteredList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "id" => $value['id'],
                "name" => $value['name'],
                "slug" => $value['slug'],
                "page" => self::getPageById(5),
                "download" => self::getDownloadUrl($value['id']),
                "fileType" => $value['file_type'],
                "icon" => $value['icon'],
                "duration" => $value['duration'],
                "category" => $value['categories_data'],
                "genre" => $value['genres_data'],
                "language" => $value['languages_data'],
                "status" => $value['status'],
                "artistName" => Artist::getArtistNameById($value['artist_id']),
                "artistId" => $value['artist_id'],
                "releaseDate" => getFormatedDate($value['release_date']),
                "noLikes" => $value['num_likes'],
                "noViews" => $value['num_streams'],
                "noDownloads" => $value['num_downloads'],
            ];
        }
        return $return;
    }

    public static function uploadIcon($fileObject)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;

        // Storage Upload
        $filePath = 'icons/' . $filename;
        $upload = Storage::disk('s3')->put($filePath, file_get_contents($photo), 'public');

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0)
            Admin::compressImages('s3', $filePath);

        return Storage::disk('s3')->url($filePath);
        // return $filename;
    }

    public static function uploadIconEncoded($songIconBase64)
    {
        $image_parts = explode(";base64,", $songIconBase64);
        $ext = str_replace('data:image/', '', $image_parts[0]);
        $imageName = rand() . '_' . time() . '.' . $ext;
        $image_base64 = base64_decode($image_parts[1]);

        // Storage Upload
        $filePath = 'icons/' . $imageName;
        $upload = Storage::disk('s3')->put($filePath, $image_base64, 'public');

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0)
            Admin::compressImages('s3', $filePath);

        return Storage::disk('s3')->url($filePath);
        // return $imageName;
    }

    public static function uploadSong($fileObject)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;

        // Storage Upload
        $filePath = 'images/' . $filename;
        $upload = Storage::disk('s3')->put($filePath, file_get_contents($photo), 'public');
        return Storage::disk('s3')->url($filePath);
    }

    public static function uploadSongTest($fileObject)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = 'song_from_test' . '_' . time() . '.' . $ext;

        // Storage Upload
        $filePath = 'images-test/' . $filename;
        $upload = Storage::disk('s3')->put($filePath, file_get_contents($photo), 'public');
        return Storage::disk('s3')->url($filePath);
    }

    public static function uploadSongEncoded($songIconBase64)
    {
        $image_parts = explode(";base64,", $songIconBase64);
        $ext = str_replace('data:image/', '', $image_parts[0]);
        $imageName = rand() . '_' . time() . '.' . $ext;
        $image_base64 = base64_decode($image_parts[1]);

        // Normal Upload
        // $imageFullPath = public_path() . '/assets/images/user_posts/' . $imageName;
        // file_put_contents($imageFullPath, $image_base64);

        // Storage Upload
        $filePath = 'images/' . $imageName;
        $upload = Storage::disk('s3')->put($filePath, $image_base64, 'public');
        return Storage::disk('s3')->url($filePath);
        // return $imageName;
    }

    public static function getNameById($id)
    {
        $return = self::withTrashed()->where('id', $id)->pluck('name')->first();
        return $return;
    }

    public static function getIconById($id)
    {
        $return = self::where('id', $id)->pluck('icon')->first();
        return $return;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('songs.id,name,CONCAT(firstname," ",lastname) as fullName')->leftjoin('users', 'users.id', 'songs.artist_id')
            ->where('status', '1')
            ->where('users.is_active', '1')
            ->where('users.is_verify', '1')
            ->whereNull('users.deleted_at')
            ->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        if ($limit) {
            $return->limit($limit);
        }
        if ($operation == 'getTotal') {
            $return = $return->count();
        } else {
            $return = $return->get();
        }
        return $return;
    }

    public static function checkValidArtist($songId)
    {
        $songsData = self::find($songId);
        $return  = 0;
        $authId = User::getLoggedInId();
        if ($authId == $songsData->artist_id) {
            $return = 1;
        }
        return $return;
    }

    public static function getFile($id)
    {
        $return = "";
        $authId = User::getLoggedInId();
        if ($authId) { }
        return $return;
    }

    public static function getSongPhoto($songId)
    {
        $oldData = self::where('id', $songId)->first();
        if ($oldData) {
            return $oldData->icon;
        }
    }
    public static function getSongsByGenre($id, $limit = "", $page = "")
    {
        $return = [];
        //$data = self::where('genre',$id);
        $data = self::whereRaw("FIND_IN_SET($id,genre)");
        if ($page) {
            $limit = 6;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedListNew($data);
        return ['data' => $return, 'page' => $page, 'limit' => $limit];
    }

    public static function getSongsByCategories($id, $limit = "", $page = "")
    {
        $return = [];
        //$data = self::where('genre',$id);
        $data = self::has('activeArtist')->whereRaw("FIND_IN_SET($id,categories)");
        if ($page) {
            $limit = 6;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = self::formatedListNew($data);
        return ['data' => $return, 'page' => $page, 'limit' => $limit];
    }

    public static function formatedListNew($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $songData = Songs::has('activeArtist')->where('id', $value['id'])->first();
            //$artistData = Artist::where('id', $songData->artist_id)->first();
            // $artistData = MusicGenres::where('id', $songData->genre)->first();
            if ($songData) {
                $artistFullname = $songData->artist->firstname . ' ' . $songData->artist->lastname;
                $return[] = [
                    "songId" => $value['id'],
                    "songName" => $songData->name,
                    "songSlug" => $songData->slug,
                    "page" => self::getPageById(5),
                    "download" => self::getDownloadUrl($value['id']),
                    "duration" => $songData->duration,
                    "artistName" => $artistFullname,
                    "songIcon" => Songs::getSongPhoto($value['id']),
                    "artistName" => $artistFullname,
                    "createdAt" => getFormatedDate($value['created_at']),
                ];
            }
        }
        return $return;
    }

    public static function getVideoQuality($songId)
    {
        $supportedMime = getSupportedMime(getBrowser());
        $return = [];
        $data = SongVariants::selectRaw('resolution')->where('song_id', $songId)->where('type', '!=', 'mp3')->where('type', $supportedMime)->groupBy('resolution')->orderByRaw('CAST(resolution AS SIGNED) DESC')->get();
        // pre($data->toArray());
        foreach ($data as $key => $value) {
            $return[] = ['key' => $value->resolution, 'value' => $value->resolution . 'p'];
        }
        // $return[] = ['key' => 'auto', 'value' => "Auto"];
        $return = [
            "quality" => $return,
            "playbackSpeed" => [
                [
                    "key" => '0.25',
                    "value" => '0.25',
                ],
                [
                    "key" => '0.5',
                    "value" => '0.5',
                ],
                [
                    "key" => '0.75',
                    "value" => '0.75',
                ],
                [
                    "key" => '1',
                    "value" => '1',
                ],
                [
                    "key" => '1.25',
                    "value" => '1.25',
                ],
                [
                    "key" => '1.5',
                    "value" => '1.5',
                ],
                [
                    "key" => '1.75',
                    "value" => '1.75',
                ],
                [
                    "key" => '2',
                    "value" => '2',
                ]
            ]
        ];
        // $return = ["quality" => ['720p', '480p', '360p', '240p']];
        return $return;
    }

    public static function getFanSongsForMusicPlayer($songId, $param = '')
    {
        $return = [];
        $data = self::selectRaw('songs.id as song_id')->where('id', $songId);
        if ($param) {
            $data = $data->limit(1)->get()->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, $param);
        } else {
            $data = $data->get()->toArray();
            $return = FanPlaylistSongs::formatedListForMusicPlayer($data, '');
        }
        return $return;
    }

    public static function getFanSuggestedSongsForMusicPlayer($artistId, $current = '')
    {
        $return = [];
        $data = self::selectRaw('songs.id as song_id')->where('artist_id', $artistId);
        if ($current) {
            $data->orderByRaw(DB::raw("FIELD(id, $current) desc"));
        }
        $data = $data->get()->toArray();
        $return = FanPlaylistSongs::formatedListForMusicPlayer($data, '');
        return $return;
    }

    public static function getPageById($id)
    {
        $return = "";
        $pagesArr = [
            "1" => "playlist",
            "2" => "dynamic-group",
            "3" => "my-favourite",
            "4" => "recent-played",
            "5" => "single-song-in-player",
        ];
        $return = isset($pagesArr[$id]) ? $pagesArr[$id] : "";
        return $return;
    }

    public static function getDownloadUrl($id)
    {
        $return = "";
        $authId = User::getLoggedInId();
        if ($authId) {
            $songData = Songs::where('id', $id)->first();
            if ($songData) {
                $resolution = SongVariants::getMaxResultion($songData->id);
                $type = 'mp4';
                $sendFile = $songData->file;
                if ($resolution) {
                    $resData = [];
                    if ($resolution == 'mp3') {
                        $type = "mp3";
                        $resData = SongVariants::where('song_id', $songData->id)->where('type', $type)->first();
                        if ($resData && isset($resData->url)) {
                            $sendFile = $resData->url;
                        }
                    } else {
                        $type = "mp4";
                        $resData = SongVariants::where('song_id', $songData->id)->where('resolution', $resolution)->where('type', $type)->first();
                        if ($resData && isset($resData->url)) {
                            $sendFile = $resData->url;
                        }
                    }
                }

                $return = $sendFile;
            }
        }
        return $return;
    }

    public static function getSongUrlAccess($songId, $type = "")
    {
        $maxResultion = SongVariants::getMaxResultion($songId);
        $songAccess = self::find($songId);
        if ($songAccess) {
            $songSlug = $songAccess->slug;
            if ($type) {
                return route('getSongAccess', [$songSlug, $type]);
            }
            return route('getSongAccess', [$songSlug, $maxResultion]);
        }
        // $sendFile = "";
        // $resolution = SongVariants::getMaxResultion($songId);
        // $songData = Songs::find($songId);
        // temporaryUrl
        // if ($type) {
        //     $type = "mp3";
        //     $resData = SongVariants::where('song_id', $songData->id)->where('type', $type)->first();
        //     if ($resData && isset($resData->url)) {
        //         $sendFile = $resData->url;
        //     }
        // }else{
        //     if ($resolution) {
        //         $resData = [];
        //         $type = getSupportedMime(getBrowser());
        //         // $type = "mp4";
        //         $resData = SongVariants::where('song_id', $songData->id)->where('resolution', $resolution)->where('type', $type)->first();
        //         if ($resData && isset($resData->url)) {
        //             $sendFile = $resData->url;
        //         }
        //     }
        // }
        // if ($sendFile) {
        //     $file = TmpSongs::getFileNameByUrl($sendFile);
        //     $url = Storage::disk('s3')->temporaryUrl(
        //         $file,
        //         now()->addMinutes(5)
        //     );
        //     return $url;
        // }
    }

    public static function formatDuration($duration)
    {
        $return = TmpSongs::getSecToTime($duration);
        $return .= (strlen($return) > '5') ? " hour" : " min";
        return $return;
    }

    public static function songHasCategory($categoryId)
    {
        $return = '0';
        $data = self::has('activeArtist')->selectRaw('count(*) as cnt')->whereRaw("FIND_IN_SET(?, categories) > 0", [$categoryId])->first();
        if ($data) {
            $return = $data->cnt;
        }
        return $return;
    }
}
