<?php

    /**
     * This is the model class for table "tbl_users".
     *
     * The followings are the available columns in table 'tbl_users':
     *
     * @property string  $id
     * @property string  $username
     * @property string  $password
     * @property string  $user_code
     * @property string  $email
     * @property string  $fullname
     * @property string  $mobile
     * @property string  $personal_id
     * @property string  $phone
     * @property string  $company
     * @property string  $user_type
     * @property string  $address
     * @property string  $tax_id
     * @property string  $bank_id
     * @property string  $bank_name
     * @property string  $agency_contract_number
     * @property string  $extra_info
     * @property string  $created_by
     * @property string  $created_date
     * @property string  $last_login
     * @property integer $status
     * @property string  $token_key
     * @property integer $agency_id
     * @property integer $verify_email
     * @property string  $system_username
     * @property string  $prefix
     * @property string  $suffix
     * @property string  $suffix_en
     * @property string  $advertiser_group_code
     * @property string  $sale_code
     * @property integer $receive_method
     * @property string  $receive_endpoint
     * @property string  $invite_code
     */
    class FTUsers extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_users';
        }

        CONST BLOCK    = 0; // Chặn
        CONST INACTIVE = 1; //Dừng lại
        CONST ACTIVE   = 10; // Kích hoạt

        CONST USER_TYPE_KHDN    = 1; // Kháchh hàng doanh nghiệp
        CONST USER_TYPE_SDL     = 2; // Kháchh hàng Sim du lịch
        CONST USER_TYPE_AGENCY  = 3; // Đại lý mua thẻ
        CONST USER_TYPE_CTV     = 4; // Cộng tác viên

        CONST RECEIVE_TYPE_EMAIL = 1; // Nhận thẻ qua email
        CONST RECEIVE_TYPE_API   = 2; // Nhận thẻ qua API

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('username, password, sale_code', 'required'),
                array('status, agency_id, verify_email, receive_method', 'numerical', 'integerOnly' => TRUE),
                array('username, created_by', 'length', 'max' => 50),
                array('password, personal_id, address, tax_id, bank_id, bank_name, agency_contract_number, system_username, prefix, suffix, suffix_en, advertiser_group_code, receive_endpoint, invite_code', 'length', 'max' => 255),
                array('user_code', 'length', 'max' => 10),
                array('email, fullname, company, token_key', 'length', 'max' => 100),
                array('user_type','length', 'max' => 20),
                array('mobile', 'length', 'max' => 12),
                array('phone', 'length', 'max' => 15),
                array('extra_info', 'length', 'max' => 500),
                array('created_date, last_login', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, user_code, sale_code, email, fullname, mobile, personal_id, phone, company, user_type, address, tax_id, bank_id, bank_name, agency_contract_number, extra_info, created_by, created_date, last_login, status, token_key, agency_id, verify_email, system_username, prefix, suffix, suffix_en, advertiser_group_code, receive_method, receive_endpoint, invite_code', 'safe', 'on' => 'search'),
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
                'id'                     => 'ID',
                'username'               => 'Username',
                'password'               => 'Password',
                'user_code'              => 'User Code',
                'email'                  => 'Email',
                'fullname'               => 'Fullname',
                'mobile'                 => 'Mobile',
                'personal_id'            => 'Personal',
                'phone'                  => 'Phone',
                'company'                => 'Company',
                'user_type'              => 'User Type',
                'address'                => 'Address',
                'tax_id'                 => 'Tax',
                'bank_id'                => 'Bank',
                'bank_name'              => 'Bank Name',
                'agency_contract_number' => 'Agency Contract Number',
                'extra_info'             => 'Extra Info',
                'created_by'             => 'Created By',
                'created_date'           => 'Created Date',
                'last_login'             => 'Last Login',
                'status'                 => 'Status',
                'token_key'              => 'Token Key',
                'agency_id'              => 'Agency',
                'verify_email'           => 'Verify Email',
                'system_username'        => 'System Username',
                'prefix'                 => 'Prefix',
                'suffix'                 => 'Suffix',
                'suffix_en'              => 'Suffix En',
                'advertiser_group_code'  => 'Advertiser Group Code',
                'sale_code'              => 'Sale Code',
                'receive_method'         => 'Receive Method',
                'receive_endpoint'       => 'Receive Endpoint',
                'invite_code'            => 'Invite Code',
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
            $criteria->compare('receive_method', $this->receive_method, TRUE);
            $criteria->compare('receive_endpoint', $this->receive_endpoint, TRUE);
            $criteria->compare('invite_code', $this->invite_code, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * @return CDbConnection the database connection used for this class
         */
        public function getDbConnection()
        {
            return Yii::app()->db_freedoo_tourist;
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return FTUsers the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
