<?php

    class ExcelExportController extends AController
    {
        public function init()
        {
            parent::init();
        }

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
                'rights',
            );
        }


        public function actionObExport()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new CskhCtvUsers();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->user_name      = $_POST['excelExport']['user_name'];
                $model->mobile         = $_POST['excelExport']['mobile'];
                $model->finish_profile = $_POST['excelExport']['finish_profile'];
                $model->ob_status      = $_POST['excelExport']['ob_status'];
                $model->ob_3day        = $_POST['excelExport']['ob_3day'];
                $data_detail           = $model->search(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Tên đăng nhập')
                    ->setCellValue('B1', 'Họ tên')
                    ->setCellValue('C1', 'Số điện thoại')
                    ->setCellValue('D1', 'Ngày đăng ký')
                    ->setCellValue('E1', 'Trạng thái TTTK')
                    ->setCellValue('F1', 'Trạng thái OB');

                $i         = 2;
                $file_name = "Doanh thu tổng hợp từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row['user_name'])
                            ->setCellValue('B' . $i, $row['full_name'])
                            ->setCellValue('C' . $i, $row['mobile'])
                            ->setCellValue('D' . $i, $row['created_on'])
                            ->setCellValue('E' . $i, CskhCtvUsers::getFinishProfile($row['finish_profile']))
                            ->setCellValue('F' . $i, CskhCtvUsers::getObStatus($row['ob_status']));
                        $i++;
                    }
                    /*Set Style*/
                    $sharedStyle1 = new PHPExcel_Style();
                    $sharedStyle1->applyFromArray(
                        array(
                            'fill'    => array(
                                'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('argb' => '00bfff')
                            ),
                            'borders' => array(
                                'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'right'  => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                            )
                        )
                    );
                    $sheet = $objPHPExcel->getActiveSheet();
                    $sheet->setSharedStyle($sharedStyle1, "A1:R1");
                    /*End Set Style*/
                    // Rename worksheet
                    $sheet->setTitle('Sheet1');
                    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                    $objPHPExcel->setActiveSheetIndex(0);


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

                }
            }
        }
    }