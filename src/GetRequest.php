<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

/**
 * Get请求.
 * Class GetRequest.
 */
class GetRequest extends BaseRequest
{
    public function request()
    {
        $this->withMethod(self::GET_METHOD);
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }
}
