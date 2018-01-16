<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%review_log}}".
 *
 * @property integer $c_id
 * @property integer $id
 * @property integer $review_result
 * @property string $review_title
 * @property integer $review_time
 * @property string $review_content
 * @property integer $status
 *
 * @property User $id0
 * @property Company $c
 */
class ReviewLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_id'], 'required'],
            [['c_id', 'id', 'review_result', 'review_time', 'status'], 'integer'],
            [['review_title'], 'string', 'max' => 50],
            [['review_content'], 'string', 'max' => 255],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id' => 'id']],
            [['c_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['c_id' => 'c_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'id' => 'ID',
            'review_result' => '0 审核不能过
            1 待审核
            2 审核通过
            ',
            'review_title' => 'Review Title',
            'review_time' => 'Review Time',
            'review_content' => 'Review Content',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
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
     * @return ReviewLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReviewLogQuery(get_called_class());
    }
}
