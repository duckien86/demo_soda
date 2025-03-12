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


        public function actionReportIndex()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
                $model->sim_type         = $_POST['excelExport']['sim_type'];
                $model->payment_method   = $_POST['excelExport']['payment_method'];
                $model->receive_status   = $_POST['excelExport']['receive_status'];
                $model->input_type       = $_POST['excelExport']['input_type'];

                $data_detail = $model->getRenueveIndex(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Mã đơn hàng')
                    ->setCellValue('C1', 'Số thuê bao')
                    ->setCellValue('D1', 'Trạng thái thu tiền')
                    ->setCellValue('E1', 'Phương thức thanh toán')
                    ->setCellValue('F1', 'Ngày hoàn tất')
                    ->setCellValue('G1', 'TTKD')
                    ->setCellValue('H1', 'Phòng bán hàng')
                    ->setCellValue('I1', 'Doanh thu sim')
                    ->setCellValue('K1', 'Doanh thu gói')
                    ->setCellValue('L1', 'Tiền đặt cọc')
                    ->setCellValue('M1', 'Tổng doanh thu');

                $i         = 2;
                $file_name = "Doanh thu tổng hợp từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row['id'])
                            ->setCellValue('C' . $i, $row['sim'])
                            ->setCellValue('D' . $i, $row['receive_status'])
                            ->setCellValue('E' . $i, $row['payment_method'])
                            ->setCellValue('F' . $i, $row['delivered_date'])
                            ->setCellValue('G' . $i, $row['province_code'])
                            ->setCellValue('H' . $i, $row['sale_office_code'])
                            ->setCellValue('I' . $i, $row['renueve_sim'])
                            ->setCellValue('K' . $i, AOrderDetails::getItemPrice($row['id'], 'package'))
                            ->setCellValue('L' . $i, AOrderDetails::getItemPrice($row['id'], 'price_term'))
                            ->setCellValue('M' . $i, AOrderDetails::getItemPrice($row['id']));
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

        public function actionReportOnlinePaid()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
                $model->sim_type         = $_POST['excelExport']['sim_type'];
                $model->payment_method   = $_POST['excelExport']['payment_method'];
                $model->online_status    = $_POST['excelExport']['online_status'];
                $model->input_type       = $_POST['excelExport']['input_type'];
                $model->paid_status      = $_POST['excelExport']['paid_status'];
                $model->status_type      = $_POST['excelExport']['status_type'];

                $data_detail = $model->getOnlinePaidData();

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Mã đơn hàng')
                    ->setCellValue('C1', 'Số thuê bao')
                    ->setCellValue('D1', 'Phương thức thanh toán')
                    ->setCellValue('E1', 'Ngày thanh toán')
                    ->setCellValue('F1', 'TTKD')
                    ->setCellValue('G1', 'Phòng bán hàng')
                    ->setCellValue('H1', 'Doanh thu sim')
                    ->setCellValue('I1', 'Doanh thu gói')
                    ->setCellValue('J1', 'Tiền đặt cọc')
                    ->setCellValue('K1', 'Tổng doanh thu')
                    ->setCellValue('L1', 'Trạng thái')
                    ->setCellValue('M1', 'Ghi chú');

                $i         = 2;
                $file_name = "Doanh thu thanh toán " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $sim = '';
                        if ($row['sim'] != '') {
                            $sim = $row['sim'];
                        } else {
                            $sim = $row['phone_contact'];
                        }

                        $total_price = 0;
                        if ($row['price_term'] > 0) {
                            $total_price = $row['price_term'] + $row['price_sim'];
                        } else {
                            $total_price = $row['price_sim'] + $row['price_package'];
                        }

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row['order_id'])
                            ->setCellValue('C' . $i, $sim)
                            ->setCellValue('D' . $i, ReportForm::getPaymentMethod($row['payment_method']))
                            ->setCellValue('E' . $i, $row['paid_date'])
                            ->setCellValue('F' . $i, AProvince::model()->getProvince($row['province_code']))
                            ->setCellValue('G' . $i, ASaleOffices::model()->getSaleOffices($row['sale_office_code']))
                            ->setCellValue('H' . $i, $row['price_sim'])
                            ->setCellValue('I' . $i, $row['price_package'])
                            ->setCellValue('J' . $i, $row['price_term'])
                            ->setCellValue('K' . $i, $total_price)
                            ->setCellValue('L' . $i, AOrders::getStatus($row['order_id']))
                            ->setCellValue('M' . $i, $row['note']);
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

        public function actionRenueveTourist()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AFTReport();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['user_tourist'];
                $model->sim_type         = $_POST['excelExport']['contract_id'];
                $model->payment_method   = $_POST['excelExport']['order_id'];
                $model->online_status    = $_POST['excelExport']['status_order'];
                $model->input_type       = $_POST['excelExport']['item_id'];


                $data_detail = $model->getRenueveDetails(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Mã hợp đồng')
                    ->setCellValue('C1', 'Mã đơn hàng')
                    ->setCellValue('D1', 'Khách hàng')
                    ->setCellValue('E1', 'Sản lượng')
                    ->setCellValue('F1', 'Doanh thu');

                $i         = 1;
                $file_name = "Báo cáo doanh thu sim du lịch " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row['contract_code'])
                            ->setCellValue('C' . $i, $row['code'])
                            ->setCellValue('D' . $i, AFTUsers::model()->getUserById($row['user_tourist']))
                            ->setCellValue('E' . $i, $row['total'])
                            ->setCellValue('F' . $i, $row['total_renueve']);
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

        public function actionSocialIndex()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportSocial();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->customer_id = $_POST['excelExport']['customer_id'];

                $data_detail = $model->getListCustomer(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Tên đăng nhập')
                    ->setCellValue('C1', 'Số điện thoại')
                    ->setCellValue('D1', 'Ngày tham gia')
                    ->setCellValue('E1', 'Điểm tích lũy')
                    ->setCellValue('F1', 'Cấp độ thành viên');

                $i         = 2;
                $file_name = "Báo cáo diễn đàn từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row->username)
                            ->setCellValue('C' . $i, $row->phone)
                            ->setCellValue('D' . $i, $row->create_time)
                            ->setCellValue('E' . $i, $row->bonus_point)
                            ->setCellValue('F' . $i, $row->getLevel($row->bonus_point));
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

        public function actionSocialUser()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportSocial();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->status  = $_POST['excelExport']['status'];
                $data_likes     = $model->getCustomerLikes();
                $data_comment   = $model->getCustomerComment();
                $data_post      = $model->getCustomerPost();
                $data_sub_point = $model->getCustomerTotalSubPoint();
                $data_redeem    = $model->getCustomerTotalRedeem();

                $data = array_merge_recursive($data_likes, $data_post);
                $data = array_merge_recursive($data, $data_comment);
                $data = array_merge_recursive($data, $data_sub_point);
                $data = array_merge_recursive($data, $data_redeem);

                $data_detail = $model->controllDataCustomer($data, $model);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Tên đăng nhập')
                    ->setCellValue('C1', 'Tổng số like')
                    ->setCellValue('D1', 'Tổng số bình luận')
                    ->setCellValue('E1', 'Tổng số bài đăng')
                    ->setCellValue('F1', 'Số lần vi phạm')
                    ->setCellValue('G1', 'Tổng điềm đã đổi quà')
                    ->setCellValue('H1', 'Cấp độ')
                    ->setCellValue('I1', 'Tổng điểm đang có')
                    ->setCellValue('J1', 'Trạng thái');

                $i         = 2;
                $file_name = "Báo cáo thành viên từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $result = '';
                        if ($row['status'] == ACustomers::ACTIVE) {
                            $result = "KÍCH HOẠT";
                        } else {
                            $result = "ẨN";
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row['username'])
                            ->setCellValue('C' . $i, $row['total_like'])
                            ->setCellValue('D' . $i, $row['total_comment'])
                            ->setCellValue('E' . $i, $row['total_post'])
                            ->setCellValue('F' . $i, $row['total_sub_point'])
                            ->setCellValue('G' . $i, $row['sum_redeem'])
                            ->setCellValue('H' . $i, ACustomers::getLevel($row['total_sub_point']))
                            ->setCellValue('I' . $i, $row['current_point'])
                            ->setCellValue('J' . $i, $result);
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

        public function actionReportSim()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
                $model->sim_type         = $_POST['excelExport']['sim_type'];
                $model->input_type       = $_POST['excelExport']['input_type'];
                $model->payment_method   = $_POST['excelExport']['payment_method'];

                $data_detail = $model->detailRenueveSim(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Mã đơn hàng')
                    ->setCellValue('B1', 'Số thuê bao')
                    ->setCellValue('C1', 'Hình thức')
                    ->setCellValue('D1', 'Ngày mua')
                    ->setCellValue('E1', 'TTKD')
                    ->setCellValue('F1', 'Phòng bán hàng')
                    ->setCellValue('G1', 'Tiền đặt cọc')
                    ->setCellValue('H1', 'Doanh thu');

                $i         = 2;
                $file_name = "Doanh thu sim từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->id)
                            ->setCellValue('B' . $i, $row->sim)
                            ->setCellValue('C' . $i, ReportForm::getTypeSimExcel($row->type))
                            ->setCellValue('D' . $i, $row->create_date)
                            ->setCellValue('E' . $i, AProvince::model()->getProvince($row->province_code))
                            ->setCellValue('F' . $i, SaleOffices::model()->getSaleOfficesByOrder($row->id))
                            ->setCellValue('G' . $i, AOrderDetails::getItemPrice($row->id))
                            ->setCellValue('H' . $i, $row->renueve_sim);
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

        public function actionReportSimKit()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
                $model->input_type       = $_POST['excelExport']['input_type'];
                $model->package_id       = $_POST['excelExport']['package_id'];
                $data_detail             = $model->detailRenuevePackage(ReportForm::SIMKIT, TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Mã đơn hàng')
                    ->setCellValue('B1', 'Số thuê bao')
                    ->setCellValue('C1', 'Tên gói')
                    ->setCellValue('D1', 'Ngày mua')
                    ->setCellValue('E1', 'Nhóm gói')
                    ->setCellValue('F1', 'TTKD')
                    ->setCellValue('G1', 'Phòng bán hàng')
                    ->setCellValue('H1', 'Doanh thu');

                $i         = 2;
                $file_name = "Doanh thu simkit từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->id)
                            ->setCellValue('B' . $i, $row->phone_contact)
                            ->setCellValue('C' . $i, $row->item_name)
                            ->setCellValue('D' . $i, $row->create_date)
                            ->setCellValue('E' . $i, ReportForm::getTypeSimExcel($row->type))
                            ->setCellValue('F' . $i, AProvince::model()->getProvince($row->province_code))
                            ->setCellValue('G' . $i, SaleOffices::model()->getSaleOfficesByOrder($row->id))
                            ->setCellValue('H' . $i, $row->renueve);
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

        public function actionReportPackage()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                };

                $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
                $model->province_code    = $_POST['excelExport']['province_code'];
                $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
                $model->input_type       = $_POST['excelExport']['input_type'];
                $model->package_id       = $_POST['excelExport']['package_id'];
                $model->package_group    = $_POST['excelExport']['package_group'];
                $data_detail             = $model->detailRenuevePackage('', TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Mã đơn hàng')
                    ->setCellValue('B1', 'Số thuê bao')
                    ->setCellValue('C1', 'Tên gói')
                    ->setCellValue('D1', 'Ngày mua')
                    ->setCellValue('E1', 'Nhóm gói')
                    ->setCellValue('F1', 'TTKD')
                    ->setCellValue('G1', 'Phòng bán hàng')
                    ->setCellValue('H1', 'Doanh thu');

                $i         = 2;
                $file_name = "Doanh gói từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->id)
                            ->setCellValue('B' . $i, $row->phone_contact)
                            ->setCellValue('C' . $i, $row->item_name)
                            ->setCellValue('D' . $i, $row->create_date)
                            ->setCellValue('E' . $i, ReportForm::getTypeSimExcel($row->type))
                            ->setCellValue('F' . $i, AProvince::model()->getProvince($row->province_code))
                            ->setCellValue('G' . $i, SaleOffices::model()->getSaleOfficesByOrder($row->id))
                            ->setCellValue('H' . $i, number_format($row->renueve, 0, "", "."));
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

        public function actionReportCard()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new ReportOci();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->price_card    = $_POST['excelExport']['price_card'];
                $model->province_code = $_POST['excelExport']['province_code'];


                $data_detail = $model->getCardFreedooDetail();

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Số TB nạp thẻ')
                    ->setCellValue('C1', 'Ngày mua')
                    ->setCellValue('D1', 'Mệnh giá')
                    ->setCellValue('E1', 'Doanh thu')
                    ->setCellValue('F1', 'Tỉnh');

                $i         = 2;
                $file_name = "Doanh thu thẻ từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i)
                            ->setCellValue('B' . $i, $row['MSISDN'])
                            ->setCellValue('C' . $i, $row['CREATED_DATE'])
                            ->setCellValue('D' . $i, $row['NAPTIEN'])
                            ->setCellValue('E' . $i, $row['NAPTIEN'])
                            ->setCellValue('F' . $i, $row['MATINH']);
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

        public function actionReportPackageFlexible()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new Report();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                };
                $model->package_id    = $_POST['excelExport']['package_id'];
                $model->package_group = $_POST['excelExport']['package_group'];
                $model->period        = $_POST['excelExport']['period'];
                $data_detail          = $model->getInfoPackageFlexible();

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Mã đơn hàng')
                    ->setCellValue('B1', 'Số thuê bao')
                    ->setCellValue('C1', 'Thoại nội mạng')
                    ->setCellValue('D1', 'Thoại ngoại mạng')
                    ->setCellValue('E1', 'SMS nội mạng')
                    ->setCellValue('F1', 'SMS ngoại mạng')
                    ->setCellValue('G1', 'Data')
                    ->setCellValue('H1', 'Ngày mua')
                    ->setCellValue('I1', 'Doanh thu');

                $i         = 2;
                $file_name = "Doanh gói tùy biến từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row['id'])
                            ->setCellValue('B' . $i, $row['customer_msisdn'])
                            ->setCellValue('C' . $i, $row['capacity_call_int'])
                            ->setCellValue('D' . $i, $row['capacity_call_ext'])
                            ->setCellValue('E' . $i, $row['capacity_sms_int'])
                            ->setCellValue('F' . $i, $row['capacity_sms_ext'])
                            ->setCellValue('G' . $i, $row['capacity_data'])
                            ->setCellValue('H' . $i, $row['create_date'])
                            ->setCellValue('I' . $i, number_format($row['total'], 0, "", ".") . " đ");
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

        // Báo cáo tổng quan giao vận
        public function actionTrafficRenueve()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new ATraffic();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                };
                $data_detail = $model->search_renueve_report('', FALSE, TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'TTKD')
                    ->setCellValue('B1', 'Phòng bán hàng')
                    ->setCellValue('C1', 'Mã ĐH')
                    ->setCellValue('D1', 'Ngày đặt hàng')
                    ->setCellValue('E1', 'NV Giao vận')
                    ->setCellValue('F1', 'Phương thức thanh toán')
                    ->setCellValue('G1', 'Trạng thái GV')
                    ->setCellValue('H1', 'Tiền sim')
                    ->setCellValue('I1', 'Tiền gói')
                    ->setCellValue('J1', 'Tiền đặt cọc')
                    ->setCellValue('K1', 'Phí vận chuyển')
                    ->setCellValue('L1', 'Tổng tiền');

                $i = 2;

                $file_name = "Doanh thu giao vận từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, ATraffic::model()->getProvince($row->province_code))
                            ->setCellValue('B' . $i, SaleOffices::model()->getSaleOfficesByOrder($row->id))
                            ->setCellValue('C' . $i, $row->id)
                            ->setCellValue('D' . $i, $row->create_date)
                            ->setCellValue('E' . $i, ATraffic::model()->getShipperName($row->shipper_id))
                            ->setCellValue('F' . $i, AOrders::getPaymentMethod($row->payment_method))
                            ->setCellValue('G' . $i, ATraffic::model()->getStatusTraffic(ATraffic::model()->getStatus($row->id)))
                            ->setCellValue('H' . $i, ATraffic::model()->getRenueveByType('sim', $row->id))
                            ->setCellValue('I' . $i, ATraffic::model()->getRenueveByType('package', $row->id))
                            ->setCellValue('J' . $i, ATraffic::model()->getRenueveByType('price_term', $row->id))
                            ->setCellValue('K' . $i, ATraffic::model()->getPriceShip($row->id))
                            ->setCellValue('L' . $i, ATraffic::model()->getRenueveByType('', $row->id, TRUE));
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

        public function actionOrderAdmin()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AOrders();
            $post  = FALSE;

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                $post = $_POST['excelExport']['post'];
                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                };
                $data_detail = $model->search($post, TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Mã ĐH')
                    ->setCellValue('B1', 'SĐT liên hệ')
                    ->setCellValue('C1', 'Số thuê bao')
                    ->setCellValue('D1', 'Người nhận')
                    ->setCellValue('E1', 'Loại TB')
                    ->setCellValue('F1', 'Địa chỉ')
                    ->setCellValue('G1', 'Thời gian mua hàng')
                    ->setCellValue('H1', 'Trạng thái')
                    ->setCellValue('I1', 'Trạng thái GV');

                $i = 2;

                $file_name = "Danh sách đơn hàng từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $address = '';
                        if ($row->delivery_type == AOrders::COD) {
                            $address = $row->address_detail . " -- " . Ward::model()->getWard($row->ward_code) . " -- "
                                . District::model()->getDistrict($row->district_code) . " -- " . Province::model()->getProvince($row->province_code);
                        } else {
                            $address = District::model()->getDistrict($row->district_code) . " -- "
                                . Province::model()->getProvince($row->province_code);
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->id)
                            ->setCellValue('B' . $i, $row->phone_contact)
                            ->setCellValue('C' . $i, AOrders::model()->getSim($row->id))
                            ->setCellValue('D' . $i, $row->full_name)
                            ->setCellValue('E' . $i, AOrders::model()->getTypeSimByOrder($row->id))
                            ->setCellValue('F' . $i, $address)
                            ->setCellValue('G' . $i, $row->create_date)
                            ->setCellValue('H' . $i, AOrders::getStatus($row->id))
                            ->setCellValue('I' . $i, ($row->delivery_type == 1) ? AOrders::model()->getStatusTraffic(AOrders::model()->getTrafficStatus($row->id)) : 'Nhận tại ĐGD');
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

        public function actionUserAdmin()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new User();
            $post  = FALSE;

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];

                $criteria = new CDbCriteria;
                if (!SUPER_ADMIN && !ADMIN) {
                    if (Yii::app()->user->province_code) {
                        if (!isset(Yii::app()->user->sale_offices_id) || Yii::app()->user->sale_offices_id == '') {
                            $criteria->compare('province_code', Yii::app()->user->province_code);
                        } else {
                            if (Yii::app()->user->sale_offices_id != '') {
                                if (isset(Yii::app()->user->brand_offices_id) && Yii::app()->user->brand_offices_id != '') {
                                    $criteria->compare('brand_offices_id', Yii::app()->user->brand_offices_id);
                                }
                                $criteria->compare('sale_offices_id', Yii::app()->user->sale_offices_id);
                            }
                        }
                    } else {
                        $criteria->compare('parent_id', Yii::app()->user->id);
                    }
                }
                if (ADMIN) {
                    $criteria->condition = "username !='admin'";
                }

                if ($model->province_code != '') {
                    $criteria->addCondition("province_code = '" . $model->province_code . "'");
                }
                if ($model->sale_offices_id != '') {
                    $criteria->addCondition("sale_offices_id = '" . $model->sale_offices_id . "'");
                }
                if ($model->brand_offices_id != '') {
                    $criteria->addCondition("brand_offices_id = '" . $model->brand_offices_id . "'");
                }

                $data_detail = User::model()->findAll($criteria);
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Tài khoản')
                    ->setCellValue('B1', 'Số điện thoại')
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'Họ và tên')
                    ->setCellValue('E1', 'TTKD')
                    ->setCellValue('F1', 'Tên PBH')
                    ->setCellValue('G1', 'Tên ĐGD')
                    ->setCellValue('H1', 'Làn ghé thăm cuối')
                    ->setCellValue('I1', 'Chức vụ')
                    ->setCellValue('J1', 'Trạng thái');


                $i = 2;

                $file_name = "Danh sách người dùng .";

                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $regency = '';
                        if ($row->regency == 'ADMIN') {
                            $regency = "ADMIN";
                        } else if ($row->regency == 'STAFF') {
                            $regency = "Quản lý";
                        } else if ($row->regency == 'ACCOUNTANT') {
                            $regency = "Kế toán";
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->username)
                            ->setCellValue('B' . $i, $row->phone)
                            ->setCellValue('C' . $i, $row->email)
                            ->setCellValue('D' . $i, User::model()->getFullName($row->id))
                            ->setCellValue('E' . $i, Province::model()->getProvince($row->province_code))
                            ->setCellValue('F' . $i, ($row->sale_offices_id != '') ? SaleOffices::model()->getSaleOffices($row->sale_offices_id) : '')
                            ->setCellValue('G' . $i, ($row->brand_offices_id != '') ? BrandOffices::model()->getBrandOffices($row->brand_offices_id) : '')
                            ->setCellValue('H' . $i, ($row->lastvisit) ? date("d.m.Y H:i:s", $row->lastvisit) : UserModule::t("Not visited"))
                            ->setCellValue('I' . $i, $regency)
                            ->setCellValue('J' . $i, User::itemAlias("UserStatus", $row->status));
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

        public function actionShipperAdmin()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AShipper();
            $post  = FALSE;

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $post              = $_POST['excelExport']['post'];

                $data_detail = $model->search($post, TRUE);
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Tài đăng nhập')
                    ->setCellValue('B1', 'Họ tên')
                    ->setCellValue('C1', 'Email')
                    ->setCellValue('D1', 'SĐT_1')
                    ->setCellValue('E1', 'SĐT_2')
                    ->setCellValue('F1', 'TTKD')
                    ->setCellValue('G1', 'Tên PBH')
                    ->setCellValue('H1', 'Trạng thái');


                $i = 2;

                $file_name = "Danh sách nhân viên giao vận .";

                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $status = '';
                        if ($row->status == AShipper::ACTIVE) {
                            $status = AShipper::ACTIVE_TEXT;
                        } else {
                            $status = AShipper::INACTIVE_TEXT;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $row->username)
                            ->setCellValue('B' . $i, $row->full_name)
                            ->setCellValue('C' . $i, $row->email)
                            ->setCellValue('D' . $i, $row->phone_1)
                            ->setCellValue('E' . $i, $row->phone_2)
                            ->setCellValue('F' . $i, Province::model()->getProvince($row->province_code))
                            ->setCellValue('G' . $i, SaleOffices::model()->getSaleOfficesId($row->sale_offices_code))
                            ->setCellValue('H' . $i, $status);
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

        public function actionOrderReceive()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new ATraffic();
            $post  = FALSE;

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                $post = $_POST['excelExport']['post'];
                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                };

                $data_detail = $model->search('', FALSE, TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'TTKD')
                    ->setCellValue('B1', 'Phòng BH')
                    ->setCellValue('C1', 'Giao dịch viên')
                    ->setCellValue('D1', 'Mã ĐH')
                    ->setCellValue('E1', 'Tiền sim')
                    ->setCellValue('F1', 'Tiền gói')
                    ->setCellValue('G1', 'Tiền đặt cọc')
                    ->setCellValue('H1', 'Phí vận chuyển')
                    ->setCellValue('I1', 'Phương thức thanh toán')
                    ->setCellValue('K1', 'Tổng tiền')
                    ->setCellValue('L1', 'Trạng thái thu tiền')
                    ->setCellValue('M1', 'Người thu');

                $i = 2;

                $file_name = "Danh sách thu tiền từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {

                        $return = '';
                        $user   = '';
                        if (isset($row->shipper_id)) {
                            $return = ATraffic::model()->getShipperName($row->shipper_id);
                        } else {

                            $logsim = ALogsSim::model()->findByAttributes(array('order_id' => $row->id));

                            if ($logsim) {
                                $user_data = User::model()->findByAttributes(array('id' => $logsim->user_id));
                                if ($user_data) {
                                    $return = $user_data->username;
                                }
                            }

                        }

                        $user_by = User::model()->findByAttributes(array('id' => $row->receive_cash_by));
                        if ($user_by) {
                            $user = $user_by->username;
                        }

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, ATraffic::model()->getProvince($row->province_code))
                            ->setCellValue('B' . $i, SaleOffices::model()->getSaleOfficesByOrder($row->id))
                            ->setCellValue('C' . $i, $return)
                            ->setCellValue('D' . $i, $row->id)
                            ->setCellValue('E' . $i, ATraffic::model()->getRenueveByType('sim', $row->id))
                            ->setCellValue('F' . $i, ATraffic::model()->getRenueveByType('package', $row->id))
                            ->setCellValue('G' . $i, ATraffic::model()->getRenueveByType('price_term', $row->id))
                            ->setCellValue('H' . $i, ATraffic::model()->getPriceShip($row->id))
                            ->setCellValue('I' . $i, ATraffic::getPaymentMethod($row->payment_method))
                            ->setCellValue('K' . $i, ATraffic::model()->getRenueveByType('', $row->id, TRUE))
                            ->setCellValue('L' . $i, ATraffic::model()->getAllStatusTrafficAdminByid($row->id))
                            ->setCellValue('M' . $i, $user);
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

        public function actionReportSimAt()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportAT();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->province_code = $_POST['excelExport']['province_code'];
                $model->sim_type      = $_POST['excelExport']['sim_type'];
                $model->channel_code  = $_POST['excelExport']['channel_code'];
                $model->status        = $_POST['excelExport']['status'];


                $data_detail = $model->getSimDetails(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Mã ĐH')
                    ->setCellValue('C1', 'Số TB')
                    ->setCellValue('D1', 'Hình thức')
                    ->setCellValue('E1', 'Trạng thái')
                    ->setCellValue('F1', 'TTKD')
                    ->setCellValue('G1', 'TransID')
                    ->setCellValue('H1', 'Kênh bán hàng')
                    ->setCellValue('I1', 'Lý do')
                    ->setCellValue('J1', 'Ngày kích hoạt')
                    ->setCellValue('K1', 'Tiền sim')
                    ->setCellValue('L1', 'Tiền đặt cọc')
                    ->setCellValue('M1', 'Tổng')
                    ->setCellValue('N1', 'Hoa hồng bán sim');

                $i         = 2;
                $file_name = "Doanh thu sim AT từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $order_note = '';
                        if ($row->order_status == 0) {
                            $order_note = $row->order_note;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, $row->order_id)
                            ->setCellValue('C' . $i, $row->item_name)
                            ->setCellValue('D' . $i, AReportATForm::getTypeSimByType($row->sub_type))
                            ->setCellValue('E' . $i, AReportATForm::getStatusOrderAT($row->order_status))
                            ->setCellValue('F' . $i, AProvince::model()->getProvince($row->order_province_code))
                            ->setCellValue('G' . $i, $row->affiliate_click_id)
                            ->setCellValue('H' . $i, AReportATForm::getChannelByCode($row->affiliate_channel))
                            ->setCellValue('I' . $i, $order_note)
                            ->setCellValue('J' . $i, AOrders::model()->getActiveSimDate($row->order_id))
                            ->setCellValue('K' . $i, $row->item_price)
                            ->setCellValue('L' . $i, $row->item_price_term)
                            ->setCellValue('M' . $i, $row->item_price_term + $row->item_price)
                            ->setCellValue('N' . $i, $row->amount);
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

        public function actionReportTraffic()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new ReportOci();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }


                $data_detail = $model->getUserTraffixByHour();
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Ngày')
                    ->setCellValue('C1', 'Chiến dịch')
                    ->setCellValue('D1', 'Kênh')
                    ->setCellValue('E1', 'Tổng lượt truy cập');

                $i         = 2;
                $file_name = "Báo cáo hiệu năng chiến dịch từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $order_note = '';
                        if ($row->order_status == 0) {
                            $order_note = $row->order_note;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, $row['RXTIME_DATE'])
                            ->setCellValue('C' . $i, $row['CAMPAIGN'])
                            ->setCellValue('D' . $i, $row['CHANNEL_CODE'])
                            ->setCellValue('E' . $i, $row['TOTAL']);
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

        public function actionReportPackageAt()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportAT();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->province_code = $_POST['excelExport']['province_code'];
                $model->package_group = $_POST['excelExport']['package_group'];
                $model->package_id    = $_POST['excelExport']['package_id'];
                $model->channel_code  = $_POST['excelExport']['channel_code'];
                $model->status        = $_POST['excelExport']['status'];


                $data_detail = $model->getPackageDetails(TRUE);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'Mã ĐH')
                    ->setCellValue('C1', 'Số TB mua gói')
                    ->setCellValue('D1', 'Tên gói')
                    ->setCellValue('E1', 'Giá gói')
                    ->setCellValue('F1', 'Trạng thái')
                    ->setCellValue('G1', 'Ngày đặt hàng')
                    ->setCellValue('H1', 'Nhóm gói')
                    ->setCellValue('I1', 'Mã TTKD')
                    ->setCellValue('J1', 'TransID')
                    ->setCellValue('K1', 'Kênh bán hàng')
                    ->setCellValue('L1', 'Lý do')
                    ->setCellValue('M1', 'Doanh thu gói đầu tháng')
                    ->setCellValue('N1', 'Số lần gia hạn')
                    ->setCellValue('O1', 'Hoa hồng bán gói');

                $i         = 2;
                $file_name = "Doanh thu gói AT từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $order_note = '';
                        if ($row->order_status == 0) {
                            $order_note = $row->order_note;
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, $row->order_id)
                            ->setCellValue('C' . $i, $row->phone_customer)
                            ->setCellValue('D' . $i, $row->item_name)
                            ->setCellValue('E' . $i, $row->item_price)
                            ->setCellValue('F' . $i, AReportATForm::getStatusOrderAT($row->order_status))
                            ->setCellValue('G' . $i, $row->order_create_date)
                            ->setCellValue('H' . $i, AReportATForm::getPackageGroupByType($row->package_type))
                            ->setCellValue('I' . $i, AProvince::model()->getProvince($row->order_province_code))
                            ->setCellValue('J' . $i, $row->affiliate_click_id)
                            ->setCellValue('K' . $i, AReportATForm::getChannelByCode($row->affiliate_channel))
                            ->setCellValue('L' . $i, $order_note)
                            ->setCellValue('M' . $i, $row->item_price)
                            ->setCellValue('N' . $i, $row->renewal_count)
                            ->setCellValue('O' . $i, $row->amount);
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

        public function actionReportAffiliateAt()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportAT();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->start_date = $_POST['excelExport']['start_date'];
                $model->end_date   = $_POST['excelExport']['end_date'];

                if ($model->start_date && $model->end_date) {

                    $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                    $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                }
                $model->province_code = $_POST['excelExport']['province_code'];
                $model->status        = $_POST['excelExport']['status'];

                $data_detail_sim = $model->getSimAffiliateDetails();

                $data_detail_package = $model->getPackageAffiliateDetails();
                $data_detail         = self::controllDataDetailAffiliate($data_detail_sim, $data_detail_package);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'TTKD')
                    ->setCellValue('C1', 'PBH')
                    ->setCellValue('D1', 'Mã ĐH')
                    ->setCellValue('E1', 'CTV')
                    ->setCellValue('F1', 'Mã CTV')
                    ->setCellValue('G1', 'Mã GT')
                    ->setCellValue('H1', 'Hình thức')
                    ->setCellValue('I1', 'Trạng thái')
