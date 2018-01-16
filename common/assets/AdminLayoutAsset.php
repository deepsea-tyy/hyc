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
class AdminLayoutAsset extends AssetBundle
{
    public $sourcePath = '@m47';

    public $baseUrl = '@web';

    public $css = [

        // BEGIN PAGE LEVEL PLUGINS
        
        // END PAGE LEVEL PLUGINS
        
        // BEGIN THEME LAYOUT STYLES
        'layouts/layout/css/layout.min.css',
        'layouts/layout/css/themes/darkblue.min.css',
        'layouts/layout/css/custom.min.css',
        // END THEME LAYOUT STYLES
        
    ];

    public $js = [
        
        // BEGIN PAGE LEVEL PLUGINS
        // END PAGE LEVEL PLUGINS
        
        // BEGIN THEME LAYOUT SCRIPTS
        'layouts/global/scripts/quick-sidebar.min.js',
        'layouts/global/scripts/quick-nav.min.js',
        // END THEME LAYOUT SCRIPTS 

    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
