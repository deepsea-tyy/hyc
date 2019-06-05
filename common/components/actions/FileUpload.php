<?php
namespace common\components\actions;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\File;

/**
* 文件上传
*/
class FileUpload extends Action
{
	
	public function run()
    {
    	$file = UploadedFile::getInstanceByName('file'); //单文件
        // $fileBlob = UploadedFile::getInstanceByName('fileBlob'); //分片文件
        // Yii::$app->request->post('chunkCount');//总分片
        // Yii::$app->request->post('chunkIndex');//上传第几分片
    	if (empty($file)) return $this->controller->asJson(['error'=>'未选择文件']);
    	$dirpath = File::getFileStoragePath();
		if(!file_exists($dirpath)){
			mkdir($dirpath, 0777, true);
		}
        $filename = '/' . md5($file->baseName . '_' . time()) . '.' .$file->extension;
		$imgpath = Yii::$app->params['file_storage']['local']['dirpath'] . $filename;
        $realpath = $dirpath . $filename;
        if ($file->saveAs($realpath)) {//保存图片
        	//保存信息
            $img = getimagesize($realpath);
            $data = [
                'photo_width' =>$img[0],
                'photo_hight' =>$img[1],
                'file_size' =>$file->size,
                'type' =>$file->type,
                'name' =>$file->name,
                'file_url' =>$imgpath,
                'created_by' =>Yii::$app->user->id,
            ];
            $model = new File();
            $model->load($data,'');
            if ($model->save()) {
               //返回插件数据
                $img = Html::img($imgpath, ['style' => 'width:auto;height:auto;max-width:100%;max-height:100%;']);
                $config[] = [
                    'caption' => $file->name,
                    'key' => $model->id, // 图片标示
                    'size' => $file->size,
                    'downloadUrl' => $imgpath, // 下载地址
                    'url' => Url::to(['public/filedelete']), // 删除地址 对应 key
                ];
               $output = ['initialPreview' => [$img], 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
            }else{
                unlink($realpath);
                $output = ['error'=>$model->getErrors()];
            }
        } else {
            $output = ['error'=>'上传失败'];
        }
        return $this->controller->asJson($output);
    }

}