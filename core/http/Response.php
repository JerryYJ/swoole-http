<?php


namespace Core\http;

use Swoole\Http\Response as SwooleResponse;

class Response
{
    protected $swooleResponse;
    protected $body;

    /**
     * Response constructor.
     * @param SwooleResponse $swooleResponse
     */
    public function __construct($swooleResponse)
    {
        $this->swooleResponse = $swooleResponse;
        $this->setHeader('Content-Type', 'test/plain: charset=utf-8;');
    }

    /**
     * @param SwooleResponse $swooleResponse
     * @return Response
     */
    public static function init($swooleResponse)
    {
        return new self($swooleResponse);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setHeader($key, $value)
    {
        $this->swooleResponse->header($key, $value);
    }

    public function setHttpStatus(int $status)
    {
        $this->swooleResponse->setStatusCode($status);
    }

    public function redirect($url, $code=301)
    {
        $this->setHttpStatus($code);
        $this->setHeader("Location", $url);
    }

    public function end()
    {
        $res = $this->getBody();
        $jsonConver = ['array'];
        if (in_array(gettype($res), $jsonConver)) {
            $this->swooleResponse->header("Content-Type", "application/json; charset=utf-8");
            $res = json_encode($res, true);
        } else {
            $this->swooleResponse->header("Content-Type", "text/html; charset=utf-8");
        }
        $this->swooleResponse->write($res);
        $this->swooleResponse->end();
    }
}