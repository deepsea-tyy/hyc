<?php

namespace backend\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle
{
    // public $sourcePath = '';
    public $baseUrl = '@web';

    public $js = [
    	'js/menu.js',
    ];
    public $depends = [
    	'yii\web\JqueryAsset',
    	'yii\bootstrap\BootstrapAsset',
        'yii\widgets\PjaxAsset'
    ];
}