<?php
/**
 * author crusj
 * date   2019/10/12 4:50 下午
 */


namespace App\Http\apiException;

/**
 * 错误响应
 * Class FailResponse
 * @package App\Exceptions
 */
class FailResponse extends Response
{

    public function getCode(): int
    {
        return config('bucket.fail_code', 4000);
    }
}