//                    ->setCellValue('J1', 'Số lần gia hạn')
                    ->setCellValue('J1', 'Kênh bán hàng')
                    ->setCellValue('K1', 'Tiền sim')
                    ->setCellValue('L1', 'Tiền gói')
                    ->setCellValue('M1', 'Tổng')
                    ->setCellValue('N1', 'Hoa hồng phát triển CTV')
                    ->setCellValue('O1', 'Hoa hồng bán sim')
                    ->setCellValue('P1', 'Hoa hồng bán gói')
                    ->setCellValue('Q1', 'Tổng hoa hồng');

                $i         = 2;
                $file_name = "Báo cáo hoa hồng affiliate từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $amount_ctv = 0;
                        $data_ctv   = AReportATForm::getPublisherAward($row['order_code'], $row['action_status']);
                        if (!empty($data_ctv)) {
                            $amount_ctv = $data_ctv[0]['amout'];
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, AProvince::model()->getProvinceVnp($row['vnp_province_id']))
                            ->setCellValue('C' . $i, SaleOffices::model()->getSaleOfficesByOrder($row['order_code']))
                            ->setCellValue('D' . $i, $row['order_code'])
                            ->setCellValue('E' . $i, ACtvUsers::getUserName($row['publisher_id']))
                            ->setCellValue('F' . $i, ACtvUsers::getOwnerCode($row['publisher_id']))
                            ->setCellValue('G' . $i, ACtvUsers::getInviterCode($row['publisher_id']))
                            ->setCellValue('H' . $i, AReportATForm::getTypeSimByType($row['sub_type']))
                            ->setCellValue('I' . $i, AReportATForm::getStatusOrder($row['action_status']))
