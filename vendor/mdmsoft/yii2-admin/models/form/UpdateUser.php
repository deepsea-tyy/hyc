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

    public $password;
    public $role;

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

    /**
     * update user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function userUpdate()
    {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->id = $this->id;
            $user->status = $this->status;
            $user->u_type = $this->u_type;
            $user->c_id = $this->c_id;
            $user->mobile = $this->mobile;
            $user->wechar_id = $this->wechar_id;
            $user->blog_id = $this->blog_id;
            $user->qq_id = $this->qq_id;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                $model = new Assignment($this->id);
                $role = $this->role ? $this->role : 'editor';
                $success = $model->assign([$role]);
                return $user;
            }
        }

        return null;
    }
}
