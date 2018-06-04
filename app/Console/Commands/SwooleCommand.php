<?php

namespace App\Console\Commands;

use App\Handler\SwooleHandler;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class SwooleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swoole {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $serv;
    protected $config = [
        'heartbeat_check_interval ' => 10,
        'worker_num' => 8,
        'daemonize' => false,
        'max_request' => 10000,
        'dispatch_mode' => 2,
        'package_max_length' => 8192,
        'open_eof_check' => true,
        'open_eof_split' => true,
        'package_eof' => "0D0A",
        'tcp_fastopen' => true
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $argc = $this->argument('action');
        switch ($argc) {
            case "start":
                $this->start();
        }
    }

    public function start()
    {
        $this->serv = new \swoole_server("0.0.0.0", 9501);
        $this->serv->set($this->config);
        $handler = new SwooleHandler();
        $this->serv->on('Start', array($handler, 'onStart'));
        $this->serv->on('Connect', array($handler, 'onConnect'));
        $this->serv->on('Receive', array($handler, 'onReceive'));
        $this->serv->on('Close', array($handler, 'onClose'));
        $this->serv->on('Task', array($handler, 'onTask'));
        $this->serv->start();
    }

    public function getArguments()
    {
        return [
            ["action", InputArgument::REQUIRED, 'start|stop|restart']
        ];
    }
}
