<?php

    /**
     * This is the model class for table "{{shipper}}".
     *
     * The followings are the available columns in table '{{shipper}}':
     *
     * @property string $id
     * @property string $username
     * @property string $password
     * @property string $full_name
     * @property string $avatar
     * @property string $phone_1
     * @property string $phone_2
     * @property string $address_detail
     * @property string $district_code
     * @property string $province_code
     * @property string $status
     * @property string $created_date
     * @property string $created_by
     */
    class CskhShipper extends Shipper
    {

        public $renueve;
        public $total_order;

        public $start_date;
        public $end_date;

        public $status_traffic;
        public $assign_by;

        public $renueve_order;
        public $renueve_shipper;
        public $rose_shipper;
        public $sale_offices_code;
        public $brand_offices_id;

        const ACTIVE        = 1;
        const INACTIVE      = 0;
        const ACTIVE_TEXT   = 'Active';
        const INACTIVE_TEXT = 'Banned';

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('start_date, end_date', 'required', 'on' => 'admin_renueve'),
                array('province_code, sale_offices_code', 'required', 'on' => 'create, update'),
                array('status, username, full_name, address_detail,email, personal_id, phone_1, phone_2', 'required', 'on' => 'create,update'),
                array('gender', 'numerical', 'integerOnly' => TRUE),
                array('username, password, full_name, avatar, phone_1, phone_2, address_detail, district_code, province_code, ward_code, email, otp, personal_id_create_place, status', 'length', 'max' => 255),
                array('personal_id', 'length', 'max' => 100),
                array('created_by', 'length', 'max' => 20),
                array('username', 'unique', 'message' => 'Tên đăng nhập đã được sử dụng!'),
                array('username', 'length', 'min' => 5, 'max' => 25, 'tooShort' => '{attribute} không được ngắn hơn 5 ký tự', 'tooLong' => '{attribute} không được dài quá 25 ký tự'),
                array('username', 'match', 'pattern' => '/^([a-z0-9_\.-])+$/', 'message' => "Chỉ gồm các ký tự a->z, 0->9, _ ,- và dấu chấm"),
                array('phone_1', 'authenticateMsisdn'),
//                array('phone_1', 'unique', 'className' => 'CskhShipper', 'attributeName' => 'phone_1', 'message' => 'Số điện thoại này đã được đăng ký!'),

                array('phone_2', 'authenticateMsisdn'),
//                array('phone_2', 'unique', 'className' => 'CskhShipper', 'attributeName' => 'phone_2', 'message' => 'Số điện thoại này đã được đăng ký!'),

                array('personal_id', 'authenticatePersonalId'),

                array('personal_id', 'unique', 'message' => 'Số chứng minh thu đã được sử dụng!', 'on' => 'create'),

                array('email', 'email'),
                array('email', 'unique', 'message' => 'Email đã được sử dụng!', 'on' => 'create'),
                array('birthday, personal_id_create_date, created_date, sale_offices_code', 'safe'),

                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, full_name, avatar, created_date, created_by, address_detail, district_code, province_code, ward_code, brand_office_id, otp, gender, birthday, personal_id, personal_id_create_date, personal_id_create_place, status', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'                       => 'Mã shipper',
                'username'                 => 'Tên đăng nhập',
                'password'                 => 'Mật khẩu',
                'full_name'                => 'Họ tên',
                'avatar'                   => 'Avatar',
                'phone_1'                  => 'SĐT 1',
                'phone_2'                  => 'SĐT 2',
                'address_detail'           => 'Địa chỉ',
                'district_code'            => 'Quận/huyện',
                'province_code'            => 'Trung tâm kinh doanh',
                'ward_code'                => 'Phường xã',
                'brand_office_id'          => 'Điểm giao dịch',
                'email'                    => 'Email',
                'otp'                      => 'Otp',
                'gender'                   => 'Giới tính',
                'birthday'                 => 'Ngày sinh',
                'personal_id'              => 'CMND',
                'personal_id_create_date'  => 'Ngày cấp',
                'personal_id_create_place' => 'Nơi cấp',
                'status'                   => 'Trạng thái',
                'start_date'               => 'Ngày bắt đầu',
                'end_date'                 => 'Ngày kết thúc',
                'total_order'              => 'Số đơn hàng',
                'renueve'                  => 'Số tiền shipper nhận',
                'total_renueve'            => 'Số tiền trả cho ship',
                'created_date'             => 'Ngày tham gia',
                'created_by'               => 'Người tạo',
                'renueve_order'            => 'Doanh thu ĐH',
                'renueve_shipper'          => 'Doanh thu shipper',
                'rose_shipper'             => 'Hoa hồng shipper',
                'status_traffic'           => 'Trạng thái giao vận',
                'sale_offices_code'        => 'Phòng bán hàng',
                'brand_offices_id'         => 'Điểm giao dịch',
                'assign_by'                => 'Người phân công',
            );
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function authenticatePersonalId($attribute, $params)
        {
            $pattern = '/^([0-9]{9}|[0-9]{12})$/';
            if ($this->$attribute && !preg_match($pattern, $this->$attribute)) {
                $this->addError($attribute, "Số CMT chỉ gồm 9 hoặc 12 chữ số");
            }
        }

        public function authenticateMsisdn($attribute, $params)
        {
            if ($this->$attribute) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);

                if (preg_match("/^84[0-9]{9,11}$/i", $this->$attribute) === 0) {
                    $this->addError($attribute,
                        'Số điện thoại không đúng định dạng!');
                }
            }
        }


        public static function makePhoneNumberStandard($phoneNumber)
        {
            $phoneNumberStandard = '';
            if ($phoneNumber != '') {
                if (substr($phoneNumber, 0, 1) == '0') {
                    $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
                } else if (substr($phoneNumber, 0, 2) == '84') {
                    $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
                }
                $phoneNumberStandard = '84' . $phoneNumberStandard;
            }

            return $phoneNumberStandard;
        }

        /**
         * Retrieves a list of models based on the current search/filter conditions.
         *
         * Typical usecase:
         * - Initialize the model fields with values from filter form.
         * - Execute this method to get CActiveDataProvider instance which will filter
         * models according to data in model fields.
         * - Pass data provider to CGridView, CListView or any similar widget.
         *
         * @return CActiveDataProvider the data provider that can return the models
         * based on the search/filter conditions.
         */
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

