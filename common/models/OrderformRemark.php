<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%orderform_remark}}".
 *
 * @property integer $r_id
 * @property integer $r_pid
 * @property integer $o_id
 * @property integer $f_id
 * @property string $r_title
 * @property string $r_content
 * @property string $r_photos
 * @property integer $created_by
 * @property integer $createdd_at
 *
 * @property File $f
 * @property Orderform $o
 */
class OrderformRemark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%orderform_remark}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['r_id', 'createdd_at'], 'required'],
            [['r_id', 'r_pid', 'o_id', 'f_id', 'created_by', 'createdd_at'], 'integer'],
            [['r_title', 'r_photos'], 'string', 'max' => 100],
            [['r_content'], 'string', 'max' => 255],
            [['f_id'], 'exist', 'skipOnError' => true, 'targetClass' => File::className(), 'targetAttribute' => ['f_id' => 'f_id']],
            [['o_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orderform::className(), 'targetAttribute' => ['o_id' => 'o_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_id' => 'R ID',
            'r_pid' => 'R Pid',
            'o_id' => 'O ID',
            'f_id' => 'F ID',
            'r_title' => 'R Title',
            'r_content' => 'R Content',
            'r_photos' => 'R Photos',
            'created_by' => 'Created By',
            'createdd_at' => 'Createdd At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getF()
    {
        return $this->hasOne(File::className(), ['f_id' => 'f_id']);
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
     * @return OrderformRemarkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderformRemarkQuery(get_called_class());
    }
}
