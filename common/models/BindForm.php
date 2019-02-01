<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class BindForm extends Model
{
    public $uid;
    public $sid;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['uid', 'sid'], 'required'],

            // [['uid', 'sid'], 'validateBind'],
        ];
    }

    public function validateBind($attribute, $params)
    {
        return true;
    }
}
