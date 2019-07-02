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
    // //图片上传插件
    echo $form->field($model, 'c_logo')->widget(FileInput::classname(), [
        'options' => [
            'accept' => '*',
            'name' => 'file',
            'multiple' => true,
        ],
        'pluginOptions' => [
            // 'theme'=> 'explorer', //列表显示
            'uploadUrl'=> Url::to(['public/fileupload']),
            'maxFileCount'=> 10, //上传数量
            'overwriteInitial'=> false,
            'initialPreviewAsData'=> true,//插件默认展示

            'allowedFileTypes'=> ['image'],//文件类型
            // //分片设置
            // 'enableResumableUpload'=> true,//开启分片
            // 'showCancel'=> true,//取消按钮
            // 'resumableUploadOptions'=> [
            //     // 'testUrl'=> "/site/test-file-chunks", //分片检测续传等功能
            //     'chunkSize'=> 1024 , // 1 kB chunk size
            // ],

            'layoutTemplates' => [
                // 'actionUpload'=>''//去除上传预览缩略图中的上传图片
                // 'actionZoom'=>'',   //去除上传预览缩略图中的查看详情预览的缩略图标
                'actionDownload'=>'' //去除上传预览缩略图中的下载图标
                // 'actionDelete'=>'', //去除上传预览的缩略图中的删除图标
            ],
            'uploadExtraData'=> ['uid'=>1], //拓展参数
        ],
        'pluginEvents' => [//回调事件
            // 上传成功后执行
            'fileuploaded'=> new JsExpression('function(event, params) {
                                console.log(params)
            }'),
            // 排序后执行
            'filesorted'=> new JsExpression('function(event, params) {
                                console.log(params.stack)
            }'),
            // 分片上传等待一秒
            'filechunkbeforesend'=> new JsExpression('function(event, params) {
                            var start = (new Date()).getTime();
                            while ((new Date()).getTime() - start < 100) {
                                continue;
                            }
            }'),
        ]
    ]);

    //视频播放插件
    /*echo \hzhihua\videojs\VideoJsWidget::widget([
        'options' => [  // video tag attibutes
            'class' => 'video-js vjs-default-skin vjs-big-play-centered',
            'title' => '',
            'poster' => "//vjs.zencdn.net/v/oceans.png",  // 视频播放封面地址
            'controls' => true, // 显示控制页面
            'width' => '300', // 设置宽度
            'data' => [
                'setup' => [
                    'language' => 'zh', // 设置语言
                ],
            ],
        ],
        'jsOptions' => [
            'playbackRates' => [0.5, 1, 1.5, 2],  // 播放速率选项
        ],
        'tags' => [
            'source' => [
                ['src' => '//vjs.zencdn.net/v/oceans.mp4', 'type' => 'video/mp4'],
            ],
            'p' => [
                ['content' => '您的浏览器不支持媒体播放'],
            ],
        ]
    ]);*/

    //下拉选择插件
   /* echo $form->field($dynamicModel, 'test',['options'=>['class'=>'form-group col1-md-4']])->widget('kartik\select2\Select2',[
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
    ]);*/
    // 多极联动选择插件
    // echo $form->field($dynamicModel, 't1')->widget('sh\cxselect\Cxselect',['url'=>Url::to(['public/area']),'options'=>['data-first-title'=>'请选择','data-first-value'=>'0','data-json-value'=>'id','data-json-name'=>'name','data-json-sub'=>'_child']]);
    // 编辑器插件
    // echo $form->field($model, 'c_name')->widget('sh\ueditor\UEditor',[
    //     'clientOptions'=>[
    //         'serverUrl'=>Url::to(['public/ueditor']),
    //         'UEDITOR_HOME_URL'=>"$UEditorAsset/"
    //     ]
    // ]);
    //头像裁剪
    // echo $form->field($model, 'c_logo')->widget(\budyaga\cropper\Widget::className(), [
    //     'uploadUrl' => Url::toRoute(['public/imagecropper']),
    // ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


