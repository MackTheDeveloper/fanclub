<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;


class UserSecurityQuestions extends Model
{
    //use SoftDeletes;

    protected $table = 'users_security_questions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'security_question_id', 'answer', 'created_at'
    ];

    public static function getSecurityQuestionDetails($authId, $questionId)
    {
        $data = UserSecurityQuestions::where('user_id', $authId)->where('security_question_id', $questionId)->orderBy('created_at', 'DESC')->first();
        return ($data) ?: [];
    }
    
    public static function addNew($data)
    {
        $return = '';
        $success = true;

        try {
            $create = new UserSecurityQuestions();
            foreach ($data as $key => $value) {
                $create->$key = $value;
            }
            $create->save();
            $return = $create;
        } catch (\Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }
    public static function updateAnswer($data, $authId, $questionId)
    {
        $return = '';
        $success = true;

        try {
            $update = UserSecurityQuestions::where('user_id', $authId)->where('security_question_id', $questionId)->orderBy('created_at', 'DESC')->first();
            foreach ($data as $key => $value) {
                $update->$key = $value;
            }
            $update->update();
            $return = $update;
        } catch (\Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }
    public static function deleteAnswer($authId, $questionId)
    {
        $return = '';
        $success = true;
        try {
            $update = UserSecurityQuestions::where('user_id', $authId)->where('security_question_id', $questionId)->delete();
            $return = 1;
        } catch (\Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }
    public static function checkSecurityQuestion($questionID, $answer, $userId=0)
    {
        $return = '';
        $success = true;

        try {
            $data = UserSecurityQuestions::where('security_question_id', $questionID)->where('answer', $answer);
            if ($userId) {
                $data->where('user_id', $userId);
            }
            $data = $data->first();
            pre($data,1);
            if (empty($data)){
                $success = false;
            }
            $return = $data;
        } catch (\Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }
    public static function checkSecurityQuestionAnswer($question,$answer)
    {
        $return = '';
        $success = true;

        try {
            $data = UserSecurityQuestions::where('security_question_id', $question)->where('answer', $answer);
            $data = $data->pluck('user_id')->toArray();
            // pre($data, 1);
            if (empty($data)) {
                $success = false;
            }
            $return = $data;
        } catch (\Exception $e) {
            $return = $e->getMessage();
            $success = false;
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function addSecurityQuestions($answers)
    {
        $success = true;
        $return = '';
        // user id
        $authId = User::getLoggedInId();
        // get all security questions
        $question = SecurityQuestions::whereNull('deleted_at')->where('status', 1)->get();

        foreach ($question as $key => $value) {
            if (isset($answers[$value->id])){
                UserSecurityQuestions::updateOrCreate(
                    ['user_id' => $authId, 'security_question_id' => $value->id],
                    ['answer' => $answers[$value->id]],
                );
            }else{
                UserSecurityQuestions::where('user_id', $authId)->where('security_question_id', $value->id)->delete();
            }
            // check if answer already exist
            // $check = UserSecurityQuestions::where('user_id', $authId)->where('security_question_id', $value->id)->first();
            // if (!empty($check)) {
            //     // delete answer
            //     $check->delete();
            // } else {
            //     // add new answer
            //     if(!empty($answers[$value->id]))
            //     {
            //         UserSecurityQuestions::updateOrCreate(
            //             ['user_id' => $authId, 'security_question_id' => $value->id],
            //             [
            //                 'security_question_id' => $value->id,
            //                 'answer' => $answers[$value->id],
            //             ],
            //         );
            //     }
            //     // delete old answer
            //     else
            //     {
            //         $answers[$value->id] = '';
            //     }
            // }
        }
        return ['data' => $return, 'success' => $success];
    }

    public static function checkValidLengths($input){
        $minAns = GlobalSettings::getSingleSettingVal('security_question');
        $answerArr = [];
        foreach ($input as $key => $value) {
            if (str_contains($key, 'answer') && $value) {
                $keyArr = explode('_', $key);
                $answerArr[] = $value;
            }
        }
        if (count(array_filter($answerArr)) == 0) {
            return 'Please answer the questions';
        }else if (count(array_filter($answerArr)) < $minAns) {
            return  'Please answer atleast ' . $minAns . ' questions';
        }else {
            return 'true';
        }
    }
}
