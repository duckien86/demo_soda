<?php

class RoamingController extends Controller
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

    protected function performAjaxValidation($model)
    {

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'form_send_otp_ir_only') {

            echo CActiveForm::validate($model);

            Yii::app()->end();

        }

    }

    /**
     * list package
     */
    public function actionIndex()
    {
        $this->pageTitle = 'Sản phẩm - Gói cước Roaming';
        $packages = WPackage::getListPackageRoaming(WPackage::PACKAGE_ROAMING);

        $this->render('index', array(
            'packages' => $packages
        ));
    }

    /**
     * get list nation
     */
    public function actionGetNations()
    {
        $package_id = Yii::app()->request->getParam('package_id', '');
        $nations_prepaid = WNations::getListNationByPackageId($package_id, WPackagesNations::PACKAGE_PREPAID);
        $nations_postpaid = WNations::getListNationByPackageId($package_id, WPackagesNations::PACKAGE_POSTPAID);

        echo CJSON::encode(
            array(
                'content' => $this->renderPartial('_list_nation', array(
                    'nations_prepaid' => $nations_prepaid,
                    'nations_postpaid' => $nations_postpaid,
                ), TRUE)
            )
        );
        Yii::app()->end();
    }

    /**
     * action get form register package roaming
     */
    public function actionGetFormRegister()
    {
        $package_id = Yii::app()->request->getParam('package_id', '');
        $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package_id));
        if ($modelPackage) {
            $modelOrder = new WOrders();
            $modelOrder->scenario = 'register_package_rx';
            $html = $this->renderPartial('_form_send_otp_rx', array(
                'modelPackage' => $modelPackage,
                'modelOrder' => $modelOrder,
            ), TRUE);
        } else {
            $msg = Yii::t('web/portal', 'package_not_exist');
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action get form send otp=> search package roaming
     */
    public function actionGetFormSendOtpSearch()
    {
        if (Yii::app()->request->isPostRequest) {
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            //render html
            $html = $this->renderPartial('_form_send_otp', array(
                'otpModel' => $otpModel,
            ), TRUE);
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action get form confirm cancel package roaming(cvqt or rx)
     */
    public function actionGetFormConfirmCancel()
    {
        $otpModel = new OtpForm();
        $otpModel->scenario = 'getTokenKeyRoaming';

        //render html
        $html = $this->renderPartial('_confirm_cancel', array(
            'otpModel' => $otpModel,
        ), TRUE);

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }


    /**
     * action get form send otp=> cancel package roaming
     */
    public function actionGetFormSendOtpCancel()
    {
        if (Yii::app()->request->isPostRequest) {
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            //render html
            $html = $this->renderPartial('_form_send_otp_cancel', array(
                'otpModel' => $otpModel,
            ), TRUE);
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action get form send otp=> cancel ir(cvqt)
     */
    public function actionGetFormSendOtpCancelIr()
    {
        if (Yii::app()->request->isPostRequest) {
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            //render html
            $html = $this->renderPartial('_form_send_otp_cancel_ir', array(
                'otpModel' => $otpModel,
            ), TRUE);
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action get form register CVQT: #btn_register_ir
     */
    public function actionGetFormRegisterIrOnly()
    {
        if (Yii::app()->request->isPostRequest) {
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            //render html
            $html = $this->renderPartial('_form_send_otp_ir_only', array(
                'otpModel' => $otpModel,
            ), TRUE);
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action call api -> send otp: search
     */
    public function actionSendOtpSearch()
    {
        $msg = '';
        if (Yii::app()->request->isPostRequest) {
            $orders_data = new OrdersData();
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            if (isset($_POST['OtpForm'])) {
                $otpModel->attributes = $_POST['OtpForm'];
                if ($otpModel->validate()) {
                    //set cache
                    $cache_key = 'package_roaming_' . $otpModel->msisdn;
                    $pack_cache_key = new CHttpCookie('package_roaming_cache_key', $cache_key);
                    $pack_cache_key->expire = time() + 60 * 10;//10'
                    Yii::app()->request->cookies['package_roaming_cache_key'] = $pack_cache_key;
                    $orders_data->otp_form = $otpModel;

                    //call api send otp
                    $data = array(
                        'so_tb' => $otpModel->msisdn,
                        'service_otp' => 'rx_check_remain_amount',
                    );
                    $response = $orders_data->dataRoamingSendOtp($data);
                    $response_code = isset($response['code']) ? $response['code'] : '';
                    $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');
                    if ($response_code == 1) {
                        $orders_data->session_cart = time();//count down OTP
                        //set cache order
                        Yii::app()->cache->set($cache_key, $orders_data);

                        $otpModel = new OtpForm();
                        $otpModel->scenario = 'checkTokenKey';
                        //render html
                        $html = $this->renderPartial('_form_verify_otp_search', array(
                            'otpModel' => $otpModel,
                            'session_cart' => $orders_data->session_cart,
                        ), TRUE);
                    } else {//error send otp
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    //render html
                    $html = $this->renderPartial('_form_send_otp', array(
                        'otpModel' => $otpModel,
                    ), TRUE);
                }
            } else {
                //render html
                $html = $this->renderPartial('_form_send_otp', array(
                    'otpModel' => $otpModel,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action call api -> send otp: cancel rx
     */
    public function actionSendOtpCancel()
    {
        $msg = '';
        if (Yii::app()->request->isPostRequest) {
            $orders_data = new OrdersData();
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            if (isset($_POST['OtpForm'])) {
                $otpModel->attributes = $_POST['OtpForm'];
                if ($otpModel->validate()) {
                    //set cache
                    $cache_key = 'package_roaming_' . $otpModel->msisdn;
                    $pack_cache_key = new CHttpCookie('package_roaming_cache_key', $cache_key);
                    $pack_cache_key->expire = time() + 60 * 10;//10'
                    Yii::app()->request->cookies['package_roaming_cache_key'] = $pack_cache_key;
                    $orders_data->otp_form = $otpModel;

                    //call api send otp
                    $data = array(
                        'so_tb' => $otpModel->msisdn,
                        'service_otp' => 'rx_deregister',
                    );
                    $response = $orders_data->dataRoamingSendOtp($data);
                    $response_code = isset($response['code']) ? $response['code'] : '';
                    $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');
                    if ($response_code == 1) {
                        $orders_data->session_cart = time();//count down OTP
                        //set cache order
                        Yii::app()->cache->set($cache_key, $orders_data);

                        $otpModel = new OtpForm();
                        $otpModel->scenario = 'checkTokenKey';
                        //render html
                        $html = $this->renderPartial('_form_verify_otp_cancel', array(
                            'otpModel' => $otpModel,
                            'session_cart' => $orders_data->session_cart,
                        ), TRUE);
                    } else {//error send otp
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    //render html
                    $html = $this->renderPartial('_form_send_otp_cancel', array(
                        'otpModel' => $otpModel,
                    ), TRUE);
                }
            } else {
                //render html
                $html = $this->renderPartial('_form_send_otp_cancel', array(
                    'otpModel' => $otpModel,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action call api -> send otp: cancel ir(cvqt)
     */
    public function actionSendOtpCancelIr()
    {
        $msg = '';
        if (Yii::app()->request->isPostRequest) {
            $orders_data = new OrdersData();
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';

            if (isset($_POST['OtpForm'])) {
                $otpModel->attributes = $_POST['OtpForm'];
                if ($otpModel->validate()) {
                    //set cache
                    $cache_key = 'package_roaming_' . $otpModel->msisdn;
                    $pack_cache_key = new CHttpCookie('package_roaming_cache_key', $cache_key);
                    $pack_cache_key->expire = time() + 60 * 10;//10'
                    Yii::app()->request->cookies['package_roaming_cache_key'] = $pack_cache_key;
                    $orders_data->otp_form = $otpModel;

                    //call api send otp
                    $data = array(
                        'so_tb' => $otpModel->msisdn,
                        'service_otp' => 'ir_deregister',
                    );
                    $response = $orders_data->dataRoamingSendOtp($data);
                    $response_code = isset($response['code']) ? $response['code'] : '';
                    $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');
                    if ($response_code == 1) {
                        $orders_data->session_cart = time();//count down OTP
                        //set cache order
                        Yii::app()->cache->set($cache_key, $orders_data);

                        $otpModel = new OtpForm();
                        $otpModel->scenario = 'checkTokenKey';
                        //render html
                        $html = $this->renderPartial('_form_verify_otp_cancel_ir', array(
                            'otpModel' => $otpModel,
                            'session_cart' => $orders_data->session_cart,
                        ), TRUE);
                    } else {//error send otp
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    //render html
                    $html = $this->renderPartial('_form_send_otp_cancel_ir', array(
                        'otpModel' => $otpModel,
                    ), TRUE);
                }
            } else {
                //render html
                $html = $this->renderPartial('_form_send_otp_cancel_ir', array(
                    'otpModel' => $otpModel,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action call api -> send otp
     */
    public function actionSendOtpRx()
    {
        $msg = '';
        if (Yii::app()->request->isPostRequest) {
            $orders_data = new OrdersData();
            $modelOrder = new WOrders();
            $orderDetails = new WOrderDetails();
            $modelOrder->scenario = 'register_package_rx';
            $package_id = isset($_POST['WPackage']['id']) ? $_POST['WPackage']['id'] : '';
            $modelPackage = WPackage::model()->find('id=:id', array(':id' => $package_id));
            if(isset($_POST['ajax'])) {
                if ($_POST['ajax']=='form_send_otp_rx') {
                    echo CActiveForm::validate($modelOrder);
                }
                Yii::app()->end();
            }
            if ($modelPackage) {
                $orders_data->package = $modelPackage;//display view panel_order
                $modelOrder->id = $modelOrder->generateOrderId();
                $orderDetails->setOrderDetailsPackage($modelPackage, $modelOrder, $orderDetails);
                if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
                    $modelOrder->attributes = $_POST['WOrders'];
                    $modelOrder->payment_method = (string)WPaymentMethod::PM_AIRTIME;
                    //sso_id, phone_contact(after submit)
                    if (!Yii::app()->user->isGuest) {
                        $modelOrder->sso_id = Yii::app()->user->sso_id;
                    }
                    //check cookie campaign
                    if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                        $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                    }
                    if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                        $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                    }

                    if ($modelOrder->validate()) {
                        //if empty promo_code: check cookie affiliate
                        if (empty($modelOrder->promo_code)) {
                            if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                                $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                            }
                            if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                                $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                            }
                        }
                        //order state
                        $orderState = new WOrderState();
                        $orderState->setOrderState($modelOrder, $orderState, WOrderState::CONFIRMED);
                        $orders_data->order_state = $orderState;
                        //set cache order
                        $cache_key = 'orders_data_package_roaming_' . $modelOrder->id;
                        $pack_cache_key = new CHttpCookie('package_roaming_cache_key', $cache_key);
                        $pack_cache_key->expire = time() + 60 * 30;//30'
                        Yii::app()->request->cookies['package_roaming_cache_key'] = $pack_cache_key;
                        $orders_data->orders = $modelOrder;
                        $orders_data->order_details['packages'] = $orderDetails->attributes;
                        
                        // call api check member
                        $is_RU = WPackage::checkRU($modelPackage->code);
                        $check_member = array(
                            'code' => 1
                        );
                        if($is_RU){
                            $data_check_member = array(
                                'so_tb' => $modelOrder->phone_contact,
                            );
                            $check_member = $orders_data->dataRoamingCheckVnptMember($data_check_member, TRUE);
                        }
                        //.END call api check member
                        if($check_member && $check_member['code'] && $check_member['code'] == 1) {
                            //call api check cvqt
                            $data_check_ir = array(
                                'so_tb' => $modelOrder->phone_contact,
                            );

                            $check_ir = $orders_data->dataRoamingCheckIr($data_check_ir);
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
                                    //render html
                                    $html = $this->renderPartial('_form_verify_otp_rx', array(
                                        'modelPackage' => $modelPackage,
                                        'modelOrder'   => $modelOrder,
                                        'otpModel'     => $otpModel,
                                        'session_cart' => $orders_data->session_cart,
                                    ), TRUE);
                                } else {//error send otp
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {//modal confirm register ir: mo chuyen vung quoc te va send otp de confirm
                                //set cache order
                                Yii::app()->cache->set($cache_key, $orders_data);
                                //render html
                                $html = $this->renderPartial('_confirm_register_ir', array(
                                    'modelPackage' => $modelPackage,
                                ), TRUE);
                            }
                        }else{
                            //render html
                            $msg = "Đăng ký không thành công.
                            Thuê bao của Quý khách không đủ điều kiện đăng ký gói $modelPackage->name. 
                            Chi tiết xin vui lòng liên hệ: +8424.3773.1857 (miễn phí khi đang chuyển vùng Quốc tế) 
                            hoac 18001091 (Khi đang ở trong nước).";
                            $html = $this->renderPartial('_message_fail', array(
                                'msg' => $msg
                            ), TRUE);
                        }
                    } else {
                        //render html
                        $html = $this->renderPartial('_form_send_otp_rx', array(
                            'modelPackage' => $modelPackage,
                            'modelOrder' => $modelOrder,
                        ), TRUE);
                    }
                } else {
                    //render html
                    $html = $this->renderPartial('_form_send_otp_rx', array(
                        'modelPackage' => $modelPackage,
                        'modelOrder' => $modelOrder,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'package_not_exist');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action call api -> send otp register ir
     */
    public function actionSendOtpIr()
    {
        $flag = FALSE;
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $modelOrder = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    $modelPackage = $orders_data->package;
                    if ($modelOrder && $order_details && $order_state && $modelPackage) {
                        if (isset($_POST['WPackage']) && isset($_POST['WOrders'])) {
                            $modelOrder->attributes = $_POST['WOrders'];
                            if ($modelOrder->validate()) {
                                //call api send otp register ir
                                $data = array(
                                    'so_tb' => $modelOrder->phone_contact,
                                    'service_otp' => 'ir_register',
                                );
                                $response = $orders_data->dataRoamingSendOtp($data);
                                $response_code = isset($response['code']) ? $response['code'] : '';
                                $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code == 1) {
                                    $orders_data->session_cart = time();//count down OTP
                                    //set cache order
                                    Yii::app()->cache->set($cache_key, $orders_data);

                                    $flag = TRUE;
                                    $otpModel = new OtpForm();
                                    $otpModel->scenario = 'checkTokenKey';
                                    //render html
                                    $html = $this->renderPartial('_form_verify_otp_ir', array(
                                        'modelPackage' => $modelPackage,
                                        'modelOrder' => $modelOrder,
                                        'otpModel' => $otpModel,
                                        'session_cart' => $orders_data->session_cart,
                                    ), TRUE);
                                } else {
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                $modelOrder->scenario = 'register_package_ir';
                                //render html
                                $html = $this->renderPartial('_form_send_otp_ir', array(
                                    'modelPackage' => $modelPackage,
                                    'modelOrder' => $modelOrder,
                                ), TRUE);
                            }
                        } else {
                            $modelOrder->scenario = 'register_package_ir';
                            //render html
                            $html = $this->renderPartial('_form_send_otp_ir', array(
                                'modelPackage' => $modelPackage,
                                'modelOrder' => $modelOrder,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');

                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');

                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');

                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');

            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }


    /**
     * action call api -> check CVQT->send OTP register CVQT
     */
    public function actionSendOtpIrOnly()
    {
        $flag = FALSE;
        $msg = '';
        $html = '';
        if (Yii::app()->request->isPostRequest) {
            $orders_data = new OrdersData();
            $otpModel = new OtpForm();
            $otpModel->scenario = 'getTokenKeyRoaming';
            $this->performAjaxValidation($otpModel);
            if(isset($_POST['ajax'])) {
                if ($_POST['ajax']=='form_send_otp_ir_only') {
                    echo CActiveForm::validate($otpModel);
                }
                Yii::app()->end();
            }
            if (isset($_POST['OtpForm'])) {
                $otpModel->attributes = $_POST['OtpForm'];
                if ($otpModel->validate()) {
                    //set cache
                    $cache_key = 'package_roaming_' . $otpModel->msisdn;
                    $pack_cache_key = new CHttpCookie('package_roaming_cache_key', $cache_key);
                    $pack_cache_key->expire = time() + 60 * 10;//10'
                    Yii::app()->request->cookies['package_roaming_cache_key'] = $pack_cache_key;
                    $orders_data->otp_form = $otpModel;

                    //call api check cvqt
                    $data_check_ir = array(
                        'so_tb' => $otpModel->msisdn,
                    );
                    $check_ir = $orders_data->dataRoamingCheckIr($data_check_ir);
                    if ($check_ir) {
                        $msg = Yii::t('web/portal', 'checked_ir');
                        //render html
                        $html = $this->renderPartial('_message', array(
                            'msg' => $msg,
                        ), TRUE);
                    } else {
                        //call api send otp CVQT
                        $data = array(
                            'so_tb' => $otpModel->msisdn,
                        );
                        $response = $orders_data->dataRoamingConfirmRegisterIr($data);
                        $response_code = isset($response['code']) ? $response['code'] : '';
                        $msg           = Yii::t('web/portal', 'error_roaming');

                        if ($response_code == 1) {
                            $orders_data->session_cart = time();//count down OTP
                            //set cache order
                            Yii::app()->cache->set($cache_key, $orders_data);

                            $flag = TRUE;
                            $otpModel = new OtpForm();
                            $otpModel->scenario = 'checkTokenKey';
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_ir', array(
                                'otpModel' => $otpModel,
                                'session_cart' => $orders_data->session_cart,
                                'url_action_form' => $this->createUrl('roaming/verifyRegisterIrOnly'),
                            ), TRUE);
                        }
                    }
                } else {
                    //render html
                    $html = $this->renderPartial('_form_send_otp_ir_only', array(
                        'otpModel' => $otpModel,
                    ), TRUE);
                }
            } else {
                //render html
                $html = $this->renderPartial('_form_send_otp_ir_only', array(
                    'otpModel' => $otpModel,
                ), TRUE);
            }
        }

        if ($flag == FALSE) {
            if (empty($msg)) {
                $msg = Yii::t('web/portal', 'error_exception');
            }

            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm register ir(cvqt)
     */
    public function actionConfirmRegisterIr()
    {
        $flag = FALSE;
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $modelOrder = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    $modelPackage = $orders_data->package;
                    if ($modelOrder && $order_details && $order_state && $modelPackage) {
                        //call api send otp
                        $data = array(
                            'so_tb' => $modelOrder->phone_contact,
                        );
                        $response = $orders_data->dataRoamingConfirmRegisterIr($data);
                        $response_code = isset($response['code']) ? $response['code'] : '';
                        $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                        if ($response_code == 1) {
                            $orders_data->session_cart = time();//count down OTP
                            //set cache order
                            Yii::app()->cache->set($cache_key, $orders_data);

                            $flag = TRUE;
                            $otpModel = new OtpForm();
                            $otpModel->scenario = 'checkTokenKey';
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_ir', array(
                                'modelPackage' => $modelPackage,
                                'modelOrder' => $modelOrder,
                                'otpModel' => $otpModel,
                                'session_cart' => $orders_data->session_cart,
                            ), TRUE);
                        }
                    }
                }
            }
        }

        if ($flag == FALSE) {
            if (empty($msg)) {
                $msg = Yii::t('web/portal', 'error_exception');
            }

            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->cvqt->register Rx
     */
    public function actionVerifyRegisterIr()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $modelOrder = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    $modelPackage = $orders_data->package;
                    if ($modelOrder && $order_details && $order_state && $modelPackage) {
                        $otpModel = new OtpForm();
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm'])) {
                            $otpModel->attributes = $_POST['OtpForm'];
                            $otpModel->msisdn = $modelOrder->phone_contact;
                            if ($otpModel->validate()) {
                                //call api send otp
                                $data_verify = array(
                                    'so_tb' => $modelOrder->phone_contact,
                                    'otp' => $otpModel->token,
                                    'service_otp' => 'ir_register',
                                );
                                $response_verify = $orders_data->dataRoamingVerifyRegisterIr($data_verify);
                                $response_code_verify = isset($response_verify['code']) ? $response_verify['code'] : '';
                                $msg = isset($response_verify['msg']) ? $response_verify['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code_verify == 1) {
                                    //call api send otp register rx
                                    $data = array(
                                        'so_tb' => $modelOrder->phone_contact,
                                        'service_otp' => 'rx_register',
                                    );
                                    $response = $orders_data->dataRoamingSendOtp($data);
                                    $response_code = isset($response['code']) ? $response['code'] : '';
                                    $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');
                                    if ($response_code == 1) {
                                        $orders_data->session_cart = time();//count down OTP
                                        //set cache order
                                        Yii::app()->cache->set($cache_key, $orders_data);

                                        $otpModel->scenario = 'checkTokenKey';
                                        //render html verify otp rx
                                        $html = $this->renderPartial('_form_verify_otp_rx', array(
                                            'modelPackage' => $modelPackage,
                                            'modelOrder' => $modelOrder,
                                            'otpModel' => $otpModel,
                                            'session_cart' => $orders_data->session_cart,
                                        ), TRUE);
                                    } else {//error send otp
                                        //render html
                                        $html = $this->renderPartial('_message_fail', array(
                                            'msg' => $msg,
                                        ), TRUE);
                                    }
                                } else {
                                    if (empty($msg)) {
                                        $msg = Yii::t('web/portal', 'error_exception');
                                    }

                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_send_otp_ir', array(
                                    'modelPackage' => $modelPackage,
                                    'modelOrder' => $modelOrder,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_send_otp_ir', array(
                                'modelPackage' => $modelPackage,
                                'modelOrder' => $modelOrder,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');

                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');

                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');

                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');

            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->cvqt only
     */
    public function actionVerifyRegisterIrOnly()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $otpModel = $orders_data->otp_form;
                    if ($otpModel) {
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm']['token'])) {
                            $otpModel->token = $_POST['OtpForm']['token'];
                            if ($otpModel->validate()) {
                                //call api send otp
                                $data_verify = array(
                                    'so_tb' => $otpModel->msisdn,
                                    'otp' => $otpModel->token,
                                    'service_otp' => 'ir_register',
                                );
                                $response_verify = $orders_data->dataRoamingVerifyRegisterIr($data_verify);
                                $response_code = isset($response_verify['code']) ? $response_verify['code'] : '';
//                                    $msg             = isset($response_verify['msg']) ? $response_verify['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code == 1) {
                                    $msg = Yii::t('web/portal', 'register_ir_success');
                                    //render html
                                    $html = $this->renderPartial('_message', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                } else {
                                    $msg = Yii::t('web/portal', 'register_ir_fail');
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_verify_otp_rx', array(
                                    'otpModel' => $otpModel,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_rx', array(
                                'otpModel' => $otpModel,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');
                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->register package roaming
     */
    public function actionRegisterRx()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $modelOrder = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state = $orders_data->order_state;
                    $modelPackage = $orders_data->package;
                    if ($modelOrder && $order_details && $order_state && $modelPackage) {
                        $otpModel = new OtpForm();
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm'])) {
                            $otpModel->attributes = $_POST['OtpForm'];
                            $otpModel->msisdn = $modelOrder->phone_contact;
                            if ($otpModel->validate()) {
                                $modelOrder->otp = $otpModel->token;
                                //call api send otp
                                $data = array(
                                    'orders' => $modelOrder->attributes,
                                    'order_details' => $order_details,
                                    'order_state' => $order_state->attributes,
                                );
                                $response = $orders_data->dataRoamingRegisterRx($data);
                                $response_code = isset($response['code']) ? $response['code'] : '';
                                $msg = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code == 1) {
                                    //render html
                                    $html = $this->renderPartial('_message_success', array(
                                        'modelPackage' => $modelPackage,
                                        'modelOrder' => $modelOrder,
                                    ), TRUE);
                                } else {
                                    if (empty($msg)) {
                                        $msg = Yii::t('web/portal', 'error_exception');
                                    }
                                    $msg = OrdersData::getCheckMemberMsg($response, $modelPackage->name);
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_verify_otp_rx', array(
                                    'modelPackage' => $modelPackage,
                                    'modelOrder' => $modelOrder,
                                    'otpModel' => $otpModel,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_rx', array(
                                'modelPackage' => $modelPackage,
                                'modelOrder' => $modelOrder,
                                'otpModel' => $otpModel,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');
                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->search package roaming
     */
    public function actionSearchRx()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $otpModel = $orders_data->otp_form;
                    if ($otpModel) {
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm']['token'])) {
                            $otpModel->token = $_POST['OtpForm']['token'];
                            if ($otpModel->validate()) {
                                //call api send otp
                                $data = array(
                                    'so_tb' => $otpModel->msisdn,
                                    'otp' => $otpModel->token,
                                );
                                $response = $orders_data->dataRoamingSearchRx($data);
                                $response_code = '';
                                if (isset($response['status'])) {
                                    $status = $response['status'];
                                    if (isset($status['code'])) {
                                        $response_code = $status['code'];
                                    }
                                }
                                $data_output = array();

                                if (isset($response['data'])) {
                                    $data_arr = CJSON::decode($response['data']);
                                    if (isset($data_arr['result'])) {
                                        $data_output = $data_arr['result'];
                                    }
                                }

                                if ($response_code == 1) {
                                    if ($data_output) {
                                        //render html
                                        $html = $this->renderPartial('_result_search', array(
                                            'data' => $data_output,
                                        ), TRUE);
                                    } else {
                                        $msg = Yii::t('web/portal', 'roaming_not_register');
                                        //render html
                                        $html = $this->renderPartial('_message_fail', array(
                                            'msg' => $msg,
                                        ), TRUE);
                                    }
                                } else {
                                    $msg = Yii::t('web/portal', 'error_exception');
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_verify_otp_rx', array(
                                    'otpModel' => $otpModel,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_rx', array(
                                'otpModel' => $otpModel,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');
                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->cancel package roaming(rx)
     */
    public function actionCancelRx()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $otpModel = $orders_data->otp_form;
                    if ($otpModel) {
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm']['token'])) {
                            $otpModel->token = $_POST['OtpForm']['token'];
                            if ($otpModel->validate()) {
                                //call api send otp
                                $data = array(
                                    'so_tb' => $otpModel->msisdn,
                                    'otp' => $otpModel->token,
                                );
                                $response = $orders_data->dataRoamingCancelRx($data);
                                $response_code = isset($response['code']) ? $response['code'] : '';
//                                    $msg           = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code == 1) {
                                    $msg = Yii::t('web/portal', 'cancel_rx_success');
                                    //render html
                                    $html = $this->renderPartial('_message', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                } else {
                                    $msg = Yii::t('web/portal', 'cancel_rx_fail');
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_verify_otp_rx', array(
                                    'otpModel' => $otpModel,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_rx', array(
                                'otpModel' => $otpModel,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');
                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }

    /**
     * action confirm otp->cancel ir(cvqt)
     */
    public function actionCancelIr()
    {
        if (Yii::app()->request->isPostRequest) {
            if (isset(Yii::app()->request->cookies['package_roaming_cache_key'])
                && !empty(Yii::app()->request->cookies['package_roaming_cache_key']->value)
            ) {
                $cache_key = Yii::app()->request->cookies['package_roaming_cache_key']->value;
                $orders_data = Yii::app()->cache->get($cache_key);
                if ($orders_data) {
                    $otpModel = $orders_data->otp_form;
                    if ($otpModel) {
                        $otpModel->scenario = 'checkTokenKey';
                        if (isset($_POST['OtpForm']['token'])) {
                            $otpModel->token = $_POST['OtpForm']['token'];
                            if ($otpModel->validate()) {
                                //call api send otp
                                $data = array(
                                    'so_tb' => $otpModel->msisdn,
                                    'otp' => $otpModel->token,
                                );
                                $response = $orders_data->dataRoamingCancelIr($data);
                                $response_code = isset($response['code']) ? $response['code'] : '';
//                                    $msg           = isset($response['msg']) ? $response['msg'] : Yii::t('web/portal', 'error_exception');

                                if ($response_code == 1) {
                                    $msg = Yii::t('web/portal', 'cancel_ir_success');
                                    //render html
                                    $html = $this->renderPartial('_message', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                } else {
                                    $msg = Yii::t('web/portal', 'cancel_ir_fail');
                                    //render html
                                    $html = $this->renderPartial('_message_fail', array(
                                        'msg' => $msg,
                                    ), TRUE);
                                }
                            } else {
                                //render html
                                $html = $this->renderPartial('_form_verify_otp_rx', array(
                                    'otpModel' => $otpModel,
                                ), TRUE);
                            }
                        } else {
                            //render html
                            $html = $this->renderPartial('_form_verify_otp_rx', array(
                                'otpModel' => $otpModel,
                            ), TRUE);
                        }
                    } else {
                        $msg = Yii::t('web/portal', 'session_timeout');
                        //render html
                        $html = $this->renderPartial('_message_fail', array(
                            'msg' => $msg,
                        ), TRUE);
                    }
                } else {
                    $msg = Yii::t('web/portal', 'session_timeout');
                    //render html
                    $html = $this->renderPartial('_message_fail', array(
                        'msg' => $msg,
                    ), TRUE);
                }
            } else {
                $msg = Yii::t('web/portal', 'session_timeout');
                //render html
                $html = $this->renderPartial('_message_fail', array(
                    'msg' => $msg,
                ), TRUE);
            }
        } else {
            $msg = Yii::t('web/portal', 'error_exception');
            //render html
            $html = $this->renderPartial('_message_fail', array(
                'msg' => $msg,
            ), TRUE);
        }

        echo CJSON::encode(
            array(
                'content' => $html
            )
        );
        Yii::app()->end();
    }
} //end class