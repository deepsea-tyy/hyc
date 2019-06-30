<?php
namespace backend\common\grid;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;


class ActionColumn extends \kartik\grid\ActionColumn
{

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('delete', 'trash', [
            'class'=>'ajax-request',
            'data-swal'=> json_encode([
                'title' => '删除',
                'message'=>'确定删除?',
                'type'=>'info',
                'allowOutsideClick'=>false,
                'showConfirmButton'=>true,
                'showCancelButton'=>true,
                'confirmButtonClass'=>'btn-danger',
                'cancelButtonClass' => 'btn-default',
                'closeOnConfirm' => false,
                'closeOnCancel' => true,
                'confirmButtonText' => '确定',
                'cancelButtonText' => '取消',
            ]),
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