<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use File;
use Image;

class MusicCategories extends Model
{
    use SoftDeletes;

    protected $table = 'music_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name','image','status','sort_order', 'seo_title', 'seo_meta_keyword', 'seo_description'
    ];


    public static function getList(){
        $return = self::selectRaw('id,name,image,sort_order')->where('status',1)->get();
        return $return;
    }

    public static function getSortOrder(){
        $return = self::selectRaw('sort_order')->where('status',1)->orderBy('sort_order','desc')->first();
        return $return?$return->sort_order+1:1;
    }

    public function getImageAttribute($image){
        $return = url('public/assets/frontend/img/'.config('app.default_image'));
        $path = public_path().'/assets/images/music-category/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/music-category/'.$image);
        }
        return $return;
    }

    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/music-category';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.musicCategoryIconDimension.width');
        $height = config('app.musicCategoryIconDimension.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/music-category/'.$oldData->image;
                if(file_exists($path)){
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }
        return $filename;
    }
    public static function getMusicCategoryListApi($search = "")
    {
        $return = [];
        $data = MusicCategories::where('status',1)->whereNull('deleted_at');
        if ($search)
        {
                $data->where(function ($query) use ($search)
                {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }
        $data = $data->get()->toArray();
        if ($data)
        {
            $data = self::formatMusicCategoryList($data);
        }
        $return = ['musicCategory' => $data];
        return $return;
    }
    public static function formatMusicCategoryList($data){
        $authId = User::getLoggedInId();
        $navigate = $authId ? "1" : "0";
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "navigate" => $navigate,
                "navigateType" => "17",
                "navigateTo" => "category/" . $value['id'],
                "name"=>$value['name'],
                "slug" => $value['slug'],
                "id" => (string) $value['id'],
                "image"=>$value['image'],
                "status"=>$value['status'],
                "sortOrder"=>$value['sort_order'],
            ];
        }
        return $return;
    }

    public static function getCategoryPhoto($categoryId)
    {
        $oldData = self::where('id', $categoryId)->first();
        if ($oldData) {
            return $oldData->image;
        }
    }


    public static function getCategoryById($categoryId)
    {
        $oldData = self::where('id', $categoryId)->first();
        if ($oldData) {
            $return = [
                "name" => $oldData->name,
                "slug" => $oldData->slug,
                "image" => self::getCategoryPhoto($oldData->id),
                "status" => $oldData->status,
                "sortOrder" => $oldData->sort_order,
                "seo_title" => $oldData->seo_title,
                "seo_meta_keyword" => $oldData->seo_meta_keyword,
                "seo_description" => $oldData->seo_description,
            ];
            return $return;
        }
    }

    public static function getDataForFooter($ids)
    {
        $data = self::selectRaw('id,name,slug')->whereNull('deleted_at')->whereIn('id', explode(',', $ids))->get();
        $return = array();
        foreach ($data as $k => $v) {
            $return[$v->name] = route('categoryDetails', $v->slug);
        }
        return $return;
    }
}
