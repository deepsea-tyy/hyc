<?php
namespace common\components\actions;

use Yii;
use yii\base\Action;
use common\models\File;

/**
* 文件删除
*/
class FileDelete extends Action
{
	public function run()
    {
        $key = Yii::$app->request->post('key');
        $model = File::find()->where(['id'=>$key])->one();
        $file = File::getFilePath($model->file_url);
        if (file_exists($file)) {
            @unlink($file);
            $model->delete();
            return $this->controller->asJson(['success'=>'删除成功']);
        }
    }
}