<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class PostRequest extends BaseRequest
{
    public $method = self::POST_METHOD;
}
