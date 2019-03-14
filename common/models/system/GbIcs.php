<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "sys_gb_ics".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $num 标准数量
 * @property int $leaf 0无子集1有子集
 */
class GbIcs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_gb_ics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num', 'leaf'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'num' => 'Num',
            'leaf' => 'Leaf',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GbIcsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GbIcsQuery(get_called_class());
    }
}
