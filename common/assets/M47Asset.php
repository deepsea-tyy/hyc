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
        // BEGIN GLOBAL MANDATORY STYLES
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'global/plugins/bootstrap-sweetalert/sweetalert.css',
        // END GLOBAL MANDATORY STYLES
        
        // BEGIN THEME GLOBAL STYLES
        'global/css/components.min.css',
        'global/css/plugins.min.css',
        // END THEME GLOBAL STYLES
        
        // BEGIN THEME LAYOUT STYLES
        'layouts/layout/css/layout.min.css',
        'layouts/layout/css/themes/darkblue.min.css',
        'layouts/layout/css/custom.min.css',
        // END THEME LAYOUT STYLES
        
    ];

    public $js = [
        // BEGIN CORE PLUGINS
        'global/plugins/bootstrap/js/bootstrap.min.js',
        // 'global/plugins/js.cookie.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'global/plugins/bootstrap-sweetalert/sweetalert.min.js',
        // END CORE PLUGINS
        
        // BEGIN THEME GLOBAL SCRIPTS
        'global/scripts/app.min.js',
        // END THEME GLOBAL SCRIPTS
        
        // BEGIN THEME LAYOUT SCRIPTS
        'layouts/global/scripts/quick-sidebar.min.js',
        'layouts/global/scripts/quick-nav.min.js',
        // END THEME LAYOUT SCRIPTS 

    ];

    public $depends = ['common\assets\JqueryAsset'];
}
