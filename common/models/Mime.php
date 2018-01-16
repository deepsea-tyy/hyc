<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%mime}}".
 *
 * @property integer $mime_id
 * @property string $type_name
 * @property string $suffix
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $file_total_count
 * @property integer $file_total_size
 * @property integer $status
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mime_id', 'created_at', 'updated_at'], 'required'],
            [['mime_id', 'created_by', 'updated_by', 'created_at', 'updated_at', 'file_total_count', 'file_total_size', 'status'], 'integer'],
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
