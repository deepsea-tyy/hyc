<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%orderform}}".
 *
 * @property integer $o_id
 * @property integer $hycnum
 * @property integer $car_id
 * @property integer $qyd
 * @property string $qyd_py
 * @property string $qyd_gps
 * @property integer $mdd
 * @property string $mdd_py
 * @property string $mdd_gps
 * @property string $distant_from
 * @property integer $clgz
 * @property integer $clzk
 * @property integer $ysxlj
 * @property integer $c_id
 * @property string $bottom_price
 * @property integer $fhsj
 * @property integer $dhsj
 * @property string $xxms
 * @property integer $is_ok
 * @property integer $is_no
 * @property integer $is_gz
 * @property integer $r_id
 * @property integer $create_by
 * @property integer $update_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $remark
 * @property integer $status
 *
 * @property File[] $files
 * @property Offer[] $offers
 * @property OrderformRemark[] $orderformRemarks
 */
class Orderform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orderform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['o_id', 'created_at', 'updated_at'], 'required'],
            [['o_id', 'hycnum', 'car_id', 'qyd', 'mdd', 'clgz', 'clzk', 'ysxlj', 'c_id', 'fhsj', 'dhsj', 'is_ok', 'is_no', 'is_gz', 'r_id', 'create_by', 'update_by', 'created_at', 'updated_at', 'status'], 'integer'],
            [['distant_from', 'bottom_price'], 'number'],
            [['qyd_py', 'qyd_gps', 'mdd_py', 'mdd_gps'], 'string', 'max' => 50],
            [['xxms', 'remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'o_id' => 'O ID',
            'hycnum' => 'Hycnum',
            'car_id' => 'Car ID',
            'qyd' => 'Qyd',
            'qyd_py' => 'Qyd Py',
            'qyd_gps' => 'Qyd Gps',
            'mdd' => 'Mdd',
            'mdd_py' => 'Mdd Py',
            'mdd_gps' => 'Mdd Gps',
            'distant_from' => 'Distant From',
            'clgz' => 'Clgz',
            'clzk' => 'Clzk',
            'ysxlj' => 'Ysxlj',
            'c_id' => 'C ID',
            'bottom_price' => 'Bottom Price',
            'fhsj' => 'Fhsj',
            'dhsj' => 'Dhsj',
            'xxms' => 'Xxms',
            'is_ok' => 'Is Ok',
            'is_no' => 'Is No',
            'is_gz' => 'Is Gz',
            'r_id' => 'R ID',
            'create_by' => 'Create By',
            'update_by' => 'Update By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remark' => 'Remark',
            'status' => '2 正式发布
            1 草稿
            0 删除的',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['o_id' => 'o_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['o_id' => 'o_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderformRemarks()
    {
        return $this->hasMany(OrderformRemark::className(), ['o_id' => 'o_id']);
    }

    /**
     * @inheritdoc
     * @return OrderformQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderformQuery(get_called_class());
    }
}
