<?php
namespace common\components\actions;

use yii\base\Action;

/**
* 上传
*/
class upload extends Action
{
	
	public function run()
    {
    	echo '{"code": 0, "url": "http://g.hiphotos.baidu.com/image/pic/item/c8ea15ce36d3d539f09733493187e950342ab095.jpg", "attachment": "图片地址"}';
    	// echo '{"code": 1, "msg": "error"}';
    }

}