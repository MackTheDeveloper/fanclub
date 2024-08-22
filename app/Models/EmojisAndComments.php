<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class EmojisAndComments extends Model
{
    use SoftDeletes;

    protected $table = 'emoji_comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','type','image','comment','status','sort_order'
    ];

    public function getImageAttribute($image){
        $return = url('public/assets/frontend/img/'.config('app.default_image'));
        $path = public_path().'/assets/images/emojis_icon/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/emojis_icon/'.$image);
        }
        return $return;
    }

    public static function getSortOrder(){
        $return = self::selectRaw('sort_order')->where('status',1)->orderBy('sort_order','desc')->first();
        // $return = self::selectRaw('sort_order')->where('type',$type)->orderBy('sort_order','desc')->first();
        return $return?$return->sort_order+1:1;
    }

    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/emojis_icon/';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.emojiIcon.width');
        $height = config('app.emojiIcon.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/emojis_icon/'.$oldData->image;
                if(file_exists($path)){
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }
        return $filename;
    }
    public static function getProfilePhoto($userId)
    {
        $return = url('public/assets/frontend/img/'.config('app.default_image'));
        $oldData = EmojisAndComments::where('id', $userId)->first();
        if ($oldData) {
                $return = $oldData->image;
        }
        return $return;
    }

}
