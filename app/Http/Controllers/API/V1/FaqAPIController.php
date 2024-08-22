<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Artist;
use App\Models\Faqs;
use App\Models\FaqTags;
use App\Models\Songs;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\ReviewUploads;
use Validator;
use Mail;
use Hash;

class FaqAPIController extends BaseController
{

    public function index(Request $request)
    {
        // $artistData = Faqs::getListByType('artist');
        // $fanData = Faqs::getListByType('fan');
        $faqs = Faqs::getListByType();
        $faqTags = FaqTags::getTagList();

        $faqTopComponent = [
            "componentId" => "faqTopComponent",
            "sequenceId" => "1",
            "isActive" => "1",
            "faqTopComponentData" => [
                "title"=>"How can I help you?",
                "fan"=>"Fan",
                "artist"=>"Artist",
                "isArtistSelected"=>"0"                    
            ]
        ];
        $faqCategory = [
            "componentId" => "faqCategory",
            "sequenceId" => "1",
            "isActive" => "1",
            "faqCategoryData" => ["list"=>$faqTags]
        ];
        $faqList =
            [
                "componentId" => "faqList",
                "sequenceId" => "1",
                "isActive" => "1",
                "faqListData" => [
                    "list" => $faqs
                ],
            ];
        $component = [$faqTopComponent,$faqCategory,$faqList];
        return $this->sendResponse($component, 'FAQ listed successfully.');
    }

}
