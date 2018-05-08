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
            'class'=>'ajax-request',
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

    /**
     * Initializes the default button rendering callback for single button.
     * @param string $name Button name as it's written in template
     * @param string $iconName The part of Bootstrap glyphicon class that makes it unique
     * @param array $additionalOptions Array of additional options
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge($additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}