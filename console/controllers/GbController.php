<?php

namespace console\controllers;

use Yii;
use serhatozles\simplehtmldom\SimpleHTMLDom;
use yii\httpclient\Client;
use common\models\system\Gb;
use common\models\system\GbIcs;
use common\models\system\SystemConfig;

/**
 * 国标查询爬虫
 * pdf预览 http://c.gb688.cn/bzgk/gb/showGb?type=online&hcno={$code}
 * pdf下载 http://c.gb688.cn/bzgk/gb/showGb?type=download&hcno={$code}
 */
class GbController extends BaseController
{
    public function actionGb()
    {
    	$config = SystemConfig::find()->where(['name'=>'GB_CONTENT'])->one();
    	$row = json_decode($config->content,true);
		$res = GbIcs::find()->where('id>'.$row['id'])->andWhere('leaf=1')->limit($row['limit'])->all();
		$pageSize = 50;
		foreach ($res as $v) {
			$pages = ceil($v->num/$pageSize);
			$data = [];
			for ($i=1; $i <= $pages; $i++) { 
    			$data = array_merge($data,$this->getContent($v->id,1,$i,$pageSize));
				
			}
			// break;
			for ($i=1; $i <= $pages; $i++) { 
    			$data = array_merge($data,$this->getContent($v->id,2,$i,$pageSize));
			}
		}

		if (empty($res)) {
			$config->content = json_encode(['id'=>0,'limit'=>$row['limit']]);
		}else{
			$last = end($res);
			$config->content = json_encode(['id'=>$last->id,'limit'=>$row['limit']]);
		}

		foreach ($data as $k => $v) {
			$row = Gb::find()->where(['gb_number' => $v['gb_number']])->one();
			if ($row) {
				$row->gb_number  = 	$v['gb_number'];
				$row->code 	 	 = 	$v['code'];
				$row->sampling   = 	$v['sampling'];
				$row->name 	 	 = 	$v['name'];
				$row->status 	 = 	$v['status'];
				$row->type 	 	 = 	$v['type'];
				$row->updated_at = 	time();
				$row->save();
				unset($data[$k]);
			}
		}
		$config->save();
		Yii::$app->db->createCommand()
       	->batchInsert(Gb::tableName(),['code','gb_number','sampling','name','status','issue_time','implementation_time','type','ics_id','created_at'],$data)
       	->execute();
    }

    /**
     * 国标详情
     */
    public function actionGbdetal()
    {
    	$config = SystemConfig::find()->where(['name'=>'GB_CONTENT_DETAIL'])->one();
    	$row = json_decode($config->content,true);
		$res = Gb::find()->where('id>'.$row['id'])->limit($row['limit'])->all();
        $client = new Client();
		foreach ($res as $v) {
			$url = 'http://www.gb688.cn/bzgk/gb/newGbInfo?hcno='.$v['code'];
	    	$response = $client->createRequest()
	            ->setMethod('GET')
	            ->setUrl($url)
	            ->send();
	        if ($response->isOk) $html = (string)$response->getData(true);
			$html_dom =  SimpleHTMLDom::str_get_html($html);
			$online = $html_dom->find('.tdlist',0)->find('tr',-1)->find('span',0);
			if (empty($online)) $online = ''; else $online = trim($online->innertext);
			// print_r($online);
			// exit();
			$node = $html_dom->find('.tdlist',0)->nextSibling()->nextSibling();
			$v->ccs 		= trim($node->find('div',1)->plaintext);
			$v->department 	= trim($node->nextSibling()->nextSibling()->find('div',1)->plaintext);
			$v->unit 		= trim($node->nextSibling()->nextSibling()->find('div',3)->plaintext);
			$v->issue_unit 	= trim($node->nextSibling()->nextSibling()->nextSibling()->find('div',1)->plaintext);
			$v->remake 		= trim($node->nextSibling()->nextSibling()->nextSibling()->nextSibling()->find('div',1)->plaintext);
			$v->online 		= $online;
			$v->save(false);
		}
    	
		if (empty($res)) {
			$config->content = json_encode(['id'=>0,'limit'=>$row['limit']]);
		}else{
			$last = end($res);
			$config->content = json_encode(['id'=>$last->id,'limit'=>$row['limit']]);
		}
		$config->save();
    }

