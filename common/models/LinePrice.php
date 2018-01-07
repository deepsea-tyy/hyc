<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "line_price".
 *
 * @property integer $starting
 * @property integer $destination
 * @property string $price
 * @property integer $startingshow
 * @property integer $destinationshow
 */
class LinePrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'line_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['starting', 'destination', 'startingshow', 'destinationshow'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'starting' => '起运地',
            'destination' => '目的地',
            'price' => '价格',
            'startingshow' => '补全6位',
            'destinationshow' => '补全6位',
        ];
    }

    /**
     * @inheritdoc
     * @return LinePriceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinePriceQuery(get_called_class());
    }
}
