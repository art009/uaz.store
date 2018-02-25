<?php

namespace frontend\components;

use common\components\AppComponentTrait;
use yii\web\Request;
use yii\web\Session;
use yii\web\User;

/**
 * Trait FrontAppComponentTrait
 *
 * @package frontend\components
 */
trait FrontAppComponentTrait
{
	use AppComponentTrait;

	/**
	 * @return Session
	 */
	public function getSessionComponent(): Session
	{
		return $this->getApp()->getSession();
	}

	/**
	 * @return Request
	 */
	public function getRequestComponent(): Request
	{
		return $this->getApp()->getRequest();
	}

	/**
	 * @return User
	 */
	public function getUserComponent(): User
	{
		return $this->getApp()->getUser();
	}

	/**
	 * @return Cart
	 */
	public function getCartComponent(): Cart
	{
		return $this->getApp()->cart;
	}
}
