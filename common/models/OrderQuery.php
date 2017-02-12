<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Order]].
 *
 * @see Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
	/**
	 * Поиск по статусу
	 *
	 * @param int|int[] $status
	 *
	 * @return $this
	 */
    public function byStatus($status)
    {
        return $this->andWhere(['status' => $status]);
    }

	/**
	 * Поиск по идентификатору пользователя
	 *
	 * @param integer $userId
	 *
	 * @return $this
	 */
    public function byUserId($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * @inheritdoc
     * @return Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
