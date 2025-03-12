<?php

    class AFTReport
    {
        public $start_date;
        public $end_date;
        public $user_tourist;
        public $status_order;
        public $contract_id;
        public $order_id;
        public $total;
        public $item_id;
        public $province_code;

        public function __construct()
        {
            $this->_ora = Oracle::getInstance();
            $this->_ora->connect();
        }

        public function getOrderData($excel = FALSE)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria            = new CDbCriteria();
            $criteria->select    = "t.id, t.total_success, t.total_fails,
                                    (
                                        SELECT
                                            SUM(od1.quantity)
                                        FROM
                                            vsb_tourist.tbl_order_details od1
                                        WHERE
                                            od1.order_id = od.order_id
                                    ) AS total,
                                    t.status";
            $criteria->condition = "1=1";
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_time >= '$this->start_date' and t.create_time <='$this->end_date' and t.status !=-1");
            }

            if ($this->user_tourist != '') {
                $criteria->addCondition("c.user_id ='$this->user_tourist'");
            }
            if ($this->status_order != '') {
                $criteria->addCondition("t.status ='$this->status_order'");
            }
            if ($this->order_id != '') {
                $criteria->addCondition("t.id ='$this->order_id'");
            }
            if ($this->contract_id != '') {
                $criteria->addCondition("t.contract_id ='$this->contract_id'");
            }
            if (!ADMIN && !SUPER_ADMIN) {
                if (isset(Yii::app()->user->vnp_province_id)) {
                    if (!empty(Yii::app()->user->vnp_province_id)) {
                        $criteria->addCondition("t.province_code ='" . Yii::app()->user->vnp_province_id . "'");
                    }
                }
            }
            if ($this->user_tourist != '') {
                $criteria->join = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id
                                   INNER JOIN vsb_tourist.tbl_contracts c ON c.id = t.contract_id ";
            } else {
                $criteria->join = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id";
            }
            $criteria->group = "t.id";
            if ($excel) {
                $data = AFTOrders::model()->findAll($criteria);
            } else {
                $data = new CActiveDataProvider('AFTOrders', array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 't.create_time asc'),
                    'pagination' => array(
                        'params'   => array(
                            'get'                         => 1,
                            'AFTReportForm[start_date]'   => $this->start_date,
                            'AFTReportForm[end_date]'     => $this->end_date,
                            'AFTReportForm[user_tourist]' => $this->user_tourist,
                            'AFTReportForm[status_order]' => $this->status_order,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }

            return $data;
        }

        public function getDetailOrders($order_id)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }


            $result = array();

            $sql = "SELECT
                        MSISDN,
                        CONTRACT_ID, 
                        ORDER_ID,
                        ORDER_STATUS,
                        ASSIGN_KIT_STATUS,
                        to_char(CREATED_DATE,'yyyy-mm-dd hh24:mi:ss') AS CREATED_DATE,
                        to_char(ASSIGN_KIT_TIME,'yyyy-mm-dd hh24:mi:ss') AS ASSIGN_KIT_TIME,
                        NOTE,
                        PROVINCE_CODE,
                        to_char(SERIA_NUMBER) AS SERIA_NUMBER,
                        NOTE,
                        SUB_TYPE
                        FROM 
                        SDL_ACTIONS
                        ";
            $sql .= " WHERE 1=1";
            if ($this->start_date !== '' && $this->end_date !== '') {
                $sql .= " AND
                                    CREATED_DATE >= to_date ('" . $this->start_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                 AND
                                    CREATED_DATE <= to_date ('" . $this->end_date . "', 'YYYY-MM-DD - HH24:MI:SS')
                                ";
            }
            if ($order_id != '') {
                $sql .= " AND ORDER_ID =:ORDER_ID";
            }
            $stmt = oci_parse($this->_ora->oraConn, $sql);
            if ($order_id != '') {
                oci_bind_by_name($stmt, ':ORDER_ID', $order_id);
            }

            oci_execute($stmt);
            $result = array();
            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }

            return $result;
        }

        public static function getTotal($type = 1, $order_id)
        {


            $result = array();

            $sql = "SELECT
                        COUNT(ORDER_ID) AS TOTAL
                        FROM 
                        SDL_ACTIONS
                        ";
            $sql .= " WHERE 1=1";

            $success = 10;
            if ($order_id != '') {
                $sql .= " AND ORDER_ID =:ORDER_ID";
            }
            if ($type == '1') {
                $sql .= " AND ASSIGN_KIT_STATUS =:ASSIGN_KIT_STATUS";
            } else {
                $sql .= " AND ASSIGN_KIT_STATUS !=:ASSIGN_KIT_STATUS ";
            }
            $stmt = oci_parse(Oracle::getInstance()->oraConn, $sql);
            if ($order_id != '') {
                oci_bind_by_name($stmt, ':ORDER_ID', $order_id);
            }
            oci_bind_by_name($stmt, ':ASSIGN_KIT_STATUS', $success);
            oci_execute($stmt);
            $result = array();
            while ($entry = oci_fetch_array($stmt, OCI_ASSOC)) {
                $result[] = $entry;
            }

            return $result;
        }

        public function getRenueveOverview()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select = "p.name as package_name, SUM(od.total_success) as total, SUM(od.total_success * od.price) as total_renueve";
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_time >='$this->start_date' and t.create_time <= '$this->end_date'");
            }
            if ($this->status_order != '') {
                $criteria->addCondition("t.status ='$this->status_order'");
            }
            if ($this->user_tourist != '') {
                $criteria->addCondition("c.user_id ='$this->user_tourist'");
            }
            if ($this->order_id != '') {
                $criteria->addCondition("t.id ='$this->order_id'");
            }
            if ($this->item_id != '') {
                $criteria->addCondition("p.id ='$this->item_id'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='$this->province_code'");
            }
            $criteria->join  = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id
                              INNER JOIN vsb_tourist.tbl_package p ON p.id = od.item_id
                              INNER JOIN vsb_tourist.tbl_contracts c ON c.id = t.contract_id ";
            $criteria->group = "p.name";

            $data = self::controlRenueveOverview(AFTOrders::model()->findAll($criteria));

            return $data;
        }

        public function getRenueveDetails($excel = FALSE)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select = "t.code as code,
                                c.code as contract_code,
                                c.user_id as user_tourist,
                                SUM(od.total_success) as total,
                                SUM(od.total_success * od.price) as total_renueve";
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_time >='$this->start_date' and t.create_time <= '$this->end_date'");
            }
            if ($this->status_order != '') {
                $criteria->addCondition("t.status ='$this->status_order'");
            }
            if ($this->user_tourist != '') {
                $criteria->addCondition("c.user_id ='$this->user_tourist'");
            }
            if ($this->order_id != '') {
                $criteria->addCondition("t.id ='$this->order_id'");
            }
            if ($this->item_id != '') {
                $criteria->addCondition("p.id ='$this->item_id'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code ='$this->province_code'");
            }
            $criteria->join  = "INNER JOIN vsb_tourist.tbl_order_details od ON od.order_id = t.id
                              INNER JOIN vsb_tourist.tbl_package p ON p.id = od.item_id
                              INNER JOIN vsb_tourist.tbl_contracts c ON c.id = t.contract_id ";
            $criteria->group = "t.code";
            IF ($excel) {

                $criteria->order = "t.create_time asc";

                $data = AFTOrders::model()->findAll($criteria);

            } ELSE {
                $data = new CActiveDataProvider('AFTOrders', array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 't.create_time asc'),
                    'pagination' => array(
                        'params'   => array(
                            'get'                          => 1,
                            'AFTReportForm[start_date]'    => $this->start_date,
                            'AFTReportForm[end_date]'      => $this->end_date,
                            'AFTReportForm[order_id]'      => $this->order_id,
                            'AFTReportForm[province_code]' => $this->province_code,
                            'AFTReportForm[user_tourist]'  => $this->user_tourist,
                            'AFTReportForm[item_id]'       => $this->item_id,
                            'AFTReportForm[status_order]'  => $this->status_order,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }

            return $data;
        }

        public static function controlRenueveOverview($data)
        {
            $result = array();
            if (!empty($data) && is_array($data)) {
                foreach ($data as $key => $value) {
                    $result_key                  = array(
                        'package_name'  => '',
                        'total'         => '',
                        'total_renueve' => '',
                    );
                    $result_key['package_name']  = isset($value->package_name) ? $value->package_name : '';
                    $result_key['total']         = isset($value->total) ? $value->total : '';
                    $result_key['total_renueve'] = isset($value->total_renueve) ? $value->total_renueve : '';
                    $result[]                    = $result_key;
                }
            }

            return $result;
        }

        public static function getStatusJoinKit($status)
        {
            $data = array(
                0  => 'Chưa xử lý',
                1  => 'Thất bại',
                10 => 'Hoàn thành'
            );

            return isset($data[$status]) ? $data[$status] : '';
        }


    }


?>