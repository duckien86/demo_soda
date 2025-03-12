<?php

class ExcelRepository
{
    /*
     * list colunm in excel file
     * */
    public  $collumn_init_list =  array('A' => 7, 'B' => 13, 'C' => 20, 'D' => 13, 'E' => 20, 'F' => 20, 'G' => 45,
        'H' => 10, 'I' => 10, 'J' => 15, 'K' => 15, 'L' => 16, 'M' => 16, 'N' => 15, 'O' => 15, 'P' => 15,
        'Q' => 15, 'R' => 15, 'S' => 10, 'T' => 10, 'U' => 10, 'V' => 10, 'W' => 10, 'X' => 10, 'Y' => 10,
        'Z' => 10);
    /*
     * export report excel
     * */
    public function exporteddExcel($campaigns, $model, $fileName){
        ini_set('memory_limit', '-1');
        /** Include PHPExcel */
        /*Set Data*/
        /*Include PHP Excel lib*/
        spl_autoload_unregister(array('YiiBase', 'autoload'));
        Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
        spl_autoload_register(array('YiiBase', 'autoload'));
        /*End Include PHP Excel lib*/


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");


        // Add some data
        $collumn_init_list = $this->collumn_init_list;

        $collumn_key       = array_keys($collumn_init_list);
        $collumn_width     = array_values($collumn_init_list);
        $sheet = $objPHPExcel->getActiveSheet();
        foreach ((array)($model->excelTitles()) as $key => $collumn_title) {
            $sheet->setCellValue($collumn_key[$key] . 1, $collumn_title);
            $sheet->getColumnDimension($collumn_key[$key])->setWidth($collumn_width[$key]);
        }

        $index = 2;
        foreach ($campaigns as $key => $data) {
            $aray_item = $model->getFormatItem($key, $data);
            $col_index = 0;
            foreach ($aray_item as $svalue) {
                $sheet->setCellValue($collumn_key[$col_index] . $index, $svalue);
                $col_index++;
            }
            $index++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);

        $file_name = $fileName;

        /*Download Excel File after Render*/
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 15 Jul 1991 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        /*End Download Excel File after Render*/

        exit;
    }
}

?>
