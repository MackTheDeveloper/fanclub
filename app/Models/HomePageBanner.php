<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use File;
use Image;

class HomePageBanner extends Model
{
    use HasFactory;
    protected $table = 'home_page_banners';
    protected $fillable = ['name', 'image', 'status', 'sortOrder', 'deleted_at', 'created_at', 'updated_at'];

    public static function getSortOrder()
    {
        $return = self::selectRaw('sortOrder')->where('is_active', 1)->orderBy('sortOrder', 'desc')->first();
        return $return ? $return->sortOrder + 1 : 1;
    }
    public function getImageAttribute($image)
    {
        $return = url('public/assets/frontend/img/placeholder/rectangle_654_368.jpg');
        $path = public_path() . '/assets/images/HomePageBanner/' . $image;
        if (file_exists($path) && $image) {
            $return = url('/public/assets/images/HomePageBanner/' . $image);
        }
        return $return;
    }
    public static function getListApi()
    {
        $return = [];
        $data = self::whereNull('deleted_at')->where('is_active', '1')->orderBy('sortOrder','asc')->get();
        $return = self::formatedList($data);
        return $return;
    }

    public static function getListType()
    {
        return ['1'=> "Dynamic Groups", '2' => "Artist", '0' => "None"];
    }

    public static function getNavigateTo($type,$id="")
    {
        $return = "";
        if ($type) {
            if ($type == '2') // Artist Detail Page
            {
                $return = 'artist-detail/' . $id;
            } else if ($type == '1') // Dynamic Group Detail Page
            {
                $groupData = DynamicGroups::getDynamicGroupDetail($id);
                if ($groupData) {
                    $urlPre = '';
                    if ($groupData->type == 1)
                        $urlPre = 'artists';
                    else if ($groupData->type == 2)
                        $urlPre = 'songs';
                    else if ($groupData->type == 4)
                        $urlPre = 'categories';
                    else if ($groupData->type == 5)
                        $urlPre = 'languages';

                    $return = $urlPre . '/' . $id;
                }
            }
        }
        // return ['1' => "Dynamic Groups", '2' => "Artist", '0' => "None"];
        return $return;
    }

    public static function getNavigateType($type, $id)
    {
        $return = "";
        if ($type) {
            if ($type == '2') // Artist Detail Page
            {
                $return = '13';
            } else if ($type == '1') // Dynamic Group Detail Page
            {
                $groupData = DynamicGroups::getDynamicGroupDetail($id);
                if ($groupData) {
                    if ($groupData->type == 1)
                        $return = '12';
                    else if ($groupData->type == 2)
                        $return = '14';
                    else if ($groupData->type == 3)
                        $return = '20';
                    else if ($groupData->type == 4)
                        $return = '0';
                    else if ($groupData->type == 5)
                        $return = '0';
                }
            }
        }
        // return ['1' => "Dynamic Groups", '2' => "Artist", '0' => "None"];
        return $return;
    }
    
    public static function formatedList($data)
    {
        $return = [];
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $navigateGuest = "1";
        foreach ($data as $key => $value) {
            $return[] = [
                "Id" => $value['id'],
                "Name" => $value['name'],
                "navigate" => $navigateGuest,
                // "navigateType" => "6",
                "navigateType" => self::getNavigateType($value['type'], $value['type_id']),
                "navigateTo" => self::getNavigateTo($value['type'], $value['type_id']),
                "Image" =>  $value['image'],
                "bannerType" =>  $value['type'],
                "bannerTypeId" =>  $value['type_id'],
                "bannerUrl" => $value['type'] != '3' ? self::getBannerUrl($value['type'], $value['type_id']) : '#',
                "sortOrder" => $value['sortOrder'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

    public static function uploadAndSaveImage($fileObject, $id = '')
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;
        $filePath = public_path() . '/assets/images/HomePageBanner';
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        // $width = config('app.homePageImageHeight.width');
        $width = config('app.homepageBannerDimentions.width');
        // $height = config('app.homePageImageHeight.height');
        $height = config('app.homepageBannerDimentions.height');
        if ($img->width() == $width && $img->height() == $height) {
            $photo->move($filePath . '/', $filename);
        } else {
            //$img->resize($width, $height)->save($filePath . '/' . $filename);
            $photo->move($filePath . '/', $filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path() . '/assets/images/HomePageBanner/' . $oldData->image;
                if (file_exists($path)) {
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0)
            Admin::compressImages('local', $filePath . '/' . $filename);

        return $filename;
    }

    public static function getBannerUrl($type = '', $typeId = '')
    {
        if ($type == '2') // Artist Detail Page
        {
            $artistData = Artist::getArtistDetail($typeId);
            if ($artistData) {
                return url('artist/' . $artistData->slug);
            }
        } else if ($type == '1') // Dynamic Group Detail Page
        {
            $groupData = DynamicGroups::getDynamicGroupDetail($typeId);
            if ($groupData) {
                $urlPre = '';
                if ($groupData->type == 1)
                    $urlPre = 'artists';
                else if ($groupData->type == 2)
                    $urlPre = 'songs';
                else if ($groupData->type == 4)
                    $urlPre = 'categories';
                else if ($groupData->type == 5)
                    $urlPre = 'languages';

                return url($urlPre . '/' . $groupData->slug);
            }else{
                return 'javascript:void(0)';
            }
        }
        return 'javascript:void(0)';
    }
}
