<?php
use yii\helpers\Html;
use backend\assets\IeAsset;
use backend\assets\AdminLoginAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Login */

IeAsset::register($this);
AdminLoginAsset::register($this);

$assets_url=$this->assetBundles[AdminLoginAsset::className()]->baseUrl;
// $assets_url=$this->assetBundles[AdminLoginAsset::className()]->baseUrl;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
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
        <meta content=" " name="description" />

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" href="/favicon.ico" />
    </head>

<?php $this->beginBody() ?>
    <body class=" login">
        <div class="logo">
            <a href="index.html">
                <img src="<?=$assets_url?>/pages/img/logo-big.png" alt="" />
            </a>
        </div>
        <div class="content">

            <?=$content?>
            
        </div>>


    </body>
<?php $this->endBody() ?>

</html>
<?php $this->endPage() ?>
