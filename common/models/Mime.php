<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\components\behaviors\model\GetIdBehavior;

/**
 * This is the model class for table "{{%mime}}".
 *
 * @property int $mime_id
 * @property string $type_name
 * @property string $suffix
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $file_total_count
 * @property int $file_total_size
 * @property int $status 1 启用
             0 禁用
             
             10 公司照片?
             20 客户照片?
 *
 * @property File[] $files
 */
class Mime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%mime}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => GetIdBehavior::className(),
                'attribute' => 'mime_id',
                'value' => self::find()->max('mime_id') + 1,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by', 'file_total_count', 'file_total_size', 'status'], 'integer'],
            [['type_name', 'suffix'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mime_id' => 'Mime ID',
            'type_name' => 'Type Name',
            'suffix' => 'Suffix',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'file_total_count' => 'File Total Count',
            'file_total_size' => 'File Total Size',
            'status' => '1 启用
            0 禁用
            
            10 公司照片?
            20 客户照片?',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['mime_id' => 'mime_id']);
    }

    /**
     * @inheritdoc
     * @return MimeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MimeQuery(get_called_class());
    }
}
