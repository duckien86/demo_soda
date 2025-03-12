<?php

    /**
     * This is the model class for table "{{orders}}".
     *
     * The followings are the available columns in table '{{orders}}':
     *
     * @property string $id
     * @property string $sso_id
     * @property string $promo_code
     * @property string $invitation
     * @property string $create_date
     * @property string $last_update
     * @property string $shipper_id
     * @property string $delivery_type
     * @property string $delivery_date
     * @property string $payment_method
     * @property string $full_name
     * @property string $ward_code
     * @property string $district_code
     * @property string $province_code
     * @property string $address_detail
     * @property string $phone_contact
     * @property string $customer_note
     * @property string $otp
     * @property string $affiliate_transaction_id
     * @property string $affiliate_source
     * @property string $sale_office_code
     * @property string $campaign_source
     * @property string $campaign_id
     * @property string $email
     * @property string $pre_order_date
     * @property string agency_contract_id
     */
    class Orders extends CActiveRecord
    {
        public $active_cod;
        public $sim_type;

        const ACTIVE_COD   = 1;
        const INACTIVE_COD = 0;

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{orders}}';
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
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, sale_office_code, receive_cash_by, full_name, ward_code, address_detail, otp, affiliate_transaction_id, affiliate_source, campaign_source, campaign_id, email', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('package', 'required','on' =>'register_sim','message' => 'Hãy chọn 1 gói cước bất kì'),
                array('customer_note', 'length', 'max' => 500),
                array('agency_contract_id', 'length', 'max' => 100),
                array('create_date, last_update, delivery_date, receive_cash_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, receive_cash_date, receive_cash_by, invitation, create_date, last_update, shipper_id, delivery_type, delivery_date, payment_method, full_name, ward_code, district_code, province_code, address_detail, phone_contact, customer_note, otp, affiliate_transaction_id, affiliate_source, campaign_source, campaign_id, email, sim_type', 'safe', 'on' => 'search'),
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
                'promo_code'               => 'Promo Code',
                'invitation'               => 'Invitation',
                'create_date'              => 'Create Date',
                'last_update'              => 'Last Update',
                'shipper_id'               => 'Shipper',
                'delivery_type'            => 'Delivery Type',
                'delivery_date'            => 'Delivery Date',
                'payment_method'           => 'Payment Method',
                'full_name'                => 'Full Name',
                'ward_code'                => 'Ward Code',
                'district_code'            => 'District Code',
                'province_code'            => 'Province Code',
                'address_detail'           => 'Address Detail',
                'phone_contact'            => 'Phone Contact',
                'customer_note'            => 'Customer Note',
                'otp'                      => 'Otp',
                'affiliate_transaction_id' => 'Affiliate Transaction',
                'affiliate_source'         => 'Affiliate Source',
                'campaign_source'          => 'Campaign Source',
                'campaign_id'              => 'Campaign id',
                'email'                    => 'Email',
                'sim_type'                 => 'Sim Type'

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
            $criteria->compare('promo_code', $this->promo_code, TRUE);
            $criteria->compare('invitation', $this->invitation, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('shipper_id', $this->shipper_id, TRUE);
            $criteria->compare('delivery_type', $this->delivery_type, TRUE);
            $criteria->compare('delivery_date', $this->delivery_date, TRUE);
            $criteria->compare('payment_method', $this->payment_method, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('phone_contact', $this->phone_contact, TRUE);
            $criteria->compare('customer_note', $this->customer_note, TRUE);
            $criteria->compare('otp', $this->otp, TRUE);
            $criteria->compare('affiliate_transaction_id', $this->affiliate_transaction_id, TRUE);
            $criteria->compare('affiliate_source', $this->affiliate_source, TRUE);
            $criteria->compare('campaign_source', $this->campaign_source, TRUE);
            $criteria->compare('campaign_id', $this->campaign_id, TRUE);
            $criteria->compare('email', $this->email, TRUE);

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
         * @return Orders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
