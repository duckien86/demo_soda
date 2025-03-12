<?php

class CheckoutController extends Controller
{
    public $layout = '/layouts/main';

    private $isMobile = FALSE;

    public function init()
    {
        parent::init();
        $detect = new MyMobileDetect();
        $this->isMobile = $detect->isMobile();
        if ($detect->isMobile()) {
            $this->layout = '/layouts/mobile_main';
        }
        $this->pageImage = 'http://' . SERVER_HTTP_HOST . Yii::app()->theme->baseUrl . '/images/slider1.jpg';
        $this->pageDescription = Yii::t('web/portal', 'page_description');
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
     * action get list district by province
     */
    public function actionGetDistrictByProvince()
    {
        $province_code = Yii::app()->request->getParam('province_code', '');
        $district = WDistrict::getListDistrictByProvince($province_code);
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
        $ward = WWard::getListWardDistrict($district_code);
        $html_ward = "<option value=''>" . Yii::t('web/portal', 'select_ward') . "</option>";
        foreach ($ward as $key => $value) {
            $html_ward .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }

        $brand_offices = WBrandOffices::getListBrandOfficesByDistrict($district_code);
        $html_brand_offices = "<option value=''>" . Yii::t('web/portal', 'brand_offices') . "</option>";
        foreach ($brand_offices as $key => $value) {
            $html_brand_offices .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }

        echo CJSON::encode(array(
            'html_ward' => $html_ward,
            'html_brand_offices' => $html_brand_offices,
        ));
        Yii::app()->end();
    }

    /**
     * action get list brand_offices by province, district
     */
    public function actionGetListBrandOffices()
    {
        $ward_code = Yii::app()->request->getParam('ward_code', '');
        $ward = WWard::model()->find('id=:code', array(':code' => $ward_code));
        $brand_default = WBrandOffices::getBrandOfficesByWard($ward_code);
        $brand_offices = WBrandOffices::getListBrandOfficesByDistrict($ward->district_code);
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
        $brand_offices = WBrandOffices::model()->find('id=:id', array(':id' => $brand_offices_id));

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
        if ($sim_type == WSim::TYPE_PREPAID) { //check sim type=>get package
            if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                $package[] = $orders_data->package_sim_kit;//array slide package
                $package_id = !empty($orders_data->package_sim_kit->id) ? $orders_data->package_sim_kit->id : '';
                $province = WProvince::getListProvinceByPackageId($package_id);
            } else {
                //check config package selected (file config.ini)
                if (!empty($GLOBALS['config_common']['package']['selected'])
                    && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                ) {
                    $code_selected = $GLOBALS['config_common']['package']['selected'];
                    //fix package selected
                    $package_selected = WPackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => WPackage::PACKAGE_ACTIVE));
                    $package[] = $package_selected;//array slide package
                } else {
                    //array slide package
                    if (!empty(Yii::app()->params->checkout_prepaid)) {
                        $package = WPackage::getListPackageById(Yii::app()->params->checkout_prepaid);
                    } else {
                        $package = WPackage::getListPackageByType($sim_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
                    }
                }
                $province = WProvince::getListProvince(TRUE);
            }
        } else {
            //array slide package
            $package = WPackage::getListPackageByType($sim_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
            $province = WProvince::getListProvince(TRUE);
        }

        $html_province = "<option value=''>" . Yii::t('web/portal', 'select_province') . "</option>";
        foreach ($province as $key => $value) {
            $html_province .= CHtml::tag('option', array('value' => $key), CHtml::encode($value), TRUE);
        }

        echo CJSON::encode(
            array(
                'html_package' => $this->renderPartial('_list_package', array(
                    'package' => $package,
                ), TRUE),
                'html_province' => $html_province
            ));

        Yii::app()->end();
    }

