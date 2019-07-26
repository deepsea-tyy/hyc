<?php

namespace console\models\mongo\hf;

use Yii;

/**
 * This is the model class for collection "configs".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 */
class Configs extends \yii\mongodb\ActiveRecord
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
            '_id',
            'name',
            'data',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
        ];
    }
}
