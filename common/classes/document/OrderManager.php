<?php

namespace common\classes\document;

use Yii;

/**
 * Class OrderManager
 *
 * Класс управляющий генерацией документов
 *
 * @package common\classes\document
 */
class OrderManager extends OrderObject
{
	const TYPE_INDIVIDUAL_USER_INVOICE = 'iu_invoice';

	const TYPE_LEGAL_USER_INVOICE = 'lu_invoice';
	const TYPE_LEGAL_USER_SPECIFICATION_FOR_CONTRACT = 'lu_specification_for_contract';
	const TYPE_LEGAL_USER_ACCOUNT = 'lu_account';
	const TYPE_LEGAL_USER_WAYBILL = 'lu_waybill';

	const TEMPLATE_FILE_PATH = '@common/classes/document/templates/';
	const RESULT_FILE_PATH = '@frontend/web/uploads/user/document/';

	/**
	 * @return array
	 */
	protected function getGeneratorMap(): array
	{
		if ($this->isUserLegal()) {
			return [
			    self::TYPE_LEGAL_USER_INVOICE => OrderInvoiceGenerator::class,
                self::TYPE_LEGAL_USER_SPECIFICATION_FOR_CONTRACT => OrderInvoiceGenerator::class,
                self::TYPE_LEGAL_USER_WAYBILL => OrderWayballGenerator::class,
                self::TYPE_LEGAL_USER_ACCOUNT => OrderWayballGenerator::class,
            ]; // TODO Тут добавятся генераторы доков юр лица
		}

		return [
			self::TYPE_INDIVIDUAL_USER_INVOICE => OrderInvoiceGenerator::class,
		];
	}

	/**
	 * @return array
	 */
	public function getDocumentList(): array
	{
		if ($this->isUserLegal()) {
			return [
			    self::TYPE_LEGAL_USER_INVOICE => 'Счет на юр лицо',
                self::TYPE_LEGAL_USER_SPECIFICATION_FOR_CONTRACT => 'Специф к дог на юр лицо',
                self::TYPE_LEGAL_USER_WAYBILL => 'Накладная на юр лицо',
                self::TYPE_LEGAL_USER_ACCOUNT => 'Счет-фактура на юр лицо',
            ]; // TODO Тут добавятся доки юр лица
		}

		return [
			self::TYPE_INDIVIDUAL_USER_INVOICE => 'Счет на физ лицо',
		];
	}

	/**
	 * @param string $type
	 *
	 * @return array
	 */
	public function checkDocument(string $type): array
	{
		$result = [];
		$map = $this->getGeneratorMap();
		if (!array_key_exists($type, $map)) {
			$result['error'][] = 'Неизвестный тип документа';
		}
		$orderData = $this->getOrderData();
		$dataErrors = $orderData->validate();
		foreach ($dataErrors as $error) {
			$result['warning'][] = $error;
		}

		return $result;
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	public function getDocumentPath(string $type): string
	{
		$filePath = $this->getFilePath($type);
		if (file_exists($filePath)) {
			return $filePath;
		}

		return $this->generateDocument($type);
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function getFilePath(string $type): string
	{
		return Yii::getAlias(self::RESULT_FILE_PATH) . $this->getFileName($type);
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function getTemplatePath(string $type): string
	{
		return Yii::getAlias(self::TEMPLATE_FILE_PATH) . $type . '.xls';
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function generateDocument(string $type): string
	{
		$filePath = $this->getFilePath($type);
		if ($this->checkGenerator($type)) {
			$generator = $this->initGenerator($type);
			if ($generator->generate()) {
				return $filePath;
			}
		}

		return '';
	}

	/**
	 * @param string $type
	 *
	 * @return bool
	 */
	protected function checkGenerator(string $type): bool
	{
		$map = $this->getGeneratorMap();

		return array_key_exists($type, $map);
	}

	/**
	 * @param string $type
	 *
	 * @return GeneratorInterface
	 */
	protected function initGenerator(string $type): GeneratorInterface
	{
		$map = $this->getGeneratorMap();

		/* @var $generator GeneratorInterface */
		$generator = new $map[$type];
		$generator->setFilePath($this->getFilePath($type));
		$generator->setTemplatePath($this->getTemplatePath($type));
		$generator->setTemplateData($this->getOrderData()->toArray());

		return $generator;
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	protected function getFileName(string $type): string
	{
		if ($this->checkGenerator($type)) {
			return implode('_', [$type, $this->getUserId(), $this->getOrderId()]) . '.xls';
		}

		return '';
	}

	/**
	 * @param string $type
	 *
	 * @return string
	 */
	public function getDocumentLabel(string $type): string
	{
		$list = $this->getDocumentList();
		$name = $list[$type] ?? 'Документ';

		return $name . ' для заказа № ' . $this->getOrderId() . ' от ' . $this->getOrderDate() . '.xls';
	}

	/**
	 * @return OrderData
	 */
	protected function getOrderData(): OrderData
	{
		return new OrderData($this->getOrder());
	}
}
