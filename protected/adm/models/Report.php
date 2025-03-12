<?php

class Report extends CFormModel
{
    public $start_date;
    public $end_date;
    public $on_detail; // Check box detail

    public $ctv_id; // Mã CTV

    public $province_code; // Trung tâm kinh doanh
    public $district_code;
    public $ward_code;
    public $sale_office_code;
    public $brand_offices_id;

    public $msisdn;
    public $sim_type; // Hình thức sim

    public $package_id; // Check box detail
    public $package_group; // Check box detail
    public $sim_freedoo; // Phân biệt loại thuê bao freedoo hay thuê bao thường.

    public $period; // Check box offices_id

    public $input_type;

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
    public $card_type;
    public $channel_code;
    public $esim;

    public $item_sim_type;

    public $receive_status; // Trạng thái thu tiền

    private $_ora;

    const SIM_TYPE     = 1;
    const CARD_TYPE    = 2;
    const PACKAGE_TYPE = 3;

    const HOME = 1;
    const DGD  = 2;

    const  FREEDOO_TYPE   = 1;
    const  VINAPHONE_TYPE = 2;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('start_date, end_date, ctv_id, province_code, district_code,
                ward_code, sale_office_code, brand_offices_id, msisdn, sim_type,
                package_id, package_group, sim_freedoo, period, input_type,
                online_status, paid_status, status_type, invitation, create_date,
                commission_earned, total, price_card, payment_method, renueve_term,
                delivery_type, card_type, receive_status, item_sim_type, on_detail', 'safe')
        );
    }

    public function __construct($oracle = TRUE)
    {
        parent::__construct();
        if($oracle){

            $this->_ora = Oracle::getInstance();
            $this->_ora->connect();
        }

    }

    public function getRenueveIndexOverview()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }
        $criteria         = new CDbCriteria();
        $criteria->select = "SUM(od.price) as renueve,
                             od.type as order_type,
                             s.type as type";
        if ($this->start_date && $this->end_date) {
            $criteria->condition = "od.type IN ('sim','esim','package','price_term') 
                                AND ot.delivered = 10
                                AND ot.create_date >= '$this->start_date'
                                AND ot.create_date <= '$this->end_date'
                                AND ot.id = (
                                            SELECT
                                                max(ot2.id)
                                            FROM
                                                tbl_order_state ot2
                                            WHERE
                                                ot2.order_id = t.id
                                        )";
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

        $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                            INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                            INNER JOIN tbl_sim s ON s.order_id = t.id
                            LEFT JOIN tbl_shipper_order so ON so.order_id = t.id";
        $criteria->group = "od.type, s.type";

        $data = self::controlRenueveOverview(ROrders::model()->findAll($criteria));

        return $data;
    }

    public static function controlRenueveOverview($data)
    {
        $return    = array();
        $key_array = array(
            1, 2
        );
        foreach ($key_array as $key) {
            $result_key         = array(
                'type'            => '',
                'renueve_sim'     => 0,
                'renueve_package' => 0,
            );
            $result_key['type'] = $key;
            foreach ($data as $value) {
                if ($value->type == $key) {

                    if ($value->order_type == 'sim' || $value->order_type == 'esim' || $value->order_type == 'price_term') {
                        $result_key['renueve_sim'] += $value->renueve;
                    } else {
                        $result_key['renueve_package'] += $value->renueve;
                    }

                }
            }
            $return[] = $result_key;
        }

        return $return;
    }

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
            $criteria->condition = "od.type IN ('sim', 'esim') and ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
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
                    if ($orders->type == 'sim' || $orders->type == 'esim') {
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
        $criteria->condition = "od.type IN ('sim','esim') and ot.create_date>='" . $this->start_date . "' and  ot.create_date <='" . $this->end_date . "' and ot.delivered =10";
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
                                  WHERE od1.order_id=od.order_id and od1.type IN ('sim','esim')
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
            $criteria->condition = "od.type IN ('package','sim','esim') and ot.create_date >='$this->start_date' 
                                and ot.create_date <='$this->end_date' and p.type NOT IN ('" . ReportForm::SIMKIT . "',
                                '" . ReportForm::FLEXIBLE_SMS_INT . "','" . ReportForm::FLEXIBLE_SMS_EXT . "','" . ReportForm::FLEXIBLE_CALL_EXT . "'
                                ,'" . ReportForm::FLEXIBLE_CALL_INT . "','" . ReportForm::FLEXIBLE_DATA . "') AND ot.delivered =10 AND s.order_id is null";
        } else {
            $criteria->condition = "od.type IN ('package','sim','esim') and ot.create_date >='$this->start_date' 
                                and ot.create_date <='$this->end_date'  AND ot.delivered =10 AND s.order_id is not null";
        }

        $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");

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
//            if ($this->sim_freedoo != '') {
//                if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
//                    $criteria->addCondition('s1.msisdn is not null');
//                } else {
//                    $criteria->addCondition('s1.msisdn is null');
//                }
//            }


        $criteria->join  = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                            INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                            INNER JOIN tbl_package p ON p.code = od.item_id
                            LEFT JOIN tbl_sim s ON s.order_id = t.id
                            ";
        $criteria->group = "od.item_name";

        $data = new CActiveDataProvider('ROrders', array(
            'criteria'   => $criteria,
            'sort'       => array('defaultOrder' => 't.create_date asc'),
            'pagination' => array(
                'params'   => array(
                    'get'                       => 1,
                    'ReportForm[start_date]'    => $this->start_date,
                    'ReportForm[end_date]'      => $this->end_date,
                    "ReportForm[package_group]" => $this->package_group,
                    "ReportForm[package_id]"    => $this->package_id,
                    "ReportForm[on_detail]"     => isset($this->on_detail) ? $this->on_detail : "on",
                    "ReportForm[input_type]"    => isset($this->input_type) ? $this->input_type : '',
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
            $criteria->condition = "od.type IN ('package','sim','esim') and ot.create_date >='$this->start_date' 
                                and ot.create_date <='$this->end_date' and p.type NOT IN ('" . ReportForm::SIMKIT . "',
                                '" . ReportForm::FLEXIBLE_SMS_INT . "','" . ReportForm::FLEXIBLE_SMS_EXT . "','" . ReportForm::FLEXIBLE_CALL_EXT . "'
                                ,'" . ReportForm::FLEXIBLE_CALL_INT . "','" . ReportForm::FLEXIBLE_DATA . "') AND ot.delivered =10  AND s.order_id is null";
        } else {
            $criteria->condition = "od.type IN ('package','sim','esim') and ot.create_date >='$this->start_date' 
                                and ot.create_date <='$this->end_date' AND ot.delivered =10 AND s.order_id is not null";
        }
        if($this->sim_type != ''){
            $criteria->addCondition("s.type ='". $this->sim_type . "'");
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
        $criteria->addCondition("ot.id=(SELECT max(ot2.id) FROM tbl_order_state ot2  WHERE ot2.order_id = t.id)");

//            if ($this->sim_freedoo != '') {
//                if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
//                    $criteria->addCondition('s1.msisdn is not null');
//                } else {
//                    $criteria->addCondition('s1.msisdn is null');
//                }
//            }

        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                            INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                            INNER JOIN tbl_package p ON p.code = od.item_id
                            LEFT JOIN tbl_sim s ON s.order_id = t.id
                            ";

        $criteria->group = "t.id";

        if ($excel) {
            $criteria->order = "t.create_date asc";

            $data = ROrders::model()->findAll($criteria);
        } else {
            $data = new CActiveDataProvider('ROrders', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                       => 1,
                        'ReportForm[start_date]'    => $this->start_date,
                        'ReportForm[end_date]'      => $this->end_date,
                        "ReportForm[package_group]" => $this->package_group,
                        "ReportForm[package_id]"    => $this->package_id,
                        "ReportForm[on_detail]"     => isset($this->on_detail) ? $this->on_detail : "on",
                        "ReportForm[input_type]"    => isset($this->input_type) ? $this->input_type : '',
                    ),
                    'pageSize' => 30,
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
     * Lấy số liệu tổng quan nạp thẻ và topup
     */
    public function getCardTopupFreeDooOverView()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria = new CDbCriteria();

        $criteria->select    = "od.type as type,
                                COUNT(DISTINCT od.order_id) AS total_card,
                                SUM(od.price) AS renueve_card";
        $criteria->condition = "ot.delivered = 10 AND od.type IN ('card','topup') 
                                AND ot.create_date >='$this->start_date' 
                                AND ot.create_date <='$this->end_date'";

        if ($this->payment_method != '') {
            $criteria->addCondition("t.payment_method ='$this->payment_method'");
        }
        if ($this->price_card != '') {
            $criteria->addCondition("od.item_id ='$this->price_card'");
        }
        if ($this->card_type != '') {
            $criteria->addCondition("od.type ='$this->card_type'");
        }
        if ($this->sim_freedoo != '') {
            if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
                $criteria->addCondition('s.msisdn is not null');
            } else {
                $criteria->addCondition('s.msisdn is null');
            }
        }

        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                           INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                           LEFT JOIN tbl_sim s ON s.msisdn = t.phone_contact";

        $criteria->group = "od.type";

        $data = ROrders::model()->findAll($criteria);

        return $data;
    }

    /**
     * Lấy số liệu tổng quan nạp thẻ
     */
    public function getCardFreeDooType()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria = new CDbCriteria();

        $criteria->select    = "od.item_id as item_id,
                                COUNT(DISTINCT od.order_id) AS total_card,
                                SUM(od.price) AS renueve_card";
        $criteria->condition = "ot.delivered = 10 AND od.type IN ('card') 
                                AND ot.create_date >='$this->start_date' 
                                AND ot.create_date <='$this->end_date'";

        if ($this->payment_method != '') {
            $criteria->addCondition("t.payment_method ='$this->payment_method'");
        }
        if ($this->price_card != '') {
            $criteria->addCondition("od.item_id ='$this->price_card'");
        }
        if ($this->card_type != '') {
            $criteria->addCondition("od.type ='$this->card_type'");
        }
        if ($this->sim_freedoo != '') {
            if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
                $criteria->addCondition('s.msisdn is not null');
            } else {
                $criteria->addCondition('s.msisdn is null');
            }
        }
        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                           INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                           LEFT JOIN tbl_sim s ON s.msisdn = t.phone_contact";

        $criteria->group = "od.item_id";
        $data            = ROrders::model()->findAll($criteria);

        return $data;
    }

    /**
     * Lấy số liệu tổng quan nạp thẻ
     */
    public function getCardFreeDooDetails()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria = new CDbCriteria();

        $criteria->select    = "t.id, od.type, od.item_id, od.price, t.phone_contact, t.payment_method";
        $criteria->condition = "ot.delivered = 10 AND od.type IN ('card','topup') 
                                AND ot.create_date >='$this->start_date' 
                                AND ot.create_date <='$this->end_date'";

        if ($this->payment_method != '') {
            $criteria->addCondition("t.payment_method ='$this->payment_method'");
        }
        if ($this->price_card != '') {
            $criteria->addCondition("od.item_id ='$this->price_card'");
        }
        if ($this->card_type != '') {
            $criteria->addCondition("od.type ='$this->card_type'");
        }
        if ($this->sim_freedoo != '') {
            if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
                $criteria->addCondition('s.msisdn is not null');
            } else {
                $criteria->addCondition('s.msisdn is null');
            }
        }
        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                           INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                           LEFT JOIN tbl_sim s ON s.msisdn = t.phone_contact";

