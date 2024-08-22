<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use App\Models\User;

use phpDocumentor\Reflection\Types\Self_;

class ForumFavouriteComment extends Model
{
    use SoftDeletes;

    protected $table = 'favourite_forum_comment';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id',	'forum_comment_id'
    ];

    public static function checkForumLiked($id)
    {
        $authId = User::getLoggedInId();
        $data = self::where('forum_comment_id',$id)->where('user_id',$authId)->first();
        if ($data) {
            return 1;
        }
        return 0;
    }
    
}
