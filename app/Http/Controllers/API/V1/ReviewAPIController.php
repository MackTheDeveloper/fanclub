<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Songs;
use App\Models\Artist;
use App\Models\ReviewUploads;
use Validator;
use Mail;
use Hash;

class ReviewAPIController extends BaseController
{
	public function index(Request $request)
    {
        $authId = User::getLoggedInId();
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $data = Reviews::userWiseData($authId,$request,$page,$search);
        $return = [
            [
                "componentId"=> "sortFilterBar",
                "sequenceId"=> "1",
                "isActive"=> "1",
                "sortTitleData"=> [
                    [
                        "id"=> "latest",
                        "sort"=> "Most Recent"
                    ],
                    [
                        "id"=> "old",
                        "sort"=> "Old"
                    ],
                    [
                        "id"=> "three-month",
                        "sort"=> "Last 3 Months"
                    ],
                    [
                        "id"=> "one-month",
                        "sort"=> "Last 1 Month"
                    ]
                ]
            ],
            [
                "title" => "Your Valueable Reviews",
                "desc" => $data['countable'] . " Reviews",
                "componentId"  => "myReviewList",
                "sequenceId" => "1",
                "isActive"=> "1",
                // "pageSize" => "10",
                "pageSize" => (string) $data['limit'],
                "pageNo" => (string) $data['page'],
                "myReviewListData" => ["list" => $data['data']]
            ]
        ];
        return $this->sendResponse($return, 'Reviews listed successfully.');
    }

    public function indexReviewSongs($songId,Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        //$artistid = Reviews::where('song_id',$songId)->pluck('artist_id')->first();
        $artistid = Songs::where('id',$songId)->pluck('artist_id')->first();
        $data = Reviews::getSongWiseData($songId,$page);
        $return = ["componentId"=> "reviewList",
                  "sequenceId"=> "1",
                  "isActive"=> "1",
                  "pageSize" => "10",
                  "pageNo" => (string) $data['page'],
									"songID" => $songId,
                  "songName" => Songs::getNameById($songId),
                  "songIcon" => Songs::getIconById($songId),
                  "artistName" => Artist::getNameById($artistid),
                  "totalReviews" => Reviews::getTotalCountData($songId),
                  "myReviewListData" => ["list" => $data['data']]
                ];
        return $this->sendResponse($return, 'Reviews listed successfully.');
    }

    public function indexReviewArtists($artistId)
    {
        $data = Reviews::getArtistWiseData($artistId);
        $return = ["componentId"=> "reviewList",
                  "sequenceId"=> "1",
                  "isActive"=> "1",
                  "averageRating" => Reviews::getAvgRatingOfArtists($artistId),
                  "myReviewsData"=>$data
                ];
        return $this->sendResponse($return, 'Reviews listed successfully.');
    }


    public function create(Request $request)
    {
        $val = [];
        if($request->song_id)
        {
           $data = Reviews::apiAddReviewsWithSong($request->song_id,$request->artist_id,$request->rating,$request->comment);
            if ($data)
            { return $this->sendResponse("[]", getResponseMessage('AddReview',$data['data']['song']['name'])); }
            else
            { return $this->sendError("[]", 'Something went wrong.'); }
        }
        else
        {
            $data = Reviews::apiAddReviews($request->artist_id,$request->rating,$request->comment);
            if ($data)
            { return $this->sendResponse("[]", getResponseMessage('AddReview',$data['data']['artist']['firstname'])); }
            else
            { return $this->sendError("[]", 'Something went wrong.'); }
        }
    }
    public function deleteReview(Request $request)
    {
        $validation = Reviews::where('id',$request->id)->first();
        $authId = User::getLoggedInId();
        if(isset($validation->customer_id) && $validation->customer_id == $authId)
        {
           $data = Reviews::apiDeleteReview($request->id);
            if ($data){ 
                $msg = getResponseMessage('RemoveReview',Reviews::getName($request->id));
                return $this->sendResponse([], $msg); 
            }else{ 
                return $this->sendError([], 'Something went wrong.'); 
            }
        }
        else
        {
            return $this->sendError([], 'Something went wrong.');
        }

    }

    public function edit($id)
    {
        $data = Reviews::getReviewById($id);
        return $this->sendResponse($data, 'Review retrived successfully.');
    }

     public function editReview(Request $request)
    {
        if($request->id)
        {
            $data = Reviews::apiEditReview($request);
            if ($data){
                $msg = getResponseMessage('UpdateReview',Reviews::getName($request->id));
                return $this->sendResponse([], $msg); 
            }else{ 
                return $this->sendError([], 'Something went wrong.'); 
            }
        }
    }
    public function getListOfAllReviews(Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $data = Reviews::getReviewIfExist($page,$search,$request);
        $return = ["componentId"=> "reviewList",
                  "sequenceId"=> "1",
                  "isActive"=> "1",
                  "pageSize" => "10",
                  "pageNo" => (string) $data['page'],
                  "title" => "Reviews of My Songs",
                  "myReviewListData" => ["list" => $data['forums']]
                ];
        return $this->sendResponse($return, 'Reviews listed successfully.');
    }
		public function rejectReview(Request $request)
    {
        $validation = Reviews::where('id',$request->reviewId)->first();
        $authId = User::getLoggedInId();
        if(isset($validation->artist_id) && $validation->artist_id == $authId)
        {
           $data = Reviews::apiRejectReview($request->reviewId);
            if ($data){
                $msg = getResponseMessage('ReviewUnapprovedByArtist',Reviews::getName($request->reviewId));
                return $this->sendResponse([], 'Review Rejected successfully.'); 
            }else{ 
                return $this->sendError([], 'Something went wrong.'); 
            }
        }
        else
        {
            return $this->sendError([], 'Something went wrong.');
        }

    }
 
}
