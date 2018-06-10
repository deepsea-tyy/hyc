<?php
namespace mdm\admin\models\form;

use Yii;
use mdm\admin\models\User;
use yii\db\ActiveRecord;
use mdm\admin\models\Assignment;
use mdm\admin\components\Configs;

/**
 * Signup form
 */
class CreateUser extends ActiveRecord
{

    public $password;

     /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Configs::instance()->userTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'mdm\admin\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['u_type', 'number'],

            [['status', 'u_type', 'c_id', 'task', 'deals', 'fails', 'revoke_times', 'money'], 'integer'],
            [['remark'], 'string', 'max' => 255],
            [['mobile', 'wechar_id', 'blog_id', 'qq_id', 'reg_area', 'location', 'money_crc'], 'string', 'max' => 50]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '邮箱',
            'password' => '密码',

            'status' => '1可用0用户注销',
            'u_type' => '0 个人客户 1 注册物流公司人员',
            'c_id' => 'C ID',
            'mobile' => '手机号',
            'wechar_id' => 'Wechar ID',
            'blog_id' => 'Blog ID',
            'qq_id' => 'Qq ID',
            'last_login_area' => '登录区域',
            'reg_area' => '登录区域',
            'location' => '运营商',
            'login_count' => 'Login Count',
            'task' => 'Task',
            'deals' => 'Deals',
            'fails' => 'Fails',
            'revoke_times' => 'Revoke Times',
            'money' => '单位分',
            'money_crc' => 'Money Crc',
            'remark' => 'Remark',
            'role' => '角色',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
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
                $role = Configs::DEFAULT_ROLE;
                $success = $model->assign([$role]);
                return $user;
            }
        }

        return null;
    }
}
