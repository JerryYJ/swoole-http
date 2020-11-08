<?php


namespace Core\http;

use Swoole\Http\Request as SwooleRequest;


class Request
{
    protected $server = [];
    protected $uri;
    protected $queryParams;
    protected $postParams;
    protected $method;
    protected $header = [];
    protected $body;
    protected $swooleRequest;

    /**
     * Request constructor.
     * @param array $server
     * @param $uri
     * @param $queryParams
     * @param $postParams
     * @param $method
     * @param array $header
     * @param $body
     */
    private function __construct(array $server, $uri, $queryParams, $postParams, $method, array $header, $body)
    {
        $this->server = $server;
        $this->uri = $uri;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->method = $method;
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getQueryParams()
    {
        return $this->queryParams ?? [];
    }

    /**
     * @return mixed
     */
    public function getPostParams()
    {
        return $this->postParams ?? [];
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header ?? '';
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body ?? '';
    }

    /**
     * @return mixed
     */
    public function getSwooleRequest()
    {
        return $this->swooleRequest;
    }

    public static function  init(SwooleRequest $request)
    {
        $server = $request->server;
        $uri = $server['request_uri'];
        $queryParams = $request->get;
        $postParams = $request->post;
        $method = $server['request_method'] ?? 'GET';
        $header = $request->header;
        $body = $request->rawContent();

        $newRequest = new self($server, $uri, $queryParams, $postParams, $method, $header, $body);
        $newRequest->swooleRequest = $request;
        return $newRequest;
    }

}