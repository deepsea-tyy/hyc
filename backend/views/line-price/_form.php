<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\LinePrice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="line-price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'starting')->textInput() ?>

    <?= $form->field($model, 'destination')->textInput() ?>

    <?= $form->field($model, 'startingshow')->textInput() ?>

    <?= $form->field($model, 'destinationshow')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
