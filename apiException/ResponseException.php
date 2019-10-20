<?php
/**
 * author crusj
 * date   2019/10/12 1:49 下午
 */


namespace App\Http\apiException;

class ResponseException extends \Exception
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function render()
    {
        if ($this->response instanceof SuccessResponse) {
            $data = [
                'code'  => $this->response->getCode(),
                'data'  => $this->response->getData(),
                'error' => ''
            ];
        } else {
            $data = [
                'code'  => $this->response->getCode(),
                'data'  => [],
                'error' => $this->response->getData()
            ];
        }
        return response()->json($data);
    }
}
