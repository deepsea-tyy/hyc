<?php

namespace console\controllers;

use Yii;
use QL\QueryList;
use console\models\mongo\hf\HfBrand;
use console\models\mongo\hf\HfGoods;
use console\models\mongo\hf\Configs;
use yii\web\JsExpression;
use QL\Ext\Chrome;
use common\helpers\Tools;

class IndexController extends BaseController
{

	public function actionIndex()
	{
		Yii::$app->memcached->multiAdd(['a'=>1,'b'=>2]);
	}

	/**
	 * 汉服品牌
	 * @return [type]        [description]
	 */
	public function actionHbrand()
	{
		$rl = [
	          'initial'=>['.sl-v-logos>.J_valueList>li','data-initial'],
	          'url'=>['.sl-v-logos>.J_valueList>li>a','href'],
	          'name'=>['.sl-v-logos>.J_valueList>li>a','text'],
	          'logo'=>['.sl-v-logos>.J_valueList>li>a>img','src'],
	          'brand_id'=>['.sl-v-logos>.J_valueList>li','id'],
	      ];
	    $rl2 = [
	          'initial'=>['.J_brands>li','data-initial'],
	          'url'=>['.J_brands>li>a','href'],
	          'name'=>['.J_brands>li>a','text'],
	          'logo'=>['.J_brands>li>a>img','src'],
	          'brand_id'=>['.J_brands>li','id'],
	      ];
		$ql = QueryList::rules($rl);
		$data = $ql->get('https://search.jd.com/Search?keyword=%E6%B1%89%E6%9C%8D&enc=utf-8')->query()->getData(function($item){
				$item['brand_id'] = str_replace('brand-', '', $item['brand_id']);
				if (empty($item['logo'])) $item['logo'] = '';
		        return $item;
		    })->all();

		$qData = array_combine(array_column($data, 'brand_id'), array_map(function ($v){return 1;}, range(1, count($data))));

		$data2 = $ql->rules($rl2)->post('https://search.jd.com/brand.php?keyword=%E6%B1%89%E6%9C%8D&enc=utf-8&qrst=1&rt=1&stop=1&vt=2',$qData)
			->query()->getData(function($item){
				$item['brand_id'] = str_replace('brand-', '', $item['brand_id']);
				if (empty($item['logo'])) $item['logo'] = '';
		        return $item;
		    })->all();
			// ->queryData();
		$insert = array_merge($data2,$data);
		$row = HfBrand::getCollection()
       	->batchInsert($insert);

       	// print_r($row);
	}

	/**
	 * 汉服商品列表
	 * @return [type] [description]
	 */
	public function actionHsku()
	{
		$rows = HfBrand::find()->orderBy('_id asc')->offset(0)->limit(5)->asArray()->all();
		$jd = 'https://search.jd.com';
		foreach ($rows as $row) {

			$url = $jd . $row['url'];
			$ql = QueryList::get($url);
			
			preg_match('/base_url=\'.*;/i', $ql->getHtml(),$base_url);
			$base_url = substr($base_url[0], 10,-2);
			
			preg_match('/page_count:?\"\d+/i', $ql->getHtml(),$page_count);
			$page_count = str_replace('page_count:"', '', $page_count[0]);

			for ($i=1; $i <= $page_count; $i++) {
				if ($i%2 == 0) {
					$p1 = $i + 1;
				}else{
					$p1 = $i > 1 ? $i + 2 :$i;
				}
				$s1 = ($p1-1)*30+1;
				$p2 = $p1+1;
				$s2 = ($p2-1)*30+1;

				$url = $jd . '/search?'. $base_url . "&page={$p1}&s={$s1}&click=0";
				$url2 = $jd . '/s_new.php?'. $base_url ."&page={$p2}&s={$s2}&scrolling=y";
				$data = $this->getPid($url,$url2);
				$this->saveData($data);
			}
			break;
		}

	}

	/**
	 * 获取商品sku
	 * @param  string $url  [description]
	 * @param  [type] $url2 [description]
	 * @return array       [description]
	 */
	public function getPid($url,$url2)
	{
		$rl = [
	          'sku_id'=>['#J_goodsList>ul>.gl-item','data-sku'],
	      ];
	    $ql = QueryList::rules($rl)->get($url);
		$data = $ql->queryData();
		preg_match('/log_id:?\'\d+\.\d+/i', $ql->getHtml(),$logid);
		$log_id = str_replace('log_id:\'', '', $logid[0]);

		$rl2 = [
			'sku_id' => ['.gl-item','data-sku']
		];
		$url2 = $url2 . '&log_id=' .$log_id . '&tpl=3_L&show_items=' . implode(',', array_column($data, 'pid'));

		$data2 = $ql->rules($rl2)->get($url2)->queryData();

		return array_merge($data,$data2);
	}

	/**
	 * [saveData description]
	 * @param  [type] $data [description]
	 * @return bool       [description]
	 */
	public function saveData($data)
	{
		$row = HfGoods::getCollection()
       	->batchInsert($data);
       	return $row;
	}

