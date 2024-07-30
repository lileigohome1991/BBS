<?php

namespace App\Console\Commands;

use App\Handlers\Chat;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Illuminate\Console\Command;
use Workerman\Worker;

class GatewayWorkerCommand extends Command
{
    // 命令行的名称及签名。
    protected $signature = 'gateway-worker:server';
    // 命令行的描述
    protected $description = "开启gateway  服务.....";

    protected $port = 9502;

    protected $registerPort = 9501;

    protected $WS_Intranet_ip = '8.130.77.188';

    public function __construct()
    {
        $this->WS_Intranet_ip = env('WS_Intranet_ip');
        parent::__construct();
    }

    public function handle()
    {
        global $argv;
        if (!in_array($action = $this->argument('action'), ['start', 'stop', 'restart', 'reload'])) {
            $this->error('Error Arguments');
            exit;
        }
        $argv[0] = 'gateway-worker:server';
        $argv[1] = $action;
        $argv[2] = $this->option('daemon') ? '-d' : '';//该参数是以daemon（守护进程）方式启动
        $this->start();
    }

    public function start()
    {
        $this->startGateWay();
        $this->startBusinessWorker();
        $this->startRegister();
        Worker::runAll();
    }

    public function startBusinessWorker()
    {
        $work = new BusinessWorker();
        $work->name = 'BusinessWorker';#设置BusinessWorker进程的名称
        $work->count = 2;#设置BusinessWorker进程的数量
        $work->registerAddress = $this->WS_Intranet_ip . ':' . $this->registerPort;#注册服务地址
        $work->eventHandler = Chat::class;#设置使用哪个类来处理业务,业务类至少要实现onMessage静态方法，onConnect和onClose静态方法可以不用实现
    }

    public function startGateWay()
    {
        $content=array(
            "ssl"=>array(
                'local_cert'=>'/etc/nginx/cert/limuyi.shop.pem',
                'local_pk'=>'/etc/nginx/cert/limuyi.shop.key',
                "verify_peer"=>false
            )

        );
        $gateway = new Gateway('websocket://0.0.0.0:' . $this->port,$content);
        $gateway->name = 'Gateway';#设置Gateway进程的名称，方便status命令中查看统计
        $gateway->count = 2;#进程的数量
        $gateway->lanIp = $this->WS_Intranet_ip;#内网ip,多服务器分布式部署的时候需要填写真实的内网ip
        $gateway->startPort = 9000;#监听本机端口的起始端口
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 0;#服务端主动发送心跳
        $gateway->pingData = '{"mode":"heart"}';
        $gateway->registerAddress = $this->WS_Intranet_ip . ':' . $this->registerPort;#注册服务地址
    }

    public function startRegister()
    {
        new Register('text://' . $this->WS_Intranet_ip . ':' . $this->registerPort);
    }
}