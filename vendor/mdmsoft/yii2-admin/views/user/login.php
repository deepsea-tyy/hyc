<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<?php $form = ActiveForm::begin(['id' => 'login-form', 'options'=>['class'=>'login-form']]); ?>
    <h3 class="form-title font-green">登陆</h3>
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?//=$form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
      //  'captchaAction' => '/admin/user/captcha',
      //  'template' => '<div class="row"><div class="col-lg-12">{image}</div><div class="col-lg-12">{input}</div></div>',
    //])?>

    <?=$form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
        'captchaAction'=>'user/captcha',
        'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer']
    ])?>
    <div style="color:#999;margin:1em 0">
        忘记密码你可以 <?= Html::a('重 置', ['user/request-password-reset']) ?>
         <?= Html::a('注 册', ['user/signup'],['class'=>'pull-right']) ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('rbac-admin', '确 定'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        <label class="rememberme check"><?= Html::checkbox($model->formName().'[rememberMe]',$model->rememberMe) ?> 记住我</label>
        <?= Html::a('忘 记 密 码?', ['user/request-password-reset'],['class'=>'forget-password']) ?>
    </div>

<?php ActiveForm::end(); ?>