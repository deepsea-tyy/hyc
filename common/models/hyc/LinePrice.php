<?php

namespace common\models\hyc;

use Yii;

/**
 * This is the model class for table "sys_line_price".
 *
 * @property int $id
 * @property int $starting 起运地
 * @property int $destination 目的地
 * @property int $startingshow 补全6位
 * @property int $destinationshow 补全6位
 * @property string $price 价格
 * @property int $create_at
 * @property int $updated_at
 */
class LinePrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_line_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['starting', 'destination', 'startingshow', 'destinationshow', 'create_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'starting' => 'Starting',
            'destination' => 'Destination',
            'startingshow' => 'Startingshow',
            'destinationshow' => 'Destinationshow',
            'price' => 'Price',
            'create_at' => 'Create At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return LinePriceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinePriceQuery(get_called_class());
    }
}
