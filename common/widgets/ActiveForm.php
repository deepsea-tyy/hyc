<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;

use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ActiveFormAsset;

/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * For more details and usage information on ActiveForm, see the [guide article on forms](guide:input-forms).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveForm extends \yii\widgets\ActiveForm
{

    public function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $attributes = Json::htmlEncode($this->attributes);
        $view = $this->getView();
        ActiveFormAsset::register($view);
        $view->registerJs("jQuery('#$id').off();jQuery('#$id').yiiActiveForm($attributes, $options);");
    }

}