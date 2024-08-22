<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Artist;
use App\Models\SongComments;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;
use Mail;
use Hash;

class SongCommentAPIController extends BaseController
{

    public function addSongComment(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'song_id' => 'required',
                'comment' => 'required',
            ]
        );
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 300);
        } else {
            // $user_id = Auth::user()->id;
            $input = $request->all();
            $data = SongComments::addCommentSong($input);
            if ($data) {
                return $this->sendResponse([], 'Songs Comment Added successfully.');
            }else{
                return $this->sendError([], 'Something went wrong.');
            }
        }
    }


}
