<?php
namespace backend\common\grid;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;


class ActionColumn extends \yii\grid\ActionColumn
{

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open',['class'=>'ajaxify']);
        $this->initDefaultButton('update', 'pencil',['class'=>'ajaxify']);
        $this->initDefaultButton('delete', 'trash', [
            'title' => '删除',
            'class'=>'ajaxify',
            'method'=>'POST',
            'data-sa'=>'1',
            'data-title'=>'删除',
            'data-message'=>'确定删除?',
            'data-type'=>'info',
            'data-allow-outside-click'=>'false',
            'data-show-confirm-button'=>'true',
            'data-show-cancel-button'=>'true',
            'data-confirm-button-class'=>'btn-danger',
            'data-cancel-button-class'=>'btn-default',
            'data-close-on-confirm'=>'false',
            'data-close-on-cancel'=>'true',
            'data-confirm-button-text'=>'确定',
            'data-cancel-button-text'=>'取消',
        ]);
    }


}