//                            ->setCellValue('J' . $i, $row['renewal_count'])
                            ->setCellValue('J' . $i, 'AFFILIATE')
                            ->setCellValue('K' . $i, $row['price_sim'])
                            ->setCellValue('L' . $i, $row['price_package'])
                            ->setCellValue('M' . $i, $row['price_sim'] + $row['price_package'])
                            ->setCellValue('N' . $i, $amount_ctv)
                            ->setCellValue('O' . $i, $row['amount_sim'])
                            ->setCellValue('P' . $i, $row['amount_package'])
                            ->setCellValue('Q' . $i, $row['amount_sim'] + $row['amount_package'] + $amount_ctv);
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

        public function actionReportPaidAffiliate()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AReportAT();

            if (isset($_POST['excelExport'])) {
                $model->attributes = $_POST['excelExport'];
                $model->year       = $_POST['excelExport']['year'];
                $model->month      = $_POST['excelExport']['month'];


                $model->province_code = $_POST['excelExport']['province_code'];
                $model->ctv_id        = $_POST['excelExport']['ctv_id'];
                $model->ctv_type      = $_POST['excelExport']['ctv_type'];

                $data_detail = $model->getPaidAffiliateDetails();


                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'STT')
                    ->setCellValue('B1', 'CTV')
                    ->setCellValue('C1', 'Mã CTV')
                    ->setCellValue('D1', 'Mã GT')
                    ->setCellValue('E1', 'Ngân hàng')
                    ->setCellValue('F1', 'Tên tài khoản')
                    ->setCellValue('G1', 'Số tài khoản')
                    ->setCellValue('H1', 'TTKD')
                    ->setCellValue('I1', 'Thời gian thanh toán')
                    ->setCellValue('J1', 'Thù lao tháng đối soát')
                    ->setCellValue('K1', 'Thù lao tồn đọng')
                    ->setCellValue('L1', 'Tổng thù lao')
                    ->setCellValue('M1', 'Trạng thái')
                    ->setCellValue('N1', 'Lý do');

                $i         = 2;
                $file_name = "Báo cáo thanh toán tháng " . $_POST['excelExport']['month'] . " năm " . $_POST['excelExport']['year'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $update_by = '';
                        if (!empty($data['update_by'])) {
                            $update_by = CHtml::encode(ACtvSystemUser::getUserName($data['update_by']));
                        } else {
                            $update_by = "Chưa thanh toán";
                        }
                        $update_time = '';
                        if ($data['update_time'] != NULL) {
                            $update_time = CHtml::encode($data['update_time']);
                        } else {
                            $update_time = "Chưa thanh toán";
                        }
                        $month = !empty($model->month) ? $model->month : '';

                        $bank = ACtvCommissionStatisticMonth::getBanks($row['publisher_id']);

                        $amount_receive = ACtvCommissionStatisticMonth::getCommisionReceive($row['publisher_id'], $month, $row['transaction_id']);
                        $lydo           = '';
                        if ($bank == '' && $row['status'] != 10) {
                            $lydo = "Chưa đủ thông tin thanh toán!";
                        } else if ($row['status'] != 10 && ($row['total_amount'] + $amount_receive) < 200000) {
                            $lydo = "Tổng thù lao tháng nhỏ hơn 200.000 đ!";
                        }
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, ACtvUsers::getUserName($row['publisher_id']))
                            ->setCellValue('C' . $i, ACtvUsers::getOwnerCode($row['publisher_id']))
                            ->setCellValue('D' . $i, ACtvUsers::getInviterCode($row['publisher_id']))
                            ->setCellValue('E' . $i, ACtvCommissionStatisticMonth::getBanks($row['publisher_id']))
                            ->setCellValue('F' . $i, ACtvCommissionStatisticMonth::getAccountName($row['publisher_id']))
                            ->setCellValue('G' . $i, ACtvCommissionStatisticMonth::getBankAccount($row['publisher_id']))
                            ->setCellValue('H' . $i, AProvince::model()->getProvinceVnp($row['vnp_province_id']))
                            ->setCellValue('I' . $i, $update_time)
                            ->setCellValue('J' . $i, $row['total_amount'])
                            ->setCellValue('K' . $i, ACtvCommissionStatisticMonth::getCommisionReceive($row['publisher_id'], $month, $row['transaction_id']))
                            ->setCellValue('L' . $i, $row['total_amount'] + ACtvCommissionStatisticMonth::getCommisionReceive($row['publisher_id'], $month, $row['transaction_id']))
                            ->setCellValue('M' . $i, AReportATForm::getStatusPaid($row['status']))
                            ->setCellValue('M' . $i, $lydo);
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

        public function actionReportARedeemHistory()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new ARedeemHistory();

            if (isset($_POST['excelExport'])) {

                $data_detail = $model->search(TRUE);


                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Số điện thoại khách hàng')
                    ->setCellValue('B1', 'Tên đăng nhập')
                    ->setCellValue('C1', 'Ngày đổi quà')
                    ->setCellValue('D1', 'Mã gói')
                    ->setCellValue('E1', 'Số điểm đổi');

                $i         = 2;
                $file_name = "Lịch sử đổi quà";
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, $row->msisdn)
                            ->setCellValue('C' . $i, $row->username)
                            ->setCellValue('D' . $i, $row->create_date)
                            ->setCellValue('E' . $i, $row->package_code)
                            ->setCellValue('F' . $i, $row->point_amount);
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

        public function actionExportDetailSimTourist()
        {
            spl_autoload_unregister(array('YiiBase', 'autoload'));
            Yii::import('ext.PhpExcel2007.PHPExcel', TRUE);
            spl_autoload_register(array('YiiBase', 'autoload'));

            $model = new AFTReport();

            if (isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['order_id'])) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['start_date']))) . ' 00:00:00';
                $model->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['end_date']))) . ' 23:59:59';

                $data_detail = $model->getDetailOrders($_POST['order_id']);

                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Số TT')
                    ->setCellValue('B1', 'Số thuê bao')
                    ->setCellValue('C1', 'Số serial')
                    ->setCellValue('D1', 'Mã đơn hàng')
                    ->setCellValue('E1', 'Mã hợp đồng')
                    ->setCellValue('F1', 'Tên bộ kít')
                    ->setCellValue('G1', 'Giá bộ kít')
                    ->setCellValue('H1', 'Trạng thái ghép')
                    ->setCellValue('I1', 'Thời gian ghép kít')
                    ->setCellValue('J1', 'Ghi chú');

                $i         = 2;
                $file_name = "Chi tiết đơn hàng " . $_POST['order_id'];
                if (!empty($data_detail)) {
                    foreach ($data_detail as $row) {
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $i, $i - 1)
                            ->setCellValue('B' . $i, $row['MSISDN'])
                            ->setCellValueExplicit('C' . $i, $row['SERIA_NUMBER'], PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('D' . $i, AFTOrders::model()->getCodeOfOrders($row['ORDER_ID']))
                            ->setCellValue('E' . $i, AFTContracts::model()->getContractCode($row['CONTRACT_ID']))
                            ->setCellValue('F' . $i, AFTPackage::getNameByCode($row['SUB_TYPE']))
                            ->setCellValue('G' . $i, AFTPackage::getPriceByCode($row['SUB_TYPE']))
                            ->setCellValue('H' . $i, AFTReport::getStatusJoinKit($row['ASSIGN_KIT_STATUS']))
                            ->setCellValue('I' . $i, $row['ASSIGN_KIT_TIME'])
                            ->setCellValue('J' . $i, $row['NOTE']);
                        $i++;
                    }
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . 2, 1 - 1)
                        ->setCellValue('B' . 2, '')
                        ->setCellValue('C' . 2, '')
                        ->setCellValue('D' . 2, '')
                        ->setCellValue('E' . 2, '')
                        ->setCellValue('F' . 2, '')
                        ->setCellValue('G' . 2, '');
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
            } else {
                echo "Chưa có dữ liệu";
            }

        }

        public function controllDataDetailAffiliate($data_sim, $data_package)
        {
            $orders = array();
            $result = array();
            if (is_array($data_sim) && !empty($data_sim)) {
                foreach ($data_sim as $key => $value) {
                    if (isset($value->order_code)) {
                        if (!in_array($value->order_code, $orders)) {
                            array_push($orders, $value->order_code);
                        }
                    }
                }
            }
            if (is_array($data_package) && !empty($data_package)) {
                foreach ($data_package as $key => $value) {
                    if (isset($value->order_code)) {
                        if (!in_array($value->order_code, $orders)) {
                            array_push($orders, $value->order_code);
                        }
                    }
                }
            }

            foreach ($orders as $order) {

                $result_key = array(
                    'order_code'      => $order,
                    'vnp_province_id' => '',
                    'msisdn'          => '',
                    'package_name'    => '',
                    'action_status'   => '',
                    'price_sim'       => 0,
                    'price_package'   => 0,
                    'transaction_id'  => 0,
                    'renueve_sim'     => 0,
                    'sub_type'        => '',
                    'renueve_package' => 0,
                    'publisher_id'    => '',
                    'amount_sim'      => 0,
                    'amount_package'  => 0,
                );
                foreach ($data_sim as $key => $value) {
                    if ($value->order_code == $order) {
                        $result_key['order_code']      = $value->order_code;
                        $result_key['vnp_province_id'] = $value->vnp_province_id;
                        $result_key['msisdn']          = $value->msisdn;
                        $result_key['action_status']   = $value->action_status;
                        $result_key['publisher_id']    = $value->publisher_id;
                        $result_key['transaction_id']  = $value->transaction_id;
                        $result_key['price_sim']       = $value->price_sim;
                        $result_key['amount_sim']      = ($value->action_status == 3) ? $value->amount : 0;
                        $result_key['sub_type']        = $value->type;
                        $result_key['renueve_sim']     = $value->total_money;
                    }
                }
                foreach ($data_package as $key => $value) {
                    if ($value->order_code == $order) {
                        $result_key['order_code']      = $value->order_code;
                        $result_key['vnp_province_id'] = $value->vnp_province_id;
                        $result_key['msisdn']          = $value->msisdn;
                        $result_key['action_status']   = $value->action_status;
                        $result_key['publisher_id']    = $value->publisher_id;
                        $result_key['package_name']    = $value->product_name;
                        $result_key['price_package']   = $value->price_package;
                        $result_key['sub_type']        = $value->type;
                        $result_key['amount_package']  = ($value->action_status == 3) ? $value->amount : 0;
                        $result_key['renueve_package'] = $value->total_money;
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }


    }