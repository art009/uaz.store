<?php

namespace common\models;

use common\classes\OrderStatusWorkflow;
use Yii;
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
	const STATUS_CART = 0;
	const STATUS_CART_CLEAR = 1;

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
            'user_id' => 'ID Пользователя',
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
}
