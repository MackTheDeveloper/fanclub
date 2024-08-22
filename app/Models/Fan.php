<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class Fan extends Model
{
    // use HasApiTokens;
    // use HasFactory;
    // use HasProfilePhoto;
    // use HasTeams;
    // use TwoFactorAuthenticatable; 
    use SoftDeletes;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id', 'firstname', 'lastname', 'phone', 'email', 'password', 'user_type', 'role_id', 'ip_address', 'os_name', 'browser_name', 'browser_version', 'address', 'country', 'is_active', 'is_verify', 'current_subscription', 'subscription_expire_at', 'gender', 'dob', 'prefix', 'state', 'subscription_start_date', 'subscription_end_date'
    ];

    public function subscriptionPlan()
    {
        return $this->hasOne(SubscriptionPlan::class, 'id', 'current_subscription');
    }

    public static function getDaywiseCount($date)
    {
        return self::where('role_id', '3')->whereDate('created_at', $date)->count();
    }

    public static function  getMonthwiseCount($month, $year)
    {
        return self::where('role_id', '3')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    }

    public static function  getYearwiseCount($year)
    {
        return self::where('role_id', '3')->whereYear('created_at', $year)->count();
    }

    public static function getCountryList()
    {
        $return = [];
        $data = self::where('role_id', '3')->groupBy('country')->pluck('country')->toArray();
        if ($data) {
            $return = array_unique($data);
        }
        return $return;
    }

    public static function getDetailApi($id)
    {
        $return = [];
        $data = self::find($id);
        $return = self::formatedList($data);
        return $return;
    }

    public static function  updateExist($data)
    {
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $allowed = ["firstname", "lastname", "phone", "address", "country", "gender", "dob", "prefix", "state"];
        $data['lastname'] = "";
        $data = array_intersect_key($data, array_flip($allowed));
        $exist = Fan::where('id', $authId)->first();
        if ($exist) {
            try {
                foreach ($data as $key => $value) {
                    $exist->$key = $value;
                }
                $exist->save();
                $return = $exist;
            } catch (\Exception $e) {
                $return = $e->getMessage();
                $success = false;
            }
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function formatedList($data)
    {
        $genders = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
        $returnGender = [];
        foreach ($genders as $key => $value) {
            $temp = ['key' => $key, 'value' => $value];
            $temp['selected'] = ($data['gender'] == $key) ? "1" : "0";
            $returnGender[] = $temp;
        }
        $return = [];
        $return = [
            "introducer" => Artist::getNameById($data['introducer_id']),
            "profilePhoto" => UserProfilePhoto::getProfilePhoto(User::getLoggedInId()),
            // "profilePhoto" => UserProfilePhoto::getProfilePhoto(User::getLoggedInId(), 'round_192_192.png'),
            "firstName" => $data['firstname'],
            "lastName" => $data['lastname'],
            "email" => $data['email'],
            "phone" => $data['phone'],
            "prefix" => $data['prefix'],
            "address" => $data['address'],
            "country" => $data['country'],
            "state" => $data['state'],
            "gender" => $returnGender,
            "dob" => $data['dob'],
        ];
        return $return;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('id,CONCAT(firstname," ",lastname) as fullName,email')->where('role_id', '3')->whereNull('deleted_at')
            ->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
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

    public static function myCollectionApp($fanId)
    {
        $favArtist = FanFavouriteArtists::has('activeArtist')->selectRaw('*,"2" as type')->where('fan_id', $fanId)->get();
        $favGroup = FanFavouriteGroups::has('group')->selectRaw('*,"1" as type')->where('fan_id', $fanId)->get();
        $fanPlaylist = FanPlaylist::selectRaw('*,"3" as type')->where('user_id', $fanId)->get();
        $result = $favArtist->merge($favGroup)->merge($fanPlaylist)->sortBy('created_at');
        $return = self::formatedListCollection($result, $fanId);
        return $return;
    }

    public static function formatedListCollection($data, $fanId)
    {
        // pre($data);
        $return = [];
        $single = [
            "navigate" => "1",
            "navigateType" => "6",
            "navigateTo" => "fan-favourite-songs",
            "Icon" => url('public/assets/frontend/img/my-collection-banner.png'),
            "name" => "My Songs",
            "noOfSongs" => FanFavouriteSongs::countSongLiked($fanId),
            "tags" => "1",
        ];
        $return[] = $single;
        foreach ($data as $key => $value) {
            $single = [];
            if ($value->type == "1") {
                $single = [
                    "Id" => $value->id,
                    "type" => 'group',
                    "navigate" => "1",
                    "navigateType" => "9",
                    //"navigateTo" => "fan-playlist-songs/" . $value->group_id,
                    "navigateTo" => "fanclub-group/" . $value->group_id,
                    "Icon" => DynamicGroupItems::getGroupIcon($value->group_id, $value->group->view_all),
                    "name" => DynamicGroups::getAttrById($value->group_id, 'name'),
                    "slug" => DynamicGroups::getAttrById($value->group_id, 'slug'),
                    "noOfSongs" => DynamicGroupItems::getGrpTotal($value->group_id, $value->group->view_all, $value->group->allow_max),
                    "tags" => $value->type,
                    "createdAt" => getFormatedDate($value->created_at),
                ];
            } elseif ($value->type == "3") {
                $single = [
                    "Id" => $value->id,
                    "type" => 'playlist',
                    "navigate" => "1",
                    "navigateType" => "8",
                    // "navigateTo" => "fan-playlist/" . $value->id,
                    "navigateTo" => "fan-playlist-songs/" . $value->id,
                    "Icon" => FanPlaylist::getGroupIcon($value->id),
                    "name" => $value->playlist_name,
                    "slug" => $value->slug,
                    "noOfSongs" => FanPlaylist::getSongsCount($value->id),
                    // "tags" => $value->type,
                    "tags" => "1",
                    "createdAt" => getFormatedDate($value->created_at),
                ];
            } else {
                $single = [
                    "Id" => $value->id,
                    "type" => 'artist',
                    "navigate" => "1",
                    "navigateType" => "11",
                    "navigateTo" => "artist-detail/" . $value->artist_id,
                    "name" => Artist::getAttrById($value->artist_id, 'firstname'),
                    "email" => Artist::getAttrById($value->artist_id, 'email'),
                    "slug" => Artist::getAttrById($value->artist_id, 'slug'),
                    "Icon" => UserProfilePhoto::getProfilePhoto($value->artist_id),
                    "status" => Artist::getAttrById($value->artist_id, 'is_active'),
                    "approved" => Artist::getAttrById($value->artist_id, 'is_verify'),
                    "tags" => $value->type,
                    "createdAt" => getFormatedDate($value->created_at)

                ];
            }
            $return[] = $single;
        }
        return $return;
    }
}
