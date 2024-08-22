<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use File;
use Image;
class ArtistEvents extends Model
{
    use SoftDeletes;
    protected $table = 'artist_events';

    protected $fillable = ['artist_id','name','description','location','time','date','banner_image','status', 'location_url'];

    public static function getList($id)
    {
        $data = self::where('artist_events.artist_id',$id)->whereNull('deleted_at')->get();
        return $data;
    }
    public static function getListByArtistId($id, $page = "1", $filter = [])
    {
        $data = self::where('artist_id',$id)->whereDate('date', '>=', Carbon::now())->orderBy('date','asc');
        $limit = 0;
        if ($page) {
            $limit = 10;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get();
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "id" => $value['id'],
                "artistId" => $value['artist_id'],
                "name" => $value['name'],
                "description" => $value['description'],
                "location" => $value['location'],
                "location_url" => $value['location_url'],
                "banner" => $value['banner_image'],
                "time" => date("h:i A",strtotime($value['time'])),
                "date" => getFormatedDate($value['date']),
                "releaseDate" => getFormatedDate($value['date']),
            ];
        }
        // pre($return);
        $return = ['data' => $return, 'page' => $page, 'limit' => $limit];
        return $return;
    }

    public static function getEventsByArtist($id,$limit=0){
        $data = self::whereNull('deleted_at')
            ->whereDate('date', '>=', Carbon::now())
            ->where('artist_id',$id)
            ->where('status','1')
            ->orderBy('date','asc');
            // ->limit($limit)->get();
        if ($limit) {
            $data->limit($limit);
        }
        $data = $data->get();
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "id" => $value['id'],
                "artistId" => $value['artist_id'],
                "name" => $value['name'],
                "description" => $value['description'],
                "location" => $value['location'],
                "location_url" => $value['location_url'],
                "banner" => $value['banner_image'],
                "time" => date("h:i A",strtotime($value['time'])),
                "date" => getFormatedDate($value['date']),
                "releaseDate" => getFormatedDate($value['date']),
                "date_form" => $value['date'],
            ];
        }
        return $return;
    }

    public static function getEventById($id){
        $data = self::find($id);
        $return = [];
        if ($data) {
            $return = [
                "id" => $data['id'],
                "name" => $data['name'],
                "description" => $data['description'],
                "location" => $data['location'],
                "location_url" => $data['location_url'],
                "banner" => $data['banner_image'],
                "time" => date("h:i A",strtotime($data['time'])),
                "date" => getFormatedDate($data['date']),
                "releaseDate" => getFormatedDate($data['date']),
                "date_form" => $data['date'],
            ];
        }
        return $return;
    }

    public function getBannerImageAttribute($image){
        $return = url('public/assets/frontend/img/placeholder/square_350_248.jpg');
        $path = public_path().'/assets/images/events/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/events/'.$image);
        }
        return $return;
    }

    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/events';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.artistEvent.width');
        $height = config('app.artistEvent.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $photo->move($filePath.'/', $filename);
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0) {
            Admin::compressImages('local', $filePath . '/' . $filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/events/'.$oldData->banner_image;
                if(file_exists($path)){
                    unlink($path);
                }
                // $oldData->image = $filename;
                // $oldData->save();
            }
        }
        return $filename;
    }

    public static function uploadAndSaveImageViaCropped($fileObject, $input, $id = '')
    {
        $ext = $fileObject->extension();
        $imageName = rand() . '_' . time() . '.' . $ext;
        $image_parts = explode(";base64,", $input['hiddenPreviewImg']);
        $image_base64 = base64_decode($image_parts[1]);
        $filePath = public_path() . '/assets/images/events';

        $image = Image::make($image_base64);
        // $image->scale(50);
        $width = config('app.artistEvent.width');
        $height = config('app.artistEvent.height');
        $image->save($filePath . '/' . $imageName);
        // $image->resize($width, $height)->save($filePath . '/' . $imageName);

        //--Tinify Called a function compressImages to compress the image
        if (env('TINIFY_IS_ACTIVE') && getCountOfTinifyOptimization() > 0) {
            Admin::compressImages('local', $filePath . '/' . $imageName);
        }

        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path() . '/assets/images/events/' . $oldData->banner_image;
                if (file_exists($path)) {
                    unlink($path);
                }
                // $oldData->image = $filename;
                // $oldData->save();
            }
        }

        return $imageName;
    }

    public static function addNew($data){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['artist_id'] = $authId;
        $allowed = ['artist_id','name','description','location','time','date','banner_image','status', 'location_url'];
        $data = array_intersect_key($data, array_flip($allowed));
        try{
            $create = new ArtistEvents();
            foreach ($data as $key => $value) {
                $create->$key = $value;
            }
            $create->save();
            // self::makeAvgProductReview($product_id);
            $return = $create;
        }catch(\Exception $e){
            $return = $e->getMessage();
            $success = false;
        }

        return ['data'=>$return,'success'=>$success];
    }
    public static function updateData($data,$event_id){
        $return = '';
        $success = true;
        $authId = User::getLoggedInId();
        $data['artist_id'] = $authId;
        $allowed = ['artist_id','name','description','location','time','date','banner_image','status', 'location_url'];
        $data = array_intersect_key($data, array_flip($allowed));
        $update = ArtistEvents::find($event_id);
        if ($update) {
            try{
                foreach ($data as $key => $value) {
                    $update->$key = $value;
                }
                $update->update();
                // self::makeAvgProductReview($product_id);
                $return = $update;
            }catch(\Exception $e){
                $return = $e->getMessage();
                $success = false;
            }
        }

        return ['data'=>$return,'success'=>$success];
    }



}
