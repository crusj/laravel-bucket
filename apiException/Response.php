<?php
/**
 * author crusj
 * date   2019/10/12 4:48 下午
 */


namespace App\Http\apiException;


abstract class Response
{
    /**
     * @var array
     */
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    //获取返回状态码
    abstract public function getCode(): int;

    //获取data
    final public function getData()
    {
        return $this->data;
    }
}
