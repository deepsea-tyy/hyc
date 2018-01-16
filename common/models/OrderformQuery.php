<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Orderform]].
 *
 * @see Orderform
 */
class OrderformQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Orderform[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Orderform|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
