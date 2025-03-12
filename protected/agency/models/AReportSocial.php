<?php

    class AReportSocial
    {
        public $start_date;
        public $end_date;
        public $customer_id;
        public $status;

        const  LIKE      = 'like';
        const  COMMENT   = 'comment';
        const  POST      = 'post';
        const  REDEEM    = 'redeem';
        const  SUB_POINT = 'sub_point';

        /**
         * Lấy thông tin tổng bài post của một tài khoản.
         */
        public function getCustomerPost()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria         = new CDbCriteria();
            $criteria->select = "t.sso_id, t.username, t.status,t.level, t.create_time, count(sp.id) as total_post";
            if ($this->start_date != '' && $this->end_date != '') {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'
                                    and sp.create_date >='$this->start_date' and sp.create_date <='$this->end_date'
                                    and t.status =10";
                } else {
                    $criteria->condition = "sp.create_date >='$this->start_date' and sp.create_date <='$this->end_date' and t.status =10";
                }
            } else {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'";
                }
            }

            $criteria->join = "INNER JOIN sc_tbl_posts sp ON t.sso_id = sp.sso_id";

            if (!$this->customer_id) {
                $criteria->group = "t.sso_id";
            }
            $data = ACustomers::model()->findAll($criteria);

            return $data;
        }

        /**
         * Lấy thông tin tổng số comment của một tài khoản.
         */
        public function getCustomerComment()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria         = new CDbCriteria();
            $criteria->select = "t.sso_id, t.username, t.status,t.level, t.create_time, count(sc.id) as total_comment";
            if ($this->start_date != '' && $this->end_date != '') {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id' 
                                    and sc.create_date >='$this->start_date' and sc.create_date <='$this->end_date'
                                    and t.status =10";
                } else {
                    $criteria->condition = "sc.create_date >='$this->start_date' and sc.create_date <='$this->end_date' and t.status =10";
                }
            } else {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'";
                }
            }

            $criteria->join = "INNER JOIN sc_tbl_comments sc ON t.sso_id = sc.sso_id";
            if (!$this->customer_id) {
                $criteria->group = "t.sso_id";
            }
            $data = ACustomers::model()->findAll($criteria);

            return $data;
        }

        /**
         * Lấy thông tin tổng số like của một tài khoản.
         */
        public function getCustomerLikes()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria         = new CDbCriteria();
            $criteria->select = "t.sso_id, t.username, t.status,t.level, t.create_time, count(sl.id) as total_like";
            if ($this->start_date != '' && $this->end_date != '') {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'
                                    and sl.create_date >='$this->start_date' and sl.create_date <='$this->end_date 
                                    and t.status =10'";
                } else {
                    $criteria->condition = "t.sso_id = '$this->customer_id'
                                    and sl.create_date >='$this->start_date' and sl.create_date <='$this->end_date' and t.status =10";
                }
            } else {
                $criteria->condition = "t.sso_id = '$this->customer_id'";
            }

            $criteria->join = "LEFT JOIN sc_tbl_likes sl ON t.sso_id = sl.sso_id";
            if (!$this->customer_id) {
                $criteria->group = "t.sso_id";
            }
            $data = ACustomers::model()->findAll($criteria);

            return $data;
        }

        /**
         * Lấy thông tin tổng số làn vi phạm của một tài khoản.
         */
        public function getCustomerTotalSubPoint()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria         = new CDbCriteria();
            $criteria->select = "t.sso_id, t.username, t.status,t.level, t.create_time, count(sh.id) as total_sub_point,
            t.bonus_point as bonus_point";
            if ($this->start_date != '' && $this->end_date != '') {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'
                                    and sh.create_date >='$this->start_date' and sh.create_date <='$this->end_date'
                                    and sh.amount <=0";
                } else {
                    $criteria->condition = "sh.create_date >='$this->start_date' and sh.create_date <='$this->end_date'
                                    and sh.amount <=0";
                }
            } else {
                $criteria->condition = "t.sso_id = '$this->customer_id' and sh.amount <=0";
            }

            $criteria->join = "INNER JOIN sc_tbl_point_history sh ON t.sso_id = sh.sso_id";
            if (!$this->customer_id) {
                $criteria->group = "t.sso_id";
            }
            $data = ACustomers::model()->findAll($criteria);

            return $data;
        }

        /**
         * Lấy thông tin tổng số làn đổi quà của một tài khoản.
         */
        public function getCustomerTotalRedeem()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria         = new CDbCriteria();
            $criteria->select = "t.sso_id, t.username, t.status,t.level, t.create_time, sum(sh.point_amount) as sum_redeem";
            if ($this->start_date != '' && $this->end_date != '') {
                if ($this->customer_id) {
                    $criteria->condition = "t.sso_id = '$this->customer_id'
                                    and sh.create_date >='$this->start_date' and sh.create_date <='$this->end_date' and t.status =10";
                } else {
                    $criteria->condition = "sh.create_date >='$this->start_date' and sh.create_date <='$this->end_date' and t.status =10";
                }
            } else {
                $criteria->condition = "t.sso_id = '$this->customer_id'";
            }

            $criteria->join = "INNER JOIN sc_tbl_redeem_history sh ON t.sso_id = sh.sso_id";
            if (!$this->customer_id) {
                $criteria->group = "t.sso_id";
            }
            $data = ACustomers::model()->findAll($criteria);

            return $data;
        }

        /**
         * @param     $data
         * @param     $form
         * @param int $type =0 : Mảng dữ liệu || $type=1 dư liệu đơn:
         *
         * @return array
         */
        public function controllDataCustomer($data, $form, $type = 0)
        {
            $customer = ACustomers::model()->findByAttributes(array('sso_id' => $form->customer_id));

            $criteria            = new CDbCriteria();
            $criteria->condition = 'customer_type !=1';
            if ($form->status != '') {
                $criteria->addCondition("status ='" . $form->status . "'");
            }
            $criteria->order = 'username ASC';
            $all_customer    = ACustomers::model()->findAll($criteria);

            $result = array();
            if ($type == 0) {

                $user = array();
                foreach ($all_customer as $key => $value) {
                    if (!in_array($value->username, $user)) {
                        array_push($user, $value->username);
                    }
                }
                foreach ($all_customer as $key_customer => $value_customer) {
                    $result_key                  = array(
                        'sso_id'          => '',
                        'username'        => '',
                        'status'          => '',
                        'total_like'      => 0,
                        'total_comment'   => 0,
                        'total_post'      => 0,
                        'level'           => '',
                        'create_time'     => '',
                        'total_sub_point' => 0,
                        'sum_redeem'      => 0,
                        'bonus_point'     => 0,
                        'current_point'   => 0,
                    );
                    $result_key['username']      = $value_customer->username;
                    $result_key['sso_id']        = $value_customer->sso_id;
                    $result_key['status']        = $value_customer->status;
                    $result_key['current_point'] = AReportSocial::getCurrentPoint($value_customer->sso_id);
                    foreach ($data as $key => $value) {
                        if ($value_customer->username == $value->username) {
                            if ($value->total_like != NULL) {
                                $result_key['total_like'] += $value->total_like;
                            }
                            if ($value->total_comment != NULL) {
                                $result_key['total_comment'] += $value->total_comment;
                            }
                            if ($value->total_post != NULL) {
                                $result_key['total_post'] += $value->total_post;
                            }
                            if ($value->total_sub_point != NULL) {
                                $result_key['total_sub_point'] += $value->total_sub_point;
                            }
                            if ($value->bonus_point != NULL) {
                                $result_key['bonus_point'] += $value->bonus_point;
                            }
                            if ($value->sum_redeem != NULL) {
                                $result_key['sum_redeem'] += $value->sum_redeem;
                            }
                            $result_key['level']       = $value->level;
                            $result_key['create_time'] = $value->create_time;
                        }

                    }
                    $result[] = $result_key;
                }

                return $result;
            }

            if ($type == 1) {
                $result_key = array(
                    'sso_id'          => '',
                    'username'        => '',
                    'status'          => '',
                    'total_like'      => 0,
                    'total_comment'   => 0,
                    'total_post'      => 0,
                    'level'           => '',
                    'create_time'     => '',
                    'total_sub_point' => 0,
                    'sum_redeem'      => 0,
                );
                foreach ($data as $key => $value) {


                    $result_key['username'] = $customer->username;
                    $result_key['sso_id']   = $customer->sso_id;
                    if ($value->status != NULL) {
                        $result_key['status'] = $value->status;
                    }
                    if ($value->total_like != NULL) {
                        $result_key['total_like'] = $value->total_like;
                    }
                    if ($value->total_comment != NULL) {
                        $result_key['total_comment'] = $value->total_comment;
                    }
                    if ($value->total_post != NULL) {
                        $result_key['total_post'] = $value->total_post;
                    }
                    if ($value->total_sub_point != NULL) {
                        $result_key['total_sub_point'] = $value->total_sub_point;
                    }
                    if ($value->sum_redeem != NULL) {
                        $result_key['sum_redeem'] = $value->sum_redeem;
                    }
                    $result_key['level']       = $value->level;
                    $result_key['create_time'] = $value->create_time;
                }
                $result[0] = $result_key;

                return $result;
            }

            return $result;
        }

        /**
         * Lấy dánh sách bài đăng.
         *
         * @return CActiveDataProvider
         */
        public function getListPost()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date'";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "' ";
            }

            return new CActiveDataProvider('APosts', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }

        /**
         * Lấy dánh sách bình luận.
         *
         * @return CActiveDataProvider
         */
        public function getListComment()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date'";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "'";
            }

            return new CActiveDataProvider('AComments', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }

        /**
         * Lấy dánh sách bình luận.
         *
         * @return CActiveDataProvider
         */
        public function getListLikes()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            $criteria = new CDbCriteria();
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date'";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "'";
            }

            return new CActiveDataProvider('ALikes', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }

        /**
         * Lấy dánh sách số lần vi phạm.
         *
         * @return CActiveDataProvider
         */
        public function getListSubPoint()
        {
            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date' and amount < 0";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and amount < 0";
            }

            return new CActiveDataProvider('APointHistory', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }

        /**
         * Lấy dánh sách số lần vi phạm.
         *
         * @return CActiveDataProvider
         */
        public function getListRedeem()
        {
            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date'";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "'";
            }

            return new CActiveDataProvider('ARedeemHistory', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }

        /**
         * Lấy dánh sách số lần vi phạm.
         *
         * @return CActiveDataProvider
         */
        public function getListPoint()
        {
            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->condition = "sso_id='" . $this->customer_id . "' and create_date >= '$this->start_date' and create_date <= '$this->end_date'";
            } else {
                $criteria->condition = "sso_id='" . $this->customer_id . "'";
            }

            return new CActiveDataProvider('APointHistory', array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 5,
                    'params'   => array(
                        'AReportSocialForm[start_date]'  => $this->start_date,
                        'AReportSocialForm[end_date]'    => $this->end_date,
                        'AReportSocialForm[customer_id]' => $this->customer_id,
                    ),
                ),
            ));
        }


        /**
         * Lấy dữ liệu báo cáo tổng quan.
         *
         * @return CActiveDataProvider | ACustomers[]
         */
        public function getListCustomer($excel = FALSE)
        {
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

            $criteria = new CDbCriteria();
            if ($this->customer_id != '') {
                $criteria->condition = "create_time >= '$this->start_date' and create_time <='$this->end_date' and sso_id='$this->customer_id'";
            } else {
                $criteria->condition = "create_time >= '$this->start_date' and create_time <='$this->end_date'";
            }
            if ($excel) {
                $data = ACustomers::model()->findAll($criteria);

                return $data;
            } else {
                return new CActiveDataProvider('ACustomers', array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 'username ASC',
                    ),
                    'pagination' => array(
                        'pageSize' => 20,
                        'params'   => array(
                            'AReportSocialForm[start_date]'  => $this->start_date,
                            'AReportSocialForm[end_date]'    => $this->end_date,
                            'AReportSocialForm[customer_id]' => $this->customer_id,
                        ),
                    ),
                ));
            }
        }

        /**
         * Lấy điểm hiện tại
         */
        public static function getCurrentPoint($sso_id)
        {
            if ($sso_id) {
                $customer = ACustomers::model()->findByAttributes(array('sso_id' => $sso_id));
                if ($customer) {
                    return $customer->bonus_point;
                }
            }

            return 0;
        }

    }

?>