	/**
	 * 汉服商品
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public function actionHgoods()
	{
		$config = Configs::find(['name'=>'hf_goods'])->one();
		// print_r($config->data['offset']);exit();
		$rows = HfGoods::find()->orderBy('_id asc')->offset($config->data['offset'])->limit($config->data['limit'])->asArray()->all();
		foreach ($rows as $row) {
			// print_r(count($row));exit();
			$url = 'https://item.jd.com/'.$row['sku_id'].'.html';
			$rl = [
		          'sku_name'=>['.sku-name','text'],
		          'sku_price'=>['.J-summary-price .p-price>.J-p-'.$row['sku_id'],'text'],
		      ];
			$ql = QueryList::getInstance();
			// 注册插件，默认注册的方法名为: chrome
			$ql->use(Chrome::class);
			$ql->chrome(function ($page,$browser)use($url) {
			    $page->goto($url,['timeout'=> 100000]);
			    // 等待h1元素出现
			    $page->waitFor(4000);
			    // 获取页面HTML内容
			    $html = $page->content();
			    // 关闭浏览器
			    // $browser->close();
			    // 返回值一定要是页面的HTML内容
			    return $html;
			},[
				'timeout' => 9000,
  				'ignoreHTTPSErrors' => true,
			])->rules($rl);
			$preview = explode('!', $ql->find('#spec-img')->attr('data-origin'));
			$preview_size = '!'.$preview[1]??'';
			$preview = explode('_', $preview[0]??'');
			$preview_url = 'https:' . $preview[0]??'' . '_';

			preg_match('/desc:.*\/\/.*\'/i', $ql->getHtml(),$content_url);
			$content_url = substr($content_url[0], 7,-1);

			$data = $ql->queryData(function ($item)use($content_url)
			{
				$item['sku_content'] = QueryList::get('https:' . $content_url)->rules(['img'=>['img','data-lazyload']])->queryData(function ($item)
				{
					return 'https:' . substr($item['img'], 2,-2);
				});
				// print_r($item);exit();
				$item['sku_name'] = iconv('GBK','UTF-8',$item['sku_name']);
				return $item;
			});

			$rl2 = [
		          'sku_imags'=>['#spec-list img','data-url'],
		          'sku_imags_thum'=>['#spec-list img','src'],
			];
			$images = $ql->rules($rl2)->queryData(function ($item)use($preview_url,$preview_size)
			{
				$item['sku_imags'] = $preview_url.$item['sku_imags'].$preview_size;
				$item['sku_imags_thum'] = 'https:'.$item['sku_imags_thum'];
				return $item;
			});
			$rl3 = [
		          'sku_parameter'=>['.p-parameter li','text'],
			];
			$sku_parameter = $ql->rules($rl3)->queryData(function ($item)
			{
				return iconv('GBK','UTF-8',$item['sku_parameter']);
			});
			$insert = $data[0];
			$insert['sku_imags'] = array_column($images, 'sku_imags');
			$insert['sku_imags_thum'] = array_column($images, 'sku_imags_thum');
			$insert['sku_parameter'] = $sku_parameter;
			$insert['updated_at'] = 0;
			// print_r($insert);exit();
			$fg = 0;
			if (count($row)>2) {
				$fg = HfGoods::updateAll(
					['$set'=>$insert],
					[
						'_id'=>$row['_id'],
					]
				);
			}else{
				$insert['created_at'] = time();
				$fg = HfGoods::updateAll(
					['$set'=>$insert],
					[
						'_id'=>$row['_id'],
						'sku_name'=>['$exists'=>false],
						'sku_content'=>['$exists'=>false],
						'sku_imags'=>['$exists'=>false],
						'sku_imags_thum'=>['$exists'=>false],
						'sku_parameter'=>['$exists'=>false],
						'sku_price'=>['$exists'=>false],
						'created_at'=>['$exists'=>false],
						'updated_at'=>['$exists'=>false],
					]
				);
			}
			if ($fg) {
				foreach ($insert['sku_content'] as $k => $v) {
					Tools::download_file($v,$row['sku_id'] . "_{$k}." . pathinfo($v,PATHINFO_EXTENSION) ,Yii::getAlias('@files') . '/' .$row['sku_id'] . '/content');
				}
				foreach ($insert['sku_imags'] as $k => $v) {
					Tools::download_file($v,$row['sku_id'] . "_{$k}." . pathinfo($v,PATHINFO_EXTENSION) ,Yii::getAlias('@files') . '/' .$row['sku_id'] . '/imags');
				}
				foreach ($insert['sku_imags_thum'] as $k => $v) {
					Tools::download_file($v,$row['sku_id'] . "_{$k}." . pathinfo($v,PATHINFO_EXTENSION) ,Yii::getAlias('@files') . '/' .$row['sku_id'] . '/thum');
				}
				// $config->data['offset'] = 
			}

		}
	}

}
