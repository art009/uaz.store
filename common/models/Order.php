<?php

namespace common\models;

use common\classes\document\OrderManager;
use common\classes\OrderStatusWorkflow;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property integer $user_id
 * @property integer $status
 * @property float $sum
 * @property string $delivery_sum
 * @property integer $delivery_type
 * @property integer $payment_type
 * @property string $changed_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
	// Статусы
	const STATUS_CART 				= 0;
	const STATUS_CART_CLEAR 		= 1;
	const STATUS_PICKUP				= 2;
	const STATUS_PROCESS			= 3;
	const STATUS_PAYMENT_WAITING	= 4;
	const STATUS_PAYMENT_DONE 		= 5;
	const STATUS_PAYMENT_PROCESS	= 6;
	const STATUS_GATHERING 			= 7;
	const STATUS_SENDING 			= 8;
	const STATUS_DONE 				= 9;
	const STATUS_TRANSFER			= 10;
	const STATUS_REJECT 			= 11;

	// Способы доставки
	const DELIVERY_NONE 			= 0;
	const DELIVERY_PICKUP			= 1;
	const DELIVERY_RUSSIA_POST		= 2;
	const DELIVERY_EMS_POST		    = 3;
	const DELIVERY_BUSINESS_LINES   = 4;
	const DELIVERY_PEK              = 5;
	const DELIVERY_KIT              = 6;
	const DELIVERY_BAIKAL_SERVICE   = 7;

	// Способы оплаты
	const PAYMENT_NONE	    = 0;
	const PAYMENT_CARD	    = 1;
	const PAYMENT_NON_CASH	= 2;
	const PAYMENT_POD	    = 3;
	const PAYMENT_SBOL	    = 4;
	const PAYMENT_QIWI	    = 5;
	const PAYMENT_YA_MONEY  = 6;
	const PAYMENT_CASH	    = 7;

	/**
	 * Список статусов
	 *
	 * @var array
	 */
	static $statusList = [
		self::STATUS_CART 				=> 'Корзина',
		self::STATUS_CART_CLEAR 		=> 'Очищенная корзина',
		self::STATUS_PICKUP 			=> 'Самовывоз',
		self::STATUS_PROCESS 			=> 'Обработка',
		self::STATUS_PAYMENT_WAITING	=> 'Ожидание оплаты',
		self::STATUS_PAYMENT_DONE 	 	=> 'Оплачено покупателем',
		self::STATUS_PAYMENT_PROCESS 	=> 'Поступление средств',
		self::STATUS_GATHERING 			=> 'Сбор',
		self::STATUS_SENDING 			=> 'Отправка',
		self::STATUS_DONE 				=> 'Завершен',
		self::STATUS_TRANSFER 			=> 'Переведен в магазин',
		self::STATUS_REJECT 			=> 'Отказ',
	];

	/**
	 * Список способов доставки
	 *
	 * @var array
	 */
	static $deliveryList = [
		self::DELIVERY_PICKUP 			=> 'Самовывоз',
		self::DELIVERY_RUSSIA_POST 		=> 'Почта России',
		self::DELIVERY_EMS_POST         => 'EMS Почта',
		self::DELIVERY_BUSINESS_LINES   => 'Деловые линии',
		self::DELIVERY_PEK              => 'ПЭК',
		self::DELIVERY_KIT              => 'Кит',
		self::DELIVERY_BAIKAL_SERVICE   => 'Байкал Сервис',
	];

	/**
	 * Список способов оплаты
	 *
	 * @var array
	 */
	static $paymentList = [
		self::PAYMENT_CARD 	    => 'Карты Visa, MasterCard, МИР',
		self::PAYMENT_NON_CASH 	=> 'Безналичный расчет',
		self::PAYMENT_POD	    => 'Наложенный платеж',
		self::PAYMENT_SBOL	    => 'Сбербанк Онлайн',
		self::PAYMENT_QIWI	    => 'QIWI-кошелек',
		self::PAYMENT_YA_MONEY  => 'Яндекс деньги',
		self::PAYMENT_CASH  	=> 'Наличные деньги',
	];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'delivery_type', 'payment_type'], 'integer'],
            [['sum', 'delivery_sum'], 'number'],
            [['changed_at', 'created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'status' => 'Статус',
            'sum' => 'Стоимость',
            'delivery_sum' => 'Стоимость доставки',
            'delivery_type' => 'Способ доставки',
            'payment_type' => 'Метод оплаты',
            'changed_at' => 'Время изменения статуса',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'value' => date('Y-m-d H:i:s'),
			],
		];
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			if ($this->isAttributeChanged('status')) {
				$this->changed_at = date('Y-m-d H:i:s');
			}
		}

		return true;
	}

	/**
	 * @param bool $insert
	 *
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * @return OrderStatusWorkflow
	 */
	public function getWorkflow()
	{
		return new OrderStatusWorkflow($this);
	}

	/**
	 * Обновление значения суммы заказа
	 *
	 * @param bool $save
	 */
	public function updateSum($save = false)
	{
		$this->sum = 0;
		/* @var $orderProducts OrderProduct[] */
		$orderProducts = $this->getOrderProducts()->all();
		foreach ($orderProducts as $orderProduct) {
			$this->sum += round($orderProduct->price * $orderProduct->quantity, 2);
		}
		if ($save) {
			$this->update();
		}
	}

	/**
	 * Обновление цен товаров
	 */
	public function updateProductsPrices()
	{
		/* @var $orderProducts OrderProduct[] */
		$orderProducts = $this
			->getOrderProducts()
			->joinWith(['product'])
			->all();

		foreach ($orderProducts as $orderProduct) {
			$orderProduct->updatePrice();
			$orderProduct->update();
		}
	}

	/**
	 * Поиск созданного заказа или создание нового
	 *
	 * @param integer $userId
	 * @param bool|true $force
	 * @param bool|true $joinProducts
	 * @return Order|null
	 */
	public static function create($userId, $force = true, $joinProducts = true)
	{
		$query = self::find()
			->byStatus([self::STATUS_CART, self::STATUS_CART_CLEAR])
			->byUserId($userId);

		if ($joinProducts) {
			$query->joinWith(['orderProducts']);
		}

		$order = $query->one();

		if ($order === null && $force == true) {
			$order = new self();
			$order->user_id = $userId;
			$order->status = self::STATUS_CART;
			$order = $order->save() ? $order : null;
		}

		return $order;
	}

	/**
	 * Возвращает название статуса
	 *
	 * @param int $status
	 *
	 * @return string|null
	 */
	public static function statusName($status)
	{
		return array_key_exists($status, self::$statusList) ? self::$statusList[$status] : null;
	}

	/**
	 * Возвращает название статуса для текущего заказа
	 *
	 * @return string|null
	 */
	public function getStatusName()
	{
		return self::statusName($this->status);
	}

	/**
	 * Возвращает список доступных статусов
	 *
	 * @return array
	 */
	public function getAllowedStatusList()
	{
		$result = [];
		$workflowStatusList = OrderStatusWorkflow::statusList($this->status);
		foreach ($workflowStatusList as $status) {
			$result[$status] = self::statusName($status);
		}

		return $result;
	}

	/**
	 * @return float
	 */
	public function getTotal()
	{
		return (float)$this->sum + (float)$this->delivery_sum;
	}

	/**
	 * @return bool
	 */
	public function confirm(): bool
	{
		return $this->updateAttributes(['status' => self::STATUS_PROCESS]) || $this->status == self::STATUS_PROCESS;
	}

	/**
	 * @return OrderManager
	 */
	public function getDocumentManager(): OrderManager
	{
		return new OrderManager($this);
	}
}
