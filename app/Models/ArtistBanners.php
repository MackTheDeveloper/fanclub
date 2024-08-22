<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class ArtistBanners extends Model
{
    use SoftDeletes;
    protected $table = 'artist_banners';

    protected $fillable = ['artist_id', 'file'];

    public static function getList($id)
    {
        $return = [];
        $data = self::where('artist_id',$id)->get();
        foreach ($data as $key => $value) {
            $return[] = ['key'=>$value->id,'file'=>$value->file];
        }
        return $return;
    }

    public function getFileAttribute($image){
        $return = url('public/assets/frontend/img/placeholder/rectangle_192_96.jpg');
        $path = public_path().'/assets/images/artist_banners/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/artist_banners/'.$image);
        }
        return $return;
    }

    public static function addNew($data){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['artist_id'] = $authId;
        $allowed = ['artist_id', 'file'];
        $data = array_intersect_key($data, array_flip($allowed));
        try{
            $create = new ArtistBanners();
            foreach ($data as $key => $value) {
                $create->$key = $value;
            }
            $create->save();
            $return = $create;
        }catch(\Exception $e){
            $return = $e->getMessage();
            $success = false;
        }
        return ['data'=>$return,'success'=>$success];
    }


    public static function uploadAndSaveBanner($fileObject)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;
        $filePath = public_path() . '/assets/images/artist_banners';
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.artistBannerDimentions.width');
        $height = config('app.artistBannerDimentions.height');
        if ($img->width() == $width && $img->height() == $height) {
            $photo->move($filePath . '/', $filename);
        } else {
            $img->resize($width, $height)->save($filePath . '/' . $filename);
        }
        
        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0){
            Admin::compressImages('local', $filePath . '/' . $filename);
        }

        return $filename;
    }

    public static function uploadAndSaveBannerViaCropped($fileObject,$input)
    {
        $ext = $fileObject->extension();
        $imageName = rand() . '_' . time() . '.' . $ext;
        $image_parts = explode(";base64,", $input['hiddenPreviewImg']);
        $image_base64 = base64_decode($image_parts[1]);
        $filePath = public_path() . '/assets/images/artist_banners';

        $image = Image::make($image_base64);
        // $image->scale(50);
        $width = config('app.artistBannerDimentions.width');
        $height = config('app.artistBannerDimentions.height');
        $image->resize($width, $height)->save($filePath . '/' . $imageName);

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0){
            Admin::compressImages('local', $filePath . '/' . $imageName);
        }

        return $imageName;
    }
}
