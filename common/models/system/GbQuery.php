<?php

namespace common\models\system;

/**
 * This is the ActiveQuery class for [[Gb]].
 *
 * @see Gb
 */
class GbQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Gb[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Gb|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
