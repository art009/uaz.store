<?php

namespace common\models;

use common\components\AppHelper;
use common\interfaces\CartProductInterface;

/**
 * This is the model class for table "order_product".
 *
 * @property string $order_id
 * @property integer $product_id
 * @property float $price
 * @property integer $quantity
 *
 * @property CatalogProduct $product
 * @property Order $order
 */
class OrderProduct extends \yii\db\ActiveRecord implements CartProductInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['order_id', 'product_id'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID заказа',
            'product_id' => 'ID товара',
            'price' => 'Стоимость товара',
            'quantity' => 'Количество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderProductQuery(get_called_class());
    }

	/**
	 * @inheritdoc
	 */
	public function getProductId()
	{
		return $this->product_id;
	}

	/**
	 * @inheritdoc
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

	/**
	 * @inheritdoc
	 */
	public function getPrice()
	{
		return $this->price;
	}

    /**
     * @inheritdoc
     */
    public function getDiscountPrice()
    {
        $discountItem = round($this->price * ( $this->order->sale_percent / 100), 2, PHP_ROUND_HALF_DOWN);
        return $this->price - $discountItem;
    }

	/**
	 * Обновление количества
	 *
	 * @param int $quantity
	 *
	 * @return bool|int
	 *
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateQuantity($quantity)
	{
		$this->quantity = (int)$quantity;

		return (int)$this->update();
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		$result = false;
		if (parent::beforeSave($insert)) {
			if ($this->isNewRecord) {
				/* @var $product CatalogProduct */
				$product = $this->getProduct()->one();
				if ($product) {
					$this->price = $product->price;
				}
			}

			$result = true;
		}
		return $result;
	}

	/**
	 * Обновление стоимости товара
	 */
	public function updatePrice()
	{
		if ($this->product) {
			$this->price = $this->product->price;
		}
	}

	/**
	 * @param bool $insert
	 *
	 * @param array $changedAttributes
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		$this->order->updateSum(true);
	}

	/**
	 * После удаления
	 */
	public function afterDelete()
	{
		$this->order->updateSum(true);

		parent::afterDelete();
	}

	/**
	 * @inheritdoc
	 */
	public function getTitle()
	{
		return $this->product ? $this->product->title : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getImage()
	{
		if ($this->product && $this->product->image) {
			return AppHelper::uploadsPath() . '/' . CatalogProduct::FOLDER_MEDIUM . '/' . $this->product->image;
		} else {
			return null;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getCode()
	{
		return $this->product ? $this->product->id : null;
	}

	/**
	 * @inheritdoc
	 */
	public function getTotal()
	{
		return round($this->getPrice() * $this->getQuantity(), 2);
	}

	/**
	 * @inheritdoc
	 */
	public function remove()
	{
		return $this->delete();
	}
}
