<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ForumComments;
use App\Models\Forums;
use App\Models\MusicCategories;
use App\Models\MusicGenres;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\Songs;
use App\Models\ReviewUploads;
use Validator;
use Mail;
use Hash;
use DB;

class MusicGenreAPIController extends BaseController
{
    public function index(Request $request)
    {
        $input = $request->all();
        $search = isset($input['search']) ? $input['search'] : '';
        $data = MusicGenres::getMusicGenreListApi($search);
        $component = [
            "componentId" => "MusicGenreList",
            "sequenceId" => "1",
            "isActive" => "1",
            "MusicGenreListData" => ["list" => $data['musicGenre']]
        ];
        return $this->sendResponse($component, 'Music Genre Listed Successfully.');
    }

    public function getGenreById($id,$page="1")
    {
        //$page = isset($input['page']) ? $input['page'] : 1;
        $songtData = Songs::getSongsByGenre($id,'',$page);
        $genredata = MusicGenres::getGenreById($id);
        $return = [
            "componentId"=> "songList",
            "sequenceId"=> "1",
            "isActive"=> "1",
            "songData"=>$songtData,
            "genredata"=>$genredata,
            "total"=>count($songtData),
        ];
        $return['pageSize']=(string) $songtData['limit'];
        $return['pageNo']=(string) $songtData['page'];
        $return['GenreId']=$id;
        return $this->sendResponse($return, 'genre data retrived successfully.');
    }
}
