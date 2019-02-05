<?php
namespace api\common\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use GatewayClient\Gateway;
use common\models\BindForm;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

/**
 * Site controller
 */
class BaseController extends Controller
{
    public $enableCsrfValidation = false;
    public $user = null;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                // HttpBasicAuth::className(),
                // HttpBearerAuth::className(),
                QueryParamAuth::className(),
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'api\common\actions\ErrorAction',
            ],
        ];
    }

    public function init()
    {
        Gateway::$registerAddress = Yii::$app->params['workerConfig']['registerAddress'];
    }

    public function success($data=[],$message='操作成功')
    {
        return ['status'=>1,'data'=>$data,'message'=>$message];
    }


    public function fail($data=[],$message='操作失败')
    {
        return ['status'=>0,'data'=>$data,'message'=>$message];
    }


    public function actionBind()
    {
        $model = new BindForm();

        if ($model->load(Yii::$app->request->post(),'') && $model->validate()) {

            // 假设用户已经登录，用户uid和群组id在session中
            $uid      = $model->uid;
            $client_id = $model->sid;
            $group_id = 1;

            // client_id与uid绑定
            Gateway::bindUid($client_id, $uid);
            // 加入某个群组（可调用多次加入多个群组）
            // Gateway::joinGroup($client_id, $group_id);

            return $this->success($model,'绑定成功');
        }
        return $this->fail($model->errors);
    }




}
