<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Response;

class HomePageComponent extends Model
{
  use HasFactory;
  protected $table = 'home_page_components';
  protected $fillable = ['name', 'type', 'text', 'visibility', 'banner_image', 'dynamic_group_id', 'status', 'sort_order', 'deleted_at', 'created_at', 'updated_at'];

  public static function getSortOrder()
  {
    $status = '1';
    $return = self::selectRaw('sort_order')->where('status', $status)->whereNull('deleted_at')->orderBy('sort_order', 'desc')->first();
    return $return ? $return->sort_order + 1 : 1;
  }
  public static function getListApi($sequence=1)
  {
    $return = [];
    $authId = User::getLoggedInId();
    $data = self::whereNull('deleted_at')->where('status', '1')->orderBy('sort_order', 'ASC');
    if ($authId) {
      $data->whereIn('visibility', ['1', '3']);
    } else {
      $data->whereIn('visibility', ['1', '2']);
    }
    $data = $data->get();
    $return = self::formatedList($data, $sequence);
    return $return;
  }
  public static function formatedList($data, $sequence=1)
  {
    $return = [];
    $authId = User::getLoggedInId();
    $listIds = [];
    foreach ($data as $key => $value) {
      $id = str_replace(' ', '-', strtolower($value['name']));
      $id = self::checkExistReturnUnique($listIds, $id);
      $listIds[] = $id;
      $navigate = ($authId && $value['dynamic_group_id']!="") ? "1" : "0";
      $navigateGuest = "1";
      $dynamicGroupItemsList = [];
      if (!empty($value['dynamic_group_id']))
        $dynamicGroupItemsList = DynamicGroupItems::getDynamicGroupItems($value['dynamic_group_id']);
      $artistname = '';
      if (isset($value['artistfisrtname']))
        $artistname = $value['artistfisrtname'] . ' ' . $value['artistlastname'];
      $data = [
        "componentId" => $value['id'],
        "componentSlug" => $id,
        // "navigate" => $navigateGuest,
        "navigate" => $navigateGuest?DynamicGroups::getNavigateFlag($value['dynamic_group_id']):"0",
        "navigateType" => DynamicGroups::getNavigateType($value['dynamic_group_id']),
        "navigateTo" => DynamicGroups::getDyamicGroupNavigate($value['dynamic_group_id']),
        "imageShape" => DynamicGroups::getAttrImageShape($value['dynamic_group_id']),
        "componentName" => $value['name'],
        "componentArtistName" => $artistname,
        "componentType" => $value['type'],
        "componentText" => $value['text'],
        "componentBannerUrlType" => $value['banner_url_type'],
        "componentBannerUrlTypeId" => $value['banner_url_type_id'],
        "componentBanner" => url("public/assets/images/homepagecomponentbanner") . '/' . $value['banner_image'],
        "componentBannerUrl" => $value['banner_url_type'] != 0 ? self::getBannerUrl($value['banner_url_type'], $value['banner_url_type_id']) : '#',
        "dynamicGroupId" => $value['dynamic_group_id'],
        "dynamicGroupSlug" => $value['slug'],
        "componentDynamicGroup" => $dynamicGroupItemsList,
        "componentSortOrder" => $value['sort_order'],
        "createdAt" => getFormatedDate($value['created_at']),
      ];
      if ($value['dynamic_group_id'] == "" || !empty($dynamicGroupItemsList)) {
        $return[] = [
          "sequenceId" => "" . $sequence . "",
          // "sequenceId" => "" . $value['sort_order'] . "",
          "isActive" => ($value['type']=="1")?"0":"1",
          "componentData" => $data,
        ];
      }
      $sequence++;
    }
    // pre($listIds);
    return $return;
  }

  public static function getBannerUrl($type = '', $typeId = '')
  {
    if ($type == 1) // Signup
    {
      return url('signup');
    } else if ($type == 2) // Artist Detail Page
    {
      $artistData = Artist::getArtistDetail($typeId);
      return url('artist/' . $artistData->slug);
    }
    return '';
  }

