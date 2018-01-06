<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use serhatozles\simplehtmldom\SimpleHTMLDom;
use common\models\car\CarBrand;
use common\models\car\CarSeries;
use common\models\car\CarSeriesColor;
use common\models\car\CarSeriesStyling;
use common\models\car\CarConfig;
use common\models\Area;
use common\models\LinePrice;
use common\models\SystemConfig;

/**
 * TestController implements the CRUD actions for Test model.
 */
class QcController extends Controller
{
	public function actionIndex()
	{
		echo 'index';
	}

	public function actionSpider($n=0)
	{
		$spider = ['actionError','spider1','spider2','spider3','spider4','spider5'];
		$s = $spider[$n];
		$this->$s();
	}

	public function actionError()
	{
		echo 'error';
	}

	//车型报价，二手价
	public function spider5(){
		// https://dealer.api.autohome.com.cn/dealerrest/price/GetMinPriceBySpecSimple?_appId=cms&cityid=510100&SpecIds=30961,30962,30963,30964,30965 //offer 经销商报价
		
		// https://api.che168.com/auto/ForAutoCarPCInterval.ashx?callback=&_appid=cms&sid=4344&yid=0&pid=140000&cid=140200 //price2Sc 二手价
		
		//https://dealer.api.autohome.com.cn/buyapi/spec/GetSpecMinPriceByCity?_appId=cms&specIds=32114,26278,28382,30110&cityid=110100 //经销商购买价
		
		//https://api.mall.autohome.com.cn/gethomemallad/price/692/140100?_appid=cms  // 车商报价 必须指定区域

		$headerArr = $this->setHeader();

		$referer = 'https://www.autohome.com.cn';

		$data_car_price = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_5'])->one();
		$config = json_decode($configResult->content,true);

		$sResult = CarSeriesStyling::find()/*->select(['id','oid'])*/->where('id>'.$config['id'])->orderBy(['id' => SORT_ASC])->limit($config['limit'])/*->asArray()*/->all();

		$s = \yii\helpers\ArrayHelper::getColumn($sResult, function ($element) {
		    return $element->oid;
		});

		$url = 'https://dealer.api.autohome.com.cn/dealerrest/price/GetMinPriceBySpecSimple?_appId=cms&SpecIds='.implode(',', $s);

		$html =  json_decode( SimpleHTMLDom::str_get_html($this->get_html($url,$headerArr,$referer)) , true);

		$val = $html['result']['list'];

		$key = array_column($val, 'SpecId');
		$b = array_combine($key, $val);

		foreach ($sResult as $row) {
			$row->price = ($b[$row->oid]['MinOriginalPrice'] / 10000) . ' 万';
			$row->offer = ($b[$row->oid]['MinPrice'] / 10000) . ' 万';
			$row->updated_at = time();
			$row->update();
			// var_dump($row->update());
			// $row->errors;
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

	//起运价格
	public function spider4(){
		$headerArr = $this->setHeader();

		$referer = 'www.ehuoyun.com';

		$data_line_price = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_4'])->one();
		$config = json_decode($configResult->content,true);

		$row = Area::find()->select('codeid')->where('codeid>'.$config['starting'])->orderBy(['codeid' => SORT_ASC])->one();
		$sResult = Area::find()->select('codeid')->where('codeid>'.$config['destination'])->orderBy(['codeid' => SORT_ASC])->limit($config['limit'])->all();

		$a = str_pad( $row->codeid, 6, 0, STR_PAD_RIGHT);
		foreach ($sResult as $v) {
			// break;
			$b = str_pad( $v->codeid, 6, 0, STR_PAD_RIGHT);
			$url = 'http://www.ehuoyun.com/rest/quote/calculate/'.$a.'/'.$b;
			$price = json_decode($this->get_html($url,$headerArr,$referer),true);
			if ($price) {
				$data_line_price[] = [
						'starting' => $row->codeid,
						'destination' => $v->codeid,
						'startingshow' => $a,
						'destinationshow' => $b,
						'price' => $price['value'],
						'created_at' => time(),
					];
			}
		}
		

		$max = Area::find()->max('codeid');
		if (end($sResult)->codeid>=$max) {
			$config['destination'] = 0;
			$config['starting'] = $row->codeid;
		}else{
			$config['destination'] = end($sResult)->codeid;
		}
		// var_dump($sResult);
		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();
		// exit();
		Yii::$app->db->createCommand()
       	->batchInsert(LinePrice::tableName(),['starting','destination','startingshow','destinationshow','price','create_at'],$data_line_price)
       	->execute();
	}

	// 车型配置
	public function spider3(){
		$headerArr = $this->setHeader();

		$referer = 'https://www.autohome.com.cn';

		$data_car_config = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_3'])->one();
		$config = json_decode($configResult->content,true);

		$sResult = CarSeries::find()->where('id>'.$config['id'])->orderBy(['id' => SORT_ASC])->limit($config['limit'])->all();

		foreach ($sResult as $row) {//break;
            $url = 'https://car.autohome.com.cn/config/series/'.$row->oid.'.html';

			$html = $this->get_html($url,$headerArr,$referer);
			$meta = SimpleHTMLDom::str_get_html($html)->find('meta[http-equiv=Content-Type]',0)->content;
			if (strpos($meta, 'utf-8')===false) {
				$html = mb_convert_encoding($html, 'utf-8', 'gb2312,gbk');
			}

			$start = strpos($html, 'keyLink');

			$html = mb_substr( $html, $start);
			$end = strpos($html, '</script>');
			$html = mb_substr( $html, 0,$end);

			$data = explode('var', $html);
			array_shift($data);
			$data = array_splice($data, 0,5);
			$arr;

			foreach ($data as $v) {
				$sn = strpos($v, '{');
				if ($sn !== false) {
					$str = mb_substr(trim(trim($v),';'),$sn-1);
					$key = mb_substr(trim(trim($v),';'),0,$sn-1);
					$arr[$key] = $str;
				}
			}
			array_pop($arr);
			// reset($arr);
			$str_config = current($arr);
			$a = json_decode($str_config,true);
			// dump($a['result']['paramtypeitems'][0]['paramitems']);
			// echo json_encode($a['result']['paramtypeitems'][0]['paramitems']);exit;
			$list = $a['result']['paramtypeitems'][0]['paramitems'];//配置列表
			$data_jbcs = array_column($list,'valueitems' , 'name');//基本参数	
			// echo json_encode($data);
			// dump($data_jbcs);
			// exit;

			foreach ($data_jbcs as $key => $v) {
				if (strpos($key, '长')!==false && strpos($key, '宽') !== false ) {
					
					foreach ($v as $v2) {
						$data_car_config[$v2['specid']] = [
							'st_id' => $v2['specid'],
							'ckg' => $v2['value'],
						];
					}
				}
			}

			break;
		}


		$carconfig = array_keys($data_car_config);

		$sResult2 = CarConfig::find()->select(['id','st_id'])->where(['st_id'=>$carconfig])->all();
		
		$s = \yii\helpers\ArrayHelper::getColumn($sResult2, function ($element) {
		    return $element->st_id;
		});

		$sResult2 = array_combine($s, $sResult2);
		$diff = array_diff($carconfig, $s);
		$data_insert_carconfig = [];
		foreach ($carconfig as $v) {
			if (in_array($v, $diff)) {
				$row = $data_car_config[$v];
				$data_insert_carconfig[] = $row; //插入新数据
			}
		}
			// var_dump($data_insert_carconfig);
			// exit;

		//更新设置
		$max = CarSeries::find()->max('id');
		if (end($sResult)->id>=$max) {
			$config['id'] = 0;
		}else{
			$config['id'] = end($sResult)->id;
		}
		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();

		//批量插入
		Yii::$app->db->createCommand()
       	->batchInsert(CarConfig::tableName(),['st_id','ckg'],$data_insert_carconfig)
       	->execute();
	}

	// 系列车型，颜色
    public function spider2() {
		$headerArr = $this->setHeader();

		$referer = 'https://www.autohome.com.cn';

		$data_series_color = $data_series_styling = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_2'])->one();
		$config = json_decode($configResult->content,true);

		$sResult = CarSeries::find()->where('id>'.$config['id'])->orderBy(['id' => SORT_ASC])->limit($config['limit'])->all();

		foreach ($sResult as $row) {//break;
			$url = 'https:' . $row->url;

			$html = SimpleHTMLDom::str_get_html($this->get_html($url,$headerArr,$referer));
			
			// 颜色
			$carColor = $html->find('div[id=carColor]');
			try {
				$div = count($carColor) > 0 ? $carColor[0] : $html->find('.car-color-con',0);
				if ($div) {
			        foreach ($div->find('a') as $a) {
			            $color = trim( str_replace('background: ', '',  $a->find('span',0)->style),';');//color
			            $name =  $a->find('.tip-content',0)->innertext;//品牌名

		            	$data_series_color[$color] = [
		            			'color'=>$color,
		            			'name'=>mb_convert_encoding($name, 'utf-8', 'gb2312,gbk'),
		            			's_id'=>$row->oid
		            		];
			        }
				}
			} catch (Exception $e) {
				
			}


			// 在售
			$speclist20 = $html->find('div[id=speclist20]');
			//即将销售
			// $speclist10 = $html->find('div[id=speclist10]');

			try {
				$div = $speclist20[0];
				if ($div) {
			        $num = count($div->find('.interval01-title'));
			        for ($i=0; $i < $num; $i++) {

			            $group_name = $div->find('.interval01-title',$i)->find('span',0)->innertext;//分组  
			            foreach ($div->find('.interval01-list',$i)->find('li') as $li) {
			                if (!$li->find('a',0)) {
			                    continue;
			                }
			                $oid = str_replace('spec_','', $li->find('p',0)->id);
			                $name = $li->find('a',0)->innertext; //款项
			                $price = trim( $li->find('.interval01-list-guidance div',0)->plaintext);//参考价格

			            	$data_series_styling[$oid] = [
			            			'group_name'=>mb_convert_encoding($group_name, 'utf-8', 'gb2312,gbk'),
			            			'name'=>mb_convert_encoding($name, 'utf-8', 'gb2312,gbk'),
			            			'oid'=>$oid,
			            			'status'=>1,
			            			'price'=> mb_convert_encoding($price, 'utf-8', 'gb2312,gbk'),
			            			'price2Sc'=>'',
			            			's_id'=>$row->oid,
			            		];
			            }
			        }
				}
			// var_dump($data_series_styling);
			// exit;
				
			} catch (Exception $e) {
				
			}
			// break;

			// 停售
			$drop2 = $html->find('div[id=drop2]');
			try {
				$div = $drop2[0];
				if ($div) {
			        $ajaxurl = 'https://www.autohome.com.cn/ashx/series_allspec.ashx?s='.$row->oid.'&l='.rand(1,20).'&y=';
			        foreach ($div->find('li') as $li) {

			            $url2 = $ajaxurl . $li->find('a',0)->data;
			            $str = mb_convert_encoding(file_get_contents($url2), 'utf-8', 'gb2312,gbk');

			            $data = json_decode($str ,true);
			            foreach ($data['Spec'] as $v) {
			            	$data_series_styling[$v['Id']] = [
			            			'group_name'=>$v['GroupName'],
			            			'name'=>$v['Name'],
			            			'oid'=>$v['Id'],
			            			'status'=>0,
			            			'price'=>$v['Price'],
			            			'price2Sc'=>$v['Price2Sc'],
			            			's_id'=>$row->oid
		            			];
			            }
			        }
				}
				
			} catch (Exception $e) {
				
			}

			// var_dump($data_series_styling);
			// exit;
			// break;
		}

		$color = array_keys($data_series_color);

		$sResult2 = CarSeriesColor::find()->select(['id','color'])->where(['s_id'=>$row->oid])->all();
		
		$s = \yii\helpers\ArrayHelper::getColumn($sResult2, function ($element) {
		    return $element->color;
		});

		$sResult2 = array_combine($s, $sResult2);
		$diff = array_diff($color, $s);
		$data_insert_color = [];
		foreach ($color as $v) {
			if (in_array($v, $diff)) {
				$row = $data_series_color[$v];
				$data_insert_color[] = $row; //插入新数据
			}
		}


		$styling = array_keys($data_series_styling);

		$sResult2 = CarSeriesstyling::find()->select(['id','oid'])->where(['oid'=>$styling])->all();
		
		$s = \yii\helpers\ArrayHelper::getColumn($sResult2, function ($element) {
		    return $element->oid;
		});

		$sResult2 = array_combine($s, $sResult2);
		$diff = array_diff($styling, $s);
		$data_insert_styling = [];
		foreach ($styling as $v) {
			if (in_array($v, $diff)) {
				$row = $data_series_styling[$v];
				$row['created_at'] = time();
				$data_insert_styling[] = $row; //插入新数据
			}/*else{
				$row = $sResult2[$v]; //更新数据
				$row->status = $data_series_styling[$v]['status'];
				$row->price = $data_series_styling[$v]['price'];
				$row->price2Sc = $data_series_styling[$v]['price2Sc'];
				$row->updated_at = time();
				$row->update(false);
			}*/
		}

		//更新设置
		$max = CarSeries::find()->max('id');
		if (end($sResult)->id>=$max) {
			$config['id'] = 0;
		}else{
			$config['id'] = end($sResult)->id;
		}
		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();
		// var_dump($config);

		//批量插入
		Yii::$app->db->createCommand()
       	->batchInsert(CarSeriesColor::tableName(),['color','name','s_id'],$data_insert_color)
       	->execute();
		Yii::$app->db->createCommand()
       	->batchInsert(CarSeriesStyling::tableName(),['group_name','name','oid','status','price','price2Sc','s_id','created_at'],$data_insert_styling)
       	->execute();
    }

	// 品牌,系列
    public function spider1() {
		$headerArr = $this->setHeader();

		$page = ['A','B','C','D','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','V','W','X','Y','Z'];
		$referer = 'https://www.autohome.com.cn';

		$data_brand = [];
		$data_series = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_1'])->one();
		$config = json_decode($configResult->content,true);

		$url = 'https://www.autohome.com.cn/grade/carhtml/'.$page[$config['id']].'.html';

		$html = SimpleHTMLDom::str_get_html($this->get_html($url,$headerArr,$referer));
		
		foreach($html->find('dl') as $dl) {//break;

			$brand_id = $dl->id;

		    $o = $dl->id;//oid
		    $l = $dl->find('dt a img',0)->src;//logo
		    $b = $dl->find('dt div a',0)->innertext;//品牌名

		    $data_brand[$o] = [
		    	'brand'=>mb_convert_encoding($b, 'utf-8', 'gb2312,gbk'),
		    	'logo'=>$l,
		    	'oid'=>$o
		    	]; //批量数据

		    $num = count($dl->find('dd .h3-tit'));
		    for ($i=0; $i < $num; $i++) {
		        $factory = $dl->find('dd .h3-tit',$i)->innertext;//生产商名称   
		        foreach ($dl->find('dd .rank-list-ul',$i)->find('li') as $li) { //系列名称
		            if (!$li->find('h4 a',0)) {
		                continue;
		            }

		            $o = substr( $li->id, 1);
		            $url = $li->find('h4 a',0)->href;
		            $new = count($li->find('h4 i')) ? 1:0;
		            $name = $li->find('h4 a',0)->innertext;
		            $price = count($li->find('div'))>1 ? $li->find('div',0)->find('a',0)->innertext:'';

					$url2 = 'https://api.che168.com/auto/ForAutoCarPCInterval.ashx?_appid=cms&sid='.$o; //price2Sc 二手价
		            $data = json_decode($this->get_html($url2,$headerArr,$referer),true);
		            // var_dump($data);
		            // echo $price;
		            // exit;
		            $data_series[$o] = [
			            	'b_id'=>$brand_id,
			            	'new'=>$new,
			            	'url'=>$url,
			            	'oid'=>$o,
			            	'name'=>mb_convert_encoding($name, 'utf-8', 'gb2312,gbk'),
			            	'factory'=>mb_convert_encoding($factory, 'utf-8', 'gb2312,gbk'),
			            	'price'=>mb_convert_encoding($price, 'utf-8', 'gb2312,gbk'),
			            	'price2Sc'=> isset($data['result']['minPrice']) ? $data['result']['minPrice'] . '-' . $data['result']['maxPrice'] .' 万' : '',
		            	]; //批量数据
		        }
		    }
		}


		$brand = array_keys($data_brand);

		$sResult = CarBrand::find()->select(['id','oid'])->where(['oid'=>$brand])->all();
		
		$s = \yii\helpers\ArrayHelper::getColumn($sResult, function ($element) {
		    return $element->oid;
		});

		$sResult = array_combine($s, $sResult);
		$diff = array_diff($brand, $s);
		$data_insert_brand = [];
		foreach ($brand as $v) {
			if (in_array($v, $diff)) {
				$row = $data_brand[$v];
				$row['created_at'] = time();
				$data_insert_brand[] = $row; //插入新数据
			}else{
				$row = $sResult[$v]; //更新数据
				$row->updated_at = time();
				$row->update(false);
			}
		}


		$series = array_keys($data_series);

		$sResult = CarSeries::find()->select(['id','oid'])->where(['oid'=>$series])->all();

		$s = \yii\helpers\ArrayHelper::getColumn($sResult, function ($element) {
		    return $element->oid;
		});

		$sResult = array_combine($s, $sResult);
		$diff = array_diff($series, $s);
		$data_insert_series = [];
		foreach ($series as $v) {
			if (in_array($v, $diff)) {
				$row = $data_series[$v];
				$row['created_at'] = time();
				$data_insert_series[] = $row; //插入新数据
			}else{
				$row = $sResult[$v]; //更新数据
				$row->new = $data_series[$v]['new'];
				$row->url = $data_series[$v]['url'];
				$row->price = $data_series[$v]['price'];
				$row->price2Sc = $data_series[$v]['price2Sc'];
				$row->updated_at = time();
				$row->update(false);
			}
		}


		//更新设置
		$max = count($page) -1 ;
		if ($config['id']>=$max) {
			$config['id'] = 0;
		}else{
			$config['id'] += 1;
		}
		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();

		Yii::$app->db->createCommand()
       	->batchInsert(CarBrand::tableName(),['brand','logo','oid','created_at'],$data_insert_brand)
       	->execute();

		Yii::$app->db->createCommand()
       	->batchInsert(CarSeries::tableName(),['b_id','new','url','oid','name','factory','price','price2Sc','created_at'],$data_insert_series)
       	->execute();
    }

    public function get_html($url,$headers,$referer=''){
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url);

	    curl_setopt($ch, CURLOPT_HTTPHEADER , $headers);  //构造IP

	    curl_setopt($ch, CURLOPT_REFERER, $referer);   //构造来路

	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		// curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
		// curl_setopt($ch, CURLOPT_PROXY, $this->cnip()); //代理服务器地址
		// curl_setopt($ch, CURLOPT_PROXYPORT, 80); //代理服务器端口
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":"); //http代理认证帐号，username:password的格式
		// curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式

	    $request = curl_exec($ch);
	    curl_close($ch);
	    return $request;
	}

