<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Image;
use File;

class UserProfilePhoto extends Model
{
    use HasFactory;

    protected $table = 'user_profile_photos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
    ];

    public static function uploadAndSaveProfilePhoto($fileObject, $userId)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;
        $filePath = public_path() . '/assets/images/user_profile';
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.userImageDimention.width');
        $height = config('app.userImageDimention.height');
        if ($img->width() == $width && $img->height() == $height) {
            $photo->move($filePath . '/', $filename);
        } else {
            $img->resize($width, $height)->save($filePath . '/' . $filename);
        }
        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $oldData->image = $filename;
            $oldData->save();
        } else {
            $user_profile = new UserProfilePhoto;
            $user_profile->user_id = $userId;
            $user_profile->image = $filename;
            $user_profile->save();
        }

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0)
            Admin::compressImages('local', $filePath . '/' . $filename);

        return 1;
    }

    public static function uploadAndSaveProfilePhotoApi($imageEncoded, $userId)
    {
        $image_parts = explode(";base64,", $imageEncoded);
        $image_base64 = base64_decode($image_parts[1]);
        $ext = str_replace('data:image/', '', $image_parts[0]);
        $imageName = rand() . '_' . time() . '.' . $ext;
        $filePath = public_path() . '/assets/images/user_profile';
        $image = Image::make($image_base64);
        // $image->scale(50);
        $width = config('app.userImageDimention.width');
        $height = config('app.userImageDimention.height');
        $image->resize($width, $height)->save($filePath . '/' . $imageName);

        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $oldData->image = $imageName;
            $oldData->save();
        } else {
            $user_profile = new UserProfilePhoto;
            $user_profile->user_id = $userId;
            $user_profile->image = $imageName;
            $user_profile->save();
        }
        return 1;
    }

    public static function uploadAndSaveProfileViaCropped($fileObject, $userId, $input)
    {
        $ext = $fileObject->extension();
        $imageName = rand() . '_' . time() . '.' . $ext;
        $image_parts = explode(";base64,", $input['hiddenPreviewImg']);
        $image_base64 = base64_decode($image_parts[1]);
        $filePath = public_path() . '/assets/images/user_profile';

        $image = Image::make($image_base64);
        // $image->scale(50);
        $width = config('app.userImageDimention.width');
        $height = config('app.userImageDimention.height');
        $image->resize($width, $height)->save($filePath . '/' . $imageName);

        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $oldData->image = $imageName;
            $oldData->save();
        } else {
            $user_profile = new UserProfilePhoto;
            $user_profile->user_id = $userId;
            $user_profile->image = $imageName;
            $user_profile->save();
        }

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0)
            Admin::compressImages('local', $filePath . '/' . $imageName);

        return 1;
    }
    public static function uploadAndSaveProfilePhotoOld($fileObject, $userId)
    {
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand() . '_' . time() . '.' . $ext;
        $filePath = public_path() . '/assets/images/user_profile/';
        $img = Image::make($fileObject->path());


        $photo->move($filePath, $filename);

        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                unlink($path);
            }
            $oldData->image = $filename;
            $oldData->save();
        } else {
            $user_profile = new UserProfilePhoto;
            $user_profile->user_id = $userId;
            $user_profile->image = $filename;
            $user_profile->save();
        }
        return 1;
    }

    public static function getProfilePhoto($userId, $imageName = "")
    {
        $return = url('public/assets/frontend/img/placeholder/user_default.png');
        if ($imageName) {
            $return = url('public/assets/frontend/img/placeholder/' . $imageName);
        }
        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                $return = url('/public/assets/images/user_profile/' . $oldData->image);
            }
        }
        return $return;
    }

    public static function checkProfilePhoto($userId)
    {
        $return = 0;
        $oldData = UserProfilePhoto::where('user_id', $userId)->first();
        if ($oldData) {
            $path = public_path() . '/assets/images/user_profile/' . $oldData->image;
            if (file_exists($path)) {
                $return = 1;
            }
        }
        return $return;
    }
}
