<?php
use yii\helpers\Html;
use common\widgets\ActiveForm;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\JsExpression;//输出js代码
use kartik\file\FileInput;
use sh\ueditor\UEditorAsset;
UEditorAsset::register($this);

$UEditorAsset=$this->assetBundles[UEditorAsset::className()]->baseUrl;
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

    <?php $form = ActiveForm::begin(); ?>
    <?
    //图片上传插件
    // echo $form->field($model, 'c_logo')->widget(FileInput::classname(), [
    //     'options' => [
    //         'accept' => 'image/*',
    //         'name' => 'file',
    //         'multiple' => true,
    //     ],
    //     'pluginOptions' => [
    //         // 'theme'=> 'explorer', //列表显示
    //         'uploadUrl'=> Url::to(['public/upload']),
    //         'maxFileCount'=> 10, //上传数量
    //         'overwriteInitial'=> false,

    //         'allowedFileTypes'=> ['image'],//文件类型
    //         'enableResumableUpload'=> true,//开启分片
    //         'showCancel'=> true,//取消按钮
    //         'resumableUploadOptions'=> [
    //             // 'testUrl'=> "/site/test-file-chunks", //分片检测续传等功能
    //             'chunkSize'=> 1, // 1 kB chunk size
    //         ],

    //         // 'uploadAsync'=>  false, //默认单个上传
    //         'layoutTemplates' => [
    //             // 'actionUpload'=>''//去除上传预览缩略图中的上传图片
    //             // 'actionZoom'=>'',   //去除上传预览缩略图中的查看详情预览的缩略图标
    //             'actionDownload'=>'' //去除上传预览缩略图中的下载图标
    //             // 'actionDelete'=>'', //去除上传预览的缩略图中的删除图标
    //         ],
    //         'uploadExtraData'=> ['uid'=>1], //拓展数据
    //     ],
    //     'pluginEvents' => [//回调事件
    //         // 上传成功后执行
    //         'fileuploaded'=> new JsExpression('function(event, data, msg) {
    //                             console.log(data, msg,event)
    //                         }'),
    //     ]
    // ]);
    ?>
    <?
    //下拉选择插件
    // = $form->field($dynamicModel, 'test',['options'=>['class'=>'form-group col1-md-4']])->widget('kartik\select2\Select2',[
    //     // 'theme'=>'default',
    //     // 'size' => 'sm',
    //     // 'data' => $data,
    //     'options' => ['placeholder' => 'Select a state ...'],
    //     'pluginOptions' => [
    //         'allowClear' => true,
    //         'ajax' => [
    //             'url' => Url::to(['public/index']),
    //             'dataType' => 'json',
    //             'processResults'=> new JsExpression('function (data) { return { results: data.items  }; }'),
    //         ],
    //     ],
    //     'pluginEvents' => [
    //         'change' => new JsExpression('function(a) {
    //             console.log(a);
    //         }'),
    //     ]
    // ])
    ?>

    <?
    // 多极联动选择插件
    // = $form->field($dynamicModel, 't1')->widget('sh\cxselect\Cxselect',['url'=>Url::to(['public/area']),'options'=>['data-first-title'=>'请选择','data-first-value'=>'0','data-json-value'=>'id','data-json-name'=>'name','data-json-sub'=>'_child']]) 
    ?>

    <?
    // 编辑器插件
    echo $form->field($model, 'c_name')->widget('sh\ueditor\UEditor',[
        'clientOptions'=>[
            'serverUrl'=>Url::to(['ueditor']),
            'UEDITOR_HOME_URL'=>"$UEditorAsset/"
        ]
    ])
    ?>

    <?= $form->field($model, 'code_city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->textInput() ?>

    <?= $form->field($model, 'c_website')->textInput(['maxlength' => true]) ?>

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


