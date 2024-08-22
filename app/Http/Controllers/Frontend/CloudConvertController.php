<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CcJobList;
use App\Models\EmailTemplates;
use App\Models\Notifications;
use App\Models\SongVariants;
use App\Models\TmpSongs;
// use \CloudConvert\Laravel\Facades\CloudConvert;
use \CloudConvert\CloudConvert;
use \CloudConvert\Models\Job;
use \CloudConvert\Models\Task;
use Illuminate\Http\Request;
use Storage;

class CloudConvertController extends Controller
{
    public function index($fileName,$songId)
    {
        // $fileName = "images/1961584923_1638532105.mp4";
        
        $cloudconvert = new CloudConvert([
            'api_key' => config('cloudconvert.api_key'),
            'sandbox' => config('cloudconvert.sandbox')
        ]);
        $fileName = explode('.', $fileName);
        $extention = end($fileName);
        $originalName = implode("", array_slice($fileName, 0, -1));
        $fileNameImg = str_replace('images/', "thumb/", $originalName).'.png';
        $job = (new Job())
            ->addTask(
                (new Task('import/s3', 'upload-my-file'))
                    ->set('bucket', config('filesystems.disks.s3.bucket'))
                    ->set('region', config('filesystems.disks.s3.region'))
                    // ->set('key', "images/1961584923_1638532105.mp4")
                    ->set('key', $originalName.".".$extention)
                    ->set('access_key_id', config('filesystems.disks.s3.key'))
                    ->set('secret_access_key', config('filesystems.disks.s3.secret'))
            );
        $job->addTask(
            (new Task('metadata', 'song-retrive'))
                ->set('input', ["upload-my-file"])
        );
        $job->addTask(
            (new Task('thumbnail', 'create-thumbnail'))
                ->set('input', ["upload-my-file"])
                ->set('output_format', 'png')
        );

        $job->addTask(
            (new Task('export/s3', 'upload-thumbnail'))
                ->set('input', 'create-thumbnail')
                ->set('bucket', config('filesystems.disks.s3.bucket'))
                ->set('region', config('filesystems.disks.s3.region'))
                ->set('key', $fileNameImg)
                ->set('access_key_id', config('filesystems.disks.s3.key'))
                ->set('secret_access_key', config('filesystems.disks.s3.secret'))
        );
        $cloudconvert->jobs()->create($job);

        // $cloudconvert->jobs()->wait($job);
        // $tasks = $job->getTasks();
        // $taskToRetrive = ["upload-thumbnail"];
        // $taskToRetrive = ["song-retrive", "upload-thumbnail"];
        // foreach ($tasks as $key => $value) {
        //     $taskName = $value->getName();
        //     if (in_array($taskName, $taskToRetrive)) {
        //         $result = $value->getResult();
        //         pre($result);
        //         // for save result as imageName = 

        //         // for add job task for conversion
        //         // $duration = $result->metadata->Duration;
        //         // $height = $result->metadata->ImageHeight;
        //         // $duration = (float) $duration;
        //         // $taskId = $value->getId();
        //         // CcJobList::AddJob($songId, $jobId, $taskId, $taskName, 'metadata');
        //     }
        // }
        // pre($job);
        $jobId = $job->getId();
        $tasks = $job->getTasks();
        $taskToRetrive = ["song-retrive"];
        foreach ($tasks as $key => $value) {
            $taskName = $value->getName();
            if (in_array($taskName,$taskToRetrive)) {
                $taskId = $value->getId();
                CcJobList::AddJob($songId,$jobId, $taskId, $taskName, 'metadata');
            }
        }
        $thumbval = Storage::disk('s3')->url($fileNameImg);
        TmpSongs::setAttrValueById($songId, 'thumbnail', $thumbval);
        return true;
    }

