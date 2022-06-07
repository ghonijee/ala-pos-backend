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
     * @var Array|Null $data for send to response JSON
     */
    protected $data = null;

    /**
     * @var String $message Message data to response JSON
     */
    protected $message;

    protected function createResponse($status = true, $statusCode = 200)
    {
        return response()->json([
            "status" => $status,
            "message" => $this->message,
            "data" => $this->data,
        ], $statusCode);
    }

    protected function success($statusCode = 200)
    {
        return $this->createResponse(true, $statusCode);
    }

    protected function failed($statusCode = 500)
    {
        return $this->createResponse(false, $statusCode);
    }

    protected function responseData($data = null)
    {
        $this->data = $data;
        return $this;
    }
    protected function responseMessage($message = "Bad Request!")
    {
        $this->message = $message;;
        return $this;
    }
}
