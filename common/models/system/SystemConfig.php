<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "{{%system_config}}".
 *
 * @property string $name
 * @property string $content
 * @property string $introduction
 */
class SystemConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_system_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['introduction'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '参数名称',
            'content' => 'Content',
            'introduction' => '描述',
        ];
    }

    /**
     * @inheritdoc
     * @return SystemConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SystemConfigQuery(get_called_class());
    }
}
