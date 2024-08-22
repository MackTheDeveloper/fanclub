<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Hash;
// use Laravel\Sanctum\HasApiTokens;

class DynamicGroupItems extends Model
{
  // use HasApiTokens;
  // use HasFactory;
  // use HasProfilePhoto;
  // use HasTeams;
  // use TwoFactorAuthenticatable;
  use SoftDeletes;
  protected $table = 'dynamic_group_items';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'group_id', 'item_id', 'type', 'created_at', 'updated_at', 'deleted_at'
  ];
  public function song()
  {
    return $this->hasOne(Songs::class, 'id', 'item_id');
  }

  public function genre()
  {
    return $this->hasOne(MusicGenres::class, 'id', 'item_id');
  }

  public function categories()
  {
    return $this->hasOne(MusicCategories::class, 'id', 'item_id');
  }

  public function artist()
  {
    return $this->hasOne(Artist::class, 'id', 'item_id');
  }

  public function activeArtist()
  {
    return $this->hasOne(User::class, 'id', 'item_id')->where('is_verify', 1)->where('is_active', 1);
  }

  public function group()
  {
    return $this->hasOne(DynamicGroups::class, 'id', 'group_id');
  }

  public static function getDynamicGroupItems($id, $type = '')
  {
    $return = [];
    $viewAll = 0;
    //$dynamicGroup = DynamicGroups::find($id);
    $dynamicGroup = DynamicGroups::where('id', $id)->whereNull('deleted_at')->where('status', '1')->first();
    if ($dynamicGroup) {
      $viewAll = $dynamicGroup->view_all;
      if (empty($type)) {
        $data = DynamicGroups::select('type')->where('id', $id)->whereNull('deleted_at')->first();
        if ($data)
          $type = $data->type;
      }
      switch ($type) {
        case '1':
          if ($viewAll) {
            if ($viewAll == 1) // View All
            {
              $data = Artist::selectRaw('users.*,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,users.firstname as fullName,users.firstname as name,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,users.slug as detailUrlSlug,"' . $dynamicGroup->view_all . '" as view_all')->where('users.role_id', '2')->where('users.is_active', 1)->where('users.is_verify', 1)->whereNull('users.deleted_at')->limit(12)->get();
            } elseif ($viewAll == 5) // New Artists
            {
              $limit = $dynamicGroup->allow_max;
              $data = Artist::selectRaw('users.*,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,users.firstname as fullName,users.firstname as name,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,users.slug as detailUrlSlug,"' . $dynamicGroup->view_all . '" as view_all')->where('users.role_id', '2')->where('users.is_active', 1)->where('users.is_verify', 1)->whereNull('users.deleted_at')->orderBy('users.created_at', 'DESC')->limit($limit)->get();
            }
          } else {
            $data = self::selectRaw('users.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,users.firstname as fullName,users.firstname as name,dynamic_groups.type as groupType,dynamic_groups.slug as slugName,users.slug as detailUrlSlug,dynamic_groups.view_all')->leftjoin('users', 'users.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->where('users.is_active', 1)->where('users.is_verify', 1)->whereNull('users.deleted_at')->get();
          }
          break;
        case '2':
          if ($viewAll) {
            if ($viewAll == 1) // View All
            {
              $data = Songs::has('activeArtist')->selectRaw('songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,"' . $dynamicGroup->view_all . '" as view_all')->leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->limit(12)->get();
            } elseif ($viewAll == 2) // Latest Performances
            {
              $limit = $dynamicGroup->allow_max;
              $data = Songs::has('activeArtist')->selectRaw('songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,"' . $dynamicGroup->view_all . '" as view_all')->leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('songs.created_at', 'DESC')->limit($limit)->get();
            } elseif ($viewAll == 3) // Trending Now
            {
              $limit = $dynamicGroup->allow_max;
              $data = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,"' . $dynamicGroup->view_all . '" as view_all')
                ->leftjoin('song_views', 'song_views.song_id', 'songs.id')
                ->leftjoin('users', 'songs.artist_id', 'users.id')->whereBetween('song_views.created_at', [\DB::raw('adddate(now(),-14)'), \DB::raw('now()')])->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id')->limit($limit)->get();
                // pre($data);
            } elseif ($viewAll == 4) // Top Songs
            {
              $limit = $dynamicGroup->allow_max;
              $data = Songs::has('activeArtist')->selectRaw('COUNT(*) as num_stream,songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,"' . $dynamicGroup->name . '" as groupName,"' . $dynamicGroup->id . '" as groupId,"' . $dynamicGroup->image_shape . '" as image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,"' . $dynamicGroup->type . '" as groupType,"' . $dynamicGroup->slug . '" as slugName,"' . $dynamicGroup->view_all . '" as view_all')
                ->join('song_views', 'song_views.song_id', 'songs.id')
                ->leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id')->limit($limit)->get();
            }
          } else {
            $data = self::selectRaw('songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,dynamic_groups.type as groupType,dynamic_groups.slug as slugName,dynamic_groups.view_all')->leftjoin('songs', 'songs.id', '=', 'dynamic_group_items.item_id')->leftjoin('users', 'songs.artist_id', 'users.id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->where('users.is_active',1)->where('users.is_verify', 1)->whereNull('dynamic_group_items.deleted_at')->get();
          }
          break;
        case '3':
          $data = self::selectRaw('music_genres.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_genres.name as fullName,dynamic_groups.type as groupType,music_genres.image as icon,music_genres.slug as detailUrlSlug')->leftjoin('music_genres', 'music_genres.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->whereNull('music_genres.deleted_at')->where('music_genres.status','1')->get();
          break;
        case '4':
          $data = self::selectRaw('music_categories.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_categories.name as fullName,dynamic_groups.type as groupType,music_categories.image as icon,music_categories.slug as detailUrlSlug')->leftjoin('music_categories', 'music_categories.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->whereNull('music_categories.deleted_at')->where('music_categories.status', '1')->get();
          // pre($data);
          break;
        case '5':
          $data = self::selectRaw('music_languages.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_languages.name as fullName,dynamic_groups.type as groupType,music_languages.image as icon')->leftjoin('music_languages', 'music_languages.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->whereNull('music_languages.deleted_at')->where('music_languages.status', '1')->get();
          break;
      }
      $return = self::formatedList($data);
      // pre($return);
      return $return;
    }
    return $return;
  }
  public static function getviewAll($type, $id)
  {
    $return = [];
    switch ($type) {
      case '1':
        $data = self::selectRaw('users.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,CONCAT(users.firstname," ",users.lastname) as name,dynamic_groups.type as groupType,dynamic_groups.slug as slugName,users.slug as detailUrlSlug,user_profile_photos.image as icon')->leftjoin('users', 'users.id', '=', 'dynamic_group_items.item_id')->leftjoin('user_profile_photos', 'user_profile_photos.user_id', '=', 'users.id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->get();
        break;
      case '2':
        $data = self::selectRaw('songs.*,users.firstname as artistfisrtname,users.lastname as artistlasttname,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,CONCAT(users.firstname," ",users.lastname) as fullName,dynamic_groups.type as groupType,songs.icon as icon')->leftjoin('songs', 'songs.id', '=', 'dynamic_group_items.item_id')->leftjoin('users', 'songs.artist_id', 'users.id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->get();
        break;
      case '3':
        $data = self::selectRaw('music_genres.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_genres.name as fullName,dynamic_groups.type as groupType,music_genres.image as icon,music_genres.slug as detailUrlSlug')->leftjoin('music_genres', 'music_genres.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->get();
        break;
      case '4':
        $data = self::selectRaw('music_categories.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_categories.name as fullName,dynamic_groups.type as groupType,music_categories.image as icon')->leftjoin('music_categories', 'music_categories.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->get();
        break;
      case '5':
        $data = self::selectRaw('music_languages.*,dynamic_groups.name as groupName,dynamic_groups.id as groupId,dynamic_groups.image_shape,music_languages.name as fullName,dynamic_groups.type as groupType,music_languages.image as icon')->leftjoin('music_languages', 'music_languages.id', '=', 'dynamic_group_items.item_id')->leftjoin('dynamic_groups', 'dynamic_groups.id', '=', 'dynamic_group_items.group_id')->where('dynamic_group_items.group_id', $id)->whereNull('dynamic_group_items.deleted_at')->whereNull('music_languages.deleted_at')->get();
        break;
    }
    $return = self::formatedList($data);
    return $return;
  }
  public static function formatedList($data)
  {
    $return['data'] = [];
    $authId = User::getLoggedInId();
    $navigate = ($authId) ? "1" : "0";
    $navigateGuest = "1";
    $i = 0;
    foreach ($data as $key => $value) {
      switch ($value['image_shape']) {
        case '1':
          $shapeName = 'Square';
          break;
        case '2':
          $shapeName = 'Circle';
          break;
        case '3':
          $shapeName = 'Rectangle';
          break;
      }
      $return['commonDetails'] = [
        "ImageShape" => $shapeName,
        "groupType" => $value->groupType,
        "viewAll" => $value->view_all ?: 0,
        "DynamicGroupSlug" => $value['slugName'],
      ];

      $icon = '';
      $page = '';
      $download = '';
      $inList = "1";
      if ($value->groupType == '1') //Artist
      {
        $icon = UserProfilePhoto::getProfilePhoto($value['id']);
        // $icon = UserProfilePhoto::getProfilePhoto($value['id'], 'round_192_192.png');
        $detailUrl = url('artist/' . $value['detailUrlSlug']);
        $detailUrl2 = 'artist-detail/' . $value['id'];
        $navigateType = "13";
      } else if ($value->groupType == '2') // Song
      {
        $icon = Songs::getSongPhoto($value['id']);
        $detailUrl = url('#');
        $detailUrl2 = '#';
        $navigateType = "15";
        $page = Songs::getPageById(5);
        $download = Songs::getDownloadUrl($value['id']);
        // $navigateGuest = $navigate;
        
        $navigateType = "5";
        $detailUrl2 = "fanclub-group/" . $value['groupId'];
              // "navigateTo" => "fanclub-group/".$value['group']['id'],
      } else if ($value->groupType == '3') //Genre
      {
        $icon = MusicGenres::getGenrePhoto($value['id']);
        $detailUrl = url('genre/' . $value['detailUrlSlug']);
        $detailUrl2 = 'genre/' . $value['id'];
        $navigateType = "17";
      } else if ($value->groupType == '4') {
        $icon = MusicCategories::getCategoryPhoto($value['id']);
        $detailUrl = url('category/' . $value['detailUrlSlug']);
        $detailUrl2 = 'category/' . $value['id'];
        $navigateType = "17";
        $inList = Songs::songHasCategory($value['id']);
      } else if ($value->groupType == '5') {
        $detailUrl = url('');
        $detailUrl2 = '';
        $navigateType = "0";
      }

      if($inList){
        $return['data'][$i] = [
          "Id" => $value['id'],
          "Name" => $value['name'],
          "page" => $page,
          "download" => $download,
          "navigate" => $navigateGuest,
          "navigateType" => $navigateType,
          "navigateTo" => $detailUrl2,
          // "navigateTo" => DynamicGroups::getDyamicGroupNavigate($value['dynamic_group_id']),
          "fullName" => $value['fullName'],
          "detailUrl" => $detailUrl,
          "DynamicGroupId" => $value['groupId'],
          "DynamicGroupName" => $value['groupName'],
          //"Icon" => $value['icon'],
          "Icon" => $icon,
          "ImageShape" => $shapeName,
          "createdAt" => getFormatedDate($value['created_at']),
        ];
        $i++;
      }
    }
    // pre($return,1);
    return $return;
  }

  public static function getGroupIcon($groupId, $view_all="")
  {
    $return = url('public/assets/frontend/img/' . config('app.default_image'));
    if ($view_all=='0' || $view_all == '') {
      $data = self::has('song')->has('song.artist')->where('group_id', $groupId)->first();
      if ($data) {
        $return = $data->song->icon;
      }
    }else{
      if ($view_all == '4') {
        $data = Songs::selectRaw('COUNT(*) as num_stream,songs.*')
          ->join('song_views', 'song_views.song_id', 'songs.id')
          ->leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id')->first();
        if ($data) {
          $return = $data->icon;
        }
      } else if ($view_all == '3') {
        $data = Songs::selectRaw('COUNT(*) as num_stream,songs.*')
          ->join('song_views', 'song_views.song_id', 'songs.id')
          ->leftjoin('users', 'songs.artist_id', 'users.id')->whereBetween('song_views.created_at', [\DB::raw('adddate(now(),-14)'), \DB::raw('now()')])->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('num_stream', 'DESC')->groupBy('song_views.song_id')->first();
        if ($data) {
          $return = $data->icon;
        }
      } else if ($view_all == '2') {
        $data = Songs::leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('songs.created_at', 'DESC')->first();
        if ($data) {
          $return = $data->icon;
        }
      } else if ($view_all == '1') {
        $data = Songs::leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('songs.name', 'ASC')->first();
        if ($data) {
          $return = $data->icon;
        }
      }
    }
    return $return;
  }

  public static function getGrpTotal($groupId,$view_all="",$limit="")
  {
    $return = 0;
    if ($view_all=='0' || $view_all == '') {
      $data = self::has('song')->where('group_id', $groupId)->count();
      $return = $data;
    }else{
      // pre($view_all);
      if ($view_all == '4') {
        $data = Songs::selectRaw('COUNT(*) as num_stream,songs.*')
          ->join('song_views', 'song_views.song_id', 'songs.id')
          ->leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->groupBy('song_views.song_id')->get()->count();
        if ($data) {
          $return = ($data > $limit && $limit) ? $limit : $data;
        }
      } else if ($view_all == '3') {
        $data = Songs::selectRaw('COUNT(*) as num_stream,songs.*')
          ->join('song_views', 'song_views.song_id', 'songs.id')
          ->leftjoin('users', 'songs.artist_id', 'users.id')->whereBetween('song_views.created_at', [\DB::raw('adddate(now(),-14)'), \DB::raw('now()')])->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->groupBy('song_views.song_id')->get()->count();
        if ($data) {
          // pre($data);
          $return = ($data > $limit && $limit) ? $limit : $data;
        }
      } else if ($view_all == '2') {
        $data = Songs::leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('songs.created_at', 'DESC')->count();
        if ($data) {
          $return = ($data > $limit && $limit) ? $limit : $data;
        }
      } else if ($view_all == '1') {
        $data = Songs::leftjoin('users', 'songs.artist_id', 'users.id')->whereNull('users.deleted_at')->whereNull('songs.deleted_at')->orderBy('songs.name', 'ASC')->count();
        if ($data) {
          $return = ($data > $limit && $limit) ? $limit : $data;
        }
      }
    }
    // pre($data);
    return $return;
  }

  public static function getGrpHasItems($groupId)
  {
    $data = 0;
    $type = self::where('group_id', $groupId)->first();
    $dynamicGroupData = DynamicGroups::find($groupId);
    if ($dynamicGroupData->type) {
      if ($dynamicGroupData->type == "1") {
        $data = self::has('activeArtist')->where('group_id', $groupId)->count();
        if (!$data)
          $data = $dynamicGroupData->view_all;
      } elseif ($dynamicGroupData->type == "2") {
        $data = self::has('song')->where('group_id', $groupId)->count();
        if (!$data)
          $data = $dynamicGroupData->view_all;
      } elseif ($dynamicGroupData->type == "3") {
        $data = self::has('genre')->where('group_id', $groupId)->count();
      } elseif ($dynamicGroupData->type == "4") {
        $data = self::has('categories')->where('group_id', $groupId)->count();
      }
    }
    return $data;
  }
}
