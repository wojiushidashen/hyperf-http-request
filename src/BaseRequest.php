<?php

declare(strict_types=1);

namespace Hyperf\HttpRequest;

use GuzzleHttp\Client;
use Hyperf\Di\Container;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Exception\InvalidArgumentException;

class BaseRequest implements RequestInterface
{
    public const GET_METHOD = 'GET';

    public const POST_METHOD = 'POST';

    public const HEAD_METHOD = 'HEAD';

    public const OPTIONS_METHOD = 'OPTIONS';

    public const DELETE_METHOD = 'DELETE';

    public const PATCH_METHOD = 'PATCH';

    public const PUT_METHOD = 'PUT';

    public const APPLICATION_JSON = 'application/json';
    public const MULTIPART_FORM_DATA= 'multipart/form-data';
    public const APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    public const CONTENT_TYPES = [
        self::APPLICATION_JSON,
        self::MULTIPART_FORM_DATA,
        self::APPLICATION_X_WWW_FORM_URLENCODED,
    ];

    public $url;

    public $method;

    public $requestData = [];

    public $cookies = [];

    public $headers = [];

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Client
     */
    protected $client;

    private $_defaultClientOptions = ['timeout' => 20, 'max_connections' => 10000];

    public function __construct($createClientOptions = [])
    {
        $this->container = ApplicationContext::getContainer();
        $clientFactory = $this->container->get(ClientFactory::class);
        $this->client = $clientFactory->create(Arr::merge($this->_defaultClientOptions, $createClientOptions));
    }

    public function withRequestData(array $data = []): RequestInterface
    {
        if ($this->requestData) {
            $this->requestData = Arr::merge($this->requestData, $data);
        }
        $this->requestData = $data;
        return $this;
    }

    public function withHeaders(array $headers = []): RequestInterface
    {
        if ($this->headers) {
            $this->headers = Arr::merge($this->headers, $headers);
        }
        if (! isset($headers['Cookie']) && ($cookies = $this->getCookieArr())) {
            $headers['Cookie'] = $this->formatCookies($cookies);
        }
        $this->headers = $headers;
        return $this;
    }

    public function withUrl(string $url): RequestInterface
    {
        if ($this->url !== null) {
            throw new InvalidArgumentException(sprintf('url：【%s】已经配置！', $this->url));
        }
        $this->url = $url;
        return $this;
    }

    public function withCookies(array $cookies = []): RequestInterface
    {
        if (isset($this->cookies)) {
            $this->cookies = Arr::merge($this->cookies, $cookies);
        }

        $this->cookies = $cookies;
        return $this;
    }

    public function withMethod(string $method): RequestInterface
    {
        if ($this->method !== null) {
            throw new InvalidArgumentException(sprintf('method：【%s】已经配置！', $this->method));
        }
        $this->method = $method;
        return $this;
    }

    public function send(callable $request)
    {
        $options = [];
        if ($this->headers) {
            $options['headers'] = $this->headers;
        }
        $request($options);
        $result = $this
            ->client
            ->{$this->method}($this->url, $options)
            ->getBody()
            ->getContents();
        if (isset($this->headers['response_str']) && $this->headers['response_str'] === true) {
            return $result;
        }
        return json_decode($result ?? '[]', true);
    }

    public function request()
    {
        return $this->send(function (&$options) {
            $this->formatRequestData($options);
        });
    }

    private function getCookieArr()
    {
        $cookieArr = [];
        if ($this->cookies) {
            foreach ($this->cookies as $key => $cookie) {
                $cookieArr[] = sprintf('%s=%s', $key, $cookie);
            }
        }
        return $cookieArr;
    }

    private function formatCookies($cookieArr)
    {
        return implode(';', $cookieArr);
    }

    protected function formatRequestData(&$options)
    {
        $formatRequestDataMethod = sprintf('format%sRequestData', ucfirst(strtolower($this->method)));
        $this->{$formatRequestDataMethod}($options);
    }

    private function formatGetRequestData(&$options)
    {
        $options['query'] = $this->requestData;
    }

    private function formatDeleteRequestData(&$options)
    {
        $options['query'] = $this->requestData;
    }

    private function formatPostRequestData(&$options)
    {
        $this->setRequestOptions($options);
    }

    private function formatPutRequestData(&$options)
    {
        $this->setRequestOptions($options);
    }

    private function formatPatchRequestData(&$options)
    {
        $this->setRequestOptions($options);
    }

    private function setRequestOptions(&$options)
    {
        switch ($this->getContentType()) {
            case self::APPLICATION_JSON:
                $options['json'] = $this->requestData;
                break;
            case self::MULTIPART_FORM_DATA:
                $options['multipart'] = $this->requestData;
                break;
            case self::APPLICATION_X_WWW_FORM_URLENCODED:
                $options['form_params'] = $this->requestData;
                break;
            default:
                $options['json'] = $this->requestData;
        }
    }

    private function getContentType()
    {
       $contentType = self::APPLICATION_JSON;
       foreach ($this->headers as $key => $value) {
           if (strtolower($key) == 'content-type') {
               foreach (self::CONTENT_TYPES as $value1) {
                   if (strstr($value, $value1)) {
                       return $value1;
                   }
               }
               return $contentType;
           }
       }

        return $contentType;
    }
}
