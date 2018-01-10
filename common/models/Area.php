<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sys_area".
 *
 * @property integer $codeid
 * @property integer $parentid
 * @property string $city
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codeid', 'parentid', 'city'], 'required'],
            [['codeid', 'parentid'], 'integer'],
            [['city'], 'string', 'max' => 180],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'codeid' => 'Codeid',
            'parentid' => 'Parentid',
            'city' => 'City',
        ];
    }

    /**
     * @inheritdoc
     * @return AreaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AreaQuery(get_called_class());
    }
}
