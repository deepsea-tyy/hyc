<?php

namespace common\models\car;

use Yii;

/**
 * This is the model class for table "car_series_color".
 *
 * @property integer $id
 * @property string $color
 * @property string $name
 * @property string $series_id
 */
class CarSeriesColor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_series_color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['color', 'name'], 'string', 'max' => 255],
            [['series_id'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'name' => 'Name',
            's_id' => 'Series ID',
        ];
    }

    /**
     * @inheritdoc
     * @return CarSeriesColorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CarSeriesColorQuery(get_called_class());
    }
}
