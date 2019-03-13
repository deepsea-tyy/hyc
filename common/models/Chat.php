<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property int $id
 * @property int $bid 商户id
 * @property int $type 1文本2图片3卡片
 * @property int $status 0未读1已读
 * @property string $fromuser id
 * @property string $touser id
 * @property string $content
 * @property int $platform 0浏览器;1微信
 * @property int $created_at
 * @property int $updated_at
 */
class Chat extends \yii\db\ActiveRecord
{
    const SCENARIO_SEND_MSG = 1;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEND_MSG] = ['content', 'type', 'touser'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'platform', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['fromuser', 'touser'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bid' => 'Bid',
            'type' => 'Type',
            'status' => 'Status',
            'fromuser' => 'Fromuser',
            'touser' => 'Touser',
            'content' => 'Content',
            'platform' => 'Platform',
            'created_at' => 'Create At',
            'updated_at' => 'Update At',
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChatQuery(get_called_class());
    }
}
