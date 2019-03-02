<?php

namespace common\models\applet;

use Yii;

/**
 * This is the model class for table "{{%business}}".
 *
 * @property int $id
 * @property string $btoken
 * @property int $registrant 商户注册人关联manage表id
 * @property int $applet 0未接入1已接入
 * @property string $access_token 后台接口调用凭据
 * @property int $expires_in 后台接口调用凭据有效时间
 */
class Business extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%business}}';
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
            [['registrant', 'applet', 'expires_in'], 'integer'],
            [['btoken', 'access_token'], 'string', 'max' => 255],
            [['btoken'], 'unique'],
            [['registrant'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'btoken' => 'Btoken',
            'registrant' => 'Registrant',
            'applet' => 'Applet',
            'access_token' => 'Access Token',
            'expires_in' => 'Expires In',
        ];
    }

    /**
     * {@inheritdoc}
     * @return BusinessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BusinessQuery(get_called_class());
    }
}
