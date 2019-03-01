<?php

namespace common\models\applet;

use Yii;

/**
 * This is the model class for table "{{%business_data}}".
 *
 * @property string $bid 商户id
 * @property string $tab_name 表面
 * @property string $items 表ids
 */
class BusinessData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%business_data}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_applet');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['items'], 'string'],
            [['bid'], 'string', 'max' => 255],
            [['tab_name'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bid' => 'Bid',
            'tab_name' => 'Tab Name',
            'items' => 'Items',
        ];
    }

    /**
     * {@inheritdoc}
     * @return BusinessDataQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BusinessDataQuery(get_called_class());
    }
}
