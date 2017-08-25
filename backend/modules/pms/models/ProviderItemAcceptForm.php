<?php

namespace backend\modules\pms\models;

use app\modules\pms\models\ProviderItem;
use common\components\AppHelper;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * Class ProviderItemAcceptForm
 *
 * @package backend\modules\pms\models
 */
class ProviderItemAcceptForm extends Model
{

	/**
 	* @var array
 	*/
	public $accept;

	/**
	 * @var array
	 */
	public $ignored;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return parent::rules() + [
				[['accept','ignored'], 'required'],
			];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return parent::attributeLabels() + [
				'accept' => 'Массив подтверждённых товаров',
				'ignored' => 'Массив игнорируемых товаров'
			];
	}
	/**
	 * @param array $items
	 * @param int $providerId
	 * @return int
	 */
	public function acceptUpdate($items, $providerId)
	{
		$date = date('Y-m-d H:i:s');
		$accepted = null;

		$db = \Yii::$app->getDb();
		foreach ($items as $item) {
			$accepted = $db->createCommand()
				->update(ProviderItem::tableName(), ['price' => $item['price'], 'updated_at' => $date], [
					'code' => $item['code'],
					'ignored' => AppHelper::NO,
					'provider_id' => $providerId
				])
				->execute();
		}
		return $accepted;
	}

	/**
	 * @param array $items
	 * @param int $providerId
	 * @return int
	 */
	public function acceptIgnore($items, $providerId)
	{
		$ignored = null;

		foreach ($items as $item) {
			$ignored = ProviderItem::updateAll(['ignored' => AppHelper::YES], ['code' => $item['code'], 'provider_id' => $providerId]);
		}
		return $ignored;
	}
	/**
	 * @param int $providerId
	 * @return ArrayDataProvider
	 */
	public function getDataProvider($providerId){
		$acceptItems = json_decode(\Yii::$app->cache->get('accept' . $providerId), true);
		$dataProvider = new ArrayDataProvider([
			'allModels' => $acceptItems,
			'pagination' => [
				'pageSize' => 10,
			],
		]);
		return $dataProvider;
	}
	/**
	 * @param int $providerId
	 */
	public function clearCache($providerId)
	{
		\Yii::$app->cache->delete('accept' . $providerId);
	}
	/**
	 * @param int $providerId
	 * @return int
	 */
	public function process($providerId)
	{
		$acceptItems = [];
		$ignoreItems = [];

		$items = json_decode(\Yii::$app->cache->get('accept' . $providerId), true);

		foreach ($items as $item) {
			if ($this->accept) {
				foreach ($this->accept as $acceptCode) {
					if ($item['code'] == $acceptCode) {
						$acceptItems[$acceptCode] = $item;
					}
				}
			}
			if ($this->ignored) {
				foreach ($this->ignored as $ignoredCode) {
					if ($item['code'] == $ignoredCode) {
						$ignoreItems[$ignoredCode] = $item;
					}
				}
			}
		}
		$this->acceptUpdate($acceptItems, $providerId);
		$this->acceptIgnore($ignoreItems, $providerId);
		$this->clearCache($providerId);

		return $providerId;
	}
}
