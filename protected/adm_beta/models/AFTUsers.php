<?php

    /**
     * Class AFTUsers
     *   * @property string $re_password;
     */
    class AFTUsers extends FTUsers
    {

        public $re_password;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('sale_code, tax_id, fullname, address, phone', 'required'),
                array('username, password, email, company, re_password', 'required', 'on' => 'create'),
                array('username', 'match', 'pattern' => '/^([a-z0-9_\.-])+$/', 'message' => "Tên đăng nhập chỉ gồm các ký tự a->z, 0->9, _ ,- và dấu chấm", 'on' => 'create'),
                array('username', 'unique', 'message' => 'Tên đăng nhập đã được sử dụng!'),

                array('email', 'email'),
                array('email', 'unique', 'message' => 'Email đã được sử dụng!', 'on' => 'create'),
                array('email, fullname, company, token_key, created_by, username', 'length', 'max' => 100),

                array('password', 'match', 'pattern' => '/^([a-z0-9_\.-])+$/', 'message' => "Mật khẩu chỉ gồm các ký tự a->z, 0->9, _ ,- và dấu chấm", 'on' => 'create'),
                array('re_password', 'compare', 'compareAttribute' => 'password', 'on' => 'create'),
                array('phone', 'authenticateMsisdn'),

                array('company', 'unique', 'message' => 'Tên đã được sử dụng!', 'on' => 'create'),
                array('tax_id', 'unique', 'message' => 'Mã số thuế đã được sử dụng!', 'on' => 'create'),
                array('phone', 'unique', 'message' => 'Số điện thoại đã được sử dụng!', 'on' => 'create'),

                array('status, agency_id, verify_email', 'numerical', 'integerOnly' => TRUE),
                array('password, personal_id, address, tax_id, bank_id, bank_name, agency_contract_number, system_username, prefix, suffix, suffix_en, advertiser_group_code', 'length', 'max' => 255),
                array('user_code', 'length', 'max' => 10),
                array('mobile', 'length', 'max' => 12),
                array('phone', 'length', 'max' => 15),
                array('user_type', 'length', 'max' => 20),
                array('extra_info', 'length', 'max' => 500),
                array('created_date, last_login', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, user_code, sale_code, email, fullname, mobile, personal_id, phone, company, user_type, address, tax_id, bank_id, bank_name, agency_contract_number, extra_info, created_by, created_date, last_login, status, token_key, agency_id, verify_email, system_username, prefix, suffix, suffix_en, advertiser_group_code', 'safe', 'on' => 'search'),
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
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'                     => 'ID',
                'username'               => 'Tên đăng nhập',
                'password'               => 'Mật khẩu',
                'user_code'              => 'User Code',
                'email'                  => 'Email',
                'fullname'               => 'Họ và tên người đại diện',
                'mobile'                 => 'Số điện thoại',
                'personal_id'            => 'Số CMND',
                'phone'                  => 'Số điện thoại',
                'company'                => 'Tên công ty',
                'user_type'              => 'User Type',
                'address'                => 'Địa chỉ',
                'tax_id'                 => 'Mã số thuế',
                'bank_id'                => 'Bank',
                'bank_name'              => 'Bank Name',
                'agency_contract_number' => 'Agency Contract Number',
                'extra_info'             => 'Extra Info',
                'created_by'             => 'Created By',
                'created_date'           => 'Created Date',
                'last_login'             => 'Last Login',
                'status'                 => 'Trạng thái',
                'token_key'              => 'Token Key',
                'agency_id'              => 'Agency',
                'verify_email'           => 'Verify Email',
                'system_username'        => 'System Username',
                'prefix'                 => 'Prefix',
                'suffix'                 => 'Suffix',
                'suffix_en'              => 'Suffix En',
                'advertiser_group_code'  => 'Advertiser Group Code',
                'sale_code'              => 'Số giấy đăng ký kinh doanh',
                're_password'            => 'Nhập lại MK',
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
            $criteria->compare('username', $this->username, TRUE);
            $criteria->compare('password', $this->password, TRUE);
            $criteria->compare('user_code', $this->user_code, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('fullname', $this->fullname, TRUE);
            $criteria->compare('mobile', $this->mobile, TRUE);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('phone', $this->phone, TRUE);
            $criteria->compare('company', $this->company, TRUE);
            $criteria->compare('user_type', $this->user_type, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('tax_id', $this->tax_id, TRUE);
            $criteria->compare('bank_id', $this->bank_id, TRUE);
            $criteria->compare('bank_name', $this->bank_name, TRUE);
            $criteria->compare('agency_contract_number', $this->agency_contract_number, TRUE);
            $criteria->compare('extra_info', $this->extra_info, TRUE);
            $criteria->compare('created_by', $this->created_by, TRUE);
            $criteria->compare('created_date', $this->created_date, TRUE);
            $criteria->compare('last_login', $this->last_login, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('token_key', $this->token_key, TRUE);
            $criteria->compare('agency_id', $this->agency_id);
            $criteria->compare('verify_email', $this->verify_email);
            $criteria->compare('system_username', $this->system_username, TRUE);
            $criteria->compare('prefix', $this->prefix, TRUE);
            $criteria->compare('suffix', $this->suffix, TRUE);
            $criteria->compare('suffix_en', $this->suffix_en, TRUE);
            $criteria->compare('advertiser_group_code', $this->advertiser_group_code, TRUE);
            $criteria->compare('sale_code', $this->sale_code, TRUE);

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
         * @return AFTUsers the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy danh sách user tourist
         */
        public static function getAllUserTourist()
        {
            $users = AFTUsers::model()->findAll('status =:status', array(':status' => self::ACTIVE));

            return CHtml::listData($users, 'id', 'username');
        }

        /**
         * @return array
         * Lấy toàn bộ trạng thái
         */
        public function getStatusUsers()
        {
            return array(
                self::INACTIVE => 'Dừng lại',
                self::ACTIVE   => 'Kích hoạt',
            );
        }

        /**
         * @return array
         * Lấy tên trạng thái
         */
        public function getNameStatusUsers($status)
        {
            $data = array(
                self::INACTIVE => 'Dừng lại',
                self::ACTIVE   => 'Kích hoạt',
            );

            return isset($data[$status]) ? $data[$status] : "";
        }

        public function beforeSave()
        {
            if ($this->scenario == 'create') {
                $this->password = CPasswordHelper::hashPassword($this->password);
            }

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        public function getUserById($id)
        {
            if ($id) {
                $users = AFTUsers::model()->findByAttributes(array('id' => $id));
                if ($users) {
                    return $users->username;
                }
            }
        }
    }
