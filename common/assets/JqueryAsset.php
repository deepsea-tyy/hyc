<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main common application asset bundle.
 */
class JqueryAsset extends AssetBundle
{
    public $sourcePath = '@m47';
    // public $sourcePath = null;
    
    public $js = [
    // 'http://apps.bdimg.com/libs/jquery/1.9.1/jquery.js',
    	'global/plugins/jquery.min.js'
    ];

    
}
