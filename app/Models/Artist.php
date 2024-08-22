<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;
use DB;

class Artist extends Model
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
        'unique_id', 'firstname', 'lastname', 'phone', 'email', 'password', 'user_type', 'role_id', 'ip_address', 'os_name', 'browser_name', 'browser_version', 'address', 'country', 'is_active', 'is_verify', 'current_subscription', 'subscription_expire_at', 'gender', 'dob', 'slug', 'prefix', 'state', 'subscription_start_date', 'view_map', 'subscription_end_date'
    ];

    public function artistDetail()
    {
        return $this->hasOne(ArtistDetail::class, 'user_id', 'id');
    }

    public static function getList()
    {
        return Artist::where('role_id', '2')->whereNull('deleted_at')->get();
    }

    public static function geArtistList()
    {
        $return = self::selectRaw('id,firstname as name,lastname')->where('role_id', '2')->whereNull('deleted_at')->get();
        return $return;
    }

    public static function geArtistListActive()
    {
        $return = self::selectRaw('id,firstname as name')->where('role_id', '2')->where('is_active', '1')->where('is_verify', '1')->whereNull('deleted_at')->get();
        return $return;
    }

    public static function getDaywiseCount($date)
    {
        return self::where('role_id', '2')->whereDate('created_at', $date)->count();
    }

    public static function getMonthwiseCount($month, $year)
    {
        return self::where('role_id', '2')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    }

    public static function getYearwiseCount($year)
    {
        return self::where('role_id', '2')->whereYear('created_at', $year)->count();
    }
    public static function getArtistFullName()
    {
        return self::select(DB::raw("firstname AS fullname"), "id")->where('role_id', '2')->whereNull('deleted_at')->pluck("fullname", "id");
        // return self::select(DB::raw("CONCAT(firstname, ' ', lastname) AS fullname"), "id")->where('role_id', '2')->whereNull('deleted_at')->pluck("fullname", "id");
    }

    public static function getArtistDetail($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getArtistNameById($id)
    {
        $return = "";
        if ($id) {
            $data = self::where('role_id', '2')->where('id', $id)->first();
            if ($data) {
                $return = $data->firstname . ' ' . $data->lastname;
            }
        } else {
            $return = "";
        }
        return $return;
    }

    public static function getCountryList()
    {
        $return = [];
        $data = self::where('role_id', '2')->groupBy('country')->pluck('country')->toArray();
        if ($data) {
            $return = array_unique($data);
        }
        return $return;
    }

    public static function getSingleDetail($id)
    {
        $artistdata = Artist::where('role_id', '2')->where('id', $id)->whereNull('deleted_at')->first()->toArray();
        $artistdata['fullname'] = $artistdata['firstname'] . ' ' . $artistdata['lastname'];
        $artistdata['liked'] = FanFavouriteArtists::checkArtistLiked($artistdata['id']);
        return $artistdata;
    }

    public static function getArtistDetailAPI($id)
    {
        // basic artist details from users (role_id = 2)
        $artistdata = Artist::where('role_id', '2')->where('id', $id)->whereNull('deleted_at')->first();
        // artist_detail consist of bio,events,etc
        $artistdetaildata = ArtistDetail::where('user_id', $id)->whereNull('deleted_at')->first();

        $socialData = (explode(",", ArtistSocialMedia::getSocialMedia($id)));;
        $interest = !empty($artistdetaildata['interest']) ? Interest::getInteres($artistdetaildata['interest']) : '';
        $artistSongCollection = Songs::getSongsByArtist($id, 5);
        $artistReviews = Reviews::getArtistWiseData($id);
        $artistEvents = ArtistEvents::getEventsByArtist($id, 3);
        $artistNews = ArtistNews::getNewsByArtist($id, 3);
        $avgRatings = Reviews::AvgRatings($id, 'artist');
        $artistSong = Songs::getSongsByArtist($id);


        $data = self::formatAPIData($artistdata, $artistdetaildata, $socialData, $interest, $artistSongCollection, $artistReviews, $avgRatings, $artistEvents, $artistNews, $artistSong);

        $return = ['artistDetail' => $data];
        return $return;
    }

    public static function formatAPIData($data, $artistdetaildata, $socialData, $interest, $artistSongCollection, $artistReviews, $avgRatings, $artistEvents, $artistNews, $artistSong)
    {
        $return = [
            "id" => $data['id'],
            "allowMessage" => $data['allow_message'],
            "messageToArtistNotAllowed" => getResponseMessage('messageToArtistNotAllowed', $data['firstname'] . ' ' . $data['lastname']),
            "name" => $data['firstname'] . ' ' . $data['lastname'],
            "email" => $data['email'],
            "phone" => $data['phone'],
            "address" => $data['address'],
            "country" => $data['country'],
            "slug" => $data['slug'],
            "status" => $data['is_active'],
            "approved" => $data['is_verify'],
            "liked" => FanFavouriteArtists::checkArtistLiked($data['id']),
            "profilePic" => UserProfilePhoto::getProfilePhoto($data['id']),
            // "profilePic" => UserProfilePhoto::getProfilePhoto($data['id'], 'round_192_192.png'),
            "createdAt" => getFormatedDate($data['created_at']),
            "bio" => isset($artistdetaildata['bio']) ? $artistdetaildata['bio'] : '',
            "artisCollection" => isset($artistSongCollection) ? $artistSongCollection : '',
            "newsDetail" => isset($artistdetaildata['news_detail']) ? $artistdetaildata['news_detail'] : '',
            "avgRating" => isset($avgRatings) ? "" . $avgRatings . "" : '',
            "artistReviews" => isset($artistReviews) ? $artistReviews : '',
            "artistEvents" => isset($artistEvents) ? $artistEvents : '',
            "artistNews" => isset($artistNews) ? $artistNews : '',
            "artistSongs" => isset($artistSong) ? $artistSong : '',
            "numLikes" => isset($artistdetaildata['num_likes']) ? $artistdetaildata['num_likes'] : '',
            "numSongs" => Songs::getCountByArtist($data['id']),
            "event" => isset($artistdetaildata['event']) ? $artistdetaildata['event'] : '',
            "interest" => isset($interest) ? $interest : '',
            "facebook" => isset($socialData[0]) ? $socialData[0] : '',
            "linkedIn" => isset($socialData[1]) ? $socialData[1] : '',
            "twitter" => isset($socialData[2]) ? $socialData[2] : '',
            "instagram" => isset($socialData[3]) ? $socialData[3] : '',
        ];
        return $return;
    }
    public static function searchAPIArtist($search)
    {
        $data = [];
        $artistdata = Artist::where('role_id', '2')->where('is_active', 1)->where('is_verify', 1)->whereNull('deleted_at');
        if ($search) {
            $artistdata->where(function ($query) use ($search) {
                $query->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%');
            });
        }
        $artistdata = $artistdata->get()->toArray();
        if ($artistdata) {
            $data = self::filterSearch($artistdata);
        }
        $return = ['artistDetail' => $data];
        return $return;
    }

    public static function filterSearch($artistdata)
    {
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $navigateGuest = "1";
        $return = [];
        foreach ($artistdata as $key => $value) {
            $return[] = [
                "navigate" => $navigateGuest,
                "navigateType" => "13",
                "navigateTo" => "artist-detail/" . $value['id'],
                "name" => $value['firstname'] . ' ' . $value['lastname'],
                "email" => $value['email'],
                "artist_id" => $value['id'],
                "slug" => $value['slug'],
                "profilePic" => UserProfilePhoto::getProfilePhoto($value['id']),
                // "profilePic" => UserProfilePhoto::getProfilePhoto($value['id'], 'round_312_312.png'),
                "phone" => $value['phone'],
                "address" => $value['address'],
                "country" => $value['country'],
                "status" => $value['is_active'],
                "approved" => $value['is_verify'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }
    public static function getNameById($id)
    {
        $return = "";
        if ($id) {
            $data = self::withTrashed()->where('id', $id)->first();
            if ($data) {
                $return = $data->firstname . " " . $data->lastname;
            }
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

    public static function getArtistProfileDetailApi($id)
    {
        $return = [];
        $data = self::find($id);
        $return = self::formatedDetailList($data);
        return $return;
    }

    public static function updateExist($data)
    {
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        // $data['dob'] = date('Y-m-d',strtotime($data['dob']));
        // pre($data);
        $allowed = ["firstname", "lastname", "phone", "address", "country", "gender", "dob", "prefix", "state"];
        $data['lastname'] = "";
        $data = array_intersect_key($data, array_flip($allowed));
        $exist = self::where('id', $authId)->first();
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
        $genders = ['other' => 'Band', 'male' => 'Solo Male', 'female' => 'Solo Female'];
        $returnGender = [];
        foreach ($genders as $key => $value) {
            $temp = ['key' => $key, 'value' => $value];
            $temp['selected'] = ($data['gender'] == $key) ? "1" : "0";
            $returnGender[] = $temp;
        }
        $return = [];
        $return = [
            "profilePhoto" => UserProfilePhoto::getProfilePhoto(User::getLoggedInId()),
            // "profilePhoto" => UserProfilePhoto::getProfilePhoto(User::getLoggedInId(), 'round_192_192.png'),
            "firstName" => $data['firstname'],
            "lastName" => $data['lastname'],
            "email" => $data['email'],
            "prefix" => $data['prefix'],
            "phone" => $data['phone'],
            "address" => $data['address'],
            "country" => $data['country'],
            "state" => $data['state'],
            "gender" => $returnGender,
            "dob" => $data['dob'],
        ];
        return $return;
    }

    public static function formatedDetailList($data)
    {

        $artistData = Artist::getArtistDetailAPI($data->id);
        $return = [
            [
                "componentId" => "artistImage",
                "sequenceId" => "1",
                "isActive" => "1",
                "artistImageData" =>
                [
                    "image" => UserProfilePhoto::getProfilePhoto($artistData['artistDetail']['id']),
                    // "image" => UserProfilePhoto::getProfilePhoto($artistData['artistDetail']['id'], 'round_312_312.png'),
                    "name" => $artistData['artistDetail']['name'],
                    "isFav" => $artistData['artistDetail']['liked'],

                ]
            ],
            [
                "componentId" => "songsStatus",
                "sequenceId" => "2",
                "isActive" => "1",
                "songsStatusData" =>
                [
                    "likeText" => "Likes",
                    "likeCount" => $artistData['artistDetail']['numLikes'],
                    "songText" => "Songs",
                    "songCount" => $artistData['artistDetail']['numSongs']
                ]
            ],
            [
                "componentId" => "artistCollection",
                "sequenceId" => "3",
                "isActive" => "1",
                "artistCollectionData" =>
                [
                    "title" => "My Collection",
                    "list" => $artistData['artistDetail']['artisCollection']
                ]
            ],
            [
                "componentId" => "artistDetail",
                "sequenceId" => "4",
                "isActive" => "1",
                "artistDetailData" => [
                    "aboutTitle" => 'Bio',
                    "slug" => $artistData['artistDetail']['slug'],
                    "aboutDesc" => substr($artistData['artistDetail']['bio'], 0, 100),
                    "aboutFullDesc" => $artistData['artistDetail']['bio'],
                    "newsTitle" => "News",
                    "newsList" =>
                    $artistData['artistDetail']['artistNews']
                ]

            ],
            [
                "componentId" => "upcomingEvent",
                "sequenceId" => "5",
                "isActive" => "1",
                "upcomingEventData" =>
                [
                    "title" => "Upcoming Events",
                    "list" =>
                    $artistData['artistDetail']['artistEvents']
                ],
            ],

        ];
        return $return;
    }

    public static function getGenders()
    {
        $genders = ['other' => 'Band', 'male' => 'Solo Male', 'female' => 'Solo Female'];
        $returnGender = [];
        foreach ($genders as $key => $value) {
            $temp = ['key' => $key, 'value' => $value];
            $temp['selected'] = ('other' == $key) ? "1" : "0";
            $returnGender[] = $temp;
        }
        return $returnGender;
    }

    public static function getSearchData($search = '', $limit = 0, $operation = '')
    {
        $return = self::selectRaw('id,firstname,lastname,email')->where('role_id', '2')->whereNull('deleted_at')
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

    public static function getCountryWiseCount()
    {
        $return = [];
        $countryWiseCount = [];
        $artistId = User::getLoggedInId();
        if (isset($artistId)) {
            $countries = User::select('country')->where('role_id', 3)->where('step', 'third')->where('introducer_id', $artistId)->groupBy('country')->get();
            foreach ($countries as $key => $value) {
                $countryList[] = $value->country;
            }
            // get total count of fan in each country
            $countryWiseCount = User::selectRaw('country,count(*) as total')->where('role_id', 3)->where('step', 'third')->where('introducer_id', $artistId)->groupBy('country')->get()->toArray();
            // get array of country name and total count
            foreach ($countryWiseCount as $key => $value) {
                $countryWiseCount[$key]['country'] = $value['country'];
                $countryWiseCount[$key]['total'] = $value['total'];
            }
        }
        $list = [];
        $prefix = [];
        $total_subscribed = 0;
        if ($countryWiseCount) {

            // get total count of artist has introduced
            $total_subscribed = User::where('role_id', 3)->where('introducer_id', $artistId)->count();
            // $prefix = Country::selectRaw('name,sortname')->whereIn('name', array_keys($countryWiseCount))->get();
            foreach ($countryWiseCount as $key => $value) {
                $prefixVal = "";
                $prefix = Country::selectRaw('name,sortname')->where('name', $value['country'])->first();
                if ($prefix) {
                    $prefixVal = $prefix->sortname;
                }
                $list[] = [
                    "country" => $value['country'],
                    "total" => $value['total'],
                    "prefix" => $prefixVal,
                    "progressData" => [
                        "progress" => ($total_subscribed) ? round(($value['total'] / $total_subscribed) * 100) : 0,
                        "progressText" => $value['total'] . " Fans"
                    ]
                ];
            }
        }
        $data =  Artist::formatList($list);
        if (!empty($data)) {
            $return = array_values($data);
        }
        return $return;
    }

    public static function formatList($return)
    {
        $data = [];
        if ($return) {
            foreach ($return as $key => $value) {
                $data[] = [
                    "value" => $value['total'],
                    "name" => $value['country'],
                    "prefix" => $value['prefix'],
                    "progressData" => $value['progressData']['progress'],
                ];
            }
        }
        return $data;
    }
    public static function getDashboardApi($id)
    {
        // echo "string";die;
        $return = [];
        $data = self::with('artistDetail')->where('id', $id)->first()->toArray();
        $artistSongCount = Songs::getCountByArtist($id);
        if ($artistSongCount > 10)
            $profileCompleted = 100;
        else
            $profileCompleted = 10 * $artistSongCount;
        if ($data) {
            $likes = ($data['artist_detail']) ? $data['artist_detail']['num_likes'] : 0;
            $numSongs = Songs::getCountByArtist($data['id']);
            $streams = Songs::getArtistSongStreams($data['id']);
            // $streams = $data['artist_detail']['num_views'] ?: 0;
            $milestones = self::milestones($data['id'], $likes, $numSongs, $streams);
            $country = Artist::getCountryWiseCount();
            $return = [
                "id" => $data['id'],
                "firstname" => $data['firstname'],
                "name" => $data['firstname'] . ' ' . $data['lastname'],
                "profileUrl" => route('artistDetail', $data['slug']),
                // "email" => $data['email'],
                // "phone" => $data['phone'],
                "monthlyActSubs" => ArtistSubscriberHistory::currentMonthSubscriber($data['id']),
                "yearlyActSubs" => ArtistSubscriberHistory::currentYearSubscriber($data['id']),
                "totalActSubs" => ArtistSubscriberHistory::currentSubscriber($data['id']),
                "profilePic" => UserProfilePhoto::getProfilePhoto($data['id']),
                // "profilePic" => UserProfilePhoto::getProfilePhoto($data['id'], 'round_192_192.png'),
                "numLikes" => $likes,
                "numSongs" => $numSongs,
                "profileCompleted" => $milestones['profileProgress'],
                "milestones" => $milestones['milestones'],
                "recentSongs" => Songs::getSongsByArtist($data['id'], 5),
                "topFiveSongs" => Songs::getSongsByArtist($data['id'], 5, ['orderBy' => 'num_streams-desc']),
                "country" => Artist::getCountryWiseCount(),
                'view_map' => (isset($data['view_map']) && $country) ? $data['view_map'] : 0,
            ];
        }
        return $return;
    }

    public static function milestones($id, $likes, $numSongs, $streams)
    {
        $return = [];
        $msData = [
            '10_song' => "Upload 10 Songs",
            '1500_likes' => "Get 1,500 Likes",
            '15_song' => "Upload 15 Songs",
            '10000_streams' => "Get 10,000 Streams",
            '2500_likes' => "Get 2,500 Likes",
            // '50000_streams'=>"Get 50000 streams"
        ];
        $total = 0;
        $completed = 0;
        $songsTocalc = 0;
        $likesTocalc = 0;
        $streamsTocalc = 0;
        foreach ($msData as $key => $value) {
            $single = [
                "completed" => "0",
                "message" => $value,
            ];
            $keyArr = explode('_', $key);
            if (isset($keyArr[1])) {
                if ($keyArr[1] == 'song') {
                    if ($keyArr[0] <= $numSongs) {
                        $single['completed'] = "1";
                        $completed++;
                    } else {
                        // $completed += $numSongs/$keyArr[0];
                        $tocalc = $numSongs - $songsTocalc;
                        $fromcalc = $keyArr[0] - $songsTocalc;
                        if ($tocalc > 0) {
                            $completed += $tocalc / $fromcalc;
                        }
                    }
                    $songsTocalc = $keyArr[0];
                }

                if ($keyArr[1] == 'likes') {
                    if ($keyArr[0] <= $likes) {
                        $single['completed'] = "1";
                        $completed++;
                    } else {
                        $tocalc = $likes - $likesTocalc;
                        $fromcalc = $keyArr[0] - $likesTocalc;
                        if ($tocalc > 0) {
                            $completed += $tocalc / $fromcalc;
                        }
                    }
                    $likesTocalc = $keyArr[0];
                }
                if ($keyArr[1] == 'streams') {
                    if ($keyArr[0] <= $streams) {
                        $single['completed'] = "1";
                        $completed++;
                    } else {
                        // $completed += $streams/$keyArr[0];
                        $tocalc = $streams - $streamsTocalc;
                        $fromcalc = $keyArr[0] - $streamsTocalc;
                        if ($tocalc > 0) {
                            $completed += $tocalc / $fromcalc;
                        }
                    }
                    $streamsTocalc = $keyArr[0];
                }
            }
            $return[] = $single;
            $total++;
        }
        // die;
        $profileProgress = number_format(($completed / $total) * 100, 0);
        return ['milestones' => $return, 'profileProgress' => $profileProgress];
    }

    public static function getallArtistsListApi($page = "1", $search = "", $filter = [],$limitPage=10)
    {
        $artistData = Artist::where('role_id', '2')->where('is_active', 1)->where('is_verify', 1);
        if (!empty($search)) {
            $artistData = $artistData->where(function ($query2) use ($search) {
                $query2->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($page) {
            $limit = $limitPage;
            $offset = ($page - 1) * $limit;
            $artistData->offset($offset);
            $artistData->limit($limit);
        }

        if ($filter) {
            foreach ($filter as $key => $value) {
                $artistData->where($key, $value);
            }
        }

        $artistData = $artistData->whereNull('deleted_at')->get();
        $return = [];
        foreach ($artistData as $k => $v) {
            $return[] = [
                "id" => $v['id'],
                "name" => $v['firstname'] . ' ' . $v['lastname'],
                "image" => UserProfilePhoto::getProfilePhoto($v['id']),
                "detailUrlSlug" => url('artist/' . $v['slug'])
            ];
        }

        return ['data' => $return, 'page' => $page, 'limit' => $limit];
    }

    public static function getArtistsByDynamicGroup($id)
    {

        $groupData = DynamicGroups::find($id);
        $artistData = [];
        if ($groupData->view_all == 0) // Selected
        {
            $artistData = DynamicGroupItems::selectRaw('users.firstname,users.lastname,users.id,users.slug')->join('users', 'users.id', '=', 'dynamic_group_items.item_id')->where('dynamic_group_items.group_id', $id)->where('users.is_active', 1)->where('users.is_verify', 1)->whereNull('users.deleted_at')->get();
        } elseif ($groupData->view_all == 5) // New Artists
        { 
            $limit = $groupData->allow_max;
            $artistData = Artist::selectRaw('users.firstname,users.lastname,users.id,users.slug')->where('users.role_id', '2')->where('users.is_active', 1)->where('users.is_verify', 1)->whereNull('users.deleted_at')->orderBy('users.created_at', 'DESC')->limit($limit)->get();
        }
        $return = [];
        foreach ($artistData as $k => $v) {
            $return[] = [
                "id" => $v['id'],
                "name" => $v['firstname'] . ' ' . $v['lastname'],
                "image" => UserProfilePhoto::getProfilePhoto($v['id']),
                "detailUrlSlug" => url('artist/' . $v['slug'])
            ];
        }
        return $return;
    }

    public static function getDataForFooter($ids)
    {
        $data = self::selectRaw('id,CONCAT(firstname, " ", lastname) AS fullname,slug')->whereNull('deleted_at')->whereIn('id', explode(',', $ids))->get();
        $return = array();
        foreach ($data as $k => $v) {
            $return[$v->fullname] = route('artistDetail', $v->slug);
        }
        return $return;
    }

    public static function getAttrById($id, $attr = "")
    {
        $return = $id;
        $data = self::where('id', $id)->first();
        if ($data && isset($data->$attr)) {
            $return = $data->$attr;
        }
        return $return;
    }

    public static function getReferenceCode($id)
    {
        $return = '';
        $data = self::where('id', $id)->first();
        if ($data) {
            $string = $data->id . $data->unique_id . $data->role_id;
            $return = md5($string);
        }
        return $return;
    }

    public static function checkReferenceCode($string)
    {
        $return = 0;
        $data = self::where(DB::raw('md5(CONCAT(`id`,`unique_id`,`role_id`))'), $string)->first();
        if ($data) {
            $return = $data->id;
        }
        return $return;
    }

    public static function getAttrByEmail($id, $attr = "")
    {
        $return = 0;
        $data = self::where('email', $id)->first();
        if ($data && isset($data->$attr)) {
            $return = $data->$attr;
        }
        return $return;
    }
}
