<?php
namespace backend\assets;

use yii\web\AssetBundle;
/**
 * This asset bundle provides the base JavaScript files for the Yii Framework.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminLoginAsset extends AssetBundle
{
    public $sourcePath = '@m47';

    public $baseUrl = '@web';

    public $css = [
        'pages/css/login.min.css',
    ];

    public $js = [];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
