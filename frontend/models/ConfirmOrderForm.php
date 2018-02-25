<?php

namespace frontend\models;

use common\models\Order;
use common\models\User;
use yii\base\Model;

/**
 * Class ConfirmOrderForm
 *
 * @package frontend\models
 */
class ConfirmOrderForm extends Model
{
	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var int
	 */
	public $delivery;

	/**
	 * @var int
	 */
	public $payment;

	/**
	 * @var Order
	 */
	protected $order;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @return Order
	 */
	protected function getOrder(): Order
	{
		return $this->order;
	}

	/**
	 * @param Order $order
	 */
	protected function setOrder(Order $order)
	{
		$this->order = $order;
	}

	/**
	 * @return User
	 */
	protected function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	protected function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		$user = $this->getUser();
		$this->name = $user->name;
		$this->phone = $user->phone;
		$this->email = $user->email;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'name', 'email', 'delivery', 'payment'], 'required'],
			[['phone', 'name', 'email'], 'trim'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['name', 'string', 'min' => 2, 'max' => 255],
			['name', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'phone' => 'Телефон',
			'name' => 'Контактное лицо',
			'email' => 'E-mail',
			'delivery' => 'Способ доставки',
			'payment' => 'Вариант оплаты',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterValidate()
	{
		if (mb_strlen(preg_replace('/[^0-9]/', '', $this->phone)) < 11) {
			$this->addError('phone', 'Необходим 10-значный номер телефона.');
		}

		$phone = $this->getPhoneNumber();
		$user = User::findOne(['phone' => $phone]);
		if ($user && $user->getId() != $this->getUser()->getId()) {
			$this->addError('phone', 'Введенный номер телефона занят.');
		}

		$user = User::findOne(['email' => $this->email]);
		if ($user && $user->getId() != $this->getUser()->getId()) {
			$this->addError('email', 'Введенный E-mail занят.');
		}

		return parent::afterValidate();
	}

	/**
	 * @return string
	 */
	protected function getPhoneNumber(): string
	{
		return mb_substr(preg_replace('/[^0-9]/', '', $this->phone), -10);
	}

	/**
	 * @return bool
	 */
	protected function checkUser(): bool
	{
		$user = $this->getUser();

		return $this->name == $user->name && $this->getPhoneNumber() == $user->phone && $this->email == $user->email;
	}

	/**
	 * @return bool
	 */
	protected function updateUser(): bool
	{
		$user = $this->getUser();

		return (bool)$user->updateAttributes([
			'name' => $this->name,
			'phone' => $this->getPhoneNumber(),
			'email' => $this->email,
		]);
	}

	/**
	 * @return bool
	 */
	protected function checkOrder(): bool
	{
		$order = $this->getOrder();

		return $this->delivery == $order->delivery_type && $this->payment == $order->payment_type;
	}

	/**
	 * @return bool
	 */
	protected function updateOrder(): bool
	{
		$order = $this->getOrder();

		return (bool)$order->updateAttributes([
			'delivery_type' => $this->delivery,
			'payment_type' => $this->payment,
		]);
	}

	/**
	 * @return bool
	 */
	public function confirm(): bool
	{
		if (!$this->validate()) {
			return false;
		}

		return ($this->checkUser() || $this->updateUser()) && ($this->checkOrder() || $this->updateOrder());
	}
}
