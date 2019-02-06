<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../vendor/yiisoft/yii2/Yii.php';
require_once __DIR__ . '/../../common/config/bootstrap.php';
require_once __DIR__ . '/../config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require_once __DIR__ . '/../../common/config/main.php',
    require_once __DIR__ . '/../../common/config/main-local.php',
    require_once __DIR__ . '/../config/main.php',
    require_once __DIR__ . '/../config/main-local.php'
);

(new yii\web\Application($config))->init();


/**
 * web前段测试代码
 var ws = new WebSocket("ws://127.0.0.1:8282");
    ws.onopen = function(){
		console.info("与服务端连接成功");
		ws.send('test msg\n');//相当于发送一个初始化信息
		console.info("向服务端发送心跳包字符串");
		setInterval(show,3000);
		}
	
	function show(){
		ws.send('heart beat\n');
		}	
  
  	ws.onConnect = function(e){

		}
	ws.onmessage = function(e){
console.log(e.data);
		}
//心跳处理
//获取会员id
ws.onclose = function(e){
	 console.log(e);
	}
 */