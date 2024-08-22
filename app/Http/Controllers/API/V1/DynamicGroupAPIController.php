<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DynamicGroups;
use App\Models\Songs;
use Validator;
use Mail;
use Hash;
use DB;

class DynamicGroupAPIController extends BaseController
{
    public function index($search="")
    {
        $dynamicGroupsData = DynamicGroups::searchAPIDynamicGroups($search);
        $return[] = [
            "componentId" => "fanclubPlaylist",
            "sequenceId" => "1",
            "page" => Songs::getPageById(2),
            "isActive" => "1",
            "fanclubPlaylistData" => $dynamicGroupsData
        ];
        return $this->sendResponse($return, 'fanclub playlist listed successfully.');
    }
}
