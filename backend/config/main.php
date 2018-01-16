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
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            // 'identityClass' => 'common\models\User',
            // 'enableAutoLogin' => true,
            // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            
            'identityClass' => 'mdm\admin\models\User',
            'loginUrl' => ['admin/user/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'bundles'=>[
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@m47',
                    'js' => [
                        'global/plugins/jquery.min.js',
                    ],
                ],
            ]
        ]

    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',

        ],
        'spider' => [
            'class' => 'backend\modules\spider\Module',

        ]
    ],
    'params' => $params,
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*'
        ]
    ],
];
