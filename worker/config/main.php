<?php
$params = array_merge(
    require_once __DIR__ . '/../../common/config/params.php',
    require_once __DIR__ . '/../../common/config/params-local.php',
    require_once __DIR__ . '/params.php',
    require_once __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-worker',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'worker\controllers',
    'components' => [
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
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
        ],
    ],
    'params' => $params,
];
