<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%company_type}}".
 *
 * @property integer $t_id
 * @property integer $c_id
 * @property string $t_name
 * @property string $t_name_py
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $remark
 * @property integer $status
 *
 * @property Company $c
 */
class CompanyType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['t_id', 'created_at', 'updated_at'], 'required'],
            [['t_id', 'c_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['t_name', 't_name_py', 'remark'], 'string', 'max' => 255],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['c_id' => 'c_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            't_id' => 'T ID',
            'c_id' => 'C ID',
            't_name' => 'T Name',
            't_name_py' => 'T Name Py',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remark' => 'Remark',
            'status' => '1 可用
            0 禁用',
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
     * @return CompanyTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CompanyTypeQuery(get_called_class());
    }
}
