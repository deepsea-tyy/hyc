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
class AdminLoginAsset extends AssetBundle
{
    public $sourcePath = '@m47';

    public $baseUrl = '@web';

    public $css = [

        // BEGIN PAGE LEVEL PLUGINS
        // '',
        'pages/css/login.min.css',
        // END PAGE LEVEL PLUGINS
        
        
    ];

    public $js = [
        
        // BEGIN PAGE LEVEL PLUGINS
        
        // 'global/plugins/backstretch/jquery.backstretch.min.js',
        // 'global/plugins/jquery-validation/js/jquery.validate.min.js',
        // 'global/plugins/jquery-validation/js/additional-methods.min.js',
        // 'global/plugins/select2/js/select2.full.min.js',
        // END PAGE LEVEL PLUGINS
        

    ];

    public $depends = [];
}
