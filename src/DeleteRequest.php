<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class DeleteRequest extends BaseRequest
{
    public $method = self::DELETE_METHOD;
}