    public function actionGetPackageDetail()
    {
        $package_id = Yii::app()->request->getParam('package_id', '');
        $package = WPackage::model()->find('id=:id', array(':id' => $package_id));
        echo CJSON::encode(array('content' => $this->renderPartial('_block_package_detail', array(
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
        OtpForm::unsetSessionHtmlOrder();
        if (WOrders::checkOrdersSessionExists() === FALSE) {
            OtpForm::unsetSession(TRUE);
            $msg = Yii::t('web/portal', 'session_timeout');
            $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $modelOrder = $orders_data->orders;
            $modelSim = $orders_data->sim;
            $modelPackage = $orders_data->package;
            $package_type = $modelSim->type;
            $package_selected = array();

            $modelOrder->sim_type = $orders_data->sim_type;

            if ($modelSim->type == WSim::TYPE_PREPAID) { //check sim type=>get package
                //warning required select package
                $modelOrder->addError('package', Yii::t('web/portal', 'warning_required_package'));
                if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                    $package[] = $orders_data->package_sim_kit;//array slide package
                    $package_id = !empty($orders_data->package_sim_kit->id) ? $orders_data->package_sim_kit->id : '';
                    $province = WProvince::getListProvinceByPackageId($package_id);
                } else {
                    //check config package selected (file config.ini)
                    if (!empty($GLOBALS['config_common']['package']['selected'])) {
                        $code_selected = $GLOBALS['config_common']['package']['selected'];
                        $package_selected = WPackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => WPackage::PACKAGE_ACTIVE));
                    }else{
                        $packageSession = Yii::app()->session['ss_package_id'];
                        $package_selected = WPackage::model()->find('id=:id', array(':id' => $packageSession));
                        $orders_data->package = $package_selected;
                        Yii::app()->session['orders_data']->package = $package_selected;
                    }
                    if (!empty($GLOBALS['config_common']['package']['selected'])
                        && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                    ) {
                        //fix package selected
                        $orders_data->package = $package_selected;
                        $package[] = $package_selected;//array slide package
                    } else {
                        $package = WPackage::getListPackageByDisplayCheckout($package_type);
                        //array slide package
//                            if (!empty(Yii::app()->params->checkout_prepaid)) {
//                                $package = WPackage::getListPackageById(Yii::app()->params->checkout_prepaid);
//                            } else {
//                                $package = WPackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
//                            }
                    }
                    $province = WProvince::getListProvince(TRUE);
                }
            } else {
                //array slide package
                $package = WPackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
                $province = WProvince::getListProvince(TRUE);
            }
            $district = array();
            $ward = array();
            $brand_offices = array();

            $modelOrder->scenario = 'register_sim';
            //delivery_type
            if (empty($modelOrder->delivery_type)) {
                $modelOrder->delivery_type = WOrders::DELIVERY_TYPE_HOME;//set default
            }

            //get list district
            if (isset($_POST['WOrders']['province_code']) || !empty($modelOrder->province_code)) {
                $province_code = isset($_POST['WOrders']['province_code']) ? $_POST['WOrders']['province_code'] : $modelOrder->province_code;
                $district = WDistrict::getListDistrictByProvince($province_code);
            }

            //get list ward
            if (isset($_POST['WOrders']['district_code']) || !empty($modelOrder->district_code)) {
                $district_code = isset($_POST['WOrders']['district_code']) ? $_POST['WOrders']['district_code'] : $modelOrder->district_code;
                $ward = WWard::getListWardDistrict($district_code);
            }

            //get list brand_offices
            if (isset($_POST['WOrders']['district_code']) || !empty($modelOrder->district_code)) {
                $district_code = isset($_POST['WOrders']['district_code']) ? $_POST['WOrders']['district_code'] : $modelOrder->district_code;
                $brand_offices = WBrandOffices::getListBrandOfficesByDistrict($district_code);
            }

            //validate ajax
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_step1') {
                $errors = CJSON::decode(CActiveForm::validate($modelSim));
                $errors += CJSON::decode(CActiveForm::validate($modelOrder));
                echo CJSON::encode($errors);
                Yii::app()->end();
            }


            if (isset($_POST['WSim']) && isset($_POST['WOrders'])) {
                $modelSim->attributes = $_POST['WSim'];
                $modelOrder->attributes = $_POST['WOrders'];
                $modelOrder->sim_type = $_POST['WOrders']['sim_type'];
                $modelOrder->full_name = $modelSim->full_name;

                if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
                    $modelOrder->price_ship = $GLOBALS['config_common']['order']['price_ship'];
                    $modelOrder->brand_offices = $modelOrder->address_detail;
                } else {
                    $modelOrder->price_ship = 0;
                    $modelOrder->address_detail = isset($_POST['WOrders']['brand_offices']) ? $_POST['WOrders']['brand_offices'] : '';
                }

                if ($modelSim->validate() && $modelOrder->validate()) {
//                        if (empty(Yii::app()->params->checkout_prepaid)
//                            || ($modelSim->type == WSim::TYPE_POSTPAID)
//                            || (!empty(Yii::app()->params->checkout_prepaid) && ($modelSim->type == WSim::TYPE_PREPAID) && !empty($_POST['WOrders']['package']))
//                        ) {
                    //if empty promo_code: check cookie affiliate
                    if (empty($modelOrder->promo_code)) {
                        if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])
                            && empty($modelOrder->affiliate_source)//vne: apisgw/addtocart
                        ) {
                            if (Yii::app()->request->cookies['utm_source']->value == 'Zalo' || Yii::app()->request->cookies['utm_source']->value == 'zalo') {
                                $modelOrder->affiliate_source = '';
                            } else {
                                $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                            }
                        }
                        if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])
                            && empty($modelOrder->affiliate_transaction_id)//vne: apisgw/addtocart
                        ) {
                            if (Yii::app()->request->cookies['utm_source']->value == 'Zalo' || Yii::app()->request->cookies['utm_source']->value == 'zalo') {
                                $modelOrder->affiliate_transaction_id = '';
                            } else {
                                $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                            }
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
                        $orders_data->package = $modelPackage;//display view panel_order
                    } else {
                        unset($orders_data->order_details['packages']);
                        unset($orders_data->package);
                    }

                    //add order detail with card
                    $card_value = 0;
                    if (isset($_POST['WOrders']['card']) && !empty($_POST['WOrders']['card'])) {
                        $modelOrder->card = $_POST['WOrders']['card'];
                        $card_value = $_POST['WOrders']['card'];
                    }

                    if ($card_value) {
                        $order_details_card = new WOrderDetails();
                        $order_details_card->setOrderDetailsCard($card_value, $modelOrder, $order_details_card);
                        $orders_data->order_details['card'] = $order_details_card->attributes;
                    }

                    //order state
                    $order_state = new WOrderState();
                    $order_state->setOrderState($modelOrder, $order_state, WOrderState::UNCONFIRMED, WOrderState::UNPAID);
                    $orders_data->order_state = $order_state;

                    $orders_data->sim = $modelSim;
                    $orders_data->orders = $modelOrder;
                    //set session Order
                    Yii::app()->session['orders_data'] = $orders_data;
                    $data = array(
                        'sim' => $modelSim->attributes,
                        'orders' => $modelOrder->attributes,
                        'order_details' => $orders_data->order_details,
                        'order_state' => $order_state->attributes,
                    );

                    if ($orders_data->buySim($data)) {
                        $this->redirect($this->createUrl('checkout/checkout2'));
                    } else {
                        if (!empty(Yii::app()->session['msg_code'])) {
                            $this->redirect($this->createUrl('checkout/message', array('t' => Yii::app()->session['msg_code'])));
                        } else {
                            $msg = Yii::t('web/portal', 'insert_order_fail');
                            $this->redirect($this->createUrl('checkout/message', array('t' => 0)));
                        }
                    }
