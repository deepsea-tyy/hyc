<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shortrange}}".
 *
 * @property integer $s_id
 * @property integer $c_id
 * @property string $token
 * @property integer $qy_province
 * @property integer $qy_city
 * @property integer $qy_area
 * @property integer $md_province
 * @property integer $md_city
 * @property integer $md_area
 * @property string $distant_from
 * @property integer $rtype
 * @property integer $price
 * @property integer $suv_price
 * @property integer $departure_time
 * @property integer $days
 * @property integer $discount
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $search_times
 * @property integer $show_times
 * @property integer $is_bottom_price
 * @property integer $is_highest_price
 * @property string $remark
 * @property integer $status
 *
 * @property Company $c
 */
class Shortrange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shortrange}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id', 'qy_province', 'qy_city', 'qy_area', 'md_province', 'md_city', 'md_area', 'rtype', 'price', 'suv_price', 'departure_time', 'days', 'discount', 'create_at', 'update_at', 'created_by', 'updated_by', 'search_times', 'show_times', 'is_bottom_price', 'is_highest_price', 'status'], 'integer'],
            [['distant_from'], 'number'],
            [['token', 'remark'], 'string', 'max' => 200],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['c_id' => 'c_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => 'S ID',
            'c_id' => 'C ID',
            'token' => '一个公司的和条线路为一个TOKEN 用秒+5位随机数，编辑时 TOKEN不变，复制一个同样的KOKEN，前一条为状态0，新的为状态1可通知，删除时状态为-1',
            'qy_province' => 'Qy Province',
            'qy_city' => 'Qy City',
            'qy_area' => 'area',
            'md_province' => 'Md Province',
            'md_city' => 'Md City',
            'md_area' => 'area',
            'distant_from' => 'Distant From',
            'rtype' => '0小板
            1大板',
            'price' => 'Price',
            'suv_price' => 'Suv Price',
            'departure_time' => 'Departure Time',
            'days' => 'Days',
            'discount' => 'Discount',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'search_times' => 'Search Times',
            'show_times' => 'Show Times',
            'is_bottom_price' => 'Is Bottom Price',
            'is_highest_price' => 'Is Highest Price',
            'remark' => 'Remark',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(Company::className(), ['c_id' => 'c_id']);
    }

    /**
     * @inheritdoc
     * @return ShortrangeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShortrangeQuery(get_called_class());
    }
}
