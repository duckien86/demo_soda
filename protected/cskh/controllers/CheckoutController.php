<?php

    class CheckoutController extends AController
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
        }

        /**
         * @return array action filters
         */
        public function filters()
        {
            return array(
//			'accessControl', // perform access control for CRUD operations
                //'postOnly + delete', // we only allow deletion via POST request
                'rights', // perform access control for CRUD operations
            );
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         *
         * @return array access control rules
         */
        public function accessRules()
        {
            return array(
                array('allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => array('index', 'view'),
                    'users'   => array('*'),
                ),
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => array('create', 'update'),
                    'users'   => array('@'),
                ),
                array('allow', // allow admin user to perform 'admin' and 'delete' actions
                    'actions' => array('admin', 'delete'),
                    'users'   => array('admin'),
                ),
                array('deny',  // deny all users
                    'users' => array('*'),
                ),
            );
        }

        /**
         * Performs the AJAX validation.
         *
         * @param $model
         *
         * @return string
         */
        protected function performAjaxValidation($model)
        {
            $msg = '';
            if (isset($_POST['WOrders'])) {
                $msg = CActiveForm::validate($model);
            }

            return CJSON::decode($msg);
        }

        /**
         * action cart
         */
        public function actionCart()
        {
            $orders = WOrders::getOrdersByCustomer(1, TRUE);
            $this->render('cart', array(
                'orders' => $orders,
            ));
        }

        /**
         * action get list district by province
         */
        public function actionGetDistrictByProvince()
        {
            $province_code = Yii::app()->request->getParam('province_code', '');
            $district      = CskhDistrict::model()->findAll('province_code=:province_code', array(':province_code' => $province_code));
            $district      = CHtml::listData($district, 'code', 'name');
            echo "<option value=''>" . Yii::t('web/portal', 'select_district') . "</option>";
            foreach ($district as $key => $value) {
                echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }
            Yii::app()->end();
        }

        /**
         * action get list brand_offices by province, district
         */
        public function actionGetBrandOfficesByDistrict()
        {
            $district_code = Yii::app()->request->getParam('district_code', '');
            $district      = CskhDistrict::model()->find('code=:code', array(':code' => $district_code));
            $brand_offices = CskhBrandOffices::getListBrandOffices($district->province_code, $district_code);
            echo "<option value=''>" . Yii::t('web/portal', 'brand_offices') . "</option>";
            foreach ($brand_offices as $key => $value) {
                echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }
            Yii::app()->end();
        }

        /**
         * actionCheckout
         */
        public function actionCheckout()
        {
            if (((time() - Yii::app()->session['session_cart']) / 60) >= Yii::app()->params['sessionTimeout']
                || !isset(Yii::app()->session['data_orders'])
                || empty(Yii::app()->session['data_orders'])
            ) {

                OtpForm::unsetSession();
                $msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
            } else {

                $orders_data   = Yii::app()->session['data_orders'];
                $modelOrder    = $orders_data->orders;
                $modelSim      = $orders_data->sim;
                $package       = CskhPackage::model()->findAll('status=:status', array(':status' => CskhPackage::PACKAGE_ACTIVE));
                $package       = CHtml::listData($package, 'id', 'name');
                $card          = CskhCard::getListCard();
                $card          = CHtml::listData($card, 'id', 'price');
                $province      = CskhProvice::model()->findAll();
                $province      = CHtml::listData($province, 'code', 'name');
                $district      = CskhDistrict::model()->findAll();
                $district      = CHtml::listData($district, 'code', 'name');
                $brand_offices = CskhBrandOffices::getListBrandOffices();

                $modelOrder->scenario      = 'register_sim';
                $modelOrder->delivery_type = CskhOrders::DELIVERY_TYPE_HOME;//set default

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_step1') {
                    echo CActiveForm::validate($modelSim);
                    Yii::app()->end();
                }

                if (isset($_POST['CskhSim']) && isset($_POST['CskhOrders'])) {

                    $modelSim->attributes   = $_POST['CskhSim'];
                    $modelOrder->attributes = $_POST['CskhOrders'];

                    $modelOrder->full_name = $modelSim->full_name;
                    if ($modelOrder->address_detail) {
                        $modelOrder->brand_offices = $modelOrder->address_detail;
                    } else {
                        $modelOrder->address_detail = $modelOrder->brand_offices;
                    }

                    if ($modelSim->validate() && $modelOrder->validate()) {
                        $orders_data->sim    = $modelSim;
                        $orders_data->orders = $modelOrder;

                        //add order detail with package
                        $package_id = '';
                        if (isset($_POST['CskhOrders']['package']) && is_array($_POST['CskhOrders']['package']) && !empty($_POST['CskhOrders']['package'])) {
                            $package = $_POST['CskhOrders']['package'];
                            if (isset($package[0]) && !empty($package[0])) {
                                $package_id = $package[0];
                            }
                        }

                        $modelPackage = CskhPackage::model()->find('id=:id', array(':id' => $package_id));
                        if ($modelPackage) {
                            $order_details_pack                    = new CskhOrderDetails();
                            $order_details_pack->order_id          = $modelOrder->id;
                            $order_details_pack->item_name         = $modelPackage->name;
                            $order_details_pack->price             = $modelPackage->price;
                            $order_details_pack->quantity          = 1;
                            $order_details_pack->type              = CskhOrderDetails::TYPE_PACKAGE;
                            $orders_data->order_details['package'] = $order_details_pack->attributes;
                        }

                        //add order detail with card
                        $card_id = '';
                        if (isset($_POST['CskhOrders']['card']) && is_array($_POST['CskhOrders']['card']) && !empty($_POST['CskhOrders']['card'])) {
                            $card = $_POST['CskhOrders']['card'];
                            if (isset($card[0]) && !empty($card[0])) {
                                $card_id = $card[0];
                            }

                        }
                        $modelCard = CskhCard::model()->find('id=:id', array(':id' => $card_id));
                        if ($modelCard) {
                            $order_details_card                 = new CskhOrderDetails();
                            $order_details_card->order_id       = $modelOrder->id;
                            $order_details_card->item_name      = $modelCard->serial_number;
                            $order_details_card->price          = $modelCard->price;
                            $order_details_card->quantity       = 1;
                            $order_details_card->type           = CskhOrderDetails::TYPE_CARD;
                            $orders_data->order_details['card'] = $order_details_card->attributes;
                        }
                        //set session Order
                        Yii::app()->session['data_orders'] = $orders_data;
                        if ($orders_data) {
                            $check_sale = SupportSale::model()->findByAttributes
                            (array('user_id' => Yii::app()->user->id, 'order_id' => $orders_data->orders->id));
                            if (!$check_sale) {
                                $suppost_sale           = new SupportSale();
                                $suppost_sale->order_id = $orders_data->orders->id;
                                $suppost_sale->user_id  = Yii::app()->user->id;
                                if ($suppost_sale->save()) {
                                    echo "<script type='text/javascript'>alert('Đặt hàng thành công!');</script>";
                                }
                            } else {
                                echo "<script type='text/javascript'>alert('Bạn đã đặt đơn này rồi!');</script>";
                            }
                        }
//                        $this->redirect($this->createUrl('checkout/checkout2'));
                    }
                }

                $this->render('checkout_step1', array(
                    'modelSim'      => $modelSim,
                    'modelOrder'    => $modelOrder,
                    'package'       => $package,
                    'card'          => $card,
                    'province'      => $province,
                    'district'      => $district,
                    'brand_offices' => $brand_offices,
                ));
            }
        }

        /**
         * actionCheckout step 2
         */
        public function actionCheckout2()
        {

            if (((time() - Yii::app()->session['session_cart']) / 60) >= Yii::app()->params['sessionTimeout']
                || !isset(Yii::app()->session['data_orders'])
                || empty(Yii::app()->session['data_orders'])
            ) {
                OtpForm::unsetSession();
                $msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
            } else {
                $orders_data    = Yii::app()->session['data_orders'];
                $orders         = $orders_data->orders;
                $order_details  = $orders_data->order_details;
                $payment_method = WPaymentMethod::getListPaymentMethod();
                if (isset($_POST['PaymentMethod'])) {
                    $model_pm = WPaymentMethod::model()->find('id=:id', array(':id' => $_POST['PaymentMethod']));
                    if ($model_pm) {
                        $orders->payment_method = $model_pm->id;//payment_method
                        $arr_result             = array('urlRequest' => '', 'msg' => '');
                        $time                   = time();

                        //amount
                        $vpc_Amount = 0;
                        foreach ($order_details as $item) {
                            $vpc_Amount += (int)($item['price'] * $item['quantity']);
                        }

                        Yii::app()->session['phone_contact'] = $orders->phone_contact;//test
                        if ((@parse_ini_string($model_pm->config_param, TRUE)) == TRUE) {
                            $config_param = parse_ini_string($model_pm->config_param, TRUE);
                            if (isset($config_param['model']) && class_exists($config_param['model'])) {
                                $class                       = $config_param['model'];
                                $model_name                  = new $class();
                                $model_name->vpc_Version     = '2.0';
                                $model_name->vpc_Command     = 'pay';
                                $model_name->vpc_AccessCode  = 'ECAFAB';
                                $model_name->vpc_MerchTxnRef = 'Centech_test_' . $time;
                                $model_name->vpc_Merchant    = 'SMLTEST';
                                $model_name->vpc_OrderInfo   = $orders->id;
                                $model_name->vpc_Amount      = $vpc_Amount;
                                $model_name->vpc_ReturnURL   = 'http://localhost/vnpt_online/portal/source/checkout/response';
                                $model_name->vpc_BackURL     = 'http://localhost/vnpt_online/portal/source/checkout/checkout';
//                                $model_name->vpc_ReturnURL      = 'http://10.2.0.107:8694/vnpt_online/portal/source/checkout/response';
//                                $model_name->vpc_BackURL        = 'http://10.2.0.107:8694/vnpt_online/portal/source/checkout/checkout';
                                $model_name->vpc_Locale         = 'vn';
                                $model_name->vpc_CurrencyCode   = 'VND';
                                $model_name->vpc_TicketNo       = '127.0.0.1';
                                $model_name->vpc_PaymentGateway = '';//INT || ATM
                                $model_name->vpc_CardType       = '';//SML
                                $req_ary_param                  = array(
                                    'vpc_Amount'         => $model_name->vpc_Amount,
                                    'vpc_Version'        => $model_name->vpc_Version,
                                    'vpc_OrderInfo'      => $model_name->vpc_OrderInfo,
                                    'vpc_Command'        => $model_name->vpc_Command,
                                    'vpc_Currency'       => $model_name->vpc_CurrencyCode,
                                    'vpc_Merchant'       => $model_name->vpc_Merchant,
                                    'vpc_BackURL'        => $model_name->vpc_BackURL,
                                    'vpc_ReturnURL'      => $model_name->vpc_ReturnURL,
                                    'vpc_AccessCode'     => $model_name->vpc_AccessCode,
                                    'vpc_MerchTxnRef'    => $model_name->vpc_MerchTxnRef,
                                    'vpc_TicketNo'       => $model_name->vpc_TicketNo,
                                    'vpc_PaymentGateway' => $model_name->vpc_PaymentGateway,
                                    'vpc_CardType'       => $model_name->vpc_CardType,
                                    'vpc_Locale'         => $model_name->vpc_Locale,
                                );

                                $arr_result = $model_name->{$config_param['function']}($req_ary_param);
                            }
                        }
                        $orders_data->orders = $orders;
                        //set session
                        Yii::app()->session['data_orders'] = $orders_data;

                        Yii::app()->user->setFlash('success', $arr_result['msg']);
                        $this->redirect($arr_result['urlRequest']);
                    }
                }

                $this->render('checkout_step2', array(
                    'payment_method' => $payment_method,
                    'modelOrder'     => $orders_data->orders,
                    'modelSim'       => $orders_data->sim,
                ));
            }
        }

        /**
         * action verify token key (check token key)
         * insert order
         */
        public function actionVerifyTokenKey()
        {
            if (((time() - Yii::app()->session['session_cart']) / 60) >= Yii::app()->params['sessionTimeout']
                || !isset(Yii::app()->session['data_orders'])
                || empty(Yii::app()->session['data_orders'])
            ) {
                OtpForm::unsetSession();
                $msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
            } else {
                $otpModel           = new OtpForm();
                $otpModel->scenario = 'checkTokenKey';
                $msg                = '';

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'otp_form') {
                    echo CActiveForm::validate($otpModel);
                    Yii::app()->end();
                }

                if (isset($_POST['OtpForm'])) {
                    $otpModel->attributes = $_POST['OtpForm'];

                    $otpModel->msisdn = Yii::app()->session['phone_contact'];
                    if ($otpModel->validate()) {
                        //check timeout OPT confirm
                        if (((time() - Yii::app()->session['time_reset']) / 60) <= Yii::app()->params['verify_config']['times_reset']) {
                            //check max verify number
                            if (isset(Yii::app()->session['verify_number']) && Yii::app()->session['verify_number'] != '') {
                                if (Yii::app()->session['verify_number'] > Yii::app()->params['verify_config']['verify_number']) {
                                    $msg = Yii::t('web/portal', 'err_verify_limited');
                                    $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
                                } else {
                                    //set time and ++ verify_number
                                    Yii::app()->session['verify_number'] += 1;
                                    Yii::app()->session['time_reset'] = time();
                                }
                            } else {
                                //not exist-->set time and verify_number
                                Yii::app()->session['verify_number'] = 1;
                                Yii::app()->session['time_reset']    = time();
                            }

                            if ($otpModel->checkTokenKey()) {//Check Token key
                                $orders_data = Yii::app()->session['data_orders'];
                                $sim         = $orders_data->sim;
                                $orders      = $orders_data->orders;
                                $order_state = $orders_data->order_state;
                                $data        = array(
                                    'sim'           => $sim->attributes,
                                    'orders'        => $orders->attributes,
                                    'order_details' => $orders_data->order_details,
                                    'order_state'   => $order_state->attributes,
                                );
                                if ($orders_data->insertOrders($data)) {
                                    $otpModel->unsetSession();
                                    $msg = Yii::t('web/portal', 'checkout_success', array('{order_id}' => $orders->id));
                                } else {
                                    $msg = Yii::t('web/portal', 'checkout_fail');
                                }
                                //redirect to message
                                $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
                            } else {
                                $msg = Yii::t('web/portal', 'verify_fail');
                            }
                        } else {
                            $msg = Yii::t('web/portal', 'verify_exp');
                        }
                    }
                }

                $this->render('verify_otp', array(
                    'otpModel' => $otpModel,
                    'msg'      => $msg,
                ));
            }
        }

        public function actionResponse()
        {
            if (isset($_REQUEST) && isset(Yii::app()->session['data_orders']) && !empty(Yii::app()->session['data_orders'])) {
                $arr_response  = $_REQUEST;
                $orders_data   = Yii::app()->session['data_orders'];
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;
                if (isset($arr_response['vpc_ResponseCode']) && ($arr_response['vpc_ResponseCode'] == 0)
                    && isset($arr_response['vpc_OrderInfo']) && ($arr_response['vpc_OrderInfo'] == $orders->id)
                ) {//success || order_id
                    $order_state->paid = WOrderState::PAID;

                    $data = array(
                        'sim'           => $sim->attributes,
                        'orders'        => $orders->attributes,
                        'order_details' => $order_details,
                        'order_state'   => $order_state->attributes,
                    );
                    if ($orders_data->insertOrders($data)) {
                        OtpForm::unsetSession();
                        $msg = Yii::t('web/portal', 'checkout_success', array('{order_id}' => $orders->id));
                    } else {
                        $msg = Yii::t('web/portal', 'checkout_fail');
                    }
                    //redirect to message
                    $this->redirect($this->createUrl('checkout/message', array('msg' => $msg)));
                }
            }
        }

        public function actionMessage($msg)
        {
            if (((time() - Yii::app()->session['session_cart']) / 60) >= Yii::app()->params['sessionTimeout']
                || !isset(Yii::app()->session['data_orders'])
                || empty(Yii::app()->session['data_orders'])
            ) {
                OtpForm::unsetSession();
                $msg = Yii::t('web/portal', 'session_timeout');
            }

            $this->render('message', array('msg' => $msg));
        }
    } //end class