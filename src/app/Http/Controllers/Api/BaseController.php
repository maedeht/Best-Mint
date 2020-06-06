<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected function successResponse($message = "", $code = 200, $data = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => isset($data['data']) ? $data['data']:[]
        ];
        if(isset($data['meta']))
            $response['meta'] = $data['meta'];

        return response()->json($response, $code);
    }

    protected function errorResponse($message = "", $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
