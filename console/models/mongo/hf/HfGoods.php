<?php

namespace console\models\mongo\hf;

use Yii;

/**
 * This is the model class for collection "hf_goods".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class HfGoods extends \yii\mongodb\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function collectionName()
    {
        return ['spider', 'hf_goods'];
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
            'created_at',
            'updated_at',
            'sku_id',
            'sku_name',
            'sku_price',
            'sku_content',
            'sku_imags',
            'sku_parameter',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
