<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class PutRequest extends BaseRequest
{
    public function request()
    {
        $this->withMethod(self::PUT_METHOD);
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }
}
