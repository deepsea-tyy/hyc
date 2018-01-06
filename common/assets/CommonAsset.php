<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * Main common application asset bundle.
 */
class CommonAsset extends AssetBundle
{
    public $baseUrl = '@web';

    public $js = [
      'js/common.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}