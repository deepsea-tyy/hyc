<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%offer}}".
 *
 * @property integer $offer_id
 * @property integer $o_id
 * @property integer $c_id
 * @property integer $b_id
 * @property integer $yx_date
 * @property integer $tc_date
 * @property integer $sd_date
 * @property string $note
 * @property integer $is_sms
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $remark
 * @property integer $status
 *
 * @property Company $c
 * @property BiddingRules $b
 * @property Orderform $o
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%offer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offer_id', 'created_at', 'updated_at'], 'required'],
            [['offer_id', 'o_id', 'c_id', 'b_id', 'yx_date', 'tc_date', 'sd_date', 'is_sms', 'created_by', 'updated_by', 'created_at', 'updated_at', 'status'], 'integer'],
            [['note', 'remark'], 'string', 'max' => 255],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['c_id' => 'c_id']],
            [['b_id'], 'exist', 'skipOnError' => true, 'targetClass' => BiddingRules::className(), 'targetAttribute' => ['b_id' => 'b_id']],
            [['o_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orderform::className(), 'targetAttribute' => ['o_id' => 'o_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'offer_id' => 'Offer ID',
            'o_id' => 'O ID',
            'c_id' => 'C ID',
            'b_id' => 'B ID',
            'yx_date' => 'Yx Date',
            'tc_date' => 'Tc Date',
            'sd_date' => 'Sd Date',
            'note' => 'Note',
            'is_sms' => '成交后发送短信',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
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
    public function getC()
    {
        return $this->hasOne(Company::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getB()
    {
        return $this->hasOne(BiddingRules::className(), ['b_id' => 'b_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getO()
    {
        return $this->hasOne(Orderform::className(), ['o_id' => 'o_id']);
    }

    /**
     * @inheritdoc
     * @return OfferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OfferQuery(get_called_class());
    }
}
