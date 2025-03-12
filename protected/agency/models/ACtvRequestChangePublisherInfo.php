<?php

    /**
     * This is the model class for table "{{request_change_publisher_info}}".
     *
     * The followings are the available columns in table '{{request_change_publisher_info}}':
     *
     * @property string  $id
     * @property string  $publisher_id
     * @property string  $personal_id
     * @property string  $business_license_date
     * @property string  $business_license_address
     * @property string  $resident_address
     * @property string  $address
     * @property integer $province_code
     * @property integer $district_code
     * @property integer $ward_code
     * @property string  $representant_mobile
     * @property string  $representant_email
     * @property string  $account_number
     * @property string  $account_name
     * @property string  $bank_id
     * @property string  $bank_office
     * @property string  $created_date
     * @property string  $last_update
     * @property string  $file_path
     * @property integer $status
     * @property string  $note
     */
    class ACtvRequestChangePublisherInfo extends CActiveRecord
    {
        const STATUS_CHANGED     = -1;//Đã thay đổi thông tin cho CTV
        const STATUS_NOT_PROCESS = 0;// chưa xử lý
        const CORRECT_INFO       = 1;// Thông tin đúng
        const INCORRECT_INFO     = 2;// Thông tin sai


        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_request_change_publisher_info';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('publisher_id', 'required'),
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('publisher_id', 'length', 'max' => 128),
                array('personal_id', 'length', 'max' => 60),
                array('province_code, district_code, ward_code', 'length', 'max' => 50),
                array('business_license_address, address, representant_email, account_name, bank_office', 'length', 'max' => 255),
                array('resident_address', 'length', 'max' => 512),
                array('representant_mobile', 'length', 'max' => 30),
                array('account_number', 'length', 'max' => 100),
                array('bank_id', 'length', 'max' => 11),
                array('file_path', 'length', 'max' => 1024),
                array('business_license_date, created_date, last_update, note', 'safe'),
                array('representant_email', 'email', 'message' => 'Email không đúng định dạng!'),
                array('personal_id', 'valid_cmtnd', 'message' => 'Số CMT/thẻ căn cước không đúng định dạng!'),
                array('representant_mobile', 'call_valid_mobile'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, publisher_id, personal_id, business_license_date, business_license_address, resident_address, address, province_code, district_code, ward_code,  representant_mobile, representant_email, account_number, account_name, bank_id, bank_office, created_date, last_update, file_path, status, note', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function call_valid_mobile()
        {
            if ($this->representant_mobile) {
                $input = $this->representant_mobile;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('mobile', 'Số điện thoại không đúng định dạng');
                }
            }
        }

        public function valid_cmtnd()
        {
            if ($this->personal_id) {
                $input = $this->personal_id;
                if (preg_match("/^[0-9]{8,12}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('personal_id', 'Số CMND/thẻ căn cước không hợp lệ');
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
            return array(
//                'bank' => array(self::BELONGS_TO, 'Banks', 'bank_id'),
//                'user' => array(self::BELONGS_TO, 'Users', 'publisher_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'                       => 'ID',
                'publisher_id'             => 'User ID',
                'personal_id'              => 'Số CMT/thẻ căn cước',
                'business_license_date'    => ' Ngày cấp CMT/thẻ căn cước',
                'business_license_address' => 'Nơi cấp CMT/thẻ căn cước',
                'resident_address'         => 'Địa chỉ thường trú',
                'address'                  => 'Địa chỉ hiện tại',
                'province_code'            => 'Tỉnh thành',
                'district_code'            => 'Quận/huyện',
                'ward_code'                => 'Phường/xã',
                'representant_mobile'      => 'Điện thoại liên hệ',
                'representant_email'       => 'Email liên hệ',
                'account_number'           => 'Số tài khoản ngân hàng',
                'bank_id'                  => 'Ngân hàng',
                'bank_office'              => 'Chi nhánh ngân hàng',
                'created_date'             => 'Ngày tạo',
                'status'                   => 'Trạng thái',
                'note'                     => 'Ghi chú',
                'last_update'              => 'Cập nhật lần cuối',
                'file_path'                => 'Đường dẫn file word/pdf',
                'account_name'             => 'Tên chủ tài khoản',

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
            $criteria->compare('publisher_id', $this->publisher_id, TRUE);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('business_license_date', $this->business_license_date, TRUE);
            $criteria->compare('business_license_address', $this->business_license_address, TRUE);
            $criteria->compare('resident_address', $this->resident_address, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('province_id', $this->province_code, TRUE);
            $criteria->compare('district_id', $this->district_code, TRUE);
            $criteria->compare('ward_id', $this->ward_code, TRUE);
            $criteria->compare('representant_mobile', $this->representant_mobile, TRUE);
            $criteria->compare('representant_email', $this->representant_email, TRUE);
            $criteria->compare('account_number', $this->account_number, TRUE);
            $criteria->compare('account_name', $this->account_name, TRUE);
            $criteria->compare('bank_id', $this->bank_id, TRUE);
            $criteria->compare('bank_office', $this->bank_office, TRUE);
            $criteria->compare('created_date', $this->created_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('file_path', $this->file_path, TRUE);
//            $criteria->compare('status', $this->status);

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
         * @return ACtvRequestChangePublisherInfo the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getStatus($user_id)
        {
            if ($user_id) {
                $users_request_change = ACtvRequestChangePublisherInfo::model()->findAllByAttributes(array('publisher_id' => $user_id));
                if (isset($users_request_change)) {
                    return $users_request_change->status;
                }
            }

            return "";
        }
    }
