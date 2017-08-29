<?php

namespace app\modules\pms\models;

use common\components\AppHelper;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "shop_item".
 *
 * @property integer $id
 * @property string $code
 * @property string $vendor_code
 * @property string $title
 * @property float $price
 * @property float $percent
 * @property float $site_price
 * @property string $unit
 * @property integer $ignored
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ProviderItem[] $providerItems
*/
class ShopItem extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 0;
	const STATUS_IGNORE = 1;
	const STATUS_PROFIT = 2;
	const STATUS_LOST = 3;
	const STATUS_WITHOUT_RELATION = 4;
	const STATUS_WITHOUT_RELATION_AND_NOT_IGNORED = 5;

	/**
	 * @var array
	 */
	public static $statusList = [
		self::STATUS_ACTIVE => 'Есть связь + Нет пропуска + Цена для сайта больше 0 (Актив)',
		self::STATUS_IGNORE => 'Есть связь + Пропуск (Игнор)',
		self::STATUS_PROFIT => 'Цена для сайта > цены магазина (Выгода)',
		self::STATUS_LOST => 'Есть связь + Цена для сайта равна 0 (Потеря)',
		self::STATUS_WITHOUT_RELATION => 'Нет связи + пропуск',
		self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED => 'Нет связи + нет пропуска',
	];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['price', 'percent', 'site_price'], 'number'],
            [['ignored'], 'boolean'],
            [['created_at', 'updated_at', 'status'], 'safe'],
            [['code', 'vendor_code', 'title', 'unit'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'vendor_code' => 'Артикул',
            'title' => 'Название',
            'price' => 'Цена',
            'percent' => 'Процент накрутки',
            'site_price' => 'Цена расчитанная',
            'unit' => 'Единица измерения',
            'ignored' => 'Пропуск обновления',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
            'status' => 'Статус',
            'statusLabel' => 'Статус',
        ];
    }

    /**
     * @inheritdoc
     * @return ShopItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShopItemQuery(get_called_class());
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
	 * @return int
	 */
	public function getStatus()
	{
		if ($this->providerItems) {
			if ($this->ignored == AppHelper::YES) {
				$result = self::STATUS_IGNORE;
			} elseif ($this->site_price == 0) {
				$result = self::STATUS_LOST;
			} elseif ($this->site_price > $this->price) {
				$result = self::STATUS_PROFIT;
			} else {
				$result = self::STATUS_ACTIVE;
			}
		} else {
			if ($this->ignored == AppHelper::YES) {
				$result = self::STATUS_WITHOUT_RELATION;
			} else {
				$result = self::STATUS_WITHOUT_RELATION_AND_NOT_IGNORED;
			}
		}

		return $result;
	}

	/**
	 * Название статуса
	 *
	 * @return string
	 */
	public function getStatusLabel()
	{
		return self::$statusList[$this->getStatus()] ?? 'Неизвестен';
	}
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getProviderItems()
	{
		return $this->hasMany(ProviderItem::className(), ['id' => 'provider_item_id'])->viaTable('provider_item_to_shop_item', ['shop_item_id' => 'id']);
	}
}
