<?php

namespace common\components\document\classes;

use PHPExcel_Worksheet;

class GenerateXlsByArrayData
{
    const SCREEN = '%';

    /**
     * @var array
     */
    protected static $masks;

    /**
     * @var array
     */
    protected static $data;

    /**
     * @var string
     */
    protected static $prefixKey = self::SCREEN;

    /**
     * @var string
     */
    protected static $posfixKey = self::SCREEN;

    /**
     * Private constructor for GenerateXlsByArrayData.
     */
    private function __construct() {}

    /**
     * Load masks by array data
     * @param PHPExcel_Worksheet $aSheet
     * @param array $data
     */
    public static function loadData(PHPExcel_Worksheet $aSheet, array $data)
    {
        self::setData($data);
        self::setMasks($data);
        if (empty(self::$masks)) {
            return;
        }

        self::setCellCoordinateByMask($aSheet);
    }

    /**
     * Generate cells by mask in the active sheet PHPExcel
     * @param PHPExcel_Worksheet $aSheet
     * @param array $data
     */
    public static function generate(PHPExcel_Worksheet $aSheet, $data = [])
    {
        if ($data) {
            self::loadData($aSheet, $data);
        }

        if (empty(self::$masks)) {
            return;
        }

        self::setCellValue($aSheet);
    }

    /**
     * @param string $prefix
     * @param string $posfix
     * @return void
     */
    public static function setScreening($prefix = self::SCREEN, $posfix = self::SCREEN)
    {
        self::$prefixKey = $prefix;
        self::$posfixKey = $posfix;
    }

    /**
     * Set masks
     * @param $data
     */
    protected static function setMasks($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $index => $columns) {
                    self::$masks[$key][] = array_keys($columns);
                }
            }
        }
    }

    /**
     * Set data
     * @param $data
     */
    protected static function setData($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::$data[$key] = $value;
            }
        }
    }

    /**
     * Set cell coordinate in masks
     * @param PHPExcel_Worksheet $aSheet
     * @return void
     */
    protected static function setCellCoordinateByMask(PHPExcel_Worksheet $aSheet)
    {
        if (!empty(self::$masks)) {

            // Получим итератор строки и пройдемся по нему циклом
            foreach($aSheet->getRowIterator() as $row) {

                // Получим итератор ячеек текущей строки
                $cellIterator = $row->getCellIterator();

                $activeMaskKey = null;
                $maskCellCoordinate = [];

                // Пройдемся циклом по ячейкам строки
                foreach ($cellIterator as $cell) {
                    /* @var $cell \PHPExcel_Cell */

                    $val = trim($cell->getValue());
                    if (!empty($val)) {

                        foreach (array_keys(self::$masks) as $maskTitle) {
                            if (strpos($val, self::$prefixKey . $maskTitle) === 0) {
                                $activeMaskKey = $maskTitle;
                                break;
                            }
                        }

                        if ($activeMaskKey) {
                            foreach (self::$masks[$activeMaskKey][0] as $maskItem) {
                                if ($val == self::$prefixKey . $activeMaskKey . '.' . $maskItem . self::$posfixKey) {
                                    $maskCellCoordinate[$maskItem] = $cell->getMergeRange();
                                    break;
                                }
                            }
                        }
                    }
                }

                if ($activeMaskKey) {
                    $currRow = $row->getRowIndex();
                    $insertRow = $currRow;

                    foreach (self::$masks[$activeMaskKey] as &$templateMask) {
                        $templateMask = str_replace($currRow, $insertRow, $maskCellCoordinate);

                        if ($insertRow > $currRow) {
                            $aSheet->insertNewRowBefore($insertRow);
                            foreach ($templateMask as $cellKey => $cellCoordinate) {
                                $aSheet->mergeCells($cellCoordinate);

                                $crewTemplate = $aSheet->getStyle($maskCellCoordinate[$cellKey]);
                                $aSheet->duplicateStyle($crewTemplate, $cellCoordinate);
                            }
                            $aSheet->getRowDimension($insertRow)->setRowHeight(-1);
                        }

                        $insertRow++;
                    }
                }
            }
        }
    }

    /**
     * Set cells value by masks
     * @param PHPExcel_Worksheet $aSheet
     */
    protected static function setCellValue(PHPExcel_Worksheet $aSheet)
    {
        $cells = [];
        foreach (self::$masks as $maskKey => $maskRows) {
            if (isset(self::$data[$maskKey])) {

                $data = self::$data[$maskKey];
                foreach ($maskRows as $maskRowKey => $maskRowColumns) {
                    if (isset($data[$maskRowKey])) {

                        foreach ($maskRowColumns as $maskRowColumnKey => $maskRowColumnVal) {
                            if (isset($data[$maskRowKey][$maskRowColumnKey])) {
                                $cells[$maskRowColumnVal] = $data[$maskRowKey][$maskRowColumnKey];
                            }
                        }
                    }
                }
            }
        }

        if (!empty($cells)) {

            foreach ($cells as $cellCoordinate => $cellValue) {
                $cellName = explode(':', $cellCoordinate)[0];
                $aSheet->setCellValue($cellName, $cellValue);
            }
        }
    }
}