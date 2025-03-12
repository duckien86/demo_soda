<?php

    class CardController extends Controller
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
        }

        /**
         * buy card
         */
        public function actionBuycard()
        {
            $this->pageTitle = 'Sản phẩm - Mua mã thẻ';
            OtpForm::unsetSessionHtmlOrder();
            $orders_data            = new OrdersData();
            $orders_data->operation = OrdersData::OPERATION_BUYCARD;
            $modelOrder             = new WOrders();
            $orderDetails           = new WOrderDetails();
            $modelOrder->scenario   = 'buy_card';
            $orderDetails->scenario = 'buy_card';
            //validate ajax
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'card_form') {
                echo CActiveForm::validate($modelOrder);
                Yii::app()->end();
            }
            if (isset($_POST['WOrders']) && isset($_POST['WOrderDetails'])) {
                $modelOrder->attributes   = $_POST['WOrders'];
                $orderDetails->attributes = $_POST['WOrderDetails'];
                $modelOrder->id           = $modelOrder->generateOrderId();
                //sso_id
                if (isset(Yii::app()->user->sso_id)) {
                    $modelOrder->sso_id = Yii::app()->user->sso_id;
                }

                //check cookie
                if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                    $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                }
                if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                    $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                }

                $orderDetails->order_id  = $modelOrder->id;
                $orderDetails->item_id   = $orderDetails->price;
                $orderDetails->item_name = $orderDetails->price;
                $orderDetails->type      = WOrderDetails::TYPE_CARD;
                if ($modelOrder->validate() && $orderDetails->validate()) {
                    //fix test
                    $modelOrder->otp = '123456';
                    //set price discount
                    $orderDetails->price = (int)($orderDetails->price * WOrders::PRICE_DISCOUNT_CARD);

                    $orders_data->order_details['card'] = $orderDetails->attributes;
                    $orders_data->card                  = $orderDetails;
                    $orders_data->orders                = $modelOrder;

                    $order_state              = new WOrderState();
                    $order_state->order_id    = $modelOrder->id;
                    $order_state->confirm     = WOrderState::UNCONFIRMED;
                    $order_state->paid        = WOrderState::UNPAID;
                    $orders_data->order_state = $order_state;

                    //set session Order
                    Yii::app()->session['session_cart'] = time();
                    Yii::app()->session['orders_data']  = $orders_data;

                    $data = array(
                        'orders'        => $modelOrder->attributes,
                        'order_details' => $orders_data->order_details,
                        'order_state'   => $order_state->attributes,
                    );

                    if ($orders_data->buySim($data)) {
                        $this->redirect($this->createUrl('card/checkout2'));
                    } else {
                        $msg = Yii::t('web/portal', 'insert_order_fail');

                        $this->redirect($this->createUrl('checkout/message', array('t' => 0)));
                    }
                }
            }

            //order amount
            $amount = (int)$modelOrder->getCardOrderAmount($orderDetails);

            $this->render('checkout_step1', array(
                'modelOrder'   => $modelOrder,
                'orderDetails' => $orderDetails,
                'operation'    => $orders_data->operation,
                'amount'       => $amount,
            ));
        }

        public function actionTopup()
        {
            $this->pageTitle = 'Sản phẩm - Nạp thẻ';
            OtpForm::unsetSessionHtmlOrder();
            $orders_data            = new OrdersData();
            $orders_data->operation = OrdersData::OPERATION_TOPUP;
            $modelOrder             = new WOrders();
            $orderDetails           = new WOrderDetails();
            $modelOrder->scenario   = 'topup';
            $orderDetails->scenario = 'topup';
            //validate ajax
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'topup_form') {
                echo CActiveForm::validate($modelOrder);
                Yii::app()->end();
            }
            if (isset($_POST['WOrders']) && isset($_POST['WOrderDetails'])) {
                $modelOrder->attributes   = $_POST['WOrders'];
                $orderDetails->attributes = $_POST['WOrderDetails'];
                $modelOrder->id           = $modelOrder->generateOrderId();
                //sso_id
                if (isset(Yii::app()->user->sso_id)) {
                    $modelOrder->sso_id = Yii::app()->user->sso_id;
                }
                //check cookie
                if (isset(Yii::app()->request->cookies['utm_source']) && !empty(Yii::app()->request->cookies['utm_source'])) {
                    $modelOrder->affiliate_source = Yii::app()->request->cookies['utm_source']->value;
                }
                if (isset(Yii::app()->request->cookies['aff_sid']) && !empty(Yii::app()->request->cookies['aff_sid'])) {
                    $modelOrder->affiliate_transaction_id = Yii::app()->request->cookies['aff_sid']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_source']) && !empty(Yii::app()->request->cookies['campaign_source'])) {
                    $modelOrder->campaign_source = Yii::app()->request->cookies['campaign_source']->value;
                }
                if (isset(Yii::app()->request->cookies['campaign_id']) && !empty(Yii::app()->request->cookies['campaign_id'])) {
                    $modelOrder->campaign_id = Yii::app()->request->cookies['campaign_id']->value;
                }
                $orderDetails->order_id  = $modelOrder->id;
                $orderDetails->item_id   = $orderDetails->price;
                $orderDetails->item_name = $orderDetails->price;
                $orderDetails->type      = WOrderDetails::TYPE_TOPUP;
                if ($modelOrder->validate() && $orderDetails->validate()) {
                    //fix test
                    $modelOrder->otp = '123456';
                    //set price discount
                    $orderDetails->price = (int)($orderDetails->price * WOrders::PRICE_DISCOUNT_CARD);

                    $orders_data->order_details['card'] = $orderDetails->attributes;
                    $orders_data->card                  = $orderDetails;
                    $orders_data->orders                = $modelOrder;

                    $order_state              = new WOrderState();
                    $order_state->order_id    = $modelOrder->id;
                    $order_state->confirm     = WOrderState::UNCONFIRMED;
                    $order_state->paid        = WOrderState::UNPAID;
                    $orders_data->order_state = $order_state;

                    //set session Order
                    Yii::app()->session['session_cart'] = time();
                    Yii::app()->session['orders_data']  = $orders_data;

                    $data = array(
                        'orders'        => $modelOrder->attributes,
                        'order_details' => $orders_data->order_details,
                        'order_state'   => $order_state->attributes,
                    );
                    if ($orders_data->buySim($data)) {
                        $this->redirect($this->createUrl('card/checkout2'));
                    } else {
                        $msg = Yii::t('web/portal', 'insert_order_fail');

                        $this->redirect($this->createUrl('checkout/message', array('t' => 0)));
                    }
                }
            }

            //order amount
            $amount = (int)$modelOrder->getCardOrderAmount($orderDetails);

            $this->render('checkout_step1', array(
                'modelOrder'   => $modelOrder,
                'orderDetails' => $orderDetails,
                'operation'    => $orders_data->operation,
                'amount'       => $amount,
            ));
        }

        /**
         * actionCheckout step 2
         */
        public function actionCheckout2()
        {
            $operation = isset(Yii::app()->session['orders_data']->operation) ? Yii::app()->session['orders_data']->operation : '';
            if (WOrders::checkOrdersSessionExists($operation) === FALSE) {
                OtpForm::unsetSession(TRUE);
                $msg = Yii::t('web/portal', 'session_timeout');
                $this->redirect($this->createUrl('checkout/message', array('t' => 2)));
            } else {
                $orders_data = Yii::app()->session['orders_data'];
                $orders      = $orders_data->orders;
                if ($orders_data->operation == OrdersData::OPERATION_BUYCARD) {
                    $pageTitle = 'Mua mã thẻ';
                } else {
                    $pageTitle = 'Nạp thẻ';
                }
                $this->pageTitle = $pageTitle . ' - Thanh toán';
                //order amount
                $amount = (int)$orders->getCardOrderAmount($orders_data->card);
                //get location_napas, location_vietinbank
                $location_napas      = WLocationNapas::model()->find('id=:id', array(':id' => WOrders::PROVINCE_CODE_CARD));
                $location_vietinbank = WLocationVietinbank::model()->find('id=:id', array(':id' => WOrders::PROVINCE_CODE_CARD));
                $location_vnptpay    = WLocationVnptpay::model()->find('id=:id', array(':id' => WOrders::PROVINCE_CODE_CARD));

                $model_pm = new WPaymentMethod();
                if (isset($_POST['WOrders']) && isset($_POST['WOrders']['payment_method']) && !empty($_POST['WOrders']['payment_method'])) {
                    $payment_method         = $_POST['WOrders']['payment_method'];
                    $orders->payment_method = $payment_method;//payment_method

                    if (isset(Yii::app()->session['message_order'])) {
                        $message_order                       = Yii::app()->session['message_order'];
                        $message_order['payment_method']     = $payment_method;
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
                                'msg'        => ''
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
                            $cod        = new Cod();
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
                    'qr_code'    => $model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_QR_CODE, $location_vietinbank, $location_napas, $location_vnptpay),
                    'napas_atm'  => $model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_NAPAS_ATM, $location_vietinbank, $location_napas, $location_vnptpay),
                    'napas_int'  => FALSE,
                    'vietinbank' => $model_pm->checkPaymentMethodAccept(WPaymentMethod::PM_VIETINBANK, $location_vietinbank, $location_napas, $location_vnptpay),//the quoc te
//                    'vietinbank' => FALSE,//the quoc te
                    'vietin_atm' => FALSE, //the noi dia
                    'vnpt_pay'   => FALSE, //vnpt_pay
                    'vnpay'      => FALSE, //vnpay
                    'cod'        => TRUE, //cod
                );

                //fix test username: vtb_test, pass=123456vtb
                if (!Yii::app()->user->isGuest && Yii::app()->user->sso_id == 'vbdpw8ftxnkgjoq9yc75z643ilra2sue') {
                    $arr_payment['vietin_atm'] = TRUE;
                }

                $this->render('checkout_step2', array(
                    'modelOrder'   => $orders,
                    'orderDetails' => $orders_data->card,
                    'amount'       => $amount,
                    'arr_payment'  => $arr_payment,
                    'operation'    => $orders_data->operation,
                ));
            }
        }

        /**
         * action get order price by ajax
         */
        public function actionGetOrderPrice()
        {
            $modelOrder   = new WOrders();
            $orderDetails = new WOrderDetails();
            $amount       = 0;
            $operation    = (isset($_POST['OrdersData_operation'])) ? $_POST['OrdersData_operation'] : '';

            if (isset($_POST['WOrders']) && isset($_POST['WOrderDetails'])) {
                $modelOrder->attributes   = $_POST['WOrders'];
                $orderDetails->agetOrderPricettributes = $_POST['WOrderDetails'];
                $orderDetails->item_id    = $orderDetails->price;
                $orderDetails->item_name  = $orderDetails->price;

                //set price discount
                $orderDetails->price = (int)($orderDetails->price * WOrders::PRICE_DISCOUNT_CARD);

                //order amount
                $amount = (int)$modelOrder->getCardOrderAmount($orderDetails);
            }

            echo CJSON::encode(
                array(
                    'content' => $this->renderPartial('_panel_order_table', array(
                        'modelOrder'   => $modelOrder,
                        'orderDetails' => $orderDetails,
                        'amount'       => $amount,
                        'operation'    => $operation,
                    ), TRUE)
                )
            );
        }

        public function actionOrderresult()
        {
            $url = $GLOBALS['config_common']['iframe']['order_result'];
            $this->render('order_result', array('url' => $url));
        }

        public function actionAccesscard()
        {
            $url = $GLOBALS['config_common']['iframe']['access_card'];
            $this->render('access_card', array('url' => $url));
        }
    } //end class