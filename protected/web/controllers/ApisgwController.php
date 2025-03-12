<?php

    class ApisgwController extends CController
    {
        const VINAPHONE_TELCO = 'VINAPHONE';

        public function init()
        {
            if (isset($_REQUEST) && count($_REQUEST) > 0) {
                $p = new CHtmlPurifier();
                foreach ($_REQUEST as $k => $v) {
                    $_REQUEST[$k] = $p->purify($v);
                }
            }
        }

        /**
         * Basic http authentication
         *
         * @param string $username
         * @param string $password
         */
        private function _checkAuth($username = 'Vne_shop_test', $password = '@vNe#20171025')
        {
            $check = false;
            if(!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])){
                $user = WAffiliateManager::getAffiliateByCode($_SERVER['PHP_AUTH_USER']);
                if($user){
                    $secret_key = md5($user->code.$user->create_date.$user->status);
                    if($secret_key == $_SERVER['PHP_AUTH_PW']){
                        $check = true;
                    }
                }
            }
            if(!$check){
                header('WWW-Authenticate: Basic realm="https://freedoo.vnpt.vn/ Authentication System"');
                header('HTTP/1.0 401 Unauthorized');
                echo "You must enter a valid login ID and password to access this page\n";
                exit();
            }
        }

        /**
         * @param $file_name
         * @param $logMsg
         */
        private function writeLogs($file_name, $logMsg)
        {
            $logFolder  = "web/Log_apis_gateway/" . date("Y/m/d");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . $file_name . '.log');
            $logObj->processWriteLogs($logMsg);
        }

        /**
         * @param $array_param
         *
         * @return string
         */
        private function hashAllFields($array_param)
        {
            $dataCover = implode('', array_values($array_param));
            $user = WAffiliateManager::getAffiliateByCode($array_param['channel']);
            $secret_key = md5('hash secure failed');
            if($user){
                $secret_key =  md5($user->code.$user->create_date.$user->status);
            }
            return md5($dataCover . $secret_key);
        }

        private function detectByTelco($phone_number)
        {
            $flag = FALSE;
            if ($phone_number) {
                $telco = Utils::detectTelcoByMsisdn($phone_number);
                if ($telco == self::VINAPHONE_TELCO) {
                    $flag = TRUE;
                }
            }

            return $flag;
        }

        /**
         * add sim to cart
         * redirect to checkout
         * key cache: apisgw_orders_data_sim_[order_id]
         */
        public function actionAddtocart()
        {
            // new
            $params  = $_GET;//params request
            $channel = isset($params['channel']) ? ucfirst(strtolower($params['channel'])) : '';
            if(class_exists($channel)){
                $class_name = new $channel;
            }else{
                $class_name = new AddToCard;
            }
            $addToCart = $class_name->addToCart($params);

            if ($addToCart['flag']) {
                $this->redirect($this->createUrl($addToCart['url'].'/checkout'));
            } else {
                $this->redirect($this->createUrl($addToCart['url'].'/message', array('t' => $addToCart['t'])));

            }
            die;
            //.END new
            
            $params = $_GET;//params request

            $file_name = 'addtocart';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: addtocart Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $logMsg[]  = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());

            $sim_number     = isset($params['sim_number']) ? $params['sim_number'] : '';
            $sim_price      = isset($params['sim_price']) ? $params['sim_price'] : '';
            $sim_type       = isset($params['sim_type']) ? $params['sim_type'] : '';
            $sim_term       = isset($params['sim_term']) ? $params['sim_term'] : '';
            $sim_priceterm  = isset($params['sim_priceterm']) ? $params['sim_priceterm'] : '';
            $sim_store      = isset($params['sim_store']) ? $params['sim_store'] : '';
            $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : '';
            $channel        = isset($params['channel']) ? strtolower($params['channel']) : '';
            $secure         = isset($params['secure']) ? $params['secure'] : '';
            $opt            = isset($params['otp']) ? $params['otp'] : '';
            $option         = isset($params['option']) ? $params['option'] : '';
            // kiểm tra có gọi api mới hay không
            $checkNewApi = WAffiliateManager::checkApiCheckout($channel);

            $arr_params = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
                'opt'           => $opt,
                'option'        => $option,
            );

            $secure_hash = $this->hashAllFields($arr_params);
            $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
            $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
            $flag        = FALSE;
            if ($secure == $secure_hash) {
                if ($sim_number && $sim_type && $sim_store && $transaction_id && $channel) {

                    $this->setCookieFromAffiliate($channel, $transaction_id);

                    /*begin add to cart*/
                    $data_input = array(
                        'so_tb' => $sim_number,
                        'store' => $sim_store,
                        'otp'   => $opt,
                        'option'  => $option,
                    );
                    if($checkNewApi){
                        if($sim_priceterm > 0){
                            $sim_price = 60000;
                            $sim_type = 2;
                        }else{
                            $sim_price = 50000;
                            $sim_type = 1;
                        }
                    }
                    $sim         = new WSim();
                    $orders_data = new OrdersData();
                    //set sim_number to array sim_raw_data
                    $orders_data->sim_raw_data = array(
                        array(
                            'msisdn'      => $sim_number,
                            'msisdn_type' => $sim_type,
                            'price'       => $sim_price,
                            'term'        => $sim_term,
                            'price_term'  => $sim_priceterm,
                            'store'       => $sim_store,
                            'otp'   => $opt,
                            'option'  => $option,
                        )
                    );
                    if ($orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $sim)) {
                        //call api addtocart
                        $addToCartResult = $orders_data->addToCart($data_input);
                        if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                            $orders_data->operation = OrdersData::OPERATION_BUYSIM;
                            $modelOrder             = new WOrders();
                            $modelOrder->otp        = $addToCartResult['mtx'];
                            $modelOrder->id         = $modelOrder->generateOrderId();
                            //sso_id
                            if (!Yii::app()->user->isGuest) {
                                $modelOrder->sso_id = Yii::app()->user->sso_id;
                            }
                            $modelOrder->affiliate_source         = $channel;
                            $modelOrder->affiliate_transaction_id = $transaction_id;

                            $orders_data->orders = $modelOrder;
                            $orders_data->sim    = $sim;

                            $flag = TRUE;
                            //set session
                            Yii::app()->session['orders_data']  = $orders_data;
                            Yii::app()->session['session_cart'] = time();
                        } else {
                            $logMsg[] = array(CJSON::encode($addToCartResult), 'call api addToCart:' . __LINE__, 'E', time());
                        }
                    } else {
                        $logMsg[] = array('Fail', 'checkSimInRawData():' . __LINE__, 'E', time());
                    }/*end add to cart*/
                } else {
                    $logMsg[] = array('empty', '$sim_number || $sim_type || $sim_store || $transaction_id || $channel:' . __LINE__, 'E', time());
                }
            } else {
                $logMsg[] = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
            }

            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            //redirect
            if ($flag) {
                if($checkNewApi){
                    $this->redirect($this->createUrl('checkoutapi/checkout'));
                }else{
                    $this->redirect($this->createUrl('checkout/checkout'));
                }
            } else {
                if($checkNewApi){
                    $this->redirect($this->createUrl('checkoutapi/message', array('t' => 0)));
                }else{
                    $this->redirect($this->createUrl('checkout/message', array('t' => 0)));
                }
            }
        }

        /**
         * get list package Freedoo
         */
        public function actionPackages()
        {
            $this->_checkAuth();

            $file_name = 'packages';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: list package Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $packages  = WPackage::getListPackageApi();

            if ($packages) {
                $results = array(
                    'ok'    => TRUE,
                    'data'  => $packages,
                    'error' => NULL
                );
            } else {
                $results = array(
                    'ok'    => TRUE,
                    'data'  => NULL,
                    'error' => 'Empty results'
                );
            }

            $logMsg[] = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * register package:
         * call api check discount: true/false
         * send OTP: create new or get old token_key(limited=3)
         */
        public function actionRegpackage()
        {
            $this->_checkAuth();
            $file_name = 'reg_package';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: register package Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,//otp
                'error' => NULL
            );
            $flag      = FALSE;
            if (Yii::app()->request->isPostRequest) {
                $logMsg[] = array(CJSON::encode($_REQUEST), 'Request params:' . __LINE__, 'T', time());
                //request params
                $phone_number   = Yii::app()->request->getParam('phone_number', '');
                $promo_code     = Yii::app()->request->getParam('promo_code', '');
                $package_id     = Yii::app()->request->getParam('package_id', '');
                $transaction_id = Yii::app()->request->getParam('transaction_id', '');
                $channel        = Yii::app()->request->getParam('channel', '');
                $modelPackage   = WPackage::model()->find('id=:id', array(':id' => $package_id));
                if ($phone_number && $package_id && $transaction_id && $channel) {
                    //set cache order
                    $cache_key = 'apisgw_orders_data_package_' . $phone_number . '_' . $package_id;
                    $arr_cache = Yii::app()->cache->get($cache_key);
                    $otp_data  = isset($arr_cache['otp_data']) ? $arr_cache['otp_data'] : '';

                    //check limited send OTP
                    if ($otp_data && is_array($otp_data) && isset($otp_data['send_otp_number']) && $otp_data['send_otp_number'] > Yii::app()->params['verify_config']['send_otp_number']) {
                        $results['error'] = 'Send OTP limited.';
                        $logMsg[]         = array('send_otp_number= ' . $otp_data['send_otp_number'], 'Send OTP limited:' . __LINE__, 'E', time());
                    } else {
                        if ($modelPackage) {
                            //check vip_user
//                            if ((WPackage::checkVipUser() == FALSE) && ($modelPackage->vip_user >= WPackage::VIP_USER)) { //cannot register
//                                $results['error'] = 'Can not register.';
//                                $logMsg[]         = array('Can not register', 'checkVipUser():' . __LINE__, 'E', time());
//                            } else {//vip_user=0 || vip_user=1(aff ->login + sim freedoo)
                            $orders_data               = new OrdersData();
                            $modelOrder                = new WOrders();
                            $orderDetails              = new WOrderDetails();
                            $orders_data->package      = $modelPackage;//display view panel_order
                            $modelOrder->scenario      = 'register_package';
                            $modelOrder->id            = $modelOrder->generateOrderId();
                            $modelOrder->phone_contact = $phone_number;
                            $modelOrder->promo_code    = $promo_code;
                            //sso_id, phone_contact(after submit)
                            if (!Yii::app()->user->isGuest) {
                                $modelOrder->sso_id = Yii::app()->user->sso_id;
                                $customer           = WCustomers::model()->find('sso_id=:sso_id', array(':sso_id' => Yii::app()->user->sso_id));
//                                    if (WPackage::checkVipUser() && $modelPackage->vip_user >= WPackage::VIP_USER && $customer) {
//                                        $modelOrder->phone_contact = CFunction::makePhoneNumberBasic($customer->phone);
//                                    }
                            }

                            $modelOrder->affiliate_source         = $channel;
                            $modelOrder->affiliate_transaction_id = $transaction_id;
                            $discount                             = FALSE;
                            if ($this->detectByTelco($modelOrder->phone_contact)) {
                                //check package freedoo
                                if ($modelPackage->freedoo != WPackage::FREEDOO_PACKAGE
                                    || ($modelPackage->freedoo == WPackage::FREEDOO_PACKAGE && WPackage::checkSimFreedoo($modelOrder->phone_contact) == TRUE)
                                ) {
                                    //call api web_check_ctkm: price_discount
                                    $modelPackage->checkDiscountPricePackage($orders_data, $modelOrder, $modelPackage, $discount);
                                    //set order_detail
                                    $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);

                                    //get token key
                                    $otp_form = new OtpForm();
                                    if ($otp_data && is_array($otp_data) && isset($otp_data['token_key']) && !empty($otp_data['token_key'])) {
                                        //get old token_key
                                        $token_key                   = $otp_data['token_key'];
                                        $otp_data['send_otp_number'] += 1;
                                        $logMsg[]                    = array("token_key: $token_key", 'get old OTP:' . __LINE__, 'T', time());
                                    } else {//create new token_key
                                        $token_key                   = $otp_form->getTokenKey($modelOrder->phone_contact);
                                        $otp_data['send_otp_number'] = 1;
                                        $logMsg[]                    = array("token_key: $token_key", 'create new OTP:' . __LINE__, 'T', time());
                                    }

                                    if ($token_key) {
                                        $otp_data['verify_number'] = 1;
                                        $otp_data['time_reset']    = time();
                                        $otp_data['token_key']     = $token_key;
                                        $modelOrder->otp           = $token_key;

                                        //order state
                                        $orderState = new WOrderState();
                                        $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                                        $orders_data->order_state = $orderState;

                                        //set cache
                                        $arr_cache = array(
                                            'orders_data' => $orders_data,
                                            'otp_data'    => $otp_data,
                                            'package_id'  => $package_id,
                                        );

                                        if (YII_DEBUG == TRUE) {
                                            $orders_data->orders                    = $modelOrder;
                                            $orders_data->order_details['packages'] = $orderDetails->attributes;

                                            //set cache order
                                            Yii::app()->cache->set($cache_key, $arr_cache);
                                            $flag            = TRUE;
                                            $results['data'] = array(
                                                'otp'      => $token_key,
                                                'discount' => $discount,
                                            );
                                        } else {
                                            //send MT token key
                                            $mt_content = Yii::t('web/mt_content', 'otp_register_package', array(
                                                '{token_key}'    => $token_key,
                                                '{package_name}' => $modelPackage->name,
                                            ));
                                            if ($otp_form->sentMtVNP($modelOrder->phone_contact, $mt_content, 'package')) {
                                                $orders_data->orders                    = $modelOrder;
                                                $orders_data->order_details['packages'] = $orderDetails->attributes;

                                                //set cache order
                                                Yii::app()->cache->set($cache_key, $arr_cache);
                                                $flag            = TRUE;
                                                $results['data'] = array(
                                                    'otp'      => $token_key,
                                                    'discount' => $discount,
                                                );
                                            } else {
                                                $results['error'] = 'Server busy.';
                                                $logMsg[]         = array('Send MT fail', '$otp_form->sentMtVNP():' . __LINE__, 'E', time());
                                            }
                                        }
                                    } else {//get token key fail
                                        $results['error'] = 'Server busy.';
                                        $logMsg[]         = array('Get token key fail', '$otp_form->getTokenKey():' . __LINE__, 'E', time());
                                    }
                                } else {//package_freedoo
                                    $results['error'] = 'Server busy.';
                                    $logMsg[]         = array('msisdn must be sim freedoo || CTV', 'package_freedoo:' . __LINE__, 'E', time());
                                }
                            } else {
                                $results['error'] = 'Phone number must be Vinaphone.';
                                $logMsg[]         = array('Phone number must be Vinaphone', '$modelOrder->detectByTelco():' . __LINE__, 'E', time());
                            }
//                            }
                        } else {
                            $results['error'] = 'Package not exists.';
                            $logMsg[]         = array('Package not exists', 'check package:' . __LINE__, 'E', time());
                        }
                    }//check send otp limited
                } else {
                    $results['error'] = 'Invalid params.';
                    $logMsg[]         = array('Invalid params', 'empty $phone_number || $package_id || $transaction_id || $channel:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed.';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }

            $results['ok'] = $flag;

            $logMsg[] = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * register package flexible:
         * send OTP: create new or get old token_key(limited=3)
         */
        public function actionRegpackageflex()
        {
            $this->_checkAuth();
            $file_name = 'reg_package_flex';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: register package flexible Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,//otp
                'error' => NULL
            );
            $flag      = FALSE;
            if (Yii::app()->request->isPostRequest) {
                $logMsg[] = array(CJSON::encode($_REQUEST), 'Request params:' . __LINE__, 'T', time());
                //request params
                $phone_number   = Yii::app()->request->getParam('phone_number', '');
                $package_id     = Yii::app()->request->getParam('package_id', '');
                $channel        = Yii::app()->request->getParam('channel', '');
                $transaction_id = Yii::app()->request->getParam('transaction_id', '');

                if ($phone_number && $package_id && $transaction_id && $channel) {
                    $str_pack_id = preg_replace("/[\s,]/", "", $package_id);//remove space||,
                    //set cache order
                    $cache_key = 'apisgw_orders_data_package_' . $phone_number . '_' . $str_pack_id;
                    $arr_cache = Yii::app()->cache->get($cache_key);
                    $otp_data  = isset($arr_cache['otp_data']) ? $arr_cache['otp_data'] : '';

                    //check limited send OTP
                    if ($otp_data && is_array($otp_data) && isset($otp_data['send_otp_number']) && $otp_data['send_otp_number'] > Yii::app()->params['verify_config']['send_otp_number']) {
                        $results['error'] = 'Send OTP limited.';
                        $logMsg[]         = array('send_otp_number= ' . $otp_data['send_otp_number'], 'Send OTP limited:' . __LINE__, 'E', time());
                    } else {
                        $orders_data                          = new OrdersData();
                        $modelOrder                           = new WOrders();
                        $orderDetails                         = new WOrderDetails();
                        $modelOrder->scenario                 = 'register_package';
                        $modelOrder->id                       = $modelOrder->generateOrderId();
                        $modelOrder->phone_contact            = $phone_number;
                        $modelOrder->affiliate_source         = $channel;
                        $modelOrder->affiliate_transaction_id = $transaction_id;

                        $modelPackage         = new WPackage();//display view panel_order
                        $modelPackage->name   = Yii::t('web/portal', strtolower(WPackage::PACKAGE_FLEXIBLE));
                        $modelPackage->type   = WPackage::PACKAGE_FLEXIBLE;
                        $orders_data->package = $modelPackage;//display view panel_order
                        $discount             = FALSE;
                        if ($this->detectByTelco($modelOrder->phone_contact)) {
                            $arr_pack_id = explode(',', $package_id);
                            //set order detail
                            WOrderDetails::setOrderDetailsPackageFlexible($arr_pack_id, $modelOrder, $orderDetails, FALSE);
                            if ($orderDetails) {
                                //get token key
                                $otp_form = new OtpForm();
                                if ($otp_data && is_array($otp_data) && isset($otp_data['token_key']) && !empty($otp_data['token_key'])) {
                                    //get old token_key
                                    $token_key                   = $otp_data['token_key'];
                                    $otp_data['send_otp_number'] += 1;
                                    $logMsg[]                    = array("token_key: $token_key", 'get old OTP:' . __LINE__, 'T', time());
                                } else {//create new token_key
                                    $token_key                   = $otp_form->getTokenKey($modelOrder->phone_contact);
                                    $otp_data['send_otp_number'] = 1;
                                    $logMsg[]                    = array("token_key: $token_key", 'create new OTP:' . __LINE__, 'T', time());
                                }

                                if ($token_key) {
                                    $otp_data['verify_number'] = 1;
                                    $otp_data['time_reset']    = time();
                                    $otp_data['token_key']     = $token_key;
                                    $modelOrder->otp           = $token_key;

                                    //order state
                                    $orderState = new WOrderState();
                                    $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                                    $orders_data->order_state = $orderState;

                                    //set cache
                                    $arr_cache = array(
                                        'orders_data' => $orders_data,
                                        'otp_data'    => $otp_data,
                                        'package_id'  => $str_pack_id,
                                    );

                                    if (YII_DEBUG == TRUE) {
                                        $orders_data->orders                    = $modelOrder;
                                        $orders_data->order_details['packages'] = $orderDetails->attributes;

                                        //set cache order
                                        Yii::app()->cache->set($cache_key, $arr_cache);
                                        $flag            = TRUE;
                                        $results['data'] = array(
                                            'otp'      => $token_key,
                                            'discount' => $discount,
                                        );
                                    } else {
                                        //send MT token key
                                        $mt_content = Yii::t('web/mt_content', 'otp_register_package', array(
                                            '{token_key}'    => $token_key,
                                            '{package_name}' => $modelPackage->name,
                                        ));
                                        if ($otp_form->sentMtVNP($modelOrder->phone_contact, $mt_content, 'package')) {
                                            $orders_data->orders                    = $modelOrder;
                                            $orders_data->order_details['packages'] = $orderDetails->attributes;

                                            //set cache order
                                            Yii::app()->cache->set($cache_key, $arr_cache);
                                            $flag            = TRUE;
                                            $results['data'] = array(
                                                'otp'      => $token_key,
                                                'discount' => $discount,
                                            );
                                        } else {
                                            $results['error'] = 'Server busy.';
                                            $logMsg[]         = array('Send MT fail', '$otp_form->sentMtVNP():' . __LINE__, 'E', time());
                                        }
                                    }
                                } else {//get token key fail
                                    $results['error'] = 'Server busy.';
                                    $logMsg[]         = array('Get token key fail', '$otp_form->getTokenKey():' . __LINE__, 'E', time());
                                }
                            } else {
                                $results['error'] = 'Package not exists.';
                                $logMsg[]         = array('Package not exists', 'check package:' . __LINE__, 'E', time());
                            }
                        } else {
                            $results['error'] = 'Phone number must be Vinaphone.';
                            $logMsg[]         = array('Phone number must be Vinaphone', '$modelOrder->detectByTelco():' . __LINE__, 'E', time());
                        }
                    }//check send otp limited
                } else {
                    $results['error'] = 'Invalid params.';
                    $logMsg[]         = array('Invalid params', 'empty $phone_number || $package_id || $transaction_id || $channel:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed.';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }

            $results['ok'] = $flag;

            $logMsg[] = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * confirm otp register package
         * verify OTP
         * call api register
         */
        public function actionConfirmotp()
        {
            $this->_checkAuth();
            $file_name = 'confirm_otp';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: confirm otp register package Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => '',
                'error' => ''
            );
            $flag      = FALSE;
            if (Yii::app()->request->isPostRequest) {
                $phone_number = Yii::app()->request->getParam('phone_number', '');
                $token_key    = Yii::app()->request->getParam('otp', '');
                $package_id   = Yii::app()->request->getParam('package_id', '');
                if ($phone_number && $token_key && $package_id) {
                    $str_pack_id = preg_replace("/[\s,]/", "", $package_id);//remove space||,
                    //get orders_data from cache
                    $cache_key = 'apisgw_orders_data_package_' . $phone_number . '_' . $str_pack_id;
                    $arr_cache = Yii::app()->cache->get($cache_key);
                    if (isset($arr_cache['package_id']) && $arr_cache['package_id'] == $str_pack_id) {
                        $orders_data = isset($arr_cache['orders_data']) ? $arr_cache['orders_data'] : '';
                        $otp_data    = isset($arr_cache['otp_data']) ? $arr_cache['otp_data'] : '';
                        if ($orders_data && $otp_data && is_array($otp_data)) {
                            $orders                 = $orders_data->orders;
                            $order_details          = $orders_data->order_details;
                            $order_state            = $orders_data->order_state;
                            $orders->payment_method = (string)WPaymentMethod::PM_AIRTIME;
                            $modelPackage           = $orders_data->package;
                            if ($modelPackage) {
                                //check vip_user
//                                if ((WPackage::checkVipUser() == FALSE) && (isset($modelPackage->vip_user) && $modelPackage->vip_user >= WPackage::VIP_USER)) { //cannot register
//                                    $results['error'] = 'Can not register.';
//                                    $logMsg[]         = array('Can not register', 'checkVipUser():' . __LINE__, 'E', time());
//
//                                    //delete cache
//                                    Yii::app()->cache->delete($cache_key);
//                                } else {//vip_user=0 || vip_user=1(aff ->login + sim freedoo)
                                //check phone_contact from tbl_orders
                                if ($phone_number == $orders->phone_contact) {
                                    //check package freedoo
                                    if ($modelPackage->freedoo != WPackage::FREEDOO_PACKAGE
                                        || ($modelPackage->freedoo == WPackage::FREEDOO_PACKAGE && WPackage::checkSimFreedoo($orders->phone_contact) == TRUE)
                                    ) {
                                        $otpModel           = new OtpForm();
                                        $otpModel->scenario = 'checkTokenKeyApi';
                                        $otpModel->msisdn   = $phone_number;
                                        $otpModel->token    = $token_key;
                                        if ($otpModel->validate()) {//check required
                                            //check timeout OTP confirm: set to cache
                                            if (((time() - $otp_data['time_reset']) / 60) <= Yii::app()->params['verify_config']['apisgw_times_reset']) {
                                                //check max verify number
                                                if (isset($otp_data['verify_number']) && $otp_data['verify_number'] >= 1) {
                                                    if ($otp_data['verify_number'] > Yii::app()->params['verify_config']['verify_number']) {
                                                        $results['error'] = 'Server busy.';
                                                        $logMsg[]         = array($otp_data['verify_number'], 'verify_number limited:' . __LINE__, 'E', time());
                                                    } else {
                                                        //set time and ++ verify_number
                                                        $otp_data['verify_number'] += 1;
                                                        $otp_data['time_reset']    = time();
                                                    }
                                                } else {
                                                    //not exist-->set time and verify_number
                                                    $otp_data['verify_number'] = 1;
                                                    $otp_data['time_reset']    = time();
                                                }
                                                $arr_cache = array(
                                                    'orders_data' => $orders_data,
                                                    'otp_data'    => $otp_data,
                                                );
                                                //set cache time out otp
                                                Yii::app()->cache->set($cache_key, $arr_cache);
                                                //end check time out otp and set to cache

                                                //Check Token key from cache with request param
                                                if ($token_key == $orders->otp && $otpModel->checkTokenKeyApi($otp_data['token_key'])) {
                                                    $data = array(
                                                        'orders'        => $orders->attributes,
                                                        'order_details' => $order_details,
                                                        'order_state'   => $order_state->attributes,
                                                    );

                                                    //call api register
                                                    $response_arr = $orders_data->registerPackage($data);

                                                    $logMsg[] = array(CJSON::encode($response_arr), 'Output call api registerPackage:' . __LINE__, 'E', time());
                                                    if (isset($response_arr['code']) && $response_arr['code'] == 1) {
                                                        $flag             = TRUE;
                                                        $results['error'] = '';
                                                    } else {
                                                        $results['error'] = $response_arr['msg'];
                                                        $logMsg[]         = array('Fail', 'Call api registerPackage:' . __LINE__, 'E', time());
                                                    }

                                                    //delete cache
                                                    Yii::app()->cache->delete($cache_key);
                                                } else {
                                                    $results['error'] = 'Server busy.';
                                                    $logMsg[]         = array('Fail', 'checkTokenKeyApi():' . __LINE__, 'E', time());
                                                    $logMsg[]         = array("params otp=$token_key, tbl_orders: otp=" . $orders->otp, 'check $orders->otp:' . __LINE__, 'E', time());
                                                }
                                            } else {
                                                $results['error'] = 'Otp timeout.';
                                                $logMsg[]         = array('Time out', 'check timeout OTP confirm:' . __LINE__, 'E', time());
                                            }
                                        }
                                    } else {//package_freedoo
                                        $results['error'] = 'Server busy.';
                                        $logMsg[]         = array('msisdn must be sim freedoo || CTV', 'package_freedoo:' . __LINE__, 'E', time());
                                    }
                                } else {
                                    $results['error'] = 'Wrong phone_number.';
                                    $logMsg[]         = array("params phone_number=$phone_number, tbl_orders: phone_contact=" . $orders->phone_contact, 'check $orders->phone_contact:' . __LINE__, 'E', time());
                                }
//                                }
                            } else {
                                $results['error'] = 'Package not exists.';
                                $logMsg[]         = array('Package not exists', 'check package:' . __LINE__, 'E', time());
                                //delete cache
                                Yii::app()->cache->delete($cache_key);
                            }
                        } else {
                            $results['error'] = 'Server busy.';
                            $logMsg[]         = array('null || empty', 'get cache $orders_data:' . __LINE__, 'E', time());
                            //delete cache
                            Yii::app()->cache->delete($cache_key);
                        }
                    } else {
                        $results['error'] = 'Invalid params.';
                        $logMsg[]         = array($str_pack_id, 'package_id from request: ' . __LINE__, 'E', time());
                        $logMsg[]         = array($arr_cache['package_id'], 'package_id from cache:' . __LINE__, 'E', time());
                        //delete cache
                        Yii::app()->cache->delete($cache_key);
                    }
                } else {
                    $results['error'] = 'Invalid params.';
                    $logMsg[]         = array('Invalid params', 'empty $phone_number || $token_key || $package_id:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed.';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }

            $results['ok'] = $flag;

            $logMsg[] = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        public function actionQueryDrTransaction()
        {
            $file_name = 'queryDrTransaction';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway: queryDrTransaction Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());

            $results = array(
                'ok'    => FALSE,
                'data'  => array(
                    'order_id'       => '',
                    'payment_method' => '',
                ),
                'error' => NULL
            );
            if (Yii::app()->request->isPostRequest) {
                $logMsg[] = array(CJSON::encode($_REQUEST), 'Request params:' . __LINE__, 'T', time());
                $order_id = Yii::app()->request->getParam('order_id', '');
                if ($order_id) {
                    $results['ok']   = WTransactionRequest::getTransactionRequestQueryDr($order_id, $payment_method, $logMsg);
                    $results['data'] = array(
                        'order_id'       => $order_id,
                        'payment_method' => $payment_method,
                    );
                } else {
                    $results['error'] = 'Invalid params.';
                    $logMsg[]         = array('Invalid params', 'empty $order_id:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed.';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[] = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);
            header('Content-Type: application/json');

            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * create file cross-checking with Vietinbank(ATM - payment_method = 9)
         * query tbl_transaction_response
         */
        public function actionCreateFileCheckingVtb($date = NULL)
        {
            $logMsg     = array();
            $logMsg[]   = array('Start apis gateway: createFileCheckingVtb Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]   = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $trans_date = date("Y-m-d", strtotime('-1 day', time()));
            if ($date) {
                $trans_date = date('Y-m-d', strtotime($date));
            }
            $create_date = time();

            $transactions_response = WTransactionResponse::getListResponseByDate($trans_date, WPaymentMethod::PM_VIETIN_ATM);
            $total                 = count($transactions_response);
            $logMsg[]              = array($total, 'getListResponseByDate() total: ' . __LINE__, 'T', time());
            $vietinbank            = new Vietinbank();
            $private_key           = '12345678';
            $title                 = array(
                'RecordType',
                'RcReconcile',
                'MsgType',
                'CurCode',
                'Amount',
                'TranId',
                'RefundId',
                'TranDate',
                'MerchantId',
                'BankTrxSeq',
                'BankResponseCode',
                'CardNumber',
                'Checksum',
            );
            $finish                = array(
                '0009',     //record type
                $total,     //total row transactions file
                'System',   //username create file
                date('d/m/Y H:i:s', $create_date),//time create file
                md5(date('d/m/Y H:i:s', $create_date) . $total . $private_key),//checksum: create_date+total+private_key
            );
            $row_title             = implode(',', $title);//row title
            $row_finish            = implode('|', $finish);//row finish
            $data_file[]           = $row_title;
            //row transactions
            $vietinbank->convertDataChecking($private_key, $transactions_response, $data_file);
            $data_file[] = $row_finish;

            $logMsg[] = array(CJSON::encode($data_file), 'data file: ' . __LINE__, 'T', time());
            //dir upload
            $dir_upload = Yii::app()->params->upload_dir . Yii::app()->params->ftp_vtb . Yii::app()->params->vtb_out;
            if (!is_dir($dir_upload)) {
                mkdir($dir_upload, 0777, TRUE);
            }
            $file_name = $dir_upload . date('Ymd', strtotime($trans_date)) . '_TRANS_VINAPHONE.txt';
            //create new file txt
            $create_file = fopen($file_name, 'w');
            fwrite($create_file, implode(PHP_EOL, array_values($data_file)));//write to file txt
            if (file_exists($file_name) && is_file($file_name)) {
                fclose($create_file);
                chmod($file_name, 0777);
                $logMsg[] = array($file_name, 'Create file success: ' . __LINE__, 'T', time());
                echo 'Create file success: ' . $file_name;
            } else {
                $logMsg[] = array('Fail', 'Create file: ' . __LINE__, 'T', time());
                echo 'Create file fail';
            }
            $file_log = 'create_file_checking_vtb';
            $logMsg[] = array($file_log, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_log, $logMsg);
            Yii::app()->end();
        }

        public function actionConfirmFileCheckingVtb($date = NULL)
        {
            $logMsg   = array();
            $logMsg[] = array('Start apis gateway: ConfirmFileCheckingVtb Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[] = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());

            $trans_date = date("Y-m-d", strtotime('-1 day', time()));
            if ($date) {
                $trans_date = date('Y-m-d', strtotime($date));
            }
            $create_date = time();

            //read file Vietinbank
            $file_name = Yii::app()->params->upload_dir . Yii::app()->params->ftp_vtb . Yii::app()->params->vtb_in . date('Ymd', strtotime($trans_date)) . '_TRANS_DISPUTE_VINAPHONE.txt';
//            $file_name = Yii::app()->params->upload_dir . Yii::app()->params->ftp_vtb . Yii::app()->params->vtb_in. '20180201_TRANS_VINAPHONE.txt';
            $get_file = fopen($file_name, 'r');
            $vtb_data = explode(PHP_EOL, fread($get_file, filesize($file_name)));
            $logMsg[] = array(CJSON::encode($vtb_data), 'content file Vietinbank: ' . __LINE__, 'T', time());
            //remove the first and last element
            array_shift($vtb_data);//remove first
            array_pop($vtb_data);//remove last

            //query tbl_transaction_response
            $transactions_response = WTransactionResponse::getListResponseByDate($trans_date, WPaymentMethod::PM_VIETIN_ATM);
            $total                 = count($transactions_response);
            $logMsg[]              = array($total, 'getListResponseByDate() total: ' . __LINE__, 'T', time());
            $vietinbank            = new Vietinbank();
            $private_key           = '12345678';

            $arr_map = $vtb_data;
            //row transactions
            $vietinbank->convertDataChecking($private_key, $transactions_response, $arr_map);
            //diff array vietinbank vs freedoo
            $data_file  = array_diff($arr_map, $vtb_data);
            $logMsg[]   = array(CJSON::encode($arr_map), 'array_map file Vietinbank, file Freedoo: ' . __LINE__, 'T', time());
            $logMsg[]   = array(CJSON::encode($data_file), 'array_diff file Vietinbank, file Freedoo: ' . __LINE__, 'T', time());
            $title      = array(
                'RecordType',
                'RcReconcile',
                'MsgType',
                'CurCode',
                'Amount',
                'TranId',
                'RefundId',
                'TranDate',
                'MerchantId',
                'BankTrxSeq',
                'BankResponseCode',
                'CardNumber',
                'Checksum',
            );
            $finish     = array(
                '0009',     //record type
                $total,     //total row transactions file
                'System',   //username create file
                date('d/m/Y H:i:s', $create_date),//time create file
                md5(date('d/m/Y H:i:s', $create_date) . $total . $private_key),//checksum: create_date+total+private_key
            );
            $row_title  = implode(',', $title);//row title
            $row_finish = implode('|', $finish);//row finish

            //add title and finish
            array_unshift($data_file, $row_title);
            array_push($data_file, $row_finish);

            $logMsg[] = array(CJSON::encode($data_file), 'data file: ' . __LINE__, 'T', time());

            //dir upload
            $dir_upload = Yii::app()->params->upload_dir . Yii::app()->params->ftp_vtb . Yii::app()->params->vtb_out;
            if (!is_dir($dir_upload)) {
                mkdir($dir_upload, 0777, TRUE);
            }
            $file_name = $dir_upload . date('Ymd', strtotime($trans_date)) . '_TRANS_SOLVED_VINAPHONE.txt';
            //create new file txt
            $create_file = fopen($file_name, 'w');
            fwrite($create_file, implode(PHP_EOL, array_values($data_file)));//write to file txt
            if (file_exists($file_name) && is_file($file_name)) {
                fclose($create_file);
                chmod($file_name, 0777);
                $logMsg[] = array($file_name, 'Create file success: ' . __LINE__, 'T', time());
                echo 'Create file success: ' . $file_name;
            } else {
                $logMsg[] = array('Fail', 'Create file: ' . __LINE__, 'T', time());
                echo 'Create file fail';
            }
            $file_log = 'confirm_file_checking_vtb';
            $logMsg[] = array($file_log, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_log, $logMsg);
            Yii::app()->end();
        }

        public function actionTest()
        {
            $params         = $_GET;
            $sim_number     = isset($params['sim_number']) ? $params['sim_number'] : '';
            $sim_price      = isset($params['sim_price']) ? $params['sim_price'] : '';
            $sim_type       = isset($params['sim_type']) ? $params['sim_type'] : '';
            $sim_term       = isset($params['sim_term']) ? $params['sim_term'] : '';
            $sim_priceterm  = isset($params['sim_priceterm']) ? $params['sim_priceterm'] : '';
            $sim_store      = isset($params['sim_store']) ? $params['sim_store'] : '';
            $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : '';
            $channel        = isset($params['channel']) ? $params['channel'] : '';

            $arr_params = array(
                'sim_number'     => $sim_number,
                'sim_price'      => $sim_price,
                'sim_type'       => $sim_type,
                'sim_term'       => $sim_term,
                'sim_priceterm'  => $sim_priceterm,
                'sim_store'      => $sim_store,
                'transaction_id' => $transaction_id,
                'channel'        => $channel,
            );

            CVarDumper::dump($arr_params, 10, TRUE);
            CVarDumper::dump($this->hashAllFields($arr_params), 10, TRUE);
            die;
        }

        public function setCookieFromAffiliate($utm_source_value, $aff_sid_value)
        {

            if ($utm_source_value && $aff_sid_value) {

                //check affiliate exists
                if (WAffiliateManager::getAffiliateByCode($utm_source_value)) {

                    $utm_source         = new CHttpCookie('utm_source', $utm_source_value);
                    $aff_sid            = new CHttpCookie('aff_sid', $aff_sid_value);
                    $utm_source->expire = time() + 60 * 60 * 24 * 180;//30 days
                    $aff_sid->expire    = time() + 60 * 60 * 24 * 180;//30 days

                    Yii::app()->request->cookies['utm_source'] = $utm_source;
                    Yii::app()->request->cookies['aff_sid']    = $aff_sid;
                }
            }
        }

        /**
         * api add sim to cart, checkout
         */
        public function actionCheckout()
        {
            $affiliate_manage = WAffiliateManager::getAffiliateByCode($_REQUEST['channel']);
            $username         = isset($affiliate_manage->username) ? $affiliate_manage->username : '';
            $password         = isset($affiliate_manage->password) ? $affiliate_manage->password : '';
//            $this->_checkAuth($username, $password);
            $file_name = 'checkout';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,//otp
                'error' => NULL
            );
            $flag      = FALSE;
            if (Yii::app()->request->isPostRequest) {
                $logMsg[] = array(CJSON::encode($_REQUEST), 'Request params:' . __LINE__, 'T', time());
                //request params
                $sim_number     = Yii::app()->request->getParam('sim_number', '');
                $sim_price      = Yii::app()->request->getParam('sim_price', '');
                $sim_type       = Yii::app()->request->getParam('sim_type', '');
                $sim_term       = Yii::app()->request->getParam('sim_term', '');
                $sim_priceterm  = Yii::app()->request->getParam('sim_priceterm', '');
                $sim_store      = Yii::app()->request->getParam('sim_store', '');
                $channel        = Yii::app()->request->getParam('channel', '');
                $transaction_id = Yii::app()->request->getParam('transaction_id', '');
                $secure         = Yii::app()->request->getParam('secure', '');
                $full_name      = Yii::app()->request->getParam('full_name', '');
                $phone_contact  = Yii::app()->request->getParam('phone_contact', '');
                $delivery_type  = Yii::app()->request->getParam('delivery_type', '');
                $province_code  = Yii::app()->request->getParam('province_code', '');
                $district_code  = Yii::app()->request->getParam('district_code', '');
                $ward_code      = Yii::app()->request->getParam('ward_code', '');
                $address_detail = Yii::app()->request->getParam('address_detail', '');
                $customer_note  = Yii::app()->request->getParam('customer_note', '');
                $package_id     = Yii::app()->request->getParam('package_id', '');
                $promo_code     = Yii::app()->request->getParam('promo_code', '');
                $arr_params     = array(
                    'sim_number'     => $sim_number,
                    'sim_price'      => $sim_price,
                    'sim_type'       => $sim_type,
                    'sim_term'       => $sim_term,
                    'sim_priceterm'  => $sim_priceterm,
                    'sim_store'      => $sim_store,
                    'transaction_id' => $transaction_id,
                    'channel'        => $channel,
                );

                $secure_hash = $this->hashAllFields($arr_params);
                $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                if ($secure == $secure_hash) {
                    if ($sim_number && $sim_type && $sim_store && $transaction_id && $channel && $secure && $full_name
                        && $phone_contact && $delivery_type && $province_code && $district_code && $address_detail
                    ) {
                        $modelSim    = new WSim();
                        $modelOrder  = new WOrders();
                        $orders_data = new OrdersData();
                        if ($modelOrder->startsWith($transaction_id, 'TK')) {
                            //add to cart
                            $data_input = array(
                                'so_tb' => $sim_number,
                                'store' => $sim_store,
                            );

                            //set sim_number to array sim_raw_data
                            $orders_data->sim_raw_data = array(
                                array(
                                    'msisdn'      => $sim_number,
                                    'msisdn_type' => $sim_type,
                                    'price'       => $sim_price,
                                    'term'        => $sim_term,
                                    'price_term'  => $sim_priceterm,
                                    'store'       => $sim_store
                                )
                            );
                            if ($orders_data->checkSimInRawData($sim_number, $sim_type, $sim_price, $orders_data->sim_raw_data, $modelSim)) {
                                //verify the selected package by sim type
                                if ($modelSim->verifyPackageBySimType($package_id, $orders_data, $modelSim)) {
                                    $orders_data->package = WPackage::model()->find('id=:id AND status=:status', array(':id' => $package_id, ':status' => WPackage::PACKAGE_ACTIVE));
                                    //call api addtocart
                                    $addToCartResult = $orders_data->addToCart($data_input);
                                    if (isset($addToCartResult['mtx']) && !empty($addToCartResult['mtx'])) {
                                        $orders_data->operation = OrdersData::OPERATION_BUYSIM;
                                        $modelSim->full_name    = $full_name;
                                        $modelOrder->scenario   = 'register_sim';
                                        $modelOrder->otp        = $addToCartResult['mtx'];
                                        $modelOrder->id         = $modelOrder->generateOrderId();
                                        //sso_id
                                        if (!Yii::app()->user->isGuest) {
                                            $modelOrder->sso_id = Yii::app()->user->sso_id;
                                        }
                                        $modelOrder->affiliate_source         = $channel;
                                        $modelOrder->affiliate_transaction_id = $transaction_id;
                                        $modelOrder->full_name                = $modelSim->full_name;
                                        $modelOrder->phone_contact            = $phone_contact;
                                        $modelOrder->delivery_type            = $delivery_type;
                                        $modelOrder->province_code            = $province_code;
                                        $modelOrder->district_code            = $district_code;
                                        $modelOrder->ward_code                = $ward_code;
                                        $modelOrder->address_detail           = $address_detail;
                                        $modelOrder->brand_offices            = $address_detail;
                                        $modelOrder->customer_note            = $customer_note;
                                        $modelOrder->promo_code               = $promo_code;
                                        if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
                                            $modelOrder->price_ship = $GLOBALS['config_common']['order']['price_ship'];
                                        } else {
                                            $modelOrder->price_ship = 0;
                                        }

                                        if ($modelSim->validate() && $modelOrder->validate()) {
                                            //checkout
                                            if (empty(Yii::app()->params->checkout_prepaid)
                                                || ($modelSim->type == WSim::TYPE_POSTPAID)
                                                || (!empty(Yii::app()->params->checkout_prepaid) && ($modelSim->type == WSim::TYPE_PREPAID) && !empty($orders_data->package))
                                            ) {
                                                //if empty promo_code: check cookie affiliate
                                                if (empty($modelOrder->promo_code)) {
                                                    if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])
                                                        && empty($modelOrder->affiliate_source)
                                                    ) {
                                                        $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                                                    }
                                                    if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])
                                                        && empty($modelOrder->affiliate_transaction_id)
                                                    ) {
                                                        $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                                                    }
                                                }

                                                //detail sim
                                                $order_details_sim = new WOrderDetails();
                                                $order_details_sim->setOrderDetailsSim($modelSim, $modelOrder, $order_details_sim);
                                                $orders_data->order_details['sim'] = $order_details_sim->attributes;

                                                //detail price_term
                                                $detail_price_term = new WOrderDetails();
                                                $detail_price_term->setOrderDetailsPriceTerm($modelSim, $modelOrder, $detail_price_term);
                                                $orders_data->order_details['price_term'] = $detail_price_term->attributes;

                                                //detail price_ship
                                                $detail_price_ship = new WOrderDetails();
                                                $detail_price_ship->setOrderDetailsPriceShip($modelOrder, $detail_price_ship);
                                                $orders_data->order_details['price_ship'] = $detail_price_ship->attributes;

                                                //add order detail with package
                                                $modelPackage = $orders_data->package;
                                                if ($modelPackage) {
                                                    $package_raw = $modelPackage;//order_detail: package(price)
                                                    //check price_discount
                                                    if ($package_raw->price_discount > 0) {
                                                        $package_raw->price = $package_raw->price_discount;
                                                    } elseif ($package_raw->price_discount == -1) {
                                                        $package_raw->price = 0;
                                                    }
                                                    $order_details_pack = new WOrderDetails();
                                                    $order_details_pack->setOrderDetailsPackage($package_raw, $modelOrder, $order_details_pack);
                                                    $orders_data->order_details['packages'] = $order_details_pack->attributes;
                                                } else {
                                                    unset($orders_data->order_details['packages']);
                                                    unset($orders_data->package);
                                                }

                                                //order state
                                                $order_state = new WOrderState();
                                                $order_state->setOrderState($modelOrder, $order_state, WOrderState::UNCONFIRMED, WOrderState::UNPAID);
                                                $orders_data->order_state = $order_state;

                                                //sim, order
                                                $orders_data->sim    = $modelSim;
                                                $orders_data->orders = $modelOrder;
                                                $data                = array(
                                                    'sim'           => $modelSim->attributes,
                                                    'orders'        => $modelOrder->attributes,
                                                    'order_details' => $orders_data->order_details,
                                                    'order_state'   => $order_state->attributes,
                                                );

                                                if ($orders_data->buySim($data)) {
                                                    $flag            = TRUE;
                                                    $results['data'] = $modelOrder->otp;
                                                    //set cache order
                                                    $key = 'orders_data_api_' . $modelOrder->id;
                                                    Yii::app()->redis_orders_data->set($key, $orders_data, Yii::app()->params->cache_timeout_config['cart_iframe']);//30'
                                                } else {
                                                    $results['error'] = 'Server busy';
                                                    $logMsg[]         = array(Yii::t('web/portal', 'insert_order_fail'), 'Invalid params: ' . __LINE__, 'E', time());
                                                }
                                            } else {
                                                $results['error'] = 'Required package_id';
                                                $logMsg[]         = array(Yii::t('web/portal', 'err_required_package'), 'Invalid params: ' . __LINE__, 'E', time());
                                            }
                                        } else {
                                            $results['error'] = 'Invalid params';
                                            $logMsg[]         = array('Fail', 'validate modelSim||modelOrder :' . __LINE__, 'E', time());
                                        }
                                    } else {
                                        $results['error'] = 'Server busy';
                                        $logMsg[]         = array(CJSON::encode($addToCartResult), 'call api addToCart fail:' . __LINE__, 'E', time());
                                    }
                                } else {
                                    $results['error'] = 'Package not match';
                                    $logMsg[]         = array('Fail', 'verifyPackageBySimType():' . __LINE__, 'E', time());
                                }
                            } else {
                                $results['error'] = 'Select sim fail';
                                $logMsg[]         = array('Fail', 'checkSimInRawData():' . __LINE__, 'E', time());
                            }//end add to cart
                        } else {
                            $results['error'] = 'Invalid params';
                            $logMsg[]         = array('transaction_id wrong format', 'Invalid params: ' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Invalid params';
                        $logMsg[]         = array('empty: sim_number||sim_type||sim_store||transaction_id||$channel||secure||full_name||phone_contact||delivery_type||province_code||district_code||address_detail', 'Invalid params:' . __LINE__, 'E', time());
                    }
                } else {
                    $results['error'] = 'Secure not match';
                    $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }

            $results['ok'] = $flag;
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            header('HTTP/1.0 200 Success');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * search msisdn
         * return json
         */
        public function actionSearchMsisdn()
        {
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $data_input = $_GET;
            $secure = $data_input['secure'];
            $arr_params = array_diff_key($data_input , ['secure' => 123]);
            $secure_hash = $this->hashAllFields($arr_params);
//            echo $secure_hash; die;
            if ($secure == $secure_hash) {
                if (!empty($arr_params['prefix']) && !empty($arr_params['search'])) {
                    //call api
                    // testing
                    $orders_data = new OrdersData();
                    $data_output = $orders_data->searchMsisdn($arr_params);
                    // $data_output = CJSON::decode('[{
                    //   "msisdn": "0886057743",
                    //   "msisdn_type": "1",
                    //   "price": "50000",
                    //   "term": "0",
                    //   "price_term": "0",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886059743",
                    //   "msisdn_type": "2",
                    //   "price": "60000",
                    //   "term": "0",
                    //   "price_term": "0",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886059743",
                    //   "msisdn_type": "1",
                    //   "price": "50000",
                    //   "term": "0",
                    //   "price_term": "0",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886063443",
                    //   "msisdn_type": "2",
                    //   "price": "60000",
                    //   "term": "18",
                    //   "price_term": "200000",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886064343",
                    //   "msisdn_type": "2",
                    //   "price": "60000",
                    //   "term": "18",
                    //   "price_term": "100000",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886065843",
                    //   "msisdn_type": "2",
                    //   "price": "60000",
                    //   "term": "0",
                    //   "price_term": "0",
                    //   "store": "32878"
                    // },
                    // {
                    //   "msisdn": "0886065843",
                    //   "msisdn_type": "1",
                    //   "price": "50000",
                    //   "term": "0",
                    //   "price_term": "0",
                    //   "store": "32878"
                    // }]');
                    $data_new_output = [];
                    foreach($data_output as $key => $value){
                        switch($arr_params['sim_type']){
                            case '1':
                                $sim_type_arr = [1];
                                break;
                            case '2':
                                $sim_type_arr = [2];
                                break;
                            case '10':
                                $sim_type_arr = [1, 2];
                                break;
                            default:
                                $sim_type_arr = [1];
                        }
                        if(in_array($value['msisdn_type'], $sim_type_arr)){
                            array_push($data_new_output, $value);
                        }
                    }
                    if($data_new_output){
                        $results['ok'] = TRUE;
                        $results['data'] = $data_new_output;
                    }else{
                        $results['error'] = 'Sever busy';
                    }
                }else{
                    $results['error'] = 'Invalid params';
                }
            }else{
                $results['error'] = 'Security not match';
            }
            echo CJSON::encode($results); die;
        }
        /**
         * keep msisdn
         * key cache: orders_data_ . $transaction_id;
         * return json
         */
        public function actionKeepMsisdn()
        {
            if(Yii::app()->request->isPostRequest){
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $channel = isset($params['channel']) ? ucfirst(strtolower($params['channel'])) : '';
                if (class_exists($channel)) {
                    $class_name = new $channel;
                } else {
                    $class_name = new Zalo;
                }
                $addToCart = $class_name->addToCart($params);

                header('Content-Type: application/json');
                header('HTTP/1.0 200 Success');
                echo CJSON::encode($addToCart);
                Yii::app()->end();
            }else{
                echo CJSON::encode(array('ok' => false, 'error' => 'method not allowed'));
                Yii::app()->end();
            }
        }

        /**
         * checkout order
         */
        public function actionCheckoutOrder()
        {
            $file_name = 'checkout-order';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $flag      = FALSE;
            if (Yii::app()->request->isPostRequest) {
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());

                //request params
                $transaction_id = $params['transaction_id'];
                $package_id     = $params['package_id'];
                $channel        = $params['channel'];
                $secure         = $params['secure'];

                $arr_params = array_diff_key($params, ['secure' => "xy"]);

                $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                if ($secure == $secure_hash) {
                    if ($this->validateCheckout($arr_params)) {
                        //get cache
                        $key        = 'orders_data_' .$channel .'_'. $transaction_id;
                        $cache_data = Yii::app()->cache->get($key);
                        if ($cache_data) {
                            $modelSim    = $cache_data->sim;
                            $modelOrder  = $cache_data->orders;
                            $orders_data = new OrdersData();

                            // add model sim
                            $modelSim->full_name = $arr_params['full_name'];

                            // add model order
                            $modelOrder->id             = $modelOrder->generateOrderId();
                            $modelOrder->full_name      = $arr_params['full_name'];
                            $modelOrder->address_detail = $arr_params['address_detail'];
                            $modelOrder->delivery_type  = $arr_params['delivery_type'];
                            $modelOrder->phone_contact  = $arr_params['phone_contact'];
                            $modelOrder->province_code  = $arr_params['province_code'];
                            $modelOrder->district_code  = $arr_params['district_code'];
                            $modelOrder->ward_code      = $arr_params['ward_code'];
                            $modelOrder->customer_note  = $arr_params['customer_note'];
                            $modelOrder->price_ship     = $modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME ? $GLOBALS['config_common']['order']['price_ship'] : 0;
                            if ($modelSim->validate() && $modelOrder->validate()) {

                                $orders_data->package = WPackage::model()->find('code_vnpt=:code AND status=:status', array(':code' => $package_id, ':status' => WPackage::PACKAGE_ACTIVE));
                                if(!empty($orders_data->package)) {
                                    //detail sim
                                    $order_details_sim = new WOrderDetails();
                                    $order_details_sim->setOrderDetailsSim($modelSim, $modelOrder, $order_details_sim);
                                    $orders_data->order_details['sim'] = $order_details_sim->attributes;

                                    //detail price_term
                                    $detail_price_term = new WOrderDetails();
                                    $detail_price_term->setOrderDetailsPriceTerm($modelSim, $modelOrder, $detail_price_term);
                                    $orders_data->order_details['price_term'] = $detail_price_term->attributes;

                                    //detail price_ship
                                    $detail_price_ship = new WOrderDetails();
                                    $detail_price_ship->setOrderDetailsPriceShip($modelOrder, $detail_price_ship);
                                    $orders_data->order_details['price_ship'] = $detail_price_ship->attributes;

                                    //add order detail with package
                                    $modelPackage = $orders_data->package;
                                    if ($modelPackage) {
                                        $package_raw = $modelPackage;//order_detail: package(price)
                                        //check price_discount
                                        if ($package_raw->price_discount > 0) {
                                            $package_raw->price = $package_raw->price_discount;
                                        } elseif ($package_raw->price_discount == -1) {
                                            $package_raw->price = 0;
                                        }
                                        $order_details_pack = new WOrderDetails();
                                        $order_details_pack->setOrderDetailsPackage($package_raw, $modelOrder, $order_details_pack);
                                        $orders_data->order_details['packages'] = $order_details_pack->attributes;
                                    } else {
                                        unset($orders_data->order_details['packages']);
                                        unset($orders_data->package);
                                    }

                                    //order state
                                    $order_state = new WOrderState();
                                    $order_state->setOrderState($modelOrder, $order_state, WOrderState::UNCONFIRMED, WOrderState::UNPAID);
                                    $orders_data->order_state = $order_state;

                                    //sim, order
                                    $orders_data->sim    = $modelSim;
                                    $orders_data->orders = $modelOrder;

                                    $data = array(
                                        'sim'           => $modelSim->attributes,
                                        'orders'        => $modelOrder->attributes,
                                        'order_details' => $orders_data->order_details,
                                        'order_state'   => $order_state->attributes,
                                    );
                                    $logMsg[] = array(CJSON::encode($data), 'Input: ' . __LINE__, 'T', time());
                                    //call api java->web_checkout
                                    $result_checkout = $orders_data->buySim($data, TRUE);
                                    if ($result_checkout && isset($result_checkout['code']) && $result_checkout['code'] == 1) {
                                        $modelOrder->payment_method = 4;
                                        $order_state->confirm       = 10;
                                        $order_state->paid          = '';
                                        $data                       = array(
                                            'sim'           => $modelSim->attributes,
                                            'orders'        => $modelOrder->attributes,
                                            'order_details' => $orders_data->order_details,
                                            'order_state'   => $order_state->attributes,
                                        );
                                        $result_update              = $orders_data->updateOrderStatus($data);
                                        if ($result_update) {
                                            $results['ok']   = TRUE;
                                            $results['data'] = array(
                                                "msisdn"         => $modelSim->msisdn,
                                                "order_id"       => $modelOrder->id,
                                                "transaction_id" => $modelOrder->affiliate_transaction_id
                                            );
                                        } else {
                                            $results['error'] = 'Failed Update checkout';
                                            $logMsg[]         = array('Update checkout fail' . __LINE__, 'E', time());
                                        }
                                    } else {
                                        if(isset($result_checkout['msg']) && $result_checkout['msg'] == 'STK-1234'){
                                            $results['error'] = 'Limited buy sim';
                                            $logMsg[]         = array('Limited buy sim' . __LINE__, 'E', time());
                                        }else{
                                            $results['error'] = 'Failed Checkout';
                                            $logMsg[]         = array('Checkout fail' . __LINE__, 'E', time());
                                        }
                                    }
                                }else{
                                    $results['error'] = 'Invalid package';
                                    $logMsg[]         = array('Invalid package' . __LINE__, 'E', time());
                                }
                            }else{
                                $results['error'] = 'Invalid request';
                                $logMsg[]         = array('Invalid request' . __LINE__, 'E', time());
                            }
                        } else {
                            $results['error'] = 'Session timeout';
                            $logMsg[]         = array('empty cache:' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Invalid params';
                        $logMsg[]         = array('empty: sim_number||sim_type||sim_store||transaction_id||$channel||secure||full_name||phone_contact||delivery_type||province_code||district_code||address_detail', 'Invalid params:' . __LINE__, 'E', time());
                    }
                } else {
                    $results['error'] = 'Secure not match';
                    $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            header('HTTP/1.0 200 Success');
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * SendOtpRx
         */
        public function actionSendOtpRx(){
            $file_name = 'SendOtpRx-Apisgw';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL,
                "code"  => 0
            );
            $required_params = ["phone_contact", "channel", "package_id", "transaction_id", "secure"];
            if (Yii::app()->request->isPostRequest) {
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());
                if($this->validateParams($required_params, $params)) {
                    //request params
                    $secure = $params['secure'];

                    $arr_params = array_diff_key($params, ['secure' => "xy"]);

                    $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                    $logMsg[] = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                    $logMsg[] = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                    if ($secure == $secure_hash) {
                        $data_output = Utils::getInfoPhone(['so_tb' => $arr_params['phone_contact']]);

                        if($data_output['code']== 1) {
                            $orders_data          = new OrdersData();
                            $modelOrder           = new WOrders();
                            $orderDetails         = new WOrderDetails();
                            $modelOrder->scenario = 'register_package_rx';

                            // get package detail by code_vnpt
                            $package_id   = isset($arr_params['package_id']) ? $arr_params['package_id'] : '';
                            $modelPackage = WPackage::getPackageByCodeVnpt($package_id);

                            //check exit package
                            if ($modelPackage) {
                                $orders_data->package = $modelPackage;//display view panel_order
                                $modelOrder->id       = $modelOrder->generateOrderId();
                                $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);

                                $modelOrder->attributes     = array(
                                    'phone_contact' => $arr_params['phone_contact']
                                );
                                $modelOrder->payment_method = (string)WPaymentMethod::PM_AIRTIME;

                                if ($modelOrder->validate()) {
                                    //order state
                                    $orderState = new WOrderState();
                                    $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                                    $orders_data->order_state = $orderState;
                                    //cache order key
                                    $cache_key                              = 'orders_data_package_roaming_' . $modelOrder->id;
                                    $orders_data->orders                    = $modelOrder;
                                    $orders_data->order_details['packages'] = $orderDetails->attributes;

                                    //call api check cvqt
                                    $data_check_ir = array(
                                        'so_tb' => $modelOrder->phone_contact,
                                    );
                                    $check_ir      = $orders_data->dataRoamingCheckIr($data_check_ir);
                                    if ($check_ir) {
                                        //call api send otp
                                        $data          = array(
                                            'so_tb'       => $modelOrder->phone_contact,
                                            'service_otp' => 'rx_register',
                                        );
                                        $response      = $orders_data->dataRoamingSendOtp($data);
                                        $response_code = isset($response['code']) ? $response['code'] : '';
                                        $msg           = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');
                                        if ($response_code == 1) {
                                            $orders_data->session_cart = time();//count down OTP
                                            //set cache order
                                            Yii::app()->cache->set($cache_key, $orders_data);

                                            $otpModel           = new OtpForm();
                                            $otpModel->scenario = 'checkTokenKey';
                                            // success send otp
                                            $results = array(
                                                'ok'    => TRUE,
                                                'data'  => array(
                                                    'order_id' => $modelOrder->id
                                                ),
                                                'error' => NULL
                                            );
                                        } else {//error send otp
                                            $results['error'] = 'Fail to send otp';
                                            $results['code'] =  8;
                                            $logMsg[]         = array($msg, 'Fail to send otp:' . __LINE__, 'E', time());
                                        }
                                    } else {
                                        $results['error'] = 'Fail to check cvqt';
                                        $results['code'] =  7;
                                        $logMsg[]         = array('Fail to check cvqt', 'Check function dataRoamingSendOtp:' . __LINE__, 'E', time());
                                    }
                                } else {
                                    $results['error'] = 'Invalid params';
                                    $results['code'] =  3;
                                    $logMsg[]         = array('empty: phone_contact||package_id', 'Invalid params:' . __LINE__, 'E', time());
                                }
                            } else {
                                $results['error'] = 'Package does not exits';
                                $results['code'] =  6;
                                $logMsg[]         = array('Package does not exits', 'check get modelPackage:' . __LINE__, 'E', time());
                            }
                        }else{
                            $results['error'] = 'Error vinaphone';
                            $results['code'] =  5;
                            $logMsg[]         = array('Error vinaphone', 'check api:' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Secure not match';
                        $results['code'] =  4;
                        $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                    }
                }else{
                    $results['error'] = 'Invalid params';
                    $results['code'] =  3;
                    $logMsg[]         = array('check params', 'Invalid params:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $results['code'] =  2;
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            if($results['ok']){
                header('HTTP/1.0 200 Success');
            }else{
                header("HTTP/1.1 500 Server busy");
            }
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * RegisterRx
         */
        public function actionRegisterRx(){
            $file_name = 'RegisterRx-Apisgw';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $required_params = ["channel", "order_id", "phone_contact", "package_id", "token", "transaction_id", "secure"];
            if (Yii::app()->request->isPostRequest) {
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());
                if($this->validateParams($required_params, $params)) {
                    //request params
                    $secure = $params['secure'];

                    $arr_params = array_diff_key($params, ['secure' => "xy"]);

                    $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                    $logMsg[] = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                    $logMsg[] = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                    if ($secure == $secure_hash) {
                        $orders_data          = new OrdersData();
                        $modelOrder           = new WOrders();
                        $orderDetails         = new WOrderDetails();
                        $modelOrder->scenario = 'register_package_rx';

                        // get package detail by code_vnpt
                        $package_id   = isset($arr_params['package_id']) ? $arr_params['package_id'] : '';
                        $modelPackage = WPackage::getPackageByCodeVnpt($package_id);

                        //check exit package
                        if ($modelPackage) {
                            $orders_data->package = $modelPackage;//display view panel_order
                            $modelOrder->id       = $arr_params['order_id'];
                            $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);
                            $orderDetails->transaction_id = $arr_params['transaction_id'];

                            $modelOrder->attributes     = array(
                                'phone_contact' => $arr_params['phone_contact']
                            );
                            $modelOrder->payment_method = (string)WPaymentMethod::PM_AIRTIME;

                            if ($modelOrder->validate()) {
                                //order state
                                $orderState = new WOrderState();
                                $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                                $orders_data->order_state = $orderState;
                                $orders_data->orders                    = $modelOrder;
                                $orders_data->order_details['packages'] = $orderDetails->attributes;
                                
                                $modelOrder    = $orders_data->orders;
                                $order_details = $orders_data->order_details;
                                $order_state   = $orders_data->order_state;
                                
                                $otpModel           = new OtpForm();
                                $otpModel->scenario = 'checkTokenKey';
                                $otpModel->attributes = array(
                                    'token' => $arr_params['token']
                                );
                                $otpModel->msisdn     = $modelOrder->phone_contact;
                                if ($otpModel->validate()) {
                                    $modelOrder->otp = $otpModel->token;
                                    //call api send otp
                                    $data          = array(
                                        'orders'        => $modelOrder->attributes,
                                        'order_details' => $order_details,
                                        'order_state'   => $order_state->attributes,
                                    );
                                    $response      = $orders_data->dataRoamingRegisterRx($data);
                                    $response_code = isset($response['code']) ? $response['code'] : '';
                                    $msg           = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                                    if ($response_code == 1) {
                                        //render html
                                        $results = array(
                                            'ok'    => TRUE,
                                            'data'  => array(
                                                'order_id' => $modelOrder->id,
                                            ),
                                            'error' => NULL
                                        );
                                    } else {
                                        $results['error'] = 'Fail to Register ';
                                        $logMsg[]         = array($msg, 'Check register:' . __LINE__, 'E', time());
                                    }
                                } else {
                                    $results['error'] = 'Invalid params';
                                    $logMsg[]         = array('token', 'Invalid params:' . __LINE__, 'E', time());
                                }
                            }else{
                                $results['error'] = 'Invalid params';
                                $logMsg[]         = array('in model order', 'Invalid params:' . __LINE__, 'E', time());
                            }
                        }else{
                            $results['error'] = 'Package does not exits';
                            $logMsg[]         = array('Package does not exits', 'check get modelPackage:' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Secure not match';
                        $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                    }
                }else{
                    $results['error'] = 'Invalid params';
                    $logMsg[]         = array('check params', 'Invalid params:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            if($results['ok']){
                header('HTTP/1.0 200 Success');
            }else{
                header("HTTP/1.1 500 Server busy");
            }
            echo CJSON::encode($results);
            Yii::app()->end();
        }
        /**
         * Get Packages
         * return json
         */
        public function actionGetPackages()
        {
            $file_name = 'getPackage-Apisgw';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $required_params = ['channel', 'secure'];

            $params  = $_GET;//params request
            $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());
            if($this->validateParams($required_params, $params)) {
                //request params
                $secure = $params['secure'];

                $arr_params = array_diff_key($params, ['secure' => "xy"]);

                $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                $logMsg[] = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                $logMsg[] = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                if ($secure == $secure_hash) {
                    $packages = WPackage::getPackageAPI();
                    if ($packages) {
                        $rows = array();
                        foreach ($packages as $package) {
                            $rows[] = array_filter($package->attributes);
                        }
                        $results['ok']   = TRUE;
                        $results['data'] = $rows;
                    } else {
                        $results['error'] = 'Package not found';
                        $logMsg[]         = array('not found', 'Package API:' . __LINE__, 'E', time());
                    }
                } else {
                    $results['error'] = 'Secure not match';
                    $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                }
            }else{
                $results['error'] = 'Invalid params';
                $logMsg[]         = array('channel | secure', 'Invalid params: ' . __LINE__, 'E', time());
            }

            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            if($results['ok']){
                header('HTTP/1.0 200 Success');
            }else{
                header("HTTP/1.1 500 Server busy");
            }
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * action confirm register ir(cvqt)
         */
        public function actionConfirmRegisterIr()
        {
            $file_name = 'ConfirmRegisterIr-Apisgw';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $required_params = ["channel", "phone_contact", "transaction_id", "secure"];
            if (Yii::app()->request->isPostRequest) {
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());
                if($this->validateParams($required_params, $params)) {
                    //request params
                    $secure = $params['secure'];

                    $arr_params = array_diff_key($params, ['secure' => "xy"]);

                    $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                    $logMsg[] = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                    $logMsg[] = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                    if ($secure == $secure_hash) {
                        //call api send otp
                        $orders_data = new OrdersData();
                        $data = array(
                            'so_tb' => $arr_params['phone_contact'],
                        );
                        $response = $orders_data->dataRoamingConfirmRegisterIr($data);
                        $response_code = isset($response['code']) ? $response['code'] : '';

                        if ($response_code == 1) {
                            $results['ok'] = TRUE;
                            $results['data'] = [
                                'phone_contact' => $arr_params['phone_contact']
                            ];
                        }else{
                            $results['error'] = 'Fail to register cvqt';
                            $logMsg[]         = array('Fail to register cvqt', 'API dataRoamingConfirmRegisterIr:' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Secure not match';
                        $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                    }
                }else{
                    $results['error'] = 'Invalid params';
                    $logMsg[]         = array('check params', 'Invalid params:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            if($results['ok']){
                header('HTTP/1.0 200 Success');
            }else{
                header("HTTP/1.1 500 Server busy");
            }
            echo CJSON::encode($results);
            Yii::app()->end();
        }

        /**
         * action confirm otp->cvqt->register Rx
         */
        public function actionVerifyRegisterIr()
        {
            $file_name = 'VerifyRegisterIr-Apisgw';
            $logMsg    = array();
            $logMsg[]  = array('Start apis gateway checkout Log', 'Start process: ' . __LINE__, 'I', time());
            $logMsg[]  = array(Yii::app()->request->requestUri, 'Request URI: ' . __LINE__, 'T', time());
            $results   = array(
                'ok'    => FALSE,
                'data'  => NULL,
                'error' => NULL
            );
            $required_params = ["channel", "phone_contact", "token", "transaction_id", "secure"];
            if (Yii::app()->request->isPostRequest) {
                $params  = CJSON::decode(file_get_contents('php://input'));//params request
                $logMsg[] = array(CJSON::encode($params), 'Request params:' . __LINE__, 'T', time());
                if($this->validateParams($required_params, $params)) {
                    //request params
                    $secure = $params['secure'];

                    $arr_params = array_diff_key($params, ['secure' => "xy"]);

                    $secure_hash = $this->hashAllFields($arr_params);
//                echo $secure_hash; die;
                    $logMsg[] = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                    $logMsg[] = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                    if ($secure == $secure_hash) {
                        $orders_data = new OrdersData();
                        //call api send otp
                        $data_verify = array(
                            'so_tb' => $arr_params['phone_contact'],
                            'otp' => $arr_params['token'],
                            'service_otp' => 'ir_register',
                        );
                        $response_verify = $orders_data->dataRoamingVerifyRegisterIr($data_verify);
                        $response_code_verify = isset($response_verify['code']) ? $response_verify['code'] : '';

                        if ($response_code_verify == 1) {
                            //call api send otp register rx
                            $data = array(
                                'so_tb' => $arr_params['phone_contact'],
                                'service_otp' => 'rx_register',
                            );
                            $response = $orders_data->dataRoamingSendOtp($data);
                            $response_code = isset($response['code']) ? $response['code'] : '';
                            if ($response_code == 1) {
                                $results['ok'] = TRUE;
                                $results['data'] = [
                                    'phone_contact' => $arr_params['phone_contact'],
                                    'token' => $arr_params['token']
                                ];
                            } else {//error send otp
                                $results['error'] = 'Fail to SEND OTP';
                                $logMsg[]         = array('Fail to SEND OTP', 'Check dataRoamingSendOtp:' . __LINE__, 'E', time());
                            }
                        } else {
                            $results['error'] = 'Fail to Verify OTP';
                            $logMsg[]         = array('Fail to VerifyRegisterIr', 'Check dataRoamingVerifyRegisterIr:' . __LINE__, 'E', time());
                        }
                    } else {
                        $results['error'] = 'Secure not match';
                        $logMsg[]         = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                    }
                }else{
                    $results['error'] = 'Invalid params';
                    $logMsg[]         = array('check params', 'Invalid params:' . __LINE__, 'E', time());
                }
            } else {
                $results['error'] = 'Method not allowed';
                $logMsg[]         = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
            }
            $logMsg[]      = array(CJSON::encode($results), 'Output: ' . __LINE__, 'T', time());
            $logMsg[]      = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $this->writeLogs($file_name, $logMsg);

            header('Content-Type: application/json');
            if($results['ok']){
                header('HTTP/1.0 200 Success');
            }else{
                header("HTTP/1.1 500 Server busy");
            }
            echo CJSON::encode($results);
            Yii::app()->end();
        }
        /**
         * validate params checkout api
         */
        public function validateCheckout($array_param){
            $array_param = array_diff_key($array_param, ['secure' => "xy", 'customer_note' => 'xy']);
            $model = new CheckoutForm();
            $model->attributes = $array_param;
            if($model->validate()){
                return TRUE;
            }
            return FALSE;
        }

        public function validateParams($required_params, $array_param){
            foreach($required_params as $key => $value){
                if(empty($array_param[$value])){
                    return FALSE;
                }
            }
            return TRUE;
        }

    } //end class