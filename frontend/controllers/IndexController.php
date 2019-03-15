<?php

namespace frontend\controllers;

use Yii;
use common\models\applet\WeixinAppletMessage;
use yii\httpclient\Client;
use serhatozles\simplehtmldom\SimpleHTMLDom;


class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $num = 25;
        $url = 'http://www.gb688.cn/bzgk/gb/std_list_type?page=1&pageSize='. $num .'&p.p1=1&p.p90=circulation_date&p.p91=desc';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData(['pcode' => $offset, 'count' => $limit])
            ->send();
        if ($response->isOk) $html = (string)$response->getData(true);

        $html_dom =  SimpleHTMLDom::str_get_html($html);
        // $val = $html['result']['list'];
        $data = [];
        for ($i=1; $i <= $num; $i++) { 
            $tr = $html_dom->find('.table-responsive tbody',0)->find('tr',$i);
            $data[$i]['number']              = trim($tr->find('td',1)->plaintext);
            $data[$i]['sampling']            = trim($tr->find('td',2)->plaintext);
            $data[$i]['name']                = trim($tr->find('td',3)->plaintext);
            $data[$i]['status']              = trim($tr->find('td',4)->plaintext);
            $data[$i]['issue_time']          = trim($tr->find('td',5)->plaintext);
            $data[$i]['implementation_time'] = trim($tr->find('td',6)->plaintext);
        }

        return $this->asJson($data);
    }


    public function actionChat()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',1);//获取全局身份
        return $this->render('chat',['token'=>$user->access_token,'uuid'=>$user->id]);
    }

    public function actionCust()
    {
        $this->layout = false;
        $s_uid = 1;
        $user = Yii::$app->user->identityClass::getAuthorizationBySubsystem($s_uid,'wechat_applet',2);//获取全局身份
        return $this->render('cust',['token'=>$user->access_token,'uuid'=>$user->id]);
    }
}
