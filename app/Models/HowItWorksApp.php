<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;


class HowItWorksApp extends Model
{
    use SoftDeletes;

    protected $table = 'how_it_works_app';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','type','image','status','title','description'
    ];

    public function getImageAttribute($image){
        $return = url('public/assets/frontend/img/'.config('app.default_image'));
        $path = public_path().'/assets/images/how-it-works-app/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/how-it-works-app/'.$image);
        }
        return $return;
    }

    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/how-it-works-app';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.howitworksapp.width');
        $height = config('app.howitworksapp.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/how-it-works-app/'.$oldData->image;
                if(file_exists($path)){
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }
        return $filename;
    }
    public static function getListApi(){
        $return = [];
        $data = self::whereNull('deleted_at')->get();
        $return = self::formatedList($data);
        return $return;
    }
    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "Id" => $value['id'],
                "Type" => $value['type'],
                "Image" => $value['image'],
                "createdAt" => getFormatedDate($value['created_at']),
            ];
        }
        return $return;
    }

}
