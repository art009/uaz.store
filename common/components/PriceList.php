<?php

namespace common\components;

use Yii;
use common\models\CatalogProduct;

/**
 * Class PriceList
 *
 * @package common\components
 */
class PriceList
{
	/**
	 * Возвращает путь к файлу с именем
	 *
	 * @return string
	 */
	public static function filename()
	{
		return Yii::getAlias('@console') . '/runtime/price.xls';
	}

	/**
	 * Проверяет наличие файла и время изменения
	 */
	public static function check()
	{
		$filename = self::filename();

		return file_exists($filename) && filemtime($filename) > strtotime(date('Y-m-d 00:00:00'));
	}

	/**
	 * Выполняет проверку и обновляет файл
	 *
	 * @return bool
	 */
	public static function execute()
	{
		return self::check() || self::generate();
	}

	/**
	 * Генерация файла
	 *
	 * @return bool
	 */
	public static function generate()
	{
		$result = false;

		try {
			$data = CatalogProduct::find()
				->select(['title', 'price'])
				->asArray()
				->all();

			if ($data) {
				$objPHPExcel = new \PHPExcel();
				$objPHPExcel->getProperties()
					->setCreator("uaz.store")
					->setTitle("Price list");

				$sheet = $objPHPExcel->setActiveSheetIndex(0);
				$sheet->setTitle('Прайс-лист от ' . date('d.m.Y'));
				$sheet->setSelectedCell('A1');
				$sheet->fromArray($data, null, 'A1');

				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save(self::filename());

				$result =  true;
			}
		} catch (\Exception $e) {
			$result = false;
		}

		return $result;
	}

	/**
	 * Удаление прайс-листа
	 *
	 * @return bool
	 */
	public static function remove()
	{
		return @unlink(self::filename());
	}

	/**
	 * Возвращает время изменения файла по заданному формату
	 *
	 * @param string $format
	 *
	 * @return string|null
	 */
	public static function date($format = 'd.m.Y')
	{
		$filename = self::filename();
		if (file_exists($filename)) {
			return date($format, filemtime($filename));
		} else {
			return null;
		}
	}
}