  public static function getHomePageHeaderMenuData($countMyPlaylist = 0, $countMyFavouritePlaylist = 0, $countMyCollection = 0, $countMyArtist = 0, $countMyRecent = 0)
  {
    $authId = User::getLoggedInId();
    $homePageHeaderMenuData = array();
    if ($authId) {
      if ($countMyRecent)
        $homePageHeaderMenuData[] = ["key" => 'Recently Played', "value" => 'recently-played',"isApp"=>'1'];
      if ($countMyPlaylist)
        $homePageHeaderMenuData[] = ["key" => 'My Playlists', "value" => 'my-playlists',"isApp"=>'1'];
      if ($countMyArtist)
        $homePageHeaderMenuData[] = ["key" => 'My Artists', "value" => 'my-artists',"isApp"=>'1'];
      if ($countMyCollection)
        $homePageHeaderMenuData[] = ["key" => 'My Collection', "value" => 'my-collection',"isApp"=>'0'];
      if ($countMyFavouritePlaylist)
          $homePageHeaderMenuData[] = ["key" => 'fanclub Playlists', "value" => 'fav-playlists',"isApp"=>'1'];
    } else {
      $homePageHeaderMenuData[] = ["key" => 'How It Works', "value" => 'how-it-works',"isApp"=>'1'];
      $homePageHeaderMenuData[] = ["key" => 'Why Choose Fanclub', "value" => 'whyChoose',"isApp"=>'1'];
    }
    $homePageHeaderMenuDataFromComponent = self::getMenuFromComponent();
    return array_merge($homePageHeaderMenuData, $homePageHeaderMenuDataFromComponent);
  }

  public static function getSidebarMenuData()
  {
    $sideBarMenuData = array();
    $authId = User::getLoggedInId();
    if ($authId) {
      $sideBarMenuData[] = ["key" => 'My Playlists', "value" => 'myplaylist'];
      $sideBarMenuData[] = ["key" => 'Favourite Playlists', "value" => 'favourite-playlist'];
      $sideBarMenuData[] = ["key" => 'My Collection', "value" => 'my-favourite'];
      $sideBarMenuData[] = ["key" => 'My Artists', "value" => 'my-artists'];

      /* $sideBarMenuData['My Playlists'] = '#';
      $sideBarMenuData['Favourite Playlists'] = url('favourite-playlist');
      $sideBarMenuData['My Collection'] = '#';
      $sideBarMenuData['My Artists'] = url('my-artists'); */
    } else {
      $sideBarMenuData = self::getMenuFromComponent();
    }
    return $sideBarMenuData;
  }

  public static function getMenuFromComponent()
  {
    $return = [];
    $authId = User::getLoggedInId();
    $data = self::selectRaw('name,type,dynamic_group_id')->whereNull('deleted_at')->where('status', '1')->orderBy('sort_order', 'ASC');
    if ($authId) {
      $data->whereIn('visibility', ['1', '3']);
    } else {
      $data->whereIn('visibility', ['1', '2']);
    }
    $data = $data->get();
    $listIds = array();
    $componentData = array();
    $notForApp = ['why-choose-fanclub'];
    foreach ($data as $key => $value) {
      $id = str_replace(' ', '-', strtolower($value['name']));
      // $key = array_search($id, array_column($componentData, 'value'));
      $id = self::checkExistReturnUnique($listIds,$id);
      // $counts = DynamicGroupItems::getGrpHasItems($groupId)
      if ($value['type']=='3') {
        $counts = DynamicGroupItems::getGrpHasItems($value['dynamic_group_id']);
        if ($counts) {
          $componentData[] = ["key" => $value['name'], "value" => $id,"isApp" => (in_array($id, $notForApp))?"0":"1"];
          $listIds[] = $id;
        }
      }else {
        $componentData[] = ["key" => $value['name'], "value" => $id, "isApp" => (in_array($id, $notForApp)) ? "0" : "1"];
        $listIds[] = $id;
      }
    }
    // pre($listIds);
    return $componentData;
  }

  public static function checkExistReturnUnique($componentData,$string){
    // $key = array_search($string, array_column($componentData, 'value'));
    if (in_array($string, $componentData)) {
      $string = $string.'1';
      $string = self::checkExistReturnUnique($componentData, $string);
      return $string;
    }else{
      return $string;
    }
  }
}
