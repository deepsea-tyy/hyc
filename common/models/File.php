<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\helpers\Tools;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property string $name
 * @property string $file_url
 * @property int $photo_width
 * @property int $photo_hight
 * @property int $created_by
 * @property int $file_size
 * @property int $status 1 有用 0 删除的
 * @property int $created_at
 * @property int $updated_at
 */
class File extends \yii\db\ActiveRecord
{
    public static $deleteUrl = '/public/filedelete';
    public static $uploadUrl = '/public/fileupload';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => time(),
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['photo_width', 'photo_hight', 'created_by', 'file_size', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'file_url'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 32],
            [['id'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'file_url' => 'File Url',
            'photo_width' => 'Photo Width',
            'photo_hight' => 'Photo Hight',
            'created_by' => 'Created By',
            'file_size' => 'File Size',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return FileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileQuery(get_called_class());
    }

    /**
     * 文件存根路径
     * @return string
     */
    public static function getFileStoragePath()
    {
        return Yii::$app->params['file_storage']['local']['rootpath'] . Yii::$app->params['file_storage']['local']['dirpath'];
    }

    /**
     * 文件存储路径
     * @return string
     */
    public static function getFilePath($path='')
    {
        return Yii::$app->params['file_storage']['local']['rootpath'] . $path;
    }

    /**
     * 文件访问url
     * @return string
     */
    public static function getFileUrl($path='')
    {
        return $path;
    }

    /**
     * 删除文件
     * @return string
     */
    public static function fileDelete($path='')
    {
        $file = static::getFilePath($path);
        if (file_exists($file)) {
            return @unlink($file);
        }
        return true;
    }

    /**
     * 获取存储文件夹
     * return string
     */
    public static function getFileDir($ext)
    {
        $fileDir = '';
        if (static::getFileTypeByExt($ext) == 'image') {
            $fileDir = '/images';
        }elseif (static::getFileTypeByExt($ext) == 'video') {
            $fileDir = '/videos';
        }elseif (static::getFileTypeByExt($ext) == 'file') {
            $fileDir = '/files';
        }
        $dir = static::getFileStoragePath() . $fileDir . '/' . date('Ymd',time());
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        return Yii::$app->params['file_storage']['local']['dirpath'] . $fileDir . '/' . date('Ymd',time());
    }

    /**
     * 获取文件类型
     */
    public static function getFileTypeByExt($ext='')
    {
        if (in_array($ext, ['png','jpg','jpeg','gif','bmp'])) return 'image';
        if (in_array($ext, ['flv','swf','mkv','avi','rm','rmvb','mpeg','mpg','ogg','ogv','mov','wmv','mp4','webm','mp3','wav','mid'])) return 'video';
        return 'file';
    }

    /**
     * 分片文件存储文件夹
     * return string
     */
    public static function getChunkFileDir($path='')
    {
        $dir = static::getFileStoragePath() . '/tmp/' . date('Ymd',time());
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        return $path ? $dir . '/' . $path : $dir;
    }

    /**
     * 合并分片文件
     */
    public static function mergeChunkFile($fname='',$count=0,$ext='')
    {
        $chunkpath = static::getChunkFileDir();
        $chunks = glob("{$chunkpath}/{$fname}_*");
        if ($count > 1 && count($chunks) == $count) {
            $realpath = static::getFileDir($ext) . '/' . Tools::get_sn(10) . '.' .$ext;
            $handle = fopen(static::getFilePath($realpath), 'a+');

            foreach ($chunks as $file) {
                fwrite($handle, file_get_contents($file));
            }

            foreach ($chunks as $file) {
                @unlink($file);
            }

            fclose($handle);
            return $realpath;
        }
        return false;
    }

}
