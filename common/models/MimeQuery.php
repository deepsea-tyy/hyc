<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Mime]].
 *
 * @see Mime
 */
class MimeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Mime[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Mime|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
