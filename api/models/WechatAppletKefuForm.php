<?php

namespace api\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class WechatAppletKefuForm extends Model
{
    public $touser;
    public $content;
    public $type;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['touser', 'content', 'type', 's_uid'], 'required'],
        ];
    }
}
