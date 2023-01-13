<?php

if (! function_exists('api_response_unauthorized')) {
    function api_response_unauthorized()
    {
        return response([
            'error' => [
                'code' => '401',
                'message' => 'Unauthorized.',
            ],
        ], 401);
    }
}

if (! function_exists('api_response')) {
    function api_response($data, $status)
    {
        return response()->json([
            'data' => $data,
        ], $status);
    }
}

if (! function_exists('api_response_errors')) {
    function api_response_errors($data, $status)
    {
        return response()->json([
            'errors' => $data,
        ], $status);
    }
}