    public function variationGenerate($songId,$fileName,$maxWidth,$videoHeight)
    {
        $varient_generate = TmpSongs::getAttrValueById($songId, 'varient_generate');
        if ($varient_generate=='0') {
            TmpSongs::setAttrValuesById($songId, ['varient_generate' => 1]);
            $variations = [
                // "108" => "144",
                "360" => "480",
                "480" => "640",
                "720" => "1280",
                "1080" => "1920",
                "1440" => "2560",
                // "1080" => "2048",
                "2160" => "3840",
                "4320" => "7680"
            ];
            $type = ["x264"=> "mp4", "vp9"=> "webm"];
            $audiotype = ["mp4"=> "aac", "webm"=> "opus"];
            $cloudconvert = new CloudConvert([
                'api_key' => config('cloudconvert.api_key'),
                'sandbox' => config('cloudconvert.sandbox')
            ]);
            $fileName = explode('.',$fileName);
            $extention = end($fileName);
            $originalName = implode("", array_slice($fileName, 0, -1));
            $fileNameVid = str_replace('images/', "videos/", $originalName);
            $job = (new Job())
                // ->addTask(new Task('import/upload', 'upload-my-file'))
                ->addTask(
                    (new Task('import/s3', 'upload-my-file'))
                        ->set('bucket', config('filesystems.disks.s3.bucket'))
                        ->set('region', config('filesystems.disks.s3.region'))
                        ->set('key', $originalName.'.'.$extention)
                        ->set('access_key_id', config('filesystems.disks.s3.key'))
                        ->set('secret_access_key', config('filesystems.disks.s3.secret'))
                );
            $importTasks = [];
            $exportTasks = [];
            foreach ($variations as $key1 => $value1) {
                if (($value1 <= $maxWidth) || ($key1 <= $videoHeight)) {
                    $height = SongVariants::getEquivalentHeight($value1, $maxWidth, $videoHeight);
                    foreach ($type as $key2 => $value2) {
                        $taskName = 'convert-my-file_' . $key1 . '_' . $key2;
                        $importTasks[] = $taskName;
                        $audioFormat = $audiotype[$value2];
                        $job->addTask(
                            (new Task('convert', $taskName))
                                ->set('input', 'upload-my-file')
                                // ->set('input_format', 'mp4')
                                ->set('output_format', $value2)
                                ->set('engine', 'ffmpeg')
                                ->set('video_codec', $key2)
                                ->set('crf', 31)
                                ->set('width', $value1)
                                ->set('height', $height)
                                ->set('audio_codec', $audioFormat)
                                // ->set('audio_codec', 'copy')
                                ->set('audio_bitrate', 128)
                        );
                    }
                }
            }
    
            $taskName = 'convert-my-file_mp3';
            $importTasks[] = $taskName;
            $job->addTask(
                (new Task('convert', $taskName))
                    ->set('input', 'upload-my-file')
                    // ->set('input_format', 'mp4')
                    ->set('output_format', 'mp3')
                    ->set('engine', 'ffmpeg')
                    // ->set('video_codec', $key2)
                    // ->set('crf', 31)
                    // ->set('width', $value1)
                    // ->set('audio_codec', 'opus')
                    ->set('audio_codec', 'mp3')
                    // ->set('audio_bitrate', 128)
            );
    
            foreach ($variations as $key1 => $value1) {
                if (($value1 <= $maxWidth) || ($key1 <= $videoHeight)) {
                // if ($value1 <= $maxWidth) {
                    foreach ($type as $key2 => $value2) {
                        $input = 'convert-my-file_' . $key1 . '_' . $key2;
                        if (in_array($input, $importTasks)) {
                            $taskName = 'export-it_' . $key1 . '_' . $key2;
                            $exportTasks[] = $taskName;
                            $name = $fileNameVid."_". $key1.".". $value2;
                            // $name = $originalName."_". $key1.".". $key2;
                            $job->addTask(
                                (new Task('export/s3', $taskName))
                                    ->set('input', $input)
                                    ->set('bucket', config('filesystems.disks.s3.bucket'))
                                    ->set('region', config('filesystems.disks.s3.region'))
                                    ->set('key', $name)
                                    ->set('access_key_id', config('filesystems.disks.s3.key'))
                                    ->set('secret_access_key', config('filesystems.disks.s3.secret'))
                            );
                        }
                    }
                }
            }
    
            $input = 'convert-my-file_mp3';
            if (in_array($input, $importTasks)) {
                $taskName = 'export-it_mp3';
                $exportTasks[] = $taskName;
                $name = $fileNameVid . ".mp3";
                $job->addTask(
                    (new Task('export/s3', $taskName))
                        ->set('input', $input)
                        ->set('bucket', config('filesystems.disks.s3.bucket'))
                        ->set('region', config('filesystems.disks.s3.region'))
                        ->set('key', $name)
                        ->set('access_key_id', config('filesystems.disks.s3.key'))
                        ->set('secret_access_key', config('filesystems.disks.s3.secret'))
                );
            }
    
            $cloudconvert->jobs()->create($job);     
            
            $jobId = $job->getId();
            $tasks = $job->getTasks();
            $taskToRetrive = $exportTasks;
            foreach ($tasks as $key => $value) {
                $taskName = $value->getName();
                if (in_array($taskName, $taskToRetrive)) {
                    $taskId = $value->getId();
                    CcJobList::AddJob($songId, $jobId, $taskId, $taskName, 'conversion');
                }
            }
        }
        return true;
    }

