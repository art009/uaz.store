<?php

namespace common\components\document\classes;


use PHPExcel_Worksheet;

class GenerateXlsByStringData
{
    const SCREEN = '%';

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
     * Private constructor for GenerateXlsByStringData.
     */
    private function __construct() {}

    /**
     * Load data array
     * @param PHPExcel_Worksheet $aSheet
     * @param array $data
     */
    public static function loadData(PHPExcel_Worksheet $aSheet, array $data)
    {
        foreach ($data as $key => $val) {
            if (is_string($val) or is_integer($val)) {
                self::$data[self::$prefixKey . $key . self::$posfixKey] = $val;
            }
        }
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

        if (empty(self::$data)) {
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
     * Set cells value
     * @param PHPExcel_Worksheet $aSheet
     */
    protected static function setCellValue(PHPExcel_Worksheet $aSheet)
    {
        // Получим итератор строки и пройдемся по нему циклом
        foreach($aSheet->getRowIterator() as $row) {

            // Получим итератор ячеек текущей строки
            $cellIterator = $row->getCellIterator();

            // Пройдемся циклом по ячейкам строки
            foreach($cellIterator as $cell) {
                /* @var $cell \PHPExcel_Cell */

                $val = trim($cell->getValue());

                $newVal = strtr($val, self::$data);
                if (is_string($newVal)) {
                    $cell->setValue($newVal);
                }
            }
        }
    }
}