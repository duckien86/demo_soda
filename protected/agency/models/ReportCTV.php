<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class ReportCTV
    {
        public $start_date;
        public $end_date;
        public $ctv_id; // Mã CTV
        public $province_code; // Trung tâm kinh doanh
        public $sim_type; // Hình thức sim
        public $on_detail; // Check box detail
        public $package_id; // Check box detail
        public $period; // Check box detail
        public $package_group; // Check box detail

        public $invitation;
        public $create_date;
        public $commission_earned;
        public $total;

        const SIM_TYPE     = 'sim';
        const CARD_TYPE    = 'card';
        const PACKAGE_TYPE = 'package';

        const SIM_TYPE_RENUEVE        = 'sim';
        const PACKAGE_TYPE_RENUEVE    = 'package';
        const CARD_TYPE_RENUEVE       = 'card';
        const PACKAGE_KEEPING_RENUEVE = 'package_keeping';
        const SUPPORT_RENUEVE         = 'support';
        const BROKER_RENUEVE          = 'broker';


        // Function.

        /**
         * Lấy thông tin tông quan doanh thu  sim.
         */
        public function getSim()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria            = new CDbCriteria();
            $criteria->select    = "o.invitation as invitation, s.type as type, sum(t.commission_earned) as total_renueve, count(t.item_id) as total_sim, t.create_date";
            $criteria->condition = "t.item_type ='" . self::SIM_TYPE . "' AND t.config_commision_code= '" . self::SIM_TYPE_RENUEVE . "'
                                    and t.create_date between '$this->start_date' and '$this->end_date'";

            if ($this->province_code != '') {
                $criteria->addCondition("o.province_code ='" . $this->province_code . "'");
            }
            if ($this->ctv_id != '') {
                $criteria->addCondition("o.invitation='" . $this->ctv_id . "'");
            }
            $criteria->join  = "INNER JOIN {{sim}} s ON s.msisdn = t.item_id
                                    INNER JOIN {{orders}} o ON o.id = t.order_id ";
            $criteria->group = "s.type, o.invitation";

            $data = Commission::model()->findAll($criteria);

            return $data;
        }

        public function getDetailSim()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria            = new CDbCriteria();
            $criteria->select    = "s.msisdn, o.create_date as create_date_sell,c.username as customer_name, o.invitation as invitation";
            $criteria->condition = "t.item_type ='" . self::SIM_TYPE . "' AND t.config_commision_code ='" . self::SIM_TYPE_RENUEVE . "' 
                                    and t.create_date between '$this->start_date' and '$this->end_date'";
            if ($this->province_code != '') {
                $criteria->addCondition("o.province_code ='" . $this->province_code . "'");
            }
            if ($this->ctv_id != '') {
                $criteria->addCondition("o.invitation='" . $this->ctv_id . "'");
            }
            $criteria->join = "INNER JOIN {{sim}} s ON s.msisdn = t.item_id
                               INNER JOIN {{orders}} o ON o.id = t.order_id
                               INNER JOIN {{customers}} c ON c.sso_id = o.sso_id";
            $data           = new CActiveDataProvider('Commission', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                       => 1,
                        'ReportForm[start_date]'    => $this->start_date,
                        'ReportForm[end_date]'      => $this->end_date,
                        "ReportForm[province_code]" => $this->province_code,
                        "ReportForm[ctv_id]"        => $this->ctv_id,
                        "ReportForm[on_detail]"     => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 20,
                ),
            ));

            return $data;
        }

        /**
         * Xử lý dữ liệu doanh thu
         */
        public function controlRenueveSim($data)
        {
            $result = array();

            $type_sim = array(
                1 => 1,
                2 => 2,
            );
            if (isset($data) && is_array($data)) {
                foreach ($type_sim as $type) {
                    $result_key         = array(
                        'type'          => '',
                        'total_sim'     => '',
                        'total_renueve' => '',
                    );
                    $result_key['type'] = $type;
                    foreach ($data as $value) {
                        if ($value->type == $type) {
                            $result_key['total_renueve'] += $value->total_renueve;
                            $result_key['total_sim'] += $value->total_sim;
                        }
                    }
                    $result[] = $result_key;
                }
            }

            return $result;
        }

        /**
         *  Lấy username khách hàng by sso_id.
         */
        public function getCustomerByID($sso_id)
        {

            $data = Customers::model()->findByAttributes(array('sso_id' => $sso_id));

            return $data;
        }

        /**
         * Lấy hình thức sim by type
         */
        public function getTypeSim($type)
        {
            $data = array(
                1 => "Trả trước",
                2 => "Trả sau",
                3 => "Data",
                4 => "Vas",
            );

            return $data[isset($type) ? $type : 1];
        }

        public function getTitleDetail()
        {
            return array(
                'msisdn'           => 'Số điện thoại',
                'create_date_sell' => 'Ngày mua',
                'customer_name'    => 'Khách hàng',
                'invitation'       => 'Mã giới thiệu',
            );
        }

        public function getPackageRenueve($type)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria            = new CDbCriteria();
            $criteria->select    = "p.name as package_name, p.price as package_price, count(t.item_id) as total_package, sum(t.commission_earned) as total_renueve";
            $criteria->condition = "t.item_type ='" . self::PACKAGE_TYPE . "' AND t.config_commision_code='" . $type . "'
                                    and t.create_date between '$this->start_date' and '$this->end_date'";

            if ($this->period != '') {
                $criteria->addCondition("p.period ='" . $this->period . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("t.item_id='" . $this->package_id . "'");
            }
            if ($this->package_group != '') {
                $criteria->addCondition("p.type='" . $this->package_group . "'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("o.province_code ='" . $this->province_code . "'");
            }
            if ($this->ctv_id != '') {
                $criteria->addCondition("o.invitation='" . $this->ctv_id . "'");
            }
            $criteria->join  = "INNER JOIN tbl_package p ON p.id = t.item_id
                                INNER JOIN {{orders}} o ON o.id = t.order_id
                                INNER JOIN {{customers}} c ON c.sso_id = o.sso_id";
            $criteria->group = "p.name";


            $data = Commission::model()->findAll($criteria);

            return $data;
        }

        public function getPackageDetail($type)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria            = new CDbCriteria();
            $criteria->select    = " o.phone_contact as phone_contact, o.create_date as create_date_sell,p.name as package_name, p.price as package_price,  o.invitation as invitation";
            $criteria->condition = "t.item_type ='" . self::PACKAGE_TYPE . "' AND t.config_commision_code='" . $type . "'
                                    and t.create_date between '$this->start_date' and '$this->end_date'";

            if ($this->period != '') {
                $criteria->addCondition("p.period ='" . $this->period . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("t.item_id='" . $this->package_id . "'");
            }
            if ($this->package_group != '') {
                $criteria->addCondition("p.type='" . $this->package_group . "'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("o.province_code ='" . $this->province_code . "'");
            }
            if ($this->ctv_id != '') {
                $criteria->addCondition("o.invitation='" . $this->ctv_id . "'");
            }
            $criteria->join  = "INNER JOIN tbl_package p ON p.id = t.item_id
                                INNER JOIN tbl_orders o ON o.id = t.order_id
                                INNER JOIN {{customers}} c ON c.sso_id = o.sso_id";
            $criteria->group = "p.name";

            $data = new CActiveDataProvider('Commission', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                       => 1,
                        'ReportForm[start_date]'    => $this->start_date,
                        'ReportForm[end_date]'      => $this->end_date,
                        "ReportForm[period]"        => $this->period,
                        "ReportForm[package_id]"    => $this->package_id,
                        "ReportForm[package_group]" => $this->package_group,
                        "ReportForm[on_detail]"     => isset($this->on_detail) ? $this->on_detail : "on",
                    ),
                    'pageSize' => 10,
                ),
            ));

            return $data;
        }

        /**
         * Xử lý dữ liệu doanh thu
         */
        public function controlRenuevePackage($data)
        {
            $result = array();

            $package = array();
            foreach ($data as $value) {
                if (!in_array($value->package_name, $package)) {
                    $package[] = $value->package_name;
                }
            }
            if (isset($data) && is_array($data)) {
                foreach ($package as $packages) {
                    $result_key                 = array(
                        'package_name'  => '',
                        'package_price' => '',
                        'total_package' => '',
                        'total_renueve' => '',
                    );
                    $result_key['package_name'] = $packages;

                    foreach ($data as $value) {
                        if ($value->package_name == $packages) {
                            $result_key['package_price'] = $value->package_price;
                            $result_key['total_renueve'] += $value->total_renueve;
                            $result_key['total_package'] += $value->total_package;
                        }
                    }
                    $result[] = $result_key;
                }
            }

            return $result;
        }

        public function getBrokerSupportCTV($type)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria            = new CDbCriteria();
            $criteria->select    = "t.item_type, count(t.item_type) as total, sum(t.commission_earned) as total_renueve";
            $criteria->condition = "t.config_commision_code='" . $type . "'
                                    and t.create_date between '$this->start_date' and '$this->end_date'";

            if ($this->ctv_id != '') {
                $criteria->addCondition("o.invitation='" . $this->ctv_id . "'");
            }
            $criteria->join  = "INNER JOIN {{orders}} o ON o.id = t.order_id";
            $criteria->group = "t.item_type";

            $data = new CActiveDataProvider('Commission', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                    => 1,
                        'ReportForm[start_date]' => $this->start_date,
                        'ReportForm[end_date]'   => $this->end_date,
                        "ReportForm[ctv_id]"     => $this->ctv_id,
                    ),
                    'pageSize' => 10,
                ),
            ));

            return $data;
        }

        /**
         * @param       $records
         * @param array $columns
         * Lấy cột footer Tổng của tbgridView.
         *
         * @return array
         */
        public function getTotal($records, $columns = array())
        {
            $total = array();


            foreach ($records as $record) {

                foreach ($columns as $column) {
                    if (!isset($total[$column])) $total[$column] = 0;
                    $total[$column] += $record[$column];
                }
            }

            return $total;
        }
    }
