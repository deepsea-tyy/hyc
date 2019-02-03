<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-worker',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'worker\controllers',
    'components' => [
        'request' => [
            'class' => 'common\components\web\Request',
            'csrfParam' => '_csrf-worker',
            'enableCsrfValidation' => false,
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            // this is the name of the session cookie used for login on the api
            'name' => 'app-worker',
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
        
        'urlManager' => [
            'class' => 'common\components\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            // 'on beforeSend' => function ($event) {
            //     $response = $event->sender;
            //     $response->data = [
            //         'code' => $response->getStatusCode(),
            //         'data' => $response->data,
            //         'message' => $response->statusText
            //     ];
            // },
        ],
    ],
    'params' => $params,
];
