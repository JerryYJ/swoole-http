<?php


namespace Server;


use Swoole\Http\Server;

class HttpServer
{
    private $server;

    /**
     * HttpServer constructor.
     */
    public function __construct()
    {
        $this->server = new Server('0.0.0.0', 9501);
        $this->server->set([
            'worker_num' => 1,
            'daemonize' => false
        ]);

        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('Start', [$this, 'onStart']);
        $this->server->on('ShutDown', [$this, 'onShutDown']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('ManageStart', [$this, 'onManageStart']);
    }

    public function onRequest()
    {

    }

    public function onStart()
    {
        cli_set_process_title('swoole master');
        $this->server->master_pid;

    }

    public function onShutDown()
    {

    }

    public function onWorkerStart()
    {
        cli_set_process_title('swoole worker');
    }
    public function onManageStart()
    {
        cli_set_process_title('swoole manage');
    }

}