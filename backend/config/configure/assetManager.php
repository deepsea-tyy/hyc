<?php
return [
    'class' => 'yii\web\AssetManager',
    'linkAssets' => true,
    'bundles'=>array_merge([
        'yii\web\JqueryAsset' => [
            'sourcePath' => '@m47',
            'js' => [
                'global/plugins/jquery.min.js',
            ],
        ],
        'yii\web\YiiAsset' => [
            'depends' => [
                'common\assets\JqueryAsset',
            ],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'depends' => [],
        ],
        'yii\widgets\ActiveFormAsset' => [
            'depends' => [],
        ],
        


    ],require('asset/kartik.php')),

];