	/**
	 * 采集国标内容
	 * $type 1 强制标准 2 推荐标准
	 */
    public function getContent($ics_id,$type=1,$page=1,$pageSize=25)
    {
		$url = "http://www.gb688.cn/bzgk/gb/std_list_type?page={$page}&pageSize={$pageSize}&p.p1={$type}&p.p90=circulation_date&p.p91=desc";
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();
        if ($response->isOk) $html = (string)$response->getData(true);

		$html_dom =  SimpleHTMLDom::str_get_html($html);
		$data = [];
		$created_at = time();
		for ($i=1; $i <= $pageSize; $i++) {
			$tr = $html_dom->find('.table-responsive tbody',0)->find('tr',$i);
			if (empty($tr)) continue;
			$pattern = "#'(.*?)'#i"; 
			preg_match_all($pattern, trim($tr->find('td a',0)->onclick) , $matches); 
			$data[$i]['code'] 			 	 = $matches[1][0];
			$data[$i]['gb_number'] 			 = trim($tr->find('td',1)->plaintext);
			$data[$i]['sampling'] 			 = empty(trim($tr->find('td',2)->plaintext)) ? 0:1;
			$data[$i]['name'] 				 = trim($tr->find('td',3)->plaintext);
			$data[$i]['status'] 			 = trim($tr->find('td',4)->plaintext);
			$data[$i]['issue_time'] 		 = strtotime(trim($tr->find('td',5)->plaintext));
			$data[$i]['implementation_time'] = strtotime(trim($tr->find('td',6)->plaintext));
			$data[$i]['type'] 				 = $type;
			$data[$i]['ics_id'] 			 = $ics_id;
			$data[$i]['created_at'] 			 = $created_at;
		}
		return $data;
    }

    /**
     * 采集ICS分类数据
     */
    public function actionIcs()
    {
    	$config = SystemConfig::find()->where(['name'=>'GB_ICS'])->one();
    	$row = json_decode($config->content,true);
    	$deep = $row['deep'];
    	$data = [];
    	if ($deep == 0) {
    		$res = GbIcs::find()->where(['parent'=>0])->all();
    		$code = array_column($res, 'code');

	    	$query = ['pcode' => -1, 'p.p1' => 0];
	    	$data = $this->getIcs($query);
	    	$k = array_column($data, 'code');
	    	$i = array_intersect($code, $k);
	    	$data = array_combine($k, $data);
	    	foreach ($res as $v) {
	    		if (in_array($v->code, $i)) {
	    			$v->updated_at = time();
	    			$v->name = $data[$v->code]['name'];
	    			$v->num = $data[$v->code]['num'];
	    			$v->save();
	    			unset($data[$v->code]);
	    		}
	    	}
    	}

    	if ($deep == 1) {
    		$res = GbIcs::find()->where('id>'.$row['id'])->limit($row['limit'])->all();
    		foreach ($res as $v) {
    			if ($v['leaf'] == 0) {
    				$query = ['pcode' => $v['code'], 'p.p1' => 0];
	    			$data = array_merge($data, $this->getIcs($query,$v['id']));
			    	$k = array_column($data, 'code');
			    	$data = array_combine($k, $data);
		    		$rows = GbIcs::find()->where(['code'=>$k])->all();
		    		$code = array_column($rows, 'code');
		    		// print_r($rows);exit();
			    	$i = array_intersect($code, $k);
		    		foreach ($rows as $v) {
		    		if (in_array($v->code, $i)) {
			    			$v->updated_at = time();
			    			$v->name = $data[$v->code]['name'];
			    			$v->num = $data[$v->code]['num'];
			    			$v->save();
			    			unset($data[$v->code]);
			    		}
			    	}

    			}
    			
    		}
    	}

		if ($deep == 0) {
			$config->content = json_encode(['deep'=>1,'id'=>0,'limit'=>$row['limit']]);
			$config->save();
		}

		if ($deep == 1) {
			if (empty($res)) {
				$config->content = json_encode(['deep'=>0,'id'=>0,'limit'=>$row['limit']]);
			}else{
				$last = end($res);
				$config->content = json_encode(['deep'=>1,'id'=>$last->id,'limit'=>$row['limit']]);
			}
			$config->save();
		}
		Yii::$app->db->createCommand()
       	->batchInsert(GbIcs::tableName(),['name','code','num','leaf','parent','created_at'],$data)
       	->execute();
    }

    public function getIcs($query,$parent=0)
    {
    	
		$url = 'http://www.gb688.cn/bzgk/gb/ajaxIcsList';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($url)
            ->setData($query)
            ->send();
		$data = [];
        if ($response->isOk) {
        	$res = $response->data;
        	if (empty($res)) return;
    		$created_at = time();
        	foreach ($res as $v) {
        		$row['name'] = $v['icsName'];
        		$row['code'] = $v['icsCode'];
        		$row['num'] = (int)$v['count'];
        		$row['leaf'] = $v['leaf'];
        		$row['parent'] = $parent;
        		$row['created_at'] = $created_at;
        		$data[] = $row;
        	}
        }
    	return $data;
    }






}
