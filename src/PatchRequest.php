<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class PatchRequest extends BaseRequest
{
    public function request()
    {
        $this->withMethod(self::PATCH_METHOD);
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }
}
