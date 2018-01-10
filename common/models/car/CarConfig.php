<?php

namespace common\models\car;

use Yii;

/**
 * This is the model class for table "car_config".
 *
 * @property integer $id
 * @property integer $styling_id
 * @property string $ckg
 */
class CarConfig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_styling_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['styling_id'], 'integer'],
            [['ckg'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'styling_id' => 'Styling ID',
            'ckg' => '长宽高(mm)',
        ];
    }

    /**
     * @inheritdoc
     * @return CarConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CarConfigQuery(get_called_class());
    }
}
