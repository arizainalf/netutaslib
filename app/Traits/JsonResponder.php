<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait JsonResponder
{
    public function successResponse($data = null, $message = 'Success', $code = Response::HTTP_OK)
    {
        return response()->json(compact('code', 'message', 'data'), $code)->header('Content-Type', 'application/json');
    }

    public function errorResponse($data = null, $message = 'Error', $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(compact('code', 'message', 'data'), $code)->header('Content-Type', 'application/json');
    }
}