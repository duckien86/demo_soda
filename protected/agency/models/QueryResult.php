<?php

class QueryResult implements ExportDataInterface
{
    public static $ColunmFileTitles;
    public static $ExcelTitles;
    public function getFormatItem($key, $data){
        $item = [];
        foreach ($data as $key => $val){
            $item[$key]= $val;
        }
        return $item;
    }
    public function getColunmFileTitles(){
        return self::$ColunmFileTitles;
    }
    public function excelTitles(){
        return self::$ExcelTitles;
    }
}

?>
