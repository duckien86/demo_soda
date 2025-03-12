<?php

    class CheckoutForm extends CFormModel
    {
        public $sim_number;
        public $sim_price;
        public $sim_type;
        public $sim_term;
        public $sim_priceterm;
        public $sim_store;
        public $transaction_id;
        public $channel;
        public $full_name;
        public $phone_contact;
        public $delivery_type;
        public $province_code;
        public $district_code;
        public $ward_code;
        public $address_detail;
        public $customer_note;
        public $package_id;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                array('sim_number, sim_price, sim_type, sim_term, sim_priceterm, sim_store, transaction_id, 
                channel, full_name, phone_contact, delivery_type, province_code, district_code, ward_code, 
                address_detail, package_id', 'required'),
                array('phone_contact', 'msisdn_validation'),
                array('sim_type', 'sim_type_package_validation'),
            );
        }

        /**
         * Declares customized attribute labels.
         * If not declared here, an attribute would have a label that is
         * the same as its name with the first letter in upper case.
         */
        public function attributeLabels()
        {
            return array(
                
            );
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            $short_pattern = "/^0[0-9]{9,10}$/i";
            $full_pattern = "/^84[0-9]{9,10}$/i";
            if (!empty($this->phone_contact)) {
                $input = $this->phone_contact;
                if (preg_match($short_pattern, $input) == TRUE || preg_match($full_pattern, $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('phone_contact', Yii::t('web/portal', 'msisdn_validation'));
                    return FALSE;
                }
            }
            return TRUE;
        }
        /**
         * điều kiện chỉ được mua sim trả trước kèm gói
         * @return bool
         */
        public function sim_type_package_validation(){
            if ($this->sim_type == '2') {
                $this->addError('sim_type', 'Không được mua số trả sau');
                return FALSE;
            }
            return TRUE;
        }
    }