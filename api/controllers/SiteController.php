<?php
namespace api\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use api\common\controllers\BaseController;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\PasswordResetRequestForm;
use api\models\ResetPasswordForm;
use api\models\SignupForm;
use api\models\ContactForm;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    public $layout = false;
    public function behaviors(){
        return [];
    }
    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['captcha'] = [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ];
        return $actions;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        if ($model->load(Yii::$app->request->post(),'') &&  $user = $model->apiLogin()) {
            return $this->success(['token'=>$user->access_token]);
        } else {
            return $this->fail($model->errors);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->success();
    }

}
