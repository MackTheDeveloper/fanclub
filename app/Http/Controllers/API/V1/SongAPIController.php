<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Artist;
use App\Models\Forums;
use App\Models\Songs;
use App\Models\User;
use App\Models\SongViews;
use App\Models\FanPlaylist;
use App\Models\FanFavouriteGroups;
use App\Models\FanFavouriteArtists;
use App\Models\FanFavouriteSongs;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Frontend\CloudConvertController;
use App\Models\DynamicGroups;
use App\Models\RecentPlayed;
use App\Models\SongVariants;
use App\Models\TmpSongs;
use Validator;
use Mail;
use Hash;

class SongAPIController extends BaseController
{
    public function filteredList(Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $filter = isset($input['filter']) ? $input['filter'] : [];
        $data = Songs::getFilteredList($page, $search, $filter);
        $component = [
            "componentId" => "songFiltered",
            "sequenceId" => "1",
            "isActive" => "1",
            "pageSize" => $data['limit'],
            "pageNo" => (string) $data['page'],
            "songFilteredData" => $data['data'],
        ];
        return $this->sendResponse($component, 'Song Listed Successfully.');
    }

    public function artistSongs(Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $filter = isset($input['filter']) ? $input['filter'] : [];
        $filter['artist_id'] = User::getLoggedInId();
        $data = Songs::getFilteredList($page, $search, $filter);
        $sortFilterBar = [
            "componentId" => "sortFilterBar",
            "sequenceId" => "1",
            "isActive" => "1",
            "sortTitleData" => [
                [
                    "id" => "latest",
                    "sort" => "Most Recent"
                ],
                [
                    "id" => "old",
                    "sort" => "Old"
                ],
                [
                    "id" => "name_asc",
                    "sort" => "A-Z"
                ],
                [
                    "id" => "name_desc",
                    "sort" => "Z-A"
                ]
            ]
        ];
        $songFiltered = [
            "componentId" => "songFiltered",
            "sequenceId" => "1",
            "isActive" => "1",
            "pageSize" => $data['limit'],
            "pageNo" => (string) $data['page'],
            "songFilteredData" => $data['data'],
        ];
        $component = [$sortFilterBar, $songFiltered];
        return $this->sendResponse($component, 'Song Listed Successfully.');
    }

    public function filteredReviewList(Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $filter = isset($input['filter']) ? $input['filter'] : [];
        $data = Songs::getFilteredReviewList($page, $search, $filter);
        $component = [
            "componentId" => "songFiltered",
            "sequenceId" => "1",
            "isActive" => "1",
            "pageSize" => $data['limit'],
            "pageNo" => (string) $data['page'],
            "songFilteredData" => $data['data'],
        ];
        return $this->sendResponse($component, 'Song Listed Successfully.');
    }

    public function filteredReviewListArtist(Request $request)
    {
        $input = $request->all();
        $authId = User::getLoggedInId();
        $input['filter']['artist_id'] = $authId;
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $filter = isset($input['filter']) ? $input['filter'] : [];
        $data = Songs::getFilteredReviewList($page, $search, $filter);
        $component = [
            "componentId" => "songFiltered",
            "sequenceId" => "1",
            "isActive" => "1",
            "pageSize" => $data['limit'],
            "pageNo" => (string) $data['page'],
            "songFilteredData" => $data['data'],
        ];
        return $this->sendResponse($component, 'Song Listed Successfully.');
    }

    public function SongsDetails($id)
    {
        $data = Songs::getSongsAPIDetails($id);
        $component = [
            "componentId" => "SongsDetailsList",
            "sequenceId" => "1",
            "isActive" => "1",
            "SongsDetailsListData" => $data['songsDetails']
        ];
        return $this->sendResponse($component, 'Songs Details Listed Successfully.');
    }

    public function SongsIncreaseView(Request $request)
    {
        $input = $request->all();
        $songId = $input['song_id'];
        $authId = User::getLoggedInId();
        $songDetail = Songs::where('id', $songId)->first();
        if ($songDetail) {
            $songDetail->num_views = ($songDetail->num_views != NULL) ? $songDetail->num_views + 1 : 1;
            $songDetail->save();
            // SongViews::create([
            //     "song_id" => $songId,
            //     "viewer_id" => $authId
            // ]);
        }
        return $this->sendResponse([], 'Song view increased successfully.');
    }

