<?php

namespace common\models\applet;

use Yii;

/**
 * This is the model class for table "{{%user_token}}".
 *
 * @property string $token
 * @property int $user_id
 * @property int $platform 平台类型，1就是默认，2就是微信小程序
 * @property string $ctime
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_token}}';
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
            [['token', 'user_id', 'ctime'], 'required'],
            [['user_id', 'platform', 'ctime'], 'integer'],
            [['token'], 'string', 'max' => 32],
            [['token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'user_id' => 'User ID',
            'platform' => 'Platform',
            'ctime' => 'Ctime',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserTokenQuery(get_called_class());
    }
}
