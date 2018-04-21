<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CompanySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'c_id') ?>

    <?= $form->field($model, 'codeid') ?>

    <?= $form->field($model, 'code_city') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'c_name') ?>

    <?php // echo $form->field($model, 'c_website') ?>

    <?php // echo $form->field($model, 'c_logo') ?>

    <?php // echo $form->field($model, 'c_add') ?>

    <?php // echo $form->field($model, 'c_gps') ?>

    <?php // echo $form->field($model, 'c_area') ?>

    <?php // echo $form->field($model, 'reg_ip') ?>

    <?php // echo $form->field($model, 'reg_time') ?>

    <?php // echo $form->field($model, 'c_level') ?>

    <?php // echo $form->field($model, 'review') ?>

    <?php // echo $form->field($model, 'review_user') ?>

    <?php // echo $form->field($model, 'review_log_id') ?>

    <?php // echo $form->field($model, 'review_time') ?>

    <?php // echo $form->field($model, 'zdjj_id') ?>

    <?php // echo $form->field($model, 'zdjj_ks') ?>

    <?php // echo $form->field($model, 'zdjj_js') ?>

    <?php // echo $form->field($model, 'zdjj_mod') ?>

    <?php // echo $form->field($model, 'invoice_name') ?>

    <?php // echo $form->field($model, 'invoice_taxcode') ?>

    <?php // echo $form->field($model, 'invoice_add') ?>

    <?php // echo $form->field($model, 'invoice_tel') ?>

    <?php // echo $form->field($model, 'invoice_bank') ?>

    <?php // echo $form->field($model, 'invoice_bank_account') ?>

    <?php // echo $form->field($model, 'cj') ?>

    <?php // echo $form->field($model, 'fw') ?>

    <?php // echo $form->field($model, 'hp') ?>

    <?php // echo $form->field($model, 'cp') ?>

    <?php // echo $form->field($model, 'xl') ?>

    <?php // echo $form->field($model, 'xlr') ?>

    <?php // echo $form->field($model, 'xldh') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'is_bidding') ?>

    <?php // echo $form->field($model, 'bidding') ?>

    <?php // echo $form->field($model, 'bidding_num') ?>

    <?php // echo $form->field($model, 'bidding_back') ?>

    <?php // echo $form->field($model, 'bidding_modify') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
