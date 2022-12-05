<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendError($error, $message, $code = 404)
    {
    	$response = [
            'success' => false,
            'error'   => $error,
            'message' => $message,
            'code'    => $code
        ];

        return response($response);
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendResponse($data = null, $message, $code)
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'code'    => $code
        ];
        if(!empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response);
    }
}
