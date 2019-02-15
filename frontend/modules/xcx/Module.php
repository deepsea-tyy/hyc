<?php

namespace frontend\modules\xcx;
use Yii;

/**
 * xcx module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\xcx\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        
        define('MODULE_ID', $this->id);
        include_once Yii::getAlias('@xcx');
        exit();
    }
}
