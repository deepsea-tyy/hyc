<?php

namespace console\controllers;

use Yii;
use serhatozles\simplehtmldom\SimpleHTMLDom;
use common\models\car\CarBrand;
use common\models\car\CarSeries;
use common\models\car\CarSeriesColor;
use common\models\car\CarSeriesStyling;
use common\models\car\CarConfig;
use common\models\Area;
use common\models\LinePrice;
use common\models\system\SystemConfig;

/**
 * 汽车之家数据爬虫
 */
class QcController extends BaseController
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
		if ($val) {
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

	//起运价格
	public function spider4(){
		$headerArr = $this->setHeader();

		$referer = 'www.ehuoyun.com';

		$data_line_price = [];

		$configResult = SystemConfig::find()->where(['name'=>'SPIDER_4'])->one();
		$config = json_decode($configResult->content,true);

		if ($config['starting'] == '-1') {
			return;
		}

		$row = Area::find()/*->select('codeid')*/->where('codeid>'.$config['starting'])->orderBy(['codeid' => SORT_ASC])->one();
		$config['destination'] = $config['destination'] < 999 ? 999 : $config['destination'];
		$sResult = Area::find()->select('codeid')->where('codeid>'.$config['destination'])->orderBy(['codeid' => SORT_ASC])->limit($config['limit'])->all();

		$municipality = [ //直辖市
				11,
				12,
				50,
				71,
				81,
				91,
			];

		$max = 9101;

		$idmuni1 = substr($row->codeid, 0,2);
		$ismuni1 = in_array($idmuni1, $municipality);
		if ($ismuni1) {
			$row = Area::find()->where(['parentid'=>$row->parentid])->orderBy(['codeid' => SORT_DESC])->one();
			$a = str_pad( $idmuni1, 6, 0, STR_PAD_RIGHT);
		}else{
			$a = str_pad( $row->codeid, 6, 0, STR_PAD_RIGHT);
		}

		foreach ($sResult as $v) {

			$idmuni2 = substr($v->codeid, 0,2);
			$ismuni2 = in_array($idmuni2, $municipality);
			if ($ismuni2) {
				$b = str_pad( $idmuni2, 6, 0, STR_PAD_RIGHT);
			}else{
				$b = str_pad( $v->codeid, 6, 0, STR_PAD_RIGHT);
			}

			$url = 'http://www.ehuoyun.com/rest/quote/calculate/'.$a.'/'.$b;
			$price = json_decode($this->get_html($url,$headerArr,$referer),true);
			if ($price) {
				$data_line_price[] = [
						'starting' => $ismuni1 ? $idmuni1 : $row->codeid,
						'destination' => $ismuni2 ? $idmuni2 : $v->codeid,
						'startingshow' => $a,
						'destinationshow' => $b,
						'price' => $price['value'],
						'created_at' => time(),
					];
			}
			if ($v->codeid >= $max) {
				break;
			}
		}
		// array_unique($data_line_price);
		// $max = Area::find()->max('codeid');
		if (end($sResult)->codeid>=$max) {
			$config['destination'] = 0;
			if ($row->codeid>=$max) {
				$config['starting'] = '-1'; //爬完不再跟新
			}else{
				$config['starting'] = $row->codeid;
			}
		}else{
			$config['destination'] = end($sResult)->codeid;
		}

		$content = json_encode($config);
		$configResult->content = $content;
		$configResult->save();

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
			$arr = [];

			foreach ($data as $v) {
				$sn = strpos($v, '{');
				if ($sn !== false) {
					$str = mb_substr(trim(trim($v),';'),$sn-1);
					$key = mb_substr(trim(trim($v),';'),0,$sn-1);
					$arr[$key] = $str;
				}
			}
			if (!empty($arr)) {
				
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
			}
			// break;
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


			// 在售
			//即将销售
			// $speclist10 = $html->find('div[id=speclist10]');

				$div = $html->find('div[id=speclist20]',0);
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

			// break;

			// 停售
				$div = $html->find('div[id=drop2]',0);
				if ($div) {
			        $ajaxurl = 'https://www.autohome.com.cn/ashx/series_allspec.ashx?s='.$row->oid.'&l='.rand(1,20).'&y=';
			        foreach ($div->find('li') as $li) {
			        	$year = $li->find('a',0)->data;
			            if (empty($year)) {
			            	continue;
			            }
			            $url2 = $ajaxurl . $year;
			            $str = mb_convert_encoding($this->get_html($url2,$headerArr,$referer), 'utf-8', 'gb2312,gbk');

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

}



