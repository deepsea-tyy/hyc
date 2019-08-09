<?php

namespace console\controllers;

use Yii;
use common\helpers\Tools;
use console\models\redis\Rdemo;
use console\jobs\Aa;

/**
 * 测试
 */
class TestController extends BaseController
{
	public function actionIndex()
	{
		Tools::srequest('0.0.0.0:18307','user::getList',['id'=>1,'type'=>2]);
	}


	public function actionRedis()
	{
		// echo Yii::$app->redis->rpush('list1','test1');
		// echo Yii::$app->redis->lpop('list1');
		// $mod = new Rdemo();
		// $mod->attributes = ['msg' => 'test'];
		// $mod->save();
		// echo $mod->id; // 如果没有明确设置 id 会自动递增
		$a= Yii::$app->queue->push(new Aa([
			'p1'=>1,
			'p2'=>2,
			'p3'=>3,
		]));
		var_dump($a);
	}
}