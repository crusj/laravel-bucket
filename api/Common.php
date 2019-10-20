<?php
/**
 * author crusj
 * date   2019/10/20 4:10 下午
 */


namespace App\Http\Controllers\Api;


use App\Http\apiException\FailResponse;
use App\Http\apiException\ResponseException;
use App\Http\apiException\SuccessResponse;
use App\Http\Controllers\Controller;

class Common extends Controller
{

    //成功
    public function success($data = [])
    {
        self::httpSuccess($data);
    }

    //失败
    public function fail($data = [])
    {
        self::httpFail($data);
    }

    public static function httpSuccess($data = [])
    {
        throw new ResponseException(new SuccessResponse($data));
    }

    public static function httpFail($data = [])
    {
        throw new ResponseException(new FailResponse($data));
    }
}
