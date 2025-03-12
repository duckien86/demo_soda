<?php

    /**
     * This is the model class for table "{{location_napas}}".
     *
     * The followings are the available columns in table '{{location_napas}}':
     *
     * @property string $id
     * @property string $vpc_AccessCode
     * @property string $vpc_Merchant
     * @property string $secure_secret
     * @property string $end_point
     * @property string $bank_account
     * @property string $bank_name
     */
    class LocationNapas extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{location_napas}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id', 'required'),
                array('id, vpc_AccessCode, vpc_Merchant, secure_secret, end_point, bank_account, bank_name', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, vpc_AccessCode, vpc_Merchant, secure_secret, end_point, bank_account, bank_name', 'safe', 'on' => 'search'),
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
                'id'             => 'ID',
                'vpc_AccessCode' => 'Vpc Access Code',
                'vpc_Merchant'   => 'Vpc Merchant',
                'secure_secret'  => 'Secure Secret',
                'end_point'      => 'End Point',
                'bank_account'   => 'Bank Account',
                'bank_name'      => 'Bank Name',
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
            $criteria->compare('vpc_AccessCode', $this->vpc_AccessCode, TRUE);
            $criteria->compare('vpc_Merchant', $this->vpc_Merchant, TRUE);
            $criteria->compare('secure_secret', $this->secure_secret, TRUE);
            $criteria->compare('end_point', $this->end_point, TRUE);
            $criteria->compare('bank_account', $this->bank_account, TRUE);
            $criteria->compare('bank_name', $this->bank_name, TRUE);

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
         * @return LocationNapas the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
