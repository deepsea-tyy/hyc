<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\assets\IeAsset;
use common\assets\M47Asset;
use common\assets\CommonAsset;



CommonAsset::register($this);
IeAsset::register($this);
M47Asset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <head>
        <meta charset="utf-8" />

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="tyy-yii2-admin" name="description" />
        <meta content="649909457@qq.com" name="tyy" />

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        
        <link rel="shortcut icon" href="/favicon.ico" />
    </head>
<?php $this->beginBody() ?>
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">

            <?=$this->render('page-header');?>
            <div class="clearfix"> </div>

            <div class="page-container">
                <div class="page-sidebar-wrapper">
                        <?=$this->render('page-menu');?>
                </div>
                <div class="page-content-wrapper">
                    <div class="page-content" style="min-height: 1000px;">
                        <div class="page-content-body" id="container">
                            <?=$content ?>
                        </div>
                    </div>
                </div>
                <?=$this->render('page-quick-sidebar');?>
            </div>

            <div class="page-footer">
                <div class="page-footer-inner">
                    2018 &copy; integration the theme by Tyy
                </div>
            </div>
        </div>
    </body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>