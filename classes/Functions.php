<?php

class Functions
{

    public static function p($bject)
    {
        print('<pre>' . print_r($bject, true) . '</pre>');
    }

    public static function getXLS($xls){
        include_once ROOT . '/include/phpexcel/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load($xls);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();
        $array = array();
        foreach($aSheet->getRowIterator() as $row){
            $cellIterator = $row->getCellIterator();
            $item = array();
            foreach($cellIterator as $cell){
                array_push($item, $cell->getCalculatedValue());
            }
            array_push($array, $item);
        }
        return $array;
    }


}
