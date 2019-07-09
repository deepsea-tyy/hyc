<?php
namespace backend\assets;

use yii\web\AssetBundle;

class M47Asset extends AssetBundle
{
    public $sourcePath = '@m47';

    public $baseUrl = '@web';

    public $css = [
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap-sweetalert/sweetalert.css',
        'global/css/components.min.css',
        'layouts/layout/css/layout.min.css',
        'layouts/layout/css/themes/darkblue.min.css',
    ];

    public $js = [
        'global/plugins/bootstrap-sweetalert/sweetalert.min.js',
        'global/scripts/app.min.js',
        'layouts/global/scripts/quick-sidebar.min.js',
        '/js/common.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
