<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ForumComments;
use App\Models\Forums;
use App\Models\MusicCategories;
use App\Models\MusicGenres;
use App\Models\MusicLanguages;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\ReviewUploads;
use Validator;
use Mail;
use Hash;
use DB;

class MusicLanguageAPIController extends BaseController
{
    public function index(Request $request)
    {
        $input = $request->all();
        $search = isset($input['search']) ? $input['search'] : '';
        $data = MusicLanguages::getMusicLanguageList($search);
        $component = [
            "componentId" => "MusicLanguageList",
            "sequenceId" => "1",
            "isActive" => "1",
            "MusicLanguageListData" => [ "list" => $data['musicGenre'] ]
        ];

        return $this->sendResponse($component, 'Music Language Listed Successfully.');
    }


}
