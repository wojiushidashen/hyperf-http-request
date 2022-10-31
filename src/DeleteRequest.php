<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class DeleteRequest extends BaseRequest
{
    public function request()
    {
        $this->withMethod(self::DELETE_METHOD);
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }

    private function formatRequestData(&$options)
    {
        $options['query'] = $this->requestData;
    }
}
