<?php

namespace App\Http\Controllers\API\V1;

use App\Models\ForumComments;
use App\Models\ForumFavouriteComment;
use App\Models\Forums;
use App\Models\UserProfilePhoto;
use App\Models\ForumFavourite;
use App\Models\User;
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

class ForumAPIController extends BaseController
{
    public function index(Request $request)
    { 
        $input = $request->all();
        $filter = isset($input['filter']) ? $input['filter'] : '';
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $data = Forums::getForumListApi($page, $search, $filter);
        $component = [
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
                            "id"=> "liked_desc",
                            "sort"=> "Most Helpful"
                        ],
                        [
                            "id"=> "name_asc",
                            "sort"=> "A-Z"
                        ],
                        [
                            "id"=> "name_desc",
                            "sort"=> "Z-A"
                        ]
                    ]
                ],
                [ 
                    "componentId" => "forumsearch",
                    "sequenceId" => "1",
                    "isActive" => "1",
                    "forumsearchData" => 
                    [
                        "title" =>  "Welcome to fanclub Forum",
                        "desc" => "Generate a discussion or share your thoughts, our artists and fans want to hear from you."
                    ] 
                ],
                [
                    "componentId" => "forumList",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    "pageSize" => (string) $data['limit'],
                    "pageNo" => (string) $data['page'],
                    "ForumListData" => ["list" => $data['forums']]
                ]
             
        ];
        return $this->sendResponse($component, 'Forums Listed Successfully.');
    }
    public function commentIndex(Request $request,$id)
    {
        $component = [];
        $authId = User::getLoggedInId();
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';
        $commentData = ForumComments::getForumCommentApi($page, $search,$id);
        // pre($commentData);
        $total_count = ForumComments::where('forum_id',$id)->get()->count();
        // $total_count = ForumComments::where('forum_id',$id)->where('created_by',$authId)->get()->count();
        if ($commentData['forumData']) {
            $component = [
                [
                    "componentId" => "forumDetails",
                    "sequenceId" => "1",
                    "isActive" => "1",
                    "forumDetailsData" => [
                        "id" => $id,
                        "title" => $commentData['forumData']->post_topic,
                        "desc" => $commentData['forumData']->description,
                        "image" => UserProfilePhoto::getProfilePhoto($commentData['forumData']->created_by),
                        "userName" => $commentData['forumData']->Created_By,
                        "created_at" =>$commentData['forumData']->created_at,
                        "createdAt" => getFormatedDate($commentData['forumData']->created_at),
                        "createdAtShow" => getFormatedDateForWeb($commentData['forumData']->created_at),
                        "noOfLikes" => $commentData['forumData']->no_likes,
                        "liked" =>  ForumFavourite::checkForumLiked($id),
                        "noOfComents" => $total_count,
                        "noOfAnswers" => $total_count .''." Answers.",
                    ]
                ],
                [
                    "componentId" => "forumComments",
                    "sequenceId" => "2",
                    "isActive" => "1",
                    // "pageSize" => "10",
                    "pageSize" => (string) $commentData['limit'],
                    "pageNo" => (string) $commentData['page'],
                    "forumCommentData" => ["list" => $commentData['forumsComment']]
                ]     
            ];
        }
        return $this->sendResponse($component, 'forum Comments Listed successfully.');
    }

    public function detailIndex($id)
    {
        $commentData = ForumComments::getForumOnlyFive($id);
        $forumData = $commentData['forumData'];
        
        $component = [
            [
                "componentId" => "forumDetails",
                "sequenceId" => "1",
                "isActive" => "1",
                "forumDetailsData" => 
                [
                    "topic" =>$forumData['post_topic'],
                    "description"=>$forumData['description'],
                    "status"=>$forumData['status'],
                    "noLikes"=>$forumData['no_likes'],
                    "createdBy" =>$forumData['created_by_name'],
                ] 
            ],
            [
                "componentId" => "forumDetailsAnswers",
                "sequenceId" => "2",
                "isActive" => "1",
                "forumDetailsAnswersData" => ["list" => $commentData['forumsComment']]
            ]
         
    ];
        return $this->sendResponse($component, 'forum Latest Detailed Listed successfully.');


    }
    public static function createNewTopic($request)
    {   
        $authId = User::getLoggedInId();
        if($authId)
        {
            $data = Forums::create($request);
        }
        return $data;
    }
    public static function createTopic(Request $request)
    {   
        $authId = User::getLoggedInId();
        if($authId)
        {
            $data = Forums::create($request);
        }
        return $data;
    }
    public static function createCommentMain(Request $request)
    {   
        $authId = User::getLoggedInId();
        if($authId)
        {
            $data = ForumComments::createComment($request);
        }
        return $data;
    }
     
     public function ForumIncreaseLike(Request $request)
     {
        $authId = User::getLoggedInId();
        $likeData = ForumFavourite::where('forum_id',$request->f_id)->where('user_id',$authId)->first();
        $forumData = Forums::where('id',$request->f_id)->first();
        $msgVal = true;
        $msg = "";
        $likes = 0;
        if($likeData)
        {
            $msgVal = true;
            $likeData->delete();
            
            $likes = $forumData->no_likes = ($forumData->no_likes!=NULL)?$forumData->no_likes-1:0;
            $forumData->save();
        }
        else
        {
            $msgVal = false;
            $forum = new ForumFavourite();
            $forum->user_id = $authId;
            $forum->forum_id = $request->f_id;
            $forum->save();
            
            $likes = $forumData->no_likes = ($forumData->no_likes!=NULL)?$forumData->no_likes+1:1;
            $forumData->save();
        }
        if($msgVal == true)
        {
            $msg = getResponseMessage('ForumTopicUnliked');
            return $this->sendResponse(['no_likes'=>$likes],$msg);
        }
        else
        {
            $msg = getResponseMessage('ForumTopicLiked');
            return $this->sendResponse(['no_likes'=>$likes],$msg);
        }
     }

     public function ForumCommentIncreaseLike(Request $request)
     {
        $authId = User::getLoggedInId();
        $likeData = ForumFavouriteComment::where('forum_comment_id',$request->f_id)->where('user_id',$authId)->first();
        $forumData = ForumComments::where('id',$request->f_id)->first();
        $msgVal = true;
        $msg = "";
        if($likeData)
        {
            $msgVal = true;
            $likeData->delete();
            $forumData->no_likes = ($forumData->no_likes!=NULL)?$forumData->no_likes-1:0;
            $total_likes = $forumData->no_likes;
            $forumData->save();
        }
        else
        {
            $msgVal = false;
            $forum = new ForumFavouriteComment();
            $forum->user_id = $authId;
            $forum->forum_comment_id = $request->f_id;
            $forum->save();
            
            $forumData->no_likes = ($forumData->no_likes!=NULL)?$forumData->no_likes+1:1;
            $total_likes = $forumData->no_likes;
            $forumData->save();
            
        }
        if($msgVal == true)
        {
            $msg = getResponseMessage('ForumCommentUnliked');
            return $this->sendResponse(['no_likes'=>$total_likes],$msg);
        }
        else
        {
            $msg = getResponseMessage('ForumCommentLiked');
            return $this->sendResponse(['no_likes'=>$total_likes],$msg);
        }
     }

    /*public function index(Request $request)
    {
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $search = isset($input['search']) ? $input['search'] : '';

        $authId = User::getLoggedInId();
        $data = UserPosts::getList($authId, $page, $search);
        $component = ["componentId" => "discoverList", "sequenceId" => "1", "isActive" => "1", "pageSize" => "10", "pageNo" => (string) $data['page'], "discoverListData" => ["list" => $data['posts']]];
        return $this->sendResponse($component, 'User Posts listed successfully.');
    }*/

}
