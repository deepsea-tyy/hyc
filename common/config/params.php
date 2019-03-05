<?php
return [
    'user.token_time' => 3600,
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'domain' => '/',
	'webuploader' => [
		// 后端处理图片的地址，value 是相对的地址
		'uploadUrl' => 'blog/upload',
		// 多文件分隔符
		'delimiter' => ',',
		// 基本配置
		'baseConfig' => [
			'defaultImage' => 'http://img1.imgtn.bdimg.com/it/u=2056478505,162569476&fm=26&gp=0.jpg',
			'disableGlobalDnd' => true,
			'accept' => [
				'title' => 'Images',
				'extensions' => 'gif,jpg,jpeg,bmp,png',
				'mimeTypes' => 'image/*',
			],
			'pick' => [
				'multiple' => false,
			],
		],
	],
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
	
];
