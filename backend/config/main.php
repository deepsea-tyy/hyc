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
        'user' => [
            // 'identityClass' => 'common\models\User',
            // 'enableAutoLogin' => true,
            // 'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            
            'identityClass' => 'mdm\admin\models\User',
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
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => true,
            'bundles'=>[
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@m47',
                    'js' => [
                        'global/plugins/jquery.min.js',
                    ],
                ],
                'kartik\base\WidgetAsset' => [
                    'depends' => [],
                ],
                'kartik\select2\ThemeKrajeeAsset' => [
                    'depends' => [],
                ],
                'kartik\select2\Select2Asset' => [
                    'depends' => [],
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'depends' => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'depends' => [],
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

        ],
        'debug' => [
            'class' => \yii\debug\Module::class,
            'panels' => [
                'queue' => \yii\queue\debug\Panel::class,
            ],
        ],
    ],
    'params' => $params,
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'admin/user/login',
            'admin/user/request-password-reset',
            'admin/user/signup',
            'admin/user/captcha',
            'mime/upload',
            'public/*',
        ]
    ],
];
