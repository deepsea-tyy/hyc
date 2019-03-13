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

/**
 * CompositeAuth is an action filter that supports multiple authentication methods at the same time.
 *
 * The authentication methods contained by CompositeAuth are configured via [[authMethods]],
 * which is a list of supported authentication class configurations.
 *
 * The following example shows how to support three authentication methods:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'compositeAuth' => [
 *             'class' => \yii\filters\auth\CompositeAuth::className(),
 *             'authMethods' => [
 *                 \yii\filters\auth\HttpBasicAuth::className(),
 *                 \yii\filters\auth\QueryParamAuth::className(),
 *             ],
 *         ],
 *     ];
 * }
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
                return $identity;
            }
        }

        return null;
    }
}