    public function webhook(Request $request)
    {
        $filePath = public_path('/assets/webhook');
        // $filePath = public_path('/assets/webhook.txt');
        $myfile = fopen($filePath."/testfile.txt", "w");

        $cloudconvert = new CloudConvert([
            'api_key' => config('cloudconvert.api_key'),
            'sandbox' => config('cloudconvert.sandbox')
        ]);

        $signingSecret = config('cloudconvert.webhook_signing_secret'); // You can find it in your webhook settings

        $payload = @file_get_contents('php://input');
        $signature = $_SERVER['HTTP_CLOUDCONVERT_SIGNATURE'];

        try {
            $webhookEvent = $cloudconvert->webhookHandler()->constructEvent($payload, $signature, $signingSecret);
            $job = $webhookEvent->getJob();
            $job->getTag();
            $jobId = $job->getId();
            // file_put_contents($filePath, print_r($job, true));
            $tasks = $job->getTasks();
            if ($tasks) {
                $myfile = fopen($filePath . "/". $jobId.".txt", "w");
                fwrite($myfile, print_r($tasks, true));
                fclose($myfile);
                $failed = "0";
                foreach ($tasks as $key => $value) {
                    $taskId = $value->getId();
                    $checkExist = CcJobList::GetTask($taskId);
                    if ($checkExist) {
                        $taskName = $value->getName();
                        $status = $value->getStatus();
                        $message = $value->getMessage();
                        if ($status == Task::STATUS_FINISHED) {
                            $result = $value->getResult();

                            // $myfile = fopen($filePath . "/" . $jobId.$key. ".txt", "w");
                            // fwrite($myfile, print_r($result, true));
                            // fclose($myfile);
                            CcJobList::setStatus($taskId, 1);
                            $songId = $checkExist->tmp_song_id;
                            if ($taskName== "song-retrive") {
                                // for add job task for conversion
                                $duration = $result->metadata->Duration;
                                $height = $result->metadata->ImageHeight;
                                $width = $result->metadata->ImageWidth;
                                $rotation = $result->metadata->Rotation;
                                $duration = TmpSongs::getTimeInSec($duration);
                                // $taskId = $value->getId();
                                
                                // TmpSongs::setAttrValueById($songId, 'duration', $duration);
                                // TmpSongs::setAttrValueById($songId, 'video_width', $width);
                                TmpSongs::setAttrValuesById($songId, ['video_width'=> $width, 'duration'=> $duration]);
                                $fileName = TmpSongs::getFileNameById($songId);
                                // file_put_contents($filePath, print_r($fileName, true));
                                if ($rotation && $rotation!='180') {
                                    $this->variationGenerate($songId, $fileName, $height, $width);
                                }else{
                                    $this->variationGenerate($songId, $fileName, $width, $height);
                                }
                            }else{
                                // $meta = CcJobList::getResolution($string);
                                // $taskId = $value->getId();
                                if (isset($result->files)) {
                                    $string = $result->files[0]->filename;
                                    $dir = $result->files[0]->dir;
                                    $resolution = CcJobList::getResolution($string);
                                    $url = Storage::disk('s3')->url($dir . $string);
                                    SongVariants::addVarient($songId, $url, $resolution['type'], $resolution['resolution']);
                                    TmpSongs::checkAndPublishSong($songId);
                                }
                            }
                        }else{
                            $failed = "1";
                        }
                        CcJobList::setTask($taskId,$status,$message);
                    }
                }
                $artistEmailName = TmpSongs::getArtistDetailByTmpSong($songId);
                if ($failed == "1") {
                    Notifications::publishStatus($songId,'failure');
                    $data = ['NAME'=>$artistEmailName['name'],'MESSAGE' => 'Your song transcoding is gone fail please contact to fanclub support for the same.'];   
                }else{
                    Notifications::publishStatus($songId,'success');
                    $data = ['NAME'=>$artistEmailName['name'],'MESSAGE' => 'Your song published successfully. Please check on fanclub.'];
                }
                if (isset($artistEmailName['email'])) {
                    EmailTemplates::sendMail('song-publish-status', $data, $artistEmailName['email']);
                }
            }
            // file_put_contents($filePath, print_r($job, true));
        } catch(\CloudConvert\Exceptions\UnexpectedDataException $e) {
            // Invalid payload
            // $job = "Invalid payload";
            // file_put_contents($filePath, print_r($job, true));
            http_response_code(400);
            exit();
        } catch(\CloudConvert\Exceptions\SignatureVerificationException $e) {
            // Invalid signature
            // $job = "Invalid signature";
            // file_put_contents($filePath, print_r($job, true));
            http_response_code(400);
            exit();
        }
        
        http_response_code(200);
    }
}