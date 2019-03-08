<?php

namespace common\models\applet;

use Yii;

/**
 * This is the model class for table "{{%weixin_applet_message}}".
 *
 * @property int $id
 * @property int $type 1文本2图片3卡片
 * @property string $content
 * @property string $touser openid
 * @property int $status 0位回复
 * @property int $pid 回复id
 * @property int $bid 商户id
 * @property int $ctime
 */
class WeixinAppletMessage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%weixin_applet_message}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_applet');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'bid', 'ctime'], 'integer'],
            [['content'], 'string'],
            [['ctime','fromuser','touser'], 'required'],
            // [[''], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'content' => 'Content',
            'touser' => 'Touser',
            'fromuser' => 'Fromuser',
            'status' => 'Status',
            'bid' => 'Bid',
            'ctime' => 'Ctime',
        ];
    }

    /**
     * {@inheritdoc}
     * @return WeixinAppletMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WeixinAppletMessageQuery(get_called_class());
    }
}
