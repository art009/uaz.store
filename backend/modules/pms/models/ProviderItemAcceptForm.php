<?php

namespace backend\modules\pms\models;

use app\modules\pms\models\Provider;
use app\modules\pms\models\ProviderItem;
use backend\modules\pms\components\ProviderItemAcceptCache;
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
	 * @var int
	 */
	public $providerId;

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
		return [
			[['accept','ignored'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'accept' => 'Массив подтверждённых товаров',
			'ignored' => 'Массив игнорируемых товаров'
		];
	}

	/**
	 * @return ProviderItemAcceptCache
	 */
	protected function getAcceptCache()
	{
		return new ProviderItemAcceptCache($this->providerId);
	}

	/**
	 * Данные из кеша для текущего поставщика
	 *
	 * @return array
	 */
	protected function getData(): array
	{
		return $this->getAcceptCache()->get();
	}

	/**
	 * @param array $items
	 *
	 * @return int
	 */
	protected function acceptUpdate(array $items): int
	{
		$accepted = 0;
		$date = date('Y-m-d H:i:s');
		$db = \Yii::$app->getDb();
		foreach ($items as $item) {
			$accepted += $db->createCommand()
				->update(ProviderItem::tableName(), ['price' => $item['price'], 'updated_at' => $date], [
					'code' => $item['code'],
					'ignored' => AppHelper::NO,
					'provider_id' => $this->providerId
				])
				->execute();
		}
		return $accepted;
	}

	/**
	 * @param array $items
	 * @return int
	 */
	protected function acceptIgnore(array $items): int
	{
		$ignored = 0;
		foreach ($items as $item) {
			$ignored += ProviderItem::updateAll(['ignored' => AppHelper::YES], [
				'code' => $item['code'], 'provider_id' => $this->providerId
			]);
		}

		return $ignored;
	}

	/**
	 * @return ArrayDataProvider
	 */
	public function getDataProvider()
	{
		$dataProvider = new ArrayDataProvider([
			'allModels' => $this->getData(),
			'pagination' => false,
		]);

		return $dataProvider;
	}

	/**
	 * @param null $attributeNames
	 * @param bool $clearErrors
	 *
	 * @return bool
	 */
	public function validate($attributeNames = null, $clearErrors = true)
	{
		if (Provider::findOne($this->providerId) === null) {
			$this->addError('accept', 'Некорректный поставщик');
		}

		if (empty($this->getData())) {
			$this->addError('accept', 'Нет данных для указанного поставщика');
		}

		if (!is_array($this->accept)) {
			$this->accept = [];
		}
		if (!is_array($this->ignored)) {
			$this->ignored = [];
		}

		if (empty($this->accept) && empty($this->ignored)) {
			$this->addError('accept', 'Не выбрано ни одного действия');
		}

		return parent::validate($attributeNames, $clearErrors);
	}

	/**
	 * @return bool
	 */
	public function process()
	{
		if ($this->validate()) {
			$items = $this->getData();
			$acceptItems = $ignoreItems = [];
			foreach ($items as $item) {
				if (in_array($item['code'], $this->accept)) {
					$acceptItems[] = $item;
				}
				if (in_array($item['code'], $this->ignored)) {
					$ignoreItems[] = $item;
				}
			}
			$updated = $this->acceptUpdate($acceptItems);
			if ($updated) {
				\Yii::$app->session->addFlash('success', 'Обновлено позиций: ' . $updated);
			}
			$ignored = $this->acceptIgnore($ignoreItems);
			if ($ignored) {
				\Yii::$app->session->addFlash('info', 'Добавлено в игнор: ' . $ignored);
			}

			$this->getAcceptCache()->clear();

			return true;
		} else {
			foreach ($this->errors as $attribute => $errors) {
				foreach ($errors as $error) {
					\Yii::$app->session->addFlash('warning', $error);
				}
			}

			return false;
		}
	}
}
