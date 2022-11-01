<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

/**
 * Get请求.
 * Class GetRequest.
 */
class GetRequest extends BaseRequest
{
    public $method = self::GET_METHOD;
}
