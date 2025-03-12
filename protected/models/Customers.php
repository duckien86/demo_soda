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
     * @property string  $level
     */
    class Customers extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{customers}}';
        }

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
                array('avatar, profile_picture, level', 'length', 'max' => 255),
                array('birthday, create_time, last_update, personal_id_create_date, extra_info', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, phone, username, email, birthday, bonus_point, create_time, last_update, otp, full_name, genre, customer_type, district_code, province_code, address_detail, personal_id, personal_id_create_date, personal_id_create_place, extra_info, bank_account_id, bank_brandname, bank_name, bank_account_name, job, status, level', 'safe', 'on' => 'search'),
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
                'phone'                    => 'Phone',
                'username'                 => 'Username',
                'email'                    => 'Email',
                'birthday'                 => 'Birthday',
                'bonus_point'              => 'Bonus Point',
                'create_time'              => 'Create Time',
                'last_update'              => 'Last Update',
                'otp'                      => 'Otp',
                'full_name'                => 'Full Name',
                'genre'                    => 'Genre',
                'customer_type'            => 'Customer Type',
                'district_code'            => 'District Code',
                'province_code'            => 'Province Code',
                'address_detail'           => 'Address Detail',
                'personal_id'              => 'Personal',
                'personal_id_create_date'  => 'Personal Id Create Date',
                'personal_id_create_place' => 'Personal Id Create Place',
                'extra_info'               => 'Extra Info',
                'bank_account_id'          => 'Bank Account',
                'bank_brandname'           => 'Bank Brandname',
                'bank_name'                => 'Bank Name',
                'bank_account_name'        => 'Bank Account Name',
                'job'                      => 'Job',
                'status'                   => 'Status',
                'level'                    => 'Level',
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
    }
