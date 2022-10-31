<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

use http\Exception\InvalidArgumentException;

/**
 * Http 请求类.
 */
class Request
{
    public const METHODS = [
        BaseRequest::GET_METHOD,
        BaseRequest::DELETE_METHOD,
        BaseRequest::POST_METHOD,
        BaseRequest::PATCH_METHOD,
        BaseRequest::PUT_METHOD,
    ];

    /**
     * 获取请求的实例.
     *
     * @param array $clientOptions
     * @param mixed $method
     * @return RequestInterface
     */
    public function getInstance($method, $clientOptions = [])
    {
        $this->checkMethod($method);
        $className = $this->getRequestClassName($method);

        return make($className, [$clientOptions]);
    }

    private function getRequestClassName($method)
    {
        return sprintf('\Hyperf\HttpRequest\%sRequest', ucfirst(strtolower($method)));
    }

    /**
     * @param mixed $method
     * @return bool
     */
    private function checkMethod($method)
    {
        $map = array_flip(self::METHODS);

        if (! isset($map[strtoupper($method)])) {
            throw new InvalidArgumentException('参数`method`错误！');
        }

        return true;
    }
}
