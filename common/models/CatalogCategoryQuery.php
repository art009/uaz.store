<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CatalogCategory]].
 *
 * @see CatalogCategory
 */
class CatalogCategoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return CatalogCategory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return CatalogCategory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
