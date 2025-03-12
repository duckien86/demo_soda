<?php

    class AReportAT
    {
        public $start_date;
        public $end_date;
        public $ctv_id; // Mã CTV
        public $province_code; // Trung tâm kinh doanh
        public $sim_type; // Hình thức sim
        public $channel_code; // Kênh bán hàng
        public $package_group; //Nhóm gói
        public $package_id;
        public $ctv_type;
        public $status;
        public $month;
        public $year;

        const SIM_TYPE     = 1;
        const CARD_TYPE    = 2;
        const PACKAGE_TYPE = 3;

        const PREPAID  = 1;
        const POSTPAID = 2;

        const HOME = 1;
        const DGD  = 2;


        /**
         * Lấy số liệu tổng quan sim.
         */
        public function getSimOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "type,SUM(total_income) as total_renueve, SUM(total) as total_order";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "create_date >= '$this->start_date' and create_date <='$this->end_date' and affiliate_channel =1";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->channel_code != '') {
                $criteria->addCondition("affiliate_channel ='" . $this->channel_code . "'");
            }
            if ($this->sim_type != '') {
                $criteria->addCondition("type ='" . $this->sim_type . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("status ='" . $this->status . "'");
            }
            $criteria->group = "type";
            $data            = new CActiveDataProvider('ACommissionSimByDate', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                       => 1,
                        'ReportForm[start_date]'    => $this->start_date,
                        'ReportForm[end_date]'      => $this->end_date,
                        'ReportForm[province_code]' => $this->province_code,
                        'ReportForm[sim_type]'      => $this->sim_type,
                        'ReportForm[channel_code]'  => $this->channel_code,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

        public function getSimDetails($excel = FALSE)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "create_date >= '$this->start_date' and create_date <='$this->end_date' and affiliate_channel=1";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->channel_code != '') {
                $criteria->addCondition("affiliate_channel ='" . $this->channel_code . "'");
            }
            if ($this->sim_type != '') {
                $criteria->addCondition("sub_type ='" . $this->sim_type . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("order_status ='" . $this->status . "'");
            }
            if ($excel) {
                $criteria->order = "create_date asc";
                $data            = ACommisionsSimDetails::model()->findAll($criteria);

                return $data;
            }
            $data = new CActiveDataProvider('ACommisionsSimDetails', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'AReportATForm[start_date]'    => $this->start_date,
                        'AReportATForm[end_date]'      => $this->end_date,
                        'AReportATForm[province_code]' => $this->province_code,
                        'AReportATForm[sim_type]'      => $this->sim_type,
                        'AReportATForm[channel_code]'  => $this->channel_code,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

        public function getPackageOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "item_id,SUM(total_income) as total_renueve, SUM(total) as total_order";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "create_date >= '$this->start_date' and create_date <='$this->end_date' and affiliate_channel =1";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->channel_code != '') {
                $criteria->addCondition("affiliate_channel ='" . $this->channel_code . "'");
            }
            if ($this->package_group != '') {
                $criteria->addCondition("type ='" . $this->package_group . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("type ='" . $this->package_group . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("status ='" . $this->status . "'");
            }
            $criteria->group = "item_id";

            $data = new CActiveDataProvider('ACommissionPackageByDate', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'AReportATForm[start_date]'    => $this->start_date,
                        'AReportATForm[end_date]'      => $this->end_date,
                        'AReportATForm[province_code]' => $this->province_code,
                        'AReportATForm[channel_code]'  => $this->channel_code,
                        'AReportATForm[package_id]'    => $this->package_id,
                        'AReportATForm[status]'        => $this->status,
                        'AReportATForm[package_group]' => $this->package_group,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

        public function getPackageDetails($excel = FALSE)
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "create_date >= '$this->start_date' and create_date <='$this->end_date' and affiliate_channel=1";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->channel_code != '') {
                $criteria->addCondition("affiliate_channel ='" . $this->channel_code . "'");
            }
            if ($this->package_group != '') {
                $criteria->addCondition("package_type ='" . $this->package_group . "'");
            }
            if ($this->package_id != '') {
                $criteria->addCondition("package_id ='" . $this->package_group . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("order_status ='" . $this->status . "'");
            }
            if ($excel) {
                $criteria->order = "create_date asc";
                $data            = ACommisionsPackageDetails::model()->findAll($criteria);

                return $data;
            }
            $data = new CActiveDataProvider('ACommisionsPackageDetails', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.create_date asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'AReportATForm[start_date]'    => $this->start_date,
                        'AReportATForm[end_date]'      => $this->end_date,
                        'AReportATForm[province_code]' => $this->province_code,
                        'AReportATForm[channel_code]'  => $this->channel_code,
                        'AReportATForm[package_group]' => $this->package_group,
                        'AReportATForm[package_id]'    => $this->package_id,
                        'AReportATForm[status]'        => $this->status,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

        /**
         * Lấy số liệu tổng quan sim.
         */
        public function getAffiliateSimOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "order_province_code, SUM(item_price) as total_renueve, count(order_id) as total_order";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "order_create_date >= '$this->start_date' and order_create_date <='$this->end_date' and affiliate_channel IN('5','6')";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("order_status ='" . $this->status . "'");
            }
            $criteria->group = "order_province_code";

            $data = ACommisionsSimDetails::model()->findAll($criteria);

            return $data;
        }

        /**
         * Lấy số liệu tổng quan sim.
         */
        public function getAffiliatePackageOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "order_province_code, SUM(item_price) as total_renueve, count(order_id) as total_order";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "order_create_date >= '$this->start_date' and order_create_date <='$this->end_date' and affiliate_channel IN('5','6')";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("order_province_code ='" . $this->province_code . "'");
            }
            if ($this->status != '') {
                $criteria->addCondition("order_status ='" . $this->status . "'");
            }
            $criteria->group = "order_province_code";

            $data = ACommisionsPackageDetails::model()->findAll($criteria);

            return $data;
        }

        public function getAffiliateOverView()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "t.vnp_province_id, count(DISTINCT t.order_code) as total_order, SUM(total_money) as total_renueve, 
                                SUM(amount) as total_commision";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t.created_on >= '$this->start_date' and t.created_on <='$this->end_date'";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.vnp_province_id ='" . $this->province_code . "'");
            }
            if ($this->status != '') {
                if ($this->status == '3') {
                    $criteria->addCondition("t.action_status ='" . $this->status . "'");
                } else {
                    $criteria->addCondition("t.action_status IN('1','2')");
                }
            }
            $criteria->join  = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id=t.publisher_id";
            $criteria->group = "t.vnp_province_id";

            $data = new CActiveDataProvider('ACtvActions', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.created_on asc'),
                'pagination' => array(
                    'params'   => array(
                        'get'                          => 1,
                        'AReportATForm[start_date]'    => $this->start_date,
                        'AReportATForm[end_date]'      => $this->end_date,
                        'AReportATForm[province_code]' => $this->province_code,
                        'AReportATForm[status]'        => $this->status,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

        public function getSimAffiliateDetails()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "DISTINCT t.order_code, t.product_name, t.msisdn, t.publisher_id, t.total_money, 
                    t.amount, t.price as price_sim, t.vnp_province_id, t.transaction_id, t.action_status, t.type";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t.created_on >= '$this->start_date' and t.created_on <='$this->end_date' 
                and t.campaign_category_id=1 ";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.vnp_province_id ='" . $this->province_code . "'");
            }
            if ($this->status != '') {
                if ($this->status == '3') {
                    $criteria->addCondition("t.action_status ='" . $this->status . "'");
                } else {
                    $criteria->addCondition("t.action_status IN('1','2')");
                }
            }
            $criteria->join  = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id=t.publisher_id";
            $criteria->order = "t.created_on asc";
            $data            = ACtvActions::model()->findAll($criteria);

            return $data;
        }

        public function getPackageAffiliateDetails()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria         = new CDbCriteria();
            $criteria->select = "DISTINCT t.order_code, t.product_name, t.msisdn, t.publisher_id, t.total_money, 
            t.amount, t.price as price_package, t.vnp_province_id, t.transaction_id, t.action_status, t.type";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t.created_on >= '$this->start_date' and t.created_on <='$this->end_date' 
                and t.campaign_category_id=2";
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.vnp_province_id ='" . $this->province_code . "'");
            }
            if ($this->status != '') {
                if ($this->status == '3') {
                    $criteria->addCondition("t.action_status ='" . $this->status . "'");
                } else {
                    $criteria->addCondition("t.action_status IN('1','2')");
                }
            }
            $criteria->join  = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id=t.publisher_id";
            $criteria->order = "t.created_on asc";
            $data            = ACtvActions::model()->findAll($criteria);

            return $data;

        }

        public function getPaidAffiliateDetails()
        {
//            if ($this->start_date && $this->end_date) {
//                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
//                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
//            }

            $criteria = new CDbCriteria();
            if ($this->ctv_type != '') {
                if ($this->ctv_type == AReportATForm::AGENCY) {
                    $criteria->select = " (SELECT SUM(t1.amount) FROM tbl_commission_statistic_month t1 INNER JOIN vsb_affiliate.tbl_users u1
                                      ON u1.user_id = t1.publisher_id
                                      WHERE t1.month = '$this->month' and u1.agency_id =u.agency_id) as total_amount,t.status, t.month,t.update_time, 
                                    t.vnp_province_id, u.agency_id as publisher_id, t.update_by, t.transaction_id";
                } else {
                    $criteria->select = "(SELECT SUM(t1.amount) FROM tbl_commission_statistic_month t1 
                                      WHERE t1.month = '$this->month' and t1.publisher_id =t.publisher_id) as total_amount,t.status, t.month,t.update_time, 
                                    t.vnp_province_id, t.publisher_id, t.update_by, t.transaction_id";
                }
            } else {
                $criteria->select = "SUM(t.amount) as total_amount,t.status, t.month,t.update_time, 
                                    t.vnp_province_id, t.publisher_id, t.update_by, t.transaction_id";
            }
            if ($this->month != '') {
                $criteria->condition = "t.month <= '$this->month'";
            }
            if ($this->year != '') {
                $criteria->addCondition("t.year = '$this->year'");
            }
            if ($this->province_code != '') {
                $criteria->addCondition("t.vnp_province_id ='" . $this->province_code . "'");
            }
            if ($this->ctv_type != '') {
                if ($this->ctv_type == AReportATForm::CTV) {
                    if ($this->ctv_id != '') {
                        $criteria->addCondition("u.user_id = '" . $this->ctv_id . "'");
                    } else {
                        $criteria->addCondition("u.is_business = 0 and (u.agency_id ='' or u.agency_id is null)");
                    }
                } else {
                    $criteria->addCondition("u.agency_id !=''");
                }
            } else {
                if ($this->ctv_id != '') {
                    $criteria->addCondition("t.publisher_id ='" . $this->ctv_id . "'");
                }
            }
            $criteria->join = "INNER JOIN vsb_affiliate.tbl_users u ON u.user_id=t.publisher_id";
            if ($this->ctv_type != '') {
                if ($this->ctv_type == AReportATForm::AGENCY) {
                    $criteria->group = "u.agency_id";
                } else {
                    $criteria->group = "t.publisher_id";
                }
            } else {
                $criteria->group = "t.publisher_id";
            }
            $criteria->order = "t.create_date asc";
            $data            = ACtvCommissionStatisticMonth::model()->findAll($criteria);


            return $data;

        }
    }


?>