<?php

namespace app\modules\pms\models;

/**
 * This is the ActiveQuery class for [[ProviderShopItem]].
 *
 * @see ProviderShopItem
 */
class ProviderShopItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProviderShopItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProviderShopItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
