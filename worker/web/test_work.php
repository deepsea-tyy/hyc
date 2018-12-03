<?php

use \Workerman\Worker;
use \Channel\Server;

// 自动加载类
require_once __DIR__ . '/common.php';

// $db = \Yii::app()->db;
// 创建一个Worker监听2347端口，不使用任何应用层协议
$tcp_worker = new Worker("tcp://0.0.0.0:2347");

// 启动4个进程对外提供服务
$tcp_worker->count = 4;

// 当客户端发来数据时
$tcp_worker->onMessage = function($connection, $data)
{
	global $tcp_worker;
	// global $db;
// 	-- SHOW VARIABLES like '%general%';
//  -- SHOW VARIABLES like '%log_output%';
    // \Yii::$app->db->createCommand('set global general_log=on')->execute();
    // \Yii::$app->db->createCommand('set global log_output="TABLE"')->execute();
    $connection->send('message: ' . $data );
};

$tcp_worker2 = new Worker("tcp://0.0.0.0:2348");

// 启动4个进程对外提供服务
$tcp_worker2->count = 4;

// 当客户端发来数据时
$tcp_worker2->onMessage = function($connection, $data)
{
	global $tcp_worker2;

    $connection->send('message: ' . $data );
};







// 初始化一个Channel服务端
$channel_server = new Channel\Server('0.0.0.0', 2206);
$channel_server->name = 'channel_server';


// websocket服务端
$worker = new Worker('websocket://0.0.0.0:4236');
$worker->count=2;
$worker->name = 'pusher';
$worker->onWorkerStart = function($worker)
{
    // Channel客户端连接到Channel服务端
    Channel\Client::connect('127.0.0.1', 2206);
    // 以自己的进程id为事件名称
    $event_name = $worker->id;
    // 订阅worker->id事件并注册事件处理函数
    Channel\Client::on($event_name, function($event_data)use($worker){
        $to_connection_id = $event_data['to_connection_id'];
        $message = $event_data['content'];
        if(!isset($worker->connections[$to_connection_id]))
        {
            echo "connection not exists\n";
            return;
        }
        $to_connection = $worker->connections[$to_connection_id];
        $to_connection->send($message);
    });

    // 订阅广播事件
    $event_name = '广播';
    // 收到广播事件后向当前进程内所有客户端连接发送广播数据
    Channel\Client::on($event_name, function($event_data)use($worker){
        $message = $event_data['content'];
        foreach($worker->connections as $connection)
        {
            $connection->send($message);
        }
    });
};

$worker->onConnect = function($connection)use($worker)
{
    $msg = "workerID:{$worker->id} connectionID:{$connection->id} connected\n";
    echo $msg;
    $connection->send($msg);
};

// 用来处理http请求，向任意客户端推送数据，需要传workerID和connectionID
$http_worker = new Worker('http://0.0.0.0:4237');
$http_worker->name = 'publisher';
$http_worker->onWorkerStart = function()
{
    Channel\Client::connect('127.0.0.1', 2206);
};
$http_worker->onMessage = function($connection, $data)
{
    $connection->send('ok');
    if(empty($_GET['content'])) return;
    // 是向某个worker进程中某个连接推送数据
    if(isset($_GET['to_worker_id']) && isset($_GET['to_connection_id']))
    {
        $event_name = $_GET['to_worker_id'];
        $to_connection_id = $_GET['to_connection_id'];
        $content = $_GET['content'];
        Channel\Client::publish($event_name, array(
           'to_connection_id' => $to_connection_id,
           'content'          => $content
        ));
    }
    // 是全局广播数据
    else
    {
        $event_name = '广播';
        $content = $_GET['content'];
        Channel\Client::publish($event_name, array(
           'content'          => $content
        ));
    }
};





// 运行worker
Worker::runAll();