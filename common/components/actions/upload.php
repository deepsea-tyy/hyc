<?php
namespace common\components\actions;

use yii\base\Action;
use yii\web\UploadedFile;
use common\models\UploadedFile as UploadedFileModel;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
* 上传
*/
class upload extends Action
{
	
	public function run()
    {
    	$file = UploadedFile::getInstanceByName('file'); //单文件
        // $fileBlob = UploadedFile::getInstanceByName('fileBlob'); //分片文件
        // Yii::$app->request->post('chunkCount');//总分片
        // Yii::$app->request->post('chunkIndex');//上传第几分片
    	if (empty($file)) return $this->controller->asJson(['error'=>'未选择文件']);
    	$dirpath = Yii::getAlias('@backend') .'/web';
		if(!file_exists($dirpath)){
			mkdir($dirpath, 0777, true);
		}
		// $imgpath = '/static/upload/' . md5($file->baseName . '_' . time()) . '.' .$file->extension;
    	$imgpath = "http://g.hiphotos.baidu.com/image/pic/item/c8ea15ce36d3d539f09733493187e950342ab095.jpg";
        if (1/*$file->saveAs($dirpath . $imgpath)*/) {//保存图片
        	//保存信息
        	// $model = new UploadedFileModel();
        	// $model->size = $file->size;
        	// $model->name = $file->name;

        	//返回插件数据
	    	$img = Html::img($imgpath, ['style' => 'width:auto;height:auto;max-width:100%;max-height:100%;']);
			$config[] = [
	                    'caption' => $file->name,
	                    'key' => Yii::$app->request->post('fileId'), // 图片标示
	                    'size' => $file->size,
	                    'downloadUrl' => $imgpath, // 下载地址
	                    'url' => Url::to(['img/delete']), // 删除地址 对应 key
	                ];
	        $output = ['initialPreview' => [$img], 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
        } else {
            $output = ['error'=>'上传失败'];
        }
        return $this->controller->asJson($output);
    }

}