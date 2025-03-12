<?php

    class WCustomers extends Customers
    {
        const  IS_USER   = 0;
        const  IS_ADMIN  = 10;
        const  SUB_ADMIN = 1;

        const  SIM_FREEDOO = 10;//status tbl_sim

        const  GEN_MALE   = 1;
        const  GEN_FEMALE = 2;

        const POINT_EVENT_REDEEM   = 'redeem';
        const POINT_EVENT_COMMENT  = 'comment_bonus';
        const POINT_EVENT_POST     = 'post_bonus';
        const POINT_EVENT_PROFILE  = 'profile_bonus';
        const POINT_EVENT_BIRTHDAY = 'birthday_bonus';
        const POINT_REDEEM_GIFT    = 201;
        const POINT_BIRTHDAY_GIFT  = 201;

        const ACTIVE = 10;
        CONST INACTIVE = 0;

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
                array('phone', 'msisdn_validation', 'length', 'max' => 50),
                array('username, personal_id', 'length', 'max' => 100),
                array('avatar, profile_picture, level', 'length', 'max' => 255),
                array('birthday, create_time, last_update, personal_id_create_date, extra_info', 'safe'),
                array('personal_id', 'checkFormat'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, phone, username, email, birthday, bonus_point, create_time, last_update, otp, full_name, genre, customer_type, district_code, province_code, address_detail, personal_id, personal_id_create_date, personal_id_create_place, extra_info, bank_account_id, bank_brandname, bank_name, bank_account_name, job, status, level', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkFormat($attribute, $params)
        {
            $pattern = '/^([0-9]{9}|[0-9]{12})$/';
            if ($this->$attribute && !preg_match($pattern, $this->$attribute)) {
                $this->addError($attribute, Yii::t('web/portal', 'format_personal_id'));
            }
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkMaxBirthday($attribute, $params)
        {
            if ($this->$attribute) {
                $value    = date('Y', strtotime(str_replace('/', '-', $this->$attribute)));
                $max_year = date('Y') - 15;
                if ($value > $max_year) {
                    $this->addError($attribute, Yii::t('web/portal', 'error_max_birthday'));
                }
            }
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
                'sso_id'                   => 'Mã KH',
                'phone'                    => 'Số điện thoại',
                'username'                 => 'Tên đăng nhập',
                'email'                    => 'Email',
                'birthday'                 => 'Ngày sinh',
                'bonus_point'              => 'Điểm thưởng',
                'create_time'              => 'Ngày tạo',
                'last_update'              => 'Cập nhật cuối',
                'otp'                      => 'Mã xác thực',
                'full_name'                => 'Tên đầy đủ',
                'genre'                    => 'Giới tính',
                'customer_type'            => 'Nhóm KH',
                'district_code'            => 'Quận/Huyện',
                'province_code'            => 'Tỉnh/Thành phố',
                'address_detail'           => 'Địa chỉ',
                'personal_id'              => 'Số CMND',
                'personal_id_create_date'  => 'Ngày cấp CMND',
                'personal_id_create_place' => 'Nơi cấp CMND',
                'extra_info'               => 'Thông tin khác',
                'bank_account_id'          => 'Tài khoản ngân hàng',
                'bank_brandname'           => 'Chi nhánh',
                'bank_name'                => 'Tên ngân hàng',
                'bank_account_name'        => 'Chủ tài khoản',
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
        public function search()
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
            $criteria->compare('customer_type', $this->customer_type, TRUE);
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
            $criteria->compare('level', $this->level);

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

        public function beforeSave()
        {
            if (empty($this->birthday)) {
                $this->birthday = NULL;
            }

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        public function msisdn_validation()
        {
            if ($this->phone) {
                $input = $this->phone;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('phone', Yii::t('web/portal', 'msisdn_validation'));
                }
            }
        }

        public function getProvince()
        {
            $data   = WProvince::model()->findAll();
            $return = CHtml::listData($data, 'id', 'name');

            return $return;
        }

        public function getTotalLikes()
        {
            $criteria = new CDbCriteria();
            $criteria->compare('sso_id', Yii::app()->user->sso_id);
            $likes = WLikes::model()->findAll($criteria);

            return $likes;
        }

        public function getTotalComments($sso_id)
        {
            $criteria = new CDbCriteria();
            $criteria->compare('sso_id', $sso_id);
            $comment = WComments::model()->count($criteria);

            return $comment;
        }

        public static function getCountPosts($sso_id)
        {
            $criteria = new CDbCriteria();
            $criteria->compare('sso_id', $sso_id);
            $comment = WPosts::model()->count($criteria);

            return $comment;
        }

        public function getBonusPointHistory()
        {
            $criteria = new CDbCriteria();
            $criteria->compare('sso_id', Yii::app()->user->sso_id);
            $comment = WPointHistory::model()->findAll($criteria);

            return $comment;

        }

        public function getGiftHistory($sso_id)
        {
            $criteria = new CDbCriteria();
            $criteria->compare('sso_id', $sso_id);

            return new CActiveDataProvider(WRedeemHistory::model(), array(
                'criteria' => $criteria,
            ));
        }

        public function getAddressDetail($address_detail, $district_code, $province_code)
        {
            $province      = WProvince::model()->find('code=:code', array(':code' => $province_code));
            $district      = WDistrict::model()->find('code=:code', array(':code' => $district_code));
            $province_name = '';
            if ($province) {
                $province_name = ' - ' . $province->name;
            }
            $district_name = '';
            if ($district) {
                $district_name = ' - ' . $district->name;
            }

            return CHtml::encode($address_detail . $district_name . $province_name);
        }

        public static function getHobbies($sso_id, $str_return = TRUE)
        {
            $criteria         = new CDbCriteria();
            $criteria->select = 't.*';
            $criteria->join   = 'INNER JOIN sc_tbl_customer_hobbies ch ON ch.sc_tbl_hobbies_id = t.id';
            $criteria->compare('ch.sso_id', $sso_id);
            $arr_hobbies = CHtml::listData(WHobbies::model()->findAll($criteria), 'id', 'name');

            if ($str_return && is_array($arr_hobbies)) {
                return implode(',', $arr_hobbies);
            }

            return $arr_hobbies;
        }

        public static function setPoint($sso_id, $amount, $event, $note)
        {

            $customer = WCustomers::model()->findByAttributes(array('sso_id' => $sso_id));

            $hp                = new WPointHistory();
            $hp->sso_id        = $sso_id;
            $hp->amount        = $amount;
            $hp->amount_before = $customer->bonus_point;
            $hp->event         = $event;
            $hp->description   = $event;
            $hp->create_date   = date('Y-m-d H:i:s');


            $hp->note              = $note;
            $customer->bonus_point += $amount;
            if ($customer->save()) {
                return $hp->save();
            }

            return FALSE;
        }

        public static function getMinusPoint($sso_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "sum(amount) as total";
            $criteria->condition = "sso_id='" . $sso_id . "' and amount < 0";

            $data = WPointHistory::model()->findAll($criteria)[0];

            return ($data->total) ? $data->total : '';
        }

        public static function getTopUser()
        {
            $cache_key = 'sc_getTopUser';
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $criteria        = new CDbCriteria();
                $criteria->limit = '6';
                $criteria->order = 'bonus_point desc';

                $results = self::model()->findAll($criteria);
                Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['sc_top_user']);
            }

            return $results;
        }


        public function getGenre($genre)
        {
            $label = '';
            if ($genre) {
                if ($genre == WCustomers::GEN_MALE) {
                    $label = 'Nam';
                } else {
                    $label = 'Nữ';
                }
            }

            return $label;
        }

        public function getLevel($point, $is_admin = 0, $style = '')
        {
            $level = '<span>';
            if ($point >= 0 && $point <= 50) {
                $star = 1;
            } elseif ($point > 50 && $point <= 200) {
                $star = 2;
            } elseif ($point > 200 && $point <= 500) {
                $star = 3;
            } elseif ($point > 501) {
                $star = 4;
            } else {
                $star = 0;
            }

            if ($is_admin == self::IS_ADMIN) {//set admin
                $star = 5;
            }
            if ($star) {
                for ($i = 1; $i <= $star; $i++) {
                    $level .= '<i class="fa fa-star" style="' . $style . '"></i>';
                }
            }
            $level .= '</span>';

            return $level;
        }

        public function getSrcAvatar($avatar)
        {
            $src_img = $GLOBALS['config_common']['project']['hostname'] . $avatar;

            if (empty($avatar) || @getimagesize($src_img) == FALSE) {
                $src_img = Yii::app()->theme->baseUrl . '/images/logo.png';
            }

            return $src_img;
        }

        public function getSrcProfilePicture($image)
        {
            $src_img = $GLOBALS['config_common']['project']['hostname'] . $image;
            if (empty($image) || @getimagesize($src_img) == FALSE) {
                $detect = new MyMobileDetect();
                if ($detect->isMobile()) {
                    $src_img = Yii::app()->theme->baseUrl . '/images/banner_mobile.jpg';
                } else {
                    $src_img = Yii::app()->theme->baseUrl . '/images/banner.jpg';
                }
            }

            return $src_img;
        }

        public static function getLabelLevel($point, $is_admin = 0)
        {
            if ($point >= 0 && $point <= 50) {
                $level = 'Kết nối';
            } elseif ($point > 50 && $point <= 200) {
                $level = 'Trẻ trung';
            } elseif ($point > 200 && $point <= 500) {
                $level = 'Tự tin';
            } elseif ($point > 501) {
                $level = 'Phá cách';
            } else {
                $level = '';
            }

            if ($is_admin == self::IS_ADMIN) {//set admin
                $level = 'admin';
            }

            return $level;
        }

        public static function getListEvent($type)
        {
            if ($type) {
                $events = array(
                    self::POINT_EVENT_POST     => 'Đăng bài viết',
                    self::POINT_EVENT_COMMENT  => 'Bình luận bài viết',
                    self::POINT_EVENT_REDEEM   => 'Đổi quà',
                    self::POINT_EVENT_PROFILE  => 'Điền đủ thông tin cá nhân',
                    self::POINT_EVENT_BIRTHDAY => 'Sinh nhật',
                );
            } else {
                $events = array(
                    self::POINT_EVENT_POST    => 'Bị ẩn bài viết',
                    self::POINT_EVENT_COMMENT => 'Bị ẩn bình luận bài viết',
                    self::POINT_EVENT_REDEEM  => 'Đổi quà',
                );
            }

            return $events;
        }

        public static function getCustomerById($id)
        {
            $cache_key = "WCustomers_getCustomerById_$id";
            $result = Yii::app()->cache->get($cache_key);
            if(!$result){
                $criteria = new CDbCriteria();
                $criteria->condition = 't.id = :id AND t.status = :status';
                $criteria->params = array(
                    ':id' => $id,
                    ':status' => WCustomers::ACTIVE
                );
                $result = WCustomers::model()->find($criteria);
                Yii::app()->cache->set($cache_key,$result,60*5);
            }
            return $result;
        }
    }
