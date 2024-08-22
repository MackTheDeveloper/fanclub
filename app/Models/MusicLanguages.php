<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class MusicLanguages extends Model
{
    use SoftDeletes;

    protected $table = 'music_languages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'id','name','image','status','sort_order'
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
        $path = public_path().'/assets/images/music-language/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/music-language/'.$image);
        }
        return $return;
    }


    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/music-language';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.musicLanguageIconDimension.width');
        $height = config('app.musicLanguageIconDimension.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/music-language/'.$oldData->image;
                if(file_exists($path)){
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }
        return $filename;
    }

    public static function getMusicLanguageList($search = "")
    {
        $return = [];
        $data = MusicLanguages::where('status',1)->whereNull('deleted_at');
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        $data = $data->get()->toArray();
        if ($data)
        {
            $data = self::formatMusicLanguageData($data);
        }
        $return = ['musicGenre' => $data];
        return $return;
    }
    public static function formatMusicLanguageData($data){
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "name"=>$value['name'],
                "image"=>$value['image'],
                "status"=>$value['status'],
                "sortOrder"=>$value['sort_order'],
            ];
        }
        return $return;
    }


}
