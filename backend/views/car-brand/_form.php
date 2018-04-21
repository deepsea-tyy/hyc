<?php

use yii\helpers\Html;
use dlds\metronic\widgets\ActiveForm;
use dlds\metronic\widgets\Button;

/* @var $this yii\web\View */
/* @var $model common\models\car\CarBrand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-brand-form">

    <?php $form = ActiveForm::begin(['separated'=>true/*,'fieldClass'=>'dlds\metronic\widgets\ActiveField'*/,'buttons'=>[
    //optional, horizontal align
    'align' => ActiveForm::BUTTONS_ALIGN_LEFT,
    //optional, vertical position
    'position' => ActiveForm::BUTTONS_POSITION_BOTTOM,
     //optional, array of buttons
    'items' => [
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']),
        Button::widget(['label' => 'Back']),
    ],
    // optional, the HTML attributes (name-value pairs) for the form actions tag.
    'options' => ['class' => 'fluid']
]]); ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'oid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?php ActiveForm::end(); ?>

</div>
