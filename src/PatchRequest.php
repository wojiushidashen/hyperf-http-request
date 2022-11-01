<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

class PatchRequest extends BaseRequest
{
    public $method = self::PATCH_METHOD;
}
