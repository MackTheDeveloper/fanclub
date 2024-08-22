<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\ForumFavouriteComment;

class ForumComments extends Model
{
    use SoftDeletes;

    protected $table = 'forum_comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','forum_id','comment','no_likes','created_by'
    ];

    public function getCreatedByNameAttribute(){
        $createdBy = $this->created_by;
        $return = '';
        $data = User::where('id',$createdBy)->first();
        if ($data) {
            $return = $data->firstname;
        }
        return $return;
    }

    public static function getCommentById($id)
    {
        return self::select(DB::raw("users.firstname AS Created_By"),'forum_comments.comment','forum_comments.created_at','forum_comments.no_likes','forum_comments.created_by AS user_id','forum_comments.id')
            ->leftjoin('users','users.id','forum_comments.created_by')->where('forum_id',$id);
    }

    public static function getForumCommentApi($page = "", $search = "", $id = "")
    {
        $data = self::getCommentById($id);
        $forumData = Forums::getForumWithCreatedByListById($id);
        $limit = 0;
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%')
                    ->orWhere('forum_comments.comment', 'like', '%' . $search . '%');
            });
        }
        if ($page) {
            $limit = 5;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get()->toArray();
        if ($data)
        {
            $data = self::formatForumCommentList($data);
        }
        $return = [
                    'forumsComment' => $data,
                    'page' => $page,
                    'limit' => $limit,
                    'forumData'=>$forumData
        ];
        return $return;
    }
    public static function formatForumCommentList($data){

        $return = [];
        foreach ($data as $key => $value) {

            $return[] = [
                "id" => $value['user_id'],
                "liked"=> ForumFavouriteComment::checkForumLiked($value['id']),
                "forumCommentId" =>$value['id'],
                "createdBy"=>$value['Created_By'],
                "comment"=>$value['comment'],
                "createdAt" => $value['created_at'],
                "createdAtShow" => getFormatedDateForWeb($value['created_at']),
                "noLikes"=> $value['no_likes'],
                "image" => UserProfilePhoto::getProfilePhoto($value['user_id'])
            ];
        }
        return $return;
    } 

    public static function getForumOnlyFive($id)
    {
        $data = self::where('forum_id',$id)->orderBy('forum_comments.created_at','desc')->limit(5)->get();
        $forumData = Forums::where('id',$id)->whereNull('deleted_at')->first();
        if ($data)
        {
            $data = self::formatForumOnlyFive($data);
        }
        $return = [
            'forumsComment' => $data,
            'forumData'=> $forumData
        ];
        return $return;
    }

    public static function formatForumOnlyFive($data){
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "createdId" => $value['created_by'],
                "createdBy"=>$value['created_by_name'],
                "comment"=>$value['comment'],
                "createdAt" =>getFormatedDate($value['created_at']),
                "noLikes"=>$value['no_likes'],
            ];
        }
        return $return;
    }

    public static function createComment($data)
    {
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $exist = Forums::where('id', $data->forum_id)->first();
        $statusCode = "200";
        $message = getResponseMessage('ForumCommentAdded',$exist->post_topic);

        if ($exist) {
            try {
                $gen = new ForumComments();
                $gen['forum_id'] = $data->forum_id;
                $gen['comment'] = $data->comment;
                $gen['created_by'] = $authId;
                $gen['no_likes'] = '0';
                $gen->save();
                $component = [
                    "forum_id" => $data->forum_id,
                    "comment"  => $data->comment
                ];
                
            } catch (\Exception $e) {
                $component = $e->getMessage();
                $success = false;
                $statusCode = "500";
                $message = "Something is wrong.";
            }
        }
        return ['statusCode' => $statusCode, 'success' => $success,'message' => $message ,'component'=> $component];
    }


}
