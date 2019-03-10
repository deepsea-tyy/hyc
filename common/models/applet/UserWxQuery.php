<?php

namespace common\models\applet;

/**
 * This is the ActiveQuery class for [[UserWx]].
 *
 * @see UserWx
 */
class UserWxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserWx[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserWx|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
