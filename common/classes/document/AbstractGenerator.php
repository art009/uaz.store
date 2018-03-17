<?php

namespace common\classes\document;

use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel5;

/**
 * Class AbstractGenerator
 *
 * @package common\classes\document
 */
abstract class AbstractGenerator implements GeneratorInterface
{
	const QUOTE_CHAR = '`';

	/**
	 * @var string
	 */
	protected $filePath;

	/**
	 * @var string
	 */
	protected $templatePath;

	/**
	 * @var array
	 */
	protected $templateData;

	/**
	 * @var PHPExcel
	 */
	protected $phpExcel = null;

	/**
	 * Обработка шаблона
	 */
	abstract protected function processTemplate();

	/**
	 * @return string
	 */
	public function getFilePath(): string
	{
		return $this->filePath;
	}

	/**
	 * @param string $path
	 */
	public function setFilePath(string $path)
	{
		$this->filePath = $path;
	}

	/**
	 * @return string
	 */
	public function getTemplatePath(): string
	{
		return $this->templatePath;
	}

	/**
	 * @param string $path
	 */
	public function setTemplatePath(string $path)
	{
		$this->templatePath = $path;
	}

	/**
	 * @return array
	 */
	public function getTemplateData(): array
	{
		return $this->templateData;
	}

	/**
	 * @param array $data
	 */
	public function setTemplateData(array $data)
	{
		$this->templateData = $data;
	}

	/**
	 * @return PHPExcel
	 *
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 */
	public function getPhpExcel(): PHPExcel
	{
		if ($this->phpExcel === null) {
			$this->phpExcel = PHPExcel_IOFactory::load($this->getTemplatePath());
			$this->phpExcel->setActiveSheetIndex(0);
		}

		return $this->phpExcel;
	}

	/**
	 * @return bool
	 *
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 */
	public function generate(): bool
	{
		$this->processTemplate();
		$this->processData();

		return $this->saveFile();
	}

	/**
	 * Подстановка данных в шаблон
	 *
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 */
	protected function processData()
	{
		$phpExcel = $this->getPhpExcel();
		$sheet = $phpExcel->getActiveSheet();
		$data = $this->getFormattedTemplateData();
		foreach ($sheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			/* @var $cell PHPExcel_Cell */
			foreach ($cellIterator as $cell) {
				$cellValue = trim($cell->getValue());
				if (!$cellValue) {
					continue;
				}
				$value = strtr($cellValue, $data);
				if ($value != $cellValue) {
					$cell->setValue($value);
				}
			}
		}
	}

	/**
	 * Подставляется экранирование и проверяется тип
	 *
	 * @return array
	 */
	protected function getFormattedTemplateData(): array
	{
		$result = [];
		$data = $this->getTemplateData();
		foreach ($data as $key => $value) {
			$key = self::QUOTE_CHAR . $key . self::QUOTE_CHAR;
			if (is_string($value) || is_numeric($value)) {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	/**
	 * @return bool
	 *
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 * @throws \PHPExcel_Writer_Exception
	 */
	protected function saveFile(): bool
	{
		$phpExcel = $this->getPhpExcel();
		$filePath = $this->getFilePath();

		$objWriter = new PHPExcel_Writer_Excel5($phpExcel);
		$objWriter->save($filePath);

		$phpExcel->disconnectWorksheets();

		return file_exists($filePath);
	}
}
