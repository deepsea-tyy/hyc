<?php

namespace common\models\car;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "car_brand".
 *
 * @property integer $id
 * @property string $brand
 * @property string $logo
 * @property string $oid
 * @property integer $created_at
 * @property integer $updated_at
 */
class CarBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'car_brand';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oid'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['brand', 'logo'], 'string', 'max' => 255],
            [['oid'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => '品牌',
            'logo' => 'Logo',
            'oid' => 'Oid',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return CarBrandQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CarBrandQuery(get_called_class());
    }
}
