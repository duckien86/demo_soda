<?php

class ExportDataRepository
{
    /**
     * Export csv file from data(array)
     * @param array $data: dữ liệu in ra file
     * @param string $file_name: tên file
     * @param class $model: class sử dụng để tùy biến dữ liệu các cột: function getFormatItem() ,  title các cột getColunmFileTitles()
     * @return csv file
     */
    public function export2Csv($data= array(), $model, $file_name){
        if(!empty($data)){
            header("Content-Type:text/csv, charset=utf-8");
            header("Content-Disposition:attachment;filename=$file_name.csv");
            $output = fopen("php://output", 'w') or die("Can't open php://output");
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            fputcsv($output, $model->getColunmFileTitles());
            foreach ($data as $key => $item) {
                if(!empty($item)){

                    $line = $model->getFormatItem($key, $item);
                    //Input each row in csv.
                    fputcsv($output, $line);
                }
            }
            fclose($output) or die("Can't close php://output");
        }
    }
}

?>
