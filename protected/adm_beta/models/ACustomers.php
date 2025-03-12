<?php

    /**
     * This is the model class for table "{{customers}}".
     *
     * The followings are the available columns in table '{{customers}}':
     *
     * @property string  $id
     * @property string  $sso_id
     * @property string  $phone
     * @property string  $username
     * @property string  $email
     * @property string  $birthday
     * @property integer $bonus_point
     * @property string  $create_time
     * @property string  $last_update
     * @property string  $otp
     * @property string  $full_name
     * @property integer $genre
     * @property string  $customer_type
     * @property string  $district_code
     * @property string  $province_code
     * @property string  $address_detail
     * @property string  $personal_id
     * @property string  $personal_id_create_date
     * @property string  $personal_id_create_place
     * @property string  $extra_info
     * @property string  $bank_account_id
     * @property string  $bank_brandname
     * @property string  $bank_name
     * @property string  $bank_account_name
     * @property string  $job
     * @property integer $status
     * @property string  $avatar
     * @property string  $profile_picture
     */
    class ACustomers extends Customers
    {

        const ACTIVE   = 10;
        const INACTIVE = 0;

        public $total_like;
        public $total_comment;
        public $total_post;
        public $total_sub_point;
        public $sum_redeem;
        public $bonus_point;

        const  GEN_MALE   = 1;
        const  GEN_FEMALE = 2;


        const POINT_EVENT_REDEEM  = 'redeem';
        const POINT_EVENT_COMMENT = 'comment_bonus';
        const POINT_EVENT_POST    = 'post_bonus';

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('phone, username, status', 'required'),
                array('bonus_point, genre, status', 'numerical', 'integerOnly' => TRUE),
                array('sso_id, email, otp, full_name, customer_type, district_code, province_code, address_detail, personal_id_create_place, bank_account_id, bank_brandname, bank_name, bank_account_name, job', 'length', 'max' => 255),
                array('phone', 'length', 'max' => 50),
                array('username, personal_id', 'length', 'max' => 100),
                array('avatar, profile_picture', 'length', 'max' => 255),
                array('birthday, create_time, last_update, personal_id_create_date, extra_info', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, phone, username, email, birthday, bonus_point, create_time, last_update, otp, full_name, genre, customer_type, district_code, province_code, address_detail, personal_id, personal_id_create_date, personal_id_create_place, extra_info, bank_account_id, bank_brandname, bank_name, bank_account_name, job, status', 'safe', 'on' => 'search'),
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
                'id'                       => 'ID',
                'sso_id'                   => 'Sso',
                'phone'                    => 'Điện thoại',
                'username'                 => 'Tên đăng nhập',
                'email'                    => 'Email',
                'birthday'                 => 'Ngày sinh',
                'bonus_point'              => 'Điểm',
                'create_time'              => 'Ngày tạo',
                'last_update'              => 'Ngày cập nhật',
                'otp'                      => 'Otp',
                'full_name'                => 'Tên đầy đủ',
                'genre'                    => 'Giới tính',
                'customer_type'            => 'Người dùng',
                'district_code'            => 'Quận huyện',
                'province_code'            => 'Tỉnh thành',
                'address_detail'           => 'Địa chỉ',
                'personal_id'              => 'CMTND',
                'personal_id_create_date'  => 'Ngày cấp',
                'personal_id_create_place' => 'Nơi cấp',
                'extra_info'               => 'Extra Info',
                'bank_account_id'          => 'Số tài khoản',
                'bank_brandname'           => 'Chi nhánh',
                'bank_name'                => 'Tên ngân hàng',
                'bank_account_name'        => 'Tên tài khoản',
                'job'                      => 'Nghề nghiệp',
                'status'                   => 'Trạng thái',
                'level'                    => 'Cấp độ',
                'nation'                   => 'Quốc gia',
            );
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
        public function search($start_date = '', $end_date = '')
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;
            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('sso_id', $this->sso_id, TRUE);
            $criteria->compare('phone', $this->phone, TRUE);
            $criteria->compare('username', $this->username, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('birthday', $this->birthday, TRUE);
            $criteria->compare('bonus_point', $this->bonus_point);
            $criteria->compare('create_time', $this->create_time, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('otp', $this->otp, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('genre', $this->genre);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_id_create_date', $this->personal_id_create_date, TRUE);
            $criteria->compare('personal_id_create_place', $this->personal_id_create_place, TRUE);
            $criteria->compare('extra_info', $this->extra_info, TRUE);
            $criteria->compare('bank_account_id', $this->bank_account_id, TRUE);
            $criteria->compare('bank_brandname', $this->bank_brandname, TRUE);
            $criteria->compare('bank_name', $this->bank_name, TRUE);
            $criteria->compare('bank_account_name', $this->bank_account_name, TRUE);
            $criteria->compare('job', $this->job, TRUE);
            $criteria->compare('status', $this->status);

            $criteria->addCondition("customer_type !=1");
            if ($start_date != '' && $end_date != '') {
                $criteria->addCondition("create_time >= '$start_date' and create_time <='$end_date'");

                return new CActiveDataProvider('ACustomers', array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 'username ASC',
                    ),
                    'pagination' => array(
                        'pageSize' => 20,
                        'params'   => array(
                            'AReportSocialForm[start_date]' => $start_date,
                            'AReportSocialForm[end_date]'   => $end_date,
                        ),
                    ),
                ));
            }

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Customers the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tên khách hàng by sso_id
         *
         * @param $sso_id
         *
         * @return string
         */
        public static function getName($sso_id)
        {
            $customer = ACustomers::model()->findByAttributes(array('sso_id' => $sso_id));

            return ($customer) ? $customer->username : $sso_id;
        }

        /**
         * Lấy toàn bộ customer.
         */
        public static function getAllCustomers()
        {
            $data = ACustomers::model()->findAll();

            return CHtml::listData($data, 'sso_id', 'username');
        }

        /**
         * @param $point
         * Set level by point
         *
         * @return string
         */
        public static function getLevel($point = 0)
        {
            $result = "Kết nối";
            if (isset($point)) {
                if ($point < 5) {
                    $result = "Kết nối";
                } else if ($point >= 10 && $point <= 15) {
                    $result = "Trẻ chung";
                } else if ($point >= 15 && $point <= 20) {
                    $result = "Trẻ chung";
                } else if ($point >= 50) {
                    $result = "Phá cách";
                }
            }

            return $result;
        }

        /**
         * @param $genre
         * lấy dữ liệu giới tính.
         *
         * @return string
         */
        public static function getGenre($genre)
        {
            $label = '';
            if ($genre) {
                if ($genre == ACustomers::GEN_MALE) {
                    $label = 'Nam';
                } else {
                    $label = 'Nữ';
                }
            }

            return $label;
        }

        /**
         * Đặt điểm người dùng.
         *
         * @param $sso_id
         * @param $amount
         * @param $event
         * @param $note
         *
         * @return bool
         */
        public static function setPoint($sso_id, $amount, $event, $note)
        {

            $customer = ACustomers::model()->findByAttributes(array('sso_id' => $sso_id));

            $hp                = new APointHistory();
            $hp->sso_id        = $sso_id;
            $hp->amount        = $amount;
            $hp->amount_before = $customer->bonus_point;
            $hp->event         = $event;
            $hp->description   = $event;
            $hp->create_date   = date('Y-m-d H:i:s');

            $hp->note = $note;
            $customer->bonus_point += $amount;

            if ($customer->bonus_point < 0) {
                $customer->bonus_point = 0;
            }
            if ($customer->save()) {
                return $hp->save();
            }

            return FALSE;
        }

        /**
         * @param $code
         * Lấy tỉnh thành theo code
         *
         * @return string
         */
        public function getProvince($code)
        {
            $province = array();
            if ($code) {
                $province = Province::model()->find('code=:code', array(':code' => $code));
            }

            return ($province) ? CHtml::encode($province->name) : $code;
        }

        public static function getCustomerCompleteInfo($data, $security, $username)
        {

            $type = 'app_get_data_complete_order';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'      => $type,
                'user_name' => $username,
                'id'        => 'id_backend_vsb',
                'data'      => $data,
                'security'  => $security,
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response  = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);
            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('app_get_data_complete_order.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);
            //decode output
            $arr_response = CJSON::decode($response);

            return CJSON::decode($arr_response['data']);

        }

        public static function getCustomerCompleteOrders($data, $security, $username)
        {

            $type = 'app_register_subscription_complete';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'      => $type,
                'user_name' => $username,
                'id'        => 'id_backend_vsb',
                'data'      => $data,
                'security'      => $security,
            );

            $str_json = CJSON::encode($arr_param);
            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response  = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);
            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('app_register_subscription_complete.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);
            //decode output
            $arr_response = CJSON::decode($response);
            if ($arr_response['status']) {
                return $arr_response['status'];
            } else {
                return FALSE;
            }

        }


        public static function getCustomerRegisterInfo($data, $security, $username)
        {

            $type = 'app_register_subscription_info';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'      => $type,
                'user_name' => $username,
                'id'        => 'id_backend_vsb',
                'data'      => $data,
                'security'  => $security,
            );

            $str_json = CJSON::encode($arr_param);
            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);

            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('app_register_subscription_info.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);
            //decode output
            $arr_response = CJSON::decode($response);
            if ($arr_response) {
                return $arr_response;
            } else {
                return FALSE;
            }

        }


    }