//                        } else {
//                            $modelOrder->addError('package', Yii::t('web/portal', 'err_required_package'));
//                        }
                }
            }

            //order amount
            $amount = (int)$modelOrder->getOrderAmount($orders_data);

            $this->render('checkout_step1', array(
                'modelSim' => $modelSim,
                'modelOrder' => $modelOrder,
                'modelPackage' => !empty($orders_data->package) ? $orders_data->package : $package_selected,
                'change_sim_type' => $orders_data->change_sim_type,
                'package' => $package,
                'province' => $province,
                'district' => $district,
                'ward' => $ward,
                'brand_offices' => $brand_offices,
                'amount' => $amount,
            ));
        }
    }

    /**
     * actionCheckout step 2
     */
    public function actionCheckout2()
    {

        $this->pageTitle = 'Sim số - Thanh toán';
        if (WOrders::checkOrdersSessionExists() === FALSE) {
            OtpForm::unsetSession(TRUE);
            $msg = Yii::t('web/portal', 'session_timeout');
            $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $orders = $orders_data->orders;
            $packageSession = Yii::app()->session['ss_package_id'];

            if ($packageSession){

                $package_selected = WPackage::model()->find('id=:id', array(':id' => $packageSession));
                $orders_data->package = $package_selected;
            }


            //order amount
            $amount = (int)$orders->getOrderAmount($orders_data);

            //get location_napas, location_vietinbank
            $location_napas = WLocationNapas::model()->find('id=:id', array(':id' => $orders->province_code));
            $location_vietinbank = WLocationVietinbank::model()->find('id=:id', array(':id' => $orders->province_code));
            $location_vnptpay = WLocationVnptpay::model()->find('id=:id', array(':id' => $orders->province_code));
            $modelSim = $orders_data->sim;
            $model_pm = new WPaymentMethod();
            if (isset($_POST['WOrders']) && isset($_POST['WOrders']['payment_method']) && !empty($_POST['WOrders']['payment_method'])) {
                $payment_method = $_POST['WOrders']['payment_method'];
                $orders->payment_method = $payment_method;//payment_method


                if (isset(Yii::app()->session['message_order'])) {
                    $message_order = Yii::app()->session['message_order'];
                    $message_order['payment_method'] = $payment_method;
                    Yii::app()->session['message_order'] = $message_order;
                }

                $arr_result = array('urlRequest' => '', 'msg' => '');


                switch ($payment_method) {
                    case WPaymentMethod::PM_NAPAS_ATM:
                        $arr_result = $model_pm->paymentViaNapas($orders_data, $amount, $location_napas);
                        break;
                    case WPaymentMethod::PM_NAPAS_INT:
                        $arr_result = $model_pm->paymentViaNapas($orders_data, $amount, $location_napas);
                        break;
                    case WPaymentMethod::PM_VNPAY:
                        $arr_result = $model_pm->paymentViaVietinbankVnpay($orders_data, $amount, $location_vietinbank);
                        break;
                    case WPaymentMethod::PM_VIETINBANK:
//                            $arr_result = $model_pm->paymentViaVietinbank($orders_data, $amount, $location_vietinbank);

                        $arr_result = array(
                            'urlRequest' => Yii::app()->controller->createUrl('checkout/confirmVietinbank'),
                            'msg' => ''
                        );
                        break;
                    case WPaymentMethod::PM_QR_CODE:
                        $arr_result = $model_pm->paymentViaQRCode($orders_data, $amount, $location_vietinbank);
                        break;
                    case WPaymentMethod::PM_VIETIN_ATM:
                        $arr_result = $model_pm->paymentViaVietinAtm($orders_data, $amount, $location_vietinbank);
                        break;
                    case WPaymentMethod::PM_VNPT_PAY:
                        $arr_result = $model_pm->paymentViaVnptPay($orders_data, $amount, $location_vnptpay);
                        break;
                    default://cod
                        $cod = new Cod();
                        /*if (isset($modelSim->raw_data['price_term']) && $modelSim->raw_data['price_term'] > 0 && $payment_method == WPaymentMethod::PM_COD) {
                            if (!Yii::app()->user->hasFlash('danger')) {
                                Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'payment_method_cod'));
                            }
                            $this->redirect($this->createUrl('checkout/checkout2'));
                        }*/

                        $arr_result = $cod->createRequestUrl($orders, $orders_data->sim);

                }
                $orders_data->orders = $orders;

                //set session
                Yii::app()->session['orders_data'] = $orders_data;


                Yii::app()->user->setFlash('success', $arr_result['msg']);
                $this->redirect($arr_result['urlRequest']);


            } else {
                if (!Yii::app()->user->hasFlash('danger')) {
                    Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'payment_method_required'));
                }
            }
            //array payment accept
            $arr_payment = array(
                'qr_code' => FALSE, //$model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_QR_CODE, $location_vietinbank, $location_napas, $location_vnptpay),
                'napas_atm' => FALSE,/*$model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_NAPAS_ATM, $location_vietinbank, $location_napas, $location_vnptpay),*/
                'napas_int' => FALSE,
                'cod' => $model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_COD),
                'vietinbank' => FALSE, //$model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_VIETINBANK, $location_vietinbank, $location_napas, $location_vnptpay),//the quoc te
