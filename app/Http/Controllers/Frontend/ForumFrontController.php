<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\API\V1\FanAPIController;
use App\Http\Controllers\API\V1\ForumAPIController;
use App\Http\Controllers\Controller;
use App\Models\ForumComments;
use App\Models\Forums;
use App\Models\User;
use App\Models\ForumFavourite;
use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Http\Controllers\API\V1\ArtistAPIController;
use App\Http\Controllers\API\V1\SongAPIController;
use App\Models\GlobalSettings;
use Exception;
use Validator;
use Session;
use Auth;
use Mail;
use DB;
use Response;

class ForumFrontController extends Controller
{
	public function index(Request $request){
        $api = new ForumAPIController();
        $data = $api->index($request);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        //pre($content);
        $seo_title = GlobalSettings::getSingleSettingVal('forums_seo_title');
        $seo_meta_keyword = GlobalSettings::getSingleSettingVal('forums_seo_meta_keyword');
        $seo_description = GlobalSettings::getSingleSettingVal('forums_seo_description');
		return view('frontend.forum.forum-list',compact('content', 'seo_title', 'seo_meta_keyword', 'seo_description'));
	}
    public function details(Request $request,$id)
    {
        $forum_data = Forums::where('id',$id)->where('status','1')->whereNull('deleted_at')->first();
        if ($forum_data) {
            $api = new ForumAPIController();
            $data = $api->commentIndex($request,$id);
            $data = $data->getData();
            $content = $data->component;
            // pre($content);
            $content = componentWithNameObject($content);
            return view('frontend.forum.forum-details',compact('content'));
        }else{
            abort(404, 'Page not found');
        }
    }

    public function ajaxForum(Request $request)
    {
        $api = new ForumAPIController();
        $data = $api->index($request);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        return view('frontend.forum.forum-load-more',compact('content'));
    }
 
    public function loadmoreComments(Request $request)
    {
        $api = new ForumAPIController();
        $data = $api->commentIndex($request,$request->id_val);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
        return view('frontend.forum.forum-load-more-comments',compact('content'));
        
    }
    function autocompleteSearch(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Forums::where('post_topic', 'LIKE', "%{$query}%")->get()->toArray();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            if (!empty($data)) {
                foreach($data as $row)
                {
                    $link = url('forum-detail/'.$row['id']);
                    $output .= '<li class="list-group-item"><a href="'.$link.'">'.$row['post_topic'].'</a></li>';
                }
            }else{
                $output .= '<li class="list-group-item"><a class="disabled" href="javascript:void(0)">No Matching forum topic found.</a></li>';
            }
        $output .= '</ul>';
        echo $output;
     }
    }
    

    public function search(Request $request)
    {
        $forum_total = Forums::where('status','1')->whereNull('deleted_at')->count();
        $url = str_replace("search","forum-detail",$request->url());
        $api = new ForumAPIController();
        $data = $api->index($request);
        $data = $data->getData();
        $content = $data->component;
        $content = componentWithNameObject($content);
            //pre($content);
        return view('frontend.forum.forum-search',compact('content','forum_total','url'));
    }
    public function newTopicCreate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,
        [
            // 'category_id' => 'required',
            'topic' => 'required',
            'desc' => 'required',
        ]);
        if($validator->fails())
        {
            // return $this->sendError('Validation Error.', $validator->errors(),300);
            echo "error";
        }
        else
        {
            $data = ForumAPIController::createNewTopic($request);
            return Response::json($data);
        }

    }

    public function newForumCommentCreate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,
        [
            // 'category_id' => 'required',
            'forum_id' => 'required',
            'comment' => 'required',
        ]);
        if($validator->fails())
        {
            // return $this->sendError('Validation Error.', $validator->errors(),300);
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else
        {
            $data = ForumAPIController::createCommentMain($request);
            // return Response::json($data);
            // pre($data);
            $notification = array(
                'message' => $data['message'],
                'alert-type' => $data['success']?'success':'error'
            );
            return redirect()->back()->with($notification);
        }

    }
    public function forumLikeDislike(Request $request)
    {
        $api = new ForumAPIController();
        $data = $api->ForumIncreaseLike($request);
        $data = $data->getData();
        return Response::json($data);
    }
    public function forumCommentLikeDislike(Request $request)
    {
        $api = new ForumAPIController();
        $data = $api->ForumCommentIncreaseLike($request);
        $data = $data->getData();
        return Response::json($data);
    }

 
}
