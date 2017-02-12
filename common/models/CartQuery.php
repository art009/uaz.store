<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Cart]].
 *
 * @see Cart
 */
class CartQuery extends \yii\db\ActiveQuery
{
	/**
	 * Отбор по идентификатору сущности
	 *
	 * @param string $identityId
	 * @return $this
	 */
    public function byIdentityId($identityId)
    {
        return $this->andWhere([
        	'identity_id' => $identityId,
		]);
    }

	/**
	 * Отбор по идентификатору товара
	 *
	 * @param integer $productId
	 * @return $this
	 */
    public function byProductId($productId)
    {
        return $this->andWhere([
        	'product_id' => $productId,
		]);
    }

    /**
     * @inheritdoc
     * @return Cart[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Cart|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
