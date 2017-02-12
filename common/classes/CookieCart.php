<?php

namespace common\classes;

use Yii;
use yii\web\Cookie;

/**
 * Class CookieCart
 * 
 * @package common\classes
 */
class CookieCart extends SessionCart
{
	/**
	 * Ключ идентификатора в куках
	 */
	const COOKIE_IDENTITY_KEY = 'ccik';

	/**
	 * @return string
	 */
	public function findIdentityId()
	{
		return Yii::$app->request->cookies->getValue(self::COOKIE_IDENTITY_KEY);
	}

	/**
	 * Обновление сущности корзины в куках
	 *
	 * @param string $identityId
	 */
	public static function updateIdentityId($identityId)
	{
		Yii::$app->response->cookies->add(new Cookie([
			'name' => self::COOKIE_IDENTITY_KEY,
			'value' => $identityId,
		]));
	}
}
