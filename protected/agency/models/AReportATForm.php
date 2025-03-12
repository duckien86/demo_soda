<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class AReportATForm extends CFormModel
    {
        public $start_date;
        public $end_date;
        public $status;
        public $ctv_id;

        public $province_code; // Trung tâm kinh doanh

        public $sim_type; // Hình thức sim
        public $package_group; //Nhóm gói
        public $package_id;

        public $month;
        public $year;
        public $channel_code; // Kênh bán hàng
        public $ctv_type; // Loại ctv

        const PREPAID  = 1;
        const POSTPAID = 2;

        const PREPAID_PACKAGE  = 1;
        const POSTPAID_PACKAGE = 2;
        const DATA             = 3;
        const VAS              = 4;
        const SIMKIT           = 5;
        const DOIQUA           = 6;

        const FLEXIBLE_CALL_INT = 7;
        const FLEXIBLE_CALL_EXT = 8;
        const FLEXIBLE_SMS_INT  = 9;
        const FLEXIBLE_SMS_EXT  = 10;
        const FLEXIBLE_DATA     = 11;

        const DAY_FLEXIBLE   = 1;
        const MONTH_FLEXIBLE = 30;

        //Loại hình ctv.
        const CTV    = 1; // CTV thường
        const AGENCY = 2; // Con của DLTC




        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('start_date, end_date', 'required', 'except' => 'paid_affiliate'),
                array('month, year', 'required', 'on' => 'paid_affiliate'),
                array('msisdn', 'required', 'on' => 'logMT'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                array('package_group, package_id, period, status', 'safe'),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'start_date'    => 'Ngày bắt đầu',
                'end_date'      => 'Ngày kết thúc',
                'sim_type'      => 'Hình thức thuê bao',
                'province_code' => 'Chọn TTKD',
                'package_group' => 'Nhóm gói',
                'package_id'    => 'Tên gói',
                'channel_code'  => 'Kênh bán hàng',
                'ctv_type'      => 'Loại hình CTV',
                'status'        => 'Trạng thái',
                'ctv_id'        => 'CTV',
                'year'          => 'Năm',
                'month'         => 'Tháng',
            );
        }

        /**
         * Authenticates the password.
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticate($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $this->_identity = new UserIdentity($this->username, $this->password);
                if (!$this->_identity->authenticate())
                    $this->addError('password', 'Incorrect username or password.');
            }
        }

        /**
         * Lấy hình thức sim
         */
        public function getTypeSim()
        {
            return array(
                self::PREPAID  => "Trả trước",
                self::POSTPAID => "Trả sau",
            );
        }

        /**
         * Lấy trung tâm kinh doanh.
         */
        public function getProvice()
        {
            $data = Province::model()->findAll();

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy danh sách CTV.
         */
        public function getUserCTV()
        {
            $data = Yii::app()->db_affiliates->createCommand("select * from tbl_users")
                ->queryAll();

            return CHtml::listData($data, 'user_name', 'user_name');
        }

        /**
         * Lấy danh sách CTV theo provice.
         */
        public function getUserCTVProvice($provice_code)
        {
            $data = Yii::app()->db_affiliates->createCommand("select * from tbl_users where province=:provice")
                ->bindParam(':provice', $provice_code)
                ->queryAll();

            return CHtml::listData($data, 'user_name', 'user_name');
        }

        /**
         * Lấy danh sách gói cước.
         */
        public function getAllPackage()
        {
            $data = Package::model()->findAll();

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy danh sách gói cước.
         */
        public function getPackageById($id)
        {
            if ($id) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "id=" . $id;

                $data = Package::model()->findAll($criteria);
            }

            return isset($data[0]) ? $data[0]->name : '';
        }

        /**
         * Lấy danh sách gói cước.
         */
        public static function getPackageByCode($code)
        {
            if ($code) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "code='" . $code . "'";

                $data = Package::model()->findAll($criteria);
            }

            return isset($data[0]) ? $data[0]->name : '';
        }

        /**
         * Lấy danh sách gói cước.
         */
        public function getPackageByGroup($type)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "type=" . $type;

            $data = Package::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy hình thức thanh toán của gói cước
         *
         * @return array
         */
        public function getPackageGroup()
        {
            return array(
                self::PREPAID_PACKAGE  => 'Trả trước',
                self::POSTPAID_PACKAGE => 'Trả sau',
                self::DATA             => 'Data',
                self::VAS              => 'Vas',
                self::SIMKIT           => 'Sim Kit',
                self::DOIQUA           => 'Đổi quà'
            );
        }

        /**
         * Lấy hình thức thanh toán của gói cước
         *
         * @return array
         */
        public static function getPackageGroupByType($type = 0)
        {
            $data = array(
                self::PREPAID_PACKAGE  => 'Trả trước',
                self::POSTPAID_PACKAGE => 'Trả sau',
                self::DATA             => 'Data',
                self::VAS              => 'Vas',
                self::SIMKIT           => 'Sim Kit',
                self::DOIQUA           => 'Đổi quà'
            );

            return isset($data[$type]) ? $data[$type] : $type;
        }

        /**
         * Lấy toàn bộ danh sách gói cước.
         *
         * @return array
         */
        public function getAllPeriod()
        {
            $data = Package::model()->findAll();

            return CHtml::listData($data, 'period', 'period');
        }

        /**
         * Lấy danh sách gói cước theo chu kỳ.
         *
         * @return array
         */
        public function getPackageByPeriod($period)
        {
            $criteria = new CDbCriteria();

            $criteria->condition = "period= " . $period;

            $data = Package::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }


        /**
         * Lấy thể loại sim
         */
        public static function getTypeSimByType($type)
        {
            $type_sim = array(
                1 => 'Trả trước',
                2 => 'Trả sau',
            );

            return $type_sim[isset($type) ? $type : 1];
        }

        /**
         * Lấy thể loại sim
         */
        public static function getStatusOrder($type)
        {
            $order_status = array(
                0 => 'Hủy',
                1 => 'Hủy',
                2 => 'Hủy',
                3 => 'Hoàn thành',
            );

            return $order_status[isset($type) ? $type : 1];
        }

        /**
         * Lấy thể loại sim
         */
        public static function getStatusPaid($type)
        {
            $paid_status = array(
                0 => 'Chưa thanh toán',
                10 => 'Đã thanh toán',
            );

            return $paid_status[isset($type) ? $type : 1];
        }

        /**
         * Lấy thể loại sim
         */
        public static function getStatusOrderAT($type)
        {
            $order_status = array(
                0 => 'Hủy',
                1 => 'Hoàn thành',

            );

            return $order_status[isset($type) ? $type : 1];
        }

        public static function getAllStatusAT()
        {
            $order_status = array(
                0 => 'Hủy',
                1 => 'Hoàn thành',

            );

            return $order_status;
        }

        /**
         * Lấy thể loại sim
         */
        public static function getTypeSimExcel($type)
        {
            $type_sim = array(
                1 => 'Trả trước',
                2 => 'Trả sau',
            );

            return $type_sim[isset($type) ? $type : 1];
        }

        public function getAllPaymentMethod()
        {
            return array(
                1 => 'QR CODE',
                2 => 'THẺ ATM NỘI ĐỊA',
                3 => 'THẺ ATM QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK',
            );

        }

        /**
         * Lấy danh sách kênh bán hàng.
         *
         * @return array
         */
        public function getChannelCode()
        {
            return array(
                1 => 'ACCESSTRADE',
                7 => 'ADFLEX',
            );
        }

        /**
         * Lấy danh sách kênh bán hàng.
         *
         * @return array
         */
        public static function getChannelByCode($type)
        {
            $data = array(
                1 => 'ACCESSTRADE',
                5 => 'AFFILIATE',
                6 => 'AFFILIATE',
                7 => 'ADFLEX',
            );

            return isset($data[$type]) ? $data[$type] : '';
        }

        public static function getTypeCTV()
        {
            return array(
                self::CTV    => 'CTV',
                self::AGENCY => 'ĐLTC',
            );
        }

        public static function getTypeCTVByType($type)
        {
            $data = array(
                self::CTV    => 'CTV',
                self::AGENCY => 'ĐLTC',
            );

            return isset($data[$type]) ? $data[$type] : '';
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

        /**
         * @param $order_id
         * Lấy thông tin cộng tác viên theo mã đơn hàng kết nối affiliate
         */
        public static function getCTV($affiliate_transaction_id)
        {

            $actions = Yii::app()->db_affiliates->createCommand()
                ->select('u.user_name,u.owner_code ,u.inviter_code')->from('tbl_clicks t')
                ->join('tbl_users u', 't.publisher_id=u.user_id')
                ->where('t.click_id =:click_id',
                    array(':click_id' => $affiliate_transaction_id))->queryAll();

            return $actions;
        }

        public static function getCTVByOrder($order_code)
        {

            $actions = ACtvActions::model()->findByAttributes(array('order_code' => $order_code));
            if ($actions) {
                if (isset($actions->publisher_id)) {
                    $users = ACtvUsers::model()->findByAttributes(array('user_id' => $actions->publisher_id));
                    if ($users) {
                        return $users;
                    }
                }
            }

            return $actions;
        }

        public static function getInviterCTVByOrder($order_code)
        {
            $actions = ACtvActions::model()->findByAttributes(array('order_code' => $order_code));

            if ($actions) {
                if (isset($actions->publisher_id)) {
                    $users = ACtvUsers::model()->findByAttributes(array('user_id' => $actions->publisher_id));
                    if ($users) {
                        if ($users->agency_id != '') {
                            $agency = ACtvUsers::model()->findByAttributes(array('user_id' => $users->agency_id));

                            return $agency->owner_code;
                        }

                        return $users->owner_code;
                    }
                }
            }

            return "";
        }


        /**
         * @param $order_id
         * Lấy thông tin cộng tác viên theo mã đơn hàng kết nối affiliate
         */
        public static function getPublisherAward($order_id, $status)
        {
            if ($status == 0) {
                $status = 2;
            } else {
                $status = 3;
            }
            $actions = Yii::app()->db_affiliates->createCommand()
                ->select('pa.amout')->from('tbl_actions a')
                ->join('tbl_publisher_award pa', 'pa.transaction_id=a.transaction_id')
                ->where('a.order_code =:order_code and a.action_status=:action_status',
                    array(':order_code' => $order_id, ':action_status' => $status))->queryAll();

            return $actions;
        }

        public function getAllStatus()
        {
            return array(
                2 => 'Hủy',
                3 => 'Hoàn thành',
            );
        }

        public function getAllCtv()
        {
            $criteria            = new CDbCriteria();
            $criteria->select = "t.user_id, t.user_name";
//            $criteria->condition = "status=1";
            $users               = ACtvUsers::model()->findAll($criteria);

            return CHtml::listData($users, 'user_id', 'user_name');
        }

        public function getMonthArray()
        {
            return array(
                1  => '01',
                2  => '02',
                3  => '03',
                4  => '04',
                5  => '05',
                6  => '06',
                7  => '07',
                8  => '08',
                9  => '09',
                10 => '10',
                11 => '11',
                12 => '12',
            );
        }

        public function getYearArray()
        {
            return array(
                2017 => '2017',
                2018 => '2018',
                2019 => '2019',
                2020 => '2020',
                2021 => '2021',
                2022 => '2022',
            );
        }

    }
