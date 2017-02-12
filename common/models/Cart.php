<?php

namespace common\models;

use common\interfaces\CartProductInterface;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cart".
 *
 * @property string $identity_id
 * @property integer $product_id
 * @property integer $quantity
 *
 * @property CatalogProduct $product
 */
class Cart extends \yii\db\ActiveRecord implements CartProductInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['identity_id', 'product_id'], 'required'],
            [['product_id', 'quantity'], 'integer'],
            [['identity_id'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatalogProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'identity_id' => 'ID корзины',
            'product_id' => 'ID товара',
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
     * @inheritdoc
     * @return CartQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CartQuery(get_called_class());
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
		return $this->product ? $this->product->price : 0;
	}

	/**
	 * Обновление количества
	 *
	 * @param int $quantity
	 *
	 * @return int
	 */
	public function updateQuantity($quantity)
	{
		$this->quantity = (int)$quantity;

		return (int)$this->update();
	}
}
