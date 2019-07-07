<?php

namespace api\modules\rbac\controllers;

use Yii;
use yii\data\Pagination;
use mdm\admin\components\MenuHelper;
use mdm\admin\models\AuthItem;
use yii\rbac\Item;
use common\helpers\Tools;

class RoleController extends ItemController
{
   
    public $type = Item::TYPE_ROLE;
    
    /**
     * @inheritdoc
     */
    public function getType()
    {
        return Item::TYPE_ROLE;
    }

}
