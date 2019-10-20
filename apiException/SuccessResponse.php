<?php
/**
 * author crusj
 * date   2019/10/12 4:49 下午
 */


namespace App\Http\apiException;

class SuccessResponse extends Response
{
    public function getCode(): int
    {
        return config('bucket.success_code', 2000);
    }
}
