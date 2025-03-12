<?php

    class WOrders extends Orders
    {
        const VINAPHONE_TELCO = 'VINAPHONE';

        const DELIVERY_TYPE_HOME  = 1;
        const DELIVERY_TYPE_SHOP  = 2;
        const PRICE_SHIP          = 0;
        const PRICE_DISCOUNT_CARD = 0.96;//Phan tram chiet khau khi mua card, topup
        const PROVINCE_CODE_CARD  = '01';//Ma tinh/thanh mac dinh khi mua card, topup
        const ESIM = 1;
        const NOTESIM = 0;
        const COD = 1;

        public $amount;
        public $package;
        public $card;
        public $brand_offices;
        public $status;
        public $price_ship;
        public $captcha;
        public $sim_type;

        public $c_name;
        public $c_phone;
        public $c_email;
        public $c_tax_code;
        public $c_address;
        public $order_einvoice_id;
        public $stb_use;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('full_name, phone_contact, district_code, province_code, address_detail, brand_offices', 'required', 'on' => 'register_sim'),
                array('phone_contact', 'required', 'on' => 'register_package, register_package_rx, register_package_ff', 'message' => Yii::t('web/portal', 'phone_contact_required')),
                array('email', 'required', 'on' => 'buy_card', 'message' => Yii::t('web/portal', 'required_email_buy_card')),
                array('email', 'email', 'on' => 'buy_card'),
                array('phone_contact', 'required', 'on' => 'topup', 'message' => Yii::t('web/portal', 'phone_contact_topup')),
                array('phone_contact', 'msisdn_validation'),
                array('phone_contact', 'checkInfoPhone', 'on' => 'register_package, buy_card, topup, register_package_rx, register_package_ff'),
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, full_name, address_detail, otp, brand_offices, affiliate_transaction_id, affiliate_source, ward_code, campaign_source, campaign_id, email', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('package', 'required','on' =>'register_sim','message' => 'Hãy chọn 1 gói cước bất kì'),
                array('customer_note', 'length', 'max' => 500),
                array('price_ship', 'length', 'max' => 11),
                array('promo_code', 'checkCouponCode'),
                array('ward_code', 'checkRequiredWardCode', 'on' => 'register_sim'),
                array('sim_type', 'required', 'on' => 'register_sim'),
                array('last_update', 'safe'),
                array('captcha', 'verifyCaptcha', 'on' => 'register_package_rx, register_package, register_package_ff'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, invitation, create_date, last_update, shipper_id, delivery_type, payment_method, full_name, ward_code, district_code, province_code, address_detail, phone_contact, customer_note, otp, affiliate_transaction_id, affiliate_source, campaign_source, campaign_id, email,sim_type,package', 'safe', 'on' => 'search'),
            );
        }

        public function beforeValidate()
        {
            if($this->scenario == 'register_sim'){
                if(empty($this->sim_type)){
                    $this->sim_type = WOrders::NOTESIM;
                }
            }
            if(!empty($this->phone_contact)){
                $msisdn = $this->phone_contact;
                if(is_numeric($msisdn)){
                    $msisdn_old = CFunction::makePhoneNumberStandard($msisdn);
                    $msisdn_new = CFunction::convertNewMsisdn($msisdn, true , false);
                    if($msisdn_new != $msisdn_old){
                        if(substr($msisdn, 0, 1) == '0'){
                            $msisdn_new = CFunction::makePhoneNumberBasic($msisdn_new);
                        }
                        $this->phone_contact = $msisdn_new;
                    }
                }
            }

            return TRUE;
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function verifyCaptcha($attribute, $params)
        {
            if (!Utils::googleVerify(Yii::app()->params->secret_key)) {
                $msg = Yii::t('web/portal', 'captcha_error');
                $this->addError($attribute, $msg);
            }
        }

        /**
         * @param $haystack
         * @param $needle
         *
         * @return bool
         */
        function startsWith($haystack, $needle)
        {
            $length = strlen($needle);

            return (substr($haystack, 0, $length) === $needle);
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkCouponCode($attribute, $params)
        {
            if ($this->$attribute) {
                $str  = $this->$attribute;
                $flag = TRUE;
                //check cookie and user affiliate
                if ($this->startsWith($str, 'P') || $this->startsWith($str, 'AP') || $this->startsWith($str, 'APP')
                    || $this->startsWith($str, 'p') || $this->startsWith($str, 'ap') || $this->startsWith($str, 'app')
                ) {
                    $pattern = '/^(P|AP|APP|p|ap|app)([0-9]{7})$/';//user code
                    //comment tracking link
//                    if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])
//                        && isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])
////                        && Yii::app()->request->cookies['utm_source']->value == 'freedoo'
//                    ) {//check cookie from affiliate
//                        $flag = FALSE;
//                        $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
//                    } else {
                    //check valid
                    if (!preg_match($pattern, $str)) {
                        $flag = FALSE;
                        $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
                    }
//                    }
                } else {
                    $flag = FALSE;
                    $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
                }

                if ($flag) {
                    $orders_data = new OrdersData();
                    $data_input  = array(
                        'promo_code' => $str
                    );
                    $valid       = $orders_data->checkCouponCode($data_input);
                    if (!$valid) {
                        $this->addError($attribute, Yii::t('web/portal', 'invalid_promo_code'));
                    }
                }
            }
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkInvitation($attribute, $params)
        {
            if ($this->$attribute) {
                $customer = WCustomers::model()->find('username=:username', array(
                    ':username' => $this->$attribute,
                ));
                if (!$customer) {
                    $this->addError($attribute, Yii::t('web/portal', 'cannot_use_invitation'));
                }
            }
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            $short_pattern = "/^0[0-9]{9,10}$/i";
            $full_pattern = "/^84[0-9]{9,10}$/i";
//            $short_pattern = "/^0[0-9]{9}$/i";
//            $full_pattern = "/^84[0-9]{9}$/i";
            if (!empty($this->phone_contact)) {
                $input = $this->phone_contact;
                if (preg_match($short_pattern, $input) == TRUE || preg_match($full_pattern, $input) == TRUE) {
//                    $this->phone_contact = CFunction::convertMsisdn($this->phone_contact);
                    return TRUE;
                } else {
                    $this->addError('phone_contact', Yii::t('web/portal', 'msisdn_validation'));
                    return FALSE;
                }
            }
            return TRUE;
        }

        /**
         * @return bool
         */
        public function detectByTelco()
        {
            if ($this->phone_contact) {
                $telco = Utils::detectTelcoByMsisdn($this->phone_contact);
                if ($telco != self::VINAPHONE_TELCO) {
                    $this->addError('phone_contact', Yii::t('web/portal', 'error_msisdn_vinaphone'));
                }
            }

            return TRUE;
        }

        public function checkInfoPhone()
        {
            $msisdn = $this->phone_contact;
            $data_input = array(
                'so_tb' => $msisdn
            );
            $data_output = Utils::getInfoPhone($data_input);
            if($data_output['code']== -1){
                $this->addError('phone_contact', Yii::t('web/portal', 'error_msisdn_vinaphone'));
            }
            return TRUE;
        }



        /**
         * @param $attribute
         * @param $params
         */
        public function checkRequiredWardCode($attribute, $params)
        {
            if ($this->delivery_type == self::DELIVERY_TYPE_HOME) {
                if (empty($this->$attribute)) {
                    $this->addError($attribute, Yii::t('web/portal', 'err_field_empty', array('{field}' => Yii::t('web/portal', $attribute))));
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
                'detail' => array(self::HAS_MANY, 'WOrderDetails', 'order_id'),
                'state'  => array(self::HAS_MANY, 'WOrderState', 'order_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'                       => Yii::t('web/portal', 'order_id'),
                'sso_id'                   => Yii::t('web/portal', 'sso_id'),
                'promo_code'               => Yii::t('web/portal', 'promo_code'),
                'invitation'               => Yii::t('web/portal', 'invitation'),
                'create_date'              => Yii::t('web/portal', 'create_date'),
                'last_update'              => Yii::t('web/portal', 'last_update'),
                'shipper_id'               => Yii::t('web/portal', 'shipper_id'),
                'delivery_type'            => Yii::t('web/portal', 'delivery_type'),
                'payment_method'           => Yii::t('web/portal', 'payment_method'),
                'personal_id'              => Yii::t('web/portal', 'personal_id'),
                'full_name'                => Yii::t('web/portal', 'full_name_delivery'),
                'district_code'            => Yii::t('web/portal', 'district'),
                'province_code'            => Yii::t('web/portal', 'province'),
                'address_detail'           => Yii::t('web/portal', 'address_detail'),
                'phone_contact'            => Yii::t('web/portal', 'phone_contact'),
                'customer_note'            => Yii::t('web/portal', 'customer_note'),
                'status'                   => Yii::t('web/portal', 'status'),
                'otp'                      => Yii::t('web/portal', 'otp'),
                'package'                  => Yii::t('web/portal', 'list_package'),
                'card'                     => Yii::t('web/portal', 'list_card'),
                'transaction_office'       => Yii::t('web/portal', 'transaction_office'),
                'affiliate_transaction_id' => Yii::t('web/portal', 'affiliate_transaction_id'),
                'affiliate_source'         => Yii::t('web/portal', 'affiliate_source'),
                'ward_code'                => Yii::t('web/portal', 'ward_code'),
                'brand_offices'            => Yii::t('web/portal', 'brand_offices'),
                'campaign_source'          => Yii::t('web/portal', 'campaign_source'),
                'campaign_id'              => Yii::t('web/portal', 'campaign_id'),
                'sim_type'                 => Yii::t('web/portal', 'sim_type'),
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
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('campaign_source', $this->campaign_source, TRUE);
            $criteria->compare('campaign_id', $this->campaign_id, TRUE);

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
         * @return WOrders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * get list status
         *
         * @return array
         */
        public function getAllStatus()
        {
            return array(
                WOrderState::UNDELIVERED => Yii::t('web/portal', 'order_pending'),
                WOrderState::DELIVERED   => Yii::t('web/portal', 'order_complete'),
            );
        }

        /**
         * get label status
         *
         * @param $status
         *
         * @return mixed
         */
        public static function getStatusLabel($status)
        {
            return ($status == WOrderState::DELIVERED) ? Yii::t('web/portal', 'order_complete') : Yii::t('web/portal', 'order_pending');
        }

        /**
         * @param $shipper_id
         *
         * @return string
         */
        public function getShipperName($shipper_id)
        {
            $shipper = array();
            if ($shipper_id) {
                $shipper = WShipper::model()->find('id=:id', array(':id' => $shipper_id));
            }

            return ($shipper) ? CHtml::encode($shipper->full_name) : $shipper_id;
        }

        /**
         * @param $province_code
         *
         * @return string
         */
        public function getProvince($province_code)
        {
            $province = array();
            if ($province_code) {
                $province = WProvince::model()->find('code=:code', array(':code' => $province_code));
            }

            return ($province) ? CHtml::encode($province->name) : $province_code;
        }

        /**
         * @param $district_code
         *
         * @return string
         */
        public function getDistrict($district_code)
        {
            $district = array();
            if ($district_code) {
                $district = WDistrict::model()->find('code=:code', array(':code' => $district_code));
            }

            return ($district) ? CHtml::encode($district->name) : $district_code;
        }

        /**
         * @param $ward_code
         *
         * @return string
         */
        public function getWard($ward_code)
        {
            $ward = array();
            if ($ward_code) {
                $ward = WWard::model()->find('code=:code', array(':code' => $ward_code));
            }

            return ($ward) ? CHtml::encode($ward->name) : $ward_code;
        }

        /**
         * @param           $customer_id
         * @param null      $order_id
         * @param null      $status
         * @param null      $from_date
         * @param null      $to_date
         * @param bool|TRUE $dataProvider
         * @param int       $limit
         * @param int       $offset
         *
         * @return array|CActiveDataProvider
         */
        public function getOrdersByCustomer($customer_id, $order_id = NULL, $status = NULL, $from_date = NULL, $to_date = NULL, $dataProvider = TRUE, $limit = 10, $offset = 0)
        {
            if ($status == '') {
                $status = -1;
            }
            $status = (int)$status;

            $criteria           = new CDbCriteria;
            $criteria->distinct = TRUE;
            $criteria->select   = 't.*, os.delivered as status';

            $criteria->compare('t.id', $order_id, TRUE);
            $criteria->compare('t.sso_id', $customer_id);
            if ($from_date) {
                $criteria->addCondition("'$from_date' <= DATE_FORMAT(t.create_date, '%d/%m/%Y')");
            }

            if ($to_date) {
                $criteria->addCondition("'$to_date' >= DATE_FORMAT(t.create_date, '%d/%m/%Y')");
            }
            $criteria->join = 'LEFT JOIN {{order_state}} os ON os.order_id = t.id';
            switch ($status) {
                case  WOrderState::DELIVERED:
                    $criteria->addCondition('os.id =
                                            (
                                               SELECT MAX(os2.id)
                                               FROM tbl_order_state as os2
                                               WHERE os2.order_id = t.id
                                            ) AND os.delivered="' . $status . '"
                ');
                    break;
                case  WOrderState::UNDELIVERED:
                    $criteria->addCondition('os.id =
                                            (
                                               SELECT MAX(os2.id)
                                               FROM tbl_order_state as os2
                                               WHERE os2.order_id = t.id
                                            ) AND
                                            (os.delivered="' . $status . '" OR os.delivered=""  OR os.delivered IS NULL)
                ');
                    break;
                default:
                    $criteria->addCondition('os.id =
                                            (
                                               SELECT MAX(os2.id)
                                               FROM tbl_order_state as os2
                                               WHERE os2.order_id = t.id
                                            )
                ');
                    break;

            }

            if ($dataProvider) {
                return new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 't.create_date DESC'),
                    'pagination' => array(
                        'pageSize' => $limit,
                    ),
                ));
            } else {
                $criteria->limit  = $limit;
                $criteria->offset = $offset;
                $criteria->order  = 't.create_date DESC';
                $results          = self::model()->findAll($criteria);

                return $results;
            }
        }

        /**
         * @return string
         */
        public function generateOrderId()
        {
            return Utils::generateRandomString(11, TRUE);
        }


        public function getOrderAmountByOrderId($order_id)
        {
            $order_details = WOrderDetails::getOrderDetailsByOrderId($order_id);
            $amount        = $this->getOrderAmount($order_details);

            return $amount;
        }

        /**
         * @param $orders_data
         *
         * @return int
         */
        public function getOrderAmount($orders_data)
        {

            $modelOrderNew                = new WOrders();
            $modelSimNew                  = new WSim();
            $modelOrderNew->delivery_type = $orders_data->orders->delivery_type;
            $modelSimNew->msisdn          = $orders_data->sim->msisdn;
            $modelSimNew->type            = $orders_data->sim->type;
            // tinh gia
            $amount = $this->calculatePrice($modelOrderNew, $modelSimNew, $orders_data->sim->raw_data, $orders_data->package, $orders_data);

            return $amount;
        }

        /**
         * @param WOrders $modelOrder
         * @param WSim    $modelSim
         * @param         $sim_raw_data
         * @param         $modelPackage
         * @param         $orders_data
         *
         * @return int
         */
        public function calculatePrice(WOrders &$modelOrder, WSim &$modelSim, $sim_raw_data, $modelPackage, $orders_data)
        {
            $modelSim->price_term = (int)$sim_raw_data['price_term'];
            $modelSim->price      = (int)$sim_raw_data['price'];
            $amount               = 0;
            // tinh phi ship
            $modelOrder->price_ship = 0;
            if (isset($modelOrder->delivery_type)) {
                if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
                    $amount                 = $GLOBALS['config_common']['order']['price_ship'];
                    $modelOrder->price_ship = $GLOBALS['config_common']['order']['price_ship'];
                } else {
                    $modelOrder->price_ship = 0;
                }
            }
            // tinh phi sim tra truoc || tra sau
            if (isset($modelSim->type)) {
//                if ($modelSim->type != $sim_raw_data['msisdn_type']) {
//                    if ($modelSim->type == WSim::TYPE_PREPAID) {
//                        $amount          += (int)$sim_raw_data['price'] - (int)Yii::app()->params->prepaid_postpaid_price;
//                        $modelSim->price = (int)$sim_raw_data['price'] - (int)Yii::app()->params->prepaid_postpaid_price;
//                    } else {
//                        $amount          += (int)$sim_raw_data['price'] + (int)Yii::app()->params->prepaid_postpaid_price;
//                        $modelSim->price = (int)$sim_raw_data['price'] + (int)Yii::app()->params->prepaid_postpaid_price;
//                    }
//                } else {
//                    $amount          += (int)$sim_raw_data['price'];
//                    $modelSim->price = (int)$sim_raw_data['price'];
//                }
                //get WSim info from raw data
                $this->getSimInRawData($modelSim->msisdn, $modelSim->type, $modelSim->price, $orders_data->sim_raw_data, $modelSim);
                $amount += (int)$modelSim->price;
            } else {
                $amount          += (int)$sim_raw_data['price'];
                $modelSim->price = (int)$sim_raw_data['price'];
                $modelSim->type  = $sim_raw_data['msisdn_type'];
            }

            // tinh goi cuoc va phi cam ket
            if ($modelPackage) {
                //check price_discount
                if ($modelPackage->price_discount > 0) {
                    $modelPackage->price = $modelPackage->price_discount;
                } elseif ($modelPackage->price_discount == -1) {
                    $modelPackage->price = 0;
                }

                if (isset($sim_raw_data['price_term']) && $sim_raw_data['price_term'] > 0) {
                    $amount += (int)$sim_raw_data['price_term'];
//                    $modelPackage->price = 0;//comment 17.01.2018
                    /*if ($sim_raw_data['price_term'] <= Yii::app()->params->min_free_price_term) {
                        $amount               += (int)$modelPackage->price;
                        $modelSim->price_term = 0;
                    } else {
                        if ($sim_raw_data['price_term'] > (int)$modelPackage->price) {
                            $amount              += (int)$sim_raw_data['price_term'];
                            $modelPackage->price = 0;
                        } else {
                            $amount               += (int)$modelPackage->price;
                            $modelSim->price_term = 0;
                        }
                    }*/
                } else {
                    if ($modelSim->type == WSim::TYPE_POSTPAID) {
//                        $modelPackage->price = 0;//comment 17.01.2018
                    } else {
                        $amount += (int)$modelPackage->price;
                    }
                }
            } else { // neu ko mua kem goi cuoc thi cong them tien cam ket
                $amount += (int)$sim_raw_data['price_term'];
            }

            return $amount;
        }

        /**
         * @param $msisdn
         * @param $sim_type
         * @param $price
         * @param $sim_raw_data_arr
         * @param $sim
         */
        public function getSimInRawData($msisdn, $sim_type, $price, $sim_raw_data_arr, &$sim)
        {
            foreach ($sim_raw_data_arr as $sim_raw) {
                if ((isset($sim_raw['msisdn']) && $sim_raw['msisdn'] == $msisdn)
                    && (isset($sim_raw['msisdn_type']) && $sim_type == $sim_raw['msisdn_type'])
                    && (isset($sim_raw['price']) && $price == $sim_raw['price'])
                ) {
                    $sim->msisdn     = $sim_raw['msisdn'];
                    $sim->price      = $sim_raw['price'];
                    $sim->type       = $sim_raw['msisdn_type'];
                    $sim->term       = $sim_raw['term'];
                    $sim->price_term = $sim_raw['price_term'];
                    $sim->store_id   = (string)$sim_raw['store'];
                    $sim->raw_data   = $sim_raw;
                }
            }
        }

        /**
         * @param string $operation
         *
         * @return bool
         */
        public static function checkOrdersSessionExists($operation = OrdersData::OPERATION_BUYSIM)
        {
            $flag = FALSE;
            if ((time() - Yii::app()->session['session_cart']) < Yii::app()->params['sessionTimeout']
                && isset(Yii::app()->session['orders_data']) && !empty(Yii::app()->session['orders_data'])
                && isset(Yii::app()->session['orders_data']->operation)
                && (Yii::app()->session['orders_data']->operation == $operation)
            ) {
                $flag = TRUE;
            }

            return $flag;
        }

        /**
         * @param string $operation
         *
         * @return bool
         */
        public static function checkOrdersSessionExistsApi($operation = OrdersData::OPERATION_BUYSIM)
        {
            $flag = FALSE;
            if ((time() - Yii::app()->session['session_cart']) < Yii::app()->params['sessionTimeoutApi']
                && isset(Yii::app()->session['orders_data']) && !empty(Yii::app()->session['orders_data'])
                && isset(Yii::app()->session['orders_data']->operation)
                && (Yii::app()->session['orders_data']->operation == $operation)
            ) {
                $flag = TRUE;
            }

            return $flag;
        }
        /**
         * @param WOrderDetails $orderDetails
         *
         * @return int
         */
        public function getCardOrderAmount(WOrderDetails $orderDetails)
        {
            $amount = 0;
            if ($orderDetails) {
                $amount = (int)($orderDetails->price * $orderDetails->quantity);
            }

            return $amount;
        }

        public function getAddress(){
            $province = (!empty($this->province_code)) ? WProvince::getProvinceNameByCode($this->province_code) : "";
            $district = (!empty($this->district_code)) ? WDistrict::getDistrictNameByCode($this->district_code) : "";
            $ward = (!empty($this->ward_code)) ? WWard::getWardNameByCode($this->ward_code) : "";
            if ($this->delivery_type == WOrders::COD) {
                $address = $this->address_detail . " -- " . $ward . " -- " . $district . " -- " . $province;
            } else {
                $address = $district . " -- " . $province;
            }
            return $address;
        }

        public static function getExportedInvOrder($item_numbers, $order_id = false){
//            $now = date('Y-m-d');
            $now = '2019-01-28';
            $criteria = new CDbCriteria;
            $criteria->select = 'os.order_id as id, os.delivered, os.create_date 
            ,oe.id as order_einvoice_id, oe.c_name, oe.c_phone, oe.c_email, oe.c_tax_code, oe.c_address
            , t.sso_id, t.province_code';
            $criteria->join = 'JOIN {{order_state}} os ON t.id = os.order_id 
            JOIN {{order_einvoice}} oe ON t.id = oe.order_id';
            if($order_id){
                $criteria->condition = 't.id =:order_id';
                $criteria->params = array(
                    ':order_id'  => $order_id,
                );
                $order = WOrders::model()->find($criteria);
            }else{
                $criteria->condition = 'os.create_date >= :create_date 
                                    AND os.delivered =:delivered
                                    AND oe.status =:status 
                                    AND os.id = (SELECT MAX(id) from {{order_state}} as n_os WHERE n_os.order_id = t.id)';
                $criteria->order = 'os.create_date';
                $criteria->params = array(
                    ':create_date' =>$now,
                    ':delivered' =>WOrderState::DELIVERED,
                    ':status' => WOrderEinvoice::INVOICING,
                );

                if($item_numbers == 'all'){
                    $criteria->limit = '10';
                    $order = WOrders::model()->findAll($criteria);
                }else{
                    $order = WOrders::model()->find($criteria);
                }
            }
            return $order;
        }

        public static function setXmlInvData($model, $adjust = false, $fkey = ''){
            $invoice = new EBInvoices();
            $invoice->cus_name = $model->c_name;
            $invoice->cus_phone = $model->c_phone;
            $invoice->cus_tax_code = $model->c_tax_code;
            $invoice->cus_address = $model->c_address;
            $invoice->cus_code = $model->id;
            $invoice->products = array();
            $invoice->key = EBInvoices::FREEDOO_INV_KEY.Province::getProvinceVnpByCode($model->province_code).$model->id;
            $invoice->total = 0;
            $order_details = WOrderDetails::model()->findAllByAttributes(array('order_id' => $model->id));
            foreach ($order_details as $detail){
                $product = new EBProducts();
                $product->prod_name = $detail->item_name;
                $product->prod_unit = '';
                $product->prod_price = $detail->price;
                $product->prod_quantity = $detail->quantity;
                $product->amount = $detail->price*$detail->quantity;
                $invoice->total += $product->amount;
                $invoice->products[] = $product;
            }
            $invoice->vat_rate = 0;
            $invoice->vat_amount = 0*total;
            $invoice->amount = $invoice->vat_amount + $invoice->total;
            $invoice->amount_in_words = ucfirst(strtolower(Utils::convert_number_to_words($invoice->amount))).' đồng';

            // chỉnh sửa hóa đơn
            if($adjust){
                $xmlData = EBInvoices::parserAdjustToXml($invoice, $fkey);
            }else{ // phát hành hóa đơn
                $xmlData = EBInvoices::parserToXml(array($invoice));
            }
            return $xmlData;
        }
    }
