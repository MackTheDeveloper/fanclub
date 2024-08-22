<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Storage;

class TmpSongs extends Model
{
    use SoftDeletes;
    protected $table = 'tmp_songs';

    protected $fillable = ['name', 'slug', 'artist_id', 'file_type', 'file', 'icon', 'duration', 'video_width', 'categories', 'languages', 'genre', 'status', 'tag', 'thumbnail'];

    public function artist(){
        return $this->hasOne(User::class, 'id', 'artist_id');
    }
    
    public static function setAttrValueById($id,$attr,$value){
        $data = self::find($id);
        if ($data) {
            $data->$attr = $value;
        }
        $data->save();
    }
    
    public static function setAttrValuesById($id,$attrValue){
        $data = self::find($id);
        if ($data) {
            foreach ($attrValue as $attr => $value) {
                $data->$attr = $value;
            }
        }
        $data->save();
    }

    public static function getAttrValueById($id, $attr)
    {
        $return = "";
        $data = self::find($id);
        if ($data) {
            $return = $data->$attr;
        }
        return $return;
    }


    public static function checkAndPublishSong($id){
        $filePath = public_path('/assets/webhook.txt');
        file_put_contents($filePath, print_r('comes in publish song', true),FILE_APPEND);

        $selectAll = CcJobList::where('tmp_song_id',$id)->where('status', "0")->count();
        file_put_contents($filePath, print_r($selectAll, true),FILE_APPEND);
        if ($selectAll=="0") {
            file_put_contents($filePath, print_r('step no job pending', true),FILE_APPEND);
            $data = self::find($id);
            file_put_contents($filePath, print_r($data, true),FILE_APPEND);
            if ($data && $data->status=="0") {
                file_put_contents($filePath, print_r('step 2 status 0', true),FILE_APPEND);

                try {
                    $data->status = "1";
                    $data->save();

                    $newSong = new Songs();
                    $newSong->artist_id = $data->artist_id;
                    $newSong->name = $data->name;
                    $newSong->slug = getSlug($data->name, "", 'songs', 'slug');
                    $newSong->genre = $data->genre;
                    $newSong->categories = $data->categories;
                    $newSong->languages = $data->languages;
                    $newSong->duration = $data->duration;
                    $newSong->thumbnail = $data->thumbnail;
                    $newSong->icon = $data->icon;
                    $newSong->file = $data->file;
                    $newSong->tag = $data->tag;
                    $newSong->release_date = date('Y-m-d');
                    $newSong->save();
                    if ($newSong && isset($newSong->id)) {
                        SongVariants::where('tmp_song_id',$id)->update(['song_id'=> $newSong->id]);
                        Notifications::songAddedtoFavArtist($newSong->id);
                    }
                } catch (\Exception $e) {
                    file_put_contents($filePath, print_r($e->getMessage(), true),FILE_APPEND);
                }
            }
        }
        return true;
    }

    public static function getFileNameByUrl($string){
        $replaceUrl = "https://". config('filesystems.disks.s3.bucket').".s3.amazonaws.com/";
        $return = str_replace($replaceUrl,'', $string);
        return $return;
    }

    public static function getFileNameById($id){
        $return = "";
        $data = self::find($id);
        if ($data) {
            $string = $data->file;
            $replaceUrl = "https://". config('filesystems.disks.s3.bucket').".s3.amazonaws.com/";
            $return = str_replace($replaceUrl,'', $string);
        }
        return $return;
    }

    public static function getTimeInSec($string){
        $return = 0;
        if (strpos($string, 's') !== false) {
            $return = (float) $string;
        }
        if (strpos($string, ':') !== false) {
            $times = array_reverse(explode(':', $string));
            foreach ($times as $key => $value) {
                $return += $value* pow(60, $key);
            }
        }
        return $return;
    }

    public static function getSecToTime($seconds)
    {
        $return = '';
        $return .= (($seconds / 3600) >= 1) ? sprintf('%02d', ($seconds / 3600)) : "";
        $return .= $return ? ":" : "";
        $return .= sprintf('%02d:%02d', ($seconds / 60 % 60), ($seconds % 60));
        return $return;
    }

    public static function getArtistDetailByTmpSong($songId){
        $return = [];
        $data = self::find($songId);
        if($data){
            $artistId = $data->artist_id;

            $return['name'] = Artist::getAttrById($artistId,'firstname');
            $return['email'] = Artist::getAttrById($artistId,'email');
        }
        return $return;
    }
}
