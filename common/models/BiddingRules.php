<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bidding_rules}}".
 *
 * @property integer $b_id
 * @property integer $c_id
 * @property integer $r_id
 * @property integer $is_sms
 * @property integer $is_sms_
 * @property integer $is_gps
 * @property integer $is_time
 * @property integer $is_invoice_gen
 * @property integer $is_invoice
 * @property integer $is_sf_picc
 * @property integer $is_sf_location
 * @property integer $is_jc_free
 * @property integer $is_sc_free
 * @property integer $is_car_wash
 * @property integer $is_navigation
 * @property integer $is_video
 * @property integer $is_photo
 * @property integer $is_picc
 * @property integer $is_contant_class
 * @property integer $is_v_car
 * @property integer $is_df
 * @property integer $is_scsx
 * @property integer $is_km
 * @property integer $is_cc
 * @property integer $is_tc
 * @property integer $is_jch
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property Company $c
 * @property Offer[] $offers
 */
class BiddingRules extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bidding_rules}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_id', 'created_at', 'updated_at'], 'required'],
            [['b_id', 'c_id', 'r_id', 'is_sms', 'is_sms_', 'is_gps', 'is_time', 'is_invoice_gen', 'is_invoice', 'is_sf_picc', 'is_sf_location', 'is_jc_free', 'is_sc_free', 'is_car_wash', 'is_navigation', 'is_video', 'is_photo', 'is_picc', 'is_contant_class', 'is_v_car', 'is_df', 'is_scsx', 'is_km', 'is_cc', 'is_tc', 'is_jch', 'created_at', 'updated_at', 'status'], 'integer'],
            [['remark'], 'string', 'max' => 255],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['c_id' => 'c_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'b_id' => 'B ID',
            'c_id' => 'C ID',
            'r_id' => 'R ID',
            'is_sms' => 'Is Sms',
            'is_sms_' => 'Is Sms',
            'is_gps' => 'Is Gps',
            'is_time' => 'Is Time',
            'is_invoice_gen' => 'Is Invoice Gen',
            'is_invoice' => 'Is Invoice',
            'is_sf_picc' => 'Is Sf Picc',
            'is_sf_location' => 'Is Sf Location',
            'is_jc_free' => 'Is Jc Free',
            'is_sc_free' => 'Is Sc Free',
            'is_car_wash' => 'Is Car Wash',
            'is_navigation' => 'Is Navigation',
            'is_video' => 'Is Video',
            'is_photo' => 'Is Photo',
            'is_picc' => 'Is Picc',
            'is_contant_class' => 'Is Contant Class',
            'is_v_car' => 'Is V Car',
            'is_df' => 'Is Df',
            'is_scsx' => 'Is Scsx',
            'is_km' => 'Is Km',
            'is_cc' => 'Is Cc',
            'is_tc' => 'Is Tc',
            'is_jch' => 'Is Jch',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => '1 有用
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
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['b_id' => 'b_id']);
    }

    /**
     * @inheritdoc
     * @return BiddingRulesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BiddingRulesQuery(get_called_class());
    }
}
