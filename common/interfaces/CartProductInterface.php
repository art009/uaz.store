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
	 * Возвращает название товара
	 *
	 * @return integer
	 */
	public function getTitle();

	/**
	 * Возвращает картинку товара
	 *
	 * @return integer
	 */
	public function getImage();

	/**
	 * Возвращает артикул товара
	 *
	 * @return integer
	 */
	public function getCode();

	/**
	 * Возвращает стоимость позиции
	 *
	 * @return integer
	 */
	public function getTotal();

	/**
	 * Обновление количества товаров
	 *
	 * @param integer $quantity
	 *
	 * @return boolean
	 */
	public function updateQuantity($quantity);

	/**
	 * Удаление товара
	 *
	 * @return boolean
	 */
	public function remove();
}
