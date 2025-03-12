<?php

    /**
     * This is the model class for table "{{brand_name}}".
     *
     * The followings are the available columns in table '{{brand_name}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $note
     * @property string  $file_profile
     * @property string  $created_date
     * @property string  $last_update
     * @property integer $user_id
     * @property string  $approved_by_system_user
     * @property integer $status
     * @property integer $note_ob
     * @property integer $ob_last_update
     */
    class CskhCtvUsers extends CActiveRecord
    {

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        public $input_type;
        public $ob_3day;
        public $ob_last_update;
        public $ctv_2;
        public $info_search;
        public $status_change;
        public $start_date;
        public $end_date;
        public $ob_status; // Trạng thái OB cộng tác viên,
        public $total_amount;
        const IS_FINISH_PROFILE    = 1;    //đã hoàn thành hồ sơ các nhân
        const IS_NO_FINISH_PROFILE = 0; //chưa hoàn thành hồ sơ các nhân
        const INCORRECT_PROFILE    = 2; //Thông tin sai
        const NO_CONTACT           = 3; //Không tương tác.

        const FINISH_PAYMENT_PROFILE           = 1;    //đã hoàn thành hồ sơ (hồ sơ thành toán và đã gửi bản cam kết và đc duyệt)
        const NOT_FINISH_PAYMENT_PROFILE       = 0; //chưa hoàn thành hồ sơ
        const INCORRECT_FINISH_PAYMENT_PROFILE = 2; //thông tin hồ sơ thanh toán sai

        const OB_YET        = 0; // Chưa OB.
        const OB_NEED       = 1; // Cần OB.
        const OB_NO_CONTACT = 2; // Không liên lạc được.
        const OB_DONE       = 3; // Đã OB.
        const OB_NO_REG     = 4; // Không tương tác không muốn đăng ký.

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_users';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
//                array('user_id, user_name, email, created_on', 'required'),
                array('info_search', 'required'),
                array('sex, status, is_business, finish_profile, profile_approved, finish_payment_profile', 'numerical', 'integerOnly' => TRUE),
                array('owner_points', 'numerical'),
                array('user_id', 'length', 'max' => 128),
                array('agency_id', 'length', 'max' => 128),
                array('user_name', 'length', 'max' => 255),
                array('province_code, district_code, ward_code', 'length', 'max' => 50),
                array('email', 'length', 'max' => 100),
                array('email', 'email', 'message' => 'Email không đúng định dạng!'),
                array('representant_email', 'email', 'message' => 'Email không đúng định dạng!'),
                array('mobile, representant_mobile', 'length', 'max' => 30),
                array('full_name, personal_id, province', 'length', 'max' => 255),
                array('personal_photo_font_url, personal_photo_behind_url, avatar', 'length', 'max' => 512),
                array('address, representant_email', 'length', 'max' => 255),
                array('resident_address', 'length', 'max' => 512),
                array('business_license_address', 'length', 'max' => 255),
                array('website', 'length', 'max' => 1024),
                array('owner_code, inviter_code', 'length', 'max' => 20),
                array('agent_site_id', 'length', 'max' => 250),
//                array('mobile, representant_mobile', 'call_valid_msisdn'),
//                array('personal_id', 'valid_cmtnd'),
                array('website', 'url'),        //'defaultScheme' => 'http'
                array('date_of_birth, business_license_date, agent_joined_on, modified_on, note', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('user_id, agency_id, input_type, user_name, email, mobile, 
                 full_name, date_of_birth,business_license_date,business_license_address sex, 
                 personal_id, personal_photo_font_url, personal_photo_behind_url, avatar, address, 
                 resident_address, province, website, status, province_id, district_id, ward_id, finish_profile,
                 profile_approved, finish_payment_profile, owner_points, owner_code, inviter_code, is_business, 
                 agent_site_id, agent_joined_on, created_on, modified_on, note, note_ob', 'safe', 'on' => 'search'),
            );
        }


        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'user_id'                   => 'User id',
                'agency_id'                 => 'Agency id',
                'user_name'                 => 'Tên đăng nhập',
                'email'                     => 'Email',
                'mobile'                    => 'Số điện thoại',
                'full_name'                 => 'Họ và tên',
                'date_of_birth'             => 'Ngày tháng năm sinh',
                'sex'                       => 'Giới tính',
                'personal_id'               => 'Số CMTND/thẻ căn cước',
                'business_license_date'     => 'Ngày cấp',
                'business_license_address'  => 'Nơi cấp',
                'personal_photo_font_url'   => 'Ảnh mặt trước chứng minh thư/thẻ căn cước',
                'personal_photo_behind_url' => 'Ảnh mặt sau chứng minh thư/thẻ căn ',
                'avatar'                    => 'Ảnh đại diện',
                'address'                   => 'Số nhà',
                'resident_address'          => 'Địa chỉ thường chú',
                'province'                  => 'Tỉnh thành',
                'province_code'             => 'Tỉnh thành',
                'district_code'             => 'Quận/huyện',
                'ward_code'                 => 'Phường/xã',
                'website'                   => 'Website',
                'status'                    => 'Trạng thái',
                'finish_profile'            => 'Phê duyệt thông tin đăng ký?',
                'profile_approved'          => 'Đã gửi "cập nhật" thông thông tin thanh toán?',
                'finish_payment_profile'    => 'Phê duyệt hồ sơ thanh toán?',
                'is_business'               => 'Đại lý/tổ chức?',
                'agent_site_id'             => 'Agent Site id',
                'agent_joined_on'           => 'Agent Joined On',
                'created_on'                => 'Ngày tạo',
                'modified_on'               => 'Cập nhật',
                'owner_points'              => 'Điểm',
                'owner_code'                => 'Mã CTV',
                'inviter_code'              => 'Giới thiệu bởi',
                'representant_mobile'       => 'Điện thoại liên hệ',
                'note'                      => 'Ghi chú',
                'input_type'                => 'Chọn tiêu chí tra cứu',
                'info_search'               => 'Thông tin tra cứu',
                'tax_code'                  => 'Mã số thuế',
                'status_change'             => 'Phê duyệt thay đổi hồ sơ?',
                'start_date'                => 'Ngày bắt đầu',
                'end_date'                  => 'Ngày kêt thúc',
                'ob_status'                 => 'Trạng thái OB',
                'note_ob'                   => 'Note',
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
        public function search($excel = FALSE, $convert = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            if ($convert) {
                $criteria->select = "user_id";
            }

            $now = time();


            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }

            if (isset($this->ob_3day)) { // Check nếu đã OB quá 3 ngày không tương tác.
                if ($this->ob_3day == 'on') {
                    $date_delivered = date('Y-m-d H:i:s', $now);
                    $date_delivered = date('Y-m-d H:i:s', strtotime($date_delivered . '-3days'));

                    $criteria->addCondition("ob_last_update <='$date_delivered'");
                }
            }
            if ($this->user_name != '') {
                $criteria->addCondition("user_name ='$this->user_name'");
            }
            if ($this->mobile != '') {
                $standard_mobile = self::makePhoneNumberStandard($this->mobile);
                $criteria->addCondition("mobile ='$this->mobile' or mobile ='$standard_mobile'");
            }
            if ($this->finish_profile != '') {
                $criteria->addCondition("finish_profile ='$this->finish_profile'");
            }

            if ($this->ob_status != '') {
                $criteria->addCondition("ob_status ='$this->ob_status'");
            }
            if ($excel || $convert) {

                $data = CskhCtvUsers::model()->findAll($criteria);

                return $data;
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 30,
                    'params'   => array(
                        'get'                          => 1,
                        "CskhCtvUsers[start_date]"     => $this->start_date,
                        "CskhCtvUsers[end_date]"       => $this->end_date,
                        "CskhCtvUsers[user_name]"      => $this->user_name,
                        "CskhCtvUsers[mobile]"         => $this->mobile,
                        "CskhCtvUsers[finish_profile]" => $this->finish_profile,
                        "CskhCtvUsers[ob_status]"      => $this->ob_status,

                    ),
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return CskhCtvUsers the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Loại thông tin.
         */
        public function getInputType()
        {
            return array(
                'user_name'  => 'Tên đăng nhập',
//                'full_name'  => 'Tên',
                'mobile'     => 'Số ĐT',
                'email'      => 'Email',
                'owner_code' => 'Mã CTV',
            );
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

        public static function getFinishProfileStatus()
        {
            return array(
                array('id' => self::IS_FINISH_PROFILE, 'finish_profile' => 'Đã hoàn thiện'),
                array('id' => self::IS_NO_FINISH_PROFILE, 'finish_profile' => 'Chờ phê duyệt'),
                array('id' => self::INCORRECT_PROFILE, 'finish_profile' => 'Thông tin sai'),
            );
        }

        public static function getPaymentProfileStatus()
        {
            return array(
                array('id' => self::FINISH_PAYMENT_PROFILE, 'finish_payment_profile' => 'Đã phê duyệt'),
                array('id' => self::NOT_FINISH_PAYMENT_PROFILE, 'finish_payment_profile' => 'Chờ phê duyệt'),
                array('id' => self::INCORRECT_FINISH_PAYMENT_PROFILE, 'finish_payment_profile' => 'Thông tin sai'),
            );
        }

        public static function getFinishProfileStatusText($finish_profile, $publisher_id)
        {
            $txt = '';
            switch ($finish_profile) {
                case self::IS_FINISH_PROFILE:
                    $txt = '<span class="text-bold text-success">Đã hoàn thiện <a href="javascript:void(0);" onclick="CP.publisher.changeProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                case self::IS_NO_FINISH_PROFILE:
                    $txt = '<span class="text-danger">Chờ phê duyệt <a href="javascript:void(0);" onclick="CP.publisher.changeProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                case self::INCORRECT_PROFILE:
                    $txt = '<span class="text-bold text-red">Thông tin sai <a href="javascript:void(0);" onclick="CP.publisher.changeProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                default:
                    $txt = 'Chưa rõ';
                    break;
            }

            return $txt;
        }


        public static function getPaymentProfileStatusText($finish_payment_profile, $publisher_id)
        {
            $txt = '';
            switch ($finish_payment_profile) {
                case self::FINISH_PAYMENT_PROFILE:
                    $txt = '<span class="text-bold text-success">Đã phê duyệt <a href="javascript:void(0);" onclick="CP.publisher.changePaymentProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                case self::NOT_FINISH_PAYMENT_PROFILE:
                    $txt = '<span class="text-danger">Chờ phê duyệt <a href="javascript:void(0);" onclick="CP.publisher.changePaymentProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                case self::INCORRECT_FINISH_PAYMENT_PROFILE:
                    $txt = '<span class="text-bold text-red">Thông tin sai <a href="javascript:void(0);" onclick="CP.publisher.changePaymentProfileStatusForm(\'' . $publisher_id . '\');"></a></span>';
                    break;
                default:
                    $txt = 'Chưa rõ';
                    break;
            }

            return $txt;
        }

        public static function getUserName($user_id)
        {
            if ($user_id) {
                $users = self::model()->findByAttributes(array('user_id' => $user_id));

                if (isset($users)) {
                    if (isset($users->user_name)) {
                        return $users->user_name;
                    }
                }
            }

            return "";
        }

        public static function getTypeOfCtvById($user_id)
        {
            if ($user_id) {
                $users = self::model()->findByAttributes(array('user_id' => $user_id));
                if ($users->agency_id != '') {
                    return "ĐLTC";
                } else {
                    return "CTV";
                }
            }
        }

        public static function getOwnerCode($user_id)
        {
            if ($user_id) {
                $users = self::model()->findByAttributes(array('user_id' => $user_id));
                if (isset($users)) {
                    return $users->owner_code;
                }
            }

            return "";
        }

        public static function getInviterCode($user_id)
        {
            if ($user_id) {
                $users = self::model()->findByAttributes(array('user_id' => $user_id));
                if (isset($users)) {
                    return $users->inviter_code;
                }
            }

            return "";
        }

        public function getAllObStatus()
        {
            return array(
                self::OB_NEED       => 'Cần OB',
                self::OB_NO_CONTACT => 'Không liên lạc được',
                self::OB_DONE       => 'Đã OB',
                self::OB_NO_REG     => 'Không tương tác (Không muốn ĐK)',
            );
        }

        public function getAllFinishProfile()
        {
            return array(
                self::IS_FINISH_PROFILE    => 'Đã hoàn thiện',
                self::IS_NO_FINISH_PROFILE => 'Chờ phê duyệt',
                self::INCORRECT_PROFILE    => 'Thông tin sai',
                self::NO_CONTACT           => 'Không tương tác (Không muốn ĐK)',
            );
        }

        public static function getFinishProfile($finish_profile)
        {
            $data = array(
                self::IS_FINISH_PROFILE    => 'Đã hoàn thiện',
                self::IS_NO_FINISH_PROFILE => 'Chờ phê duyệt',
                self::INCORRECT_PROFILE    => 'Thông tin sai',
                self::NO_CONTACT           => 'Không tương tác (Không muốn ĐK)',
            );

            return isset($data[$finish_profile]) ? $data[$finish_profile] : '';
        }

        public static function getFinishPaymentProfile($finish_payment_profile)
        {
            $data = array(
                self::FINISH_PAYMENT_PROFILE           => 'Đã phê duyệt',
                self::NOT_FINISH_PAYMENT_PROFILE       => 'Chờ phê duyệt',
                self::INCORRECT_FINISH_PAYMENT_PROFILE => 'Thông tin sai',
            );

            return isset($data[$finish_payment_profile]) ? $data[$finish_payment_profile] : '';
        }

        public static function getObStatus($status)
        {
            $data = array(
                self::OB_YET        => 'Chưa OB',
                self::OB_NEED       => 'Cần OB',
                self::OB_NO_CONTACT => 'Không liên lạc được',
                self::OB_DONE       => 'Đã OB',
                self::OB_NO_REG     => 'Không tương tác (Không muốn ĐK)',
            );

            return isset($data[$status]) ? $data[$status] : '';
        }

        public static function getNoteObStatus($user_id)
        {
//           $

//            return isset($data[$status]) ? $data[$status] : '';
        }

        /**
         * Thêm số điện thoại ctv được hưởng ưu đãi đăng kí gói cước FHAPPY, FCLUB
         *
         * @return bool
         */
        public function addPhoneToList($data)
        {
            $type = 'backend_add_package_3t';
            $id   = Yii::app()->request->csrfToken;

//            $logMsg   = array();
//            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
//            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

//            $logMsg[] = array($GLOBALS['config_common']['api']['hostname'], 'URL: ' . __LINE__, 'T', time());
//            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
            //call api
            $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);


//            $logMsg[]  = array($http_code, 'http_code: ' . __LINE__, 'T', time());
//            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
//            $logFolder = "Log_call_api/" . date("Y/m/d");
//            $logObj    = CskhTraceLog::getInstance($logFolder);
//            $logObj->setLogFile($type . '.log');
//            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
//            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {
                $status = $arr_response['status'];

                return $status;
            }

            return FALSE;
        }

        /**
         * Xóa số điện thoại ctv khỏi danh sách được hưởng ưu đãi đăng kí gói cước FHAPPY, FCLUB
         *
         * @return bool
         */
        public function removePhoneToList($data)
        {
            $type = 'backend_remove_package_3t';
            $id   = Yii::app()->request->csrfToken;

//            $logMsg   = array();
//            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
//            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

//            $logMsg[] = array($GLOBALS['config_common']['api']['hostname'], 'URL: ' . __LINE__, 'T', time());
//            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
            //call api
            $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);
//            $logMsg[]  = array($http_code, 'http_code: ' . __LINE__, 'T', time());
//            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
//            $logFolder = "Log_call_api/" . date("Y/m/d");
//            $logObj    = CskhTraceLog::getInstance($logFolder);
//            $logObj->setLogFile($type . '.log');
//            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
//            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {
                $status = $arr_response['status'];

                return $status;
            }
            return FALSE;
        }


    }
