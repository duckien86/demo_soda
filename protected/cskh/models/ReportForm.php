<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class ReportForm extends CFormModel
    {
        public $start_date;
        public $end_date;
        public $ctv_id; // Mã CTV

        public $province_code; // Trung tâm kinh doanh
        public $district_code;
        public $ward_code;
        public $sale_office_code;
        public $brand_offices_id;

        public $sim_type; // Hình thức sim
        public $on_detail; // Check box detail.
        public $package_group;
        public $period;
        public $package_id;
        public $payment_method;

        public $input_type;
        public $price_card;

        public $msisdn; // Số điên thoại tra cứu
        public $type_msisdn; // Loại tin MO/MT

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

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('start_date, end_date', 'required'),
                array('msisdn', 'required','on'=>'logMT'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                array('package_group, package_id, period', 'safe'),
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
                'start_date'       => 'Ngày bắt đầu',
                'end_date'         => 'Ngày kết thúc',
                'ctv_id'           => 'Mã cộng tác viên',
                'sim_type'         => 'Hình thức sim',
                'on_detail'        => 'Hiển thị chi tiết',
                'province_code'    => 'Chọn TTKD',
                'package_group'    => 'Nhóm gói',
                'period'           => 'Chu kỳ',
                'package_id'       => 'Gói cước',
                'brand_offices_id' => 'Điểm giao dịch',
                'price_card'       => 'Mệnh giá',
                'ctv'              => 'Mã cộng tác viên',
                'district_code'    => 'Quận huyện',
                'ward_code'        => 'Phường xã',
                'input_type'       => 'PT nhận hàng',
                'sale_office_code' => 'Phòng bán hàng',
                'payment_method'   => 'Phương thức thanh toán',
                'msisdn'           => 'Số thuê bao',
                'msisdn_type'      => 'Loại tin',
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
            $criteria            = new CDbCriteria();
            $criteria->condition = "id=" . $id;

            $data = Package::model()->findAll($criteria);

            return ($data[0]) ? $data[0]->name : '';
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
         * Lấy hình thức thanh toán của gói cước linh hoạt
         *
         * @return array
         */
        public function getPackageGroupFlexible()
        {
            return array(
                self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
                self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
                self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
                self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
                self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
            );
        }

        /**
         * Lấy toàn bộ danh sách gói cước linh hoạt
         */
        public function getAllPackageFlexible()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "type IN('" . self::FLEXIBLE_CALL_INT . "',
                                    '" . self::FLEXIBLE_CALL_EXT . "',
                                    '" . self::FLEXIBLE_SMS_INT . "',
                                    '" . self::FLEXIBLE_SMS_EXT . "',
                                    '" . self::FLEXIBLE_DATA . "')";
            $data                = RPackage::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        public function getPackageByGroupFlexible($package_group = '', $period = '')
        {

            $criteria            = new CDbCriteria();
            $criteria->condition = "1=1";
            if ($package_group != '') {
                $criteria->addCondition("type='" . $package_group . "'");
            }
            if ($period != '') {
                $criteria->addCondition("period='" . $period . "'");
            }
            $data = RPackage::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');


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


        public function getPriceCard()
        {
            return array(
                '5000'  => '5000',
                '1000'  => '1000',
                '2000'  => '2000',
                '10000' => '10000',
            );
        }

        /**
         * Lấy thể loại sim
         */
        public function getTypeSimByType($type)
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
        public static function getTypeSimExcel($type)
        {
            $type_sim = array(
                1 => 'Trả trước',
                2 => 'Trả sau',
            );

            return $type_sim[isset($type) ? $type : 1];
        }

        public function getPackageByGroupPeriod($period)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "1=1";
            if ($period != '') {
                $criteria->addCondition("period='" . $period . "' AND type IN('" . self::FLEXIBLE_CALL_INT . "',
                                    '" . self::FLEXIBLE_CALL_EXT . "',
                                    '" . self::FLEXIBLE_SMS_INT . "',
                                    '" . self::FLEXIBLE_SMS_EXT . "',
                                    '" . self::FLEXIBLE_DATA . "')");
            }
            $data = RPackage::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');

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

        public function getCardWorth()
        {
            return array(
                10  => '10.000',
                20  => '20.000',
                30  => '30.000',
                50  => '50.000',
                100 => '100.00',
                200 => '200.00',
                300 => '300.00',
                500 => '500.00',
            );
        }
    }
