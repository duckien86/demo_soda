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
        public $channel_code;
        public $utm_campaign;
        public $online_status; // Trạng thái đơn hàng online
        public $paid_status; // Trạng thái thanh toán
        public $status_type; // Loại trạng thái.


        public $sim_type; // Hình thức sim
        public $on_detail; // Check box detail.
        public $package_group;
        public $period;
        public $package_id;
        public $payment_method;
        public $receive_status; // Trạng thái thu tiền.
        public $input_type;
        public $delivery_type; // Phương thức nhận hàng.

        public $price_card; // Mênh giá nạp thẻ
        public $vnp_province_id; // Mã tỉnh vnp
        public $total_card; // Sản lượng thẻ
        public $renueve_card; // Doanh thu thẻ
        public $card_type; // Loại thẻ

        public $msisdn; // Số điên thoại tra cứu
        public $type_msisdn; // Loại tin MO/MT

        public $sim_freedoo; // Phân biệt loại thuê bao freedoo hay thuê bao thường.

        public $source; //Kênh bán

        public $item_sim_type;

        //Hình thức sim
        const PREPAID  = 1;
        const POSTPAID = 2;

        //Nhóm gói cước
        const PREPAID_PACKAGE  = 1;
        const POSTPAID_PACKAGE = 2;
        const DATA             = 3;
        const VAS              = 4;
        const SIMKIT           = 5;
        const DOIQUA           = 6;
        const PACKAGE_ROAMING  = 12;

        // Nhóm gói linh hoạt
        const FLEXIBLE_CALL_INT = 7;
        const FLEXIBLE_CALL_EXT = 8;
        const FLEXIBLE_SMS_INT  = 9;
        const FLEXIBLE_SMS_EXT  = 10;
        const FLEXIBLE_DATA     = 11;
        const DAY_FLEXIBLE      = 1;
        const MONTH_FLEXIBLE    = 30;

        //Trạng thái thu tiền.
        const NOT_RECEIVED = 1;
        const RECEIVED     = 2;

        //Hình thức nhận hàng.
        const HOME          = 1;
        const BRAND_OFFICES = 2;

        const  FREEDOO_TYPE   = 1;
        const  VINAPHONE_TYPE = 2;

        public $date; //  Ngày xem báo cáo.

        public $freedoo_order_id;

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
                array('date', 'required', 'on' => 'subscribers'),
                array('msisdn', 'required', 'on' => 'logMT'),
                array('msisdn', 'required', 'on' => 'subscribers_by_msisdn'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                array('package_group, package_id, period, item_sim_type, vnp_province_id', 'safe'),
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
                'payment_method'   => 'Hình thức thanh toán',
                'msisdn'           => 'Số thuê bao',
                'msisdn_type'      => 'Loại tin',
                'receive_status'   => 'Trạng thái thu tiền',
                'delivery_type'    => 'Hình thức nhận hàng',
                'date'             => 'Chọn ngày',
                'channel_code'     => 'Kênh',
                'utm_campaign'     => 'Chiến dịch',
                'online_status'    => 'Trạng thái',
                'status_type'      => 'Loại trạng thái',
                'paid_status'      => 'Trạng thái',
                'sim_freedoo'      => 'Loại thuê bao',
                'card_type'        => 'Loại dịch vụ',
                'source'           => 'Kênh bán',
                'item_sim_type'    => 'Loại sim',
                'vnp_province_id'  => 'Mã tỉnh VNP',
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

        /*
         * Lấy kênh bán
         */
        public function getSource()
        {
            return array(
                'freedoo'       => "Freedoo",
                'chonsovnp'     => "Chọn số VNP",
                'accesstrade'   => "Accesstrade",
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
        public function getPackageGroup($simkit = TRUE)
        {
            if ($simkit) {
                return array(
                    self::PREPAID_PACKAGE  => 'Trả trước',
                    self::POSTPAID_PACKAGE => 'Trả sau',
                    self::DATA             => 'Data',
                    self::VAS              => 'Vas',
                    self::SIMKIT           => 'Sim Kit',
                    self::DOIQUA           => 'Đổi quà',
                    self::PACKAGE_ROAMING  => 'Data Roaming'
                );
            }

            return array(
                self::PREPAID_PACKAGE  => 'Trả trước',
                self::POSTPAID_PACKAGE => 'Trả sau',
                self::DATA             => 'Data',
                self::VAS              => 'Vas',
                self::DOIQUA           => 'Đổi quà',
                self::PACKAGE_ROAMING  => 'Data Roaming'
            );
        }

        /**
         * Lấy tất cả loại thuê bao
         *
         * @return array
         */
        public function getFreedooType()
        {
            return array(
                self::FREEDOO_TYPE   => 'Freedoo',
                self::VINAPHONE_TYPE => 'Vinaphone',
            );
        }

        /**
         * Lấy loại topup hoặc nạp thẻ
         *
         * @return array
         */
        public function getCardType()
        {
            return array(
                'topup' => 'Topup',
                'card'  => 'Nạp thẻ',
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
                '10000'  => '10.000',
                '20000'  => '20.000',
                '30000'  => '30.000',
                '50000'  => '50.000',
                '100000' => '100.000',
                '200000' => '200.000',
                '300000' => '300.000',
                '500000' => '500.000',
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

        /**
         * @param bool $cod
         * Lấy hình thức thanh toán
         *
         * @return array
         */
        public function getAllPaymentMethod($cod = TRUE)
        {
            if (!$cod) {
                return array(
                    1 => 'VIETINBANK QR CODE',
                    2 => 'NAPAS NỘI ĐỊA',
                    3 => 'NAPAS QUỐC TẾ',
                    6 => 'VIETINBANK QUỐC TẾ',
//                    7 => 'VNPAY',
//                    8 => 'OLPAY',
                    9 => 'VIETINBANK NỘI ĐỊA',
                    10 => 'VNPT PAY'
                );
            }

            return array(
                1 => 'VIETINBANK QR CODE',
                2 => 'NAPAS NỘI ĐỊA',
                3 => 'NAPAS QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK QUỐC TẾ',
//                7 => 'VNPAY',
//                8 => 'OLPAY',
                9 => 'VIETINBANK NỘI ĐỊA',
                10 => 'VNPT PAY'
            );

        }

        public static function getPaymentMethod($payment_method)
        {
            $data = array(
                1 => 'VIETINBANK QR CODE',
                2 => 'NAPAS NỘI ĐỊA',
                3 => 'NAPAS QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK QUỐC TẾ',
//                7 => 'VNPAY',
//                8 => 'OLPAY',
                9 => 'VIETINBANK NỘI ĐỊA'
            );

            return isset($data[$payment_method]) ? $data[$payment_method] : '';
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

        /**
         * Lấy trạng thái thu tiền.
         */
        public function getReceiveStatus()
        {
            return array(
                self::NOT_RECEIVED => 'Chưa thu',
                self::RECEIVED     => 'Đã thu',
            );
        }

        /**
         * Lấy trạng thái thu tiền.
         */
        public static function getNameReceiveStatus($status)
        {
            $data = array(
                self::NOT_RECEIVED => 'Chưa thu',
                self::RECEIVED     => 'Đã thu',
            );

            return isset($data[$status]) ? $data[$status] : '';
        }

        /**
         * Lấy phương thức nhận hàng.
         */
        public function getDeliveryType()
        {
            return array(
                self::HOME          => 'Tại nhà',
                self::BRAND_OFFICES => 'Tại điêm giao dịch',
            );
        }

        public static function getStatusActive($status)
        {
            $data = array(
                0 => 'TB khoá 2C',
                1 => 'TB đang hoạt động',
                2 => 'TB hủy',
                3 => 'TB khóa 1C (IC)',
                4 => 'TB khóa 1C (OC)',
            );

            return isset($data[$status]) ? $data[$status] : '';
        }

        public function getAllChannel()
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "DISTINCT utm_source";
            $criteria->condition = "status =1";
            $campaign_config     = ACampaignConfigs::model()->findAll($criteria);

            return CHtml::listData($campaign_config, 'utm_source', 'utm_source');

        }

        public function getAllCampaign()
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "DISTINCT utm_campaign";
            $criteria->condition = "status =1";
            $campaign_config     = ACampaignConfigs::model()->findAll($criteria);

            return CHtml::listData($campaign_config, 'utm_campaign', 'utm_campaign');

        }

        public function getOnlineStatus()
        {
            return array(
                10 => 'Hoàn thành',
                9  => 'Đã thanh toán',
                1  => 'Hủy',
                3  => 'Gửi trả',
            );
        }
        public function getStatusStatisticSim()
        {
            return array(
                10 => 'Hoàn thành',
                2  => 'Hủy',
                3  => 'Gửi trả',
            );
        }
        public function getStatusStatisticPackage()
        {
            return array(
                10 => 'Hoàn thành',
                2  => 'Hủy',
            );
        }
        public function getPaidStatus()
        {
            return array(
                0  => 'Chưa thanh toán',
                10 => 'Đã thanh toán',
            );
        }

        public static function getNameOnlineStatus($status)
        {
            $data = array(
                10 => 'Hoàn thành',
                9  => 'Đã thanh toán',
                1  => 'Hủy',
                3  => 'Gửi trả',
            );

            return isset($data[$status]) ? $data[$status] : '';
        }

    }
