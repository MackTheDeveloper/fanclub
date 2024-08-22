<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Storage;

class CcJobList extends Model
{
    use SoftDeletes;
    protected $table = 'cc_job_list';

    protected $fillable = ['tmp_song_id', 'job_id', 'task_id', 'type', 'task_name', 'status', 'status_text', 'message'];



    public static function AddJob($songId,$jobId,$taskId,$taskName,$type){
        // $type = "metadata/conversion";
        $insert = new CcJobList();
        $insert->tmp_song_id = $songId;
        $insert->job_id = $jobId;
        $insert->task_id = $taskId;
        $insert->type = $type;
        $insert->task_name = $taskName;
        $insert->status = "0";
        $insert->save();
        return true;
    }


    public static function GetTask($taskId)
    {
        // $type = "metadata/conversion";
        $data = CcJobList::where('task_id',$taskId)->first();
        if ($data) {
            return $data;
        }
        return false;
    }

    public static function setTask($taskId, $status, $message)
    {
        // $type = "metadata/conversion";
        $data = CcJobList::where('task_id', $taskId)->first();
        if ($data) {
            $data->status_text = $status;
            $data->message = $message;
            $data->save();
        }
        return true;
    }

    public static function setStatus($taskId, $status)
    {
        CcJobList::where('task_id', $taskId)->update(['status' => $status]);
        return true;
    }


    public static function getNameByKey($string, $key,$ext){
        return $string . "_" . $key . "." . $ext;
    }

    public static function getResolution($string){
        $string1 = explode('_', $string);
        if (count($string1)>1) {
            $string1 = end($string1);
            $string1 = explode('.', $string1);
            return ['resolution'=>$string1[0], 'type' => $string1[1]];
        }else{
            $string2 = explode('.', $string);
            return ['resolution' => "", 'type' => $string2[1]];
        }
    }
}
