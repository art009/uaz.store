<?php

namespace app\modules\pms\models;

/**
 * This is the ActiveQuery class for [[ShopItem]].
 *
 * @see ShopItem
 */
class ShopItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ShopItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ShopItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
