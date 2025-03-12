<?php

    /**
     * This is the model class for table "{{brand_name}}".
     *
     * The followings are the available columns in table '{{brand_name}}':
     *
     * @property string  user_id
     * @property string  agency_id
     * @property string  user_name
     * @property string  email
     * @property string  mobile
     * @property string  full_name
     * @property string  date_of_birth
     * @property string  sex
     * @property string  personal_id
     * @property string  personal_photo_font_url
     * @property string  personal_photo_behind_url
     * @property string  avatar
     * @property string  address
     * @property string  resident_address
     * @property string  province
     * @property string  province_code
     * @property string  district_code
     * @property string  ward_code
     * @property string  website
     * @property string  finish_profile
     * @property string  profile_approved
     * @property string  finish_payment_profile
     * @property string  status
     * @property string  owner_points
     * @property string  owner_code
     * @property string  inviter_code
     * @property string  is_business
     * @property string  agent_site_id
     * @property string  agent_joined_on
     * @property string  created_on
     * @property string  modified_on
     * @property string  business_license
     * @property string  business_license_date
     * @property string  business_license_address
     * @property string  tax_code
     * @property string  transaction_address
     * @property string  representant_person
     * @property string  representant_email
     * @property string  representant_mobile
     * @property string  campaign_id
     * @property string  finish_profile_on
     * @property string  finish_payment_profile_on
     * @property string  note
     * @property string  password
     * @property string  ob_status
     * @property string  note_ob
     * @property string  ob_last_update
     */
    class ACtvUsers extends CActiveRecord
    {

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        public $total_user;
        public $total_user_finished;
        public $total_user_renueve;
        public $total_renueve;
        public $input_type;
        public $ctv_2;
        public $info_search;
        public $status_change;
        public $start_date;
        public $end_date;
        public $date;
        public $total;
        const IS_FINISH_PROFILE    = 1;    //đã hoàn thành hồ sơ các nhân
        const IS_NO_FINISH_PROFILE = 0; //chưa hoàn thành hồ sơ các nhân
        const INCORRECT_PROFILE    = 2; //Thông tin sai

        const FINISH_PAYMENT_PROFILE           = 1;    //đã hoàn thành hồ sơ (hồ sơ thành toán và đã gửi bản cam kết và đc duyệt)
        const NOT_FINISH_PAYMENT_PROFILE       = 0; //chưa hoàn thành hồ sơ
        const INCORRECT_FINISH_PAYMENT_PROFILE = 2; //thông tin hồ sơ thanh toán sai

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
                array('info_search', 'required', 'on' => 'admin'),
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
                array('user_id, agency_id, input_type, user_name, email, mobile, representant_mobile full_name, date_of_birth,business_license_date,business_license_address sex, personal_id, personal_photo_font_url, personal_photo_behind_url, avatar, address, resident_address, province, website, status, province_id, district_id, ward_id, finish_profile, profile_approved, finish_payment_profile, owner_points, owner_code, inviter_code, is_business, agent_site_id, agent_joined_on, created_on, modified_on, note', 'safe', 'on' => 'search'),
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
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('user_id', $this->user_id, TRUE);
            $criteria->compare('agency_id', $this->agency_id, TRUE);
            $criteria->compare('user_name', $this->user_name, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('mobile', $this->mobile, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('date_of_birth', $this->date_of_birth, TRUE);
            $criteria->compare('sex', $this->sex);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_photo_font_url', $this->personal_photo_font_url, TRUE);
            $criteria->compare('personal_photo_behind_url', $this->personal_photo_behind_url, TRUE);
            $criteria->compare('personal_photo_behind_url', $this->avatar, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('resident_address', $this->resident_address, TRUE);
            $criteria->compare('province', $this->province, TRUE);
            $criteria->compare('website', $this->website, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('province_id', $this->province_code, TRUE);
            $criteria->compare('district_id', $this->district_code, TRUE);
            $criteria->compare('ward_id', $this->ward_code, TRUE);
            $criteria->compare('finish_profile', $this->finish_profile);
            $criteria->compare('profile_approved', $this->profile_approved);
            $criteria->compare('finish_payment_profile', $this->finish_payment_profile);
            $criteria->compare('owner_points', $this->owner_points);
            $criteria->compare('owner_code', $this->owner_code, TRUE);
            $criteria->compare('inviter_code', $this->inviter_code, TRUE);
            $criteria->compare('is_business', $this->is_business);
            $criteria->compare('agent_site_id', $this->agent_site_id, TRUE);
            $criteria->compare('agent_joined_on', $this->agent_joined_on, TRUE);
            $criteria->compare('created_on', $this->created_on, TRUE);
            $criteria->compare('modified_on', $this->modified_on, TRUE);
            $criteria->compare('note', $this->note, TRUE);

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
         * @return ACtvUsers the static model class
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
                'full_name'  => 'Tên',
                'mobile'     => 'Số ĐT',
                'email'      => 'Email',
                'owner_code' => 'Mã CTV',
            );
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
                    if (!empty($users->inviter_code)) {
                        return $users->inviter_code;
                    } else {
                        if (!empty($users->agency_id)) {
                            $agency = self::model()->findByAttributes(array('user_id' => $users->agency_id));
                            if (isset($agency)) {
                                if (!empty($agency->owner_code)) {
                                    return $agency->owner_code;
                                }
                            }
                        }
                    }
                }

                return "";
            }
        }

        /**
         * @param $owner_code
         * @return string
         */
        public static function getNameByCode($owner_code)
        {
            $cache_key = "ACtvUsers_getNameByCode_$owner_code";
            $result = Yii::app()->cache->get($cache_key);
            if(!$result){
                $criteria = new CDbCriteria();
                $criteria->condition = "t.owner_code = :owner_code";
                $criteria->params = array(
                    ':owner_code' => $owner_code
                );
                $model = ACtvUsers::model()->find($criteria);
                if($model){
                    $result = (!empty($model->full_name)) ? $model->full_name : $model->user_name;
                }
            }

            return $result;
        }
    }