//            $criteria->group = "od.item_id";

        $data = ROrders::model()->findAll($criteria);

        return $data;
    }

    /**
     * Lấy dữ liệu gói cước linh hoạt
     */
    public function getInfoPackageFlexible()
    {
        $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
        $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

//        $cache_key = "Report_getInfoPackageFlexible"
//            . "_start_date_$start_date"
//            . "_end_date_$end_date"
//            . "_period_$this->period"
//            . "_package_group_$this->package_group"
//            . "_package_id_$this->package_id";
//
//        $result = Yii::app()->cache->get($cache_key);

//        if(!$result){
            $criteria = new CDbCriteria();

            $criteria->select    = "t.id,
                                IF(p.type = '" . ReportForm::FLEXIBLE_CALL_INT . "',p.short_description,'') as capacity_call_int,
                                IF(p.type = '" . ReportForm::FLEXIBLE_CALL_EXT . "',p.short_description,'') as capacity_call_ext,
                                IF(p.type = '" . ReportForm::FLEXIBLE_SMS_INT . "',p.short_description,'') as capacity_sms_int,
                                IF(p.type = '" . ReportForm::FLEXIBLE_SMS_EXT . "',p.short_description,'') as capacity_sms_ext,
                                IF(p.type = '" . ReportForm::FLEXIBLE_DATA . "',p.short_description,'') as capacity_data,
                                t.create_date, sum(od.price) as total, t.phone_contact as customer_msisdn";
            $criteria->condition = "
            t.delivery_date IS NOT NULL AND t.delivery_date != ''
            AND t.create_date >='$start_date' 
            AND t.create_date <='$end_date'
            ";

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
                                INNER JOIN tbl_package p ON od.item_id = p.code";
            $criteria->group = 't.id, od.item_id';
            $criteria->order = 't.create_date DESC, t.id ASC';

            $result = self::controlDataPackageFlexible(ROrders::model()->findAll($criteria));
//            Yii::app()->cache->set($cache_key,$result,60*10);
//        }

        return $result;
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
                (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'sim',                
                (SELECT type FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'item_sim_type',                
                os.note as note
            ";
        if ($this->start_date && $this->end_date) {
            $criteria->condition = "t.create_date>='" . $this->start_date . "' and  t.create_date <='" . $this->end_date . "'
                                    AND t.payment_method !=''
                                    AND od.type IN ('sim','package','price_term','esim')
                                    AND payment_method IN('1','2','3','6')";
        }

        if(!empty($this->item_sim_type)){
            if($this->item_sim_type == 2){
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'esim')");
            }else{
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'sim')");
            }
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
        $criteria->join = "INNER JOIN tbl_order_state os ON os.order_id = t.id
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
                'item_sim_type'    => '',
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
                    $result_key['item_sim_type']    = $value->item_sim_type;
                    if ($value->type == 'sim' OR $value->type == 'esim') {
                        $result_key['price_sim'] = $value->price;
                        $result_key['sim']       = $value->sim;
                        $result_key['item_sim_type'] = $value->item_sim_type;
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
        $criteria->select = "DATE(os.create_date) as date, COUNT(DISTINCT t.id) as total, 
                            SUM(od.price) as renueve, od.type as order_type, s.type as type";

        $criteria->condition = "os.id=(SELECT max(os2.id) FROM tbl_order_state os2 WHERE os2.order_id = t.id) 
                                 AND os.delivered=10 AND t.payment_method !=''
                                 AND os.create_date >= '$this->start_date' 
                                 AND os.create_date <= '$this->end_date'";

        $criteria->join  = "INNER JOIN  tbl_order_state os ON os.order_id = t.id
                            INNER JOIN tbl_order_details od ON od.order_id = t.id
                            LEFT JOIN tbl_sim s ON s.order_id = t.id";
        $criteria->group = "DATE(os.create_date), s.type, od.type HAVING od.type NOT IN ('price_ship','price_term')";

        $data = self::controllEmailSellRenueve(ROrders::model()->findAll($criteria));


        return $data;
    }

    public function getRenueveTourist()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria         = new CDbCriteria();
        $criteria->select = "DATE(t.create_time) AS date,
                            SUM(od.total_success) AS total_tourist,
                            SUM(od.total_success * od.price) AS renueve_tourist";

        $criteria->condition = "t.create_time >='$this->start_date' and t.create_time <='$this->end_date'";

        $criteria->join  = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id";
        $criteria->group = "DATE(t.create_time)";

        $data = self::controllEmailSellRenueveTourist(AFTOrders::model()->findAll($criteria), date("Y-m-d", strtotime($this->start_date)), date("Y-m-d", strtotime($this->end_date)));


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
                    if (($value->order_type == 'sim' || $value->order_type == 'esim') && $value->type == '1') {
                        $result_key['sim_pre_total'] += $value->total;
                        $result_key['sim_pre_renueve'] += $value->renueve;
                    }
                    if (($value->order_type == 'sim' || $value->order_type == 'esim') && $value->type == '2') {
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

    public static function controllEmailSellRenueveTourist($data, $start, $end)
    {

        $result = array();

        $start_time = strtotime(date('Y-m-d', strtotime($start)));
        $end_time   = strtotime(date('Y-m-d', strtotime($end)));
        $stt        = 0;

        for ($i = $start_time; $i <= $end_time; $i += 86400) {
            $result[$stt]['date']            = date('Y-m-d', $i);
            $result[$stt]['total_tourist']   = 0;
            $result[$stt]['renueve_tourist'] = 0;
            if (!empty($data) && is_array($data)) {
                foreach ($data as $key => $value) {
                    if (date('Y-m-d', $i) == $value->date) {
                        $result[$stt]['date']            = $value->date;
                        $result[$stt]['total_tourist']   = $value->total_tourist;
                        $result[$stt]['renueve_tourist'] = $value->renueve_tourist;
                    }
                }
            }
            $stt++;

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
                                 AND os.delivered=10 AND t.payment_method !='' and os.create_date >='2017-10-01 00:00:00' and od.type IN ('sim', 'esim')";

        $criteria->join = "INNER JOIN  tbl_order_state os ON os.order_id = t.id
                           INNER JOIN tbl_order_details od ON od.order_id = t.id";

        $data = ROrders::model()->findAll($criteria);

        return $data;
    }

    /**
     * @return array|mixed|null
     * Daily report : Lấy tổng doanh thu + sản lương -> tính doanh thu lũy kế.
     */
    public function getEmailAccumulatedTourist()
    {
        if ($this->start_date && $this->end_date) {
            $this->end_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }

        $criteria         = new CDbCriteria();
        $criteria->select = "SUM(od.total_success) AS total_tourist,
                             SUM(od.total_success * od.price) AS renueve_tourist";

        $criteria->condition = "t.create_time >= '2019-01-01 00:00:00' AND t.create_time <= '$this->end_date'";

        $criteria->join = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id";

        $data = AFTOrders::model()->findAll($criteria);

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
                                AND t.create_date >= '$this->start_date' 
                                AND t.create_date <= '$this->end_date'";

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

        $criteria = new CDbCriteria();
        $criteria->condition = "t.create_date >= '$this->start_date' 
                            AND t.create_date <= '$this->end_date'
                            AND t.id IN (SELECT order_id FROM tbl_order_state WHERE confirm = 10)
                            AND t.id IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim','esim'))
                        ";
        $order_sim = ROrders::model()->findAll($criteria);

        $criteria->condition = "t.create_date >= '$this->start_date' 
                            AND t.create_date <= '$this->end_date'
                            AND t.id IN (SELECT order_id FROM tbl_order_state WHERE confirm = 10)
                            AND t.id NOT IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim', 'esim'))
                            AND t.id IN (
                                SELECT 
                                    order_id 
                                FROM 
                                    tbl_order_details od 
                                INNER JOIN tbl_package p ON od.item_id = p.code
                                WHERE 
                                    od.type = 'package'
                                    AND p.type NOT IN ('".ReportForm::SIMKIT."',
                                        '".ReportForm::FLEXIBLE_SMS_INT."',
                                        '".ReportForm::FLEXIBLE_SMS_EXT."',
                                        '".ReportForm::FLEXIBLE_CALL_EXT."',
                                        '".ReportForm::FLEXIBLE_CALL_INT."',
                                        '".ReportForm::FLEXIBLE_DATA."'
                                    )
                            )
                        ";
        $order_package = ROrders::model()->findAll($criteria);

        $data = array();
        $list_date = Utils::getListDate($this->start_date, $this->end_date);
        foreach ($list_date as $date){
            if(!isset($data[$date])){
                $data[$date]['date'] = $date;
                $data[$date]['total'] = 0;
            }
            foreach ($order_sim as $order){
                if(date('Y-m-d', strtotime($order->create_date)) == $date){
                    $data[$date]['total']++;
                }
            }
            foreach ($order_package as $order){
                if(date('Y-m-d', strtotime($order->create_date)) == $date){
                    $data[$date]['total']++;
                }
            }
        }

        return array_values($data);
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
     * $type =1: month, $type=2:accumulated
     */
    public function getTotalUserCTV($type = '', $start_date = '', $end_date = '')
    {
        $now       = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 days')) . ' 23:59:59';
        $now_start = '2017-10-18 00:00:00';
        $now_preivous = date('Y-m', strtotime(date('Y-m') . ' -1 month'));
        $now_preivous .= '-31 23:59:59';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date)) . ' 00:00:00';
            $end_date   = date('Y-m-d', strtotime($end_date)) . ' 23:59:59';
        }
        if (date('d') == 1) {
            $month = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
            $month .= '-01 00:00:00';
        } else if ($type == 3) {
            $month = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
            $month .= '-01 00:00:00';
        } else {
            $month = date('Y-m') . '-01 00:00:00';

        }
        $year = date('Y') . '-01-01 00:00:00';

        $criteria = new CDbCriteria();
        if ($type == '') {
            $criteria->select = "DATE(t.created_on) as date, COUNT(DISTINCT t.user_id) as total, t.finish_profile";
        } else {
            $criteria->select = "t.finish_profile, COUNT(DISTINCT t.user_id) as total";
        }

        $criteria->condition = "t.is_business !=1";
        if ($type != '') {
            if ($type == 1) {
                $criteria->addCondition("t.created_on >='$month' and t.created_on <='$now'");
            }else if ($type == 3) {
                $criteria->condition = "t.created_on >='$month' and t.created_on <='$now_preivous'";
            } else {
                $criteria->addCondition("t.created_on >='$now_start' and t.created_on <='$now'");
            }
            $criteria->group = "t.finish_profile";
        } else {
            $criteria->addCondition("t.created_on >='$start_date' and t.created_on <='$end_date'");
            $criteria->group = "DATE(t.created_on), t.finish_profile";
        }

        $user = ACtvUsers::model()->findAll($criteria);

        return $user;
    }

    /**
     * @return array
     * $type =1: month, $type=2:year
     */
    public function getTotalCtvRenueve($type = '', $start_date = '', $end_date = '')
    {
        $criteria = new CDbCriteria();


        $now          = date('Y-m-d', strtotime(date('Y-m-d') . ' -1 days')) . ' 23:59:59';
        $now_preivous = date('Y-m', strtotime(date('Y-m') . ' -1 month'));
        $now_preivous .= '-31 23:59:59';
        if ($start_date != '' && $end_date != '') {
            $start_date = date('Y-m-d', strtotime($start_date)) . ' 00:00:00';
            $end_date   = date('Y-m-d', strtotime($end_date)) . ' 23:59:59';

            $month_check = date('Y-m', strtotime(date('Y-m')));
            $month_check .= '-01';

            if (strtotime($month_check)> strtotime($start_date)){
                $start_date = $month_check .' 00:00:00';
            }
        }

        if (date('d') == 1) {
            $month = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
            $month .= '-01 00:00:00';
        } else if ($type == 3) {
            $month = date('Y-m-d', strtotime(date('Y-m') . ' -1 month'));
            $month .= '-01 00:00:00';
        } else {
            $month = date('Y-m') . '-01 00:00:00';
        }
        $year = date('Y') . '-01-01 00:00:00';
        if ($type == '') {
            $criteria->select = "DATE(a.order_time) as date, SUM(a.total_money) as total_renueve ,COUNT(DISTINCT t.user_id) as total_user_renueve";
        } else {
            $criteria->select = "SUM(a.total_money) as total_renueve, COUNT(DISTINCT t.user_id) as total_user_renueve";
        }

        if ($type != '') {
            if ($type == 1) {
                $criteria->condition = "a.order_time >='$month' and a.order_time <='$now' and a.action_status=3 and a.campaign_category_id IN(1,2)";
            } else if ($type == 3) {
                $criteria->condition = "a.order_time >='$month' and a.order_time <='$now_preivous' and a.action_status=3 and a.campaign_category_id IN(1,2)";
            } else {
                $criteria->condition = "a.order_time >='$year' and a.order_time <='$now' and a.action_status=3 and a.campaign_category_id IN(1,2)";
            }
        } else {
            $criteria->condition = "a.order_time >='$start_date' and a.order_time <='$end_date' and a.action_status=3 and a.campaign_category_id IN(1,2)";

            $criteria->group = "DATE(a.order_time)";
        }
        $criteria->join = "INNER JOIN vsb_affiliate.tbl_actions a ON a.publisher_id = t.user_id";

        $users = ACtvUsers::model()->findAll($criteria);

        return $users;
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

    public function getTotalObject($records, $columns = array(), $offset = 0)
    {
        $total = array();

        $stt = 0;

        foreach ($records as $record) {
            if ($stt >= $offset) {
                foreach ($columns as $column) {

                    if (!isset($total[$column])) $total[$column] = 0;
                    $total[$column] += $record->$column;
                }
            }
            $stt++;
        }

        return $total;
    }

    /**
     * @param       $records
     * @param array $columns
     * Lấy cột footer Tổng của tbgridView.
     *
     * @return array
     */
    public function getTotalNotPackage($records, $columns = array(), $offset = 0)
    {
        $total = array();

        $stt = 0;
        foreach ($records as $record) {
            if ($stt >= $offset) {
                foreach ($columns as $column) {
                    if (!isset($total[$column])) $total[$column] = 0;

                    if ($column == 'renueve_package' && $record['type'] == 2) {
                    } else {
                        $total[$column] += $record[$column];
                    }
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

    /*
     * Phân biệt thuê bao freedoo và thuê bao thường.
     */
    public static function getTypeSim($msisdn)
    {
        if ($msisdn) {
            $sim = ASim::model()->findByAttributes(array('msisdn' => $msisdn));
            if (isset($sim)) {
                if (!empty($sim)) {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Tổng hợp doanh thu gói cước kèm sim
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchRenuevePackageSimKit($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchDetailRenuevePackageSimKit(FALSE);
        if(!empty($data_detail)){

            foreach ($data_detail as $item){
                $item_id    = strtoupper($item->item_id);

                if(!isset($data_raw[$item->province_code][$item_id])){
                    $data_raw[$item->province_code][$item_id]['total'] = 0;
                    $data_raw[$item->province_code][$item_id]['revenue'] = 0;
                    $data_raw[$item->province_code][$item_id]['name'] = $item->item_name;
                }

                $data_raw[$item->province_code][$item_id]['total']++;
                $data_raw[$item->province_code][$item_id]['revenue'] += $item->renueve;
            }

            foreach ($data_raw as $key => $item){
                $order = new ROrders();
                $order->province_code = $key;
                $order->packages = $item;

                $data[] = $order;
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                           => 1,
                        'ReportForm[start_date]'        => $this->start_date,
                        'ReportForm[end_date]'          => $this->end_date,
                        "ReportForm[province_code]"     => $this->province_code,
                        "ReportForm[sale_office_code]"  => $this->sale_office_code,
                        "ReportForm[package_id]"        => $this->package_id,
                        "ReportForm[input_type]"        => $this->input_type,
                        "ReportForm[sim_type]"          => $this->sim_type,
                        "ReportForm[brand_offices_id]"  => $this->brand_offices_id,
                        "ReportForm[on_detail]"         => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 100,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Chi tiết doanh thu gói cước kèm sim
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailRenuevePackageSimKit($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

        $criteria = new CDbCriteria();
        $criteria->select = "t.id,
                (SELECT s2.type FROM tbl_sim s2 WHERE s2.order_id = t.id) AS 'type_sim',
                (SELECT od1.item_name FROM tbl_order_details od1 WHERE od1.order_id = t.id AND od1.type IN ('sim', 'esim')) AS 'sim',
                (SELECT od2.item_name FROM tbl_order_details od2 WHERE od2.order_id = t.id AND od2.type = 'package') AS 'item_name',
                (SELECT od3.item_id FROM tbl_order_details od3 WHERE od3.order_id = t.id AND od3.type = 'package') AS 'item_id',
                (SELECT p.type FROM tbl_package p WHERE p.code = (SELECT item_id FROM tbl_order_details od4 WHERE od4.type = 'package' AND od4.order_id = t.id)) AS 'type_package',  
                t.create_date,
                (SELECT create_date FROM tbl_order_state WHERE order_id = t.id AND delivered = 10 GROUP BY order_id) AS 'state_date',
                t.province_code,
                t.sale_office_code,
                (SELECT od4.price FROM tbl_order_details od4 WHERE od4.type = 'package' AND od4.order_id = t.id) AS 'renueve'
            ";
        $criteria->condition = "
                (t.id IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim', 'esim')))
                AND (t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'package'))
                AND (t.id IN (SELECT order_id FROM tbl_order_state 
                    WHERE create_date >= '$start_date' 
                        AND create_date <= '$end_date' 
                        AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = t.id 
                                AND os2.delivered = 10
                            )
                        )
                    )
            ";
        $criteria->order = 't.create_date ASC';

        if(!empty($this->province_code)){
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if(!empty($this->sale_office_code)){
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if(!empty($this->package_id)){
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'package' AND item_id = '$this->package_id')");
        }
        if(!empty($this->sim_type)){
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_sim WHERE order_id IS NOT NULL AND type = '$this->sim_type')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }

        $cache_key = "Report_searchDetailRenuevePackageSimKit"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_package_id_'.$this->package_id
            .'_input_type_'.$this->input_type
            .'_sim_type_'.$this->sim_type
            .'_brand_offices_id_'.$this->brand_offices_id;

        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                           => 1,
                        'ReportForm[start_date]'        => $this->start_date,
                        'ReportForm[end_date]'          => $this->end_date,
                        "ReportForm[province_code]"     => $this->province_code,
                        "ReportForm[sale_office_code]"  => $this->sale_office_code,
                        "ReportForm[package_id]"        => $this->package_id,
                        "ReportForm[input_type]"        => $this->input_type,
                        "ReportForm[sim_type]"          => $this->sim_type,
                        "ReportForm[brand_offices_id]"  => $this->brand_offices_id,
                        "ReportForm[on_detail]"         => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }
    }

    /**
     * Tổng hợp doanh thu sim
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchRenueveSim($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchDetailRenueveSim(FALSE);
        if(!empty($data_detail)){
            foreach ($data_detail as $item){

                if(!isset($data_raw[$item->province_code])){
                    $data_raw[$item->province_code]['total_sim_prepaid'] = 0;
                    $data_raw[$item->province_code]['total_sim_postpaid'] = 0;
                    $data_raw[$item->province_code]['revenue_sim_prepaid'] = 0;
                    $data_raw[$item->province_code]['revenue_sim_postpaid'] = 0;
                }

                if($item->type_sim == ASim::TYPE_PREPAID){
                    $data_raw[$item->province_code]['total_sim_prepaid']++;
                    $data_raw[$item->province_code]['revenue_sim_prepaid'] += $item->renueve_sim;
                }else if($item->type_sim == ASim::TYPE_POSTPAID){
                    $data_raw[$item->province_code]['total_sim_postpaid']++;
                    $data_raw[$item->province_code]['revenue_sim_postpaid'] += $item->renueve_sim;
                }

            }

            foreach ($data_raw as $key => $item){
                $order = new ROrders();
                $order->province_code           = $key;
                $order->total_sim_prepaid       = $item['total_sim_prepaid'];
                $order->total_sim_postpaid      = $item['total_sim_postpaid'];
                $order->revenue_sim_prepaid     = $item['revenue_sim_prepaid'];
                $order->revenue_sim_postpaid    = $item['revenue_sim_postpaid'];
                $order->renueve_sim             = $item['revenue_sim_prepaid'] + $item['revenue_sim_postpaid'];

                $data[] = $order;
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[item_sim_type]"         => $this->item_sim_type,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 100,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Chi tiết doanh thu SIM
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailRenueveSim($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

        $criteria = new CDbCriteria();
        $criteria->select = "
                t.id,
                (SELECT msisdn FROM tbl_sim WHERE order_id = t.id) AS 'sim',
                (SELECT type FROM tbl_sim WHERE order_id = t.id) AS 'type_sim',
                (SELECT create_date FROM tbl_order_state WHERE order_id = t.id AND delivered = 10 GROUP BY order_id) AS 'create_date',
                t.province_code,
                t.sale_office_code,
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'price_term') AS 'price_term',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'renueve_sim',
                (SELECT type FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'item_sim_type'
        ";

        $criteria->condition = "
                (t.id IN (SELECT order_id FROM tbl_order_details WHERE type IN ('esim','sim')))
                AND (t.id IN (SELECT order_id FROM tbl_order_state 
                    WHERE create_date >= '$start_date' 
                        AND create_date <= '$end_date' 
                        AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = t.id 
                                AND os2.delivered = 10
                            )
                        )
                    )
        ";
        $criteria->order = "t.create_date ASC";

        if(!empty($this->province_code)){
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if(!empty($this->sale_office_code)){
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if(!empty($this->sim_type)){
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_sim WHERE order_id IS NOT NULL AND type = '$this->sim_type')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }
        if(!empty($this->payment_method)){
            $criteria->addCondition("t.payment_method = '$this->payment_method'");
        }

        if(!empty($this->item_sim_type)){
            if($this->item_sim_type == 2){
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'esim')");
            }else{
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'sim')");
            }
        }

        $cache_key = "Report_searchDetailRenueveSim"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_sim_type_'.$this->sim_type
            .'_input_type_'.$this->input_type
            .'_payment_method_'.$this->payment_method
            .'_brand_offices_id_'.$this->brand_offices_id
            .'_item_sim_type_'.$this->item_sim_type;

        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[item_sim_type]"         => $this->item_sim_type,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }

    }

    /**
     * Tổng hợp doanh thu gói đơn lẻ
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchRenuevePackageSingle($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchDetailRenuevePackageSingle(FALSE);
        if(!empty($data_detail)){
            foreach ($data_detail as $item){
                $item_id = strtoupper($item->item_id);

                if(!isset($data_raw[$item_id])){
                    $data_raw[$item_id]['total'] = 0;
                    $data_raw[$item_id]['revenue'] = 0;
                    $data_raw[$item_id]['item_name'] = $item->item_name;
                    $data_raw[$item_id]['type_package'] = $item->type_package;
                }

                $data_raw[$item_id]['total']++;
                $data_raw[$item_id]['revenue'] += $item->renueve_package;

            }

            foreach ($data_raw as $key => $item){
                $order = new ROrders();
                $order->item_id         = $key;
                $order->item_name       = $item['item_name'];
                $order->type_package    = $item['type_package'];
                $order->total           = $item['total'];
                $order->renueve_package = $item['revenue'];

                $data[] = $order;
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[package_group]"         => $this->package_group,
                        "ReportForm[package_id]"            => $this->package_id,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[sim_freedoo]"           => $this->sim_freedoo,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 100,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Chi tiết doanh thu gói đơn lẻ
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailRenuevePackageSingle($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

        $criteria = new CDbCriteria();
        $criteria->select = "
                t.id,
                t.province_code,
                (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_id',
                (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_name',
                (SELECT type FROM tbl_package WHERE code = (SELECT item_id FROM tbl_order_details WHERE order_id = t.id)) AS 'type_package',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'renueve_package',
                t.phone_contact,
                t.create_date,
                (SELECT create_date FROM tbl_order_state WHERE order_id = t.id AND delivered = 10 GROUP BY order_id) AS 'state_date',
                CASE 
                    WHEN (SELECT count(*) FROM tbl_sim WHERE msisdn = t.phone_contact) > 0 
                    THEN 1 
                    ELSE 0 
                END as 'sim_freedoo'
            ";

        $criteria->condition = "
            (t.id IN (
                SELECT 
                    order_id 
                FROM 
                    tbl_order_details od 
                INNER JOIN tbl_package p ON od.item_id = p.code
                WHERE 
                    od.type = 'package'
                    AND p.type NOT IN ('".ReportForm::SIMKIT."',
                        '".ReportForm::FLEXIBLE_SMS_INT."',
                        '".ReportForm::FLEXIBLE_SMS_EXT."',
                        '".ReportForm::FLEXIBLE_CALL_EXT."',
                        '".ReportForm::FLEXIBLE_CALL_INT."',
                        '".ReportForm::FLEXIBLE_DATA."'
                    )
            ))
            AND (t.id NOT IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim', 'esim')))
            AND (t.id IN (SELECT order_id FROM tbl_order_state 
                WHERE create_date >= '$start_date' 
                    AND create_date <= '$end_date' 
                    AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                        WHERE os2.order_id = t.id 
                            AND os2.delivered = 10
                        )
                    )
                )
        ";
        $criteria->order = "t.create_date ASC";

        if (!empty($this->province_code)) {
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if (!empty($this->sale_office_code)) {
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if (!empty($this->package_group)) {
            $criteria->addCondition("t.id IN (
                SELECT 
                    order_id 
                FROM 
                    tbl_order_details od 
                INNER JOIN tbl_package p ON od.item_id = p.code
                WHERE 
                    od.type = 'package'
                    AND p.type = '$this->package_group'
            )");
        }
        if (!empty($this->package_id)) {
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package' AND item_id = '$this->package_id')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }
        if(!empty($this->sim_freedoo)){
            if($this->sim_freedoo == ReportForm::FREEDOO_TYPE){
                $operator = '>';
            }else{
                $operator = '=';
            }
            $criteria->addCondition("(SELECT count(*) FROM tbl_sim WHERE msisdn = t.phone_contact) $operator 0");
        }

        $cache_key = "Report_searchDetailRenuevePackageSingle"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_package_group_'.$this->package_group
            .'_package_id_'.$this->package_id
            .'_input_type_'.$this->input_type
            .'_sim_freedoo_'.$this->sim_freedoo
            .'_brand_offices_id_'.$this->brand_offices_id;


        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[package_group]"         => $this->package_group,
                        "ReportForm[package_id]"            => $this->package_id,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[sim_freedoo]"           => $this->sim_freedoo,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }
    }

    /**
     * Tổng hợp báo cáo doanh thu tổng hợp
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchRevenueSynthetic($dataProvider = TRUE)
    {
        $data = array();
        $data_detail = $this->searchDetailRevenueSynthetic(FALSE);

        $model_prepaid = new ROrders();
        $model_prepaid->type_sim = ASim::TYPE_PREPAID;
        $model_prepaid->renueve_sim = 0;
        $model_prepaid->renueve_package = 0;
        $model_prepaid->renueve_term = 0;

        $model_postpaid = new ROrders();
        $model_postpaid->type_sim = ASim::TYPE_POSTPAID;
        $model_postpaid->renueve_sim = 0;
        $model_postpaid->renueve_package = 0;
        $model_postpaid->renueve_term = 0;

        if(!empty($data_detail)){
            foreach ($data_detail as $item){
                if($item->type_sim == ASim::TYPE_PREPAID){
                    $model_prepaid->renueve_sim += $item->renueve_sim;
                    $model_prepaid->renueve_package += $item->renueve_package;
                    $model_prepaid->renueve_term += $item->renueve_term;
                }else if($item->type_sim == ASim::TYPE_POSTPAID){
                    $model_postpaid->renueve_sim += $item->renueve_sim;
                    $model_postpaid->renueve_package += $item->renueve_package;
                    $model_postpaid->renueve_term += $item->renueve_term;
                }
            }

            $data[] = $model_prepaid;
            $data[] = $model_postpaid;
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[receive_status]"        => $this->receive_status,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 10,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Chi tiết báo cáo doanh thu tổng hợp
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailRevenueSynthetic($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

        $criteria = new CDbCriteria();
        $criteria->select = "
                t.id,
                t.receive_cash_by,
                t.receive_cash_date,
                t.payment_method,
                t.last_update as 'delivery_date',
                CASE
                    WHEN (t.receive_cash_by IS NOT NULL AND t.receive_cash_by != '')
                    THEN 2
                    ELSE 1
                END AS 'receive_status',
                (SELECT msisdn FROM tbl_sim WHERE order_id = t.id) AS 'sim',
                (SELECT type FROM tbl_sim WHERE order_id = t.id) AS 'type_sim',
                CASE
                    WHEN (t.shipper_id IS NOT NULL AND t.shipper_id != '')
                    THEN (SELECT username FROM tbl_shipper WHERE id = t.shipper_id)
                    ELSE ''
                END AS 'shipper_name',
                (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_id',
                (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_name',
                t.province_code,
                t.sale_office_code,
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim', 'esim')) AS 'renueve_sim',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'renueve_package',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'price_term') AS 'renueve_term'
            ";

        $criteria->condition = "
            (t.id IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim', 'esim')))
            AND (t.id IN (SELECT order_id FROM tbl_order_state 
                WHERE create_date >= '$start_date' 
                    AND create_date <= '$end_date' 
                    AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                        WHERE os2.order_id = t.id 
                            AND os2.delivered = 10
                        )
                    )
                )
        ";
        $criteria->order = "t.create_date ASC";

        if(!empty($this->province_code)){
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if(!empty($this->sale_office_code)){
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if(!empty($this->sim_type)){
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_sim WHERE order_id IS NOT NULL AND type = '$this->sim_type')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }

        if(!empty($this->receive_status)){
            if($this->receive_status == ReportForm::NOT_RECEIVED){
                $criteria->addCondition("t.receive_cash_by IS NULL OR t.receive_cash_by = ''");
            }else{
                $criteria->addCondition("t.receive_cash_by IS NOT NULL AND t.receive_cash_by != ''");
            }
        }

        if(!empty($this->payment_method)){
            $criteria->compare('t.payment_method', $this->payment_method, FALSE);
        }

        $cache_key = "Report_searchDetailRevenueSynthetic"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_brand_offices_id_'.$this->brand_offices_id
            .'_sim_type_'.$this->sim_type
            .'_input_type_'.$this->input_type
            .'_receive_status_'.$this->receive_status
            .'_payment_method_'.$this->payment_method;


        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[receive_status]"        => $this->receive_status,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }

    }

    /**
     * Tổng hợp báo cáo Thống kê bán SIM số+gói kèm SIM
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchStatisticSim($dataProvider = TRUE)
    {
        $data = array();
        $data_detail = $this->searchDetailStatisticSim(FALSE);

        $model_prepaid = new ROrders();
        $model_prepaid->type_sim = ASim::TYPE_PREPAID;
        $model_prepaid->renueve_sim = 0;
        $model_prepaid->renueve_package = 0;
        $model_prepaid->renueve_term = 0;

        $model_postpaid = new ROrders();
        $model_postpaid->type_sim = ASim::TYPE_POSTPAID;
        $model_postpaid->renueve_sim = 0;
        $model_postpaid->renueve_package = 0;
        $model_postpaid->renueve_term = 0;

        if(!empty($data_detail)){
            foreach ($data_detail as $item){
                if($item->type_sim == ASim::TYPE_PREPAID){
                    $model_prepaid->renueve_sim += $item->renueve_sim;
                    $model_prepaid->renueve_package += $item->renueve_package;
                    $model_prepaid->renueve_term += $item->renueve_term;
                }else if($item->type_sim == ASim::TYPE_POSTPAID){
                    $model_postpaid->renueve_sim += $item->renueve_sim;
                    $model_postpaid->renueve_package += $item->renueve_package;
                    $model_postpaid->renueve_term += $item->renueve_term;
                }
            }

            $data[] = $model_prepaid;
            $data[] = $model_postpaid;
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[receive_status]"        => $this->receive_status,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[online_status]"         => $this->online_status,
                        "ReportForm[item_sim_type]"         => $this->item_sim_type,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 10,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Thống kê bán SIM số+gói kèm SIM
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailStatisticSim($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';
        $criteria = new CDbCriteria();
        $criteria->select = "
                t.id,
                t.receive_cash_by,
                t.receive_cash_date,
                t.payment_method,
                (SELECT create_date FROM tbl_order_state WHERE id = (SELECT MAX(id) FROM tbl_order_state WHERE order_id = t.id)) as 'delivery_date',
                CASE
                    WHEN (t.receive_cash_by IS NOT NULL AND t.receive_cash_by != '')
                    THEN 2
                    ELSE 1
                END AS 'receive_status',
                (SELECT msisdn FROM tbl_sim WHERE order_id = t.id) AS 'sim',
                (SELECT type FROM tbl_sim WHERE order_id = t.id) AS 'type_sim',
                CASE
                    WHEN (t.shipper_id IS NOT NULL AND t.shipper_id != '')
                    THEN (SELECT username FROM tbl_shipper WHERE id = t.shipper_id)
                    ELSE ''
                END AS 'shipper_name',
                (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_id',
                (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_name',
                t.province_code,
                t.sale_office_code,
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'renueve_sim',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'renueve_package',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'price_term') AS 'renueve_term',
                t.affiliate_source,
                t.promo_code,
                (SELECT type FROM tbl_order_details WHERE order_id = t.id AND type IN ('sim','esim')) AS 'item_sim_type'
            ";

        $criteria->condition = "t.payment_method != '' AND t.payment_method IS NOT NULL
                                AND t.id IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim', 'esim'))";

        if(empty($this->online_status)){
            $criteria->addCondition("
                t.create_date >= '$start_date' AND t.create_date <= '$end_date'
                AND (t.id IN 
                    (SELECT os.order_id FROM tbl_order_state os
                        WHERE os.confirm = 10 AND os.id = (SELECT max(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = os.order_id
                        )
                    )
                )
            ");
        }else{
            if($this->online_status == 10){
                $criteria->addCondition("
                  (t.id IN (SELECT order_id FROM tbl_order_state 
                    WHERE create_date >= '$start_date' 
                        AND create_date <= '$end_date' 
                        AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = t.id 
                                AND os2.delivered = '$this->online_status'
                            )
                        )
                    )
                ");
            }
            else{
                $criteria->addCondition("
                    t.create_date >= '$start_date' AND t.create_date <= '$end_date'
                    AND (t.id IN 
                    (SELECT os.order_id FROM tbl_order_state os
                        WHERE os.confirm = '$this->online_status' AND os.id = (SELECT max(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = os.order_id
                        )
                    )
                )
                ");
            }
        }

        if(!empty($this->channel_code)){
            $criteria->addCondition("t.affiliate_source = '$this->channel_code'");
        }

        if(!empty($this->province_code)){
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if(!empty($this->sale_office_code)){
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if(!empty($this->sim_type)){
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_sim WHERE order_id IS NOT NULL AND type = '$this->sim_type')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }

        if(!empty($this->receive_status)){
            if($this->receive_status == ReportForm::NOT_RECEIVED){
                $criteria->addCondition("t.receive_cash_by IS NULL OR t.receive_cash_by = ''");
            }else{
                $criteria->addCondition("t.receive_cash_by IS NOT NULL AND t.receive_cash_by != ''");
            }
        }

        if(!empty($this->payment_method)){
            $criteria->compare('t.payment_method', $this->payment_method, FALSE);
        }

        if(!empty($this->item_sim_type)){
            if($this->item_sim_type == 2){
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'esim')");
            }else{
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'sim')");
            }
        }

        $criteria->order = "t.create_date ASC";

        $cache_key = "Report_searchDetailStatisticSim"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_brand_offices_id_'.$this->brand_offices_id
            .'_sim_type_'.$this->sim_type
            .'_input_type_'.$this->input_type
            .'_receive_status_'.$this->receive_status
            .'_payment_method_'.$this->payment_method
            .'_channel_code_'.$this->channel_code
            .'_online_status_'.$this->online_status
            .'_item_sim_type_'.$this->item_sim_type;


        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[sim_type]"              => $this->sim_type,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[receive_status]"        => $this->receive_status,
                        "ReportForm[payment_method]"        => $this->payment_method,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                        "ReportForm[channel_code]"          => $this->channel_code,
                        "ReportForm[online_status]"         => $this->online_status,
                        "ReportForm[item_sim_type]"         => $this->item_sim_type,
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }

    }

    public function searchStatisticPackage($dataProvider = TRUE)
    {
        $data = array();
        $data_raw = array();
        $data_detail = $this->searchDetailStatisticPackage(FALSE);
        if(!empty($data_detail)){
            foreach ($data_detail as $item){
                $item_id = strtoupper($item->item_id);
                $item_name = $item->item_name;
                $item_renueve = $item->renueve_package;

                if(isset($data_raw[$item_id])){
                    $data_raw[$item_id]['total_package'] ++;
                    $data_raw[$item_id]['renueve_package'] += $item_renueve;
                }else{
                    $data_raw[$item_id]['item_name'] = $item_name;
                    $data_raw[$item_id]['total_package'] = 1;
                    $data_raw[$item_id]['renueve_package'] = $item_renueve;
                }
            }

            foreach ($data_raw as $key => $item){
                $order = new ROrders();
                $order->item_id         = $key;
                $order->item_name       = $item['item_name'];
                $order->total_package   = $item['total_package'];
                $order->renueve_package = $item['renueve_package'];
                $data[] = $order;
            }
        }

        if($dataProvider){
            return new CArrayDataProvider($data, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[package_group]"         => $this->package_group,
                        "ReportForm[package_id]"            => $this->package_id,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[sim_freedoo]"           => $this->sim_freedoo,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[online_status]"         => $this->online_status,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 10,
                ),
            ));
        }else{
            return $data;
        }
    }

    /**
     * Chi tiết doanh thu gói đơn lẻ
     *
     * @param bool $dataProvider
     * @return ROrders[] | CArrayDataProvider
     */
    public function searchDetailStatisticPackage($dataProvider = TRUE)
    {
        $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date))) . ' 00:00:00';
        $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date))) . ' 23:59:59';

        $criteria = new CDbCriteria();
        $criteria->select = "
                t.id,
                t.province_code,
                t.promo_code,
                t.affiliate_source,
                (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_id',
                (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_name',
                (SELECT type FROM tbl_package WHERE code = (SELECT item_id FROM tbl_order_details WHERE order_id = t.id)) AS 'type_package',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'renueve_package',
                t.phone_contact,
                t.create_date,
                CASE 
                    WHEN (SELECT count(*) FROM tbl_sim WHERE msisdn = t.phone_contact) > 0 
                    THEN 1 
                    ELSE 0 
                END as 'sim_freedoo'
            ";

        $criteria->condition = "
            (t.id IN (
                SELECT 
                    order_id 
                FROM 
                    tbl_order_details od 
                INNER JOIN tbl_package p ON od.item_id = p.code
                WHERE 
                    od.type = 'package'
                    AND p.type NOT IN ('".ReportForm::SIMKIT."',
                        '".ReportForm::FLEXIBLE_SMS_INT."',
                        '".ReportForm::FLEXIBLE_SMS_EXT."',
                        '".ReportForm::FLEXIBLE_CALL_EXT."',
                        '".ReportForm::FLEXIBLE_CALL_INT."',
                        '".ReportForm::FLEXIBLE_DATA."'
                    )
            ))
            AND (t.id NOT IN (SELECT order_id FROM tbl_order_details WHERE type IN ('sim','esim')))
        ";

        if(!empty($this->online_status)){
            if($this->online_status == 10){
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_state 
                    WHERE create_date >= '$start_date' 
                        AND create_date <= '$end_date' 
                        AND id = (SELECT MIN(os2.id) FROM tbl_order_state os2 
                            WHERE os2.order_id = t.id 
                                AND os2.delivered = 10
                            )
                        )
                ");
            }else{
                $criteria->addCondition("
                    t.create_date >= '$start_date' AND t.create_date <= '$end_date'
                    AND (t.id IN 
                        (SELECT os.order_id FROM tbl_order_state os
                            WHERE os.confirm = '$this->online_status' AND os.id = (SELECT max(os2.id) FROM tbl_order_state os2 
                                WHERE os2.order_id = os.order_id
                            )
                        )
                    )
                ");
            }
        }

        $criteria->order = "t.create_date ASC";

        if (!empty($this->province_code)) {
            $criteria->addCondition("t.province_code = '$this->province_code'");
        }
        if (!empty($this->sale_office_code)) {
            $criteria->addCondition("t.sale_office_code = '$this->sale_office_code'");
        }
        if (!empty($this->package_group)) {
            $criteria->addCondition("t.id IN (
                SELECT 
                    order_id 
                FROM 
                    tbl_order_details od 
                INNER JOIN tbl_package p ON od.item_id = p.code
                WHERE 
                    od.type = 'package'
                    AND p.type = '$this->package_group'
            )");
        }
        if (!empty($this->package_id)) {
            $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package' AND item_id = '$this->package_id')");
        }
        if (!empty($this->input_type)) {
            if ($this->input_type == 2) {
                $criteria->addCondition("t.delivery_type = '2'");
                if (!empty($this->brand_offices_id)) {
                    $criteria->addCondition("t.address_detail = '$this->brand_offices_id'");
                }
            } else if ($this->input_type == 1) {
                $criteria->addCondition("t.delivery_type = '1'");
            }
        }
        if(!empty($this->sim_freedoo)){
            if($this->sim_freedoo == ReportForm::FREEDOO_TYPE){
                $operator = '>';
            }else{
                $operator = '=';
            }
            $criteria->addCondition("(SELECT count(*) FROM tbl_sim WHERE msisdn = t.phone_contact) $operator 0");
        }

        $cache_key = "Report_searchDetailStatisticPackage"
            .'_start_date_'.$start_date
            .'_end_date_'.$end_date
            .'_province_code_'.$this->province_code
            .'_sale_office_code_'.$this->sale_office_code
            .'_package_group_'.$this->package_group
            .'_package_id_'.$this->package_id
            .'_input_type_'.$this->input_type
            .'_sim_freedoo_'.$this->sim_freedoo
            .'_brand_offices_id_'.$this->brand_offices_id
            .'_online_status_'.$this->online_status;


        $result  = Yii::app()->cache->get($cache_key);
        if(!$result){
            $result = ROrders::model()->findAll($criteria);
            Yii::app()->cache->set($cache_key, $result, 60*10);
        }

        if ($dataProvider) {
            return new CArrayDataProvider($result, array(
                'keyField' => false,
                'pagination' => array(
                    'params'   => array(
                        'get'                               => 1,
                        'ReportForm[start_date]'            => $this->start_date,
                        'ReportForm[end_date]'              => $this->end_date,
                        "ReportForm[province_code]"         => $this->province_code,
                        "ReportForm[sale_office_code]"      => $this->sale_office_code,
                        "ReportForm[package_group]"         => $this->package_group,
                        "ReportForm[package_id]"            => $this->package_id,
                        "ReportForm[input_type]"            => $this->input_type,
                        "ReportForm[sim_freedoo]"           => $this->sim_freedoo,
                        "ReportForm[brand_offices_id]"      => $this->brand_offices_id,
                        "ReportForm[online_status]"         => $this->online_status,
                        "ReportForm[on_detail]"             => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 30,
                ),
            ));
        } else {
            return $result;
        }
    }



    public function getRevenueDailyEmail()
    {
        $data_date = Utils::getListDate($this->start_date, $this->end_date);
        $data_sim = $this->searchDetailRenueveSim(FALSE);
        $data_package_simkit = $this->searchDetailRenuevePackageSimKit(FALSE);
        $data_package = $this->searchDetailRenuevePackageSingle(FALSE);

        $data = array();

        foreach ($data_date as $date){
            if(!isset($data[$date])){
                $data[$date]['date']                = $date;
                $data[$date]['sim_pre_total']       = 0;
                $data[$date]['sim_post_renueve']    = 0;
                $data[$date]['sim_post_total']      = 0;
                $data[$date]['sim_post_renueve']    = 0;
                $data[$date]['package_total']       = 0;
                $data[$date]['package_renueve']     = 0;
                $data[$date]['package_single_total']       = 0;
                $data[$date]['package_single_renueve']     = 0;
            }
            foreach ($data_sim as $order){
                if(date('Y-m-d', strtotime($order->create_date)) == $date){
                    if($order->type_sim == ASim::TYPE_PREPAID){
                        $data[$date]['sim_pre_total']++;
                        $data[$date]['sim_pre_renueve']+= $order->renueve_sim;
                    }else if($order->type_sim == ASim::TYPE_POSTPAID){
                        $data[$date]['sim_post_total']++;
                        $data[$date]['sim_post_renueve']+= $order->renueve_sim;
                    }

                }
            }
            foreach ($data_package_simkit as $order){
                if(date('Y-m-d', strtotime($order->state_date)) == $date){
                    $data[$date]['package_total']++;
                    $data[$date]['package_renueve']+= $order->renueve;
                }
            }
            foreach ($data_package as $order){
                if(date('Y-m-d', strtotime($order->state_date)) == $date){
                    $data[$date]['package_single_total']++;
                    $data[$date]['package_single_renueve']+= $order->renueve_package;
                }
            }

        }

        return array_values($data);
    }


    public function getRevenueAccumulated()
    {
        $criteria         = new CDbCriteria();
        $criteria->select = "t.id, od.type AS 'type', od.price AS 'renueve'";
        $criteria->condition = "t.create_date >= '2019-01-01 00:00:00'
	                        AND t.create_date <= '$this->end_date'
	                        AND t.id IN (SELECT order_id FROm tbl_order_state WHERE delivered = 10)
	                        AND od.type IN ('sim', 'esim', 'package')
                        ";

        $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id";
        $orders = ROrders::model()->findAll($criteria);
        $data[0]['total'] = 0;
        $data[0]['renueve'] = 0;

        foreach ($orders as $order){
            if(in_array($order->type, array('sim', 'esim'))){
                $data[0]['total']++;
            }
            $data[0]['renueve']+= $order->renueve;
        }

        return $data;
    }
    
    public function getDataTotalSim(){
        $criteria         = new CDbCriteria();
        $criteria->select = "COUNT(*) AS 'total_sim',SUM(od.price) AS 'renueve_sim', SUBSTR(os.create_date,1,4) AS 'year'";
        $criteria->condition = "
             od.type IN ('sim','esim','package')
             AND os.id = (SELECT MAX(id) FROM tbl_order_state WHERE order_id = t.id)
             AND os.delivered = '10'
        ";
        $criteria->join = "
                     INNER JOIN tbl_order_details od ON od.order_id = t.id
                     INNER JOIN tbl_order_state os ON os.order_id = t.id
        ";
        $criteria->group = "SUBSTR(os.create_date,1,4)";
        $orders = ROrders::model()->findAll($criteria);

        $arr = array();
        foreach ($orders as $key=>$val){
            $result_key['year'] = $val['year'];
            $result_key['total_sim'] = $val['total_sim'];
            $result_key['renueve_sim'] = $val['renueve_sim'];
            $result_key['total_sim_tourist'] ='';
            $result_key['renueve_sim_tourist'] = '';
            $arr[] = $result_key;
        }
        return $arr;
    }
    public function getDataTotalSimTourist(){
        $criteria         = new CDbCriteria();
        // SUM(od.total_success), SUM(od.price*od.total_success)
        $criteria->select = "SUM(od.total_success) AS 'total_sim_tourist',SUM(od.price*od.total_success) AS 'renueve_sim_tourist', SUBSTR(t.create_time,1,4) AS 'year'";
        // status = 8,9,10
        $criteria->condition = "
            t.`status` IN (8,9,10)
        ";
        $criteria->join = "
                     INNER JOIN tbl_order_details od ON od.order_id = t.id
        ";
        $criteria->group = "SUBSTR(t.create_time,1,4)";
        $orders = AFTOrders::model()->findAll($criteria);
        $arr = array();
        foreach ($orders as $key=>$val){
            $result_key['year'] = $val['year'];
            $result_key['total_sim'] = '';
            $result_key['renueve_sim'] = '';
            $result_key['total_sim_tourist'] = $val['total_sim_tourist'];
            $result_key['renueve_sim_tourist'] = $val['renueve_sim_tourist'];
            $arr[] = $result_key;
        }
        return $arr;
    }

    public function getOrderStatisticDailyEmail()
    {
        if ($this->start_date && $this->end_date) {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
        }
        $criteria         = new CDbCriteria();
        $criteria->select = "t.create_date, os.delivered, os.confirm";

        $criteria->condition = "os.id=(SELECT max(os2.id) FROM tbl_order_state os2 WHERE os2.order_id = t.id)
                                AND t.create_date >= '$this->start_date' 
                                AND t.create_date <= '$this->end_date'
                                AND t.id IN (SELECT order_id FROM tbl_order_state WHERE confirm = 10)";

        $criteria->join  = "INNER JOIN tbl_order_state os ON os.order_id = t.id";

        $orders = ROrders::model()->findAll($criteria);

        $data = array();
        $list_date = Utils::getListDate($this->start_date, $this->end_date);

        foreach ($list_date as $date) {
            if(!isset($data[$date])){
                $data[$date]['date']            = $date;
                $data[$date]['order_confirm']   = 0;
                $data[$date]['order_success']   = 0;
                $data[$date]['order_cancel']    = 0;
            }
            foreach ($orders as $order) {

                if (date('Y-m-d', strtotime($order->create_date)) == $date) {
                    if ($order->delivered == '10') {
                        $data[$date]['order_success']++;
                    }
                    if ($order->delivered != '10' && $order->confirm == '10') {
                        $data[$date]['order_confirm']++;
                    }
                    if ($order->confirm == '2' || $order->confirm == '3') {
                        $data[$date]['order_cancel']++;
                    }
                }

            }

        }

        return array_values($data);
    }


}

