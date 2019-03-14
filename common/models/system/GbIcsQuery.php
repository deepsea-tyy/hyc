<?php

namespace common\models\system;

/**
 * This is the ActiveQuery class for [[GbIcs]].
 *
 * @see GbIcs
 */
class GbIcsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return GbIcs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GbIcs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
