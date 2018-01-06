<?php

namespace common\models\car;

/**
 * This is the ActiveQuery class for [[CarConfig]].
 *
 * @see CarConfig
 */
class CarConfigQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CarConfig[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CarConfig|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
