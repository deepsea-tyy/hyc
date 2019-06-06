<?php
namespace common\components\actions;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\File;
use yii\data\Pagination;

/**
* 文件上传
*/
class FileAction extends Action
{
    public $config = [
        'allowFiles' => ['png','jpg','jpeg','gif','bmp','flv','swf','mkv','avi','rm','rmvb','mpeg','mpg','ogg','ogv','mov','wmv','mp4','webm','mp3','wav','mid','rar','zip','tar','gz','7z','bz2','cab','iso','doc','docx','xls','xlsx','ppt','pptx','pdf','txt','md','xml'
        ],
        'maxSize' => 2048000
    ];
    public function init()
    {
        Yii::$app->request->enableCsrfValidation = false;
    }
	
	public function run()
    {
        switch ($this->id) {
            case 'fileupload':
                $res = $this->upload();
                break;
            case 'ueditor':
                $res = $this->ueditor();
                break;
            case 'filedelete':
                $res = $this->delete();
                break;
            
            default:
                # code...
                break;
        }
        return $this->controller->asJson($res);
    }

    /**
     * ueditor插件上传文件
     */
    protected function ueditor()
    {
        $action = Yii::$app->request->get('action');
        switch ($action) {
            case 'config':
                return require(Yii::getAlias('@sh/ueditor').'/config.php');
                break;
            /* 列出图片 */
            case 'listimage':
                /* 列出文件 */
            case 'listfile':
                return $this->ueditorList();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                break;
        }

        $file = $this->checkFile(); //单文件
        switch ($file) {
            case null:
                return ['state'=>'未选择文件'];
                break;
            case '1':
                return ['state'=>'文件类型不许可'];
                break;
            case '2':
                return ['state'=>'文件大小不许可'];
                break;
        }

        if ($data = $this->saveFile($file)) {
            if ($this->saveData($data)) {
                return [
                    'state' => 'SUCCESS',                       //上传状态，上传成功时必须返回'SUCCESS'
                    'url' => $data['file_url'],                 //返回的地址
                    'title' => $file->name,                     //新文件名
                    'original' => $file->name,                  //原始文件名
                    'type' => '.'.$file->extension,             //文件类型
                    'size' => $file->size,                      //文件大小
                ];
            }else{
                unlink(File::getFilePath($data['file_url']));
                return ['state'=>'上传失败'];
            }
        } else {
            return ['state'=>'上传失败'];
        }
    }

    /**
     * fileinput插件上传文件
     */
    protected function upload()
    {
        $file = $this->checkFile(); //单文件
        switch ($file) {
            case null:
                return ['error'=>'未选择文件'];
                break;
            case '1':
                return ['error'=>'文件类型不许可'];
                break;
            case '2':
                return ['error'=>'文件大小不许可'];
                break;
        }
        
        if ($data = $this->saveFile($file)) {
            if ($this->saveData($data)) {
                $img = Html::img($data['file_url'], ['style' => 'width:auto;height:auto;max-width:100%;max-height:100%;']);
                $config[] = [
                    'caption' => $file->name,
                    'key' => $data['id'], // 图片标示
                    'size' => $file->size,
                    // 'downloadUrl' => $data['file_url'], // 下载地址
                    'url' => Url::to(['public/filedelete']), // 删除地址 对应 key
                ];
               return ['initialPreview' => [$img], 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
            }else{
                unlink(File::getFilePath($data['file_url']));
                return ['error'=>'上传失败'];
            }
        } else {
            return ['error'=>'上传失败'];
        }
    }

    /**
     * 文件检测
     */
    protected function checkFile($fname='file')
    {
        $this->config['allowFiles'];
        $file = UploadedFile::getInstanceByName('file'); //单文件
        if ($file === null) return $file;
        if (!in_array(strtolower($file->extension), $this->config['allowFiles'])) return 1;
        if ($file->size > $this->config['maxSize']) return 2;
        return $file;
    }


    /**
     * 保存文件
     * return array
     */
    protected function saveFile($file)
    {
        $filename = md5($file->baseName . '_' . time());
        $imgpath = File::getFileDir($file->extension) . '/' . $filename . '.' .$file->extension;
        $realpath = File::getFilePath($imgpath);
        if ($file->saveAs($realpath)) {
            $img = getimagesize($realpath);
            return [
                'id' => $filename,
                'photo_width' =>$img[0],
                'photo_hight' =>$img[1],
                'file_size' =>$file->size,
                'type' =>$file->type,
                'name' =>$file->name,
                'file_url' =>$imgpath,
                'created_by' =>Yii::$app->user->id,
            ];
         }
         return false;
    }

    /**
     * 保存数据
     */
    protected function saveData($data)
    {
        $model = new File();
        $model->load($data,'');
        return $model->save();
    }

    /**
     * 删除文件
     */
    protected function delete()
    {
        $key = Yii::$app->request->post('key');
        $model = File::find()->where(['id'=>$key])->one();
        if (File::fileDelete($model->file_url)) {
            $model->delete();
            return ['success'=>'删除成功'];
        }
        return ['error'=>'文件删除失败'];
    }

    /**
     * 编辑器图片列表
     */
    protected function ueditorList()
    {
        $config = require(Yii::getAlias('@sh/ueditor').'/config.php');
        $query = File::find();
        $count = $query->count();
        /* 获取参数 */
        $size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $config['fileManagerListSize'];
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $list = $query
            ->select(['name','url'=>'file_url'])
            ->offset($start)
            ->limit($size)
            // ->createCommand()
            // ->getRawSql();
            ->asArray()
            ->all();
        if ($list) {
            return [
                'state' => 'SUCCESS',
                'list' => $list,
                'start' => $start,
                'total' => $count
            ];
        }else{
            return [
                'state' => 'no match file',
                'list' => [],
                'start' => $start,
                'total' => $count
            ];
        }
    }

    /**
     * 分片文件
     */
    public function chunkFile()
    {
        
        // $fileBlob = UploadedFile::getInstanceByName('fileBlob'); //分片文件
        // Yii::$app->request->post('chunkCount');//总分片
        // Yii::$app->request->post('chunkIndex');//上传第几分片
    }
}