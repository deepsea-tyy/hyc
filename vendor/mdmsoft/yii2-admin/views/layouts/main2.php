<?php
use yii\helpers\Html;
use common\assets\IeAsset;
use common\assets\M47Asset;
use common\assets\AdminLoginAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

IeAsset::register($this);
M47Asset::register($this);
AdminLoginAsset::register($this);

$assets_url=$this->assetBundles[M47Asset::className()]->baseUrl;
// $assets_url=$this->assetBundles[M47Asset::className()]->baseUrl;

$this->title = Yii::t('rbac-admin', 'Login');
$this->params['breadcrumbs'][] = $this->title;
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
        <meta content=" " name="description" />

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" href="/favicon.ico" />
    </head>
    <!-- END HEAD -->

<?php $this->beginBody() ?>
    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.html">
                <img src="<?=$assets_url?>/pages/img/logo-big.png" alt="" />
            </a>
        </div>
        <!-- END LOGO -->
        <div class="content">

            <?=$content?>
            
        </div>>


    </body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>