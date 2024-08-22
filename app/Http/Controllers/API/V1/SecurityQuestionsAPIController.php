<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Illuminate\Support\Facades\Auth;
use App\Models\SecurityQuestions;
use App\Models\UserSecurityQuestions;
use App\Models\User;
use Validator;
use Mail;
use Hash;

class SecurityQuestionsAPIController extends BaseController
{
    // public function index(Request $request){
    //     $data = ContactUs::userAndStatusWiseData();
    //     return $this->sendResponse($data, 'Reviews listed successfully.');
    // }


    public function create(Request $request)
    {
        $input = $request->request->all();
        $minAns = GlobalSettings::getSingleSettingVal('security_question');

        if (count(array_filter($input['answer'])) == 0) {
            return $this->sendError('Wrong', ['error' => 'Please answer the questions'], 301);
        }

        if (count(array_filter($input['answer'])) < $minAns) {
            return $this->sendError('Wrong', ['error' => 'Please answer atleast ' . $minAns . ' questions'], 301);
        }

        $authId = User::getLoggedInId();
        foreach ($input['question'] as $key => $value) {
            $userQuestionDetails = UserSecurityQuestions::getSecurityQuestionDetails($authId, $value);
            if (!empty($input['answer'][$value])) {
                if (empty($userQuestionDetails)) {
                    $dataToInsert = array(
                        "user_id" => $authId,
                        "security_question_id" => $value,
                        "answer" => $input['answer'][$value],
                    );
                    $data = UserSecurityQuestions::addNew($dataToInsert);
                } else {
                    $dataToUpdate = array("answer" => $input['answer'][$value]);
                    $data = UserSecurityQuestions::updateAnswer($dataToUpdate, $authId, $value);
                }
            } else {
                if (!empty($userQuestionDetails)) {
                    $data = UserSecurityQuestions::deleteAnswer($authId, $value);
                }
            }
        }
        if ($data['success']) {
            return $this->sendResponse($data['data'], getResponseMessage('SecurityQuestionUpdates'));
        } else {
            return $this->sendError($data['data'], 'Something went wrong.');
        }
    }

    public function check(Request $request)
    {
        $input = $request->request->all();
        $success = false;
        $userId = 0;
        $minAnsValid = UserSecurityQuestions::checkValidLengths($input);
        if ($minAnsValid=='true') {
            $lastIntersect = [];
            foreach ($input['question'] as $key => $value) {
                $question = UserSecurityQuestions::where('security_question_id', $value)->first();
                if ($question && isset($input['answer_' . $value])) {
                    $data = UserSecurityQuestions::checkSecurityQuestionAnswer($value, $input['answer_' . $value]);
                    // pre($data['data']);
                    if ($key) {
                        $arr = $data['data'];
                        $lastIntersect = array_intersect($lastIntersect, $arr);
                    }else{
                        $lastIntersect = $data['data'];
                    }
                    // // $data = UserSecurityQuestions::checkSecurityQuestionAnswer($questionAnswer);
                    // // pre($data,1);
                    // if ($data['success'] == false) {
                    //     $success = false;
                    // }else{
                    //     if ($userId) {
                    //         if ($userId!= $data['data']->user_id) {
                    //             $success = false;
                    //         }
                    //     }else{
                    //         $userId = $data['data']->user_id;
                    //         $success = true;
                    //     }
                    // }
                }
            }
            if ($lastIntersect) {
                $lastIntersect = array_values($lastIntersect);
                $success = true;
                $userId = $lastIntersect[0];
            }else{
                $success = false;
            }
            if ($success) {
                $data['data']['email'] = User::getEmailById($userId);
                return $this->sendResponse($data['data'], "Great! Your account information has been recovered.");
            } else {
                $msg = 'Sorry, your answers are not matching with our system.Please give correct answer to recover your account information.';
                return $this->sendError($msg, ["error"=>$msg]);
            }
        }else{
            return $this->sendError($minAnsValid, ['error' => $minAnsValid], 301);
        }
    }
    

    public function getSecurityQuestions()
    {
        // get user id
        $authId = User::getLoggedInId();
        // get security questions
        $data = SecurityQuestions::getSecurityQuestionsListApi($authId);
        $component = [
            "componentId" => "SecurityQuestionsList",
            "sequenceId" => "1",
            "isActive" => "1",
            "title" => "Security Question",
            "description" => "Answer five of these security questions in order to recover your account, should the need arise.",
            "SecurityQuestions" => $data
        ];
        return $this->sendResponse($component, 'Security Questions Listed Successfully.');
    }

    public function getSecurityQuestionsList()
    {
        // get user id
        $data = SecurityQuestions::getQuestions();
        $securityQuestionLength = GlobalSettings::getSingleSettingVal('security_question')?:"2";
        $component = [
            "componentId" => "SecurityQuestionsList",
            "sequenceId" => "1",
            "isActive" => "1",
            "minLength" => $securityQuestionLength,
            "SecurityQuestions" => $data
        ];
        return $this->sendResponse($component, 'Security Questions Listed Successfully.');
    }

    public function setSecurityQuestions(Request $request)
    {
        // get all the request
        $input = $request->request->all();
        $minQue = SecurityQuestions::where('status', 1)->whereNull('deleted_at')->count();


        // get total security questions
        $answers = [];
        $ansCount = 0;
        foreach ($input as $key => $value) {
            if (strpos($key, 'question_') !== false) {
                $id = explode('_', $key);
                $answers[$id[1]] = $value;
            }
        }
        // max answers
        $ansCount = count($answers);

        // validate security questions
        if ($minQue < $ansCount) {
            return $this->sendError('Wrong', ['error' => 'There are at max ' . $minQue . ' questions'], 301);
        }
        if ($ansCount == 0) {
            return $this->sendError('Wrong', ['error' => 'Please answer atleast ' . 1 . ' questions'], 301);
        }

        // Api call to add security questions
        $data = UserSecurityQuestions::addSecurityQuestions($answers);

        return $this->sendResponse($data['data'], getResponseMessage('SecurityQuestionAdded'));
    }
}
