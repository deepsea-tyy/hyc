<?php

namespace common\models\car;

use Yii;

/**
 * This is the model class for table "car_series_styling".
 *
 * @property integer $id
 * @property string $group_name
 * @property string $name
 * @property string $oid
 * @property string $status
 * @property string $s_id
 * @property string $price
 * @property string $price2Sc
 * @property string $offer
 * @property integer $created_at
 * @property integer $updated_at
 */
class CarSeriesStyling extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_series_styling';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'integer'],
            [['group_name', 'name'], 'string', 'max' => 255],
            [['oid', 's_id'], 'string', 'max' => 11],
            [['status'], 'string', 'max' => 1],
            [['price', 'price2Sc', 'offer'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_name' => 'Group Name',
            'name' => 'Name',
            'oid' => 'Oid',
            'status' => '1在售',
            's_id' => '系列表oid',
            'price' => '参考价',
            'price2Sc' => '二手价',
            'offer' => '车商报价',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return CarSeriesStylingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CarSeriesStylingQuery(get_called_class());
    }
}