//                    'vietinbank' => FALSE,//the quoc te
                'vietin_atm' => FALSE,//$model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_VIETIN_ATM, $location_vietinbank, $location_napas, $location_vnptpay), //the noi dia
                'vnpt_pay' => FALSE, /*$model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_VNPT_PAY, $location_vietinbank, $location_napas, $location_vnptpay),*/ //vnpt_pay
                'vnpay' => FALSE, //vnpay
            );

            /*if (isset($modelSim->raw_data['price_term']) && $modelSim->raw_data['price_term'] > 0) {
                $arr_payment['cod'] = FALSE;
            }*/


            //active cod CSKH, admin
            if (isset($orders->active_cod) && $orders->active_cod == Orders::ACTIVE_COD) {
                $arr_payment['cod'] = TRUE;

            }

            //fix test username: vtb_test, pass=123456vtb
            if (!Yii::app()->user->isGuest && Yii::app()->user->sso_id == 'vbdpw8ftxnkgjoq9yc75z643ilra2sue') {
                $arr_payment['vietin_atm'] = TRUE;
            }


            $this->render('checkout_step2', array(
                'modelOrder' => $orders,
                'modelSim' => $modelSim,
                'modelPackage' => $orders_data->package,
                'operation' => $orders_data->operation,
                'amount' => $amount,
                'arr_payment' => $arr_payment,
            ));
        }
    }

    /**
     * action verify token key (check token key)
     * insert order
     */
    public function actionVerifyTokenKey()
    {
        $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
        if (WOrders::checkOrdersSessionExists($operation) === FALSE) {
            OtpForm::unsetSession(TRUE);
            //$msg = Yii::t('web/portal', 'session_timeout');
            $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $sim = $orders_data->sim;
            $orders = $orders_data->orders;
            $order_state = $orders_data->order_state;

            //order amount
            if ($orders_data->operation == OrdersData::OPERATION_BUYSIM) {
                $pageTitle = 'Sim số';
                $amount = (int)$orders->getOrderAmount($orders_data);
            } else {//card, topup
                $amount = (int)$orders->getCardOrderAmount($orders_data->card);
                $pageTitle = 'Mã thẻ';
            }
            $this->pageTitle = $pageTitle . ' - Xác nhận mã xác thực';

            $otpModel = new OtpForm();
            $otpModel->scenario = 'checkTokenKey';
            $msg = '';

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
                                $this->redirect($this->createUrl('checkout/message', array('t' => 3)));
                            } else {
                                //set time and ++ verify_number
                                Yii::app()->session['verify_number'] += 1;
                                Yii::app()->session['time_reset'] = time();
                            }
                        } else {
                            //not exist-->set time and verify_number
                            Yii::app()->session['verify_number'] = 1;
                            Yii::app()->session['time_reset'] = time();
                        }

                        if ($otpModel->checkTokenKey()) {//Check Token key

                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                            $data = array(
                                'sim' => $sim->attributes,
                                'orders' => $orders->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state' => $order_state->attributes,
                            );
//CVarDumper::dump($data,10,true);die();
                            if ($orders_data->updateOrderStatus($data)) {
                                $flag = 1;
                            } else {
                                $flag = 0;
                            }
                            //redirect to message
                            $this->redirect($this->createUrl('checkout/message', array('t' => $flag)));
                        } else {
                            $msg = Yii::t('web/portal', 'verify_fail');
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'verify_exp_sim',
                            array(
                                '{link_back}' => CHtml::link('Đồng ý', Yii::app()->controller->createUrl('sim/index'))
                            ));
                    }
                }
            }
