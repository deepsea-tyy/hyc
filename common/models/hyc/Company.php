<?php

namespace common\models\hyc;

use Yii;

/**
 * This is the model class for table "hyc_company".
 *
 * @property int $c_id
 * @property int $codeid
 * @property string $code_city
 * @property int $pid
 * @property string $c_name
 * @property string $c_website
 * @property string $c_logo
 * @property string $c_add
 * @property string $c_gps ARRAY             1百度             2高德             3腾讯                          或只存百度 支持百度选点
 * @property string $c_area 起运地址同注册公司所在地？
 * @property string $reg_ip
 * @property int $reg_time 用第一次注册时间变成审核日志的ID
 * @property int $c_level 1初级等级
 * @property int $review 0 审核不能过             1 待审核             2 审核通过             
 * @property int $review_user
 * @property int $review_log_id
 * @property int $review_time
 * @property int $zdjj_id
 * @property int $zdjj_ks
 * @property int $zdjj_js
 * @property int $zdjj_mod
 * @property string $invoice_name
 * @property string $invoice_taxcode
 * @property string $invoice_add
 * @property string $invoice_tel
 * @property string $invoice_bank
 * @property string $invoice_bank_account
 * @property int $cj
 * @property int $fw
 * @property int $hp
 * @property int $cp
 * @property int $xl
 * @property string $xlr
 * @property string $xldh
 * @property string $remark
 * @property int $is_bidding
 * @property int $bidding 根据等级和资料完成比算
 * @property int $bidding_num
 * @property int $bidding_back
 * @property int $bidding_modify
 * @property int $created_at
 * @property int $updated_at
 * @property int $status 1 有用             0 删除的
 *
 * @property BiddingRules[] $biddingRules
 * @property CompanyType[] $companyTypes
 * @property Offer[] $offers
 * @property Shortrange[] $shortranges
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hyc_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['c_id', 'created_at', 'updated_at'], 'required'],
            [['c_id', 'codeid', 'pid', 'reg_time', 'c_level', 'review', 'review_user', 'review_log_id', 'review_time', 'zdjj_id', 'zdjj_ks', 'zdjj_js', 'zdjj_mod', 'cj', 'fw', 'hp', 'cp', 'xl', 'is_bidding', 'bidding', 'bidding_num', 'bidding_back', 'bidding_modify', 'created_at', 'updated_at', 'status'], 'integer'],
            [['code_city', 'c_gps', 'c_area', 'reg_ip', 'invoice_name', 'invoice_taxcode', 'invoice_add', 'invoice_tel', 'invoice_bank', 'invoice_bank_account', 'xlr', 'xldh'], 'string', 'max' => 50],
            [['c_name', 'c_website', 'c_logo', 'c_add', 'remark'], 'string', 'max' => 255],
            [['c_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'codeid' => 'Codeid',
            'code_city' => 'Code City',
            'pid' => 'Pid',
            'c_name' => 'C Name',
            'c_website' => 'C Website',
            'c_logo' => 'C Logo',
            'c_add' => 'C Add',
            'c_gps' => 'C Gps',
            'c_area' => 'C Area',
            'reg_ip' => 'Reg Ip',
            'reg_time' => 'Reg Time',
            'c_level' => 'C Level',
            'review' => 'Review',
            'review_user' => 'Review User',
            'review_log_id' => 'Review Log ID',
            'review_time' => 'Review Time',
            'zdjj_id' => 'Zdjj ID',
            'zdjj_ks' => 'Zdjj Ks',
            'zdjj_js' => 'Zdjj Js',
            'zdjj_mod' => 'Zdjj Mod',
            'invoice_name' => 'Invoice Name',
            'invoice_taxcode' => 'Invoice Taxcode',
            'invoice_add' => 'Invoice Add',
            'invoice_tel' => 'Invoice Tel',
            'invoice_bank' => 'Invoice Bank',
            'invoice_bank_account' => 'Invoice Bank Account',
            'cj' => 'Cj',
            'fw' => 'Fw',
            'hp' => 'Hp',
            'cp' => 'Cp',
            'xl' => 'Xl',
            'xlr' => 'Xlr',
            'xldh' => 'Xldh',
            'remark' => 'Remark',
            'is_bidding' => 'Is Bidding',
            'bidding' => 'Bidding',
            'bidding_num' => 'Bidding Num',
            'bidding_back' => 'Bidding Back',
            'bidding_modify' => 'Bidding Modify',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBiddingRules()
    {
        return $this->hasMany(BiddingRules::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTypes()
    {
        return $this->hasMany(CompanyType::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['c_id' => 'c_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShortranges()
    {
        return $this->hasMany(Shortrange::className(), ['c_id' => 'c_id']);
    }

    /**
     * {@inheritdoc}
     * @return CompanyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyQuery(get_called_class());
    }
}
