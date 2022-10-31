hyperf http请求工具包
==========================

一、安装
--------------------------
> 命令
```shell
> composer require ezijing/http-request-utils
```

二、使用
--------------------------

```php
<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use Hyperf\HttpRequest\BaseRequest;
use Hyperf\HttpRequest\Request;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        $this->request = make(Request::class);
    }

    public function testRequestSend()
    {
        $url = 'https://XXX/growth_api/v1/lottery_config/get';
        $data = [
            'aid' => '6587',
            'uuid' => '7039128193925088782',
        ];
        $cookies = [
            'a' => 1,
        ];
        $headers = ['response_str' => true];
        $result = $this->request
            ->getInstance(BaseRequest::GET_METHOD)
            ->withUrl($url)
            ->withRequestData($data)
            ->withCookies($cookies)
            ->withHeaders($headers)
            ->request();

        $this->assertIsArray($result);
        print_r($result);
    }
}

```

