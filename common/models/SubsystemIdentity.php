<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%subsystem_identity}}".
 *
 * @property int $id
 * @property int $uid 用户id全局唯一
 * @property string $s_name 子系统名称
 * @property int $s_uid 子系统uid
 * @property int $created_at
 * @property int $updated_at
 */
class SubsystemIdentity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subsystem_identity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 's_uid', 'type'], 'integer'],
            [['s_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            's_name' => 'S Name',
            's_uid' => 'S Uid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return SubsystemIdentityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubsystemIdentityQuery(get_called_class());
    }
}
