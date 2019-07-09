<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;//输出js代码
use kartik\file\FileInput;
use sh\ueditor\UEditorAsset;
UEditorAsset::register($this);

$UEditorAsset=$this->assetBundles[UEditorAsset::className()]->baseUrl;

/* @var $this yii\web\View */
/* @var $model common\models\mall\Goods */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-form">

    <?php $form = ActiveForm::begin(['options'=>['data-goUrl'=>'/goods']]); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'gn')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'brief')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'price')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'costprice')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'mktprice')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <input type="hidden" name="image_ids" id="image_ids" value='<?=$image_ids??''?>'>
    <?//图片上传插件
        echo $form->field($model, 'image_id')->widget(FileInput::classname(), [
            'options' => [
                'accept' => 'image/*',
                'name' => 'file',
                'multiple' => true,
            ],
            'pluginOptions' => [
                'uploadUrl'=> '/public/fileupload',
                'maxFileCount'=> 5, //上传数量
                'overwriteInitial'=> false,
                'initialPreviewAsData'=>true,
                'allowedFileTypes'=> ['image'],//文件类型
                'initialCaption'=>'最多5张图片',

                'layoutTemplates' => [
                    'actionDownload'=>'' //去除上传预览缩略图中的下载图标
                ],

                'initialPreview'=> $initialPreview ?? [],
                'initialPreviewConfig' => $initialPreviewConfig ?? [],
                'uploadExtraData'=> [//拓展参数
                    // 'uid'=>Yii::$app->user->identity->id,
                    'edit' => empty($model->id) ? 0 : 1
                ], 
            ],
            'pluginEvents' => [//回调事件
                // 上传成功后执行
                'fileuploaded'=> new JsExpression('function(event, params) {
                    json_value("#image_ids",{"image_id":params.response.initialPreviewConfig[0]["key"]});
                }'),
                // 排序后执行
                'filesorted'=> new JsExpression('function(event, params) {
                    var data = params.stack.map(function(val,index){
                        return {"image_id":val["key"]};
                    });
                    json_value("#image_ids",data,3);
                }'),
                // 删除
                'filedeleted'=> new JsExpression('function(event, key, jqXHR, data) {
                    // console.log(key)
                    json_value("#image_ids",{"image_id":key},2);
                }'),
            ]
        ]);?>
    
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'goods_type_id')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'brand_id')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'is_nomal_virtual')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'is_on_shelves')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'on_shelves_time')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'off_shelves_time')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'stock')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'freeze_stock')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'volume')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'weight_unit')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'volume_unit')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'goods_cat_id')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'spes_desc')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'params')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'comments_count')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'view_count')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'buy_count')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'sort')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'is_recommend')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'is_hot')->textInput() ?>
        </div>
        <div class="col-md-6">
        <?= $form->field($model, 'label_ids')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
        <?= $form->field($model, 'status')->textInput() ?>
        </div>
        <div class="col-md-6">
        </div>
    </div>

    <?// 编辑器插件
        echo $form->field($model, 'content')->widget('sh\ueditor\UEditor',[
            'clientOptions'=>[
                'serverUrl'=>'/public/ueditor',
                'UEDITOR_HOME_URL'=>"$UEditorAsset/",
                'zIndex' => 1000
            ]
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(empty($model->id) ? '保存' : '更新', ['class' => empty($model->id) ? 'btn btn-success' : 'btn btn-primary']) ?>
        <a class="btn default" data-pjax="true" href="/goods">返回</a>
    </div>

    <?php ActiveForm::end(); ?>

</div>