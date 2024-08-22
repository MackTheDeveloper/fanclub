<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\ForumFavourite;
use App\Models\User;
use App\Models\ForumComments;
use phpDocumentor\Reflection\Types\Self_;

class Forums extends Model
{
    use SoftDeletes;

    protected $table = 'forums';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','post_topic','description','status','no_likes','created_by','created_at'
    ];

    public function getCreatedByNameAttribute(){
        $createdBy = $this->created_by;
        $return = '';
        $data = User::where('id',$createdBy)->first();
        if ($data) {
            $return = $data->firstname;
            // $return = $data->firstname.' '.$data->lastname;
        }
        return $return;
    }

    public function creator(){
        return $this->hasOne(User::class,'id', 'created_by');
    }

    public static function getNameById($id){
        return self::where('id',$id)->pluck('post_topic');
    }

    public static function getForumWithCreatedByList()
    {
       return self::select('forums.id AS f_id','post_topic','users.id','forums.created_at','description','status','no_likes',DB::raw("users.firstname AS created_by"),
            DB::raw("(SELECT count(*) FROM forum_comments WHERE forums.id = forum_comments.forum_id and forum_comments.deleted_at IS NULL) as Comments"))
            ->leftJoin('users','users.id','=','forums.created_by')->whereNull('forums.deleted_at');
    }
    public static function getForumWithCreatedByListById($id)
    {
       return self::selectRaw('users.firstname AS Created_By, forums.*')
            ->leftJoin('users','users.id','=','forums.created_by')->where('forums.id',$id)->whereNull('forums.deleted_at')->first();

    }


    public static function getListUsers(){
        return self::selectRaw('users.id,users.firstname as fullName')->leftjoin('users','users.id','forums.created_by')->pluck('fullName','id');
    }
    public static function getForumListApi($page = "", $search = "",$sort="")
    {
        $limit = 0;
        $return = [];
        $data = self::where('status','1');
        if($sort)
        {
            // $data = self::getForumWithCreatedByList();
            if($sort == "latest")
            {
                $data->orderByDesc("created_at");
            }
            if($sort == "old")
            {
                $data->orderBy("created_at");
            }
            if($sort == "liked_desc")
            {
                $data->orderBy('no_likes', 'desc');
            }
            if($sort == "name_asc")
            {
                $data->orderBy('post_topic','asc');
            }
            if($sort == "name_desc")
            {
                $data->orderByDesc('post_topic');
            }
        }else{
            $data->orderByDesc("created_at");
        }
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('post_topic', 'like', '%' . $search . '%');
            });
        }
        
        if ($page) {
            $limit = 2;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get();
        if ($data)
        {
            $data = self::formatForumList($data);
        }
        // pre($data);
        $return = ['forums' => $data, 'page' => $page,'limit' => $limit];
        return $return;
    }
    public static function formatForumList($data){
        
        $return = [];
        foreach ($data as $key => $value) {
            $total_comment = ForumComments::where('forum_id',$value['id'])->get()->count();
            $return[] = [
                "id" =>$value['id'],
                "topic"=>$value['post_topic'],
                "status"=>$value['status'],
                "likes"=>$value['no_likes'],
                "liked"=> ForumFavourite::checkForumLiked($value['id']),
                "description"=>$value['description'],
                "createdByImage"=> UserProfilePhoto::getProfilePhoto($value['created_by']),
                "createdByName"=> User::getNameByIdForChat($value['created_by']),
                "createdAt"=>$value['created_at'],
                "createdAtShow"=> getFormatedDateForWeb($value['created_at']),
                "comments"=>$total_comment,
            ];
        }
        return $return;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('forums.id,post_topic,CONCAT(firstname," ",lastname) as fullName')->leftjoin('users','users.id','forums.created_by')
        ->where('status', '1')
        ->whereNull('users.deleted_at')
        ->where(function ($query2) use ($search) {
            $query2->where('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
                ->orWhere('post_topic', 'like', '%' . $search . '%');
        });
        if ($limit) {
            $return->limit($limit);
        }
        if ($operation == 'getTotal') {
            $return = $return->count();
        } else {
            $return = $return->get();
        }
        return $return;

    }
    public static function create($data)
    {
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        //$exist = Forums::where('created_by', $authId)->first();
        $statusCode = "200";
        $message = "Forum topic created successfully.";

        if ($authId) {
            try {
                $gen = new Forums();
                $gen['post_topic'] = $data->topic;
                $gen['description'] = $data->desc;
                $gen['created_by'] = $authId;
                $gen['status'] = '1';
                $gen['no_likes'] = '0';
                $gen->save();
                $component = [
                "post_topic" => $data->topic,
                "description"  => $data->desc
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
