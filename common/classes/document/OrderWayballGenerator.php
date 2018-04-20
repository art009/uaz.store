<?php

namespace common\classes\document;


use PHPExcel_Cell;
use PHPExcel_Worksheet;

class OrderWayballGenerator extends AbstractGenerator
{
    /**
     * @var bool
     */
    private $builtTable = false;

    /**
     * Обработка шаблона
     */
    protected function processTemplate()
    {
        $phpExcel = $this->getPhpExcel();
        $sheet = $phpExcel->getActiveSheet();
        $data = $this->getTemplateData();
        $resultData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cellsCoordinate = $this->findCellsCoordinate($sheet, $key);
                if ($cellsCoordinate) {
                    foreach ($cellsCoordinate as $cellIndex => $cellCoordinate) {
                        $this->buildCells($sheet, $cellCoordinate, $cellIndex, $key, $value);
                        foreach ($value as $k => $v) {
                            $resultData[$key . $k . $cellIndex] = $v;
                        }
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
     * @return array
     */
    protected function findCellsCoordinate(PHPExcel_Worksheet $worksheet, string $key)
    {
        $cells = [];
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
                    $cells[] = [$cell->getRow(), $cell->getColumn()];
                }
            }
        }

        return $cells;
    }

    /**
     * @param PHPExcel_Worksheet $worksheet
     * @param array $cellCoordinate
     * @param int $cellIndex
     * @param string $key
     * @param array $value
     * @throws \PHPExcel_Exception
     */
    protected function buildCells(PHPExcel_Worksheet $worksheet, array $cellCoordinate, int $cellIndex, string $key, array $value)
    {
        list($rowIndex, $column) = $cellCoordinate;
        $indices = array_reverse(array_keys($value));
        if (count($value) > 1 && !$this->builtTable) {
            $worksheet->insertNewRowBefore($rowIndex, count($value) - 1);
            $rowIndex += count($value) - 1;
            $this->builtTable = true;
        }
        $cell = $worksheet->getCell($column . $rowIndex);
        $cellValue = trim($cell->getValue());
        $cellCoordinate = $cell->getCoordinate();
        $cellStyle = $worksheet->getStyle($cellCoordinate);
        $coordinate = str_replace($rowIndex, '#', $cellCoordinate);
        foreach ($indices as $index) {
            $newCoordinate = str_replace('#', $rowIndex - 1, $coordinate);
            $cell->setValue(str_replace($key, $key . $index . $cellIndex, $cellValue));
            $rowIndex--;
            $worksheet->getRowDimension($rowIndex)->setRowHeight(-1);
            $worksheet->duplicateStyle($cellStyle, $newCoordinate);
            $cell = $worksheet->getCell($column . $rowIndex);
        }
    }
}