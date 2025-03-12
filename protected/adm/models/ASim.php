<?php

    class ASim extends Sim
    {
        public $full_name;
        CONST TYPE_PREPAID  = 1;
        CONST TYPE_POSTPAID = 2;

        public $action;
        public $package_code;
        public $order_id;

        const SIM_ACTIVE   = 10;
        const SIM_INACTIVE = 0;

        // Thao tác tool vận hành.
        const KEEP         = 1;
        const CANCEL_KEEP  = 2;
        const CREATE_SIM   = 3;
        const DKTT         = 4;
        const HMTS         = 5;
        const PACKAGE_PRE  = 6;
        const PACKAGE_POST = 7;
        const OPEN_PACKAGE = 8;

        public $term;
        public $price_term;
        public $raw_data;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
//                array('store_id', 'required'),
                array('type, personal_id_type, status, gender', 'numerical', 'integerOnly' => TRUE),
                array('id, personal_id', 'length', 'max' => 100),
                array('serial_number, msisdn, short_description, description, personal_id_create_place, full_name, country, confirm_code, register_for, customer_type_vnpt_net', 'length', 'max' => 255),
                array('price, store_id, full_name', 'length', 'max' => 20),
                array('address, photo_face_url, photo_id_card_url_1, photo_id_card_url_2, photo_order_board_url', 'length', 'max' => 400),
                array('personal_id_create_date, birthday, esim_qrcode', 'safe'),
                array('msisdn, action', 'required', 'on' => 'check_msisdn'),
                array('msisdn, package_code', 'required', 'on' => 'open_package'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, serial_number, msisdn, short_description, description, price, type, personal_id, personal_id_create_date, personal_id_create_place, personal_id_type, full_name, birthday, address, status, photo_face_url, photo_id_card_url_1, photo_id_card_url_2, photo_order_board_url, gender, country, confirm_code, register_for, customer_type_vnpt_net, store_id', 'safe', 'on' => 'search'),
            );
        }


        public function attributeLabels()
        {
            return array(
                'id'                       => 'ID',
                'serial_number'            => 'Serial Number',
                'msisdn'                   => 'Số thuê bao',
                'short_description'        => 'Short Description',
                'description'              => 'Description',
                'price'                    => 'Giá sim',
                'type'                     => 'Hình thức',
                'personal_id'              => 'Personal',
                'personal_id_create_date'  => 'Personal Id Create Date',
                'personal_id_create_place' => 'Personal Id Create Place',
                'personal_id_type'         => 'Personal Id Type',
                'full_name'                => 'Full Name',
                'birthday'                 => 'Birthday',
                'address'                  => 'Address',
                'status'                   => 'Trạng thái',
                'photo_face_url'           => 'Photo Face Url',
                'photo_id_card_url_1'      => 'Photo Id Card Url 1',
                'photo_id_card_url_2'      => 'Photo Id Card Url 2',
                'photo_order_board_url'    => 'Photo Order Board Url',
                'gender'                   => 'Gender',
                'country'                  => 'Country',
                'confirm_code'             => 'Confirm Code',
                'register_for'             => 'Register For',
                'customer_type_vnpt_net'   => 'Customer Type Vnpt Net',
                'store_id'                 => 'Store',
                'action'                   => 'Thao tác',
                'package_code'             => 'Mã gói',
                'order_id'                 => 'Mã ĐH',
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
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('serial_number', $this->serial_number, TRUE);
            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('short_description', $this->short_description, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_id_create_date', $this->personal_id_create_date, TRUE);
            $criteria->compare('personal_id_create_place', $this->personal_id_create_place, TRUE);
            $criteria->compare('personal_id_type', $this->personal_id_type);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('birthday', $this->birthday, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('photo_face_url', $this->photo_face_url, TRUE);
            $criteria->compare('photo_id_card_url_1', $this->photo_id_card_url_1, TRUE);
            $criteria->compare('photo_id_card_url_2', $this->photo_id_card_url_2, TRUE);
            $criteria->compare('photo_order_board_url', $this->photo_order_board_url, TRUE);
            $criteria->compare('gender', $this->gender);
            $criteria->compare('country', $this->country, TRUE);
            $criteria->compare('confirm_code', $this->confirm_code, TRUE);
            $criteria->compare('register_for', $this->register_for, TRUE);
            $criteria->compare('customer_type_vnpt_net', $this->customer_type_vnpt_net, TRUE);
            $criteria->compare('store_id', $this->store_id, TRUE);

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
         * @return ASim the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $data
         * Khai báo serial sim.
         *
         * @return bool|mixed
         */
        public static function registerSim($data, $security, $username)
        {
            $type = 'app_sim_registration';
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

            $logMsg[] = array(Yii::app()->params['socket_api_app'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson(Yii::app()->params['socket_api_app'], $str_json);

            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('log_app_sim_registration.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            $arr_response = CJSON::decode($response);
            if ($arr_response) {
                return $arr_response;
            }

            return FALSE;
        }

        public function checkMsisdn($msisdn, $type, $store, $flags = FALSE, $package_code = '')
        {
            $id = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
            if ($flags) {
                $data = array(
                    'so_tb' => $msisdn,
                    'store' => $store
                );
            } else if ($type == 'backend_register_package') {
                $data = array(
                    'msisdn'       => $msisdn,
                    'package_code' => $package_code
                );
            } else {
                $data = array(
                    'so_tb' => $msisdn,
                );
            }
            $arr_param = array(
                'type' => $type,
                'data' => CJSON::encode($data),

            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);

            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d") . "/operation";
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            return $response;

        }

        public function getAllType()
        {
            return array(
                self::TYPE_PREPAID  => 'Trả trước',
                self::TYPE_POSTPAID => 'Trả sau',
            );
        }

        public function getAllStatus()
        {
            return array(
                self::SIM_INACTIVE => 'Chưa kích hoạt',
                self::SIM_ACTIVE   => 'Kích hoạt',
            );
        }

        public function getAction()
        {
            return array(
                self::KEEP         => 'Giữ số',
                self::CANCEL_KEEP  => 'Hủy giữ số',
                self::CREATE_SIM   => 'Check khởi tạo',
                self::DKTT         => 'Check DKTT',
                self::HMTS         => 'Check HMTS',
                self::PACKAGE_PRE  => 'Check gói trả trước',
                self::PACKAGE_POST => 'Check gói trả sau',
            );
        }

        public function getAllStore()
        {
            return array(
                '34012' => 'Kho Du lịch Kênh bán hàng online',
                '34009' => 'Kho Khai thác viên online',
                '33941' => 'Kho bán hàng onlline1',
                '33903' => 'Kho chung Kênh bán hàng online',
                '34010' => 'Kho đại lý Kênh bán hàng online',
            );
        }

        public static function getTypeLabel($type)
        {
            $model = new ASim();
            $data = $model->getAllType();

            return isset($data[$type]) ? $data[$type] : $type;
        }
    }
