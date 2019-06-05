<?php
return [
    'user.token_time' => 7200,
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'domain' => '/',
	'workerConfig' =>[
		//HTTP_ORIGIN 可访问地址
		'allow' => [],
		'registerAddress' => '127.0.0.1:1238',
		'serverPing' => true,
		'businessWorker'  => [
			//进程名称
			'name' => 'App_BusinessWorker',
			//协议
			'protocols' => null,
			//进程数
			'count' => 4,
		],
		'gateway'  => [
			'name' => 'App_Gateway',
			'protocols' => 'websocket://0.0.0.0:8282',//外部访问端口
			'count' => 4,
			// 本机ip，分布式部署时使用内网ip
			'lanIp' => '127.0.0.1',
			'startPort' => 2900,
			'pingInterval' => 50,
		],
	],
	'file_storage' => [
		'local' => [
			'rootpath' => \Yii::getAlias('@backend') .'/web',
			'dirpath' => '/static/upload'
		],
	],
];
