<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
        return  Yii::$app->params['file_storage']['local']['dirpath'] . $path;
    }

    /**
     * 文件访问url
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
    public static function getFileDir($type)
    {
        $fileDir = '';
        if (in_array($type, ['png','jpg','jpeg','gif','bmp'])) {
            $fileDir = '/images';
        }elseif (in_array($type, ['flv','swf','mkv','avi','rm','rmvb','mpeg','mpg','ogg','ogv','mov','wmv','mp4','webm','mp3','wav','mid'])) {
            $fileDir = '/videos';
        }else{
            $fileDir = '/files';
        }
        $dir = static::getFileStoragePath() . $fileDir . '/' . date('Ymd',time());
        // echo $dir;exit();
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        return Yii::$app->params['file_storage']['local']['dirpath'] . $fileDir . '/' . date('Ymd',time());
    }

}
