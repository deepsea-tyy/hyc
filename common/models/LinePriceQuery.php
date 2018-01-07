<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[LinePrice]].
 *
 * @see LinePrice
 */
class LinePriceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return LinePrice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LinePrice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
