<?php

namespace console\models\redis;

use Yii;

/**
 * This is the model class for collection "configs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 */
class Rdemo extends \yii\redis\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['spider', 'configs'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            'id',
            'msg',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['msg'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }
}
