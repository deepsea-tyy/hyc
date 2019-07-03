<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use yii\web\AssetBundle;
/**
 * This asset bundle provides the base JavaScript files for the Yii Framework.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
    ];

    public $depends = [
        'common\assets\JqueryAsset',
        'yii\widgets\PjaxAsset'
    ];
}
