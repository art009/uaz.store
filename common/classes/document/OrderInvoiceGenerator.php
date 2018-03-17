<?php

namespace common\classes\document;

use PHPExcel_Cell;
use PHPExcel_Worksheet;

/**
 * Class OrderInvoiceGenerator
 *
 * Класс генерации счета
 *
 * @package common\classes\document
 */
class OrderInvoiceGenerator extends AbstractGenerator
{
	/**
	 * @var bool
	 */
	private $builtTable = false;

	/**
	 * @throws \PHPExcel_Exception
	 * @throws \PHPExcel_Reader_Exception
	 */
	protected function processTemplate()
	{
		$phpExcel = $this->getPhpExcel();
		$sheet = $phpExcel->getActiveSheet();
		$data = $this->getTemplateData();
		$resultData = [];
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$cell = $this->findCell($sheet, $key);
				if ($cell) {
					$this->buildCells($sheet, $cell, $key, $value);
					foreach ($value as $k => $v) {
						$resultData[$key . $k] = $v;
					}
				}
			} else {
				$resultData[$key] = $value;
			}
		}

		$this->setTemplateData($resultData);
	}

	/**
	 * @param PHPExcel_Worksheet $worksheet
	 * @param string $key
	 *
	 * @return null|PHPExcel_Cell
	 */
	protected function findCell(PHPExcel_Worksheet $worksheet, string $key)
	{
		$key = self::QUOTE_CHAR . $key . self::QUOTE_CHAR;
		foreach ($worksheet->getRowIterator() as $row) {
			$cellIterator = $row->getCellIterator();
			/* @var $cell PHPExcel_Cell */
			foreach ($cellIterator as $cell) {
				$cellValue = trim($cell->getValue());
				if (!$cellValue) {
					continue;
				}
				if ($cellValue == $key) {
					return $cell;
				}
			}
		}

		return null;
	}

	/**
	 * @param PHPExcel_Worksheet $worksheet
	 * @param PHPExcel_Cell $cell
	 * @param string $key
	 * @param array $value
	 * @throws \PHPExcel_Exception
	 */
	protected function buildCells(PHPExcel_Worksheet $worksheet, PHPExcel_Cell $cell, string $key, array $value)
	{
		$rowIndex = $cell->getRow();
		$column = $cell->getColumn();
		$indices = array_reverse(array_keys($value));
		if (count($value) > 1 && !$this->builtTable) {
			$worksheet->insertNewRowBefore($rowIndex, count($value) - 1);
			$rowIndex += count($value) - 1;
			$this->builtTable = true;
		}
		$cell = $worksheet->getCell($column . $rowIndex);
		$cellValue = trim($cell->getValue());
		$cellRange = $cell->getMergeRange();
		$cellStyle = $worksheet->getStyle($cellRange);
		$range = str_replace($rowIndex, '#', $cellRange);
		foreach ($indices as $index) {
			$mergeRange = str_replace('#', $rowIndex - 1, $range);
			$worksheet->mergeCells($mergeRange);
			$cell->setValue(str_replace($key, $key . $index, $cellValue));
			$rowIndex--;
			$worksheet->getRowDimension($rowIndex)->setRowHeight(-1);
			$worksheet->duplicateStyle($cellStyle, $mergeRange);
			$cell = $worksheet->getCell($column . $rowIndex);
		}
	}
}
