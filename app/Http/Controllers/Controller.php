<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param bool $status
     * @param array $data
     * @param array $message
     * @param int $responseCode
     * @return JsonResponse
     */
    protected function responseJson($status = true, $data = null, $message = [], $responseCode = Response::HTTP_OK)
    {
        return response()->json(['status' => $status,'message' => $message, 'data' => $data], $responseCode);
    }
}
