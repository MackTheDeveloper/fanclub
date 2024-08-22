<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ArtistBanners;
use Validator;

class ArtistBannerAPIController extends BaseController
{
    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,
        [
			'banner'=>'required'
        ]);
        if($validator->fails())
        {
            return $this->sendError('Validation Error.', $validator->errors(),300);
        }else{
            $input = $request->all();
            $insert = [];
            // pre($input);
			if ($request->hasFile('banner')) {
                $fileObject = $request->file('banner');
                if (!empty($input['hiddenPreviewImg'])) {
                    $insert['file'] = ArtistBanners::uploadAndSaveBannerViaCropped($fileObject,$input);
                }else {
                    $insert['file'] = ArtistBanners::uploadAndSaveBanner($fileObject);
                }
            }
            $response = ArtistBanners::addNew($insert);
            $msg = getResponseMessage('BannerAdded');
            return $this->sendResponse($response['data'], $msg);
        }
    }

	public function delete(Request $request)
    {
        $model = ArtistBanners::where('id', $request->id)->first();
        if (!empty($model)) {
            $model->delete();
            $msg = getResponseMessage('BannerRemoved');
            return $this->sendResponse([], $msg);
        } else {
            return $this->sendError([], 'Something went wrong!!');
        }
    }

}
