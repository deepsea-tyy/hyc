<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\assets\IeAsset;
use common\assets\M47Asset;
use common\assets\AdminLayoutAsset;
use common\widgets\Alert;



IeAsset::register($this);
M47Asset::register($this);
AdminLayoutAsset::register($this);

/*$this->registerjs(" $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })",$this::POS_END);*/
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <!-- <meta content="" name="author" /> -->

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" href="/favicon.ico" /> 
    </head>
    <!-- END HEAD -->

<?php $this->beginBody() ?>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <?=$this->render('page-header');?>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                        <!-- BEGIN SIDEBAR MENU -->

                        <?=$this->render('page-menu');?>

                        <!-- END SIDEBAR MENU -->
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content" id="content">
                        
                        <!-- BEGIN THEME PANEL -->
                        <div class="theme-panel hidden-xs hidden-sm">
                            <div class="toggler"> </div>
                            <div class="toggler-close"> </div>
                            <div class="theme-options">
                                <div class="theme-option theme-colors clearfix">
                                    <span> THEME COLOR </span>
                                    <ul>
                                        <li class="color-default current tooltips" data-style="default" data-container="body" data-original-title="Default"> </li>
                                        <li class="color-darkblue tooltips" data-style="darkblue" data-container="body" data-original-title="Dark Blue"> </li>
                                        <li class="color-blue tooltips" data-style="blue" data-container="body" data-original-title="Blue"> </li>
                                        <li class="color-grey tooltips" data-style="grey" data-container="body" data-original-title="Grey"> </li>
                                        <li class="color-light tooltips" data-style="light" data-container="body" data-original-title="Light"> </li>
                                        <li class="color-light2 tooltips" data-style="light2" data-container="body" data-html="true" data-original-title="Light 2"> </li>
                                    </ul>
                                </div>
                                <div class="theme-option">
                                    <span> Theme Style </span>
                                    <select class="layout-style-option form-control input-sm">
                                        <option value="square" selected="selected">Square corners</option>
                                        <option value="rounded">Rounded corners</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Layout </span>
                                    <select class="layout-option form-control input-sm">
                                        <option value="fluid" selected="selected">Fluid</option>
                                        <option value="boxed">Boxed</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Header </span>
                                    <select class="page-header-option form-control input-sm">
                                        <option value="fixed" selected="selected">Fixed</option>
                                        <option value="default">Default</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Top Menu Dropdown</span>
                                    <select class="page-header-top-dropdown-style-option form-control input-sm">
                                        <option value="light" selected="selected">Light</option>
                                        <option value="dark">Dark</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Sidebar Mode</span>
                                    <select class="sidebar-option form-control input-sm">
                                        <option value="fixed">Fixed</option>
                                        <option value="default" selected="selected">Default</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Sidebar Menu </span>
                                    <select class="sidebar-menu-option form-control input-sm">
                                        <option value="accordion" selected="selected">Accordion</option>
                                        <option value="hover">Hover</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Sidebar Style </span>
                                    <select class="sidebar-style-option form-control input-sm">
                                        <option value="default" selected="selected">Default</option>
                                        <option value="light">Light</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Sidebar Position </span>
                                    <select class="sidebar-pos-option form-control input-sm">
                                        <option value="left" selected="selected">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </div>
                                <div class="theme-option">
                                    <span> Footer </span>
                                    <select class="page-footer-option form-control input-sm">
                                        <option value="fixed">Fixed</option>
                                        <option value="default" selected="selected">Default</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- END THEME PANEL -->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="index.html">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                            </ul>
                            <div class="page-toolbar">
                                <div class="btn-group pull-right">
                                    <button type="button" class="btn green btn-sm btn-outline dropdown-toggle" data-toggle="dropdown"> Actions
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li>
                                            <a href="#">
                                                <i class="icon-bell"></i> Action</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="icon-shield"></i> Another action</a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="icon-user"></i> Something else here</a>
                                        </li>
                                        <li class="divider"> </li>
                                        <li>
                                            <a href="#">
                                                <i class="icon-bag"></i> Separated link</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Ajax Content Page
                            <small>loading content via ajax</small>
                        </h1>
                        <!-- END PAGE TITLE-->
                        
                        <div class="page-content-body">
                            <?=$content?>
                        </div>
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                <!-- BEGIN QUICK SIDEBAR -->
                <a href="javascript:;" class="page-quick-sidebar-toggler">
                    <i class="icon-login"></i>
                </a>
                <?=$this->render('page-quick-sidebar');?>
                <!-- END QUICK SIDEBAR -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2016 &copy; Metronic Theme By
                    <a target="_blank" href="http://keenthemes.com">Keenthemes</a> &nbsp;|&nbsp;
                    <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>
                </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
        <!-- BEGIN QUICK NAV -->
        <?=$this->render('page-quick-nav');?>
        <div class="quick-nav-overlay"></div>
        <!-- END QUICK NAV -->
 
    </body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>