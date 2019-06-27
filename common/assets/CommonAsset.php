<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main common application asset bundle.
 */
class CommonAsset extends AssetBundle
{
    // public $sourcePath = '';
    public $baseUrl = '@web';

    public $js = [
    	'js/layout.js',
      	'js/common.js',

    ];
    public $depends = [];
}