//            $criteria->select = "t.*, sum(od.price * od.quantity) as renueve_order, count(distinct od.order_id) as total_order";
            $criteria->select = "t.*";
            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.username', $this->username, TRUE);
            $criteria->compare('t.district_code', $this->district_code, TRUE);
            $criteria->compare('t.province_code', $this->province_code, TRUE);
            $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            $criteria->compare('t.brand_office_id', $this->brand_office_id, TRUE);
            $criteria->compare('t.sale_offices_code', $this->sale_offices_code, TRUE);
            if (!SUPER_ADMIN && !ADMIN) {
                if (Yii::app()->user->province_code && !(Yii::app()->user->sale_offices_id)) {
                    $criteria->compare('province_code', Yii::app()->user->province_code);
                } else {
                    if (Yii::app()->cache->get(Yii::app()->user->id . "_ward_code") != '' && Yii::app()->cache->get(Yii::app()->user->id . "_brand_offices_id")) {
                        $criteria->addCondition("sale_offices_code='" . Yii::app()->user->sale_offices_id . "'");
                    }
                }
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.created_date DESC',
                ),
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));
        }


        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Shipper the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }


        /**
         * Lấy tất cả tỉnh theo quyền.
         */
        public function getAllProvince()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Province::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else {
                if (isset(Yii::app()->user->id)) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->province_code != '') {
                            $criteria            = new CDbCriteria();
                            $criteria->condition = "code = '" . $user->province_code . "'";

                            $data = Province::model()->findAll($criteria);

                            return CHtml::listData($data, 'code', 'name');
                        }
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllDistrict()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = District::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->district_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "code = '" . $user->district_code . "'";

                        $data = District::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }

            }

            return $return;
        }

        /**
         * Lấy tất cả phường xã
         */
        public function getAllWard()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Ward::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->ward_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "code = '" . $user->ward_code . "'";

                        $data = Ward::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả phường xã
         */
        public function getAllBrandOffice()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = BrandOffices::model()->findAll();

                return CHtml::listData($data, 'id', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->ward_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "code = '" . $user->ward_code . "'";

                        $data = BrandOffices::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy quận huyện theo tỉnh
         *
         * @param $code
         */
        public function getDistrict($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "province_code = '" . $code . "'";

            $data = District::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy quận huyện theo tỉnh
         *
         * @param $code
         */
        public function getWard($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "district_code = '" . $code . "'";

            $data = AWard::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy quận huyện theo tỉnh
         *
         * @param $code
         */
        public function getBrandOffice($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "ward_code = '" . $code . "'";

            $data = ABrandOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Purify trước khi lưu
         *
         * @return bool
        //         */
        public function beforeSave()
        {
            $p                = new CHtmlPurifier();
            $this->attributes = $p->purify($this->attributes);
            if (Yii::app()->user->id) {
                $this->created_by   = Yii::app()->user->id;
                $this->created_date = date('Y-m-d');
            }
            $this->birthday = date("Y-m-d", strtotime(str_replace('/', '-', $this->birthday)));

            $this->personal_id_create_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->personal_id_create_date)));


            return parent::beforeSave();

        }


        /**
         * Lấy trạng thái giao hàng.
         */
        public function getAllStatusTraffic()
        {

            $data = array(
                ''                 => 'Tất cả',
                ATraffic::NOT_SHIP => "Chưa giao",
                ATraffic::SHIPPED  => "Đã giao",
                ATraffic::RECEIVED => "Đã thu",
            );

            return $data;
        }

        /**
         * @param $id
         * Lấy tổng số đơn hàng theo shipper_id.
         *
         * @return string
         */
        public function getTotalOrder($id, $start_date = '', $end_date = '')
        {
            $criteria         = new CDbCriteria();
            $criteria->select = "count(DISTINCT t.order_id) as total_order";
            if ($start_date != '' && $end_date != '') {
                $criteria->condition = "t.shipper_id='" . $id . "' and t.assign_date >='$start_date' and t.assign_date <='$end_date'";
            } else {
                $criteria->condition = "t.shipper_id='" . $id . "'";
            }
            $criteria->join = "INNER JOIN {{order_details}} od ON od.order_id = t.order_id";
            $model          = CskhShipperOrder::model()->count($criteria);

            return number_format($model * 35000, 0, '', '.') . "đ";
        }

        /**
         * Lấy tổng đơn hàng theo điều kiện admin.
         */
        public function getTotalOrderInfoByInput()
        {
            $data     = array();
            $criteria = new CDbCriteria();

            $criteria->select = "count(distinct t.order_id) as total_order, sum(od.price) as total_renueve_order";
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t.assign_date >= '$this->start_date' and t . assign_date <= '$this->end_date'";
                if ($this->status_traffic != '') {
                    $criteria->addCondition("t.status = '" . $this->status_traffic . "'");
                }
                if ($this->province_code != '') {

                    $criteria->addCondition("o.province_code = '" . $this->province_code . "'");
                }
                if ($this->sale_offices_code != '') {
                    $sale = SaleOffices::model()->findByAttributes(array('code' => $this->sale_offices_code));
                    $criteria->addCondition("o.district_code = '" . $sale->district_code . "'");
                }
                if ($this->id) {
                    $criteria->addCondition("t.shipper_id ='" . $this->id . "'");
                }
            }
            $criteria->join = "INNER JOIN {{orders}} o ON o.id = t.order_id
                               INNER JOIN {{shipper}} s ON s.id = t.shipper_id
                               INNER JOIN {{order_details}} od ON od.order_id = t.order_id";
            $data           = CskhShipperOrder::model()->findAll($criteria)[0];

//            CVarDumper::dump($criteria,10,true);die();
            return $data;
        }

        /**
         * Lấy tổng shipper theo điều kiện admin.
         */
        public function getTotalShipperByInput()
        {
            $criteria         = new CDbCriteria();
            $criteria->select = 'COUNT(distinct t.shipper_id) as total';
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "t . assign_date >= '$this->start_date' and t . assign_date <= '$this->end_date'";
                if ($this->province_code != '') {
                    $criteria->addCondition("s.province_code = '" . $this->province_code . "'");
                }
                if ($this->district_code != '') {
                    $criteria->addCondition("s.district_code = '" . $this->district_code . "'");
                }
                if ($this->brand_office_id != '') {
                    $criteria->addCondition("s.brand_office_id = '" . $this->brand_office_id . "'");
                }
            }
            $criteria->join = "INNER JOIN tbl_shipper s ON s.id = t.shipper_id";
            $data           = CskhShipperOrder::model()->count($criteria);

            return $data;
        }

        public function getShipperBySales($shipper_code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "sale_offices_code='" . $shipper_code . "'";
            $data                = CskhShipper::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'username');
        }

        public function getAllShipper()
        {
            $criteria = new CDbCriteria();

            $data = CskhShipper::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'username');
        }


        /**
         * Gửi thông báo tạo shipper bằng sms.
         *
         * @param $msisdn
         */
        public function sendSMS($username, $password)
        {
            $shipper = CskhShipper::model()->findByAttributes(array('username' => $username));
            $msisdn  = $shipper->phone_2;
            // Send MT.
            //Lưu log gọi api.
            $mt_content = Yii::t('adm/mt_content', 'message_create_user', array(
                'username' => $shipper->username,
                'password' => $password,
            ));
            $logFolder  = "send_sms_shipper";
            if (self::sentMtVNP($msisdn, $mt_content, $logFolder)) {
                return TRUE;
            }

        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $file_name
         *
         * @return bool
         */
        public static function sentMtVNP($msisdn, $msgBody, $file_name)
        {
            $logMsg   = array();
            $logMsg[] = array('Start Send MT ' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code);
            if ($flag) {
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            }
            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "shipper/" . date("Y/m/d");
            $logObj    = CskhTraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }
    }
