<?php

namespace common\components\web;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\helpers\Url;

class UrlManager extends \yii\web\UrlManager
{
	public function createUrl($params){
		$url = parent::createUrl($params);
		return Yii::$app->request->encrypt3DES($url);
	}

    public function createAbsoluteUrl($params, $scheme = null){
		$url = parent::createAbsoluteUrl($params, $scheme);
		return Yii::$app->request->encrypt3DES($url);
    }
}