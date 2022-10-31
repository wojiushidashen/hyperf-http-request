<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

interface RequestInterface
{
    /**
     * 分发请求.
     * @return array
     */
    public function request();

    /**
     * 设置请求数据.
     */
    public function withRequestData(array $data = []): RequestInterface;

    /**
     * 设置请求的headers.
     */
    public function withHeaders(array $headers = []): RequestInterface;

    /**
     * 设置请求的链接.
     */
    public function withUrl(string $url): RequestInterface;

    /**
     * 设置请求的Cookies.
     */
    public function withCookies(array $cookies = []): RequestInterface;

    /**
     * 设置请求的方式.
     */
    public function withMethod(string $method): RequestInterface;
}
