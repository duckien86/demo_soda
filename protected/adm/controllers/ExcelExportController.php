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

    /*
     * Xuất csv tra cứu đơn hàng gói đơn lẻ
     */

    public function actionOrderPackageSingle()
    {
        $model = new AOrders('search');
        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $criteria = $model->searchPackageSingle('', TRUE);
            $tableSchema = $model->getTableSchema();
            $command = AOrders::model()->getCommandBuilder()->createFindCommand($tableSchema, $criteria);
            $data = $command->queryAll();

            $result = array();
            $result[0] = array(
                'ID' => 'Mã ĐH',
                'PHONE_CONTACT' => 'Số TB',
                'CHANNEL' => 'Kênh bán',
                'ITEM_NAME' => 'Tên gói',
                'PACKAGE_REGISTER_DATE' => 'Thời gian mở gói',
                'REVENUE' => 'Doanh thu',
                'STATUS' => 'Trạng thái',
            );

            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {

                    $result[$stt] = array(
                        'ID' => $item['id'],
                        'PHONE_CONTACT' => $item['phone_contact'],
                        'CHANNEL' => (!empty($item['promo_code'])) ? $item['promo_code'] : $item['affiliate_source'],
                        'ITEM_NAME' => $item['item_name'],
                        'PACKAGE_REGISTER_DATE' => $item['package_register_date'],
                        'REVENUE' => $item['total_renueve'],
                        'STATUS' => AOrders::getStatus($item['id']),
                    );

                    $stt++;
                }
            }

            $file_name = "Danh sách đơn hàng gói đơn lẻ từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    /*
     * Xuất csv chi tiết đơn hàng fiber
     */

    public function actionOrderDetailFiber()
    {
        $model = new AOrders('search_fiber');
        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $criteria = $model->getDetailOrderFiber('', TRUE);
            $tableSchema = $model->getTableSchema();
            $command = AOrders::model()->getCommandBuilder()->createFindCommand($tableSchema, $criteria);
            $data = $command->queryAll();
            $result = array();
            $result[0] = array(
                'create_date' => 'Ngày tạo đơn hàng',
                'ID' => 'Mã ĐH',
                'full_name' => 'Tên KH',
                'phone_contact' => 'Điện thoại',
                'package_fiber' => 'Gói đăng ký',
                'period' => 'Đăng ký TTT',
                'source' => 'Kênh bán',
                'phong_bh' => 'PBH',
                'province_code' => 'TTKD',
                'contact_date' => 'Ngày tạo hợp đồng',
                'staff_contact' => 'NV tạo hợp đồng',
                'staff_code' => 'Mã NV',
                'ma_tb' => 'A/c Fiber Number',
                'goithicong' => 'Gói thi công',
                'sothangttt' => 'Số tháng TTT',
                'cuochoamang' => 'Cước HM',
                'ckhm' => 'CK HM',
                'tienttt' => 'Tiền TTT',
                'cktt' => 'CKTTT',
                'tongtien' => 'Tổng DT',
                'status' => 'Trạng thái',
                'note' => 'Ghi chú',
                'stb_use' => 'STBOX',
            );

            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {

                    $result[$stt] = array(
                        'create_date' => $item['create_date'],
                        'ID' => $item['id'],
                        'full_name' => $item['full_name'],
                        'phone_contact' => $item['phone_contact'],
                        'package_fiber' => $item['package_fiber_name'],
                        'period' => $item['period'],
                        'source' => $item['promo_code'],
                        'phong_bh' => $item['phong_bh'],
                        'province_code' => $item['province_code'],
                        'contact_date' => $item['ngay_ky_hd'],
                        'staff_contact' => $item['ten_nv'],
                        'staff_code' => $item['ma_nv'],
                        'ma_tb' => $item['ma_tb'],
                        'goithicong' => $item['loaihinh_tb'],
                        'sothangttt' => $item['thangtratruoc'],
                        'cuochoamang' => number_format($item['cuochoamang']),
                        'ckhm' => number_format($item['ckhm']),
                        'tienttt' => number_format($item['tienttt']),
                        'cktt' => number_format($item['ckttt']),
                        'tongtien' => number_format($item['cuochoamang'] + $item['ckhm'] + $item['tienttt'] + $item['ckttt']),
                        'status' => AOrders::getStatusFiber($item['id']),
                        'note' => $item['note'],
                        'stb_use' => $item['stb_use'],
                    );

                    $stt++;
                }
            }
            $name = '';
            if($_POST['excelExport']['type_package'] == 'fiber'){
                $name = 'Internet cáp quang';
            }else if($_POST['excelExport']['type_package'] == 'mytv'){
                $name = 'Truyền hình MyTV';
            }else if($_POST['excelExport']['type_package'] == 'combo_fiber_mytv'){
                $name = 'Internet & Truyền hình';
            }else if($_POST['excelExport']['type_package'] == 'home_bundle'){
                $name = 'Internet truyền hình & di động';
            }
            $file_name = "Danh sách đơn hàng " . $name . " từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    /*
     * Xuất csv báo cáo khuyến khích pt thuê bao trả sau
     */
    public function actionIncentiveAgency()
    {

        set_time_limit(0);
        $startdate = Yii::app()->cache->get('incentives_startdate_cache');
        $endate = Yii::app()->cache->get('incentives_endate_cache');
        $list = Yii::app()->cache->get('incentives_detail_cache');
        $results[0] = array(
            'stt' => 'STT',
            'affiliate_channel' => 'Tên đăng nhập',
            'agency_name' => 'Tên ĐLTC',
            'agency_code' => 'Mã ĐLTC',
            'agency_group' => 'Nhóm ĐLTC',
            'order_id' => 'Mã đơn hàng',
            'item_name' => 'Số thuê bao',
            'order_create_date' => 'Ngày mua',
            'type_service' => 'Loại dịch vụ',
            'province_code' => 'TTKD',
            'amount' => 'Thù lao khuyến khích'
        );

        $i = 1;
        $stt = 0;
        foreach ($list as $item) {

            $results[$i] = array(
                'stt' => $stt + 1,
                'affiliate_channel' => $item['affiliate_channel'],
                'agency_name' => $item['agency_name'],
                'agency_code' => $item['affiliate_channel'],
                'agency_group' => 'Có hệ thống',
                'order_id' => $item['order_id'],
                'item_name' => $item['item_name'],
                'active_date' => $item['order_create_date'],
                'type_service' => $item['type_service'],
                'province_code' => $item['province_code'],
                'amount' => $item['amount']
            );
            $i++;
            $stt++;

        }
        $file_name = "Bao_cao_hoa_hong_khuyen_khich_phat_trien_thue_bao_tra_sau_dai_ly_to_chuc_'$startdate'_'$endate'";

        Utils::exportCSV($file_name, $results);
    }

    public function actionReportIndex()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
            $model->sim_type = $_POST['excelExport']['sim_type'];
            $model->payment_method = $_POST['excelExport']['payment_method'];
            $model->receive_status = $_POST['excelExport']['receive_status'];
            $model->input_type = $_POST['excelExport']['input_type'];

            $data_detail = $model->searchDetailRevenueSynthetic(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $results[0] = array(
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'RECEIVE_STATUS' => 'Trạng thái thu tiền',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
                'DELIVERY_DATE' => 'Ngày hoàn tất',
                'PACKAGE_NAME' => 'Tên gói',
                'SHIPPER_NAME' => 'Người hoàn tất',
                'PROVINCE_CODE' => 'TTKD',
                'SALE_OFFICE_CODE' => 'Phòng bán hàng',
                'REVENUE_SIM' => 'Doanh thu sim',
                'REVENUE_PACKAGE' => 'Doanh thu gói',
                'REVENUE_TERM' => 'Tiền đặt cọc',
                'SUM_REVENUE' => 'Tổng doanh thu'
            );

            $i = 1;
            foreach ($data_detail as $item) {

                if (!empty($item->shipper_name)) {
                    $shipper_name = $item->shipper_name;
                } else {
                    $shipper_name = ALogsSim::getUserByOrder($item->id);
                }

                if ($item->type_sim == ASim::TYPE_POSTPAID) {
                    $revenue = $item->renueve_sim + $item->renueve_term;
                } else {
                    $revenue = $item->renueve_sim + $item->renueve_term + $item->renueve_package;
                }

                $results[$i] = array(
                    'ID' => $item->id,
                    'SIM' => $item->sim,
                    'RECEIVE_STATUS' => ReportForm::getNameReceiveStatus($item->receive_status),
                    'PAYMENT_METHOD' => AOrders::getPaymentMethod($item->payment_method),
                    'DELIVERY_DATE' => $item->delivery_date,
                    'PACKAGE_NAME' => $item->item_name,
                    'SHIPPER_NAME' => $shipper_name,
                    'PROVINCE_CODE' => $list_province[$item->province_code],
                    'SALE_OFFICE_CODE' => $list_sale_office[$item->sale_office_code],
                    'REVENUE_SIM' => $item->renueve_sim,
                    'REVENUE_PACKAGE' => $item->renueve_package,
                    'REVENUE_TERM' => $item->renueve_term,
                    'SUM_REVENUE' => $revenue,
                );
                $i++;

            }

            $file_name = "Doanh thu tổng hợp từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $results);
        }
    }


    public function actionReportStatisticsSim()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
            $model->sim_type = $_POST['excelExport']['sim_type'];
            $model->payment_method = $_POST['excelExport']['payment_method'];
            $model->receive_status = $_POST['excelExport']['receive_status'];
            $model->input_type = $_POST['excelExport']['input_type'];
            $model->online_status = $_POST['excelExport']['online_status'];
            $model->channel_code = $_POST['excelExport']['channel_code'];
            $model->item_sim_type = $_POST['excelExport']['item_sim_type'];

            $data_detail = $model->searchDetailStatisticSim(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $results[0] = array(
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'RECEIVE_STATUS' => 'Trạng thái thu tiền',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
                'AFFILIATE_SOURCE' => 'Kênh bán',
                'DELIVERY_DATE' => 'Ngày hoàn tất',
                'PACKAGE_NAME' => 'Tên gói',
                'SHIPPER_NAME' => 'Người hoàn tất',
                'PROVINCE_CODE' => 'TTKD',
                'SALE_OFFICE_CODE' => 'Phòng bán hàng',
                'REVENUE_SIM' => 'Doanh thu sim',
                'REVENUE_PACKAGE' => 'Doanh thu gói',
                'REVENUE_TERM' => 'Tiền đặt cọc',
                'SUM_REVENUE' => 'Tổng doanh thu',
                'ITEM_TYPE_SIM' => 'Loại sim'
            );

            $i = 1;
            foreach ($data_detail as $item) {

                if (!empty($item->shipper_name)) {
                    $shipper_name = $item->shipper_name;
                } else {
                    $shipper_name = ALogsSim::getUserByOrder($item->id);
                }
                $source = "";
                if (!empty($item->promo_code)) {
                    $source = $item->promo_code;
                } else if (!empty($item->affiliate_source)) {
                    $source = $item->affiliate_source;
                }
                if ($item->type_sim == ASim::TYPE_POSTPAID) {
                    $revenue = $item->renueve_sim + $item->renueve_term;
                } else {
                    $revenue = $item->renueve_sim + $item->renueve_term + $item->renueve_package;
                }

                $results[$i] = array(
                    'ID' => $item->id,
                    'SIM' => $item->sim,
                    'RECEIVE_STATUS' => ReportForm::getNameReceiveStatus($item->receive_status),
                    'PAYMENT_METHOD' => AOrders::getPaymentMethod($item->payment_method),
                    'AFFILIATE_SOURCE' => $source,
                    'DELIVERY_DATE' => $item->delivery_date,
                    'PACKAGE_NAME' => $item->item_name,
                    'SHIPPER_NAME' => $shipper_name,
                    'PROVINCE_CODE' => $list_province[$item->province_code],
                    'SALE_OFFICE_CODE' => $list_sale_office[$item->sale_office_code],
                    'REVENUE_SIM' => $item->renueve_sim,
                    'REVENUE_PACKAGE' => $item->renueve_package,
                    'REVENUE_TERM' => $item->renueve_term,
                    'SUM_REVENUE' => $revenue,
                    'ITEM_TYPE_SIM' => $item->item_sim_type,
                );
                $i++;

            }

            $file_name = "Báo cáo thống kê sim và gói kèm sim " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $results);
        }
    }


    public function actionReportStatisticsPackage()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $data = $model->searchDetailStatisticPackage(FALSE);

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'CHANNEL' => 'Kênh bán',
                'SIM_FREEDOO' => 'Loại thuê bao',
                'PACKAGE' => 'Tên gói',
                'PACKAGE_GROUP' => 'Nhóm gói',
                'CREATE_DATE' => 'Ngày mua',
                'RENUEVE_PACKAGE' => 'Doanh thu',
                'STATUS' => 'Trạng thái'
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'ID' => $item->id,
                        'SIM' => $item->phone_contact,
                        'CHANNEL' => (!empty($item->promo_code)) ? $item->promo_code : $item->affiliate_source,
                        'SIM_FREEDOO' => ($item->sim_freedoo == 1) ? 'Freedoo' : 'Vinaphone',
                        'PACKAGE' => $item->item_name,
                        'PACKAGE_GROUP' => Report::getTypeName($item->type_package),
                        'CREATE_DATE' => $item->create_date,
                        'RENUEVE_PACKAGE' => $item->renueve_package,
                        'STATUS' => AOrders::getStatus($item->id)
                    );
                    $i++;
                }
                $file_name = "Chi tiết Thống kê bán gói cước đơn lẻ từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportOnlinePaid()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->sale_office_code = $_POST['excelExport']['sale_office_code'];
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->brand_offices_id = $_POST['excelExport']['brand_offices_id'];
            $model->sim_type = $_POST['excelExport']['sim_type'];
            $model->payment_method = $_POST['excelExport']['payment_method'];
            $model->online_status = $_POST['excelExport']['online_status'];
            $model->input_type = $_POST['excelExport']['input_type'];
            $model->paid_status = $_POST['excelExport']['paid_status'];
            $model->status_type = $_POST['excelExport']['status_type'];
            $model->item_sim_type = $_POST['excelExport']['item_sim_type'];

            $data = $model->getOnlinePaidData();

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $result = array();
            $result[0] = array(
                'ORDER_ID' => 'Mã đơn hàng',
                'MSISDN' => 'Số thuê bao',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
                'PAID_DATE' => 'Ngày thanh toán',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'Phòng bán hàng',
                'REVENUE_SIM' => 'Doanh thu SIM',
                'REVENUE_PACKAGE' => 'Doanh thu gói',
                'PRICE_TERM' => 'Tiền đặt cọc',
                'REVENUE_TOTAL' => 'Tổng doanh thu',
                'STATUS' => 'Trạng thái',
                'NOTE' => 'Ghi chú',
                'ITEM_SIM_TYPE' => 'Loại sim',
            );

            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {
                    $msisdn = '';
                    if ($item['sim'] != '') {
                        $msisdn = $item['sim'];
                    } else {
                        $msisdn = $item['phone_contact'];
                    }

                    $total_price = 0;
                    $sim = ASim::model()->findByAttributes(array('order_id' => $item['order_id']));
                    if ($sim) {
                        if ($item['price_term'] > 0) {
                            $total_price = $item['price_term'] + $item['price_sim'];
                        } else {
                            if ($sim->type == 2) {
                                $total_price = $item['price_sim'];
                            } else {
                                $total_price = $item['price_sim'] + $item['price_package'];
                            }
                        }
                    }

                    $result[$stt] = array(
                        'ORDER_ID' => $item['order_id'],
                        'MSISDN' => $msisdn,
                        'PAYMENT_METHOD' => ReportForm::getPaymentMethod($item['payment_method']),
                        'PAID_DATE' => Report::getPaidDate($item['order_id']),
                        'PROVINCE' => $list_province[$item['province_code']],
                        'SALE_OFFICE' => $list_sale_office[$item['sale_office_code']],
                        'REVENUE_SIM' => $item['price_sim'],
                        'REVENUE_PACKAGE' => $item['price_package'],
                        'PRICE_TERM' => $item['price_term'],
                        'REVENUE_TOTAL' => $total_price,
                        'STATUS' => AOrders::getStatus($item['order_id']),
                        'NOTE' => $item['note'],
                        'ITEM_SIM_TYPE' => $item['item_sim_type'],
                    );
                    $stt++;
                }
            }

            $file_name = "Doanh thu thanh toán Online từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $result);
        }
    }

    public function actionRenueveTourist()
    {
        $model = new AFTReport();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->brand_offices_id = $_POST['excelExport']['user_tourist'];
            $model->sim_type = $_POST['excelExport']['contract_id'];
            $model->payment_method = $_POST['excelExport']['order_id'];
            $model->online_status = $_POST['excelExport']['status_order'];
            $model->input_type = $_POST['excelExport']['item_id'];

            $data = $model->getRenueveDetails(TRUE);

            $result = array();
            $result[0] = array(
                'CONTRACT_CODE' => 'Mã hợp đồng',
                'ORDER_CODE' => 'Mã đơn hàng',
                'CUSTOMER' => 'Khách hàng',
                'OUTPUT' => 'Sản lượng',
                'REVENUE' => 'Doanh thu',
            );

            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {
                    $result[$stt] = array(
                        'CONTRACT_CODE' => $item['contract_code'],
                        'ORDER_CODE' => $item['code'],
                        'CUSTOMER' => AFTUsers::model()->getUserById($item['user_tourist']),
                        'OUTPUT' => $item['total'],
                        'REVENUE' => $item['total_renueve'],
                    );
                    $stt++;
                }
            }

            $file_name = "Báo cáo doanh thu SIM du lịch " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $result);
        }
    }

    public function actionReportTouristRevenue()
    {
        if (isset($_POST['excelExport'])) {
            $model = new AFTReport(FALSE);
            $model->scenario = 'export';
            $model->attributes = $_POST['excelExport'];

            $data = $model->searchRevenueDetail(FALSE);

            $result = array();
            $result[0] = array(
                'CONTRACT_CODE' => 'Mã hợp đồng',
                'ORDER_CODE' => 'Mã đơn hàng',
                'CUSTOMER' => 'Khách hàng',
                'PACKAGE_NAME' => 'Sản phẩm',
                'CREATE_DATE' => 'Ngày đặt hàng',
                'FINISH_DATE' => 'Ngày hoàn tất',
                'TOTAL_SUCCESS' => 'Sản lượng',
                'REVENUE' => 'Doanh thu',
                'STATUS' => 'Trạng thái',
                'NOTE' => 'Ghi chú',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    if ($item->user_type == AFTUsers::USER_TYPE_CTV) {
                        $arr = explode('@', $item->customer);
                        $customer = $arr[0] . '(CTV)';
                    } else {
                        $customer = $item->customer;
                    }

                    $result[$i] = array(
                        'CONTRACT_CODE' => ($item->user_type != AFTUsers::USER_TYPE_CTV) ? $item->contract_code : '',
                        'ORDER_CODE' => $item->code,
                        'CUSTOMER' => $customer,
                        'PACKAGE_NAME' => $item->package_name,
                        'CREATE_DATE' => $item->create_time,
                        'FINISH_DATE' => $item->finish_date,
                        'TOTAL_SUCCESS' => $item->total_success,
                        'REVENUE' => $item->total_success * $item->price,
                        'STATUS' => AFTOrders::getStatusLabelOrderSim($item->status),
                        'NOTE' => $item->note,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Doanh thu SIM KHDN từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionSocialIndex()
    {
        $model = new AReportSocial();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->customer_id = $_POST['excelExport']['customer_id'];

            $data = $model->getListCustomer(TRUE);

            $result = array();
            $result[0] = array(
                'USERNAME' => 'Tên đăng nhập',
                'PHONE' => 'Số điện thoại',
                'CREATE_TIME' => 'Ngày tham gia',
                'POINT' => 'Điểm tích lũy',
                'LEVEL' => 'Cấp độ thành viên',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'USERNAME' => $item->username,
                        'PHONE' => $item->phone,
                        'CREATE_TIME' => $item->create_time,
                        'POINT' => $item->bonus_point,
                        'LEVEL' => $item->getLevel($item->bonus_point),
                    );
                    $i++;
                }
                $file_name = "Báo cáo diễn đàn từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionSocialUser()
    {
        $model = new AReportSocial();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->status = $_POST['excelExport']['status'];
            $data_likes = $model->getCustomerLikes();
            $data_comment = $model->getCustomerComment();
            $data_post = $model->getCustomerPost();
            $data_sub_point = $model->getCustomerTotalSubPoint();
            $data_redeem = $model->getCustomerTotalRedeem();

            $data = array_merge_recursive($data_likes, $data_post);
            $data = array_merge_recursive($data, $data_comment);
            $data = array_merge_recursive($data, $data_sub_point);
            $data = array_merge_recursive($data, $data_redeem);

            $data = $model->controllDataCustomer($data, $model);

            $result = array();
            $result[0] = array(
                'USERNAME' => 'Tên đăng nhập',
                'TOTAL_LIKE' => 'Tổng số lượt thích',
                'TOTAL_COMMENT' => 'Tổng số bình luận',
                'TOTAL_POST' => 'Tổng số bài đăng',
                'TOTAL_INFRINGE' => 'Số lần vi phạm',
                'TOTAL_CHANGED_POINT' => 'Tổng điểm đã đổi quà',
                'LEVEL' => 'Cấp độ',
                'TOTAL_REMAIN_POINT' => 'Tổng điểm đang có',
                'STATUS' => 'Trạng thái',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'USERNAME' => $item['username'],
                        'TOTAL_LIKE' => $item['total_like'],
                        'TOTAL_COMMENT' => $item['total_comment'],
                        'TOTAL_POST' => $item['total_post'],
                        'TOTAL_INFRINGE' => $item['total_sub_point'],
                        'TOTAL_CHANGED_POINT' => $item['sum_redeem'],
                        'LEVEL' => ACustomers::getLevel($item['total_sub_point']),
                        'TOTAL_REMAIN_POINT' => $item['current_point'],
                        'STATUS' => ($item['status'] == ACustomers::ACTIVE) ? 'Kích hoạt' : 'Ẩn',
                    );
                    $i++;
                }
                $file_name = "Báo cáo thành viên từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportSim()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'PROVINCE' => 'TTKD',
                'TOTAL_SIM_PREPAID' => 'SL SIM trả trước',
                'TOTAL_SIM_POSTPAID' => 'SL SIM trả sau',
                'REVENUE_SIM_PREPAID' => 'Doanh thu SIM trả trước',
                'REVENUE_SIM_POSTPAID' => 'Doanh thu SIM trả sau',
                'REVENUE_SIM' => 'Tổng doanh thu'
            );

            $data = $model->searchRenueveSim(false);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'PROVINCE' => $list_province[$item->province_code],
                        'TOTAL_SIM_PREPAID' => $item->total_sim_prepaid,
                        'TOTAL_SIM_POSTPAID' => $item->total_sim_postpaid,
                        'REVENUE_SIM_PREPAID' => $item->revenue_sim_prepaid,
                        'REVENUE_SIM_POSTPAID' => $item->revenue_sim_postpaid,
                        'REVENUE_SIM' => $item->renueve_sim,
                    );
                    $i++;
                }

                $file_name = "Tổng hợp Doanh thu sim từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);
            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportSimDetail()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'TYPE_SIM' => 'Hình thức',
                'CREATE_DATE' => 'Ngày kích hoạt',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'Phòng bán hàng',
                'PRICE_TERM' => 'Tiền đặt cọc',
                'RENUEVE_SIM' => 'Doanh thu',
                'ITEM_TYPE_SIM' => 'Loại sim',
            );

            $data = $model->searchDetailRenueveSim(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'ID' => $item->id,
                        'SIM' => $item->sim,
                        'TYPE_SIM' => ASim::getTypeLabel($item->type_sim),
                        'CREATE_DATE' => $item->create_date,
                        'PROVINCE' => $list_province[$item->province_code],
                        'SALE_OFFICE' => $list_sale_office[$item->sale_office_code],
                        'PRICE_TERM' => $item->price_term,
                        'RENUEVE_SIM' => $item->renueve_sim,
                        'ITEM_TYPE_SIM' => $item->item_sim_type
                    );
                    $i++;
                }

                $file_name = "Chi tiết Doanh thu sim từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);
            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageSimKit()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result = array();
            $array_columns = array();
            $array_columns['STT'] = 'Số thứ tự';
            $array_columns['PROVINCE'] = 'Trung tâm kinh doanh';

            $data = $model->searchRenuevePackageSimKit(FALSE);
            $data_package = array();
            foreach ($data as $order) {
                foreach ($order->packages as $key => $value) {
                    if (!isset($data_package[$key])) {
                        $data_package[$key] = $value['name'];
                    }
                }
            }
            foreach ($data_package as $key => $value) {
                $array_columns["SL_$key"] = "SL $value";
            }
            foreach ($data_package as $key => $value) {
                $array_columns["DT_$key"] = "DT $value";
            }
            $array_columns['REVENUE'] = 'Tổng doanh thu';

            $result[0] = $array_columns;

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            if (!empty($data)) {
                $i = 1;

                foreach ($data as $item) {
                    $array_data = array();
                    $array_data['STT'] = $i;
                    $array_data['PROVINCE'] = $list_province[$item->province_code];
                    $sum_revenue_by_province = 0;
                    foreach ($data_package as $key => $value) {
                        $array_data["SL_$key"] = $item->packages[$key]['total'];
                    }
                    foreach ($data_package as $key => $value) {
                        $array_data["DT_$key"] = $item->packages[$key]['revenue'];
                        $sum_revenue_by_province += $item->packages[$key]['revenue'];
                    }
                    $array_data['REVENUE'] = $sum_revenue_by_province;

                    $result[$i] = $array_data;
                    $i++;
                }

                $sim_type = ASim::getTypeLabel($model->sim_type);
                $file_name = "Tổng hợp Doanh thu gói cước kèm sim $sim_type từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);
            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageSimKitDetail()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'TYPE_SIM' => 'Hình thức',
                'PACKAGE' => 'Tên gói',
                'PACKAGE_GROUP' => 'Nhóm gói',
                'CREATE_DATE' => 'Ngày mua',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'Phòng bán hàng',
                'RENUEVE_PACKAGE' => 'Doanh thu'
            );

            $data = $model->searchDetailRenuevePackageSimKit(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            if (!empty($data)) {
                $i = 1;

                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'ID' => $item->id,
                        'SIM' => $item->sim,
                        'TYPE_SIM' => ASim::getTypeLabel($item->type_sim),
                        'PACKAGE' => $item->item_name,
                        'PACKAGE_GROUP' => Report::getTypeName($item->type_package),
                        'CREATE_DATE' => $item->create_date,
                        'PROVINCE' => $list_province[$item->province_code],
                        'SALE_OFFICE' => $list_sale_office[$item->sale_office_code],
                        'RENUEVE_PACKAGE' => $item->renueve
                    );
                    $i++;
                }

                $sim_type = ASim::getTypeLabel($model->sim_type);
                $file_name = "Chi tiết Doanh thu gói cước kèm sim $sim_type từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);
            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageSingle()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $data = $model->searchRenuevePackageSingle(FALSE);

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'NAME' => 'Tên gói',
                'PREPAID' => 'Trả trước',
                'POSTPAID' => 'Trả sau',
                'DATA' => 'Data',
                'VAS' => 'Vas',
                'ROAMING' => 'Roaming',
                'REVENUE' => 'Doanh thu'
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'NAME' => $item->item_name,
                        'PREPAID' => ($item->type_package == APackage::PACKAGE_PREPAID) ? $item->total : "",
                        'POSTPAID' => ($item->type_package == APackage::PACKAGE_POSTPAID) ? $item->total : "",
                        'DATA' => ($item->type_package == APackage::PACKAGE_DATA) ? $item->total : "",
                        'VAS' => ($item->type_package == APackage::PACKAGE_VAS) ? $item->total : "",
                        'ROAMING' => ($item->type_package == APackage::PACKAGE_ROAMING) ? $item->total : "",
                        'REVENUE' => $item->renueve_package
                    );
                    $i++;
                }
                $file_name = "Tổng hợp Doanh thu gói cước đơn lẻ từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageSingleDetail()
    {
        $model = new Report(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $data = $model->searchDetailRenuevePackageSingle(FALSE);

            $result = array();
            $result[0] = array(
                'STT' => 'Số thứ tự',
                'ID' => 'Mã đơn hàng',
                'SIM' => 'Số thuê bao',
                'SIM_FREEDOO' => 'Loại thuê bao',
                'PACKAGE' => 'Tên gói',
                'PACKAGE_GROUP' => 'Nhóm gói',
                'CREATE_DATE' => 'Ngày mua',
                'RENUEVE_PACKAGE' => 'Doanh thu'
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'STT' => $i,
                        'ID' => $item->id,
                        'SIM' => $item->phone_contact,
                        'SIM_FREEDOO' => ($item->sim_freedoo == 1) ? 'Freedoo' : 'Vinaphone',
                        'PACKAGE' => $item->item_name,
                        'PACKAGE_GROUP' => Report::getTypeName($item->type_package),
                        'CREATE_DATE' => $item->create_date,
                        'RENUEVE_PACKAGE' => $item->renueve_package
                    );
                    $i++;
                }
                $file_name = "Chi tiết Doanh thu gói cước đơn lẻ từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportCard()
    {
        $model = new ReportOci();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->price_card = $_POST['excelExport']['price_card'];
            $model->province_code = $_POST['excelExport']['province_code'];

            $data = $model->getCardFreedooDetail();

            $result = array();
            $result[0] = array(
                'MSISDN' => 'Số TB nạp thẻ',
                'CREATE_DATE' => 'Ngày mua',
                'PRICE' => 'Mệnh giá',
                'REVENUE' => 'Doanh thu',
                'PROVINCE' => 'Tỉnh',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'MSISDN' => $item['MSISDN'],
                        'CREATE_DATE' => $item['CREATED_DATE'],
                        'PRICE' => $item['NAPTIEN'],
                        'REVENUE' => $item['NAPTIEN'],
                        'PROVINCE' => $item['MATINH'],
                    );
                    $i++;
                }
                $file_name = "Doanh thu thẻ từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportCardFreeDoo()
    {
        $model = new Report();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->price_card = $_POST['excelExport']['price_card'];
            $model->payment_method = $_POST['excelExport']['payment_method'];
            $model->card_type = $_POST['excelExport']['card_type'];
            $model->sim_freedoo = $_POST['excelExport']['sim_freedoo'];

            $data = $model->getCardFreeDooDetails();

            $result = array();
            $result[0] = array(
                'ORDER_ID' => 'Mã đơn hàng',
                'SERVICE_TYPE' => 'Loại dịch vụ',
                'PRICE' => 'Mệnh giá',
                'DISCOUNT' => 'Chiết khấu',
                'REVENUE' => 'Doanh thu',
                'TELCO_TYPE' => 'Loại thuê bao',
                'PHONE_CONTACT' => 'SĐT mua mã',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    if ($item['type'] == 'card') {
                        $type = 'Nạp thẻ';
                    } else {
                        $type = $item['type'];
                    }

                    if (Report::getTypeSim($item['phone_contact'])) {
                        $type_msisdn = "Freedoo";
                    } else {
                        $type_msisdn = "Vinaphone";
                    }

                    $result[$i] = array(
                        'ORDER_ID' => $item['id'],
                        'SERVICE_TYPE' => $type,
                        'PRICE' => $item['item_id'],
                        'DISCOUNT' => '4%',
                        'REVENUE' => $item['price'],
                        'TELCO_TYPE' => $type_msisdn,
                        'PHONE_CONTACT' => $item['phone_contact'],
                        'PAYMENT_METHOD' => AOrders::getPaymentMethod($item['payment_method']),
                    );
                    $i++;
                }
                $file_name = "Doanh thu thẻ từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageFlexible()
    {
        $model = new Report();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            };
            $model->package_id = $_POST['excelExport']['package_id'];
            $model->package_group = $_POST['excelExport']['package_group'];
            $model->period = $_POST['excelExport']['period'];

            $data = $model->getInfoPackageFlexible();

            $result = array();
            $result[0] = array(
                'ORDER_ID' => 'Mã đơn hàng',
                'MSISDN' => 'Số thuê bao',
                'CALL_INTERNAL' => 'Thoại nội mạng',
                'CALL_EXTERNAL' => 'Thoại ngoại mạng',
                'SMS_INTERNAL' => 'SMS nội mạng',
                'SMS_EXTERNAL' => 'SMS ngoại mạng',
                'DATA' => 'DATA',
                'CREATE_DATE' => 'Ngày mua',
                'REVENUE' => 'Doanh thu',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'ORDER_ID' => $item['id'],
                        'MSISDN' => $item['customer_msisdn'],
                        'CALL_INTERNAL' => $item['capacity_call_int'],
                        'CALL_EXTERNAL' => $item['capacity_call_ext'],
                        'SMS_INTERNAL' => $item['capacity_sms_int'],
                        'SMS_EXTERNAL' => $item['capacity_sms_ext'],
                        'DATA' => $item['capacity_data'],
                        'CREATE_DATE' => $item['create_date'],
                        'REVENUE' => $item['total'],
                    );
                    $i++;
                }
                $file_name = "Doanh thu gói linh hoạt từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    // Báo cáo tổng quan giao vận
    public function actionTrafficRenueve()
    {
        $model = new ATraffic();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            };
            $data = $model->search_renueve_report('', FALSE, TRUE);

            $result = array();
            $result[0] = array(
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'ORDER_CODE' => 'Mã đơn hàng',
                'CREATE_DATE' => 'Ngày đặt hàng',
                'SHIPPER' => 'NV Giao vận',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
                'SHIPPER_STATUS' => 'Trạng thái GV',
                'PRICE_SIM' => 'Tiền sim',
                'PRICE_PACKAGE' => 'Tiền gói',
                'PRICE_TERM' => 'Tiền đặt cọc',
                'PRICE_SHIP' => 'Phí vận chuyển',
                'PRICE_TOTAL' => 'Tổng tiền',
            );

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'PROVINCE' => $list_province[$item->province_code],
                        'SALE_OFFICE' => $list_sale_office[$item->sale_office_code],
                        'ORDER_CODE' => $item->id,
                        'CREATE_DATE' => $item->create_date,
                        'SHIPPER' => ATraffic::model()->getShipperName($item->shipper_id),
                        'PAYMENT_METHOD' => AOrders::getPaymentMethod($item->payment_method),
                        'SHIPPER_STATUS' => ATraffic::model()->getStatusTraffic(ATraffic::model()->getStatus($item->id)),
                        'PRICE_SIM' => ATraffic::model()->getRenueveByType('sim', $item->id),
                        'PRICE_PACKAGE' => ATraffic::model()->getRenueveByType('package', $item->id),
                        'PRICE_TERM' => ATraffic::model()->getRenueveByType('price_term', $item->id),
                        'PRICE_SHIP' => ATraffic::model()->getPriceShip($item->id),
                        'PRICE_TOTAL' => ATraffic::model()->getRenueveByType('', $item->id, TRUE),
                    );
                    $i++;
                }
                $file_name = "Báo cáo tổng quan giao vận từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionOrderAdmin()
    {
        $model = new AOrders('search');
        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $criteria = $model->search('', TRUE);
            $tableSchema = $model->getTableSchema();
            $command = AOrders::model()->getCommandBuilder()->createFindCommand($tableSchema, $criteria);
            $data = $command->queryAll();

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');
            $list_brand_office = $this->getCaching('backend_tbl_brand_offices', 'ABrandOffices', 'id');
            $list_district = $this->getCaching('backend_tbl_district_codes', 'ADistrict', 'code');
            $list_ward = $this->getCaching('backend_tbl_ward_codes', 'AWard', 'code');

            $result = array();
            $result[0] = array(
                'ID' => 'Mã ĐH',
                'CHANNEL' => 'Kênh bán',
                //                    'ROSE_SIM'      => 'Hoa hồng TT Sim',
                //                    'ROSE_PACKAGE'  => 'Hoa hồng TT gói',
                //                    'TOTAL_ROSE'    => 'Tổng hoa hồng TT',
                'SIM' => 'Số thuê bao',
                'TYPE_SIM' => 'Loại TB',
                'FULL_NAME' => 'Người nhận',
                'PHONE_CONTACT' => 'SĐT liên hệ',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'BRAND_OFFICE' => 'ĐGD',
                'ADDRESS' => 'Địa chỉ nhận hàng',
                'DELIVERY_TYPE' => 'Hình thức nhận hàng',
                'CREATE_DATE' => 'Thời gian mua hàng',
                'PRE_DATE' => 'Thời gian đặt hàng',
                'STATUS' => 'Trạng thái',
                'SHIPPER_STATUS' => 'Trạng thái GV',
            );

            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {

                    $sale_office = $list_sale_office[$item['sale_office_code']];
                    $brand_office = ($item['delivery_type'] == AOrders::DELIVERY_TYPE_BRAND) ? $list_brand_office[$item['address_detail']] : '';

                    $province = $list_province[$item['province_code']];
                    $district = $list_district[$item['district_code']];

                    if ($item['delivery_type'] == AOrders::COD) {
                        $ward = $list_ward[$item['ward_code']];

                        $address = $item['address_detail'] . ", " . $ward . ", " . $district . ", " . $province;
                    } else {
                        $address = $district . ", " . $province;
                    }

                    $shipper_status = ($item['delivery_type'] == 1)
                        ? AOrders::model()->getStatusTraffic(AOrders::model()->getTrafficStatus($item['id']))
                        : 'Nhận tại ĐGD';

                    //                        $create_date = date('Y-m-d',strtotime(str_replace('/','-', $item->create_date)));
                    //                        $accept_rose_date = date('Y-m-d', strtotime('2018-07-01'));
                    //                        if($create_date >= $accept_rose_date){
                    //                            $rose_sim = $item->getRoseSimProvisional();
                    //                            $rose_package = $item->getRosePackageProvisional();
                    //                        }else{
                    //                            $rose_sim = 0;
                    //                            $rose_package = 0;
                    //                        }

                    $result[$stt] = array(
                        'ID' => $item['id'],
                        'CHANNEL' => (!empty($item['promo_code'])) ? $item['promo_code'] : $item['affiliate_source'],
                        //                            'ROSE_SIM'      => $rose_sim,
                        //                            'ROSE_PACKAGE'  => $rose_package,
                        //                            'TOTAL_ROSE'    => $rose_package + $rose_sim,
                        'SIM' => $item['sim'],
                        'TYPE_SIM' => ASim::getTypeLabel($item['type_sim']),
                        'FULL_NAME' => $item['full_name'],
                        'PHONE_CONTACT' => $item['phone_contact'],
                        'PROVINCE' => $province,
                        'SALE_OFFICE' => $sale_office,
                        'BRAND_OFFICE' => $brand_office,
                        'ADDRESS' => $address,
                        'DELIVERY_TYPE' => AOrders::getDeliveredTypeByType($item['delivery_type']),
                        'CREATE_DATE' => $item['create_date'],
                        'PRE_DATE' => $item['pre_order_date'],
                        'STATUS' => AOrders::getStatus($item['id']),
                        'SHIPPER_STATUS' => $shipper_status,
                    );

                    $stt++;
                }
            }

            $file_name = "Danh sách đơn hàng từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }

    }

    public function getCaching($cache_key, $className, $key)
    {
        if (Yii::app()->cache->get($cache_key)) {
            $result = Yii::app()->cache->get($cache_key);
        } else {
            $result = CHtml::listData($className::model()->findAll(), $key, 'name');
            Yii::app()->cache->set($cache_key, $result, 24 * 60 * 60);
        }
        return $result;
    }

    public function actionOrderAdminTest()
    {
        $model = new AOrders('search');
        if (isset($_POST['excelExport'])) {

            $model->attributes = $_POST['excelExport'];

            $criteria = $model->search('', TRUE);
            $tableSchema = $model->getTableSchema();
            $command = AOrders::model()->getCommandBuilder()->createFindCommand($tableSchema, $criteria);
            $data = $command->queryAll();

            // key = code, value = name
            $district_codes = $this->getCaching('backend_tbl_district_codes', 'ADistrict', 'code');
            //  key = code, value = name
            $province_codes = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            //  key = code, value = name
            $ward_codes = $this->getCaching('backend_tbl_ward_codes', 'AWard', 'code');
            //  key = code, value = name
            $sale_office_codes = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');
            // key = id, value = name
            $brand_offices = $this->getCaching('backend_tbl_brand_offices', 'ABrandOffices', 'id');
            // get all type of sim
            $all_sim_types = array(
                1 => 'Trả trước',
                2 => 'Trả sau'
            );
            $all_delivery_types = array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );

            $result_title = array(
                'ID' => 'Mã ĐH',
                'CHANNEL' => 'Kênh bán',
                'SIM' => 'Số thuê bao',
                'TYPE_SIM' => 'Loại TB',
                'FULL_NAME' => 'Người nhận',
                'PHONE_CONTACT' => 'SĐT liên hệ',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'BRAND_OFFICE' => 'ĐGD',
                'ADDRESS' => 'Địa chỉ nhận hàng',
                'DELIVERY_TYPE' => 'Hình thức nhận hàng',
                'CREATE_DATE' => 'Thời gian mua hàng',
                'PRE_DATE' => 'Thời gian đặt hàng',
                'STATUS' => 'Trạng thái',
                'SHIPPER_STATUS' => 'Trạng thái GV',
                'ITEM_SIM_TYPE' => 'Loại sim',
                'PACKAGE' => 'Gói',
                'STORE_ID' => 'Mã kho',
            );

            // open csv
            $file_name = "Danh sách đơn hàng từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
            header("Content-Type:text/csv, charset=utf-8"); // Config header utf-8.
            header("Content-Disposition:attachment;filename=$file_name.csv");
            $output = fopen("php://output", 'w') or die("Can't open php://output");
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // Config input utf-8
            fputcsv($output, $result_title);
            $stt = 1;
            if (!empty($data)) {
                foreach ($data as $item) {
                    $province = $province_codes[$item['province_code']];
                    $district = $district_codes[$item['district_code']];

                    if ($item['delivery_type'] == AOrders::COD) {
                        $ward = $ward_codes[$item['ward_code']];

                        $address = $item['address_detail'] . ", " . $ward . ", " . $district . ", " . $province;
                    } else {
                        $address = $district . ", " . $province;
                    }

                    $shipper_status = ($item['delivery_type'] == 1)
                        ? AOrders::model()->getStatusTraffic(AOrders::model()->getTrafficStatus($item['id']))
                        : 'Nhận tại ĐGD';
                    $status = AOrders::getStatus($item['id']);

                    $result = array(
                        'ID' => $item['id'],
                        'CHANNEL' => (!empty($item['promo_code'])) ? $item['promo_code'] : $item['affiliate_source'],
                        'SIM' => $item['sim'],
                        'TYPE_SIM' => $all_sim_types[($item['type_sim'])],
                        'FULL_NAME' => $item['full_name'],
                        'PHONE_CONTACT' => $item['phone_contact'],
                        'PROVINCE' => $province,
                        'SALE_OFFICE' => $sale_office_codes[$item['sale_office_code']],
                        'BRAND_OFFICE' => ($item['delivery_type'] == AOrders::DELIVERY_TYPE_BRAND) ? $brand_offices[$item['address_detail']] : '',
                        'ADDRESS' => $address,
                        'DELIVERY_TYPE' => $all_delivery_types[$item['delivery_type']],
                        'CREATE_DATE' => $item['create_date'],
                        'PRE_DATE' => $item['pre_order_date'],
                        'STATUS' => $status,
                        'SHIPPER_STATUS' => $shipper_status,
                        'ITEM_SIM_TYPE' => $item['item_sim_type'],
                        'PACKAGE' => $item['package_name'],
                        'STORE_ID' => Yii::app()->params['stock_config'][$item['store_id']],
                    );
                    // write line
                    fputcsv($output, $result);
                    $stt++;
                }
            }
            // close csv
            fclose($output) or die("Can't close php://output");

        } else {
            echo "Chưa có dữ liệu";
        }

    }

    public function actionSurveyReport()
    {
        $model = new ASurveyReport();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }

            $data = $model->search(FALSE);

            $result = array();
            $result[0] = array(
                'ID' => 'Mã số',
                'CUSTOMER' => 'Khách hàng',
                'ORDER_ID' => 'Mã đơn hàng',
                'PHONE_CONTACT' => 'SĐT',
                'QUESTION' => 'Câu hỏi',
                'ANSWER' => 'Câu trả lời',
                'CONTENT' => 'Nội dung',
                'CREATE_DATE' => 'Ngày tạo',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'ID' => $item->id,
                        'CUSTOMER' => ACustomers::getName($item->user_id),
                        'ORDER_ID' => $item->order_id,
                        'PHONE_CONTACT' => AOrders::getOrderPhoneContact($item->order_id),
                        'QUESTION' => ASurveyQuestion::getQuestionContent($item->question_id),
                        'ANSWER' => ASurveyAnswer::getAnswerContent($item->answer_id),
                        'CONTENT' => $item->content,
                        'CREATE_DATE' => $item->create_date,
                    );
                    $i++;
                }
                $file_name = "Danh sách thống kê khảo sát " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionPtpReport()
    {
        $model = new APrepaidToPostpaid();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            };

            $criteria = new CDbCriteria();
            $criteria->condition = '(t.create_date >= :start_date AND t.create_date <= :end_date) 
                        AND (t.status = :status_fail OR t.status = :status_success OR t.status = :status_time_out)';
            $criteria->params = array(
                ':start_date' => $model->start_date,
                ':end_date' => $model->end_date,
                ':status_fail' => APrepaidToPostpaid::PTP_FAIL,
                ':status_success' => APrepaidToPostpaid::PTP_COMPLETE,
                ':status_time_out' => APrepaidToPostpaid::PTP_OUT_OF_DATE,
            );
            $data = APrepaidToPostpaid::model()->findAll($criteria);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            $result = array();
            $result[0] = array(
                'ID' => 'Mã số',
                'MSISDN' => 'Số thuê bao',
                'PACKAGE' => 'Gói cước',
                'PROVINCE' => 'Tỉnh/Thành phố',
                'RECEIVE_DATE' => 'Thời gian tiếp nhận',
                'FINISH_DATE' => 'Thời gian hoàn thành',
                'STATUS' => 'Trạng thái',
                'USER_ID' => 'User thực hiện',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $result[$i] = array(
                        'ID' => $item->id,
                        'MSISDN' => $item->msisdn,
                        'PACKAGE' => APackage::getPackageNameByCode($item->package_code),
                        'PROVINCE' => $list_province[$item->province_code],
                        'RECEIVE_DATE' => $item->receive_date,
                        'FINISH_DATE' => $item->finish_date,
                        'STATUS' => APrepaidToPostpaid::getStatusLabel($item->status),
                        'USER_ID' => $item->user_id,
                    );
                    $i++;
                }
                $file_name = "Báo cáo chi tiết chuyển đổi TTTS " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionPtpReportSynthetic()
    {
        $model = new APrepaidToPostpaid();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            };

            $criteria = new CDbCriteria();
            $criteria->select = 't.province_code';
            $criteria->distinct = TRUE;
            $criteria->condition = 't.create_date >= :start_date AND t.create_date <= :end_date';
            $criteria->params = array(
                ':start_date' => $model->start_date,
                ':end_date' => $model->end_date,
            );
            $data = APrepaidToPostpaid::model()->findAll($criteria);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            $result = array();
            $result[0] = array(
                'PROVINCE' => 'Tỉnh/Thành phố',
                'TOTAL_RECEIVE' => 'Tổng số tiếp nhận',
                'TOTAL_FINISH' => 'Tổng số hoàn thành',
                'PERCENT' => 'Tỷ lệ',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $receive = APrepaidToPostpaid::getTotalReceiveByProvince($item->province_code, $model->start_date, $model->end_date);
                    $success = APrepaidToPostpaid::getTotalSuccessByProvince($item->province_code, $model->start_date, $model->end_date);
                    $percent = $success / $receive * 100;
                    if ($percent == intval($percent)) {
                        $decimal = 0;
                    } else {
                        $decimal = 2;
                    }
                    $percent = number_format($success / $receive * 100, $decimal, ',', '.') . '%';

                    $result[$i] = array(
                        'PROVINCE' => $list_province[$item->province_code],
                        'TOTAL_RECEIVE' => $receive,
                        'TOTAL_FINISH' => $success,
                        'PERCENT' => $percent,
                    );
                    $i++;
                }
                $file_name = "Báo cáo tổng hợp chuyển đổi TTTS " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionCardUploadFileTemplate()
    {
        $filename = "Mẫu File upload kho thẻ";
        $content = "Serial, Mã thẻ, Mệnh giá, Ngày hết hạn\r\n" .
            "11111111111111, 59550001790004, 50000, 20231230\r\n" .
            "22222222222222, 59550001790005, 50000, 20231230\r\n" .
            "44444444444444, 59550001790006, 50000, 20231230\r\n" .
            "33333333333333, 59550001790007, 50000, 20231230\r\n" .
            "55555555555555, 59550001790008, 50000, 20231230\r\n" .
            "66666666666666, 59550001790009, 50000, 20231230";

        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=$filename.txt");

        print $content;
    }

    public function actionUserAdmin()
    {
        $model = new User();

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

            $data = User::model()->findAll($criteria);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');
            $list_brand_office = $this->getCaching('backend_tbl_brand_offices', 'ABrandOffices', 'id');

            $result = array();
            $result[0] = array(
                'USERNAME' => 'Tài khoản',
                'PHONE' => 'Số điện thoại',
                'EMAIL' => 'E-mail',
                'FULL_NAME' => 'Họ và tên',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'Phòng bán hàng',
                'BRAND_OFFICE' => 'Điểm giao dịch',
                'LAST_VISIT' => 'Lần cuối đăng nhập',
                'ROLE' => 'Chức vụ',
                'STATUS' => 'Trạng thái',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    $regency = '';
                    if ($item->regency == 'ADMIN') {
                        $regency = "ADMIN";
                    } else if ($item->regency == 'STAFF') {
                        $regency = "Quản lý";
                    } else if ($item->regency == 'ACCOUNTANT') {
                        $regency = "Kế toán";
                    }

                    $result[$i] = array(
                        'USERNAME' => $item->username,
                        'PHONE' => $item->phone,
                        'EMAIL' => $item->email,
                        'FULL_NAME' => User::model()->getFullName($item->id),
                        'PROVINCE' => !empty($item->province_code) ? $list_province[$item->province_code] : '',
                        'SALE_OFFICE' => !empty($item->sale_offices_id) ? $list_sale_office[$item->sale_offices_id] : '',
                        'BRAND_OFFICE' => !empty($item->brand_offices_id) ? $list_brand_office[$item->brand_offices_id] : '',
                        'LAST_VISIT' => !empty($item->lastvisit) ? date('Y-m-d H:i:s', $item->lastvisit) : UserModule::t("Not visited"),
                        'ROLE' => $regency,
                        'STATUS' => User::itemAlias("UserStatus", $item->status),
                    );
                    $i++;
                }
                $file_name = "Danh sách người dùng";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionShipperAdmin()
    {
        $model = new AShipper();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $post = $_POST['excelExport']['post'];

            $data = $model->search($post, TRUE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $result = array();
            $result[0] = array(
                'USERNAME' => 'Tên đăng nhập',
                'FULL_NAME' => 'Họ tên',
                'EMAIL' => 'E-mail',
                'PHONE_1' => 'SĐT 1',
                'PHONE_2' => 'SĐT 2',
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'STATUS' => 'Trạng thái',
            );

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {

                    if ($item->status == AShipper::ACTIVE) {
                        $status = AShipper::ACTIVE_TEXT;
                    } else {
                        $status = AShipper::INACTIVE_TEXT;
                    }

                    $result[$i] = array(
                        'USERNAME' => $item->username,
                        'FULL_NAME' => $item->full_name,
                        'EMAIL' => $item->email,
                        'PHONE_1' => $item->phone_1,
                        'PHONE_2' => $item->phone_2,
                        'PROVINCE' => $list_province[$item->province_code],
                        'SALE_OFFICE' => $list_sale_office[$item->sale_offices_code],
                        'STATUS' => $status,
                    );
                    $i++;
                }
                $file_name = "Danh sách nhân viên giao vận";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionOrderReceive()
    {
        $model = new ATraffic();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            $data = $model->search(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            $list_sale_office = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $result = array();
            $result[0] = array(
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'SHIPPER_NAME' => 'Nhân viên hoàn tất',
                'ORDER_ID' => 'Mã ĐH',
                'PRICE_SIM' => 'Tiền SIM',
                'PRICE_PACKAGE' => 'Tiền gói',
                'PRICE_TERM' => 'Tiền đặt cọc',
                'PRICE_SHIP' => 'Tiền ship',
                'PAYMENT_METHOD' => 'Phương thức thanh toán',
                'TOTAL_MONEY' => 'Tổng tiền',
                'TRAFFIC_STATUS' => 'Trạng thái thu tiền',
                'RECEIVE_CASH_BY' => 'Người thu',
                'ITEM_SIM_TYPE' => 'Loại sim'
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    if (!empty($item->receive_cash_by) && !empty($item->receive_cash_date)) {
                        $status = 'Đã thu';
                    } else {
                        $status = 'Chưa thu';
                    }

                    if (!empty($item->shipper_name)) {
                        $shipper_name = $item->shipper_name;
                    } else {
                        $shipper_name = ALogsSim::getUserByOrder($item->id);
                    }

                    $result[$stt] = array(
                        'PROVINCE' => $list_province[$item->province_code],
                        'SALE_OFFICE' => $list_sale_office[$item->sale_office_code],
                        'SHIPPER_NAME' => $shipper_name,
                        'ORDER_ID' => $item->id,
                        'PRICE_SIM' => $item->price_sim,
                        'PRICE_PACKAGE' => $item->price_package,
                        'PRICE_TERM' => $item->price_term,
                        'PRICE_SHIP' => $item->price_ship,
                        'PAYMENT_METHOD' => AOrders::getPaymentMethod($item->payment_method),
                        'TOTAL_MONEY' => $item->getTrafficTotalRevenue(),
                        'TRAFFIC_STATUS' => $status,
                        'RECEIVE_CASH_BY' => $item->receiver,
                        'ITEM_SIM_TYPE' => $item->item_sim_type,
                    );
                    $stt++;
                }

                $file_name = "Danh sách thu tiền từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportSimAt()
    {
        $model = new AReportAT();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->sim_type = $_POST['excelExport']['sim_type'];
            $model->channel_code = $_POST['excelExport']['channel_code'];
            $model->status = $_POST['excelExport']['status'];

            $data = $model->getSimDetails(TRUE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            $result = array();
            $result[0] = array(
                'ORDER_ID' => 'Mã ĐH',
                'MSISDN' => 'Số TB',
                'TYPE_SIM' => 'Hình thức',
                'STATUS' => 'Trạng thái',
                'PROVINCE' => 'TTKD',
                'TRANSACTION_ID' => 'TransID',
                'CHANNEL' => 'Kênh bán hàng',
                'REASON' => 'Lý do',
                'ACTIVE_DATE' => 'Ngày mua hàng',
                'PRICE_SIM' => 'Tiền SIM',
                'PRICE_TERM' => 'Tiền đặt cọc',
                'TOTAL' => 'Tổng',
                'ROSE_SIM' => 'Hoa hồng SIM',
                'ITEM_SIM_TYPE' => 'Loại sim',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    $order_note = '';
                    if ($item->order_status == 0) {
                        $order_note = $item->order_note;
                    }

                    $result[$stt] = array(
                        'ORDER_ID' => $item->order_id,
                        'MSISDN' => $item->item_name,
                        'TYPE_SIM' => AReportATForm::getTypeSimByType($item->sub_type),
                        'STATUS' => AReportATForm::getStatusOrderAT($item->order_status),
                        'PROVINCE' => $list_province[$item->order_province_code],
                        'TRANSACTION_ID' => $item->affiliate_click_id,
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'REASON' => $order_note,
                        'ACTIVE_DATE' => $item->order_create_date,
                        'PRICE_SIM' => $item->item_price,
                        'PRICE_TERM' => $item->item_price_term,
                        'TOTAL' => $item->item_price_term + $item->item_price,
                        'ROSE_SIM' => $item->amount,
                        'ITEM_SIM_TYPE' => $item->item_sim_type
                    );
                    $stt++;
                }

                $file_name = "Hoa hồng SIM ĐLTC từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportTraffic()
    {
        $model = new ReportOci();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }

            $data = $model->getUserTraffixByHour();

            $result = array();
            $result[0] = array(
                'DAY' => 'Ngày',
                'CAMPAIGN' => 'Chiến dịch',
                'CHANNEL' => 'Kênh',
                'TOTAL_ACCESS' => 'Tổng lượt truy cập',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    $result[$stt] = array(
                        'DAY' => $item['RXTIME_DATE'],
                        'CAMPAIGN' => $item['CAMPAIGN'],
                        'CHANNEL' => $item['CHANNEL_CODE'],
                        'TOTAL_ACCESS' => $item['TOTAL'],
                    );
                    $stt++;
                }

                $file_name = "Báo cáo hiệu năng chiến dịch từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPackageAt()
    {
        $model = new AReportAT();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->package_group = $_POST['excelExport']['package_group'];
            $model->package_id = $_POST['excelExport']['package_id'];
            $model->channel_code = $_POST['excelExport']['channel_code'];
            $model->status = $_POST['excelExport']['status'];

            $data = $model->getPackageDetails(TRUE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            $result = array();
            $result[0] = array(
                'ORDER_ID' => 'Mã ĐH',
                'MSISDN' => 'Số TB mua gói',
                'PACKAGE_NAME' => 'Tên gói',
                'PRICE_PACKAGE' => 'Giá gói',
                'STATUS' => 'Trạng thái',
                'CREATE_DATE' => 'Ngày đặt hàng',
                'PACKAGE_GROUP' => 'Nhóm gói',
                'PROVINCE' => 'TTKD',
                'TRANSACTION_ID' => 'TransID',
                'CHANNEL' => 'Kênh bán',
                'REASON' => 'Lý do',
                'REVENUE_PACKAGE' => 'Doanh thu gói đầu tháng',
                'RENEWAL_COUNT' => 'Số lần gia hạn',
                'ROSE_PACKAGE' => 'Hoa hồng bán gói',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    $order_note = '';
                    if ($item->order_status == 0) {
                        $order_note = $item->order_note;
                    }

                    $date = date('Y-m-d', strtotime($item->order_create_date));
                    if ($date < '2018-09-01') {
                        $revenue = ($item->item_price_original == 0) ? 0 : $item->item_price;
                    } else {
                        $revenue = $item->item_price_original;
                    }

                    $result[$stt] = array(
                        'ORDER_ID' => $item->order_id,
                        'MSISDN' => $item->phone_customer,
                        'PACKAGE_NAME' => $item->item_name,
                        'PRICE_PACKAGE' => $item->item_price,
                        'STATUS' => AReportATForm::getStatusOrderAT($item->order_status),
                        'CREATE_DATE' => $item->order_create_date,
                        'PACKAGE_GROUP' => AReportATForm::getPackageGroupByType($item->package_type),
                        'PROVINCE' => $list_province[$item->order_province_code],
                        'TRANSACTION_ID' => $item->affiliate_click_id,
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'REASON' => $order_note,
                        'REVENUE_PACKAGE' => $revenue,
                        'RENEWAL_COUNT' => $item->renewal_count,
                        'ROSE_PACKAGE' => $item->amount,
                    );
                    $stt++;
                }

                $file_name = "Hoa hồng gói ĐLTC từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportAffiliateAt()
    {
        $model = new AReportAT();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {

                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }
            $model->province_code = $_POST['excelExport']['province_code'];
            $model->status = $_POST['excelExport']['status'];

            $data_detail_sim = $model->getSimAffiliateDetails();
            $data_detail_package = $model->getPackageAffiliateDetails();

            $data = self::controllDataDetailAffiliate($data_detail_sim, $data_detail_package);

            $list_province = $this->getCaching('backend_tbl_province_vnp_id', 'AProvince', 'vnp_province_id');

            $result = array();
            $result[0] = array(
                'PROVINCE' => 'TTKD',
                'SALE_OFFICE' => 'PBH',
                'ORDER_ID' => 'Mã ĐH',
                'CTV' => 'CTV',
                'PROMO_CODE' => 'Mã CTV',
                'INVITE_CODE' => 'Mã giới thiệu',
                'TYPE_SIM' => 'Hình thức',
                'STATUS' => 'Trạng thái',
//                    'RENEWAL_COUNT'     => 'Số lần gia hạn',
                'CHANNEL' => 'Kênh bán',
                'PRICE_SIM' => 'Tiền SIM',
                'PRICE_PACKAGE' => 'Tiền gói',
                'TOTAL' => 'Tổng',
                'ROSE_PTCTV2' => 'Hoa hồng phát triển CTV',
                'ROSE_SIM' => 'Hoa hồng SIM',
                'ROSE_PACKAGE' => 'Hoa hồng gói',
                'TOTAL_ROSE' => 'Tổng hoa hồng',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    $amount_ctv = 0;
                    $data_ctv = AReportATForm::getPublisherAward($item['order_code'], $item['action_status']);
                    if (!empty($data_ctv)) {
                        $amount_ctv = $data_ctv[0]['amout'];
                    }

                    $result[$stt] = array(
                        'PROVINCE' => $list_province[$item['vnp_province_id']],
                        'SALE_OFFICE' => SaleOffices::model()->getSaleOfficesByOrder($item['order_code']),
                        'ORDER_ID' => $item['order_code'],
                        'CTV' => ACtvUsers::getUserName($item['publisher_id']),
                        'PROMO_CODE' => ACtvUsers::getOwnerCode($item['publisher_id']),
                        'INVITE_CODE' => ACtvUsers::getInviterCode($item['publisher_id']),
                        'TYPE_SIM' => AReportATForm::getTypeSimByType($item['sub_type']),
                        'STATUS' => AReportATForm::getStatusOrder($item['action_status']),
                        //                            'RENEWAL_COUNT'     =>  $item['renewal_count'],
                        'CHANNEL' => 'AFFILIATE',
                        'PRICE_SIM' => $item['price_sim'],
                        'PRICE_PACKAGE' => $item['price_package'],
                        'TOTAL' => $item['price_sim'] + $item['price_package'],
                        'ROSE_PTCTV2' => $amount_ctv,
                        'ROSE_SIM' => $item['amount_sim'],
                        'ROSE_PACKAGE' => $item['amount_package'],
                        'TOTAL_ROSE' => $item['amount_sim'] + $item['amount_package'] + $amount_ctv,
                    );
                    $stt++;
                }

                $file_name = "Báo cáo hoa hồng affiliate từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportPaidAffiliate()
    {
        $model = new AReportAT();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->year = $_POST['excelExport']['year'];
            $model->month = $_POST['excelExport']['month'];


            $model->province_code = $_POST['excelExport']['province_code'];
            $model->ctv_id = $_POST['excelExport']['ctv_id'];
            $model->ctv_type = $_POST['excelExport']['ctv_type'];

            $data = $model->getPaidAffiliateDetails();

            $list_province = $this->getCaching('backend_tbl_province_vnp_id', 'AProvince', 'vnp_province_id');

            $result = array();
            $result[0] = array(
                'CTV' => 'CTV',
                'PROMO_CODE' => 'Mã CTV',
                'INVITE_CODE' => 'Mã GT',
                'BANK' => 'Ngân hàng',
                'ACCOUNT_NAME' => 'Tên tài khoản',
                'BANK_ACCOUNT' => 'Số tài khoản',
                'PROVINCE' => 'TTKD',
                'PAID_TIME' => 'Thời gian thanh toán',
                'COMMISSION_MONTH' => 'Thù lao tháng đối soát',
                'COMMISSION_REMAIN' => 'Thù lao tồn đọng',
                'TOTAL' => 'Tổng thù lao',
                'STATUS' => 'Trạng thái',
                'REASON' => 'Lý do',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    if ($item['update_time'] != NULL) {
                        $update_time = CHtml::encode($item['update_time']);
                    } else {
                        $update_time = "Chưa thanh toán";
                    }
                    $month = !empty($model->month) ? $model->month : '';

                    $bank = ACtvCommissionStatisticMonth::getBanks($item['publisher_id']);

                    $amount_receive = ACtvCommissionStatisticMonth::getCommisionReceive($item['publisher_id'], $month, $item['transaction_id']);
                    $reason = '';
                    if ($bank == '' && $item['status'] != 10) {
                        $reason = "Chưa đủ thông tin thanh toán!";
                    } else if ($item['status'] != 10 && ($item['total_amount'] + $amount_receive) < 200000) {
                        $reason = "Tổng thù lao tháng nhỏ hơn 200.000 đ!";
                    }

                    $result[$stt] = array(
                        'CTV' => ACtvUsers::getUserName($item['publisher_id']),
                        'PROMO_CODE' => ACtvUsers::getOwnerCode($item['publisher_id']),
                        'INVITE_CODE' => ACtvUsers::getInviterCode($item['publisher_id']),
                        'BANK' => ACtvCommissionStatisticMonth::getBanks($item['publisher_id']),
                        'ACCOUNT_NAME' => ACtvCommissionStatisticMonth::getAccountName($item['publisher_id']),
                        'BANK_ACCOUNT' => ACtvCommissionStatisticMonth::getBankAccount($item['publisher_id']),
                        'PROVINCE' => $list_province[$item['vnp_province_id']],
                        'PAID_TIME' => $update_time,
                        'COMMISSION_MONTH' => $item['total_amount'],
                        'COMMISSION_REMAIN' => $amount_receive,
                        'TOTAL' => $item['total_amount'] + $amount_receive,
                        'STATUS' => AReportATForm::getStatusPaid($item['status']),
                        'REASON' => $reason,
                    );
                    $stt++;
                }

                $file_name = "Báo cáo thanh toán tháng " . $_POST['excelExport']['month'] . " năm " . $_POST['excelExport']['year'];

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportARedeemHistory()
    {
        $model = new ARedeemHistory();

        if (isset($_POST['excelExport'])) {

            $data = $model->search(TRUE);

            $result = array();
            $result[0] = array(
                'PHONE_CONTACT' => 'Số điện thoại kháchh hàng',
                'USERNAME' => 'Tên đăng nhập',
                'CREATE_DATE' => 'Ngày đổi quà',
                'PACKAGE_CODE' => 'Mã gói',
                'EXCHANGE_POINT' => 'Số điểm đổi',
            );

            if (!empty($data)) {

                $stt = 1;
                foreach ($data as $item) {

                    $result[$stt] = array(
                        'PHONE_CONTACT' => $item->msisdn,
                        'USERNAME' => $item->username,
                        'CREATE_DATE' => $item->create_date,
                        'PACKAGE_CODE' => $item->package_code,
                        'EXCHANGE_POINT' => $item->point_amount,
                    );
                    $stt++;
                }

                $file_name = "Lịch sử đổi quà";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionExportDetailSimTourist($show_contract = TRUE)
    {
        $model = new AFTReport(TRUE);

        if (isset($_POST['order_id'])) {
            $order = AFTOrders::model()->findByPk($_POST['order_id']);
            if ($order) {
                if ($show_contract) {
                    $data_detail = $model->getDetailOrders($_POST['order_id']);
                } else {
                    $data_detail = $model->getDetailOrdersCtv($_POST['order_id']);
                }

                $file_name = "Chi tiết đơn hàng " . AFTOrders::getOrderCode($_POST['order_id']);

                Utils::exportCSV($file_name, $data_detail);
            } else {
                echo "Không tìm thấy đơn hàng";
            }
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
                'order_code' => $order,
                'vnp_province_id' => '',
                'msisdn' => '',
                'package_name' => '',
                'action_status' => '',
                'price_sim' => 0,
                'price_package' => 0,
                'transaction_id' => 0,
                'renueve_sim' => 0,
                'sub_type' => '',
                'renueve_package' => 0,
                'publisher_id' => '',
                'amount_sim' => 0,
                'amount_package' => 0,
            );
            foreach ($data_sim as $key => $value) {
                if ($value->order_code == $order) {
                    $result_key['order_code'] = $value->order_code;
                    $result_key['vnp_province_id'] = $value->vnp_province_id;
                    $result_key['msisdn'] = $value->msisdn;
                    $result_key['action_status'] = $value->action_status;
                    $result_key['publisher_id'] = $value->publisher_id;
                    $result_key['transaction_id'] = $value->transaction_id;
                    $result_key['price_sim'] = $value->price_sim;
                    $result_key['amount_sim'] = ($value->action_status == 3) ? $value->amount : 0;
                    $result_key['sub_type'] = $value->type;
                    $result_key['renueve_sim'] = $value->total_money;
                }
            }
            foreach ($data_package as $key => $value) {
                if ($value->order_code == $order) {
                    $result_key['order_code'] = $value->order_code;
                    $result_key['vnp_province_id'] = $value->vnp_province_id;
                    $result_key['msisdn'] = $value->msisdn;
                    $result_key['action_status'] = $value->action_status;
                    $result_key['publisher_id'] = $value->publisher_id;
                    $result_key['package_name'] = $value->product_name;
                    $result_key['price_package'] = $value->price_package;
                    $result_key['sub_type'] = $value->type;
                    $result_key['amount_package'] = ($value->action_status == 3) ? $value->amount : 0;
                    $result_key['renueve_package'] = $value->total_money;
                }
            }
            $result[] = $result_key;
        }

        return $result;
    }

    /**
     * CSV
     */
    public function actionCardStoreBusinessReportImport()
    {
        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];
            $model->import_code = $_POST['excelExport']['import_code'];

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'CREATE_DATE' => 'Ngày tạo',
                'IMPORT_CODE' => 'Mã lệnh nhập kho',
                'VALUE' => 'Mệnh giá thẻ',
                'QUANTITY' => 'Số lượng',
                'USER_CREATE' => 'User thực hiện',
            );

            $criteria = new CDbCriteria;
            $criteria->compare('t.import_code', $model->import_code, TRUE);
            $criteria->select = 't.create_date, t.import_code, t.user_create, t.value, count(*) as quantity';
            $criteria->order = 't.create_date DESC';
            $criteria->group = 't.value';

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                $criteria->addCondition("t.create_date is not NULL AND t.create_date >= '$model->start_date' AND t.create_date <= '$model->end_date'");
            }

            $data = ACardStoreBusiness::model()->findAll($criteria);

            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['CREATE_DATE'] = $item->create_date;
                    $row['IMPORT_CODE'] = $item->import_code;
                    $row['VALUE'] = $item->value;
                    $row['QUANTITY'] = $item->quantity;
                    $row['USER_CREATE'] = $item->user_create;

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo nhập kho từ " . $model->start_date . " đến " . $model->end_date;

            Utils::exportCSV($file_name, $result);
        } else {
            echo "Chưa có dữ liệu";
        }
    }

    public function actionCardStoreBusinessReportExport()
    {
        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];
            $model->order_code = $_POST['excelExport']['order_code'];
            $model->status = $_POST['excelExport']['status'];

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'CREATE_DATE' => 'Ngày tạo',
                'ORDER_CODE' => 'Mã đơn hàng',
                'VALUE' => 'Mệnh giá thẻ',
                'SERIAL' => 'Số Serial',
                'STATUS' => 'Trạng thái',
                'NOTE' => 'Ghi chú',
            );

            $criteria = new CDbCriteria;
            $criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
            $criteria->select = 'od.create_time as create_date, od.code as order_code, t.serial, t.value, t.status, t.note';
            $criteria->compare('od.code', $model->order_code, TRUE);
            $criteria->compare('t.status', $model->status, TRUE);
            $criteria->order = 'od.create_time DESC';

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                $criteria->addCondition("od.create_time is not NULL AND od.create_time >= '$model->start_date' AND od.create_time <= '$model->end_date'");
            }

            $data = ACardStoreBusiness::model()->findAll($criteria);

            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['CREATE_DATE'] = $item->create_date;
                    $row['ORDER_CODE'] = $item->order_code;
                    $row['VALUE'] = $item->value;
                    $row['SERIAL'] = "'" . $item->serial;
                    $row['STATUS'] = ACardStoreBusiness::getStatusLabel($item->status);
                    $row['NOTE'] = $item->note;

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo xuất kho từ " . $model->start_date . " đến " . $model->end_date;

            Utils::exportCSV($file_name, $result);
        } else {
            echo "Chưa có dữ liệu";
        }
    }


    public function actionCardStoreBusinessReportRemain()
    {
        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $create_date = $_POST['excelExport']['create_date'];

            if ($model->create_date) {
                $model->create_date = date("Y-m-d", strtotime(str_replace('/', '-', $create_date))) . ' 23:59:59';
            }

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'CREATE_DATE' => 'Ngày',
                'VALUE' => 'Mệnh giá thẻ',
                'QUANTITY' => 'Số lượng tồn kho',
            );

            $criteria = new CDbCriteria;
            $criteria->distinct = TRUE;
            $criteria->select = 't.value';
            $criteria->group = 't.value';
            $criteria->order = 't.value ASC';

            $data = ACardStoreBusiness::model()->findAll($criteria);

            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['CREATE_DATE'] = $create_date;
                    $row['VALUE'] = $item->value;
                    $row['QUANTITY'] = ACardStoreBusiness::getCardQuantityByValue($data->value, NULL, 'remain_before', $create_date, $create_date);

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo tồn kho ngày " . $create_date;

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    public function actionCardStoreBusinessReportSynthetic()
    {
        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'VALUE' => 'Mệnh giá thẻ',
                'REMAIN_BEFORE' => 'SL tồn kho đầu kỳ',
                'IMPORT' => 'SL nhập kho trong kỳ',
                'EXPORT_SUCCESS' => 'SL xuất kho trong kỳ',
                'EXPORT_FAIL' => '',
                'EXPORT_PENDING' => '',
                'REMAIN_AFTER' => 'SL tồn kho cuối kỳ',
            );
            $result[1] = array(
                'STT' => '',
                'VALUE' => '',
                'REMAIN_BEFORE' => '',
                'IMPORT' => '',
                'EXPORT_SUCCESS' => 'Kích hoạt thành công',
                'EXPORT_FAIL' => 'Kích hoạt thất bại',
                'EXPORT_PENDING' => 'Đang xử lý',
                'REMAIN_AFTER' => '',
            );

            $criteria = new CDbCriteria;
            $criteria->distinct = TRUE;
            $criteria->select = 't.value';
            $criteria->group = 't.value';
            $criteria->order = 't.value ASC';

            $data = ACardStoreBusiness::model()->findAll($criteria);


            if (!empty($data)) {
                $stt = 2;
                foreach ($data as $item) {

                    $remain_before = ACardStoreBusiness::getCardQuantityByValue($item->value, NULL, 'remain_before', $model->start_date, $model->end_date);
                    $import = ACardStoreBusiness::getCardQuantityByValue($item->value, NULL, 'import', $model->start_date, $model->end_date);
                    $export_success = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_SUCCESS, 'export', $model->start_date, $model->end_date);
                    $export_fail = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_FAILED, 'export', $model->start_date, $model->end_date);
                    $export_pending = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_PENDING, 'export', $model->start_date, $model->end_date);
                    $export_active = ACardStoreBusiness::getCardQuantityByValue($item->value, ACardStoreBusiness::CARD_ACTIVATED, 'export', $model->start_date, $model->end_date);
                    $remain_after = ACardStoreBusiness::getCardQuantityByValue($item->value, NULL, 'remain_after', $model->start_date, $model->end_date);

                    $row = array();
                    $row['STT'] = $stt - 1;
                    $row['VALUE'] = $item->value;
                    $row['REMAIN_BEFORE'] = $remain_before;
                    $row['IMPORT'] = $import;
                    $row['EXPORT_SUCCESS'] = $export_success + $export_active;
                    $row['EXPORT_FAIL'] = $export_fail;
                    $row['EXPORT_PENDING'] = $export_pending;
                    $row['REMAIN_AFTER'] = $remain_after;

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo tổng hợp XNT từ " . $model->start_date . " đến " . $model->end_date;

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    public function actionCardStoreBusinessReportCard()
    {
        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->start_date = $_POST['excelExport']['start_date'];
            $model->end_date = $_POST['excelExport']['end_date'];
            $model->order_code = $_POST['excelExport']['order_code'];

            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
            }

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'CREATE_DATE' => 'Ngày tạo',
                'ORDER_CODE' => 'Mã đơn hàng',
                'VALUE' => 'Mệnh giá thẻ',
                'QUANTITY' => 'Số lượng',
                'TOTAL' => 'Tổng tiền',
            );

            $criteria = new CDbCriteria;
            $criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
            $criteria->select = 'od.create_time as create_date, od.code as order_code, t.order_id, t.value, count(*) as quantity';
            $criteria->group = 't.value, t.order_id';
            $criteria->compare('od.code', $model->order_code, TRUE);
            $criteria->order = 'od.create_time DESC, t.value ASC';
            if ($model->start_date && $model->end_date) {
                $model->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->start_date))) . ' 00:00:00';
                $model->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $model->end_date))) . ' 23:59:59';
                $criteria->addCondition("od.create_time is not NULL AND od.create_time >= '$model->start_date' AND od.create_time <= '$model->end_date'");
            }


            $data = ACardStoreBusiness::model()->findAll($criteria);

            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['CREATE_DATE'] = $item->create_date;
                    $row['ORDER_CODE'] = $item->order_code;
                    $row['VALUE'] = $item->value;
                    $row['QUANTITY'] = AFTOrders::getOrderCardQuantity($item->order_id, $item->value);
                    $row['TOTAL'] = AFTOrders::getOrderTotalCard($item->order_id, $item->value);

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo sản lượng từ " . $model->start_date . " đến " . $model->end_date;

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    public function actionCardStoreBusinessReportCardDetail()
    {

        $model = new ACardStoreBusiness();

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];
            $model->order_id = $_POST['excelExport']['order_id'];

            $order = AFTOrders::model()->findByPk($model->order_id);
            if (!$order) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'CREATE_DATE' => 'Ngày tạo',
                'ORDER_CODE' => 'Mã đơn hàng',
                'VALUE' => 'Mệnh giá thẻ',
                'SERIAL' => 'Serial',
                'PIN' => 'Mã bí mật',
                'STATUS' => 'Trạng thái',
            );

            $criteria = new CDbCriteria;
            $criteria->join = 'INNER JOIN tbl_orders od ON t.order_id = od.id';
            $criteria->select = 't.*, od.code as order_code, od.create_time as create_date';
            $criteria->order = 'od.create_time DESC, t.value ASC, t.serial ASC, t.status DESC';
            $criteria->addCondition('t.order_id = ' . $model->order_id);

            $data = ACardStoreBusiness::model()->findAll($criteria);

            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['CREATE_DATE'] = $item->create_date;
                    $row['ORDER_CODE'] = $item->order_code;
                    $row['VALUE'] = $item->value;
                    $row['SERIAL'] = "'" . $item->serial;
                    $row['PIN'] = "'" . $item->pin;
                    $row['STATUS'] = ACardStoreBusiness::getStatusLabel($item->status);

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Kho thẻ doanh nghiệp - Báo cáo chi tiết sản lượng mã thẻ đơn hàng " . $order->code;

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }

    public function actionAWCReport()
    {
        $model = new AWCReport();

        if (isset($_POST['AWCReport'])) {
            $model->attributes = $_POST['AWCReport'];
            $data = $model->search(FALSE);

            $result = array();
            $result[0] = array(
                'STT' => 'Stt',
                'NAME' => 'Họ tên người dự đoán',
                'PHONE' => 'Số điện thoại',
                'EMAIL' => 'Email',
                'MATCH' => 'Trận đấu',
                'MATCH_TYPE' => 'Vòng loại',
                'TEAM_SELECTED' => 'Đội lựa chọn',
                'LUCKY_NUMBER' => 'Số may mắn',
                'CREATE_TIME' => 'Thời gian dự đoán',
                'STATUS' => 'Trạng thái',
            );


            if (!empty($data)) {
                $stt = 1;
                foreach ($data as $item) {
                    $row = array();
                    $row['STT'] = $stt;
                    $row['NAME'] = $item->name;
                    $row['PHONE'] = $item->phone;
                    $row['EMAIL'] = $item->email;
                    $row['MATCH'] = $item->match;
                    $row['MATCH_TYPE'] = AWCMatch::getTypeLabel($item->match_type);
                    $row['TEAM_SELECTED'] = AWCTeam::getTeamName($item->team_selected) . " ($item->team_selected)";
                    $row['LUCKY_NUMBER'] = $item->lucky_number;
                    $row['CREATE_TIME'] = date('Y-m-d H:i:s', strtotime($item->create_time));
                    $row['STATUS'] = AWCReport::getStatusLabel($item->status);

                    $result[$stt] = $row;
                    $stt++;
                }
            }

            $file_name = "Thống kê danh sách dự đoán WORLDCUP";

            Utils::exportCSV($file_name, $result);

        } else {
            echo "Chưa có dữ liệu";
        }
    }


    public function actionTouristReportDetailRemunerationSimDetail()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            ini_set('memory_limit', '-1');
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'ORDER_CODE' => 'Mã đơn hàng',
                'PROVINCE_CODE' => 'TTKD',
                'MSISDN' => 'Số Thuê bao',
                'ACTIVE_TIME' => 'Thời gian kích hoạt',
                'CREATE_TIME' => 'Thời gian tính',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationSimDetail(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->inviter_code),
                        'PROMO_CODE' => $item->inviter_code,
                        'ORDER_CODE' => AFTOrders::getOrderCodeById($item->order_code),
                        'PROVINCE_CODE' => AFTOrders::getProvinceCodeById($item->order_code),
                        'MSISDN' => $item->msisdn,
                        'ACTIVE_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->active_date))),
                        'CREATE_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->created_on))),
                        'REVENUE' => $item->price,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo chi tiết Thù lao bán SIM phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionTouristReportDetailRemunerationPackageDetail()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            ini_set('memory_limit', '-1');
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'ORDER_CODE' => 'Mã đơn hàng',
                'PROVINCE_CODE' => 'TTKD',
                'MSISDN' => 'Số Thuê bao',
                'PACKAGE_NAME' => 'Tên gói',
                'BUNDLE' => 'Bundle',
                'ACTIVE_TIME' => 'Thời gian mở gói',
                'CREATE_TIME' => 'Thời gian tính',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationPackageDetail(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->inviter_code),
                        'PROMO_CODE' => $item->inviter_code,
                        'ORDER_CODE' => AFTOrders::getOrderCodeById($item->order_code),
                        'PROVINCE_CODE' => AFTOrders::getProvinceCodeById($item->order_code),
                        'MSISDN' => $item->msisdn,
                        'PACKAGE_NAME' => $item->product_name,
                        'BUNDLE' => ($item->bundle == 1) ? 'Có' : 'Không',
                        'ACTIVE_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->active_date))),
                        'CREATE_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->created_on))),
                        'REVENUE' => $item->price,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo chi tiết Thù lao bán Gói phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionTouristReportDetailRemunerationConsumeDetail()
    {

        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            ini_set('memory_limit', '-1');
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'ORDER_CODE' => 'Mã đơn hàng',
                'PROVINCE_CODE' => 'TTKD',
                'MSISDN' => 'Số Thuê bao',
                'CREATE_TIME' => 'Thời gian đặt hàng',
                'ACTIVE_TIME' => 'Thời gian kích hoạt',
                'CONSUME_TIME' => 'Thời gian tính',
                //                    'PACKAGE_PRICE'    => 'Cước đăng ký gói',
                'PERIOD' => 'Chu kỳ hưởng TD TKC',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationConsumeDetail(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->inviter_code),
                        'PROMO_CODE' => $item->inviter_code,
                        'ORDER_CODE' => AFTOrders::getOrderCodeById($item->order_code),
                        'PROVINCE_CODE' => AFTOrders::getProvinceCodeById($item->order_code),
                        'MSISDN' => $item->msisdn,
                        'CREATE_TIME' => $item->order_time,
                        'ACTIVE_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->active_date))),
                        'CONSUME_TIME' => date('Y-m-d', strtotime(str_replace('/', '-', $item->created_on))),
                        //                            'PACKAGE_PRICE'    => $item->price,
                        'PERIOD' => $item->product_name,
                        'REVENUE' => $item->total_money,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo chi tiết Thù lao tiêu dùng tài khoản chính phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionTouristReportDetailRemunerationSim()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'OUTPUT' => 'Sản lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationSim(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->promo_code),
                        'PROMO_CODE' => $item->promo_code,
                        'OUTPUT' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo tổng quan Thù lao bán SIM phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionTouristReportDetailRemunerationPackage()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'PACKAGE_NAME' => 'Tên gói',
                'PACKAGE_CODE' => 'Mã gói',
                'BUNDLE' => 'Bundle',
                'OUTPUT' => 'Sản lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationPackage(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->promo_code),
                        'PROMO_CODE' => $item->promo_code,
                        'PACKAGE_NAME' => $item->package_name,
                        'PACKAGE_CODE' => $item->package_code,
                        'BUNDLE' => ($item->bundle == 1) ? 'Có' : 'Không',
                        'OUTPUT' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo tổng quan Thù lao bán Gói phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionTouristReportDetailRemunerationConsume()
    {
        $model = new AFTReport(FALSE);
        $model->scenario = 'export';

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'NAME' => 'Tên CTV',
                'PROMO_CODE' => 'Mã CTV',
                'OUTPUT' => 'Số lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationConsume(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'NAME' => ACtvUsers::getNameByCode($item->promo_code),
                        'PROMO_CODE' => $item->promo_code,
                        'OUTPUT' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo tổng quan Thù lao tiêu dùng tài khoản chính phân hệ Sim Du Lịch từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportZaloRemunerationSim()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'TYPE_SIM' => 'Hình thức',
                'QUANTITY' => 'Số lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationSim(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'TYPE_SIM' => ASim::getTypeLabel($item->sub_type),
                        'QUANTITY' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo tổng quan thù lao bán SIM  từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportZaloRemunerationSimDetail()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'ORDER_ID' => 'Mã đơn hàng',
                'PROVINCE' => 'Tỉnh/Thành phố',
                'MSISDN' => 'Số thuê bao',
                'TYPE_SIM' => 'Hình thức',
                'ORDER_CREATE_DATE' => 'Ngày tạo',
                'ACTIVE_TIME' => 'Ngày kích hoạt',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationSimDetail(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'ORDER_ID' => $item->order_id,
                        'PROVINCE' => $list_province[$item->order_province_code],
                        'MSISDN' => $item->item_name,
                        'TYPE_SIM' => ASim::getTypeLabel($item->sub_type),
                        'ORDER_CREATE_DATE' => $item->order_create_date,
                        'ACTIVE_TIME' => date('Y-m-d', strtotime($item->active_time)),
                        'REVENUE' => $item->item_price,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo chi tiết thù lao bán SIM  từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionReportZaloRemunerationPackage()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'ITEM_NAME' => 'Gói cước',
                'TYPE_PACKAGE' => 'Thê loại',
                'QUANTITY' => 'Số lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationPackage(FALSE);

            $list_type = APackage::getListType();

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'ITEM_NAME' => $item->item_name,
                        'TYPE_PACKAGE' => $list_type[$item->package_type],
                        'QUANTITY' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo tổng quan thù lao bán gói từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportZaloRemunerationPackageDetail()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'ORDER_ID' => 'Mã đơn hàng',
                'PROVINCE' => 'Tỉnh/Thành phố',
                'MSISDN' => 'Số thuê bao',
                'PACKAGE' => 'Gói',
                'TYPE_PACKAGE' => 'Thể loại',
                'ORDER_CREATE_DATE' => 'Ngày tạo',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationPackageDetail(FALSE);

            $list_province = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');

            $list_type = APackage::getListType();

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'ORDER_ID' => $item->order_id,
                        'PROVINCE' => $list_province[$item->order_province_code],
                        'MSISDN' => $item->phone_customer,
                        'PACKAGE' => $item->item_name,
                        'TYPE_PACKAGE' => $list_type[$item->package_type],
                        'ORDER_CREATE_DATE' => $item->order_create_date,
                        'REVENUE' => $item->item_price,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo chi tiết thù lao bán gói từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportZaloRemunerationConsume()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'ITEM_NAME' => 'Gói cước',
                'TYPE_PACKAGE' => 'Thê loại',
                'QUANTITY' => 'Số lượng',
                'REVENUE' => 'Doanh Thu',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationConsume(FALSE);

            $list_type = APackage::getListType();

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'ITEM_NAME' => $item->item_name,
                        'TYPE_PACKAGE' => $list_type[$item->package_type],
                        'QUANTITY' => $item->total,
                        'REVENUE' => $item->revenue,
                        'ROSE' => $item->rose,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo tổng quan thù lao tiêu dùng tkc từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportZaloRemunerationConsumeDetail()
    {

        $model = new ReportZalo(FALSE);

        if (isset($_POST['excelExport'])) {
            $model->attributes = $_POST['excelExport'];

            $result[0] = array(
                'CHANNEL' => 'Kênh bán',
                'ORDER_ID' => 'Mã đơn hàng',
                'PROVINCE' => 'TTKD',
                'MSISDN' => 'Số thuê bao',
                'ORDER_CREATE_DATE' => 'Ngày đặt đơn hàng',
                'ACTIVE_TIME' => 'Ngày kích hoạt',
                'CONSUME_TIME' => 'Ngày tính',
                'REVENUE' => 'Doanh Thu',
                'PERIOD' => 'Chu kì hưởng TD TKC',
                'ROSE' => 'Hoa hồng'
            );

            $data = $model->searchRemunerationConsumeDetail(FALSE);

            if (!empty($data)) {
                $i = 1;
                foreach ($data as $item) {
                    $result[$i] = array(
                        'CHANNEL' => AReportATForm::getChannelByCode($item->affiliate_channel),
                        'ORDER_ID' => $item->order_id,
                        'PROVINCE' => AReportATForm::getProvinceById($item->order_province_code),
                        'MSISDN' => $item->item_name,
                        'ORDER_CREATE_DATE' => $item->order_create_date,
                        'ACTIVE_TIME' => $item->active_date,
                        'CONSUME_TIME' => $item->create_date,
                        'REVENUE' => $item->item_price,
                        'PERIOD' => $item->period,
                        'ROSE' => $item->amount,
                    );
                    $i++;
                }
                $file_name = "Báo cáo Zalo chi tiết thù lao tiêu dùng tkc từ $model->start_date đến $model->end_date";

                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }

    public function actionPrepaidtopostpaidAdmin(){
        $model = new APrepaidToPostpaid('search');
        if (isset($_POST['excelExport'])) {

            $model->attributes = $_POST['excelExport'];

            $data    = $model->search(FALSE);

            // key = code, value = name
            $district_codes = $this->getCaching('backend_tbl_district_codes', 'ADistrict', 'code');
            //  key = code, value = name
            $province_codes = $this->getCaching('backend_tbl_province_codes', 'AProvince', 'code');
            //  key = code, value = name
            $ward_codes = $this->getCaching('backend_tbl_ward_codes', 'AWard', 'code');
            //  key = code, value = name
            $sale_office_codes = $this->getCaching('backend_tbl_sale_office_codes', 'ASaleOffices', 'code');

            $result_title = array(
                'ID'          => 'Mã ĐH',
                'SIM'         => 'Số thuê bao',
                'CHANNEL'     => 'Kênh bán',
                'PACKAGE'     => 'Gói cước',
                'FULL_NAME'   => 'Họ tên',
                'ADDRESS'     => 'Địa chỉ',
                'CREATE_DATE' => 'Ngày tạo',
                'TIME_LEFT'   => 'Còn lại',
                'SHIPPER'     => 'User thực hiện',
                'STATUS'      => 'Trạng thái',
            );
            $result = [];
            array_push($result,$result_title);

            if (!empty($data)) {
                foreach ($data as $item) {
                    $province = $province_codes[$item['province_code']];
                    $district = $district_codes[$item['district_code']];
                    $ward = $ward_codes[$item['ward_code']];
                    $address = $item['address_detail'] . ", " . $ward . ", " . $district . ", " . $province;

                    $status = APrepaidToPostpaid::getStatusLabel($item->status);
                    $package = APackage::getPackageNameByCode($item->package_code);

                    $result_item = array(
                        'ID'          => $item->id,
                        'SIM'         => $item->msisdn,
                        'CHANNEL'     => $item->promo_code,
                        'PACKAGE'     => $package,
                        'FULL_NAME'   => $item->full_name,
                        'ADDRESS'     => $address,
                        'CREATE_DATE' => $item->create_date,
                        'TIME_LEFT'   => $item->getTimeLeftAssign($item->create_date, 72)['time'],
                        'SHIPPER'     => (!empty($item->user_id)) ? $item->user_id : Yii::t('adm/label', 'not_assigned'),
                        'STATUS'      => $status,
                    );
                    array_push($result, $result_item);
                }
                $file_name = "Danh sách đơn hàng trả trước sang trả sau từ " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }
        }
    }


    public function actionReportActiveSim(){
        $form = new ReportForm();
        $model = new ReportOci();
        if($_POST && $_POST['excelExport']){
            $form->attributes = $_POST['excelExport'];
            $model->start_date = $form->start_date;
            $model->end_date = $form->end_date;
            $model->vnp_province_id = $form->vnp_province_id;
            $data = $model->getActiveSim();

            $result_title = array(
                'SIM'               => 'Số thuê bao',
                'PACKAGE'           => 'Gói cước',
                'ACTIVE_DATE'       => 'Ngày kích hoạt',
                'CREATE_DATE'       => 'Ngày tạo',
                'VNP_PROVINCE_ID'   => 'Mã tỉnh',
                'ORDER_ID'          => 'Mã đơn hàng',
            );
            $result = [];
            array_push($result,$result_title);

            if (!empty($data)) {
                foreach ($data as $item) {

                    $result_item = array(
                        'SIM'               => $item['MSISDN'],
                        'PACKAGE'           => $item['LOAI_TB'],
                        'ACTIVE_DATE'       => $item['NGAY_KH'],
                        'CREATE_DATE'       => $item['NGAY_HM'],
                        'VNP_PROVINCE_ID'   => $item['MATINH'],
                        'ORDER_ID'          => $item['ORDER_ID'],
                    );
                    array_push($result, $result_item);
                }
                $file_name = "Danh sách sim active " . $_POST['excelExport']['start_date'] . " đến " . $_POST['excelExport']['end_date'];
                Utils::exportCSV($file_name, $result);

            } else {
                echo "Không có dữ liệu!";
            }

        }else{
            echo "Không có dữ liệu!";
        }

    }

}