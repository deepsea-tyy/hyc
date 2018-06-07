<?php

use yii\helpers\Html;
use common\widgets\ActiveForm;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Company */
/* @var $form yii\widgets\ActiveForm */
    $dynamicModel = new DynamicModel([
        'test','t1','t2'
    ]);
$dynamicModel->t1=211003;
$data = [1,2,3,4];
?>

<div class="company-form">

    <?php $form = ActiveForm::begin([
            // 'options' => [ 'enctype' => 'multipart/form-data']
    ]); ?>
    <?=$form->field($model, 'c_logo')->widget('manks\FileInput', ['clientOptions'=>['server'=>Url::to(['mime/upload'])]
]); ?>
    <?= $form->field($dynamicModel, 'test',['options'=>['class'=>'form-group col1-md-4']])->widget('kartik\select2\Select2',[
        // 'theme'=>'default',
        // 'size' => 'sm',
        // 'data' => $data,
        'options' => ['placeholder' => 'Select a state ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'ajax' => [
                'url' => Url::to(['public/index']),
                'dataType' => 'json',
                'processResults'=> new JsExpression('function (data) { return { results: data.items  }; }'),
            ],
        ],
        'pluginEvents' => [
            'change' => new JsExpression('function(a) {
                console.log(a);
            }'),
        ]
]) ?>


    <?= $form->field($dynamicModel, 't1')->widget('sh\cxselect\Cxselect',['url'=>Url::to(['public/area']),'options'=>['data-first-title'=>'请选择','data-first-value'=>'0','data-json-value'=>'id','data-json-name'=>'name','data-json-sub'=>'_child']]) ?>

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

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


