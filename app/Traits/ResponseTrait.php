<?php

namespace App\Traits;

trait ResponseTrait
{
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


        return response()->json($response);
    }


    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public static function sendResponse($result, $message, $code)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
            'code'    => $code
        ];


        return response()->json($response);
    }

}
