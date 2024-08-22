<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Chats;
use App\Models\User;
use Validator;
use Mail;
use Hash;

class ChatAPIController extends BaseController
{
    public function intiateChat(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'receiver_id' => 'required',
            'message' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $data = Chats::addNew($input);
            if ($data['success']) {
                $msg = getResponseMessage('ChatMessageNew',User::getNameByIdForChat($input['receiver_id']));
                return $this->sendResponse($data['data'], $msg);
                // return $this->sendResponse($data['data'], 'Thank you for submitting inquiry. We will get back to you soon.');
            }else{
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }

    public function listPersons(Request $request)
    {
        $authId = User::getLoggedInId();
        $data = Chats::listChatPersons($authId,$request->sortBy,$request->search);
        if ($data) {
            $msg = getResponseMessage('ChatListPersons');
            return $this->sendResponse($data, $msg);
        }else{
            $msg1 = "Please initiate your first message from artist profile page.";
            $msg2 = "There are no messages from your fan(s).";
            $msg = (User::getLoggedInId('role_id') == "2") ? $msg2 : $msg1;
            if ($request->search) {
                $role = (User::getLoggedInId('role_id') == "2") ? "fans" : "artists";
                $msg = "No " . $role . " found with your search criteria.";
            }
            // return $this->sendError([], $msg);
            return $this->sendError($msg, $msg);
        }
    }

    public function getPersonChat(Request $request)
    {
        $authId = User::getLoggedInId();
        $lastId = isset($request->last_id)?$request->last_id:"";
        $reverse = ($request->refresh)?:0;
        $data = Chats::retriveChat([$authId,$request->person_id],8,$lastId,$reverse);
        $msg = getResponseMessage('ChatListPersons');
        $this->readChat($request);
        return $this->sendResponse($data, $msg,200,1);
        // if ($data) {
        // }else{
        //     return $this->sendError([], 'Something went wrong.');
        // }
    }


    public function clearChat(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'person_id' => 'required',
            // 'last_id' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $authId = User::getLoggedInId();
            $input = $request->all();
            $data = Chats::clearChat([$authId,$input['person_id']]);
            if ($data['success']) {
                $msg = getResponseMessage('clearChat',User::getNameByIdForChat($input['person_id']));
                return $this->sendResponse($msg, $msg);
                // return $this->sendResponse([], $msg);
                // return $this->sendResponse($data['data'], 'Thank you for submitting inquiry. We will get back to you soon.');
            }else{
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }


    public function readChat(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'person_id' => 'required',
            // 'last_id' => 'required',
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $authId = User::getLoggedInId();
            $input = $request->all();
            $data = Chats::readChat([$authId,$input['person_id']]);
            if ($data['success']) {
                $msg = getResponseMessage('readChat',User::getNameByIdForChat($input['person_id']));
                return $this->sendResponse($msg, $msg);
                // return $this->sendResponse([], $msg);
                // return $this->sendResponse($data['data'], 'Thank you for submitting inquiry. We will get back to you soon.');
            }else{
                return $this->sendError($data['data'], 'Something went wrong.');
            }
        }
    }


}