<?php

namespace backend\assets;

use yii\web\AssetBundle;

class IeAsset extends AssetBundle
{
    public $sourcePath = '@m47';
    
    public $jsOptions = [
     	'condition'=> 'lt IE 9',
    ];

    public $js = [
      'global/plugins/respond.min.js',
      'global/plugins/excanvas.min.js',
      'global/plugins/ie8.fix.min.js',
    ];
}
