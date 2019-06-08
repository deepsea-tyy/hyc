<?php
namespace common\components\actions;

use Yii;
use yii\base\Action;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\File;
use yii\data\Pagination;
use yii\helpers\FileHelper;
use common\helpers\Tools;

/**
* 文件上传操作
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
            }
            File::fileDelete($data['file_url']);
        }
        return ['state'=>'上传失败'];
    }

    /**
     * fileinput插件上传文件
     */
    protected function upload()
    {
        $file = $this->checkFile(); //单文件
        $file2 = $this->checkFile('fileBlob'); //单文件
        $file = $file ? $file : $file2;
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

        if ($data = $this->saveFile($file, $file2 ? 2:1)) {
            if ($data == 2) return ['chunkIndex' => Yii::$app->request->post('chunkIndex')];
            if ($this->saveData($data)) {
                $mime = explode('/', $data['type']);
                $show = [$this->show($mime)];
                $config[] = [
                    'caption' => $file->name,
                    'key' => $data['id'], // 图片标示
                    'fileId' => Yii::$app->request->post('fileId'),
                    'size' => Yii::$app->request->post('fileSize'),
                    'url' => Url::to(['public/filedelete']), // 删除地址 对应 key
                    'type' => $mime[0]
                    // 'downloadUrl' => $data['file_url'], // 下载地址
                ];
                if ($file2) {
                    return ['initialPreview' => $show, 'initialPreviewConfig' => $config, 'append' => true,
                        'chunkIndex' => Yii::$app->request->post('chunkIndex')];
                }else {
                    return ['initialPreview' => $show, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
                }
            }
            File::fileDelete($data['file_url']);
        }
        return ['error'=>'上传失败'];
    }

    /**
     * fileinput文件显示
     * ['image', 'html', 'text', 'video', 'audio', 'flash', 'object']
     */
    public function show($url,$mime='image')
    {
        switch ($mime) {
            case 'image':
                return Html::img($url, ['style' => 'width:auto;height:auto;max-width:100%;max-height:100%;']);
                break;
            case 'video':
                return Html::video($url);
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * 文件检测
     */
    protected function checkFile($fname='file')
    {
        $this->config['allowFiles'];
        $file = UploadedFile::getInstanceByName($fname); //接收文件
        if ($file === null) return $file;
        if (!in_array(strtolower($file->extension), $this->config['allowFiles'])) return 1;
        if ($file->size > $this->config['maxSize']) return 2;
        return $file;
    }


    /**
     * 保存文件
     * $type 1:单文件直接存储 2:分片文件临时存储
     * return array
     */
    protected function saveFile($file,$type=1)
    {
        switch ($type) {
            case 1:
                $filename = Tools::get_sn(10);
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
                break;
            case 2:
                $filename = Yii::$app->user->id . '_'. md5($file->baseName) . '_' . Yii::$app->request->post('chunkIndex') . '.' .$file->extension;
                $realpath = File::getChunkFileDir($filename);
                if ($file->saveAs($realpath)){
                    $filename = Yii::$app->user->id . '_'. md5($file->baseName);
                    $count = Yii::$app->request->post('chunkCount');
                    if ($mergePath = File::mergeChunkFile($filename,$count,$file->extension)) {
                        $img = getimagesize(File::getFilePath($mergePath));
                        return [
                            'id' => basename($mergePath, '.' . $file->extension),
                            'photo_width' =>$img[0],
                            'photo_hight' =>$img[1],
                            'file_size' =>$file->size,
                            'type' =>FileHelper::getMimeType(File::getFilePath($mergePath)),
                            'name' =>$file->name,
                            'file_url' =>$mergePath,
                            'created_by' =>Yii::$app->user->id,
                        ];
                    }
                }
                return 2;
                break;
            
            default:
                return false;
                break;
        }
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
}