<?php
namespace common\helpers;

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
	 * 生成编号
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
	function secondConversion($second = 0)
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
	function get_lately_days($day, $data)
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
}
