<?php
namespace frontend\modules\v1\controllers;
use Yii;
use yii\web\Controller;

/**
 * 接口文档
 */
class ApiController extends Controller
{
	public function actionIndex()
	{
	    $openapi = \OpenApi\scan(Yii::getAlias('@frontend/modules/v1'));
	    echo $openapi->toJson();
	    exit();
	}
}