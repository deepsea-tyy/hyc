<?php
return [
    'class' => 'yii\web\AssetManager',
    'linkAssets' => true,
    'bundles'=>[
        'yii\web\JqueryAsset' => [
            'sourcePath' => '@m47',
            'js' => [
                'global/plugins/jquery.min.js',
            ],
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'sourcePath'=>'@m47',
            'css' => ['global/plugins/bootstrap/css/bootstrap.min.css'],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'sourcePath'=>'@m47',
            'js'=>['global/plugins/bootstrap/js/bootstrap.min.js'],
            'depends' => [
                'yii\web\JqueryAsset',
            ],
        ],

    ],
];
