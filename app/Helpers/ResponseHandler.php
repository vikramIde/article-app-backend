<?php

namespace App\Helpers;


class ResponseHandler
{
    public static function buildSuccessfulResponse($data, $display_message)
    {
        return response()->json([
                'result' => 'success',
                'data' => $data,
                'display_message' => $display_message,
                'status_code' => 200
        ]);
    }

    public static function buildUnsuccessfulResponse($error_type, $error_details, $display_message, $http_code)
    {
        return response()->json([
            'result' => 'fail',
            'error' => ['type' => $error_type, 'details' => $error_details],
            'display_message' => $display_message,
            'status_code' => $http_code]);
    }

    public static function buildUnsuccessfulValidationResponse($validator_result)
    {
        return response()->json($validator_result);
    }
}