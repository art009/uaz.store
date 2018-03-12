<?php

namespace common\classes\document;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Writer_Excel5;

/**
 * Class AbstractGenerator
 *
 * @package common\classes\document
 */
abstract class AbstractGenerator implements GeneratorInterface
{
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
	 * Подстановка данных в шаблок
	 */
	abstract protected function process();

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
		$this->process();

		return $this->saveFile();
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
