<?php
namespace console\controllers;

use Yii;
use common\helpers\Tools;
use QL\QueryList;
use phpQuery;

/**
 * 长投学院
 */
class CtController extends BaseController
{
	public function actionIndex()
	{
		$data = json_decode(file_get_contents( dirname(__FILE__) . '/ct.json'),true);

		foreach ($data as $v) {
			foreach ($v['lessonList'] as $file) {
				Tools::download_file($file['sourceUrl'],$file['title'].'.mp3' ,Yii::getAlias('@files') . '/audio' . '/' .$v['title']);
			}
		}
		
	}

	public function getLesson($data,$hd,$dir='')
	{
		$dir = $dir ? $dir : $data['title'];
		foreach ($data['chapterList'] as $chapter) {
			$path = Yii::getAlias('@files') . '/audio' . "/$dir/" .$chapter['title'];
			foreach ($chapter['lessonList'] as $file) {
				$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/Lesson/Detail',['lessonId'=>$file['lessonId']],$hd
				)->getHtml();

				$lesson = json_decode($json,true);
				if ($lesson['code'] == 2000) {
					$lesson = $lesson['data'];

					if (!empty($lesson['sourceUrl'])) Tools::download_file($lesson['sourceUrl'],$lesson['title'].'.mp3' ,$path);
					$content = json_decode($lesson['content'],true);
					
					if (is_array($content)){
						foreach ($content as $v) {
							Tools::download_file($v, pathinfo($v,PATHINFO_BASENAME) ,$path . '/img');
						}
					}else{
						$url = $lesson['content'];
						if (strpos($url, 'http') === false) {
							var_dump($url);
							var_dump(11111111);
							continue;
						}
						$ql = QueryList::get($url);
						$html = $ql->removeHead()->getHtml();
						$imgs = $ql->rules(['src'=>['img','src']])->queryData(function ($item)
						{
							return trim(trim($item['src']),'.');
						});

						file_put_contents($path . '/index.html', $html);
							
						foreach ($imgs as $v) {
							$v = strpos($v, 'http') === false ? dirname($url) . $v : $v;
							Tools::download_file($v, pathinfo($v,PATHINFO_BASENAME) ,$path . '/img');
						}
					}
				}
			}
		}
	}


	public function actionSubject()
	{
		$header = [
				'headers'=>[
					'X-iChangTou-Json-Api-Signature' => '6a51e95b3824701c5ae9c3e846f4dd2c3ed485c8',
					'X-iChangTou-Json-Api-Token' => '7d679c467e8647aea0b567f9efd074a5',
					'X-iChangTou-Json-Api-Nonce' => '67',
					'X-iChangTou-Json-Api-Signature-Timestamp' => '1564893607842',
				]
			];
		// https://ict-api.ichangtou.com/ict-customer-api/Customer/Goods/QueryAllSubject
		$data = json_decode(file_get_contents('https://ict-course.ichangtou.com/course/course_bk_image.json'),true);
		foreach ($data as $v) {
			$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/Detail',['subjectId'=>$v['subjectId'],'version'=>0],$header
			)->getHtml();
			$subject = json_decode($json,true);
			if ($subject['code'] == 2000) {
				// $this->getLesson($subject['data'],$header);
			}else{
				$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/ChapterList',['subjectId'=>$v['subjectId']],$header
				)->getHtml();
				$subject = json_decode($json,true);
				if ($subject['code'] == 2000) {
					$this->getLesson2($subject['data'],$header,$v['subjectId']);
					// var_dump(111111);
					// exit();
				}
			}
		}
	}


	/**
	 * 进阶课
	 * @return [type] [description]
	 */
	public function actionSubject2()
	{
		$header = [
				'headers'=>[
					'X-iChangTou-Json-Api-Signature' => '6a51e95b3824701c5ae9c3e846f4dd2c3ed485c8',
					'X-iChangTou-Json-Api-Token' => '7d679c467e8647aea0b567f9efd074a5',
					'X-iChangTou-Json-Api-Nonce' => '67',
					'X-iChangTou-Json-Api-Signature-Timestamp' => '1564893607842',
				]
			];
		$data = json_decode(file_get_contents(dirname(__FILE__) . '/ct.json'),true);
		// print_r($data);exit();
		foreach ($data as $v) {
			if ($v['spuId'] != 11) continue;
			$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/ChapterList',['subjectId'=>$v['spuId']],$header
			)->getHtml();
			$subject = json_decode($json,true);
			// print_r($subject);exit();
			if ($subject['code'] == 2000) {
				$this->getLesson2($subject['data'],$header,$v['spuId']);
			}else{

				$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Customer/Goods/QueryGoodsDetail',['subjectId'=>$v['spuId']],$header
				)->getHtml();
				$goods = json_decode($json,true);
				print_r($goods);exit();
				$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/ChapterList',['subjectId'=>$v['spuId']],$header
				)->getHtml();
				$subject = json_decode($json,true);
			}
		}
	}


	public function getLesson2($data,$hd,$dir='')
	{
		$dir = $dir ? $dir : $data['title'];
		$img_base = '';
		$img_ext = '';
		foreach ($data['chapterList'] as $cid => $chapter) {
			// print_r($data['chapterList']);exit();
			var_dump($cid);
			// if ($cid < 13 & $cid > 1) continue;
			
			foreach ($chapter['lessonList'] as $lid => $file) {
				$path = Yii::getAlias('@files') . '/audio' . "/$dir/" .$chapter['title'];
				// if (!empty($file['sourceUrl'])) Tools::download_file($file['sourceUrl'],$file['title'].'.mp3' ,$path);

				$json = QueryList::postJson('https://ict-api.ichangtou.com/ict-customer-api/Course/Lesson/Detail',['lessonId'=>$file['lessonId']],$hd
				)->getHtml();
				$lesson = json_decode($json,true);

				$pageTemp = phpQuery::newDocumentFileHTML(Yii::getAlias('@files') . '/audio/temp.html');
				$pageTemp->find('.title')->html($file['title']);
				if (!empty($file['sourceUrl'])) $pageTemp->find('.audio')->append('<audio src="./' . $file['title'].'.mp3' .'" controls="controls"></audio>');
				if ($lesson['code'] == 2000) {
					$lesson = $lesson['data'];
					$content = json_decode($lesson['content'],true);
					
					if (is_array($content)){
						foreach ($content as $v) {
							Tools::download_file($v, pathinfo($v,PATHINFO_BASENAME) ,$path . '/img');
						}
					}else{
						$url = $lesson['content'];
						if (strpos($url, 'http') === false) continue;
						$ql = QueryList::get($url);
						$html = $ql->removeHead()->getHtml();
						// print_r($html);exit();
						$imgs = $ql->rules(['src'=>['img','src']])->queryData(function ($item)
						{
							return trim(trim($item['src']),'.');
						});

						foreach ($imgs as $v) {
							$v = strpos($v, 'http') === false ? dirname($url) . $v : $v;
							$img_base = dirname($v);
							$img_ext = pathinfo($v,PATHINFO_EXTENSION);
							// Tools::download_file($v, pathinfo($v,PATHINFO_BASENAME) ,$path . '/img');
							$pageTemp->find('.pptImg')->append('<img src="./img/' . pathinfo($v,PATHINFO_BASENAME) . '"/>');
						}
					}
				}else{
					if (empty($img_base)) continue;
					for ($i=1; $i < 100; $i++) { 
						$imgname = ($cid) . '-' . ($lid+1) . '-' . $i . '.' . $img_ext;
						$url = $img_base . '/' .$imgname;
						$header = get_headers($url);
						// print_r($header);
						// exit();
						if (strpos($header[0], '200') === false) break;
						var_dump($imgname);
						// Tools::download_file($url, pathinfo($url,PATHINFO_BASENAME) ,$path . '/img');
						$pageTemp->find('.pptImg')->append('<img src="./img/' . $imgname . '"/>');
					}
					Tools::make_dir($path);
				}
				file_put_contents($path . '/index' . ($lid+1) . '.html', $pageTemp->html());
				
			}
		}
	}

	public function actionGenhtml()
	{
		$idx = ['零','一','二','三','四','五','六','七','八','九','十','十一','十二','十三','十四','十五','十六','十七','十八','十九'];
		$idx_key = array_keys($idx);
		$path = Yii::getAlias('@files') . '/audio';
		$dir = Tools::read_all_dir($path);
		foreach ($dir as $v) {
			$subject = str_replace(dirname($v) . '/', '', $v);
			if (is_numeric($subject) && $subject == 25) {
				
				$course = Tools::read_all_dir($v);
				foreach ($course as $v2) {
						// var_dump($v2);exit();
					$lesson = Tools::read_all_dir($v2);
					$cn = array_filter(array_map(function ($v,$k)use($v2)
						{
							if (strpos($v2, '第'.$v) !== false) return $k;
						}, $idx,$idx_key));
	
					foreach ($lesson as $v3) {
						$dir3 = dirname($v3);
						$l = str_replace( $dir3 . '/', '', $v3);
						$ln = array_filter(array_map(function ($v,$k)use($l)
						{
							if (strpos($l, '第'.$v) !== false) return $k;
						}, $idx,$idx_key));
						$name = str_replace( '.mp3', '', $l);
						$pageTemp = phpQuery::newDocumentFileHTML($path . '/temp.html');
						$pageTemp->find('.title')->html($name);
						$pageTemp->find('.audio')->append('<audio src="' . $l .'" controls="controls"></audio>');
						for ($i=1; $i < 20; $i++) { 
							$img = '/img/' . end($cn).'-'.end($ln)."-{$i}.jpg";
							if (!file_exists($v2 . $img)) break;
							$pageTemp->find('.pptImg')->append('<img src=".' . $img . '"/>');
							
							file_put_contents($v2 . '/index' . end($ln) . '.html', $pageTemp->html());
							var_dump($v2 . '/index' . end($ln) . '.html');
						}
					}
				}
			}
		}
	}
}