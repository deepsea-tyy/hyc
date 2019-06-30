<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'login-form', 'options'=>['class'=>'login-form']]); ?>
    <h3 class="form-title font-green">登陆</h3>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?php
    // echo $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
    //    'captchaAction' => '/site/captcha',
    //    'template' => '<div class="row"><div class="col-lg-12">{image}</div><div class="col-lg-12">{input}</div></div>',
    // ])
    ?>

    <?=$form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
        'captchaAction'=>'/site/captcha',
        'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer']
    ])?>
    <div style="color:#999;margin:1em 0">
        忘记密码你可以 <?= Html::a('重 置', ['/site/request-password-reset']) ?>
         <?= Html::a('注 册', ['/site/signup'],['class'=>'pull-right']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('确 定', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        <label class="rememberme check"><?= Html::checkbox($model->formName().'[rememberMe]',$model->rememberMe) ?> 记住我</label>
        <?= Html::a('忘 记 密 码?', ['/site/request-password-reset'],['class'=>'forget-password']) ?>
    </div>

<?php ActiveForm::end(); ?>