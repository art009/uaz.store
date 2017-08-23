<?php

namespace app\modules\pms\models;

/**
 * This is the ActiveQuery class for [[ProviderItem]].
 *
 * @see ProviderItem
 */
class ProviderItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProviderItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProviderItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
