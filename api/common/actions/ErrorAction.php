<?php
namespace api\common\actions;

use Yii;

/**
 * 错误控制器
 */
class ErrorAction extends \yii\web\ErrorAction
{
	/*public function run()
	{
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
		if ($exception instanceof UserException) {
            return $exception->getMessage();
        }

        return 'token无效';
	}*/


    protected function renderHtmlResponse()
    {
        return $this->controller->asJson($this->getViewRenderParams());
        // return $this->controller->render($this->view ?: $this->id, $this->getViewRenderParams());
    }
}