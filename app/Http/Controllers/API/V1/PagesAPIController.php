<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Models\Blogs;
use Validator;
use Mail;
use Hash;
use App\Models\ProductCategories;
use App\Models\ProfessionalCategories;
use App\Models\LocationGroup;
use App\Models\Professionals;
use App\Models\User;
use App\Models\Country;
use App\Models\States;
use App\Models\UserCategoryPhoto;
use App\Models\UserLocationPhoto;
use App\Models\Products;
use App\Models\Variables;
use App\Models\UserProfilePhoto;
use Illuminate\Support\Facades\Session;

class PagesAPIController extends BaseController
{

	public function countryList(){
        $countries = Country::getListForDropdown();
        $component = [
            "componentId" => "country",
            "sequenceId" => "1",
            "isActive" => "1",
            "countryData" => ['list'=>$countries],
        ];
        return $this->sendResponse($component, 'Countries Listed Successfully.');
    }

    public function stateList($country){
        $states = States::getListForDropdown($country);
        $component = [
            "componentId" => "states",
            "sequenceId" => "1",
            "isActive" => "1",
            "statesData" => ['list'=>$states],
        ];
        return $this->sendResponse($component, 'States Listed Successfully.');
    }


    public function variableList(){
        $variables = Variables::getVariables();
        return $this->sendResponse($variables, 'Variables Listed Successfully.');
    }

}