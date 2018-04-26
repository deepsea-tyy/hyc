<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\PasswordResetRequest */

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
    <h3 class="font-green">忘记密码 ?</h3>
    <?= $form->field($model, 'email') ?>
    <div class="form-group">
        <?= Html::a('返 回', ['user/login'], ['class' => 'btn green btn-outline']) ?>
        <?= Html::submitButton(Yii::t('rbac-admin', '确 定'), ['class' => 'btn btn-success uppercase pull-right']) ?>
    </div>
<?php ActiveForm::end(); ?>
