<?php

namespace console\models\mongo\hf;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for collection "hf_brand".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $index
 * @property mixed $url
 * @property mixed $name
 * @property mixed $logo
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class HfBrand extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['spider', 'hf_brand'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return [
            '_id',
            'initial',
            'url',
            'name',
            'logo',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['index', 'url', 'name', 'logo', 'created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'index' => 'Index',
            'url' => 'Url',
            'name' => 'Name',
            'logo' => 'Logo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
