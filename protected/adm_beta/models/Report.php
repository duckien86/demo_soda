<?php

    class Report
    {
        public $start_date;
        public $end_date;
        public $ctv_id; // Mã CTV
        public $province_code; // Trung tâm kinh doanh
        public $sim_type; // Hình thức sim
        public $on_detail; // Check box detail
        public $package_id; // Check box detail
        public $period; // Check box offices_id
        public $package_group; // Check box detail
        public $district_code;
        public $ward_code;
        public $input_type;
        public $sale_office_code;
        public $brand_offices_id;
        public $msisdn;
        public $online_status; // Trạng thái đơn hàng online
        public $paid_status; // Trạng thái thanh toán
        public $status_type; // Loại trạng thái.

        public $invitation;
        public $create_date;
        public $commission_earned;
        public $total;
        public $price_card;
        public $payment_method;
        public $renueve_term;
        public $delivery_type;

        public $receive_status; // Trạng thái thu tiền

        private $_ora;

        const SIM_TYPE     = 1;
        const CARD_TYPE    = 2;
        const PACKAGE_TYPE = 3;

        const HOME = 1;
        const DGD  = 2;


        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('package_group, package_id, period', 'safe'),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
            );
        }

//        public function __construct()
//        {
//            $this->_ora = Oracle::getInstance();
//            $this->_ora->connect();
//        }

        public function getRenueveIndex()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();

            $criteria->select = "t.province_code,
                                    t.sale_office_code,
                                    s.msisdn as sim,
                                    od.item_name as item_name,
                                    t.id,
                                    t.payment_method,
                                    od.type,od.price,ot.create_date as delivered_date";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "od.type IN ('sim') and ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
            } else {
                $criteria->condition = "ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }
            if ($this->sim_type != '') {
                $criteria->addCondition("s.type = '" . $this->sim_type . "'");
            }
            if ($this->receive_status != '') {
                if ($this->receive_status == 1) {
                    $criteria->addCondition("so.status = '" . $this->receive_status . "' OR t.receive_cash_by is null OR t.receive_cash_date is null");
                } else {
                    $criteria->addCondition("(t.receive_cash_by !='' AND t.receive_cash_by !='')");
                }
            }
            if ($this->input_type != '') {
                if ($this->input_type == 2) { // Nhận tại nhà
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) { // Nhận tại điểm giao dịch
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");
            $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id 
                                INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                                INNER JOIN tbl_sim s ON s.order_id = t.id
                                LEFT JOIN tbl_shipper_order so ON so.order_id = t.id";
            $criteria->group = "t.id, od.type";

            $data = ROrders::model()->findAll($criteria);
            $data = self::controlRenueveIndex($data);

            return $data;
        }

        public static function controlRenueveIndex($data)
        {
            $result     = array();
            $order_list = array();
            foreach ($data as $key => $orders) {
                if (!in_array($orders->id, $order_list)) {
                    array_push($order_list, $orders->id);
                }
            }
            foreach ($order_list as $key => $order_id) {
                $result_key = array(
                    'province_code'    => '',
                    'sale_office_code' => '',
                    'id'               => $order_id,
                    'payment_method'   => '',
                    'sim'              => '',
                    'renueve_sim'      => 0,
                    'delivered_date'   => '',
                    'receive_status'   => '',
                );
                foreach ($data as $key_order => $orders) {
                    if ($order_id == $orders->id) {
                        $result_key['province_code']    = AProvince::model()->getProvince($orders->province_code);
                        $result_key['sale_office_code'] = ASaleOffices::model()->getSaleOffices($orders->sale_office_code);
                        $result_key['payment_method']   = ReportForm::getPaymentMethod($orders->payment_method);
                        if ($orders->type == 'sim') {
                            $result_key['sim'] = $orders->item_name;
                            $result_key['renueve_sim'] += $orders->price;
                        }

                        $result_key['delivered_date'] = $orders->delivered_date;

                        $shipper_order = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                        if ($shipper_order) {

                            $result_key['receive_status'] = ReportForm::getNameReceiveStatus($shipper_order->status);
                        }
                        $orders = AOrders::model()->findByAttributes(array('id' => $order_id));
                        if ($orders) {
                            if (isset($orders->receive_cash_by) && isset($orders->receive_cash_date)) {
                                $result_key['receive_status'] = 'Đã thu';
                            } else {
                                $result_key['receive_status'] = 'Chưa thu';
                            }
                        }
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * Lấy số liệu doanh thu tổnng quan
         *
         * @return static[]
         */
        public function getRenueve()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria            = new CDbCriteria();
            $criteria->condition = "t.create_date >= '$this->start_date' and t.create_date <='$this->end_date' and ot.delivered =10";
            $criteria->select    = "od.type as order_type, sum(od.price) as renueve,count(distinct t.id) as total";

            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->input_type != 1) { // Nếu không chọn đến điểm giao dịch
                if ($this->sale_office_code != '') {
                    $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
                }
            }
            if ($this->input_type != '') {
                $criteria->addCondition("t.delivery_type = '" . $this->input_type . "'");
                if ($this->brand_offices_id != '' && $this->input_type == 1) {
                    $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                }
            }
            if ($this->input_type != 1) { // Nếu không chọn đến điểm giao dịch
                $criteria->join = " INNER JOIN tbl_order_details od ON od.order_id =  t.id	
                                   INNER JOIN tbl_order_state ot ON ot.order_id = t.id";
            } else { // Nếu chọn đến điểm giao dịch
                $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id =  t.id	
                                INNER JOIN tbl_order_state ot ON ot.order_id = t.id";
            }
            $criteria->group = "od.type";
            $data            = ROrders::model()->findAll($criteria);

            return $data;
        }

        public function controllRenuve($data)
        {

            $type   = array(
                'sim'     => 'Sim số',
                'package' => 'Gói cước',
            );
            $result = array();
            foreach ($type as $types => $type_value) {
                $result_key = array(
                    'type'          => '',
                    'total'         => '',
                    'renueve'       => '',
                    'share_renueve' => '',
                );
                foreach ($data as $value) {
                    $result_key['type'] = $type_value;
                    if ($types == $value->order_type) {
                        $result_key['total']   = $value->total;
                        $result_key['renueve'] = $value->renueve;
                        if ($types == 'sim') {
                            $result_key['share_renueve'] = $result_key['total'] * 15000;
                        } else {
                            $result_key['share_renueve'] = $result_key['renueve'] * 0.1;
                        }
                    }
                }
                if (!empty($data)) {
                    $result [] = $result_key;
                }
            }

            return $result;
        }

        /**
         *  Lấy số liệu doanh thu sim.
         */
        public function getSimRenueve()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();

            $criteria->select    = "DISTINCT t.id, sum(od.price) as renueve_sim, count(distinct t.id)  as total_sim, s.type as type_sim";
            $criteria->condition = "od.type = 'sim' and ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }
            if ($this->sim_type != '') {
                $criteria->addCondition("s.type = '" . $this->sim_type . "'");
            }
            if ($this->input_type != '') {
                if ($this->input_type == 2) {
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) {
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");
            $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id 
                               INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                               INNER JOIN tbl_sim s ON od.item_id =  s.id";

            $criteria->group = "s.type";
            $criteria->order = "t.create_date";

            $data = ROrders::model()->findAll($criteria);
//            $data            = new CActiveDataProvider('ROrders', array(
//                'criteria'   => $criteria,
//                'sort'       => array('defaultOrder' => 't.create_date asc'),
//                'pagination' => array(
//                    'params'   => array(
//                        'get'                          => 1,
//                        'ReportForm[start_date]'       => $this->start_date,
//                        'ReportForm[end_date]'         => $this->end_date,
//                        "ReportForm[brand_offices_id]" => $this->brand_offices_id,
//                        "ReportForm[province_code]"    => $this->province_code,
//                        "ReportForm[sim_type]"         => $this->sim_type,
//                        "ReportForm[on_detail]"        => isset($this->on_detail) ? $this->on_detail : "on",
//                        "ReportForm[input_type]"       => isset($this->input_type) ? $this->input_type : '',
//                    ),
//                    'pageSize' => 10,
//                ),
//            ));

            return $data;
        }

        /**
         * @param int $type
         * $type =1 :Trả trước 2:Trả sau
         *
         * @return \___PHPSTORM_HELPERS\static
         */
        public function getSimTerm($type = '')
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();

            $criteria->select    = "sum(od.price) as renueve_term, count(distinct t.id)  as total_sim";
            $criteria->condition = "ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }
            if ($this->input_type != '') {
                if ($this->input_type == 2) {
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) {
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");
            $criteria->addCondition("od.type IN ('price_term')");
            $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id 
                               INNER JOIN tbl_order_state ot ON ot.order_id = t.id";
            $criteria->order = "t.create_date";

            $data = ROrders::model()->findAll($criteria)[0];

            return isset($data->renueve_term) ? $data->renueve_term : 0;
        }

        /**
         * Lấy chi tiết doanh thu sim số.
         */
        public function detailRenueveSim($excel = FALSE)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select    = "t.id, s.type as type, t.province_code, (
                                      SELECT SUM(od1.price)
                                      FROM tbl_order_details as od1
                                      WHERE od1.order_id=od.order_id and od1.type='sim'
                                     ) as renueve_sim, s.msisdn as sim, ot.create_date as create_date, t.full_name as customer_name, t.invitation";
            $criteria->condition = "ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' AND ot.delivered =10";
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }

            if ($this->sim_type != '') {
                $criteria->addCondition("s.type = '" . $this->sim_type . "'");
            }
            if ($this->input_type != '') {
                if ($this->input_type == 2) {
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) {
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");
            $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id 
                                INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                                INNER JOIN tbl_sim s ON od.item_id =  s.id";
            $criteria->group = "t.id";

            if ($excel) {
                $data = ROrders::model()->findAll($criteria);
            } else {
                $data = new CActiveDataProvider('ROrders', array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 't.create_date asc'),
                    'pagination' => array(
                        'params'   => array(
                            'get'                          => 1,
                            'ReportForm[start_date]'       => $this->start_date,
                            'ReportForm[end_date]'         => $this->end_date,
                            "ReportForm[brand_offices_id]" => $this->brand_offices_id,
                            "ReportForm[sale_office_code]" => $this->sale_office_code,
                            "ReportForm[province_code]"    => $this->province_code,
                            "ReportForm[sim_type]"         => $this->sim_type,
                            "ReportForm[on_detail]"        => isset($this->on_detail) ? $this->on_detail : "on",
                            "ReportForm[input_type]"       => isset($this->input_type) ? $this->input_type : '',
                        ),
                        'pageSize' => 10,
                    ),
                ));
            }

            return $data;
        }

        /**
         * Lấy số doanh thu gói cước.
         *
         * @return
         */
        public function getPackageRenueve($type = '')
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select = "od.item_name as item_name, count(distinct t.id) as total_package, sum(od.price) as renueve_package";
            if ($type == '') {
                $criteria->condition = "od.type = 'package' and t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date' and p.type NOT IN ('" . ReportForm::SIMKIT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_INT . "','" . ReportForm::FLEXIBLE_SMS_EXT . "','" . ReportForm::FLEXIBLE_CALL_EXT . "'
                                    ,'" . ReportForm::FLEXIBLE_CALL_INT . "','" . ReportForm::FLEXIBLE_DATA . "') AND ot.delivered =10  AND t.payment_method!=''";
            } else {
                $criteria->condition = "od.type = 'package' and t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date' and p.type ='" . $type . "' AND ot.delivered =10 AND t.payment_method!=''";
            }
            if ($this->package_group != '') {
                $criteria->addCondition("p.type ='" . $this->package_group . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("od.item_id = '" . $this->package_id . "'");
            }

            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }

            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }

            if ($this->input_type != '') {
                if ($this->input_type == 2) {
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) {
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                               INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                               INNER JOIN tbl_package p ON p.code = od.item_id ";
            $criteria->group = "od.item_name";

            $data = new CActiveDataProvider('ROrders', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'ReportForm[start_date]'       => $this->start_date,
                        'ReportForm[end_date]'         => $this->end_date,
                        "ReportForm[package_group]"    => $this->package_group,
                        "ReportForm[brand_offices_id]" => $this->brand_offices_id,
                        "ReportForm[sale_office_code]" => $this->sale_office_code,
                        "ReportForm[package_id]"       => $this->package_id,
                        "ReportForm[on_detail]"        => isset($this->on_detail) ? $this->on_detail : "on",
                        "ReportForm[input_type]"       => isset($this->input_type) ? $this->input_type : '',
                    ),
                    'pageSize' => 5,
                ),
            ));

            return $data;
        }

        /**
         * Lấy chi tiết gói cước.
         *
         * @return
         */
        public function detailRenuevePackage($type = '', $excel = FALSE)
        {

            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select = "t.id, p.type as type, t.province_code, sum(od.price) as renueve ,t.phone_contact, p.name as item_name, od.price, t.create_date";
            if ($type == '') {
                $criteria->condition = "od.type = 'package' and t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date' and p.type NOT IN ('" . ReportForm::SIMKIT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_INT . "','" . ReportForm::FLEXIBLE_SMS_EXT . "','" . ReportForm::FLEXIBLE_CALL_EXT . "'
                                    ,'" . ReportForm::FLEXIBLE_CALL_INT . "','" . ReportForm::FLEXIBLE_DATA . "') AND ot.delivered =10  AND t.payment_method!=''";
            } else {
                $criteria->condition = "od.type = 'package' and t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date' and p.type ='" . $type . "' AND ot.delivered =10  AND t.payment_method!=''";
            }
            if ($this->package_group != '') {
                $criteria->addCondition("p.type ='" . $this->package_group . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("od.item_id = '" . $this->package_id . "'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }
            if ($this->input_type != '') {
                if ($this->input_type == 2) {
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) {
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }

            $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                               INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                               INNER JOIN tbl_package p ON p.code = od.item_id";

            $criteria->group = "t.id";
            if ($excel) {
                $data = ROrders::model()->findAll($criteria);
            } else {
                $data = new CActiveDataProvider('ROrders', array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 't.create_date asc'),
                    'pagination' => array(
                        'params'   => array(
                            'get'                          => 1,
                            'ReportForm[start_date]'       => $this->start_date,
                            'ReportForm[end_date]'         => $this->end_date,
                            'ReportForm[sale_office_code]' => $this->sale_office_code,
                            "ReportForm[package_group]"    => $this->package_group,
                            "ReportForm[package_id]"       => $this->package_id,
                            "ReportForm[on_detail]"        => isset($this->on_detail) ? $this->on_detail : "on",
                            "ReportForm[input_type]"       => isset($this->input_type) ? $this->input_type : '',
                        ),
                        'pageSize' => 10,
                    ),
                ));
            }

            return $data;
        }

        /**
         * Lấy chi tiết thẻ cào.
         */
        public function getDetailCard()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select    = "cu.phone as customer_msisdn, c.price, t.create_date";
            $criteria->condition = "od.type = 'card' and t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date'";
            if ($this->price_card != '') {
                $criteria->addCondition("c.price ='" . $this->price_card . "'");
            }

            $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id 
                               INNER JOIN tbl_card c ON c.serial_number = od.item_name 
                               INNER JOIN tbl_customers cu ON cu.sso_id = t.sso_id";

            $data = new CActiveDataProvider('ROrders', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                    => 1,
                        'ReportForm[start_date]' => $this->start_date,
                        'ReportForm[end_date]'   => $this->end_date,
                        "ReportForm[price_card]" => $this->price_card,
                        "ReportForm[on_detail]"  => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 10,
                ),
            ));

            return $data;
        }

        /**
         * Lấy dữ liệu gói cước linh hoạt
         */
        public function getInfoPackageFlexible()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select    = "t.id,
                                    IF(p.type = '" . ReportForm::FLEXIBLE_CALL_INT . "',p.short_description,'') as capacity_call_int,
                                    IF(p.type = '" . ReportForm::FLEXIBLE_CALL_EXT . "',p.short_description,'') as capacity_call_ext,
                                    IF(p.type = '" . ReportForm::FLEXIBLE_SMS_INT . "',p.short_description,'') as capacity_sms_int,
                                    IF(p.type = '" . ReportForm::FLEXIBLE_SMS_EXT . "',p.short_description,'') as capacity_sms_ext,
                                    IF(p.type = '" . ReportForm::FLEXIBLE_DATA . "',p.short_description,'') as capacity_data,
                                    t.create_date, sum(od.price) as total, t.phone_contact as customer_msisdn";
            $criteria->condition = "t.create_date >='$this->start_date' 
                                    and t.create_date <='$this->end_date'";
            if ($this->period != '') {
                $criteria->addCondition("p.period ='" . $this->period . "'");
            }
            if ($this->package_group != '') {
                $criteria->addCondition("p.type ='" . $this->package_group . "'");
            } else {
                $criteria->addCondition("p.type IN('" . ReportForm::FLEXIBLE_CALL_INT . "',
                                    '" . ReportForm::FLEXIBLE_CALL_EXT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_INT . "',
                                    '" . ReportForm::FLEXIBLE_SMS_EXT . "',
                                    '" . ReportForm::FLEXIBLE_DATA . "')");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("p.code ='" . $this->package_id . "'");
            }
            $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                                INNER JOIN tbl_order_state ot ON ot.order_id = t.id AND ot.delivered =10
                                INNER JOIN {{package}} p ON od.item_id = p.code";
            $criteria->group = 't.id, od.item_id';


            $data = self::controlDataPackageFlexible(ROrders::model()->findAll($criteria));

            return $data;
        }

        /**
         * Xử lý dữ liệu gói cước linh hoạt
         */
        public static function controlDataPackageFlexible($data)
        {
            $order  = array();
            $result = array();
            foreach ($data as $key => $value) {
                if (!in_array($value->id, $order)) {
                    array_push($order, $value->id);
                }
            }
            foreach ($order as $key => $order_id) {
                $result_key = array(
                    'id'                => '',
                    'customer_msisdn'   => '',
                    'capacity_call_int' => '',
                    'capacity_call_ext' => '',
                    'capacity_sms_int'  => '',
                    'capacity_sms_ext'  => '',
                    'capacity_data'     => '',
                    'total'             => 0,
                    'create_date'       => '',
                );
                foreach ($data as $key_data => $value) {
                    if ($value->id == $order_id) {
                        $result_key['id']              = $order_id;
                        $result_key['customer_msisdn'] = $value->customer_msisdn;
                        $result_key['create_date']     = $value->create_date;
                        if ($result_key['capacity_call_int'] == '') {
                            $result_key['capacity_call_int'] = $value->capacity_call_int;
                        }
                        if ($result_key['capacity_call_ext'] == '') {
                            $result_key['capacity_call_ext'] = $value->capacity_call_ext;
                        }
                        if ($result_key['capacity_sms_int'] == '') {
                            $result_key['capacity_sms_int'] = $value->capacity_sms_int;
                        }
                        if ($result_key['capacity_sms_ext'] == '') {
                            $result_key['capacity_sms_ext'] = $value->capacity_sms_ext;
                        }
                        if ($result_key['capacity_data'] == '') {
                            $result_key['capacity_data'] = $value->capacity_data;
                        }
                        $result_key['total'] += $value->total;
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }

        public function getOnlinePaidData()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();

            $criteria->select = "t.id,
                                t.payment_method,
                                t.province_code,
                                t.sale_office_code,
                                os.create_date as paid_date,
                                od.type as type,
                                od.price as price,
                                od.item_name as item_name,
                                t.phone_contact,
                                IF (od.type='sim',od.item_name,0) as sim,
                                os.note as note";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t.create_date>='" . $this->start_date . "' and  t.create_date <='" . $this->end_date . "'
                                        AND t.payment_method !=''
                                        AND od.type IN ('sim','package','price_term')
                                        AND payment_method IN('1','2','3','6')";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }

            if ($this->input_type != '') {
                if ($this->input_type == 2) { // Nhận tại nhà
                    if ($this->brand_offices_id != '') {
                        $criteria->addCondition("t.address_detail = '" . $this->brand_offices_id . "'");
                    } else {
                        $criteria->addCondition("t.delivery_type = '2'");
                    }
                } else if ($this->input_type == 1) { // Nhận tại điểm giao dịch
                    $criteria->addCondition("t.delivery_type = '1'");
                }
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            if ($this->status_type == 1) {
                if ($this->online_status != '') {
                    if ($this->online_status == 10) {
                        $criteria->addCondition("os.delivered = 10");
                    } else if ($this->online_status == 9) {
                        $criteria->addCondition("(os.paid = 10) and os.delivered !=10 and os.confirm NOT IN ('1','2') ");
                    } else if ($this->online_status == 3) {
                        $criteria->addCondition("os.confirm =3 ");
                    } else {
                        $criteria->addCondition("os.confirm IN ('1','2') ");
                    }
                }
            } else {
                if ($this->paid_status != '') {
                    if ($this->paid_status == 0) {
                        $criteria->addCondition("(SELECT COUNT(os1.order_id) FROM tbl_order_state os1 where os1.order_id= os.order_id and os1.paid=10)=0");
                    } else {
                        $criteria->addCondition("(SELECT COUNT(os1.order_id) FROM tbl_order_state os1 where os1.order_id= os.order_id and os1.paid=10)>0");
                    }
                }
            }
            if ($this->status_type == 1) {
                $criteria->addCondition('os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)');
            }
            $criteria->join  = "INNER JOIN tbl_order_state os ON os.order_id = t.id
                               INNER JOIN tbl_order_details od ON od.order_id = t.id";

            $data = ROrders::model()->findAll($criteria);

            $data = self::controllOnlinePaid($data);

            return $data;
        }

        public static function controllOnlinePaid($data)
        {
            $result = array();
            $orders = array();
            if (is_array($data) && !empty($data)) {
                foreach ($data as $key => $value) {
                    if (!in_array($value->id, $orders)) {
                        array_push($orders, $value->id);
                    }
                }
            }
            foreach ($orders as $order_id) {
                $result_key = array(
                    'order_id'         => $order_id,
                    'sim'              => '',
                    'payment_method'   => '',
                    'paid_date'        => '',
                    'province_code'    => '',
                    'sale_office_code' => '',
                    'price_sim'        => 0,
                    'price_package'    => 0,
                    'price_term'       => 0,
                    'phone_contact'    => '',
                    'status'           => 0,
                    'note'             => '',
                );
                foreach ($data as $key => $value) {
                    if ($value->id == $order_id) {
                        $result_key['payment_method']   = $value->payment_method;
                        $result_key['paid_date']        = $value->paid_date;
                        $result_key['province_code']    = $value->province_code;
                        $result_key['sale_office_code'] = $value->sale_office_code;
                        $result_key['phone_contact']    = $value->phone_contact;
                        $result_key['status']           = $value->status;
                        $result_key['note']             = $value->note;
                        if ($value->type == 'sim') {
                            $result_key['price_sim'] = $value->price;
                            $result_key['sim'] = $value->sim;
                        }
                        if ($value->type == 'package') {
                            $result_key['price_package'] = $value->price;
                        }
                        if ($value->type == 'price_term') {
                            $result_key['price_term'] = $value->price;
                        }
                    }
                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * @return array
         * Daily report : Lấy doanh thu bán hàng
         */
        public function getEmailSellRenueve()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "DATE(t.create_date) as date, COUNT(DISTINCT t.id) as total, 
                                SUM(od.price) as renueve, od.type as order_type, s.type as type";

            $criteria->condition = "os.id=(SELECT max(os2.id) FROM tbl_order_state os2 WHERE os2.order_id = t.id) 
                                     AND os.delivered=10 AND t.payment_method !=''
                                     AND t.create_date >=' $this->start_date' 
                                     AND t.create_date <='$this->end_date'";

            $criteria->join  = "INNER JOIN  tbl_order_state os ON os.order_id = t.id
                                INNER JOIN tbl_order_details od ON od.order_id = t.id
                                LEFT JOIN tbl_sim s ON s.order_id = t.id";
            $criteria->group = "DATE(t.create_date), s.type, od.type HAVING od.type NOT IN ('price_ship','price_term')";

            $data = self::controllEmailSellRenueve(ROrders::model()->findAll($criteria));


            return $data;
        }

        /**
         * @param $data
         *
         * @return array
         * Daily report : Xử lý dữ liệu doanh thu bán hàng.
         */
        public static function controllEmailSellRenueve($data)
        {

            $result = array();
            $dates  = array();
            if (!empty($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        if (!in_array($value->date, $dates)) {
                            array_push($dates, $value->date);
                        }
                    }
                }
            }
            foreach ($dates as $date) {
                $result_key = array(
                    'sim_pre_total'    => 0,
                    'sim_post_total'   => 0,
                    'package_total'    => 0,
                    'sim_pre_renueve'  => 0,
                    'sim_post_renueve' => 0,
                    'package_renueve'  => 0,
                    'date'             => $date,
                );
                foreach ($data as $key => $value) {
                    if ($value->date == $date) {
                        if ($value->order_type == 'sim' && $value->type == '1') {
                            $result_key['sim_pre_total'] += $value->total;
                            $result_key['sim_pre_renueve'] += $value->renueve;
                        }
                        if ($value->order_type == 'sim' && $value->type == '2') {
                            $result_key['sim_post_total'] += $value->total;
                            $result_key['sim_post_renueve'] += $value->renueve;
                        }
                        if ($value->order_type == 'package') {
                            $result_key['package_total'] += $value->total;
                            $result_key['package_renueve'] += $value->renueve;
                        }

                    }

                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * @return array|mixed|null
         * Daily report : Lấy tổng doanh thu + sản lương -> tính doanh thu lũy kế.
         */
        public function getEmailAccumulated()
        {
            if ($this->start_date && $this->end_date) {
                $this->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "COUNT(DISTINCT t.id) AS total, 
                                 SUM(od.price) as renueve";

            $criteria->condition = "os.id=(SELECT max(os2.id) FROM tbl_order_state os2 WHERE os2.order_id = t.id) 
                                     AND os.delivered=10 AND t.payment_method !='' and os.create_date >='2017-10-01 00:00:00' and od.type='sim'";

            $criteria->join = "INNER JOIN  tbl_order_state os ON os.order_id = t.id
                               INNER JOIN tbl_order_details od ON od.order_id = t.id";

            $data = ROrders::model()->findAll($criteria);

            return $data;
        }


        public function getEmailOrderRenueve()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "DATE(t.create_date) as date, os.delivered, os.confirm, COUNT(DISTINCT t.id) as total";

            $criteria->condition = "os.id=(SELECT max(os2.id) FROM tbl_order_state os2 WHERE os2.order_id = t.id)
                                    AND t.payment_method !=''
                                    AND t.create_date >=' $this->start_date' 
                                    AND t.create_date <='$this->end_date'";

            $criteria->join  = "INNER JOIN  tbl_order_state os ON os.order_id = t.id";
            $criteria->group = "DATE(t.create_date), os.confirm, os.delivered";
            $data            = self::controllEmailOrderRenueve(ROrders::model()->findAll($criteria));

            return $data;
        }

        public function getTotalOrderCreate()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "DATE(t.create_date) as date, COUNT(DISTINCT t.id) as total";

            $criteria->condition = "t.payment_method !=''
                                    AND t.create_date >=' $this->start_date' 
                                    AND t.create_date <='$this->end_date'";
            $criteria->group     = "DATE(t.create_date)";
            $data                = ROrders::model()->findAll($criteria);

            return $data;
        }

        /**
         * @param $order_id
         * Lấy ngày thanh toán theo đơn hàng.
         */
        public static function getPaidDate($order_id)
        {

            $order_state = AOrderState::model()->findByAttributes(array('order_id' => $order_id, 'paid' => 10));
            if ($order_state) {
                return $order_state->create_date;
            }

            return "";

        }

        public static function controllEmailOrderRenueve($data)
        {
            $result = array();
            $dates  = array();
            if (!empty($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        if (!in_array($value->date, $dates)) {
                            array_push($dates, $value->date);
                        }
                    }
                }
            }

            foreach ($dates as $date) {
                $result_key = array(
                    'order_confirm' => 0,
                    'order_success' => 0,
                    'order_cancel'  => 0,
                    'date'          => $date,
                );
                foreach ($data as $key => $value) {
                    if ($value->date == $date) {
                        if ($value->delivered == '10') {
                            $result_key['order_success'] += $value->total;
                        }
                        if ($value->delivered != '10' && $value->confirm == '10') {
                            $result_key['order_confirm'] += $value->total;
                        }
                        if ($value->confirm == '2' || $value->confirm == '3') {
                            $result_key['order_cancel'] += $value->total;
                        }

                    }

                }
                $result[] = $result_key;
            }

            return $result;
        }

        /**
         * @param $code
         *
         * @return string
         */
        public static function getProvince($code)
        {

            $province = array();
            if ($code) {

                $province = Province::model()->find('code=:code', array(':code' => $code));
            }

            return ($province) ? CHtml::encode($province->name) : $code;
        }

        /**
         * @param $code
         *
         * @return string
         */
        public static function getDistrict($code)
        {
            $district = array();
            if ($code) {
                $district = District::model()->find('code=:code', array(':code' => $code));
            }

            return ($district) ? CHtml::encode($district->name) : $code;
        }

        /**
         * Lấy thể loại sim
         */
        public static function getTypeName($type)
        {
            $type_sim = array(
                1  => 'Trả trước',
                2  => 'Trả sau',
                3  => 'Data',
                4  => 'Vas',
                5  => 'Sim Kit',
                6  => 'Đổi quà',
                12 => 'Data Roaming'
            );

            return $type_sim[isset($type) ? $type : 1];
        }

        /**
         * @return string
         * Lấy tổng số CTV.
         */
        public function getTotalUserCTV()
        {
            $user = ACtvUsers::model()->count();

            return $user;
        }

        /**
         * @return static[]
         * Lấy tổng số ctv theo trạng thái .
         */
        public function getTotalCtvByType()
        {
            $criteria         = new CDbCriteria();
            $criteria->select = "COUNT(DISTINCT t.user_id) AS total_user, t.finish_profile, t.finish_payment_profile";
            $criteria->group  = "t.finish_profile, t.finish_payment_profile";
            $users            = self::controllTotalCtvByType(ACtvUsers::model()->findAll($criteria));

            return $users;
        }

        /**
         * @return array
         */
        public function getTotalCtvRenueve()
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "COUNT(DISTINCT t.user_id) as total_user_renueve";
            $criteria->condition = "a.action_status=3";
            $criteria->join      = "INNER JOIN tbl_actions a ON a.publisher_id = t.user_id";
            $users               = ACtvUsers::model()->findAll($criteria)[0];

            return $users->total_user_renueve;
        }

        /**
         * @return array
         */
        public function getTotalRenueveCtv()
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "SUM(t.total_money) as total_renueve";
            $criteria->condition = "t.action_status=3 and t.created_on >='2017-10-01 00:00:00' and t.created_on <='$this->end_date' 
                                    and u.is_business = 0 and (u.agency_id ='' or u.agency_id is null)";
            $criteria->join      = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id =t.publisher_id";
            $actions             = ACtvActions::model()->findAll($criteria)[0];

            return $actions->total_renueve;
        }

        public function getAgencyRenueve()
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "SUM(t.total_money) as total_renueve, count(DISTINCT t.action_id) as total_order, t.campaign_category_id, u.agency_id as agency_id";
            $criteria->condition = "t.action_status=3 AND u.agency_id !=''";
            $criteria->join      = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id = t.publisher_id";
            $criteria->group     = "t.campaign_category_id, u.agency_id";

            $actions = self::controllAgencyData(ACtvActions::model()->findAll($criteria));

            return $actions;
        }

        public static function controllAgencyData($data)
        {
            $result  = array();
            $agencys = ACtvUsers::model()->findAll('is_business =:is_business and status =:status',
                array(
                    ':is_business' => 1,
                    ':status'      => 1,
                )
            );
            $agencys = CHtml::listData($agencys, 'user_id', 'user_name');

            foreach ($agencys as $key_agency => $agency) {
                $result_key = array(
                    'agency_name'     => $agency,
                    'total_sim'       => 0,
                    'renueve_sim'     => 0,
                    'total_package'   => 0,
                    'renueve_package' => 0,
                    'total_renueve'   => 0,

                );
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $key => $value) {
                        if ($key_agency == $value->agency_id) {
                            if ($value->campaign_category_id == 1) {
                                $result_key['total_sim'] += $value->total_order;
                                $result_key['renueve_sim'] += $value->total_renueve;
                            }
                            if ($value->campaign_category_id == 2) {
                                $result_key['total_package'] += $value->total_order;
                                $result_key['renueve_package'] += $value->total_renueve;
                            }
                            $result_key['total_renueve'] += $value->total_renueve;
                        }
                    }
                }
                $result[] = $result_key;
            }


            return $result;
        }

        public static function controllTotalCtvByType($data)
        {
            $result = array(
                'finish_profile'         => 0,
                'finish_payment_profile' => 0,
            );
            foreach ($data as $value) {
                if ($value->finish_profile == 1) {
                    $result['finish_profile'] += $value->total_user;
                }
                if ($value->finish_profile == 1 && $value->finish_payment_profile == 1) {
                    $result['finish_payment_profile'] += $value->total_user;
                }
            }

            return $result;
        }

        /**
         * @param       $records
         * @param array $columns
         * Lấy cột footer Tổng của tbgridView.
         *
         * @return array
         */
        public function getTotal($records, $columns = array(), $offset = 0)
        {
            $total = array();

            $stt = 0;
            foreach ($records as $record) {
                if ($stt >= $offset) {
                    foreach ($columns as $column) {
                        if (!isset($total[$column])) $total[$column] = 0;
                        $total[$column] += $record[$column];
                    }
                }
                $stt++;
            }

            return $total;
        }

        public static function dayCount($from, $to)
        {
            $first_date  = strtotime($from);
            $second_date = strtotime($to);
            $days_diff   = $second_date - $first_date;

            return date('d', $days_diff);
        }

        public function sendEmailDaily($from, $to, $subject, $short_desc, $content = '', $views_layout_path = 'web.views.layouts')
        {
            $mail = new YiiMailer();
            $mail->setLayoutPath($views_layout_path);
            $mail->setData(array('message' => $content, 'name' => $from, 'description' => $short_desc));

            $mail->setFrom(Yii::app()->params->sendEmail['username'], $from);
            $mail->setTo($to);
            $mail->setSubject($from . ' | ' . $subject);
            $mail->setSmtp(Yii::app()->params->sendEmail['host'], Yii::app()->params->sendEmail['port'], Yii::app()->params->sendEmail['type'], TRUE, Yii::app()->params->sendEmail['username'], Yii::app()->params->sendEmail['password']);

            return $mail->send();
        }
    }

?>