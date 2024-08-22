<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Storage;
use DB;

class SongVariants extends Model
{
    use SoftDeletes;
    protected $table = 'song_variants';

    protected $fillable = ['song_id', 'tmp_song_id', 'url', 'type', 'resolution'];


    public static function addVarient($songId, $url, $type, $resolution = "")
    {
        // $filePath = public_path('/assets/webhook.txt');
        // file_put_contents($filePath, print_r('comes in add varient', true),FILE_APPEND);
        try {
            $exist = self::where('tmp_song_id', $songId)->where('type', $type)->where('resolution', $resolution)->first();
            if (!$exist) {
                $insert = new SongVariants();
                $insert->tmp_song_id = $songId;
                $insert->url = $url;
                $insert->type = $type;
                if ($resolution) {
                    $insert->resolution = $resolution;
                }
                $insert->save();
            }
        } catch (\Exception $e) {
        }
        return true;
    }

    public static function getMaxResultion($songId)
    {
        $data = self::selectRaw('max(CAST(resolution AS UNSIGNED)) as maxResolution')->whereRaw('CAST(resolution AS UNSIGNED)<= 720')->where('song_id', $songId)->where('type', '!=', 'mp3')->first();
        return $data->maxResolution;
    }

    public static function getUrlOfMaxResultion($songId, $resolution)
    {
        $type = "mp4";
        $data = self::selectRaw('url')->where('song_id', $songId)->where('resolution', $resolution)->where('type', $type)->first();
        if (!$data) {
            $data = self::selectRaw('url')->where('song_id', $songId)->where('resolution', $resolution)->where('type', '!=', 'mp3')->first();
            if (!$data) {
                $data = Songs::selectRaw('file as url')->where('id', $songId)->first();
            }
        }
        if ($data)
            return $data->url;
        else
            return "";
    }

    public static function getAudioURL($songId)
    {
        $return = "";
        $data = self::selectRaw('url')->where('song_id', $songId)->where('type', 'mp3')->first();
        if ($data) {
            $return = $data->url;
        }
        return $return;
    }

    public static function getEquivalentHeight($needWidth, $actualWidth, $videoHeight)
    {
        $return = "";
        $return  = ($needWidth * $videoHeight) / $actualWidth;
        $return = floor($return / 2) * 2;
        return $return;
    }
}
