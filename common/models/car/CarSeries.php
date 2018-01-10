<?php

namespace common\models\car;

use Yii;

/**
 * This is the model class for table "car_series".
 *
 * @property integer $id
 * @property string $b_id
 * @property string $factory
 * @property string $oid
 * @property string $url
 * @property string $new
 * @property string $name
 * @property string $price
 * @property string $price2Sc
 * @property string $offer
 */
class CarSeries extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_series';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_id', 'oid'], 'string', 'max' => 11],
            [['factory', 'url', 'name', 'price'], 'string', 'max' => 255],
            [['new'], 'string', 'max' => 1],
            [['price2Sc', 'offer'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'b_id' => 'B ID',
            'factory' => '生产商',
            'oid' => 'Oid',
            'url' => 'Url',
            'new' => 'New',
            'name' => '系列名称',
            'price' => '参考价',
            'price2Sc' => '二手价',
            'offer' => '车商报价',
        ];
    }

    /**
     * @inheritdoc
     * @return CarSeriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CarSeriesQuery(get_called_class());
    }
}
