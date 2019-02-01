<?php
namespace api\common\actions;
use Yii;
use yii\base\Action;

/**
 * 错误控制器
 */
class ErrorAction extends Action
{
	public function run()
	{
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
		if ($exception instanceof UserException) {
            return $exception->getMessage();
        }

        return 'token无效';
	}
}