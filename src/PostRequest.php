<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class PostRequest extends BaseRequest
{
    public function request()
    {
        $this->withMethod(self::POST_METHOD);
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }
}
