<?php

namespace api\controllers;

use Yii;
use common\models\system\GbIcs;
use common\models\system\Gb;
use yii\data\Pagination;

/**
 * 国标接口
 */
class GbController extends Api
{

    public function actionGetics()
    {
    	$pid = Yii::$app->request->post('parentid',0);
    	$list = GbIcs::find()->select(['id','name','num','leaf'])->where(['parent'=>$pid])->all();
    	return $this->success($list);
    }

    public function actionGetgb()
    {
    	$icsid = Yii::$app->request->post('icsid',0);
    	$name = Yii::$app->request->post('name',0);
    	$page = (int)Yii::$app->request->post('page');
    	$query = Gb::find()->select(['id','name','gb_number','status','code'])->where(['ics_id'=>$icsid]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(),'page'=>$page?($page-1):0/*,'pageSize'=>$limit?$limit:10*/]);
        if ($name) {
        	$query->andFilterWhere(['like', 'name', $name]);
        }
	    $list = $query->offset($pages->offset)
	      ->limit($pages->limit)
	      ->asArray()
	      ->all();
    	foreach ($list as &$v) {
    		$v['pdf_url'] = 'http://c.gb688.cn/bzgk/gb/showGb?type=online&hcno=' . $v['code'];
    	}
      	return $this->success(['list'=>$list,'pageCount'=>$pages->pageCount,'currentPage'=>$pages->page+1]);
    }

}
