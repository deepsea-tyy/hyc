<?php
namespace mdm\admin\models\form;

use Yii;
use mdm\admin\models\User;
use mdm\admin\models\Assignment;

/**
 * Signup form
 */
class UpdateUser extends CreateUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This email address has already been taken.'],

            // ['password', 'required'],
            // ['password', 'string', 'min' => 6],
            ['u_type', 'number'],

            [['status', 'u_type', 'c_id', 'task', 'deals', 'fails', 'revoke_times', 'money'], 'integer'],
            [['remark'], 'string', 'max' => 255],
            [['mobile', 'wechar_id', 'blog_id', 'qq_id', 'reg_area', 'location', 'money_crc'], 'string', 'max' => 50]
        ];
    }
}
