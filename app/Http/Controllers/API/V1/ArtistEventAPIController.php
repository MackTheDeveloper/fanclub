<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ArtistEvents;
use App\Models\User;
// use App\Models\ArtistNews;
use Validator;
use Mail;
use Hash;

class ArtistEventAPIController extends BaseController
{

    public function index(Request $request)
    {
        $authId = User::getLoggedInId();
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $filter = isset($input['filter']) ? $input['filter'] : '';
        $data = ArtistEvents::getListByArtistId($authId, $page, $filter);
        $return = [
            "componentId"=> "event",
            "sequenceId"=> "1",
            "isActive"=> "1",
            "imageHeight" => config('app.artistEvent.height'),
            "imageWidth" => config('app.artistEvent.width'),
            "pageSize" => (string) $data['limit'],
            "pageNo" => (string) $data['page'],
            "eventData"=>$data['data']
        ];
        return $this->sendResponse($return, 'Artist Event listed successfully.');
    }

    public function list(Request $request,$id)
    {
        $authId = $id;
        $input = $request->all();
        $page = isset($input['page']) ? $input['page'] : 1;
        $filter = isset($input['filter']) ? $input['filter'] : '';
        $data = ArtistEvents::getListByArtistId($authId, $page, $filter);
        $return = [
            "componentId" => "event",
            "sequenceId" => "1",
            "isActive" => "1",
            "imageHeight" => config('app.artistEvent.height'),
            "imageWidth" => config('app.artistEvent.width'),
            "pageSize" => (string) $data['limit'],
            "pageNo" => (string) $data['page'],
            "eventData" => $data['data']
        ];
        return $this->sendResponse($return, 'Artist Event listed successfully.');
    }


    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,
        [
            // 'category_id' => 'required',
            // 'artist_id' => 'required',
            'name' => 'required',
            'description'=>'required',
            'date'=>'required',
            'location'=>'required',
            'time'=>'required',
            'banner_image' => 'mimes:jpg,jpeg,png|max:20000'
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $insert = $input;
            $insert['status']='1';
            if($request->hasFile('banner_image')) {
                $fileObject = $request->file('banner_image');
                if (!empty($input['hiddenPreviewImg'])) {
                    $insert['banner_image'] = ArtistEvents::uploadAndSaveImageViaCropped($fileObject, $input);
                }else{
                    $insert['banner_image'] = ArtistEvents::uploadAndSaveImage($fileObject);
                }
            }
            $response = ArtistEvents::addNew($insert);
            $msg = getResponseMessage('EventAdded');
            return $this->sendResponse($response['data'], $msg);
        }
    }

    public function edit($id)
    {
        $data = ArtistEvents::getEventById($id);
        return $this->sendResponse($data, 'Event retrived successfully.');
    }
    
    public function update(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,
        [
            'event_id'=>'required',
            'name' => 'required',
            'description'=>'required',
            'date'=>'required',
            'location'=>'required',
            'time'=>'required',
            'banner_images' => 'mimes:jpg,jpeg,png|max:20000'
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $update = $input;
            $event_id = $request->event_id;
            if($request->hasFile('banner_image')) {
                $fileObject = $request->file('banner_image');
                // $update['banner_image'] = ArtistEvents::uploadAndSaveImage($fileObject,$event_id);
                if (!empty($input['hiddenPreviewImg'])) {
                    $update['banner_image'] = ArtistEvents::uploadAndSaveImageViaCropped($fileObject, $input,$event_id);
                } else {
                    $update['banner_image'] = ArtistEvents::uploadAndSaveImage($fileObject,$event_id);
                }
            }
            // pre($insert);
            $response = ArtistEvents::updateData($update,$event_id);
            $msg = getResponseMessage('EventUpdated');
            return $this->sendResponse($response['data'], $msg);
        }
    }

    public function delete(Request $request)
    {
        $model = ArtistEvents::where('id', $request->id)->first();
        if (!empty($model)) {
            $model->delete();
            $msg = getResponseMessage('EventRemoved');
            return $this->sendResponse([], $msg);
        } else {
            return $this->sendError([], 'Something went wrong!!');
        }
    }
}
