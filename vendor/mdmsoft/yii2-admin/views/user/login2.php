<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <!-- <meta content="" name="author" /> -->

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="shortcut icon" href="/favicon.ico" /> </head>
    <!-- END HEAD -->

<?php $this->beginBody() ?>
    <body class="">
        <div class="page-lock">
            <div class="page-logo">
                <a class="brand" href="index.html">
                    <img src="<?=$assets_url?>/pages/img/logo-big.png" alt="logo" /> </a>
            </div>
            <div class="page-body">
                <img class="page-lock-img" src="<?=$assets_url?>/pages/media/profile/profile.jpg" alt="">
                <div class="page-lock-info">
                    <h1>Bob Nilson</h1>
                    <span class="email"> bob@keenthemes.com </span>
                    <span class="locked"> Locked </span>
                   <!--  <form class="form-inline" action="index.html">
                        <div class="input-group input-medium">
                            <input type="text" class="form-control" placeholder="Password">
                            <span class="input-group-btn">
                                <button type="submit" class="btn green icn-only">
                                    <i class="m-icon-swapright m-icon-white"></i>
                                </button>
                            </span>
                        </div>

                        <div class="relogin">
                            <a href="login.html"> Not Bob Nilson ? </a>
                        </div>
                    </form> -->
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options'=>['class'=>'form-inline']]); ?>
                        <?= $form->field($model, 'username')->label('用户名') ?>
                        <?= $form->field($model, 'password')->passwordInput()->label('密 码') ?>
                        <?= $form->field($model, 'rememberMe')->checkbox()->label('记 住 我') ?>
                        <div style="color:#999;margin:1em 0">
                            If you forgot your password you can <?= Html::a('reset it', ['user/request-password-reset']) ?>.
                            For new user you can <?= Html::a('signup', ['user/signup']) ?>.
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('rbac-admin', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="page-footer-custom"> 2014 &copy; Metronic. Admin Dashboard Template. </div>
        </div>


    </body>
<?php $this->endBody() ?>
<script>
    var Lock = function () {

    return {
        //main function to initiate the module
        init: function () {

             $.backstretch([
                "<?=$assets_url?>/pages/media/bg/1.jpg",
                "<?=$assets_url?>/pages/media/bg/2.jpg",
                "<?=$assets_url?>/pages/media/bg/3.jpg",
                "<?=$assets_url?>/pages/media/bg/4.jpg"
                ], {
                  fade: 1000,
                  duration: 8000
              });
        }

    };

}();

jQuery(document).ready(function() {
    Lock.init();
});
</script>
</html>
<?php $this->endPage() ?>