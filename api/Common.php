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
        throw new ResponseException(new SuccessResponse($data));
    }

    //失败
    public function fail($data = [])
    {
        throw new ResponseException(new FailResponse($data));
    }
}