	public function setHeader(){
		$headerArr = array(); 
		ini_set('memory_limit',-1);
		ini_set('max_execution_time',  -1);
		$ip = $this->cnip();
		$headers['CLIENT-IP'] = $ip;

		$headers['X-FORWARDED-FOR'] = $ip;

		$headers['User-Agent'] = $this->cnua();

		foreach( $headers as $n => $v ) { 

		    $headerArr[] = $n .':' . $v;  

		}
		return $headerArr;
	}

	//随机ip
	public function cnip() {
        //国内城市IP段
        $ips = '58.14.0.0,58.16.0.0,58.24.0.0,58.30.0.0,58.32.0.0,58.66.0.0,58.68.128.0,58.82.0.0,58.87.64.0,58.99.128.0,58.100.0.0,58.116.0.0,58.128.0.0,58.144.0.0,58.154.0.0,58.192.0.0,58.240.0.0,59.32.0.0,59.64.0.0,59.80.0.0,59.107.0.0,59.108.0.0,59.151.0.0,59.155.0.0,59.172.0.0,59.191.0.0,59.191.240.0,59.192.0.0,60.0.0.0,60.55.0.0,60.63.0.0,60.160.0.0,60.194.0.0,60.200.0.0,60.208.0.0,60.232.0.0,60.235.0.0,60.245.128.0,60.247.0.0,60.252.0.0,60.253.128.0,60.255.0.0,61.4.80.0,61.4.176.0,61.8.160.0,61.28.0.0,61.29.128.0,61.45.128.0,61.47.128.0,61.48.0.0,61.87.192.0,61.128.0.0,61.232.0.0,61.236.0.0,61.240.0.0,114.28.0.0,114.54.0.0,114.60.0.0,114.64.0.0,114.68.0.0,114.80.0.0,116.1.0.0,116.2.0.0,116.4.0.0,116.8.0.0,116.13.0.0,116.16.0.0,116.52.0.0,116.56.0.0,116.58.128.0,116.58.208.0,116.60.0.0,116.66.0.0,116.69.0.0,116.70.0.0,116.76.0.0,116.89.144.0,116.90.184.0,116.95.0.0,116.112.0.0,116.116.0.0,116.128.0.0,116.192.0.0,116.193.16.0,116.193.32.0,116.194.0.0,116.196.0.0,116.198.0.0,116.199.0.0,116.199.128.0,116.204.0.0,116.207.0.0,116.208.0.0,116.212.160.0,116.213.64.0,116.213.128.0,116.214.32.0,116.214.64.0,116.214.128.0,116.215.0.0,116.216.0.0,116.224.0.0,116.242.0.0,116.244.0.0,116.248.0.0,116.252.0.0,116.254.128.0,116.255.128.0,117.8.0.0,117.21.0.0,117.22.0.0,117.24.0.0,117.32.0.0,117.40.0.0,117.44.0.0,117.48.0.0,117.53.48.0,117.53.176.0,117.57.0.0,117.58.0.0,117.59.0.0,117.60.0.0,117.64.0.0,117.72.0.0,117.74.64.0,117.74.128.0,117.75.0.0,117.76.0.0,117.80.0.0,117.100.0.0,117.103.16.0,117.103.128.0,117.106.0.0,117.112.0.0,117.120.64.0,117.120.128.0,117.121.0.0,117.121.128.0,117.121.192.0,117.122.128.0,117.124.0.0,117.128.0.0,118.24.0.0,118.64.0.0,118.66.0.0,118.67.112.0,118.72.0.0,118.80.0.0,118.84.0.0,118.88.32.0,118.88.64.0,118.88.128.0,118.89.0.0,118.91.240.0,118.102.16.0,118.112.0.0,118.120.0.0,118.124.0.0,118.126.0.0,118.132.0.0,118.144.0.0,118.178.0.0,118.180.0.0,118.184.0.0,118.192.0.0,118.212.0.0,118.224.0.0,118.228.0.0,118.230.0.0,118.239.0.0,118.242.0.0,118.244.0.0,118.248.0.0,119.0.0.0,119.2.0.0,119.2.128.0,119.3.0.0,119.4.0.0,119.8.0.0,119.10.0.0,119.15.136.0,119.16.0.0,119.18.192.0,119.18.208.0,119.18.224.0,119.19.0.0,119.20.0.0,119.27.64.0,119.27.160.0,119.27.192.0,119.28.0.0,119.30.48.0,119.31.192.0,119.32.0.0,119.40.0.0,119.40.64.0,119.40.128.0,119.41.0.0,119.42.0.0,119.42.136.0,119.42.224.0,119.44.0.0,119.48.0.0,119.57.0.0,119.58.0.0,119.59.128.0,119.60.0.0,119.62.0.0,119.63.32.0,119.75.208.0,119.78.0.0,119.80.0.0,119.84.0.0,119.88.0.0,119.96.0.0,119.108.0.0,119.112.0.0,119.128.0.0,119.144.0.0,119.148.160.0,119.161.128.0,119.162.0.0,119.164.0.0,119.176.0.0,119.232.0.0,119.235.128.0,119.248.0.0,119.253.0.0,119.254.0.0,120.0.0.0,120.24.0.0,120.30.0.0,120.32.0.0,120.48.0.0,120.52.0.0,120.64.0.0,120.72.32.0,120.72.128.0,120.76.0.0,120.80.0.0,120.90.0.0,120.92.0.0,120.94.0.0,120.128.0.0,120.136.128.0,120.137.0.0,120.192.0.0,121.0.16.0,121.4.0.0,121.8.0.0,121.16.0.0,121.32.0.0,121.40.0.0,121.46.0.0,121.48.0.0,121.51.0.0,121.52.160.0,121.52.208.0,121.52.224.0,121.55.0.0,121.56.0.0,121.58.0.0,121.58.144.0,121.59.0.0,121.60.0.0,121.68.0.0,121.76.0.0,121.79.128.0,121.89.0.0,121.100.128.0,121.101.208.0,121.192.0.0,121.201.0.0,121.204.0.0,121.224.0.0,121.248.0.0,121.255.0.0,122.0.64.0,122.0.128.0,122.4.0.0,122.8.0.0,122.48.0.0,122.49.0.0,122.51.0.0,122.64.0.0,122.96.0.0,122.102.0.0,122.102.64.0,122.112.0.0,122.119.0.0,122.136.0.0,122.144.128.0,122.152.192.0,122.156.0.0,122.192.0.0,122.198.0.0,122.200.64.0,122.204.0.0,122.224.0.0,122.240.0.0,122.248.48.0,123.0.128.0,123.4.0.0,123.8.0.0,123.49.128.0,123.52.0.0,123.56.0.0,123.64.0.0,123.96.0.0,123.98.0.0,123.99.128.0,123.100.0.0,123.101.0.0,123.103.0.0,123.108.128.0,123.108.208.0,123.112.0.0,123.128.0.0,123.136.80.0,123.137.0.0,123.138.0.0,123.144.0.0,123.160.0.0,123.176.80.0,123.177.0.0,123.178.0.0,123.180.0.0,123.184.0.0,123.196.0.0,123.199.128.0,123.206.0.0,123.232.0.0,123.242.0.0,123.244.0.0,123.249.0.0,123.253.0.0,124.6.64.0,124.14.0.0,124.16.0.0,124.20.0.0,124.28.192.0,124.29.0.0,124.31.0.0,124.40.112.0,124.40.128.0,124.42.0.0,124.47.0.0,124.64.0.0,124.66.0.0,124.67.0.0,124.68.0.0,124.72.0.0,124.88.0.0,124.108.8.0,124.108.40.0,124.112.0.0,124.126.0.0,124.128.0.0,124.147.128.0,124.156.0.0,124.160.0.0,124.172.0.0,124.192.0.0,124.196.0.0,124.200.0.0,124.220.0.0,124.224.0.0,124.240.0.0,124.240.128.0,124.242.0.0,124.243.192.0,124.248.0.0,124.249.0.0,124.250.0.0,124.254.0.0,125.31.192.0,125.32.0.0,125.58.128.0,125.61.128.0,125.62.0.0,125.64.0.0,125.96.0.0,125.98.0.0,125.104.0.0,125.112.0.0,125.169.0.0,125.171.0.0,125.208.0.0,125.210.0.0,125.213.0.0,125.214.96.0,125.215.0.0,125.216.0.0,125.254.128.0,134.196.0.0,159.226.0.0,161.207.0.0,162.105.0.0,166.111.0.0,167.139.0.0,168.160.0.0,169.211.1.0,192.83.122.0,192.83.169.0,192.124.154.0,192.188.170.0,198.17.7.0,202.0.110.0,202.0.176.0,202.4.128.0,202.4.252.0,202.8.128.0,202.10.64.0,202.14.88.0,202.14.235.0,202.14.236.0,202.14.238.0,202.20.120.0,202.22.248.0,202.38.0.0,202.38.64.0,202.38.128.0,202.38.136.0,202.38.138.0,202.38.140.0,202.38.146.0,202.38.149.0,202.38.150.0,202.38.152.0,202.38.156.0,202.38.158.0,202.38.160.0,202.38.164.0,202.38.168.0,202.38.176.0,202.38.184.0,202.38.192.0,202.41.152.0,202.41.240.0,202.43.144.0,202.46.32.0,202.46.224.0,202.60.112.0,202.63.248.0,202.69.4.0,202.69.16.0,202.70.0.0,202.74.8.0,202.75.208.0,202.85.208.0,202.90.0.0,202.90.224.0,202.90.252.0,202.91.0.0,202.91.128.0,202.91.176.0,202.91.224.0,202.92.0.0,202.92.252.0,202.93.0.0,202.93.252.0,202.95.0.0,202.95.252.0,202.96.0.0,202.112.0.0,202.120.0.0,202.122.0.0,202.122.32.0,202.122.64.0,202.122.112.0,202.122.128.0,202.123.96.0,202.124.24.0,202.125.176.0,202.127.0.0,202.127.12.0,202.127.16.0,202.127.40.0,202.127.48.0,202.127.112.0,202.127.128.0,202.127.160.0,202.127.192.0,202.127.208.0,202.127.212.0,202.127.216.0,202.127.224.0,202.130.0.0,202.130.224.0,202.131.16.0,202.131.48.0,202.131.208.0,202.136.48.0,202.136.208.0,202.136.224.0,202.141.160.0,202.142.16.0,202.143.16.0,202.148.96.0,202.149.160.0,202.149.224.0,202.150.16.0,202.152.176.0,202.153.48.0,202.158.160.0,202.160.176.0,202.164.0.0,202.164.25.0,202.165.96.0,202.165.176.0,202.165.208.0,202.168.160.0,202.170.128.0,202.170.216.0,202.173.8.0,202.173.224.0,202.179.240.0,202.180.128.0,202.181.112.0,202.189.80.0,202.192.0.0,203.18.50.0,203.79.0.0,203.80.144.0,203.81.16.0,203.83.56.0,203.86.0.0,203.86.64.0,203.88.32.0,203.88.192.0,203.89.0.0,203.90.0.0,203.90.128.0,203.90.192.0,203.91.32.0,203.91.96.0,203.91.120.0,203.92.0.0,203.92.160.0,203.93.0.0,203.94.0.0,203.95.0.0,203.95.96.0,203.99.16.0,203.99.80.0,203.100.32.0,203.100.80.0,203.100.96.0,203.100.192.0,203.110.160.0,203.118.192.0,203.119.24.0,203.119.32.0,203.128.32.0,203.128.96.0,203.130.32.0,203.132.32.0,203.134.240.0,203.135.96.0,203.135.160.0,203.142.219.0,203.148.0.0,203.152.64.0,203.156.192.0,203.158.16.0,203.161.192.0,203.166.160.0,203.171.224.0,203.174.7.0,203.174.96.0,203.175.128.0,203.175.192.0,203.176.168.0,203.184.80.0,203.187.160.0,203.190.96.0,203.191.16.0,203.191.64.0,203.191.144.0,203.192.0.0,203.196.0.0,203.207.64.0,203.207.128.0,203.208.0.0,203.208.16.0,203.208.32.0,203.209.224.0,203.212.0.0,203.212.80.0,203.222.192.0,203.223.0.0,210.2.0.0,210.5.0.0,210.5.144.0,210.12.0.0,210.14.64.0,210.14.112.0,210.14.128.0,210.15.0.0,210.15.128.0,210.16.128.0,210.21.0.0,210.22.0.0,210.23.32.0,210.25.0.0,210.26.0.0,210.28.0.0,210.32.0.0,210.51.0.0,210.52.0.0,210.56.192.0,210.72.0.0,210.76.0.0,210.78.0.0,210.79.64.0,210.79.224.0,210.82.0.0,210.87.128.0,210.185.192.0,210.192.96.0,211.64.0.0,211.80.0.0,211.96.0.0,211.136.0.0,211.144.0.0,211.160.0.0,218.0.0.0,218.56.0.0,218.64.0.0,218.96.0.0,218.104.0.0,218.108.0.0,218.185.192.0,218.192.0.0,218.240.0.0,218.249.0.0,219.72.0.0,219.82.0.0,219.128.0.0,219.216.0.0,219.224.0.0,219.242.0.0,219.244.0.0,220.101.192.0,220.112.0.0,220.152.128.0,220.154.0.0,220.160.0.0,220.192.0.0,220.231.0.0,220.231.128.0,220.232.64.0,220.234.0.0,220.242.0.0,220.248.0.0,220.252.0.0,221.0.0.0,221.8.0.0,221.12.0.0,221.12.128.0,221.13.0.0,221.14.0.0,221.122.0.0,221.129.0.0,221.130.0.0,221.133.224.0,221.136.0.0,221.172.0.0,221.176.0.0,221.192.0.0,221.196.0.0,221.198.0.0,221.199.0.0,221.199.128.0,221.199.192.0,221.199.224.0,221.200.0.0,221.208.0.0,221.224.0.0,222.16.0.0,222.32.0.0,222.64.0.0,222.125.0.0,222.126.128.0,222.128.0.0,222.160.0.0,222.168.0.0,222.176.0.0,222.192.0.0,222.240.0.0,222.248.0.0';
        $arr = explode(',', $ips);
        $rnd = rand(0, count($arr) - 1);
        $ip = $arr[$rnd];
        $arr = explode('.', $ip);
        foreach ($arr as &$v) {
            if ($v == 0)
                $v = rand(0, 255);
        }
        $ip = join('.', $arr);
        return $ip;
    }

    //随机浏览器
    public function cnua(){
    	$data = [
    		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.163 Safari/535.1',
    		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0) Gecko/20100101 Firefox/6.0',
    		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
    		'Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.9.168 Version/11.50',
    		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 2.0.50727; SLCC2; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; Tablet PC 2.0; .NET4.0E)',
    		'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; InfoPath.3)',
    		'Mozilla/5.0 (Windows; U; Windows NT 6.1; ) AppleWebKit/534.12 (KHTML, like Gecko) Maxthon/3.0 Safari/534.12',
    		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E)',
    		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)',
    		'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.33 Safari/534.3 SE 2.X MetaSr 1.0',
    		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E)',
    		'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.41 Safari/535.1 QQBrowser/6.9.11079.201',
    		'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; InfoPath.3; .NET4.0C; .NET4.0E) QQBrowser/6.9.11079.201',
    		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)',
    		// '',
    		// '',
    	];
    	return $data[array_rand($data,1)];
    }
}



