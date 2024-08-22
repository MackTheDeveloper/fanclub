<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Artist;
use App\Models\Faqs;
use App\Models\FaqTags;
use App\Models\Songs;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\ReviewUploads;
use Validator;
use Mail;
use Hash;

class CountryAPIController extends BaseController
{

    public function index(Request $request)
    {
        $coutnryData = Country::getListForDropdown();
        $component =
            [
                "componentId" => "countryList",
                "sequenceId" => "1",
                "isActive" => "1",
                "countryListData" => [
                    "countries" => $coutnryData,
                ],
            ];
        return $this->sendResponse($component, 'FAQ listed successfully.');
    }

}