//            CVarDumper::dump($amount,10,true);
            $this->render('verify_otp', array(
                'otpModel' => $otpModel,
                'msg' => $msg,
                'modelOrder' => $orders,
                'modelSim' => $orders_data->sim,
                'modelPackage' => $orders_data->package,
                'amount' => $amount,
                'operation' => $orders_data->operation,
            ));
        }
    }

    public function actionQrCode()
    {
        $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
        if (WOrders::checkOrdersSessionExists($operation) === FALSE) {
            OtpForm::unsetSession(TRUE);
            //session_timeout
            $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $orders = $orders_data->orders;

            //order amount
            if ($orders_data->operation == OrdersData::OPERATION_BUYSIM) {
                $pageTitle = 'Sim số';
                $amount = (int)$orders->getOrderAmount($orders_data);
            } else {//card, topup
                $amount = (int)$orders->getCardOrderAmount($orders_data->card);
                $pageTitle = 'Mã thẻ';
            }
            $this->pageTitle = $pageTitle . ' - Thanh toán QR Code';

            if (isset(Yii::app()->session['current_qr_code']) && !empty(Yii::app()->session['current_qr_code'])) {
                $callbackurl = $this->createAbsoluteUrl('receiver/getstate', array('redirect' => TRUE));
                $this->render('qr_code', array(
                    'modelOrder' => $orders,
                    'modelSim' => $orders_data->sim,
                    'modelPackage' => $orders_data->package,
//                        'qr_code'      => Yii::app()->session['current_qr_code'] . '&callbackurl=' . $callbackurl,
                    'qr_code' => Yii::app()->session['current_qr_code'],
                    'amount' => $amount,
                    'operation' => $orders_data->operation,
                ));
            } else {
                //redirect to message
                Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'error_qrcode'));
                $this->redirect($this->createUrl('checkout/checkout2'));
            }
        }
    }

    /**
     * confirm payment Vietinbank
     * post form
     */
    public function actionConfirmVietinbank()
    {

        $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
        if (WOrders::checkOrdersSessionExists($operation) === FALSE) {
            OtpForm::unsetSession(TRUE);
            //$msg = Yii::t('web/portal', 'session_timeout');
            $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $orders = $orders_data->orders;

            //order amount
            if ($orders_data->operation == OrdersData::OPERATION_BUYSIM) {
                $amount = (int)$orders->getOrderAmount($orders_data);
                $pageTitle = 'Sim số';
            } else {//card, topup
                $amount = (int)$orders->getCardOrderAmount($orders_data->card);
                $pageTitle = 'Mã thẻ';
            }
            $this->pageTitle = $pageTitle . ' - Xác nhận thanh toán';

            $vietinbank = new Vietinbank();
            $location_vietinbank = WLocationVietinbank::model()->find('id=:id', array(':id' => $orders->province_code));
            $vietinbank = WPaymentMethod::paymentViaVietinbank($orders_data, $amount, $location_vietinbank);

            $this->render('confirm_vietinbank', array(
                'modelOrder' => $orders,
                'modelSim' => $orders_data->sim,
                'modelPackage' => $orders_data->package,
                'operation' => $orders_data->operation,
                'amount' => $amount,
                'vietinbank' => $vietinbank,
            ));
        }
    }

    /**
     * response from napas
     */
    public function actionResponse()
    {
        if (isset($_GET)) {
            $raw_data = $_GET;

            //check params
            if (isset($raw_data['vpc_ResponseCode']) && isset($raw_data['vpc_OrderInfo']) && !empty($raw_data['vpc_OrderInfo'])) {
                //get cache
                $key = 'napas_data_' . $raw_data['vpc_OrderInfo'];
                $cache_data = Yii::app()->redis_napas->get($key);

                if ($cache_data && isset($cache_data['orders_data'])) {
                    $orders_data = $cache_data['orders_data'];
                    $sim = $orders_data->sim;
                    $orders = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    if ($sim && $orders && $order_details && $order_state) {
                        $check_vpc_SecureHash = Napas::checkVpcSecureHashResponse($orders, $raw_data);
                        if (($raw_data['vpc_ResponseCode'] == '0') && ($raw_data['vpc_OrderInfo'] == $orders->id)
                            && ($check_vpc_SecureHash == TRUE)
                        ) {//success
                            //set order_state
                            // $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                            $data = array(
                                'sim' => $sim->attributes,
                                'orders' => $orders->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state' => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) {
                                //success
                                $flag = 1;
                                // clear transaction cache
                                Yii::app()->redis_napas->delete($key);
                            } else {
                                $flag = 0;
                            }
                        } else {
                            //payment_fail
                            $flag = 4;
                        }
                        //insert log DB
                        $vpc_MerchTxnRef = isset($raw_data['vpc_MerchTxnRef']) ? $raw_data['vpc_MerchTxnRef'] : '';
                        $vpc_ResponseCode = $raw_data['vpc_ResponseCode'];
                        $urlRequest = Yii::app()->controller->createAbsoluteUrl('checkout/response');
                        $note = Napas::getContentError($vpc_ResponseCode);
                        $query_string = $_SERVER['QUERY_STRING'];
                        if ($vpc_ResponseCode == '0') {
                            $status_req = WTransactionRequest::REQUEST_SUCCESS;
                        } else {
                            $status_req = WTransactionRequest::REQUEST_FAIL;
                        }
                        WTransactionResponse::writeLog(WTransactionRequest::NAPAS, $orders, $vpc_MerchTxnRef, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note);
                    } else {
                        //timeout
                        $flag = 2;
                    }
                } else {
                    // check order status
                    $order_state = OrderState::model()->findByAttributes(array(
                        'order_id' => $raw_data['vpc_OrderInfo'],
                        'paid' => WOrderState::PAID));
                    if ($order_state) {
                        $flag = 1;
                    } else {
                        $flag = 2; //chưa thanh toán
                    }
                }
            } else {
                //error
                $flag = 5;
            }
        } else {
            //$msg  = Yii::t('web/portal', 'error_payment');
            $flag = 5;
        }

        //redirect to message
        $this->redirect($this->createUrl('checkout/message', array('t' => $flag)));
    }

    /**
     * action receipt from Vietinbank
     */
    public function actionReceipt()
    {
        if (isset($_REQUEST)) {
            $raw_data = array();
            foreach ($_REQUEST as $name => $value) {
                $raw_data[$name] = $value;
            }
            $vietinbank = new Vietinbank();
            //check params
            if (isset($raw_data['signature']) && isset($raw_data['req_reference_number']) && isset($raw_data['reason_code'])) {
                //get cache
                $key = 'vietinbank_data_' . $raw_data['req_reference_number'];
                $cache_data = Yii::app()->redis_vtb->get($key);
                if ($cache_data && isset($cache_data['orders_data'])) {
                    $orders_data = $cache_data['orders_data'];
                    $sim = $orders_data->sim;
                    $orders = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    if ($sim && $orders && $order_details && $order_state) {
                        $status_req = WTransactionRequest::REQUEST_FAIL;
                        if (strcmp($raw_data['signature'], $vietinbank->sign($raw_data)) == 0
                            && $raw_data['reason_code'] == 100 && strtoupper($raw_data['decision']) == Vietinbank::ACCEPT
                        ) {//success
                            $status_req = WTransactionRequest::REQUEST_SUCCESS;
                            //set order_state
//                                $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);
                            $data = array(
                                'sim' => $sim->attributes,
                                'orders' => $orders->attributes,
                                'order_details' => $order_details,
                                'order_state' => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) {
                                //success
                                $flag = 1;
                                // clear transaction cache
                                Yii::app()->redis_vtb->delete($key);
                            } else {
                                $flag = 0;
                            }
                        } else {
                            //$msg  = Yii::t('web/portal', 'payment_fail');
                            $flag = 4;
                        }
                        //insert log DB
                        $req_reference_number = $raw_data['req_reference_number'];
                        $decision = isset($raw_data['decision']) ? $raw_data['decision'] : '';
                        $decision = Vietinbank::getErrorCode($decision, WPaymentMethod::PM_VIETINBANK);
                        $urlRequest = Yii::app()->controller->createAbsoluteUrl('checkout/receipt');
                        $note = isset($raw_data['message']) ? $raw_data['message'] : 'decision: ' . $decision;
                        $query_string = CFunction::implodeParams($raw_data);
                        WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $req_reference_number, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note);
                    } else {
                        //timeout
                        $flag = 2;
                    }
                } else {
                    //timeout
                    $flag = 2;
                    // check order status
                    $log_response = WTransactionResponse::model()->findByAttributes(array(
                        'transaction_id' => $raw_data['req_reference_number']));
                    // check order status
                    if ($log_response) {
                        $order_state = OrderState::model()->findByAttributes(array(
                            'order_id' => $log_response->order_id,
                            'paid' => WOrderState::PAID));
                        if ($order_state) {
                            $flag = 1;
                        }
                    }
                }
            } else {
                //$msg  = Yii::t('web/portal', 'error_payment');
                $flag = 5;
            }
        } else {
            //$msg  = Yii::t('web/portal', 'error_payment');
            $flag = 5;
        }

        //redirect to message
        $this->redirect($this->createUrl('checkout/message', array('t' => $flag)));
    }

    /**
     * action return from Vietinbank VNPay
     */
    public function actionReturn()
    {
        $flag = 0;
        if (isset($_GET) && isset(Yii::app()->session['orders_data']) && !empty(Yii::app()->session['orders_data'])) {
            $raw_data = $_GET;
            //check params
            if (isset($raw_data['vnp_ResponseCode']) && isset($raw_data['vnp_TxnRef'])) {
                $vnp_ResponseCode = $raw_data['vnp_ResponseCode'];
                $vnp_TxnRef = $raw_data['vnp_TxnRef'];
                //get cache
                $key = 'vnpay_data_' . $vnp_TxnRef;
                $cache_data = Yii::app()->redis_vtb->get($key);

                if ($cache_data && isset($cache_data['orders_data'])) {
                    $orders_data = $cache_data['orders_data'];
                    $sim = $orders_data->sim;
                    $orders = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    if ($sim && $orders && $order_details && $order_state) {
                        $check_vnp_SecureHash = Vietinbank::checkVnpSecureHashReturn($orders_data, $raw_data);
                        if (($check_vnp_SecureHash == TRUE) && ($vnp_ResponseCode == '00')
                            && (isset($cache_data['requestId']) && $cache_data['requestId'] == $vnp_TxnRef)
                        ) {//success
                            //set order_state
                            //$order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                            $data = array(
                                'sim' => $sim->attributes,
                                'orders' => $orders->attributes,
                                'order_details' => $order_details,
                                'order_state' => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) {
                                $flag = 1;
                                OtpForm::unsetSession();
                                // clear transaction cache
                                Yii::app()->redis_vtb->delete($key);
                            } else {
                                $flag = 0;
                            }
                        } else {
                            //payment fail
                            $flag = 4;
                        }
                        //insert log DB
                        $note = Vietinbank::getErrorCode($vnp_ResponseCode, WPaymentMethod::PM_VNPAY);
                        $urlRequest = Yii::app()->controller->createAbsoluteUrl('checkout/return');
                        $query_string = $_SERVER['QUERY_STRING'];
                        if ($vnp_ResponseCode == '00') {
                            $status_req = WTransactionRequest::REQUEST_SUCCESS;
                        } else {
                            $status_req = WTransactionRequest::REQUEST_FAIL;
                        }
                        WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $vnp_TxnRef, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note);
                    } else {
                        //timeout
                        $flag = 2;
                    }
                } else {
                    //timeout
                    $flag = 2;
                }
            } else {//error_payment
                $flag = 5;
            }
        } else { //error_payment
            $flag = 5;
        }

        //redirect to message
        $this->redirect($this->createUrl('checkout/message', array('t' => $flag)));
    }

    public function actionMessage($t = '')
    {
        /* 1: success
         * 2: session_timeout
         * 4: payment_fail
         * 5: error_payment
         */
        $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
        if ($operation == OrdersData::OPERATION_TOPUP || $operation == OrdersData::OPERATION_BUYCARD) {
            $pageTitle = 'Mã thẻ';
            $view = '/card/';
        } else {
            $pageTitle = 'Sim số';
            $view = '/checkout/';
        }

        $this->pageTitle = $pageTitle . ' - Thông báo';

        $order_id = '';
        $affiliate_source = '';
        $affiliate_transaction_id = '';
        $msisdn = '';
        $key = '';
        if (WOrders::checkOrdersSessionExists($operation)) {
            $orders_data = Yii::app()->session['orders_data'];
            $order_id = $orders_data->orders->id;
            $msisdn = $orders_data->sim->msisdn;
            $affiliate_source = $orders_data->orders->affiliate_source;
            $affiliate_transaction_id = $orders_data->orders->affiliate_transaction_id;
            $key = md5($order_id . 'E_INVOICE');
        }

        // điều hướng đến trang yêu cầu khi có mã affiliate_code
        $url_redirect = WAffiliateManager::getRedirectUrl($affiliate_source);
        if ($url_redirect) {
            $status_order = ($t == 1) ? 1 : 0;
            WAffiliateManager::redirectUrl($status_order, $affiliate_transaction_id, $order_id, $msisdn, $url_redirect);
        }

        OtpForm::unsetSession();
        OtpForm::unsetCookie();
        switch ($t) {
            case 1:
                OtpForm::unsetCookieUtmSource();
                $this->render($view . 'message_success', array('order_id' => $order_id, 'key' => $key));
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
            case 'STK-1234':
                $this->render($view . 'message_fail', array(
                    'msg' => Yii::t('web/portal', 'limited_buy_sim')
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
        $orders_data = Yii::app()->session['orders_data'];
        $modelOrderNew = new WOrders();
        $modelSimNew = new WSim();
        $modelSimNew->msisdn = $orders_data->sim->msisdn;

        $amount = 0;
        $modelPackage = array();
        if (isset($_POST['WSim']) && isset($_POST['WOrders'])) {
            $modelOrderNew->attributes = $_POST['WOrders'];
            $modelSimNew->attributes = $_POST['WSim'];
            $modelSimNew->raw_data = $orders_data->sim->raw_data;
            $modelOrderNew->price_ship = 0;
            if (isset($_POST['WOrders']['package'][0])) {
                $modelPackage = WPackage::model()->findByPk($_POST['WOrders']['package'][0]);
            }

            if ($modelSimNew->type != $orders_data->sim->type) {
                $modelPackage = NULL;
            }
            if ($modelSimNew->type == WSim::TYPE_PREPAID) {
                if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                    $modelPackage = $orders_data->package_sim_kit;
                } else {
                    //check config package selected (file config.ini)
                    if (!empty($GLOBALS['config_common']['package']['selected'])
                        && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                    ) {
                        $code_selected = $GLOBALS['config_common']['package']['selected'];
                        //fix package selected
                        $package_selected = WPackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => WPackage::PACKAGE_ACTIVE));
                        $modelPackage = $package_selected;
                    }
                }
            }

            // tinh gia
            $amount = $modelOrderNew->calculatePrice($modelOrderNew, $modelSimNew, $orders_data->sim->raw_data, $modelPackage, $orders_data);

            $orders_data->sim->price = $modelSimNew->price;
            $orders_data->sim->price_term = $modelSimNew->price_term;
            $orders_data->sim->type = $modelSimNew->type;

            $orders_data->orders->delivery_type = $modelOrderNew->delivery_type;
            $orders_data->orders->price_ship = $modelOrderNew->price_ship;

            if ($modelPackage) {
                $orders_data->package = $modelPackage;
            } else {
                $orders_data->package = NULL;
            }
            Yii::app()->session['orders_data'] = $orders_data;
        }

        echo CJSON::encode(array(
                'content' => $this->renderPartial('_panel_order_table', array(
                    'modelSim' => $modelSimNew,
                    'modelOrder' => $modelOrderNew,
                    'modelPackage' => $modelPackage,
                    'amount' => $amount,
                ), TRUE))
        );
    }

    public function actionGuideQrCode()
    {
        $this->pageTitle = 'Hướng dẫn thanh toán QR Code';
        $this->render('guide_qr_code');
    }

    /**
     * actionCheckout
     */
    public function actionCheckoutIframe()
    {
        $this->layout = '/layouts/main_iframe';
        OtpForm::unsetSessionHtmlOrder();
        if (isset(Yii::app()->request->cookies['orders_data_cache_key'])
            && !empty(Yii::app()->request->cookies['orders_data_cache_key']->value)
        ) {
            $cache_key = Yii::app()->request->cookies['orders_data_cache_key']->value;
            $orders_data = Yii::app()->redis_orders_data->get($cache_key);
            if ($orders_data && !empty($orders_data->orders) && !empty($orders_data->sim)) {
                $modelOrder = $orders_data->orders;
                $modelSim = $orders_data->sim;
                $package_type = $modelSim->type;
                $package_selected = array();
                if ($modelSim->type == WSim::TYPE_PREPAID) { //check sim type=>get package
                    //warning required select package
                    $modelOrder->addError('package', Yii::t('web/portal', 'warning_required_package'));
                    if (!empty($orders_data->package_sim_kit)) {//check package(SimkitController)
                        $package[] = $orders_data->package_sim_kit;//array slide package
                        $package_id = !empty($orders_data->package_sim_kit->id) ? $orders_data->package_sim_kit->id : '';
                        $province = WProvince::getListProvinceByPackageId($package_id);
                    } else {
                        //check config package selected (file config.ini)
                        if (!empty($GLOBALS['config_common']['package']['selected'])) {
                            $code_selected = $GLOBALS['config_common']['package']['selected'];
                            $package_selected = WPackage::model()->find('id=:id AND status=:status', array(':id' => $code_selected, ':status' => WPackage::PACKAGE_ACTIVE));
                        }
                        if (!empty($GLOBALS['config_common']['package']['selected'])
                            && !empty($GLOBALS['config_common']['package']['fixed_selected'])
                        ) {
                            //fix package selected
                            $orders_data->package = $package_selected;
                            $package[] = $package_selected;//array slide package
                        } else {
                            //array slide package
                            if (!empty(Yii::app()->params->checkout_prepaid)) {
                                $package = WPackage::getListPackageById(Yii::app()->params->checkout_prepaid);
                            } else {
                                $package = WPackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
                            }
                        }
                        $province = WProvince::getListProvince(TRUE);
                    }
                } else {
                    //array slide package
                    $package = WPackage::getListPackageByType($package_type, '', FALSE, 0, '', '', '', WPackage::FREEDOO_PACKAGE);
                    $province = WProvince::getListProvince(TRUE);
                }
                $district = array();
                $ward = array();
                $brand_offices = array();

                $modelOrder->scenario = 'register_sim';
                //delivery_type
                if (empty($modelOrder->delivery_type)) {
                    $modelOrder->delivery_type = WOrders::DELIVERY_TYPE_HOME;//set default
                }

                //get list district
                if (isset($_POST['WOrders']['province_code']) || !empty($modelOrder->province_code)) {
                    $province_code = isset($_POST['WOrders']['province_code']) ? $_POST['WOrders']['province_code'] : $modelOrder->province_code;
                    $district = WDistrict::getListDistrictByProvince($province_code);
                }

                //get list ward
                if (isset($_POST['WOrders']['district_code']) || !empty($modelOrder->district_code)) {
                    $district_code = isset($_POST['WOrders']['district_code']) ? $_POST['WOrders']['district_code'] : $modelOrder->district_code;
                    $ward = WWard::getListWardDistrict($district_code);
                }

                //get list brand_offices
                if (isset($_POST['WOrders']['district_code']) || !empty($modelOrder->district_code)) {
                    $district_code = isset($_POST['WOrders']['district_code']) ? $_POST['WOrders']['district_code'] : $modelOrder->district_code;
                    $brand_offices = WBrandOffices::getListBrandOfficesByDistrict($district_code);
                }

                //validate ajax
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_step1') {
                    $errors = CJSON::decode(CActiveForm::validate($modelSim));
                    $errors += CJSON::decode(CActiveForm::validate($modelOrder));
                    echo CJSON::encode($errors);
                    Yii::app()->end();
                }

                if (isset($_POST['WSim']) && isset($_POST['WOrders'])) {
                    $modelSim->attributes = $_POST['WSim'];
                    $modelOrder->attributes = $_POST['WOrders'];

                    $modelOrder->full_name = $modelSim->full_name;

                    if ($modelOrder->delivery_type == WOrders::DELIVERY_TYPE_HOME) {
                        $modelOrder->price_ship = $GLOBALS['config_common']['order']['price_ship'];
                        $modelOrder->brand_offices = $modelOrder->address_detail;
                    } else {
                        $modelOrder->price_ship = 0;
                        $modelOrder->address_detail = isset($_POST['WOrders']['brand_offices']) ? $_POST['WOrders']['brand_offices'] : '';
                    }

                    if ($modelSim->validate() && $modelOrder->validate()) {
                        if (empty(Yii::app()->params->checkout_prepaid)
                            || ($modelSim->type == WSim::TYPE_POSTPAID)
                            || (!empty(Yii::app()->params->checkout_prepaid) && ($modelSim->type == WSim::TYPE_PREPAID) && !empty($_POST['WOrders']['package']))
                        ) {
                            //if empty promo_code: check cookie affiliate
                            if (empty($modelOrder->promo_code)) {
                                if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])
                                    && empty($modelOrder->affiliate_source)//vne: apisgw/addtocart
                                ) {
                                    $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                                }
                                if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])
                                    && empty($modelOrder->affiliate_transaction_id)//vne: apisgw/addtocart
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
                                $orders_data->package = $modelPackage;//display view panel_order
                            } else {
                                unset($orders_data->order_details['packages']);
                                unset($orders_data->package);
                            }

                            //add order detail with card
                            $card_value = 0;
                            if (isset($_POST['WOrders']['card']) && !empty($_POST['WOrders']['card'])) {
                                $modelOrder->card = $_POST['WOrders']['card'];
                                $card_value = $_POST['WOrders']['card'];
                            }

                            if ($card_value) {
                                $order_details_card = new WOrderDetails();
                                $order_details_card->setOrderDetailsCard($card_value, $modelOrder, $order_details_card);
                                $orders_data->order_details['card'] = $order_details_card->attributes;
                            }

                            //order state
                            $order_state = new WOrderState();
                            $order_state->setOrderState($modelOrder, $order_state, WOrderState::UNCONFIRMED, WOrderState::UNPAID);
                            $orders_data->order_state = $order_state;

                            $orders_data->sim = $modelSim;
                            $orders_data->orders = $modelOrder;
                            //set redis cache orders data
