<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;

class MusicGenres extends Model
{
    use SoftDeletes;

    protected $table = 'music_genres';
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
        $return = url('public/assets/frontend/img/placeholder/rectangle_192_96.jpg');
        $path = public_path().'/assets/images/music-genre/'.$image;
        if(file_exists($path) && $image){
            $return = url('/public/assets/images/music-genre/'.$image);
        }
        return $return;
    }

    public static function uploadAndSaveImage($fileObject,$id=''){
        $photo = $fileObject;
        $ext = $fileObject->extension();
        $filename = rand().'_'.time().'.'.$ext;
        $filePath = public_path().'/assets/images/music-genre';
        if (! File::exists($filePath)) {
            File::makeDirectory($filePath);
        }

        $img = Image::make($photo->path());
        // $img->resize(50, 50, function ($const) {
        //     $const->aspectRatio();
        // })->save($filePath.'/'.$filename);
        $width = config('app.musicGenreIconDimension.width');
        $height = config('app.musicGenreIconDimension.height');
        if($img->width() == $width && $img->height() == $height){
            $photo->move($filePath.'/', $filename);
        }else{
            $img->resize($width, $height)->save($filePath.'/'.$filename);
        }
        if ($id) {
            $oldData = self::where('id', $id)->first();
            if ($oldData) {
                $path = public_path().'/assets/images/music-genre/'.$oldData->image;
                if(file_exists($path)){
                    unlink($path);
                }
                $oldData->image = $filename;
                $oldData->save();
            }
        }
        return $filename;
    }
    public static function getMusicGenreListApi($search = "",$page="")
    {
        $return = [];
        $data = MusicGenres::where('status',1)->whereNull('deleted_at');
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        }
        if ($page) {
            $limit = 2;
            $offset = ($page - 1) * $limit;
            $data->offset($offset);
            $data->limit($limit);
        }
        $data = $data->get()->toArray();
        if ($data)
        {
            $data = self::formatMusicGenreList($data);
        }
        $return = ['musicGenre' => $data];
        return $return;
    }
    public static function formatMusicGenreList($data){
        $authId = User::getLoggedInId();
        $navigate = $authId?"1":"0";
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "navigate" => $navigate,
                "navigateType" => "17",
                "navigateTo" => "genre/".$value['id'],
                "name"=>$value['name'],
                "slug"=>$value['slug'],
                "id"=> (string)$value['id'],
                "image"=>MusicGenres::getGenrePhoto($value['id']),
                "status"=>$value['status'],
                "sortOrder"=>$value['sort_order'],
            ];
        }
        return $return;
    }

    public static function getGenrePhoto($genreId)
    {
        $oldData = self::where('id', $genreId)->first();
        if ($oldData) {
            return $oldData->image;
        }
    }
    public static function getGenreById($genreId)
    {
        $oldData = self::where('id', $genreId)->first();
        if ($oldData) {
          $return = [
              "name"=>$oldData->name,
              "slug"=>$oldData->slug,
              "image"=>MusicGenres::getGenrePhoto($oldData->id),
              "status"=>$oldData->status,
              "sortOrder"=>$oldData->sort_order,
          ];
          return $return;
        }
    }
    public static function searchAPIGenres($search)
    {
        $data = [];
        $genresdata = self::selectRaw('music_genres.*')->whereNull('music_genres.deleted_at');
        if ($search) {
            $genresdata->where(function ($query) use ($search) {
                $query->where('music_genres.name', 'like', '%' . $search . '%');
            });
        }
        $genresdata = $genresdata->get();
        if ($genresdata) {
            $data = self::formatMusicGenreList($genresdata);
        }
        $return = ['genresDetails' => $data];
        return $return;
    }

    public static function getDataForFooter($ids)
    {
        $data = self::selectRaw('id,name,slug')->whereNull('deleted_at')->whereIn('id', explode(',', $ids))->get();
        $return = array();
        foreach($data as $k => $v)
        {
            $return[$v->name] = route('genreDetails',$v->slug);
        }
        return $return;
    }

}
