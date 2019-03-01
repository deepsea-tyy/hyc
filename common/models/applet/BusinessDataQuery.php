<?php

namespace common\models\applet;

/**
 * This is the ActiveQuery class for [[BusinessData]].
 *
 * @see BusinessData
 */
class BusinessDataQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return BusinessData[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return BusinessData|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
