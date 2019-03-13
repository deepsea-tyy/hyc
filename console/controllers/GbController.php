<?php

namespace console\controllers;

use serhatozles\simplehtmldom\SimpleHTMLDom;
use yii\httpclient\Client;

/**
 * 国标查询爬虫
 */
class GbController extends BaseController
{
    public function actionIndex()
    {
    	$num = 10;
    	$page = 1;
    	$p1 = 1;//1 强制标准 2 推荐标准
		$url = 'http://www.gb688.cn/bzgk/gb/std_list_type?page='. $page .'&pageSize='. $num .'&p.p1='. $p1 .'&p.p90=circulation_date&p.p91=desc';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();
        if ($response->isOk) $html = (string)$response->getData(true);

		$html_dom =  SimpleHTMLDom::str_get_html($html);
		// $val = $html['result']['list'];
		$data = [];
		for ($i=1; $i <= $num; $i++) {
			$tr = $html_dom->find('.table-responsive tbody',0)->find('tr',$i);

			$pattern = "#'(.*?)'#i"; 
			preg_match_all($pattern, trim($tr->find('td a',0)->onclick) , $matches); 
			$data[$i]['code'] 			 	 = $matches[1][0];

			$data[$i]['number'] 			 = trim($tr->find('td',1)->plaintext);
			$data[$i]['sampling'] 			 = trim($tr->find('td',2)->plaintext);
			$data[$i]['name'] 				 = trim($tr->find('td',3)->plaintext);
			// $data[$i]['status'] 			 = trim($tr->find('td',4)->plaintext);
			$data[$i]['issue_time'] 		 = trim($tr->find('td',5)->plaintext);
			$data[$i]['implementation_time'] = trim($tr->find('td',6)->plaintext);
		}
		print_r($data);
exit();
		if ($data) {
			$key = array_column($val, 'SpecId');
			$sResult2 = array_combine($s, $sResult);
			$price = array_combine($key, $val);

			foreach ($key as $k) {
				$row = $sResult2[$k];
				$row->price = ($price[$k]['MinOriginalPrice'] / 10000) . ' 万';
				$row->offer = ($price[$k]['MinPrice'] / 10000) . ' 万';
				$row->updated_at = time();
				$row->update();
				// var_dump($row->update());
				// $row->errors;
			}
		}

		//更新设置
		$max = CarSeriesStyling::find()->max('id');
		if (end($sResult)->id>=$max) {
			$config['id'] = 0;
		}else{
			$config['id'] = end($sResult)->id;
		}
		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();
    }

}
