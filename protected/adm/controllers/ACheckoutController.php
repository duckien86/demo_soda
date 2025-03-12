<?php

    class ACheckoutController extends AController
    {
        public $layout = '/layouts/main';

        private $isMobile = FALSE;

        public function init()
        {
            parent::init();
            $detect         = new MyMobileDetect();
            $this->isMobile = $detect->isMobile();
            if ($detect->isMobile()) {
                $this->layout = '/layouts/mobile_main';
            }
            $this->pageImage       = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
            $this->pageDescription = Yii::t('web/portal', 'page_description');
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
         * Performs the AJAX validation.
         *
         * @param $model
         *
         * @return string
         */
        protected function performAjaxValidation($model)
        {
            $msg = '';
            if (isset($_POST['AOrders'])) {
                $msg = CActiveForm::validate($model);
            }

            return CJSON::decode($msg);
        }

        /**
         * action get list district by province
         */
        public function actionGetDistrictByProvince()
        {
            $province_code = Yii::app()->request->getParam('province_code', '');
            $district      = ADistrict::getListDistrictByProvince($province_code);
            echo "<option value=''>" . Yii::t('web/portal', 'select_district') . "</option>";
            foreach ($district as $key => $value) {
                echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }
            Yii::app()->end();
        }

        /**
         * action get list ward by district
         */
        public function actionGetWardBrandOfficesByDistrict()
        {
            $district_code = Yii::app()->request->getParam('district_code', '');
            $ward          = AWard::getListWardDistrict($district_code);
            $html_ward     = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
            foreach ($ward as $key => $value) {
                $html_ward .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }

            $brand_offices      = ABrandOffices::getListBrandOfficesByDistrict($district_code);
            $html_brand_offices = "<option value=''>" . Yii::t('web/portal', 'brand_offices') . "</option>";
            foreach ($brand_offices as $key => $value) {
                $html_brand_offices .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }

            echo CJSON::encode(array(
                'html_ward'          => $html_ward,
                'html_brand_offices' => $html_brand_offices,
            ));
            Yii::app()->end();
        }

        /**
         * action get list brand_offices by province, district
         */
        public function actionGetListBrandOffices()
        {
            $ward_code     = Yii::app()->request->getParam('ward_code', '');
            $ward          = AWard::model()->find('id=:code', array(':code' => $ward_code));
            $brand_default = ABrandOffices::getBrandOfficesByWard($ward_code);
            $brand_offices = ABrandOffices::getListBrandOfficesByDistrict($ward->district_code);
            echo "<option value=''>" . Yii::t('web/portal', 'brand_offices') . "</option>";
            foreach ($brand_offices as $key => $value) {
                if ($key == $brand_default) {
                    echo CHtml::tag('option', array('value' => $key, 'selected' => TRUE), CHtml::encode($value), TRUE);
                } else {
                    echo CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
                }
            }
            Yii::app()->end();
        }

        /**
         * action get brand_offices
         */
        public function actionGetBrandOfficesInfo()
        {
            $brand_offices_id = Yii::app()->request->getParam('brand_offices', '');
            $brand_offices    = ABrandOffices::model()->find('id=:id', array(':id' => $brand_offices_id));

            $html = '<div><span class="font_bold">Địa chỉ: </span>' . CHtml::encode($brand_offices->address) . '</div>';
            $html .= '<div><span class="font_bold">Điện thoại: </span>' . CHtml::encode($brand_offices->hotline) . '</div>';
            echo $html;
            Yii::app()->end();
        }

        /**
         * get list package by type
         *
         * @throws CException
         */
        public function actionGetPackageByType()
        {
            $sim_type = Yii::app()->request->getParam('sim_type', '');

            $orders_data = Yii::app()->session['orders_data'];
            if ($sim_type == ASim::TYPE_PREPAID) { //check sim type=>get package
                if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                    $package[]  = $orders_data->package_sim_kit;//array slide package
                    $package_id = !empty($orders_data->package_sim_kit->id) ? $orders_data->package_sim_kit->id : '';
                    $province   = AProvince::getListProvinceByPackageId($package_id);
                } else {
                    //check config package selected (file config.ini)
                    if (!empty($GLOBALS['config_common']['package']['selected'])
                        && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                    ) {
                        $code_selected = $GLOBALS['config_common']['package']['selected'];
                        //fix package selected
                        $package_selected = APackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => APackage::PACKAGE_ACTIVE));
                        $package[]        = $package_selected;//array slide package
                    } else {
                        //array slide package
                        if (!empty(Yii::app()->params->checkout_prepaid)) {
                            $package = APackage::getListPackageById(Yii::app()->params->checkout_prepaid);
                        } else {
                            $package = APackage::getListPackageByType($sim_type, '', FALSE, 0, '', '', '', APackage::FREEDOO_PACKAGE);
                        }
                    }
                    $province = AProvince::getListProvince(TRUE);
                }
            } else {
                //array slide package
                $package  = APackage::getListPackageByType($sim_type, '', FALSE, 0, '', '', '', APackage::FREEDOO_PACKAGE);
                $province = AProvince::getListProvince(TRUE);
            }

            $html_province = "<option value=''>" . Yii::t('web/portal', 'select_province') . "</option>";
            foreach ($province as $key => $value) {
                $html_province .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
            }

            echo CJSON::encode(
                array(
                    'html_package'  => $this->renderPartial('_list_package', array(
                        'package' => $package,
                    ), TRUE),
                    'html_province' => $html_province
                ));

            Yii::app()->end();
        }

        public function actionGetPackageDetail()
        {
            $package_id = Yii::app()->request->getParam('package_id', '');
            $package    = APackage::model()->find('id=:id', array(':id' => $package_id));

            echo CJSON::encode(array('content'      => $this->renderPartial('_block_package_detail', array(
                'package' => $package,
            ), TRUE),
                                     'package_name' => CHtml::encode($package->name)
            ));

        }

        /**
         * actionCheckout
         */
        public function actionCheckout()
        {

            $this->pageTitle = 'Sim số - Đăng ký thông tin';

//            OtpForm::unsetSessionHtmlOrder();
            if (AOrders::checkOrdersSessionExists() === FALSE) {
                OtpForm::unsetSession(TRUE);
                $msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('aCheckout/message', array('t' => 2)));
            } else {
                $orders_data      = Yii::app()->session['orders_data'];
                $modelOrder       = $orders_data->orders;
                $modelSim         = $orders_data->sim;
                $package_type     = $modelSim->type;
                $package_selected = array();
                if ($modelSim->type == ASim::TYPE_PREPAID) { //check sim type=>get package
                    //warning required select package
                    $modelOrder->addError('package', Yii::t('web/portal', 'warning_required_package'));
                    if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                        $package[]  = $orders_data->package_sim_kit;//array slide package
                        $package_id = !empty($orders_data->package_sim_kit->id) ? $orders_data->package_sim_kit->id : '';
                    } else {
                        //check config package selected (file config.ini)
                        if (!empty($GLOBALS['config_common']['package']['selected'])) {
                            $code_selected    = $GLOBALS['config_common']['package']['selected'];
                            $package_selected = APackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => APackage::PACKAGE_ACTIVE));
                        }
                        if (!empty($GLOBALS['config_common']['package']['selected'])
                            && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                        ) {
                            //fix package selected
                            $orders_data->package = $package_selected;
                            $package[]            = $package_selected;//array slide package
                        } else {
                            $package = APackage::getListPackageByDisplayCheckout($package_type);
                            //array slide package
                            /*if (!empty(Yii::app()->params->checkout_prepaid)) {
                                $package = APackage::getListPackageById(Yii::app()->params->checkout_prepaid);
                            } else {
                                $package = APackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', APackage::FREEDOO_PACKAGE);
                            }*/
                        }

                    }
                } else {
                    //array slide package
                    $package = APackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', APackage::FREEDOO_PACKAGE);
                }
                $district      = array();
                $ward          = array();
                $brand_offices = array();

                $modelOrder->scenario = 'register_sim';
                //delivery_type
                if (empty($modelOrder->delivery_type)) {
                    $modelOrder->delivery_type = AOrders::DELIVERY_TYPE_HOME;//set default
                }


                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_step1') {
                    $errors = CJSON::decode(CActiveForm::validate($modelSim));
                    $errors += CJSON::decode(CActiveForm::validate($modelOrder));
                    echo CJSON::encode($errors);
                    Yii::app()->end();
                }
                //Get user admin profile
                $profile_admin = Profile::model()->findByAttributes(array('user_id' => Yii::app()->user->id));
                if ($profile_admin) {
                    $modelOrder->full_name = $modelSim->full_name = $profile_admin->firstname . ' ' . $profile_admin->lastname;
                }
                $modelOrder->phone_contact  = isset(Yii::app()->user->msisdn_eload) ? Yii::app()->user->msisdn_eload : '';
                $modelOrder->address_detail = '185 Giảng Võ';

                $modelOrder->province_code    = isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '';
                $modelOrder->sale_office_code  = isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '';
                $modelOrder->brand_offices_id = isset(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '';
                $modelOrder->district_code    = isset(Yii::app()->user->district_code) ? Yii::app()->user->district_code : '';
                $modelOrder->ward_code        = isset(Yii::app()->user->ward_code) ? Yii::app()->user->ward_code : '';
                $modelOrder->delivery_type    = AOrders::DELIVERY_TYPE_BRAND;
                $modelOrder->payment_method   = AOrders::COD_PAYMENT_METHOD;


                if (isset($_POST['ASim']) && isset($_POST['AOrders'])) {
                    $modelSim->attributes   = $_POST['ASim'];
                    $modelOrder->attributes = $_POST['AOrders'];

                    $modelOrder->full_name = $modelSim->full_name;

                    if ($modelOrder->delivery_type == AOrders::DELIVERY_TYPE_HOME) {
                        $modelOrder->price_ship    = $GLOBALS['config_common']['order']['price_ship'];
                        $modelOrder->brand_offices = $modelOrder->address_detail;
                    } else {
                        $modelOrder->price_ship     = 0;
                        $modelOrder->address_detail = isset($_POST['AOrders']['brand_offices']) ? $_POST['AOrders']['brand_offices'] : '';
                    }
                    $modelOrder->address_detail = '185 Giảng Võ';
                    if ($modelSim->validate() && $modelOrder->validate()) {

                        if (empty(Yii::app()->params->checkout_prepaid)
                            || ($modelSim->type == ASim::TYPE_POSTPAID)
                            || (!empty(Yii::app()->params->checkout_prepaid) && ($modelSim->type == ASim::TYPE_PREPAID) && !empty($_POST['AOrders']['package']))
                        ) {

                            //detail sim
                            $order_details_sim = new AOrderDetails();
                            $order_details_sim->setOrderDetailsSim($modelSim, $modelOrder, $order_details_sim);
                            $orders_data->order_details['sim'] = $order_details_sim->attributes;

                            //detail price_term
                            $detail_price_term = new AOrderDetails();
                            $detail_price_term->setOrderDetailsPriceTerm($modelSim, $modelOrder, $detail_price_term);
                            $orders_data->order_details['price_term'] = $detail_price_term->attributes;

                            //detail price_ship
                            $detail_price_ship = new AOrderDetails();
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
                                $order_details_pack = new AOrderDetails();
                                $order_details_pack->setOrderDetailsPackage($package_raw, $modelOrder, $order_details_pack);
                                $orders_data->order_details['packages'] = $order_details_pack->attributes;
                                $orders_data->package                   = $modelPackage;//display view panel_order

                            } else {
                                unset($orders_data->order_details['packages']);
                                unset($orders_data->package);
                            }

                            //add order detail with card
                            $card_value = 0;
                            if (isset($_POST['AOrders']['card']) && !empty($_POST['AOrders']['card'])) {
                                $modelOrder->card = $_POST['AOrders']['card'];
                                $card_value       = $_POST['AOrders']['card'];
                            }

                            if ($card_value) {
                                $order_details_card = new AOrderDetails();
                                $order_details_card->setOrderDetailsCard($card_value, $modelOrder, $order_details_card);
                                $orders_data->order_details['card'] = $order_details_card->attributes;
                            }

                            //order state
                            $order_state = new AOrderState();
                            $order_state->setOrderState($modelOrder, $order_state, AOrderState::UNCONFIRMED, AOrderState::UNPAID);
                            $orders_data->order_state = $order_state;

                            $orders_data->sim    = $modelSim;
                            $orders_data->orders = $modelOrder;
                            //set session Order
                            Yii::app()->session['orders_data'] = $orders_data;
                            $data                              = array(
                                'sim'           => $modelSim->attributes,
                                'orders'        => $modelOrder->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state'   => $order_state->attributes,
                            );

                            if ($orders_data->buySim($data)) {
                                $order_state->setOrderState($modelOrder, $order_state, AOrderState::CONFIRMED);
                                $data_update = array(
                                    'sim'           => $modelSim->attributes,
                                    'orders'        => $modelOrder->attributes,
                                    'order_details' => $orders_data->order_details,
                                    'order_state'   => $order_state->attributes,
                                );
                                $cod         = new ACod();

                                $arr_result = $cod->createRequestUrl($modelOrder, $orders_data->sim);


                                if ($orders_data->updateOrderStatus($data_update)) {
                                    Yii::app()->cache->set('createSim_' . $modelOrder->id, TRUE);
                                    Yii::app()->user->setFlash('success', 'Đặt hàng thành công!');
                                    $this->redirect($this->createUrl('aCheckout/createSerialSim', array('order_id' => $modelOrder->id)));

                                } else {
                                    $flag = 0;
                                }


                            } else {
                                $msg = Yii::t('web/portal', 'insert_order_fail');
                                $this->redirect($this->createUrl('aCheckout/message', array('t' => 0)));
                            }
                        } else {
                            $modelOrder->addError('package', Yii::t('web/portal', 'err_required_package'));
                        }
                    }
                }

                //order amount
                $amount = (int)$modelOrder->getOrderAmount($orders_data);

                $this->render('checkout_step1', array(
                    'modelSim'        => $modelSim,
                    'modelOrder'      => $modelOrder,
                    'modelPackage'    => !empty($orders_data->package) ? $orders_data->package : $package_selected,
                    'change_sim_type' => $orders_data->change_sim_type,
                    'package'         => $package,
                    'amount'          => $amount,
                ));
            }
        }

        /**
         * action verify token key (check token key)
         * insert order
         */
        public function actionVerifyTokenKey()
        {
            $flag      = 0;
            $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
            if (AOrders::checkOrdersSessionExists($operation) === FALSE) {
                OtpForm::unsetSession(TRUE);
                //$msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('aCheckout/message', array('t' => 2)));
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $sim         = $orders_data->sim;
                $orders      = $orders_data->orders;
                $order_state = $orders_data->order_state;

                //order amount
                if ($orders_data->operation == AOrdersData::OPERATION_BUYSIM) {
                    $pageTitle = 'Sim số';
                    $amount    = (int)$orders->getOrderAmount($orders_data);
                } else {//card, topup
                    $amount    = (int)$orders->getCardOrderAmount($orders_data->card);
                    $pageTitle = 'Mã thẻ';
                }

                $this->pageTitle = $pageTitle . ' - Xác nhận mã xác thực';

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
//                        if ($otpModel->checkTokenKey()) {//Check Token key
                        $order_state->setOrderState($orders, $order_state, AOrderState::CONFIRMED);
                        $data = array(
                            'sim'           => $sim->attributes,
                            'orders'        => $orders->attributes,
                            'order_details' => $orders_data->order_details,
                            'order_state'   => $order_state->attributes,
                        );

                        if ($orders_data->updateOrderStatus($data)) {
                            $this->redirect($this->createUrl('aCheckout/createSerialSim', array('order_id' => $orders->id)));

                        } else {
                            $flag = 0;
                        }

//                        } else {
//                            $msg = Yii::t('web/portal', 'verify_fail');
//                        }
                    }
                }

                $this->render('verify_otp', array(
                    'otpModel'     => $otpModel,
                    'msg'          => $msg,
                    'modelOrder'   => $orders,
                    'modelSim'     => $orders_data->sim,
                    'modelPackage' => $orders_data->package,
                    'amount'       => $amount,
                    'operation'    => $orders_data->operation,
                ));
            }
        }

        /**
         * Khai báo sim
         */
        public function actionCreateSerialSim($order_id)
        {

            $success       = FALSE;
            $serial_number = "";
            $operation     = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
            if (AOrders::checkOrdersSessionExists($operation) === FALSE) {
                OtpForm::unsetSession(TRUE);
                //$msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('aCheckout/message', array('t' => 2)));
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $sim         = $orders_data->sim;
                $orders      = $orders_data->orders;
                $order_state = $orders_data->order_state;

                //order amount
                $pageTitle = 'Sim số';

                $this->pageTitle = $pageTitle . ' - Khơi tạo sim';

                $createSimForm = new CreateSimForm();

                $msg = '';

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'create_sim_form') {
                    echo CActiveForm::validate($createSimForm);
                    Yii::app()->end();
                }

                if (isset($_POST['CreateSimForm'])) {
                    $createSimForm->attributes = $_POST['CreateSimForm'];

                    if(isset(Yii::app()->user->token)){

                        if ($createSimForm->validate()) {

                            // Gọi api đăng ký sim.
                            $data_resquest = array(
                                'user_id'           => -1,
                                'sim_serial_number' => $createSimForm->serial_number,
                                'order_id'          => $order_id,
                            );
                            $serial_number = $createSimForm->serial_number;
                            $data_resquest = CJSON::encode($data_resquest);
                            $security      = self::getSecurity(Yii::app()->user->password_sub, Yii::app()->user->token, $data_resquest);
                            $register      = ASim::registerSim($data_resquest, $security, Yii::app()->user->username);
                            if (isset($register['status']['code']) && ($register['status']['code'] == 1)) {
                                $orders->serial_number = $createSimForm->serial_number;
                                $orders_data->orders = $orders;
                                //set session Order
                                Yii::app()->session['orders_data'] = $orders_data;

                                $msg     = $register['status']['msg'];
                                $success = TRUE;
                                Yii::app()->cache->delete('createSim_'.$order_id);
                            } else {
                                $msg     = $register['status']['msg'];
                                $success = FALSE;
                            }
                        }
                    }else{
                        echo "<script>alert('Token của bạn đã hết hiệu lực, vui lòng đăng nhập lại để thực hiện khởi tạo SIM');</script>";
                    }
                }

                $this->render('create_serial_sim', array(
                    'model'         => $createSimForm,
                    'msg'           => $msg,
                    'success'       => $success,
                    'order_id'      => $order_id,
                    'serial_number' => $serial_number,
                ));
            }
        }


        public function actionMessage($t = '')
        {
            /* 1: success
             * 2: session_timeout
             * 4: payment_fail
             * 5: error_payment
             */
            $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
            if ($operation == AOrdersData::OPERATION_TOPUP || $operation == AOrdersData::OPERATION_BUYCARD) {
                $pageTitle = 'Mã thẻ';
                $view      = '/card/';
            } else {
                $pageTitle = 'Sim số';
                $view      = '/aCheckout/';
            }

            $this->pageTitle = $pageTitle . ' - Thông báo';

            $order_id = '';
            if (AOrders::checkOrdersSessionExists($operation)) {
                $orders_data = Yii::app()->session['orders_data'];
                $order_id    = $orders_data->orders->id;
            }

            OtpForm::unsetSession();


            switch ($t) {
                case 1:
                    $this->render($view . 'message_success', array('order_id' => $order_id));
                    break;
                case 2:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'session_timeout')
                    ));
                    break;
                case 3:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'err_verify_limited')
                    ));
                    break;
                case 4:
                    $this->render('message_fail', array(
                        'msg' => Yii::t('web/portal', 'payment_fail')
                    ));
                    break;
                case 5:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'error_payment')
                    ));
                    break;
                case 8:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'get_token_key_fail')
                    ));
                    break;
                case 9:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'send_mt_fail')
                    ));
                    break;
                case 10:
                    $this->render($view . 'message_fail', array(
                        'msg' => Yii::t('web/portal', 'customer_cancel')
                    ));
                    break;
                default:
                    $this->render($view . 'message_fail');
            }
        }

        /**
         * action get order price by ajax
         */
        public function actionGetOrderPrice()
        {
            $orders_data         = Yii::app()->session['orders_data'];
            $modelOrderNew       = new AOrders();
            $modelSimNew         = new ASim();
            $modelSimNew->msisdn = $orders_data->sim->msisdn;

            $amount       = 0;
            $modelPackage = array();
            if (isset($_POST['ASim']) && isset($_POST['AOrders'])) {
                $modelOrderNew->attributes = $_POST['AOrders'];
                $modelSimNew->attributes   = $_POST['ASim'];
                $modelSimNew->raw_data     = $orders_data->sim->raw_data;
                $modelOrderNew->price_ship = 0;
                if (isset($_POST['AOrders']['package'][0])) {
                    $modelPackage = APackage::model()->findByPk($_POST['AOrders']['package'][0]);
                }

                if ($modelSimNew->type != $orders_data->sim->type) {
                    $modelPackage = NULL;
                }
                if ($modelSimNew->type == ASim::TYPE_PREPAID) {
                    if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                        $modelPackage = $orders_data->package_sim_kit;
                    } else {
                        //check config package selected (file config.ini)
                        if (!empty($GLOBALS['config_common']['package']['selected'])
                            && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                        ) {
                            $code_selected = $GLOBALS['config_common']['package']['selected'];
                            //fix package selected
                            $package_selected = APackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => APackage::PACKAGE_ACTIVE));
                            $modelPackage     = $package_selected;
                        }
                    }
                }

                // tinh gia
                $amount = $modelOrderNew->calculatePrice($modelOrderNew, $modelSimNew, $orders_data->sim->raw_data, $modelPackage, $orders_data);

                $orders_data->sim->price      = $modelSimNew->price;
                $orders_data->sim->price_term = $modelSimNew->price_term;
                $orders_data->sim->type       = $modelSimNew->type;

                $orders_data->orders->delivery_type = $modelOrderNew->delivery_type;
                $orders_data->orders->price_ship    = $modelOrderNew->price_ship;

                if ($modelPackage) {
                    $orders_data->package = $modelPackage;
                } else {
                    $orders_data->package = NULL;
                }
                Yii::app()->session['orders_data'] = $orders_data;
            }

            echo CJSON::encode(array(
                    'content' => $this->renderPartial('_panel_order_table', array(
                        'modelSim'     => $modelSimNew,
                        'modelOrder'   => $modelOrderNew,
                        'modelPackage' => $modelPackage,
                        'amount'       => $amount,
                    ), TRUE))
            );
        }


        public function getSecurity($password, $token, $data)
        {
            $milliseconds  = round(microtime(TRUE) * 1000);
            $array_encrypt = array(
                'token'         => $token,
                'time'          => $milliseconds,
                'pass'          => $password,
                'data_checksum' => md5($data),
            );
            $array_encrypt = CJSON::encode($array_encrypt);
            $security      = self::encrypt_noreplace($array_encrypt, $password, MCRYPT_RIJNDAEL_128);

            return $security;
        }

        public function safe_b64encode_noreplace($string)
        {
            $data = base64_encode($string);

//            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode_noreplace($data)
        {
//            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public function encrypt_noreplace($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode_noreplace(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        public function decrypt_noreplace($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, $this->safe_b64decode_noreplace($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

    } //end class