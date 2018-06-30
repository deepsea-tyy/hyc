<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this  yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = Yii::t('rbac-admin', 'Update User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Update User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="create-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'u_type')->textInput() ?>

    <?= $form->field($model, 'c_id')->textInput() ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wechar_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'blog_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qq_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_login_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'task')->textInput() ?>

    <?= $form->field($model, 'deals')->textInput() ?>

    <?= $form->field($model, 'fails')->textInput() ?>

    <?= $form->field($model, 'revoke_times')->textInput() ?>

    <?= $form->field($model, 'money')->textInput() ?>

    <?= $form->field($model, 'money_crc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>
