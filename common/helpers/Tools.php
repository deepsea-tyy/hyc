<?php
namespace common\helpers;

use Yii;
use mdm\admin\models\Menu;
/**
 * 工具函数
 */
class Tools extends \yii\helpers\BaseIpHelper
{
	/**
	 * 返回当前的毫秒时间戳
	 */
	public static function mstime() {
	    list($tmp1, $tmp2) = explode(' ', microtime());
	    return sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
	}


	/**
	 * 生成编号 sign number
	 */
	public static function get_sn($type){
	    switch ($type)
	    {
	        case 1:         //订单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 2:         //支付单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 3:         //商品编号
	            $str = 'G'.substr(static::mstime().rand(0,5),1);
	            break;
	        case 4:         //货品编号
	            $str = 'P'.substr(static::mstime().rand(0,5),1);
	            break;
	        case 5:         //售后单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 6:         //退款单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 7:         //退货单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 8:         //发货单编号
	            $str = $type.substr(static::mstime().rand(0,9),1);
	            break;
	        case 9:         //提货单号
	            $str = 'T'.$type.substr(static::mstime().rand(0,5), 1);
	            break;
	        case 10:         //文件编号
	            $str = 'F'.$type.substr(static::mstime().rand(0,5), 1);
	            break;
	        default:
	            $str = substr(static::mstime().rand(0,9),1);
	    }
	    return $str;
	}


	/**
	 * 格式化数据化手机号码
	 */
	public static function format_number($number,$type=1)
	{
		switch ($type) {
			case 1://两位小数
	    		return sprintf("%.2f", $number);
				break;
			case 2://手机号码
	    		return substr($number,0,5)."****".substr($number,9,2);
				break;
			
			default:
				return false;
				break;
		}
	}

	/**
	 * 判断是否手机号
	 * @param $mobile
	 * @return bool
	 */
	public static function is_mobile($mobile = ''){
	    if (preg_match("/^1[3456789]{1}\d{9}$/", $mobile)) {
	        return true;
	    } else {
	        return false;
	    }
	}

	/**
	 * 秒转换为天，小时，分钟
	 * @param int $second
	 * @return string
	 */
	public static function secondConversion($second = 0)
	{
	    $newtime = '';
	    $d = floor($second / (3600*24));
	    $h = floor(($second % (3600*24)) / 3600);
	    $m = floor((($second % (3600*24)) % 3600) / 60);
	    if($d>'0'){
	        if($h == '0' && $m == '0'){
	            $newtime= $d.'天';
	        }else{
	            $newtime= $d.'天'.$h.'小时'.$m.'分';
	        }
	    }else{
	        if($h!='0'){
	            if($m == '0'){
	                $newtime= $h.'小时';
	            }else{
	                $newtime= $h.'小时'.$m.'分';
	            }
	        }else{
	            $newtime= $m.'分';
	        }
	    }
	    return $newtime;
	}

	/**
	 * 获取最近天数的日期和数据
	 * @param $day
	 * @param $data
	 * @return array
	 */
	public static function get_lately_days($day, $data)
	{
	    $day = $day-1;
	    $days = [];
	    $d = [];
	    for($i = $day; $i >= 0; $i --)
	    {
	        $d[] = date('d', strtotime('-'.$i.' day')).'日';
	        $days[date('Y-m-d', strtotime('-'.$i.' day'))] = 0;
	    }
	    foreach($data as $v)
	    {
	        $days[$v['day']] = $v['nums'];
	    }
	    $new = [];
	    foreach ($days as $v)
	    {
	        $new[] = $v;
	    }
	    return ['day' => $d, 'data' => $new];
	}

	/**
	 * 页面面包屑
	 */
	public static function getPageBreadcrumb($route='')
	{
		if (!$route) $route = Yii::$app->controller->getRoute();
		$submenu = Menu::find()->where(['route'=>'/' . $route])->asArray()->one();
		$data = json_decode($submenu['data'],true); //菜单deep处理
		$deep = empty($data['deep']) ? 0 : $data['deep'];
		
		$links[] = self::normalizeMenu($submenu);
		$parent = [];
		if ($submenu['parent']) {
			$parent = Menu::find()->where(['id'=>$submenu['parent']])->one();
			$links = array_merge([self::normalizeMenu($parent)],$links);
		}
		if ($deep == 2) {
			$parent = Menu::find()->where(['id'=>$parent['parent']])->one();
			$links = array_merge([self::normalizeMenu($parent)],$links);
		}
		return array_filter($links);
	}

    private static function normalizeMenu($menu)
    {
    	$data = json_decode($menu['data'],true);
    	return [
    		'label' => $data['label'],
    		// 'url' => $menu['route'],
    	];
    }

    /**
     * 数组递归
     */
    public static function limitless($data,$pid=0,$level=0){
	    $arr = array();
	    foreach ($data as $v) {
	      if ($v['wg_parent_id'] == $pid) {
	        $arr[] = $v;
	        $arr = array_merge($arr,$this->limitless($data,$v['wg_id'],$level+1));
	      }
	    }
	    return $arr;
	}

	/**
	 * 读取所有目录
	 */
	public static function read_all_dir ( $dir ){
	    $result = [];
	    $handle = opendir($dir);//读资源
	    if ($handle){
	        $file = readdir($handle);
	        while (($file = readdir($handle)) !== false ){
	            if ($file != '.' && $file != '..'){
	                $cur_path = $dir . DIRECTORY_SEPARATOR . $file;
	                if (is_dir($cur_path )){//判断是否为目录，递归读取文件
	                    $result = array_merge($result, $this->read_all_dir($cur_path ));
	                }else{
	                    $result[] = $cur_path;
	                }
	            }
	        }
	        closedir($handle);
	    }
	    return $result;
	}

	/**
	 * 下载远程文件
	 * @param  string $url
	 * @param  [type] $name
	 * @param  string $path
	 * @return string
	 */
    public static function download_file($url, $name, $path='')
    {
        
        $file = $path . '/' . $name;
        if (!file_exists($path)) {
            self::make_dir($path);
        }
        ob_start(); //打开输出
        try {
	        @readfile($url); //输出内容
        } catch (Exception $e) {
        	return false;
        }
        $content = ob_get_contents(); //得到浏览器输出
        ob_end_clean(); //清除输出并关闭
        file_put_contents($file, $content);
        return $file;
    }

    /**
     * 递归生成目录
     * @param  [type] $dir [description]
     * @return [type]      [description]
     */
    public static function make_dir( $dir ){  
	   return  is_dir ( $dir ) or self::make_dir(dirname( $dir )) and  mkdir ( $dir , 0777);
	}


}
