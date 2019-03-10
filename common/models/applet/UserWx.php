<?php

namespace common\models\applet;

use Yii;

/**
 * This is the model class for table "{{%user_wx}}".
 *
 * @property int $id 用户ID
 * @property int $type 第三方登录类型，1微信小程序，2微信公众号
 * @property int $user_id 关联用户表
 * @property string $openid
 * @property string $session_key
 * @property string $unionid
 * @property string $avatar 头像
 * @property string $nickname 昵称
 * @property int $gender 性别 0：未知、1：男、2：女
 * @property string $language 语言
 * @property string $city 城市
 * @property string $province 省
 * @property string $country 国家
 * @property string $country_code 手机号码国家编码
 * @property string $mobile 手机号码
 * @property string $ctime
 * @property string $utime
 */
class UserWx extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_wx}}';
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
            [['type', 'user_id', 'gender', 'ctime', 'utime'], 'integer'],
            [['openid', 'session_key', 'unionid', 'nickname', 'language'], 'string', 'max' => 50],
            [['avatar'], 'string', 'max' => 255],
            [['city', 'province', 'country'], 'string', 'max' => 80],
            [['country_code', 'mobile'], 'string', 'max' => 20],
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
            'user_id' => 'User ID',
            'openid' => 'Openid',
            'session_key' => 'Session Key',
            'unionid' => 'Unionid',
            'avatar' => 'Avatar',
            'nickname' => 'Nickname',
            'gender' => 'Gender',
            'language' => 'Language',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'country_code' => 'Country Code',
            'mobile' => 'Mobile',
            'ctime' => 'Ctime',
            'utime' => 'Utime',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserWxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserWxQuery(get_called_class());
    }
}
