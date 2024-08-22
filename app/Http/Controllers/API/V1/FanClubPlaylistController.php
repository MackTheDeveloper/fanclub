<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Fan;
use App\Models\User;
use App\Models\Playlists;
use App\Models\DynamicGroups;
use App\Models\DynamicGroupItems;
use Validator;
use Mail;
use Hash;

class FanClubPlaylistController extends BaseController
{
    public function detail($id)
    {
        $authId = User::getLoggedInId();
        $data = Playlists::getDetailApi($id);        
        $return = ["componentId"=> "fanClubPlaylistDetails",
                  "sequenceId"=> "1",
                  "isActive"=> "1",
                  "fanClubPlaylistDetailData"=>$data
                ];
        return $this->sendResponse($return, 'fanclub Playlist Details Listed Successfully.');
    }

    public function playlistindex()
    {
        $authId = User::getLoggedInId();
        $data = Playlists::getListApi();
        $return = ["componentId"=> "fanclubplaylist",
                  "sequenceId"=> "1",
                  "isActive"=> "1",
                  "fanclubplaylistData"=>$data
                ];
        return $this->sendResponse($return, 'fanclub Playlist listed successfully.');
    }

}
