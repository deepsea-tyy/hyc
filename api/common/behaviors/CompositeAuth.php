<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\common\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\filters\auth\AuthInterface;
use mdm\admin\components\Helper;

class CompositeAuth extends \yii\filters\auth\CompositeAuth
{
    public function authenticate($user, $request, $response)
    {
        foreach ($this->authMethods as $i => $auth) {
            if (!$auth instanceof AuthInterface) {
                $this->authMethods[$i] = $auth = Yii::createObject($auth);
                if (!$auth instanceof AuthInterface) {
                    throw new InvalidConfigException(get_class($auth) . ' must implement yii\filters\auth\AuthInterface');
                }
            }

            $identity = $auth->authenticate($user, $request, $response);
            if ($identity !== null) {
                $this->owner->user = $identity;
                $this->user = $identity;
                return $identity;
            }
        }

        return null;
    }


    public function beforeAction($action)
    {
        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return true;
        }
        
        if (parent::beforeAction($action)) {
            // rbac 权限认证
            $actionId = $action->getUniqueId();
            if (Helper::checkRoute('/' . $actionId, Yii::$app->getRequest()->get())) {
                return true;
            }
            $action->controller->send($action->controller->fail('没有权限','/' . $actionId));
            return false;
        }
    }
}
