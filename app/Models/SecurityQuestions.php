<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use File;


class SecurityQuestions extends Model
{
    use SoftDeletes;

    protected $table = 'security_question';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'question', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];
    public static function getSecurityQuestionsList($authId)
    {
        $data = SecurityQuestions::select('users_security_questions.answer', 'security_question.*')
            ->leftJoin('users_security_questions', function ($join) use ($authId) {
                $join->on('users_security_questions.security_question_id', '=', 'security_question.id');
                $join->where('users_security_questions.user_id', '=', $authId);
            });
        $data =  $data->where('security_question.status', '1')->orderBy('security_question.created_at', 'ASC')->get();
        return ($data) ?: [];
    }

    public static function getSecurityQuestionsListApi($authId)
    {
        $data = SecurityQuestions::select('users_security_questions.answer', 'security_question.*')
            ->leftJoin('users_security_questions', function ($join) use ($authId) {
                $join->on('users_security_questions.security_question_id', '=', 'security_question.id');
                $join->where('users_security_questions.user_id', '=', $authId);
            });
        $data = $data->where('security_question.status', '1')->orderBy('security_question.created_at', 'ASC')->get();
        $return = self::formatedList($data);
        $return = [
            'list' => $return,
        ];
        return ($return) ?: [];
    }
    public static function formatedList($data)
    {
        $return = [];
        foreach ($data as $key => $value) {
            $return[] = [
                "key" => isset($value['id']) ? $value['id'] : '',
                "value" => isset($value['question']) ? $value['question'] : '',
                "answer" => isset($value['answer']) ? $value['answer'] : '',
            ];
        }
        return $return;
    }

    public static function getQuestions(){
        $return = [];
        $data = self::where('status', '1')->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $return[] = [
                    "key" => $value->id,
                    "value" => $value->question
                ];
            }
        }
        return $return;
    }
}