//                                Yii::app()->redis_orders_data->set($cache_key, $orders_data, Yii::app()->params->cache_timeout_config['cart_iframe']);//30'
                            Yii::app()->redis_orders_data->set($cache_key, $orders_data, (time() - Yii::app()->session['session_cart']));
                            $data = array(
                                'sim' => $modelSim->attributes,
                                'orders' => $modelOrder->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state' => $order_state->attributes,
                            );

                            //call api java->web_checkout
                            if ($orders_data->buySim($data)) {
                                //call api of third party(tiki)

//                                    $this->redirect($this->createUrl('checkout/checkout2'));
                            } else {
                                $msg = Yii::t('web/portal', 'insert_order_fail');
                                $this->redirect($this->createUrl('checkout/message', array('t' => 0)));
                            }
                        } else {
                            $modelOrder->addError('package', Yii::t('web/portal', 'err_required_package'));
                        }
                    }
                }

                //order amount
                $amount = (int)$modelOrder->getOrderAmount($orders_data);

                $this->render('checkout_step1_iframe', array(
                    'modelSim' => $modelSim,
                    'modelOrder' => $modelOrder,
                    'modelPackage' => !empty($orders_data->package) ? $orders_data->package : $package_selected,
                    'change_sim_type' => $orders_data->change_sim_type,
                    'package' => $package,
                    'province' => $province,
                    'district' => $district,
                    'ward' => $ward,
                    'brand_offices' => $brand_offices,
                    'amount' => $amount,
                ));
            } else {
                OtpForm::unsetSession();
                Yii::app()->redis_orders_data->delete($cache_key);
                $this->redirect($this->createUrl('checkout/messageIframe', array('t' => 2)));
            }
        }
    }

    public function actionMessageIframe($t = '')
    {
        /* 1: success
         * 2: session_timeout
         * 4: payment_fail
         * 5: error_payment
         */

        $this->pageTitle = 'Sim số - Thông báo';
        $this->layout = '/layouts/main_iframe';

        $order_id = '';
        $key = '';
        if (isset(Yii::app()->request->cookies['orders_data_cache_key'])
            && !empty(Yii::app()->request->cookies['orders_data_cache_key']->value)
        ) {
            $key = Yii::app()->request->cookies['orders_data_cache_key']->value;
            $orders_data = Yii::app()->redis_orders_data->get($key);
            $order_id = $orders_data->orders->id;
        }

        OtpForm::unsetSession();
        OtpForm::unsetCookie();
        Yii::app()->redis_orders_data->delete($key);
        switch ($t) {
            case 1:
                OtpForm::unsetCookieUtmSource();
                $this->render('message_success_iframe', array('order_id' => $order_id));
                break;
            case 2:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'session_timeout')
                ));
                break;
            case 3:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'err_verify_limited')
                ));
                break;
            case 4:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'payment_fail')
                ));
                break;
            case 5:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'error_payment')
                ));
                break;
            case 8:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'get_token_key_fail')
                ));
                break;
            case 9:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'send_mt_fail')
                ));
                break;
            case 10:
                $this->render('message_fail_iframe', array(
                    'msg' => Yii::t('web/portal', 'customer_cancel')
                ));
                break;
            default:
                $this->render('message_fail_iframe');
        }
    }

    public function actionSetSimType()
    {
        if (isset($_POST['sim_type']) && isset(Yii::app()->session['orders_data'])) {

            if ($_POST['sim_type'] == WOrders::ESIM) {
                Yii::app()->session['orders_data']->sim_type = WOrders::ESIM;
                $key = 'esim_price';

                $esim_pre_order_date = Yii::app()->db->createCommand()
                    ->select('pu.key, value')
                    ->from('tbl_parameter_utils pu')
                    ->where('pu.key=:key', array(':key' => 'esim_pre_order_date'))
                    ->queryRow();

                if ($esim_pre_order_date) {
                    if (date('Y-m-d H:i:s') <= $esim_pre_order_date['value']) {
                        $key = 'esim_promotion_price';
                    }
                }
            } else {
                Yii::app()->session['orders_data']->sim_type = WOrders::NOTESIM;
                $key = 'sim_price';
            }

            $parameter_util = Yii::app()->db->createCommand()
                ->select('pu.key, value')
                ->from('tbl_parameter_utils pu')
                ->where('pu.key=:key', array(':key' => $key))
                ->queryRow();

            $price_data = json_decode($parameter_util['value']);

            $msisdn = Yii::app()->session['orders_data']->sim->msisdn;
            $msisdn_type = Yii::app()->session['orders_data']->sim->type;

            if ($msisdn_type == WSim::TYPE_PREPAID) {
                Yii::app()->session['orders_data']->sim->price = $price_data->prepaid;
                Yii::app()->session['orders_data']->sim->raw_data['price'] = $price_data->prepaid;
            } else {
                Yii::app()->session['orders_data']->sim->price = $price_data->postpaid;
                Yii::app()->session['orders_data']->sim->raw_data['price'] = $price_data->postpaid;
            }

            for ($i = 0; $i < count(Yii::app()->session['orders_data']->sim_raw_data); $i++) {
                $sim_raw = Yii::app()->session['orders_data']->sim_raw_data[$i];
                if ((isset($sim_raw['msisdn']) && $sim_raw['msisdn'] == $msisdn)
                    && (isset($sim_raw['msisdn_type']) && $sim_raw['msisdn_type'] == $msisdn_type)
                ) {
                    if ($msisdn_type == WSim::TYPE_PREPAID) {
                        $sim_raw['price'] = $price_data->prepaid;
                    } else {
                        $sim_raw['price'] = $price_data->postpaid;
                    }
                    Yii::app()->session['orders_data']->sim_raw_data[$i] = $sim_raw;
                }
            }
        }
        echo CJSON::encode("1");
        Yii::app()->end();
    }


} //end class