<?php


namespace Server;

use Core\BeanFactory;
use Core\init\RouterCollect;
use FastRoute\Dispatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServer
{
    private $server;

    private $dispatcher;

    /**
     * HttpServer constructor.
     */
    public function __construct()
    {
        BeanFactory::init();
        $this->dispatcher = BeanFactory::getBean(RouterCollect::class)->getDispatcher();
        $this->server = new Server('0.0.0.0', 9501);
        $this->server->set([
            'worker_num' => 1,
            'daemonize' => false
        ]);

        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('ShutDown', [$this, 'onShutDown']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
    }

    public function onRequest(Request $request, Response $response)
    {
        $myRequest = \Core\http\Request::init($request);
        $myResponse = \Core\http\Response::init($response);
        $routeInfo = $this->dispatcher->dispatch($myRequest->getMethod(), $myRequest->getUri());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response->status(404);
                $response->end();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response->status(405);
                $response->end();
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $extVars = [$myRequest, $myResponse];
                $myResponse->setBody($handler($vars, $extVars));
                $myResponse->end();
                break;
        }
    }

    public function onStart()
    {
        cli_set_process_title('swoole master');
        $this->server->master_pid;
        echo "server started";

    }

    public function onShutDown()
    {
        echo "server shutdown" . PHP_EOL;
    }

    public function onWorkerStart()
    {
        cli_set_process_title('swoole worker');
    }
    public function onManagerStart()
    {
        cli_set_process_title('swoole manage');
    }

    public function run()
    {
        $this->server->start();
    }

}