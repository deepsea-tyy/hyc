<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[OrderformRemark]].
 *
 * @see OrderformRemark
 */
class OrderformRemarkQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return OrderformRemark[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return OrderformRemark|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
