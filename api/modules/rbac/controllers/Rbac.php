<?php

namespace api\modules\rbac\controllers;

use Yii;

/**
 * PermissionController implements the CRUD actions for AuthItem model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Rbac extends \api\controllers\Api
{

	public function init()
	{
		parent::init();
        Yii::$app->i18n->translations['*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@mdm/admin/messages',
            'fileMap' => [
                'rbac-admin' => 'rbac-admin.php',
            ],
        ];
	}
}
