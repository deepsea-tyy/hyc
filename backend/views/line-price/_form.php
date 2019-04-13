<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\hyc\LinePrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="line-price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'starting')->textInput() ?>

    <?= $form->field($model, 'destination')->textInput() ?>

    <?= $form->field($model, 'startingshow')->textInput() ?>

    <?= $form->field($model, 'destinationshow')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
