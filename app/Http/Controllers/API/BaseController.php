<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $message,$code = 200,$needBlank="")
    {
    	$response = [
            'statusCode' => (string)$code,
            'success' => true,
            // 'component'    => $result,
            // 'data'    => $result,
            'message' => $message,
        ];
        if ($result) {
            $response['component'] = $result;
        }else{
            if ($needBlank) {
                $response['component'] = $result;
            }
        }
        return response()->json($response, $code);
    }
    

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response =[
            'statusCode' => (string)$code,
        	'success' => false,
        	'message' => $error,
        ];

        if(!empty($errorMessages))
        {
            $response['component'] = $errorMessages;
            if ($error=='Validation Error.') {
                if ($errorMessages->getMessages()) {
                    $messages = $errorMessages->getMessages();
                    $messages = reset($messages);
                    if ($messages[0]) {
                        $response['message'] = $messages[0];
                    }
                }
            }
        }
        return response()->json($response, 200);
    }

}
