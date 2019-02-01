<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class LayoutAsset extends AssetBundle
{
    public $sourcePath = '@m47';

    public $baseUrl = '@web';

    public $css = [

        // BEGIN PAGE LEVEL PLUGINS
        'global/plugins/morris/morris.css',
        'global/plugins/mapplic/mapplic/mapplic.css',
        // '',
        // END PAGE LEVEL PLUGINS
        
        // BEGIN THEME LAYOUT STYLES
        'layouts/layout7/css/layout.min.css',
        'layouts/layout7/css/custom.min.css',
        // END THEME LAYOUT STYLES
        
    ];

    public $js = [
        
        // BEGIN PAGE LEVEL PLUGINS
        'global/plugins/morris/morris.min.js',
        'global/plugins/morris/raphael-min.js',
        'global/plugins/mapplic/js/hammer.min.js',
        'global/plugins/mapplic/js/jquery.easing.js',
        'global/plugins/mapplic/js/jquery.mousewheel.js',
        'global/plugins/mapplic/mapplic/mapplic.js',
        'global/plugins/counterup/jquery.waypoints.min.js',
        'global/plugins/counterup/jquery.counterup.min.js',
        'global/plugins/jquery.sparkline.min.js',
        // '',
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
