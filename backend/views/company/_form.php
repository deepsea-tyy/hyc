<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-form">

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'c_id')->textInput() ?>

    <?= $form->field($model, 'codeid')->textInput() ?>

    <?= $form->field($model, 'code_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->textInput() ?>

    <?= $form->field($model, 'c_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_add')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_gps')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'c_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_time')->textInput() ?>

    <?= $form->field($model, 'c_level')->textInput() ?>

    <?= $form->field($model, 'review')->textInput() ?>

    <?= $form->field($model, 'review_user')->textInput() ?>

    <?= $form->field($model, 'review_log_id')->textInput() ?>

    <?= $form->field($model, 'review_time')->textInput() ?>

    <?= $form->field($model, 'zdjj_id')->textInput() ?>

    <?= $form->field($model, 'zdjj_ks')->textInput() ?>

    <?= $form->field($model, 'zdjj_js')->textInput() ?>

    <?= $form->field($model, 'zdjj_mod')->textInput() ?>

    <?= $form->field($model, 'invoice_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_taxcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_add')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_bank')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoice_bank_account')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cj')->textInput() ?>

    <?= $form->field($model, 'fw')->textInput() ?>

    <?= $form->field($model, 'hp')->textInput() ?>

    <?= $form->field($model, 'cp')->textInput() ?>

    <?= $form->field($model, 'xl')->textInput() ?>

    <?= $form->field($model, 'xlr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'xldh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_bidding')->textInput() ?>

    <?= $form->field($model, 'bidding')->textInput() ?>

    <?= $form->field($model, 'bidding_num')->textInput() ?>

    <?= $form->field($model, 'bidding_back')->textInput() ?>

    <?= $form->field($model, 'bidding_modify')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


