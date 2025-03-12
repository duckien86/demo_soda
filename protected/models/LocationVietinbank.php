<?php

    /**
     * This is the model class for table "{{location_vietinbank}}".
     *
     * The followings are the available columns in table '{{location_vietinbank}}':
     *
     * @property string $id
     * @property string $access_key
     * @property string $profile_id
     * @property string $secret_key
     * @property string $end_point
     * @property string $qr_code_merchant_id
     * @property string $vnp_TmnCode
     * @property string $vnp_hashSecret
     * @property string $vnp_end_point
     * @property string $olpay_merchantId
     * @property string $olpay_providerId
     * @property string $pServiceCode
     * @property string $pProviderId
     * @property string $pMerchantId
     * @property string $pEnd_point
     */
    class LocationVietinbank extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{location_vietinbank}}';
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
                array('id, access_key, profile_id, end_point, qr_code_merchant_id, vnp_TmnCode, vnp_hashSecret, vnp_end_point, olpay_merchantId, olpay_providerId, pServiceCode, pProviderId, pMerchantId, pEnd_point', 'length', 'max' => 255),
                array('secret_key', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, access_key, profile_id, secret_key, end_point, qr_code_merchant_id, vnp_TmnCode, vnp_hashSecret, vnp_end_point, olpay_merchantId, olpay_providerId, pServiceCode, pProviderId, pMerchantId, pEnd_point', 'safe', 'on' => 'search'),
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
                'id'                  => 'ID',
                'access_key'          => 'Access Key',
                'profile_id'          => 'Profile',
                'secret_key'          => 'Secret Key',
                'end_point'           => 'End Point',
                'qr_code_merchant_id' => 'Qr Code Merchant',
                'vnp_TmnCode'         => 'Vnp Tmn Code',
                'vnp_hashSecret'      => 'Vnp Hash Secret',
                'vnp_end_point'       => 'Vnp End Point',
                'olpay_merchantId'    => 'Olpay Merchant Id',
                'olpay_providerId'    => 'Olpay Provider Id',
                'pServiceCode'        => 'pServiceCode',
                'pProviderId'         => 'pProviderId',
                'pMerchantId'         => 'pMerchantId',
                'pEnd_point'          => 'pEnd_point',
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
            $criteria->compare('access_key', $this->access_key, TRUE);
            $criteria->compare('profile_id', $this->profile_id, TRUE);
            $criteria->compare('secret_key', $this->secret_key, TRUE);
            $criteria->compare('end_point', $this->end_point, TRUE);
            $criteria->compare('qr_code_merchant_id', $this->qr_code_merchant_id, TRUE);
            $criteria->compare('vnp_TmnCode', $this->vnp_TmnCode, TRUE);
            $criteria->compare('vnp_hashSecret', $this->vnp_hashSecret, TRUE);
            $criteria->compare('vnp_end_point', $this->vnp_end_point, TRUE);
            $criteria->compare('olpay_merchantId', $this->olpay_merchantId, TRUE);
            $criteria->compare('olpay_providerId', $this->olpay_providerId, TRUE);
            $criteria->compare('pServiceCode', $this->pServiceCode, TRUE);
            $criteria->compare('pProviderId', $this->pProviderId, TRUE);
            $criteria->compare('pMerchantId', $this->pMerchantId, TRUE);
            $criteria->compare('pEnd_point', $this->pEnd_point, TRUE);

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
         * @return LocationVietinbank the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
