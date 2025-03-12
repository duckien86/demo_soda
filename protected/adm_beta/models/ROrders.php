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
     * @property string $district_code
     * @property string $province_code
     * @property string $address_detail
     * @property string $phone_contact
     * @property string $customer_note
     * @property string $otp
     * @property string $affiliate_transaction_id
     * @property string $affiliate_source
     */
    class ROrders extends Orders
    {

        public $order_type;
        public $price;
        public $item_id;
        public $paid_date;
        public $note;

        public $total_sim;
        public $type_sim;

        public $customer_msisdn;
        public $customer_name;

        public $item_name;
        public $total_package;
        public $renueve_package;
        public $renueve_sim;

        public $sim; // Số thuê bao
        public $delivered_date; // Số thuê bao

        public $total_card;
        public $renueve_card;
        public $total;
        public $renueve;
        public $type; // Hình thức sản phẩm.
        public $status; // Trạng thái cuối cùng.

        public $capacity_call_int; //Dung lượng gói linh hoạt.
        public $capacity_call_ext; //Dung lượng gói linh hoạt.
        public $capacity_sms_int; //Dung lượng gói linh hoạt.
        public $capacity_sms_ext; //Dung lượng gói linh hoạt.
        public $capacity_data; //Dung lượng gói linh hoạt.

        public $renueve_term; //Dung lượng gói linh hoạt.

        public $paid;
        public $confirm;
        public $delivered;
        public $date;


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
                array('sso_id, promo_code, invitation, full_name, address_detail, otp, affiliate_transaction_id, affiliate_source', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('customer_note', 'length', 'max' => 500),
                array('create_date, last_update, delivery_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, invitation, create_date, last_update, shipper_id, delivery_type, delivery_date, payment_method, full_name, district_code, province_code, address_detail, phone_contact, customer_note, otp, affiliate_transaction_id, affiliate_source', 'safe', 'on' => 'search'),
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
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('phone_contact', $this->phone_contact, TRUE);
            $criteria->compare('customer_note', $this->customer_note, TRUE);
            $criteria->compare('otp', $this->otp, TRUE);
            $criteria->compare('affiliate_transaction_id', $this->affiliate_transaction_id, TRUE);
            $criteria->compare('affiliate_source', $this->affiliate_source, TRUE);

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
