<?php
namespace common\helpers;

/**
 * Json is a helper class providing JSON data encoding and decoding.
 * It enhances the PHP built-in functions `json_encode()` and `json_decode()`
 * by supporting encoding JavaScript expressions and throwing exceptions when decoding fails.
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Json extends \yii\helpers\Json
{

	public static function json_str_to_func($str)
	{
		$pattern = [
			'/"function/',
			'/}"/'
		];
		$subject = [
			'function',
			'}',
		];
		return preg_replace($pattern, $subject, $str);
	}

	public static function htmlEncode($value)
	{
		return static::json_str_to_func(parent::htmlEncode($value));
	}
}
