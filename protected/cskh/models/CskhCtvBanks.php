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
    class CskhCtvBanks extends CActiveRecord
    {

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_banks';
        }


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('bank_name', 'required'),
                array('bank_name, bank_address', 'length', 'max' => 255),
                array('swift_code', 'length', 'max' => 45),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, bank_name, bank_address, swift_code', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                'user_bank_account' => array(self::HAS_MANY, 'UserBankAccounts', 'bank_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'           => 'ID',
                'bank_name'    => 'Tên ngân hàng',
                'bank_address' => 'Địa chỉ',
                'swift_code'   => 'Swift Code',
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
            $criteria->compare('bank_name', $this->bank_name, TRUE);
            $criteria->compare('bank_address', $this->bank_address, TRUE);
            $criteria->compare('swift_code', $this->swift_code, TRUE);

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
         * @return CskhCtvBanks the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getNameByUserId($user_id)
        {
            if ($user_id) {
                $user_bank = CskhCtvUserBankAccount::model()->findByAttributes(array('user_id' => $user_id));
                if (isset($user_bank->bank_id)) {
                    if (!empty($user_bank->bank_id)) {
                        $banks = CskhCtvBanks::model()->findByAttributes(array('id' => $user_bank->bank_id));
                        if (isset($banks->bank_name)) {
                            return $banks->bank_name;
                        }
                    }
                }
            }

            return "Không có dữ liệu";
        }

    }
