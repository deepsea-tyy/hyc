<?php

namespace common\models\goods;

/**
 * This is the ActiveQuery class for [[Goods]].
 *
 * @see Goods
 */
class GooQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Goods[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Goods|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
