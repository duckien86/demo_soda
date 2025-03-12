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
     */
    class ACtvUserBankAccount extends CActiveRecord
    {

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        const ACTIVE         = 1;
        const INACTIVE       = 0;
        const IS_DEFAULT     = 1;
        const IS_NOT_DEFAULT = 0;

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_user_bank_accounts';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('user_id, bank_id, account_name, account_number, created_on', 'required'),
                array('bank_id, is_default, is_actived', 'numerical', 'integerOnly' => TRUE),
                array('user_id', 'length', 'max' => 128),
                array('account_name', 'length', 'max' => 60),
                array('account_number', 'length', 'max' => 45),
                array('bank_office', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, bank_id, is_default, is_actived, account_name, account_number, bank_office, created_on', 'safe', 'on' => 'search'),
            );
        }


        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'             => 'ID',
                'user_id'        => 'User ID',
                'bank_id'        => 'Tên ngân hàng',
                'is_default'     => 'Mặc định',
                'is_actived'     => 'Kích hoạt',
                'account_name'   => 'Chủ tài khoản',
                'account_number' => 'Số tài khoản',
                'bank_office'    => 'Chi nhánh',
                'created_on'     => 'Ngày tạo',
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

            $criteria->compare('id', $this->id);
            $criteria->compare('user_id', $this->user_id, TRUE);
            $criteria->compare('bank_id', $this->bank_id);
            $criteria->compare('is_default', $this->is_default);
            $criteria->compare('is_actived', $this->is_actived);
            $criteria->compare('account_name', $this->account_name, TRUE);
            $criteria->compare('account_number', $this->account_number, TRUE);
            $criteria->compare('bank_office', $this->bank_office, TRUE);
            $criteria->compare('created_on', $this->created_on, TRUE);

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
         * @return ACtvUserBankAccount the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $user_id
         * Lấy tên tài khoản.
         *
         * @return string
         */
        public static function getAccountName($user_id)
        {
            if ($user_id) {
                $user_bank = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
                if ($user_bank) {
                    return isset($user_bank->account_name) ? $user_bank->account_name : '';
                }
            }

            return "Không có dữ liệu";
        }

        /**
         * @param $user_id
         * Lấy số tài khoản.
         *
         * @return string
         */
        public static function getAccountNumber($user_id)
        {
            if ($user_id) {
                $user_bank = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
                if ($user_bank) {
                    return isset($user_bank->account_number) ? $user_bank->account_number : '';
                }
            }

            return "Không có dữ liệu";
        }
        /**
         * @param $user_id
         * Lấy chi nhánh tài khoản.
         *
         * @return string
         */
        public static function getBrandBankByUserId($user_id)
        {
            if ($user_id) {
                $user_bank = ACtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
                if ($user_bank) {
                    return isset($user_bank->bank_office) ? $user_bank->bank_office : '';
                }
            }

            return "Không có dữ liệu";
        }
    }