    public function SongsAddToRecent(Request $request)
    {
        $input = $request->all();
        $songId = $input['song_id'];
        RecentPlayed::addNew(['song_id'=>$songId]);
        return $this->sendResponse([], 'Song added in recent played.');
    }

    public function SongsIncreaseStream(Request $request)
    {
        $input = $request->all();
        $songId = $input['song_id'];
        $authId = User::getLoggedInId();
        $songDetail = Songs::where('id', $songId)->first();
        if ($songDetail) {
            $songDetail->num_streams = ($songDetail->num_streams != NULL) ? $songDetail->num_streams + 1 : 1;
            $songDetail->save();
            SongViews::create([
                "song_id" => $songId,
                "viewer_id" => $authId
            ]);
        }
        return $this->sendResponse([], 'Song view increased successfully.');
    }

    public function SongCreate(Request $request)
    {
        $input = $request->all();
        $input['icon'] = '';
        $input['file'] = '';
        $authId = User::getLoggedInId();
        $validator = Validator::make(
            $input,
            [
                //'post_type' => 'required',
                //'category_id' => 'required',
                // 'image' => 'required',
                'song_icon' => 'mimes:jpeg,jpg,png,gif',
                'song_file' => 'required|mimes:mp4,webm,mov,mkv',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            // pre($request->file('song_file'));
            if ($request->hasFile('song_icon')) {
                if (isset($input['hiddenPreviewImg'])) {
                    $iconObject = $request->file('song_icon');
                    $input['icon'] = Songs::uploadIconEncoded($input['hiddenPreviewImg']);
                    unset($input['hiddenPreviewImg']);
                } else {
                    $iconObject = $request->file('song_icon');
                    $input['icon'] = Songs::uploadIcon($iconObject);
                }
            }
            if ($request->hasFile('song_file')) {
                $iconObject = $request->file('song_file');
                $input['file'] = Songs::uploadSong($iconObject);
            }
            // if (isset($input['songFileBase64'])) {
            //     $input['icon'] = Songs::uploadIconEncoded($input['songIconBase64']);
            // }else{
            //     if ($request->file('song_file')) {
            //         $iconObject = $request->file('song_file');
            //         $input['icon'] = Songs::uploadIcon($fileObject);
            //     }
            // }

            // remove specialChars 
            $input['name'] = alphaNumericOnly($input['name']);
            $VIDEO_TRANSCODE_STATUS = env('VIDEO_TRANSCODE_STATUS');
            if ($VIDEO_TRANSCODE_STATUS){
                $newSong = new TmpSongs();
            }else{
                $newSong = new Songs();
            }

            $newSong->artist_id = $authId;
            $newSong->name = $input['name'];
            $newSong->slug = getSlug($input['name'], "", 'songs', 'slug');
            $newSong->genre = !empty($input['genre']) ? implode(',',$input['genre']) : null;
            $newSong->categories = !empty($input['categories']) ? implode(',',$input['categories']) : null;
            // $newSong->tags = $input['tags'];
            $newSong->icon = $input['icon'];
            $newSong->file = $input['file'];
            $newSong->tag = $input['tag'];
            // $newSong->release_date = date('Y-m-d');
            $newSong->save();
            if ($newSong && $VIDEO_TRANSCODE_STATUS) {
                # code...
                $controller = new CloudConvertController();
                //patch by nivedita for search page see all//
                $fileName = TmpSongs::getFileNameByUrl($newSong->file);
                $data = $controller->index($fileName, $newSong->id);
                // Songs::uploadSong($input['file']);
            }

            return $this->sendResponse([], getResponseMessage('UplaodSongByArtist',$newSong->name));
        }
    }

    public function myMusic()
    {
        $authId = User::getLoggedInId();
        $myPlaylist = [
            "componentId" => "myPlaylist",
            "title" => "My Playlist",
            "sequenceId" => "1",
            "isActive" => "1",
            "myPlaylistData" => FanPlaylist::getListApi($authId, 10),
        ];
        $favPlaylist = [
            "componentId" => "favPlaylist",
            "title" => "Favourite Playlists",
            "sequenceId" => "1",
            "isActive" => "1",
            "favPlaylistData" => FanFavouriteGroups::getListApi($authId,"",[], 10),
        ];
        $favArtist = [
            "componentId" => "favArtist",
            "title" => "Favourite Artist",
            "sequenceId" => "1",
            "isActive" => "1",
            "favArtistData" => FanFavouriteArtists::getListApi($authId, "", [], 10),
        ];
        $myCollections = [
            "componentId" => "myCollections",
            "title" => "My Collections",
            "sequenceId" => "1",
            "isActive" => "1",
            "myCollectionsData" => FanFavouriteSongs::getListApi($authId, 10),
        ];
        $component = [$myPlaylist, $favPlaylist, $favArtist, $myCollections];
        return $this->sendResponse($component, 'My Music Listed Successfully.');
    }

    public function getSongsByDynamicGroup($id)
    {
        $songData = DynamicGroups::getDetailApi($id);
        $return = [
            "componentId" => "groupDetail",
            "sequenceId" => "1",
            "isActive" => "1",
            "groupDetailData" => $songData
        ];
        return $this->sendResponse($return, 'song data retrived successfully.');
    }

    public function allSongs($search='',$page="1")
    {
        //patch by nivedita for search page see all//
        $songdata = Songs::searchAPISongs($search,$page);
        $return = [
            "componentId"=> "song",
            "sequenceId"=> "3",
            "isActive"=> "1",
            "songData"=>$songdata,
            "pageSize" => $songdata['limit'],
            "pageNo" => (string) $songdata['page'],
        ];
        return $this->sendResponse($return, 'songs data retrived successfully.');
    }

    public function SongCreateTest(Request $request)
    {
        $input = $request->all();
        $filePath = public_path('/assets');
        $myfile = fopen($filePath . "/webhook.txt", "w");
        // $myfile = fopen($filePath . "/webhook.txt", "a");
        $fileName = $request->file->getClientOriginalName();
        fwrite($myfile, print_r($input, true));
        fwrite($myfile, print_r($fileName, true));
        fclose($myfile);
        $authId = User::getLoggedInId();
        $iconObject = $request->file('song_file');
        $return = Songs::uploadSongTest($iconObject);
        return $this->sendResponse($return, 'song uploaded successfully.');
    }

    public function getSongUrlById($id, $resolution)
    {
        $return = "";
        $type = "mp4";
        $data = SongVariants::where('song_id', $id)->where('type',$type)->where('resolution',$resolution)->first();
        // url
        if ($data) {
            $return = $data->url;
        }
        return $this->sendResponse($return, 'song retrived successfully.');
        // getUrlOfMaxResultion($songId, $resolution)
    }

    public function SongEdit($id)
    {
        $return = [];
        $newSong = Songs::find($id);
        $authId = User::getLoggedInId();
        if ($newSong) {
            $return = [
                "id"=> $newSong->id,
                "name"=> $newSong->name,
                "artistId"=> $newSong->artist_id,
                "icon"=> $newSong->icon,
                "categories"=> $newSong->categories,
                "languages"=> $newSong->languages,
                "genre"=> $newSong->genre,
                "tag"=> $newSong->tag,
            ];
        }
        return $this->sendResponse($return, 'Song Detail Retrived successfully');
    }

    public function SongUpdate(Request $request)
    {
        $input = $request->all();
        // pre($input);
        // $input['icon'] = '';
        $id = $input['song_id'];
        $authId = User::getLoggedInId();
        $validator = Validator::make(
            $input,
            [
                'song_id' => 'required',
                'song_icon' => 'mimes:jpeg,jpg,png,gif',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            $newSong = Songs::find($id);
            if ($authId== $newSong->artist_id) {
                if ($request->hasFile('song_icon')) {
                    if (isset($input['hiddenPreviewImg'])) {
                        $iconObject = $request->file('song_icon');
                        $input['icon'] = Songs::uploadIconEncoded($input['hiddenPreviewImg']);
                        unset($input['hiddenPreviewImg']);
                    } else {
                        $iconObject = $request->file('song_icon');
                        $input['icon'] = Songs::uploadIcon($iconObject);
                    }
                }
                if ($newSong->name != $input['name']) {
                    $name = alphaNumericOnly($input['name']);
                    $newSong->name = $name;
                    $newSong->slug = getSlug($name, "", 'songs', 'slug');
                }
                $newSong->categories = !empty($input['categories']) ? implode(',', $input['categories']) : null;
                if (!empty($input['icon'])) {
                    $newSong->icon = $input['icon'];
                }
                $newSong->tag = $input['tag'];
                $newSong->save();
                return $this->sendResponse([], getResponseMessage('UpdateSongDetail', $newSong->name));
            }
            $msg = getResponseMessage('UpdateSongDetailFail');
            return $this->sendError($msg, $msg);
        }
    }
}
