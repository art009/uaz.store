<?php

namespace common\interfaces;

/**
 * Interface CartProductInterface
 * @package common\interfaces
 */
interface CartProductInterface
{
	/**
	 * Возвращает идентификатор товара
	 *
	 * @return integer
	 */
	public function getProductId();

	/**
	 * Возвращает количество
	 *
	 * @return integer
	 */
	public function getQuantity();

	/**
	 * Возвращает стоимость товара
	 *
	 * @return integer
	 */
	public function getPrice();

	/**
	 * Обновление количества товаров
	 *
	 * @param integer $quantity
	 *
	 * @return boolean
	 */
	public function updateQuantity($quantity);
}
