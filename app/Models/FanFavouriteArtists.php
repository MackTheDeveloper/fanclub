<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
// use Illuminate\Database\Eloquent\SoftDeletes;

class FanFavouriteArtists extends Model
{
    protected $table = 'fan_favourite_artists';

    protected $fillable = ['fan_id', 'artist_id'];

    public function artist()
    {
        return $this->hasOne(Artist::class, 'id', 'artist_id')->where('users.is_active', 1);
    }

    public function activeArtist()
    {
        return $this->hasOne(User::class, 'id', 'artist_id')->where('users.is_verify', 1)->where('users.is_active', 1);
    }

    public function fan()
    {
        return $this->hasOne(Fan::class, 'id', 'fan_id');
    }

    public static function checkArtistLiked($artistId)
    {
        $authId = User::getLoggedInId();
        $data = self::where('artist_id', $artistId)->where('fan_id', $authId)->first();
        if ($data) {
            return 1;
        }
        return 0;
    }

    public static function getListApi($page = "1", $search = "", $filter = [], $limits = "")
    {
        $return = [];
        $data = self::has('activeArtist');
        $data->whereHas('activeArtist', function ($q) use ($search) {
            $q->where('is_active', 1)->where('is_verify', 1);
            if (!empty($search)) {
                $q->where('firstname', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%');
            }
        });
        if ($limits) {
            $data->limit($limits);
        } else {
            if ($page) {
                $limit = 10;
                $offset = ($page - 1) * $limit;
                $data->offset($offset);
                $data->limit($limit);
            }
        }

        if ($filter) {
            foreach ($filter as $key => $value) {
                $data->where($key, $value);
            }
        }
        $data = $data->get()->sortBy('artist.firstname', SORT_REGULAR, false);
        $return = self::formatedList($data, $page);
        if ($limits) {
            return $return;
        } else {
            return ['data' => $return, 'page' => $page, 'limit' => $limit];
        }
    }

    public static function formatedList($data, $page = "1")
    {
        $return = [];
        $introducer = 0;
        if ($page == '1') {
            $authId = User::getLoggedInId();
            $introducer = User::getAttrById($authId, 'introducer_id');
            // pre($introducer);
            $artist = Artist::find($introducer);
            if ($artist) {
                $return[] = [
                    "artistId" => $artist->id,
                    "navigate" => "1",
                    "navigateType" => "13",
                    "navigateTo" => "artist-detail/" . $artist->id,
                    "artistFirstName" => $artist->firstname,
                    "artistLastName" => $artist->lastname,
                    "artistFullName" => $artist->firstname . ' ' . $artist->lastname,
                    "name" => $artist->firstname . ' ' . $artist->lastname,
                    "artistSlug" => $artist->slug,
                    "artistProfilePic" => UserProfilePhoto::getProfilePhoto($artist->id),
                    "profilePic" => UserProfilePhoto::getProfilePhoto($artist->id),
                    "image" => UserProfilePhoto::getProfilePhoto($artist->id),
                    "detailUrlSlug" => url('artist/' . $artist->slug),
                    // "createdAt" => getFormatedDate($value['created_at']),
                ];
            }
        }
        foreach ($data as $key => $value) {
            if ($introducer != $value['artist']['id']) {
                # code...
                $return[] = [
                    "artistId" => $value['artist']['id'],
                    "navigate" => "1",
                    "navigateType" => "13",
                    "navigateTo" => "artist-detail/" . $value['artist']['id'],
                    "artistFirstName" => $value['artist']['firstname'],
                    "artistLastName" => $value['artist']['lastname'],
                    "artistFullName" => $value['artist']['firstname'] . ' ' . $value['artist']['lastname'],
                    "name" => $value['artist']['firstname'] . ' ' . $value['artist']['lastname'],
                    "artistSlug" => $value['artist']['slug'],
                    "artistProfilePic" => UserProfilePhoto::getProfilePhoto($value['artist']['id']),
                    "profilePic" => UserProfilePhoto::getProfilePhoto($value['artist']['id']),
                    "image" => UserProfilePhoto::getProfilePhoto($value['artist']['id']),
                    "detailUrlSlug" => url('artist/' . $value['artist']['slug']),
                    "createdAt" => getFormatedDate($value['created_at']),
                ];
            }
        }
        return $return;
    }
    public static function searchAPIFanFavoriteArtist($search)
    {
        $data = [];
        $authId = User::getLoggedInId();
        $artistdata = FanFavouriteArtists::leftjoin('users', 'users.id', 'fan_favourite_artists.artist_id')->where('users.role_id', '2')->where('fan_favourite_artists.fan_id', $authId)->whereNull('users.deleted_at');
        if ($search) {
            $artistdata->where(function ($query) use ($search) {
                $query->where('users.firstname', 'like', '%' . $search . '%')
                    ->orWhere('users.lastname', 'like', '%' . $search . '%');
            });
        }
        $artistdata = $artistdata->orderBy('fan_favourite_artists.created_at', 'DESC')->get()->toArray();
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
        $return = [];
        // pre($artistdata);
        foreach ($artistdata as $key => $value) {
            $return[] = [
                "navigate" => $navigate,
                "navigateType" => "11",
                "navigateTo" => "artist-detail/" . $value['artist_id'],
                "name" => $value['firstname'] . ' ' . $value['lastname'],
                "email" => $value['email'],
                "artist_id" => $value['artist_id'],
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
}
