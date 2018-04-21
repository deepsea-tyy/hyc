<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%uploaded_file}}".
 *
 * @property int $id
 * @property string $name
 * @property string $filename
 * @property int $size
 * @property string $type
 */
class UploadedFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%uploaded_file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['size'], 'integer'],
            [['name', 'filename'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'filename' => 'Filename',
            'size' => 'Size',
            'type' => 'Type',
        ];
    }

    /**
     * @inheritdoc
     * @return UploadedFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UploadedFileQuery(get_called_class());
    }
}
