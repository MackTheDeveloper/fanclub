<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
// use App\User;
use URL;
use Response;
use App\Models\UserProfilePhoto;
use App\Models\Songs;
use App\Models\Artist;
use DB;
use phpDocumentor\Reflection\Types\Self_;


class Reviews extends Model
{
    use SoftDeletes;

    protected $table = 'reviews';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['id', 'artist_id', 'type', 'customer_id', 'song_id', 'ratings', 'reviews', 'status'];

    public function songs()
    {
        return $this->hasMany(Songs::class, 'id', 'song_id');
    }

    public static function getReviewIfExist($page = "", $search = "", $request = "")
    {
        $query = self::has('songs')->select('reviews.id', 'reviews.song_id', 'songs.icon', 'reviews.artist_id')->leftJoin('songs', 'songs.id', 'reviews.song_id')->whereNull('reviews.deleted_at');
        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $query->offset($offset);
            $query->limit($limit);
        }

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('songs.name', 'like', '%' . $search . '%');
            });
        }
        $data = [];
        $query = $query->get();
        foreach ($query as $key => $value) {
            $id_val = $value->id;
            $icon = $value->icon;
            $songId = $value->song_id;
            $artistId = $value->artist_id;
            $data[] = [
                "songId" => $value->song_id,
                "songIcon" => $icon,
                "songName" => Songs::getNameById($songId),
                "artistName" => Artist::getNameById($artistId),
            ];
        }
        $return = ['forums' => $data, 'page' => $page];
        return $return;
    }
    public static function getReviews()
    {
        $totalReviews = self::select('ratings')->where('status', '2')->whereNull('deleted_at')->count();
        $postive = self::has('activeArtist')->has('song')->has('fan')->where('status', '2')->whereIn('ratings', [4, 5])->whereNull('deleted_at')->count();
        $neutral = self::has('activeArtist')->has('song')->has('fan')->where('status', '2')->whereIn('ratings', [3])->whereNull('deleted_at')->count();
        $negative = self::has('activeArtist')->has('song')->has('fan')->where('status', '2')->whereIn('ratings', [1, 2])->whereNull('deleted_at')->count();

        $json_data = [
            'postive' => $postive,
            'neutral' => $neutral,
            'negative' => $negative,
            'status' => true,
        ];
        return Response::json($json_data);
    }

    public static function getSongWiseData($songId, $page = "",$noStatus=0)
    {
        $exist = self::selectRaw('reviews.*,name,users.firstname,users.lastname')
            ->leftJoin('songs', 'songs.id', 'reviews.song_id')
            ->leftjoin('users', 'users.id', 'reviews.customer_id')->where('song_id', $songId)->whereNull('reviews.deleted_at')->orderBy('id', 'asc');

        $exist->where('reviews.status','!=', 3);
        // if (!$noStatus) {
        //     $exist->where('reviews.status', 2);
        // }
        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $exist->offset($offset);
            $exist->limit($limit);
        }
        $data = [];
        $exist = $exist->get();

        foreach ($exist as $key => $value) {
            //$icon = '<img src="'.substr($request->url(), 0, strpos($request->url(), "securefcbcontrol")).'public/assets/images/album/'.$value->icon.'"  width="60" height="60" alt="">';
            if ($value->status == 1) {
                $status = "Pending";
            } else if ($value->status == 2) {
                $status = "Approved";
            } else if ($value->status == 3) {
                $status = "Rejected";
            }
            $data[] = [
                "reviewId" => $value->id,
                "customerId" => $value->customer_id,
                "artist_id" => $value->artist_id,
                "status" =>  $value->status,
                "statusText" => $status,
                "ratings" => $value->ratings,
                "reviews" => $value->reviews,
                "userName" => $value->firstname . ' ' . $value->lastname,
                "image" => UserProfilePhoto::getProfilePhoto($value->customer_id),
                "createdAt" => getFormatedDate($value->created_at),
                "createdAtForWeb" => getFormatedDateForWeb($value->created_at),
            ];
        }
        $return = ['data' => $data, 'page' => $page];
        return $return;
    }

    public static function getArtistWiseData($artistId)
    {
        $return = [];
        $data = self::selectRaw('reviews.*,users.firstname,users.lastname')->leftjoin('users', 'users.id', 'reviews.customer_id')->where('artist_id', $artistId)->whereNull('reviews.deleted_at')->orderBy('id', 'asc')->get()->toArray();
        $return = self::formatedList($data);
        return $return;
    }

    public static function getAvgRatingOfSongs($songId)
    {
        $return = [];
        $data = self::select(DB::raw('avg(ratings) as averageRating'))->where('song_id', $songId)->whereNull('deleted_at')->orderBy('id', 'asc')->first()->toArray();
        return $data['averageRating'];
    }

    public static function getAvgRatingOfArtists($artistId)
    {
        $return = [];
        $data = self::select(DB::raw('avg(ratings) as averageRating'))->where('artist_id', $artistId)->whereNull('deleted_at')->orderBy('id', 'asc')->first()->toArray();
        return $data['averageRating'];
    }

    public static function getTotalCountData($id)
    {
        $total = Reviews::where('song_id', $id)->whereNull('deleted_at')->where('status', '!=', 3)->get()->count();
        return $total;
    }
    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "reviewId" => $value['id'],
                "ratings" => $value['ratings'],
                "comment" => $value['reviews'],
                "reviewBy" => $value['firstname'] . ' ' . $value['lastname'],
                "reviewById" => $value['customer_id'],
                "createdAt" => !empty($value['created_at']) ? getFormatedDate($value['created_at']) : "",
            ];
        }
        return $return;
    }

    public function ReviewUploads()
    {
        return $this->hasMany(ReviewUploads::class, 'review_id', 'id')->selectRaw('review_id, id, type, upload');
    }

    public function Artist()
    {
        return $this->hasOne(Artist::class, 'id', 'artist_id');
    }

    public function activeArtist()
    {
        return $this->hasOne(User::class, 'id', 'artist_id')->where('is_verify', 1)->where('is_active', 1);
    }

    public function Song()
    {
        return $this->hasOne(Songs::class, 'id', 'song_id');
    }

    public function Fan()
    {
        return $this->hasOne(Fan::class, 'id', 'customer_id');
    }

    public static function addNew($data)
    {
        $return = '';
        $success = true;
        $product_id = $data['product_id'];
        // $category_id = $data['category_id'];
        $user_id = Auth::user()->id;
        $data['user_id'] = $user_id;
        $exist = self::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if (!$exist) {
            try {
                $create = new Reviews();
                foreach ($data as $key => $value) {
                    $create->$key = $value;
                }
                $create->save();
                // self::makeAvgProductReview($product_id);
                $return = $create;
            } catch (\Exception $e) {
                $return = $e->getMessage();
                $success = false;
            }
        } else {
            $return = 0;
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function makeAvgProductReview($product_id)
    {
        $return = self::selectRaw('avg(reviews) as avg_reviews')->where('product_id', $product_id)->where('status', 1)->first();
        // pre($return);
        if ($return) {
            $product = Products::where('id', $product_id)->update(['avg_rating' => $return->avg_reviews]);
        }
        return 1;
    }
    public static function AvgRatings($id, $type)
    {
        if ($type == 'artist') {
            $return = self::selectRaw('avg(ratings) as avg_ratings')->where('type', 'artist')->where('artist_id', $id)->where('status', 2)->whereNull('deleted_at')->first();
        } else {
            $return = self::selectRaw('avg(ratings) as avg_ratings')->where('type', 'song')->where('song_id', $id)->where('status', 2)->whereNull('deleted_at')->first();
        }
        // pre($return);
        if ($return) {
            return (int) $return->avg_ratings;
        } else {
            return 0;
        }
    }

    public static function userAndStatusWiseData($user_id)
    {
        $pending = self::where('user_id', $user_id)->where('status', 0)->get();
        $accepted = self::where('user_id', $user_id)->where('status', 1)->get();
        $rejected = self::where('user_id', $user_id)->where('status', 2)->get();

        $return = [
            "pending" => $pending,
            "accepted" => $accepted,
            "rejected" => $rejected,
        ];

        return Response::json($return);
    }

    public static function apiAddReviews($artistId, $rating, $comment)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId) {
                $exist = self::where('customer_id', $authId)->where('artist_id', $artistId)->where('type', 'artist')->first();
                //$exist = self::where('artist_id', $artistId)->first()->toArray();
                if (!$exist) {
                    $exist = new Reviews();
                    $exist['song_id'] = NULL;
                    $exist['customer_id'] = $authId;
                    $exist['artist_id'] = $artistId;
                    $exist['type'] = 'artist';
                    $exist['ratings'] = $rating;
                    $exist['reviews'] = $comment;
                    $exist['status'] = 1;
                    $exist->save();
                } else {
                    $exist->ratings = $rating;
                    $exist->reviews = $comment;
                    $exist->save();
                }
                $return = $exist;
                $success = true;
            }
            $success = true;
        } catch (Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }
    public static function apiAddReviewsWithSong($songId, $artistId, $rating, $comment)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId) {
                // $exist = self::has('song')->where('song_id',$songId)->first();
                /* $exist = self::where('customer_id', $authId)->where('song_id', $songId)->where('type', 'song')->first();
                // $artist_id = $exist->artist_id;
                // $exist = $exist->toArray();
                // echo "<pre>";print_r($exist);echo "</pre>";die();
                if (!$exist) { */
                    $exist = new Reviews();
                    $exist['song_id'] = $songId;
                    $exist['customer_id'] = $authId;
                    $exist['artist_id'] = $artistId;
                    $exist['type'] = 'song';
                    $exist['ratings'] = $rating;
                    $exist['reviews'] = $comment;
                    $exist['status'] = 1;
                    $exist->save();
                /* } else {
                    $exist->ratings = $rating;
                    $exist->reviews = $comment;
                    $exist->save();
                } */
                $return = $exist;
                $success = true;
                Notifications::songAddReview($songId, $authId);
            }
            $success = true;
        } catch (Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function apiDeleteReview($id)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId) {
                $exist = self::where('id', $id)->first();
                $exist->delete();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public static function apiRejectReview($id)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId) {
                $exist = self::where('id', $id)->first();
                $exist->status = 3;
                $exist->update();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public static function apiEditReview($request)
    {
        $return = [];
        $success = true;
        try {
            $authId = User::getLoggedInId();
            if ($authId) {
                $exist = self::where('id', $request->id)->first();
                $exist->ratings = $request->ratings;
                $exist->reviews = $request->comments;
                $exist->update();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getName($id)
    {
        $return = '';
        $data = self::withTrashed()->find($id);
        if ($data) {
            if ($data->song_id) {
                $return = Songs::getNameById($data->song_id);
            } else {
                $return = Artist::getNameById($data->artist_id);
            }
        }
        return $return;
    }

    public static function userWiseData($id, $request, $page = "", $search = "")
    {
        $exist = Reviews::select('reviews.id', 'reviews.type', 'users.id as UserId', 'songs.icon', 'songs.name', \DB::raw("CONCAT(firstname, ' ', lastname) AS fullname"), 'reviews.ratings', 'reviews.status', 'reviews.reviews', ('reviews.created_at'))->leftJoin('songs', 'songs.id', 'reviews.song_id')
            ->leftJoin('users', 'users.id', 'reviews.artist_id')
            ->where('customer_id', $id)
            ->whereIn('reviews.status', [1, 2])
            ->whereNull('reviews.deleted_at');

        $countable = Reviews::select('reviews.id', 'reviews.type', 'users.id as UserId', 'songs.icon', 'songs.name', \DB::raw("CONCAT(firstname, ' ', lastname) AS fullname"), 'reviews.ratings', 'reviews.status', 'reviews.reviews', ('reviews.created_at'))->leftJoin('songs', 'songs.id', 'reviews.song_id')
            ->leftJoin('users', 'users.id', 'reviews.artist_id')
            ->where('customer_id', $id)
            ->whereIn('reviews.status', [1, 2])
            ->whereNull('reviews.deleted_at');

        $limit = 0;
        if ($request->filter) {
            if ($request->filter == "latest") {
                $exist = $exist->orderByDesc("created_at");
            }
            if ($request->filter == "old") {
                $exist = $exist->orderBy("created_at");
            }
            if ($request->filter == "three-month") {
                $exist = $exist->whereBetween('reviews.created_at', [Carbon::now()->subMonths(3), Carbon::now()]);
                $countable = $countable->whereBetween('reviews.created_at', [Carbon::now()->subMonths(3), Carbon::now()]);
            }
            if ($request->filter == "one-month") {
                $exist = $exist->whereBetween('reviews.created_at', [Carbon::now()->subMonths(1), Carbon::now()]);
                $countable = $countable->whereBetween('reviews.created_at', [Carbon::now()->subMonths(1), Carbon::now()]);
            }
        } else {
            $exist = $exist->orderByDesc("created_at");
        }
        if ($page) {
            $limit = 5;
            $offset = ($page - 1) * $limit;
            $exist->offset($offset);
            $exist->limit($limit);
        }
        if ($search != '') {
            $exist->where(function ($query2) use ($search) {
                $query2->where(DB::raw("CONCAT(firstname,' ',lastname)"), 'like', '%' . $search . '%')
                    ->orWhere('songs.name', 'like', '%' . $search . '%');
            });
        }
        $data = [];
        $exist = $exist->get();
        $countable = $countable->count();
        foreach ($exist as $key => $value) {
            //$icon = '<img src="'.substr($request->url(), 0, strpos($request->url(), "securefcbcontrol")).'public/assets/images/album/'.$value->icon.'"  width="60" height="60" alt="">';
            $status = "";
            if ($value->status == 1) {
                $status = "Pending";
            } else if ($value->status == 2) {
                $status = "Approved";
            } else if ($value->status == 2) {
                $status = "Rejected";
            }
            $data[] = [
                "customerId" => $id,
                "type" => $value->type,
                "reviewId" => $value->id,
                "artistId" => $value->UserId,
                "songIcon" => Songs::getIcon($value->icon),
                "songName" => $value->name,
                "artistName" => $value->fullname,
                "artistImage" => UserProfilePhoto::getProfilePhoto($value->UserId),
                "ratings" => $value->ratings,
                "reviews" => $value->reviews,
                "statusText" => $status,
                "status" => $value->status,
                "createdAt" => getFormatedDate($value->created_at),
            ];
        }
        $return = ['data' => $data, 'page' => $page, 'limit' => $limit, 'countable' => $countable];
        return $return;
    }


    public static function getReviewsListByArtist($id, $page)
    {
        $returnData = [];
        $data = self::selectRaw('reviews.*,t1.firstname,t1.lastname')
            ->leftJoin('users AS t1', 't1.id', '=', 'reviews.customer_id')
            ->whereNull('reviews.deleted_at')
            ->where('reviews.artist_id', $id)
            ->whereIn('reviews.status', [1, 2])
            ->where('reviews.type', 'artist');
        if ($page) {
            $limit = 5;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get();
        $return['data'] = [];
        $return['limit'] = $limit;
        $return['page'] = $page;
        foreach ($data as $key => $value) {
            $return['data'] = [
                "id" => $value['id'],
                "name" => $value['firstname'] . ' ' . $value['lastname'],
                "revirews" => $value['reviews'],
                "ratings" => $value['ratings'],
                "date" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public static function getReviewsByArtist($id, $limit)
    {
        $returnData = [];
        $data = self::selectRaw('reviews.*,t1.firstname,t1.lastname')
            ->leftJoin('users AS t1', 't1.id', '=', 'reviews.customer_id')
            ->whereNull('reviews.deleted_at')
            ->where('reviews.artist_id', $id)
            ->where('reviews.type', 'artist')
            ->whereIn('reviews.status', [1, 2])
            ->limit($limit)
            ->get();
        $return['data'] = [];
        foreach ($data as $key => $value) {
            $return['data'] = [
                "id" => $value['id'],
                "name" => $value['firstname'] . ' ' . $value['lastname'],
                "revirews" => $value['reviews'],
                "ratings" => $value['ratings'],
                "date" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function getReviewById($id)
    {
        $data = self::find($id);
        $return = [];
        if ($data) {
            $artistData = Artist::where('id', $data->artist_id)->first();
            $songData = Songs::where('id', $data->song_id)->first();
            $return = [
                "id" => $data['id'],
                "songName" => $songData['name'],
                "ratings" => $data['ratings'],
                "artistFName" => $artistData['firstname'],
                "artistLName" => $artistData['lastname'],
                "reviews" => $data['reviews'],
            ];
        }
        return $return;
    }

    public static function getFirstSongReviewDataForMusicPlayer($songId)
    {
        $reviews = self::selectRaw(DB::raw('avg(ratings) as averageRating,count(*) as totalReviews'))
            ->leftJoin('songs', 'songs.id', 'reviews.song_id')
            ->where('song_id', $songId)
            ->whereIn('reviews.status', [1, 2])
            // ->where('reviews.status', 2)
            ->whereNull('reviews.deleted_at')->orderBy('reviews.id', 'asc')->first();
        $data = [
            "totalReviews" => $reviews->totalReviews,
            "averageRating" => number_format($reviews->averageRating,1),
        ];
        return $data;
    }

    public static function getSongAllReviewsDataForMusicPlayer($songId)
    {
        $exist = self::selectRaw('reviews.*,name,users.firstname')
            ->leftJoin('songs', 'songs.id', 'reviews.song_id')
            ->leftjoin('users', 'users.id', 'reviews.customer_id')->where('song_id', $songId)
            ->whereIn('reviews.status', [1,2])
            // ->where('reviews.status', 2)
            ->whereNull('reviews.deleted_at')->orderBy('created_at', 'desc');
        $data['list'] = [];
        $exist = $exist->get();

        foreach ($exist as $key => $value) {
            $data['list'][] = [
                "ratings" => $value->ratings,
                "reviews" => $value->reviews,
                "userName" => $value->firstname,
                "image" => UserProfilePhoto::getProfilePhoto($value->customer_id),
                "createdAt" => getFormatedDate($value->created_at,'j M Y'),
                "createdAtForWeb" => getFormatedDateForWeb($value->created_at),
            ];
        }
        return $data;
    }
}
