<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'redis' => [
            'class' => \yii\redis\Connection::class,
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'queue' => [
            // 'class' => \yii\queue\file\Queue::class,
            // 'path' => '@runtime/queue',
            'class' => \yii\queue\redis\Queue::class,
            'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'strictJobType' => false,
            'redis' => [
                'class' => \yii\redis\Connection::class,
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ], // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
        ],
        'queue2' => [
            // 'class' => \yii\queue\file\Queue::class,
            // 'path' => '@runtime/queue',
            'class' => \yii\queue\redis\Queue::class,
            'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'strictJobType' => false,
            'redis' => [
                'class' => \yii\redis\Connection::class,
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 2,
                // retry connecting after connection has timed out
                // yiisoft/yii2-redis >=2.0.7 is required for this.
                'retries' => 1,
            ], // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
        ],
        'user' => [
            // 'identityClass' => 'common\models\User',
            // 'enableAutoLogin' => true,
            // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            
            // 'identityClass' => 'mdm\admin\models\User',
            'identityClass' => 'common\models\User',
            'loginUrl' => ['admin/user/login'],
        ],
        // 'session' => [
        //     // this is the name of the session cookie used for login on the backend
        //     'name' => 'advanced-backend',
        // ],
        'session' => [
            'class' => 'yii\redis\Session',
            
        ],
        
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => require('configure/urlManager.php'),
        'assetManager' => require('configure/assetManager.php'),

    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',

        ],
        'spider' => [
            'class' => 'backend\modules\spider\Module',

        ],
        'debug' => [
            'class' => \yii\debug\Module::class,
            'panels' => [
                'queue' => \yii\queue\debug\Panel::class,
            ],
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'params' => $params,
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'admin/user/login',
            'admin/user/logout',
            'admin/user/request-password-reset',
            'admin/user/signup',
            'admin/user/captcha',
            'site/error',
            'site/index',
            'public/*',
        ]
    ],
];
