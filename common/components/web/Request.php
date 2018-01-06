<?php 

namespace common\components\web;

use Yii;
use yii\base\InvalidConfigException;


class Request extends \yii\web\Request{

    //加密秘钥，  
    public $_key;
    public $_iv;
    public $_enable3DES = false;

    public $_integration = [];

    public function getQueryParams()
    {
        $pathInfo = $this->getUrl();

        if (substr($pathInfo, 0, 1) === '/') {
            $pathInfo = substr($pathInfo, 1);
        }
        if ($pathInfo) {
            $pathInfo = $this->decrypt3DES($pathInfo);
        }

        // echo $pathInfo;exit;
        $urlInfo = parse_url($pathInfo);

        if (isset($urlInfo['query'])) {

            foreach(explode("&",$urlInfo['query']) as $v) {
                $b = explode("=", $v);
                $_GET[$b[0]] = isset($b[1]) ? $b[1] : '';
            }
            return $_GET;
        }

        return parent::getQueryParams();
    }

    protected function resolvePathInfo()
    {
        $pathInfo = $this->getUrl();

        if (substr($pathInfo, 0, 1) === '/') {
            $pathInfo = substr($pathInfo, 1);
        }
        if ($pathInfo) {
            $pathInfo = $this->decrypt3DES($pathInfo);
        }

        if (($pos = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }
        $pathInfo = urldecode($pathInfo);

        if (!preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]              # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
            | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
            )*$%xs', $pathInfo)
        ) {
            $pathInfo = utf8_encode($pathInfo);
        }

        $scriptUrl = $this->getScriptUrl();
        $baseUrl = $this->getBaseUrl();
        if (strpos($pathInfo, $scriptUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($scriptUrl));
        } elseif ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        } elseif (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0) {
            $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
        } else {
            throw new InvalidConfigException('Unable to determine the path info of the current request.');
        }

        $pathInfo = $this->checkIntegration($pathInfo);

        return (string) $pathInfo;
    }


    public function checkIntegration($pathInfo)
    {
        $suffix = isset(Yii::$app->components['urlManager']['suffix']) ? Yii::$app->components['urlManager']['suffix'] : '';
        foreach ($this->_integration as $v) {
            if ( $v && (strpos($pathInfo,$v)!== false) ) {
                $pathInfo = $v . $suffix;
            }
        }
        return $pathInfo;
    }

    /** 
    * 对字符串进行3DES加密 
    * @param string 要加密的字符串 
    * @return mixed 加密成功返回加密后的字符串，否则返回false 
    */  
    public function encrypt3DES($str)  
    {
        if (!$this->_enable3DES) {
            return $str;
        }
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");  
        if ($td === false) {  
            return false;  
        }
        //检查加密key，iv的长度是否符合算法要求  
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));  
        $iv = $this->fixLen($this->_iv, mcrypt_enc_get_iv_size($td));  
          
        //加密数据长度处理  
        $str = $this->strPad($str, mcrypt_enc_get_block_size($td));  
          
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {  
            return false;  
        }  
        $result = mcrypt_generic($td, $str);  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        return base64_encode($result);  
    }  
      
    /** 
    * 对加密的字符串进行3DES解密 
    * @param string 要解密的字符串 
    * @return mixed 加密成功返回加密后的字符串，否则返回false 
    */  
    public function decrypt3DES($str)  
    {  
        if (!$this->_enable3DES) {
            return $str;
        }
        $str = base64_decode($str);
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");  
        if ($td === false) {  
            return false;  
        }  
          
        //检查加密key，iv的长度是否符合算法要求  
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));  
        $iv = $this->fixLen($this->_iv, mcrypt_enc_get_iv_size($td));  
          
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {  
            return false;  
        }  
          
        $result = mdecrypt_generic($td, $str);  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
          
        return $this->strUnPad($result);  
    }  
      
    /** 
    * 返回适合算法长度的key，iv字符串 
    * @param string $str key或iv的值 
    * @param int $td_len 符合条件的key或iv长度 
    * @return string 返回处理后的key或iv值 
    */  
    private function fixLen($str, $td_len)  
    {  
        $str_len = strlen($str);  
        if ($str_len > $td_len) {  
            return substr($str, 0, $td_len);  
        } else if($str_len < $td_len) {  
            return str_pad($str, $td_len, '0');  
        }  
        return $str;  
    }  
      
    /** 
    * 返回适合算法的分组大小的字符串长度，末尾使用\0补齐 
    * @param string $str 要加密的字符串 
    * @param int $td_group_len 符合算法的分组长度 
    * @return string 返回处理后字符串 
    */  
    private function strPad($str, $td_group_len)  
    {  
        $padding_len = $td_group_len - (strlen($str) % $td_group_len);  
        return str_pad($str, strlen($str) + $padding_len, "\0");  
    }  
      
    /** 
    * 返回适合算法的分组大小的字符串长度，末尾使用\0补齐 
    * @param string $str 要加密的字符串 
    * @return string 返回处理后字符串 
    */  
    private function strUnPad($str)  
    {  
        return rtrim($str);  
    }  
}