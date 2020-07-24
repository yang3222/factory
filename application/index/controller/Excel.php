<?php
namespace app\index\controller;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author 27532
 */
class Excel extends Base{
    public function __construct() {
        parent::__construct();
    }
    protected function excelStart(){
        import('file.file', EXTEND_PATH);
        import("PHPExcel.PHPExcel",EXTEND_PATH);
        import("PHPExcel.PHPExcel.PHPExcel_IOFactory",EXTEND_PATH);
        import("PHPExcel.PHPExcel.PHPExcel_Cell",EXTEND_PATH);
    }
    //保存大Excel文件
    protected function savebigExcel(){
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        $cacheSettings = array();
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    }
}
