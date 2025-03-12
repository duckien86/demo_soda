<?php

class ReceiverController extends CController
{
    /**
     * Vietinbank Confirm payment
     * Output template :
     * {
     * 'requestId': String,
     * 'paymentStatus': String
     * }
     * value :'00' – Thành công ; '01' – Đơn hàng bị hủy bởi khách hàng ;'02' – Đơn hàng quá thời hạn thanh toán ;
     * '99' – Lỗi hệ thống
     */
    public function actionConfirmPayment()
    {
        // nhan input
        $raw_data   = file_get_contents("php://input");
        $data_arr   = CJSON::decode($raw_data);
        $return_arr = array('requestId' => '', 'paymentStatus' => '99');

        // khoi tao log
        $logMsg[] = array('Start Confirm Payment QR Code Response Log', 'Start proccess:', 'I', time());
        $logMsg[] = array($raw_data, 'Raw data:', 'T', time());

        // xu ly cap nhat don hang
        if ($data_arr && is_array($data_arr) && isset($data_arr['statusCode']) && isset($data_arr['orderId']) && isset($data_arr['requestId'])) {
            $logMsg[]                = array($data_arr['statusCode'], 'statusCode:', 'T', time());
            $return_arr['requestId'] = $data_arr['requestId'];
            //get cache
            $key        = 'qr_data_' . $data_arr['orderId'];
            $cache_data = Yii::app()->redis_vtb_qr->get($key);
            if ($cache_data) {
                $orders_data   = $cache_data['orders_data'];
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;

                if ($orders && $order_details && $order_state) {
                    //set payment method
                    $orders->payment_method = WPaymentMethod::PM_QR_CODE;
                    if ($data_arr['statusCode'] == '00') { //payment success
                        $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);
                    } else { //payment fail
                        $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                    }

                    $data = array(
                        'sim'           => $sim->attributes,
                        'orders'        => $orders->attributes,
                        'order_details' => $order_details,
                        'order_state'   => $order_state->attributes,
                    );
                    if ($orders_data->updateOrderStatus($data)) {
                        $return_arr['paymentStatus'] = '00';
                        $cache_data['status']        = TRUE;
                        Yii::app()->redis_vtb_qr->set($key, $cache_data, Yii::app()->params->cache_timeout_config['qr_code']); //30'
                    } else {
                        $return_arr['paymentStatus'] = '99';
                        $logMsg[]                    = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                    }
                } else {
                    $return_arr['paymentStatus'] = '99';
                    $logMsg[]                    = array('empty', '$orders||$order_details||$order_state:', 'E', time());
                }

                //insert log DB
                $statusCode = isset($data_arr['statusCode']) ? $data_arr['statusCode'] : '';
                $note       = isset($data_arr['statusMessage']) ? $data_arr['statusMessage'] : '';
                $urlRequest = $_SERVER['REQUEST_URI'];
                if ($statusCode == '00') {
                    $status_req = WTransactionRequest::REQUEST_SUCCESS;
                } else {
                    $status_req = WTransactionRequest::REQUEST_FAIL;
                }
                WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $data_arr['requestId'], $urlRequest, $raw_data, CJSON::encode($return_arr), WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, $status_req, $note, $logMsg);
            } else { //qua han thanh toan
                $return_arr['paymentStatus'] = '02';
                $logMsg[]                    = array("Key:$key| cache request id :" . $cache_data['requestId'], 'redis cache_data:', 'E', time());
            }
        } else { //khong ton tai don hang
            $return_arr['paymentStatus'] = '99';
            $logMsg[]                    = array('Invalid', 'Vietinbank request params:', 'E', time());
        }

        $return_data = CJSON::encode($return_arr);
        $logMsg[]    = array($return_data, 'Response data:', 'T', time());
        $logMsg[]    = array('', 'Finish process', 'F', time());
        $logFolder   = "web/Log_confirm_payment_qrcode_response/" . date("Y/m");
        $logObj      = SystemLog::getInstance($logFolder);
        $server_add  = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);

        // tra ket qua lai cho Vietinbank
        header('Content-Type:application/json');
        echo $return_data;
    }

    /**
     * redirect to message
     */
    public function actionGetState($redirect = FALSE)
    {
        $return_arr = array(
            'status'       => FALSE,
            'msg'          => '',
            'url_redirect' => '',
        );
        if (WOrders::checkOrdersSessionExists() === FALSE) {
            OtpForm::unsetSession(TRUE);
            $return_arr['url_redirect'] = $this->createUrl('checkout/message', array('t' => 2));
        } else {
            $orders_data = Yii::app()->session['orders_data'];
            $modelOrder  = $orders_data->orders;
            //get cache
            $key   = 'qr_data_' . $modelOrder->id;
            $state = FALSE;
            while ($state == FALSE) {
                $state = Yii::app()->redis_vtb_qr->get($key)['status'];
                sleep(2);
            }
            $return_arr['status'] = $state;
            if ($return_arr['status'] == TRUE) {
                $return_arr['url_redirect'] = $this->createUrl('checkout/message', array('t' => 1));
            } else {
                $return_arr['url_redirect'] = $this->createUrl('checkout/message', array('t' => 4));
            }
        }

        if ($redirect) {
            $this->redirect($return_arr['url_redirect']);
        } else {
            echo CJSON::encode($return_arr);
            Yii::app()->end();
        }
    }

    /**
     * ipn: from napas:
     * cache: from model WPaymentMethod()
     */
    public function actionIpn()
    {
        $raw_data = $_GET; //params response from napas

        // khoi tao log
        $logMsg[] = array('Start Napas IPN Response Log', 'Start proccess:', 'I', time());
        $logMsg[] = array($_SERVER['REQUEST_URI'], 'Request URI', 'T', time());
        $logMsg[] = array(CJSON::encode($raw_data), 'Raw data:', 'T', time());

        // xu ly cap nhat don hang
        if (isset($raw_data['vpc_ResponseCode']) && isset($raw_data['vpc_OrderInfo']) && !empty($raw_data['vpc_OrderInfo'])) {
            //get cache
            $key        = 'napas_data_' . $raw_data['vpc_OrderInfo'];
            $cache_data = Yii::app()->redis_napas->get($key);

            if ($cache_data && isset($cache_data['orders_data'])) {
                $orders_data   = $cache_data['orders_data'];
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;

                if ($sim && $orders && $order_details && $order_state) {
                    $check_vpc_SecureHash = Napas::checkVpcSecureHashResponse($orders, $raw_data, $arr_param, $vpc_SecureHash);

                    $logMsg[] = array(CJSON::encode($arr_param), 'arr_param_hashAllFields:', 'T', time());
                    $logMsg[] = array($vpc_SecureHash, 'vpc_SecureHash response:', 'T', time());
                    //check vpc_SecureHash && ResponseCode && order_id
                    if (($raw_data['vpc_ResponseCode'] == '0') && ($check_vpc_SecureHash == TRUE)) {
                        if ($raw_data['vpc_OrderInfo'] == $orders->id) {
                            //set order_state
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                            $data = array(
                                'sim'           => $sim->attributes,
                                'orders'        => $orders->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state'   => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) { //success
                                Yii::app()->redis_napas->delete($key);
                                $cache_data['status'] = TRUE;
                                $logMsg[]             = array('Success', '$orders_data->updateOrderStatus():', 'T', time());
                            } else { //fail
                                $logMsg[] = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                            }
                        } else {
                            $logMsg[] = array('cache_data: order_id', $orders->id, 'E', time());
                            $logMsg[] = array('response from napas: order_id', $raw_data['vpc_OrderInfo'], 'E', time());
                        }
                    } else { //thanh toan that bai
                        $logMsg[] = array($check_vpc_SecureHash, 'Napas::checkVpcSecureHashResponse:', 'E', time());
                        $logMsg[] = array('Invalid', 'Payment fail:', 'E', time());
                    }
                    //insert log DB
                    $vpc_MerchTxnRef  = isset($raw_data['vpc_MerchTxnRef']) ? $raw_data['vpc_MerchTxnRef'] : '';
                    $vpc_ResponseCode = $raw_data['vpc_ResponseCode'];
                    $urlRequest       = Yii::app()->controller->createAbsoluteUrl('receiver/ipn');
                    $note             = Napas::getContentError($vpc_ResponseCode);
                    $query_string     = $_SERVER['QUERY_STRING'];
                    if ($vpc_ResponseCode == '0') {
                        $status_req = WTransactionRequest::REQUEST_SUCCESS;
                    } else {
                        $status_req = WTransactionRequest::REQUEST_FAIL;
                    }
                    WTransactionResponse::writeLog(WTransactionRequest::NAPAS, $orders, $vpc_MerchTxnRef, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note, $logMsg);
                } else {
                    $logMsg[] = array('empty', '$sim||$orders||$order_details||$order_state:', 'E', time());
                }
            } else { //qua han thanh toan
                $logMsg[] = array("Key:$key| cache request id :" . $cache_data['requestId'], 'redis cache_data:', 'E', time());
            }
        } else { //khong ton tai don hang
            $logMsg[] = array('Invalid', 'Napas request params:', 'E', time());
        }

        $logMsg[]   = array('', 'Finish process', 'F', time());
        $logFolder  = "web/Log_napas_ipn_response/" . date("Y/m");
        $logObj     = SystemLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);
    }

    /**
     * response from Vietinbank
     * cache: from model WPaymentMethod()
     */
    public function actionResult()
    {
        $raw_data = $_REQUEST; //params response from vietinbank

        foreach ($_REQUEST as $name => $value) {
            $raw_data[$name] = $value;
        }
        // khoi tao log
        $logMsg[] = array('Start Vietinbank Response Log', 'Start proccess:', 'I', time());
        $logMsg[] = array($_SERVER['REQUEST_URI'], 'Request URI', 'T', time());
        $logMsg[] = array(CJSON::encode($raw_data), 'Raw data:', 'T', time());

        $vietinbank = new Vietinbank();
        // xu ly cap nhat don hang
        if (isset($raw_data['signature']) && isset($raw_data['req_reference_number']) && isset($raw_data['reason_code'])) {
            //get cache
            $key        = 'vietinbank_data_' . $raw_data['req_reference_number'];
            $cache_data = Yii::app()->redis_vtb->get($key);
            if ($cache_data && isset($cache_data['orders_data'])) {
                $orders_data   = $cache_data['orders_data'];
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;
                if ($sim && $orders && $order_details && $order_state) {
                    //check sign(fields) signature
                    $signature  = $vietinbank->sign($raw_data);
                    $logMsg[]   = array($signature, 'signature sign():', 'T', time());
                    $logMsg[]   = array($raw_data['signature'], 'signature response raw_data:', 'T', time());
                    $status_req = WTransactionRequest::REQUEST_FAIL;
                    if (
                        strcmp($raw_data['signature'], $signature) == 0
                        && $raw_data['reason_code'] == 100
                    ) { //success
                        $status_req = WTransactionRequest::REQUEST_SUCCESS;
                        //set order_state
                        $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                        $data = array(
                            'sim'           => $sim->attributes,
                            'orders'        => $orders->attributes,
                            'order_details' => $orders_data->order_details,
                            'order_state'   => $order_state->attributes,
                        );
                        if ($orders_data->updateOrderStatus($data)) { //success
                            // clear transaction cache
                            Yii::app()->redis_vtb->delete($key);
                            $cache_data['status'] = TRUE;
                            $logMsg[]             = array('Success', '$orders_data->updateOrderStatus():', 'T', time());
                        } else { //fail
                            $logMsg[] = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                        }
                    } else { //thanh toan that bai
                        $logMsg[] = array('Fail', 'Payment:', 'E', time());
                    }
                    //insert log DB
                    $req_reference_number = $raw_data['req_reference_number'];
                    $decision             = isset($raw_data['decision']) ? $raw_data['decision'] : '';
                    $decision             = Vietinbank::getErrorCode($decision, WPaymentMethod::PM_VIETINBANK);
                    $urlRequest           = Yii::app()->controller->createAbsoluteUrl('receiver/result');
                    $note                 = isset($raw_data['message']) ? $raw_data['message'] : $decision;
                    $query_string         = CFunction::implodeParams($raw_data);
                    WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $req_reference_number, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note, $logMsg);
                } else {
                    $logMsg[] = array('Empty', '$sim||$orders||$order_details||$order_state:', 'E', time());
                }
            } else { //qua han thanh toan
                $logMsg[] = array("Key:$key| cache request id :" . $cache_data['requestId'], 'redis cache_data:', 'E', time());
            }
        } else { //khong ton tai don hang
            $logMsg[] = array('Invalid', 'Vietinbank request params:', 'E', time());
        }

        $logMsg[]   = array('', 'Finish process', 'F', time());
        $logFolder  = "web/confirmPaymentVtbDomestic/" . date("Y/m");
        $logObj     = SystemLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);
    }

    /**
     * return from vnpay
     */
    public function actionReturn()
    {
        $raw_data = $_GET; //params response from vnpay

        // khoi tao log
        $logMsg[] = array('Start Vnpay Response Log', 'Start proccess:', 'I', time());
        $logMsg[] = array($_SERVER['REQUEST_URI'], 'Request URI', 'T', time());
        $logMsg[] = array(CJSON::encode($raw_data), 'Raw data:', 'T', time());

        //check params
        if (isset($raw_data['vnp_ResponseCode']) && isset($raw_data['vnp_TxnRef'])) {
            $vnp_ResponseCode = $raw_data['vnp_ResponseCode'];
            $vnp_TxnRef       = $raw_data['vnp_TxnRef'];
            //get cache
            $key        = 'vnpay_data_' . $vnp_TxnRef;
            $cache_data = Yii::app()->redis_vtb->get($key);

            if ($cache_data && isset($cache_data['orders_data']) && isset($cache_data['requestId'])) {
                $orders_data   = $cache_data['orders_data'];
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;

                if ($sim && $orders && $order_details && $order_state) {
                    $check_vnp_SecureHash = Vietinbank::checkVnpSecureHashReturn($orders_data, $raw_data, $arr_param, $vnp_SecureHash);
                    $logMsg[]             = array(CJSON::encode($arr_param), 'arr_param_hashAllFields:', 'T', time());
                    $logMsg[]             = array($vnp_SecureHash, 'vnp_SecureHash response:', 'T', time());
                    if (($check_vnp_SecureHash == TRUE) && ($vnp_ResponseCode == '00')) { //success
                        if ($cache_data['requestId'] == $vnp_TxnRef) {
                            //set order_state
                            //$order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED);
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                            $data = array(
                                'sim'           => $sim->attributes,
                                'orders'        => $orders->attributes,
                                'order_details' => $order_details,
                                'order_state'   => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) {
                                // clear transaction cache
                                Yii::app()->redis_vtb->delete($key);
                            } else { //fail
                                $logMsg[] = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                            }
                        } else {
                            $logMsg[] = array('cache_data: vnp_TxnRef', $cache_data['requestId'], 'E', time());
                            $logMsg[] = array('response from vnpay: vnp_TxnRef', $vnp_TxnRef, 'E', time());
                        }
                    } else { //payment fail
                        $logMsg[] = array($check_vnp_SecureHash, 'Vietinbank::checkVnpSecureHashReturn():', 'E', time());
                        $logMsg[] = array('Invalid', 'Payment fail:', 'E', time());
                    }
                    //insert log DB
                    $note         = Vietinbank::getErrorCode($vnp_ResponseCode, WPaymentMethod::PM_VNPAY);
                    $urlRequest   = Yii::app()->controller->createAbsoluteUrl('receiver/return');
                    $query_string = $_SERVER['QUERY_STRING'];
                    if ($vnp_ResponseCode == '00') {
                        $status_req = WTransactionRequest::REQUEST_SUCCESS;
                    } else {
                        $status_req = WTransactionRequest::REQUEST_FAIL;
                    }
                    WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $vnp_TxnRef, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note, $logMsg);
                } else {
                    $logMsg[] = array('empty', '$sim||$orders||$order_details||$order_state:', 'E', time());
                }
            } else { //timeout
                $logMsg[] = array("Key:$key| cache request id :" . $cache_data['requestId'], 'redis cache_data:', 'E', time());
            }
        } else {
            $logMsg[] = array('Invalid', 'Vnpay request params:', 'E', time());
        }

        $logMsg[]   = array('', 'Finish process', 'F', time());
        $logFolder  = "web/Log_vnpay_response/" . date("Y/m");
        $logObj     = SystemLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);
    }

    /**
     * confirm payment from Vietinbank (internal)
     */
    public function actionConfirmPaymentVtbDomestic()
    {
        // khoi tao log
        $logMsg[]   = array('Start Vietinbank ConfirmPaymentVtbDomestic Log', 'Start proccess:', 'I', time());
        $logMsg[]   = array($_SERVER['REQUEST_URI'], 'Request URI' . __LINE__, 'T', time());
        $return_arr = array(
            'requestId'            => '',
            'providerId'           => '',
            'merchantId'           => '',
            'confirmTransactionId' => '',
            'transTime'            => '',
            'bankTransactionId'    => '',
            'responseCode'         => '',
            'responseDesc'         => '',
            'sessionId'            => '',
            'urlResult'            => '',
            'addInfo'              => '',
            'signature'            => '',
        );

        $vietinbank = new Vietinbank();

        //params request from vtb
        $input_data = file_get_contents("php://input");
        $raw_data   = CJSON::decode($input_data);
        if ($raw_data && is_array($raw_data)) {
            $logMsg[] = array($input_data, 'Raw data:' . __LINE__, 'T', time());
            //check params
            if (
                !empty($raw_data['responseCode']) && !empty($raw_data['requestId']) && !empty($raw_data['serviceCode'])
                && !empty($raw_data['providerId']) && !empty($raw_data['merchantId']) && !empty($raw_data['confirmTransactionId'])
                && !empty($raw_data['signature'])
            ) {
                //from raw data
                $return_arr['requestId']            = $raw_data['requestId'];
                $return_arr['providerId']           = $raw_data['providerId'];
                $return_arr['merchantId']           = $raw_data['merchantId'];
                $return_arr['confirmTransactionId'] = $raw_data['confirmTransactionId'];
                $return_arr['bankTransactionId']    = $raw_data['bankTransactionId'];
                $return_arr['sessionId']            = $raw_data['sessionId'];
                $return_arr['addInfo']              = $raw_data['addInfo'];
                //confirmTransactionId: requestId from function initPayment(WPaymentMethod->paymentViaVietinAtm)
                $confirmTransactionId = $raw_data['confirmTransactionId'];
                //get cache
                $key        = 'vietin_atm_data_' . $confirmTransactionId;
                $cache_data = Yii::app()->redis_vtb->get($key);

                if ($cache_data && !empty($cache_data['orders_data'])) {
                    $orders_data   = $cache_data['orders_data'];
                    $sim           = $orders_data->sim;
                    $orders        = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state   = $orders_data->order_state;
                    if ($sim && $orders && $order_details && $order_state) {
                        $orders->payment_method = WPaymentMethod::PM_VIETIN_ATM;
                        if (($raw_data['responseCode'] == '00')
                            && ($vietinbank->verifySignature($raw_data, $vietinbank->pPublicKey, $raw_data['signature'], $logMsg))
                        ) { //success
                            //set order_state
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);
                            $data = array(
                                'sim'           => $sim->attributes,
                                'orders'        => $orders->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state'   => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) {
                                //success
                                $return_arr['responseCode'] = '00';
                                $logMsg[]                   = array('Success', '$orders_data->updateOrderStatus():' . __LINE__, 'T', time());
                                // clear transaction cache
                                Yii::app()->redis_vtb->delete($key);
                            } else {
                                $return_arr['responseCode'] = '96';
                                $logMsg[]                   = array('Fail', '$orders_data->updateOrderStatus():' . __LINE__, 'E', time());
                            }
                        } else {
                            $return_arr['responseCode'] = '93';
                            $logMsg[]                   = array('Fail', 'verifySignature():' . __LINE__, 'E', time());
                        }
                        //insert log DB
                        $requestId    = $raw_data['requestId'];
                        $responseCode = $raw_data['responseCode'];
                        $urlRequest   = Yii::app()->controller->createAbsoluteUrl('receiver/confirmPaymentVtbDomestic');
                        $note         = Vietinbank::getErrorCode($responseCode, WPaymentMethod::PM_VIETIN_ATM);
                        if ($responseCode == '00') {
                            $status_req = WTransactionRequest::REQUEST_SUCCESS;
                        } else {
                            $status_req = WTransactionRequest::REQUEST_FAIL;
                        }
                        WTransactionResponse::writeLog(WTransactionRequest::VIETINBANK, $orders, $requestId, $urlRequest, $input_data, CJSON::encode($return_arr), WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, $status_req, $note, $logMsg);
                    } else {
                        //timeout
                        $return_arr['responseCode'] = '99';
                        $logMsg[]                   = array('empty', '$sim||$orders||$order_details||$order_state:' . __LINE__, 'E', time());
                    }
                } else {
                    //timeout
                    $return_arr['responseCode'] = '99';
                    $logMsg[]                   = array("raw_data requestId key:$confirmTransactionId| cache request id :" . $cache_data['requestId'], 'redis cache_data:' . __LINE__, 'E', time());
                }
            } else {
                //error
                $return_arr['responseCode'] = '90';
                $logMsg[]                   = array('empty', 'responseCode||requestId||serviceCode||providerId||merchantId||confirmTransactionId||signature:' . __LINE__, 'E', time());
            }
        } else {
            //error
            $return_arr['responseCode'] = '99';
            $logMsg[]                   = array('empty', 'raw_data:' . __LINE__, 'E', time());
        }

        $return_arr['responseDesc'] = Vietinbank::getErrorCode($return_arr['responseCode'], WPaymentMethod::PM_VIETIN_ATM);
        $return_arr['transTime']    = date('YmdHis');
        if ($return_arr['responseCode'] == '00') {
            //create signature
            $return_arr['signature'] = $vietinbank->createSignature($return_arr, $vietinbank->pPrivateKey);
            $return_arr['urlResult'] = Yii::app()->controller->createAbsoluteUrl('checkout/message', array('t' => 1));
        } else {
            $return_arr['urlResult'] = Yii::app()->controller->createAbsoluteUrl('checkout/message', array('t' => 5));
        }
        $return_data = CJSON::encode($return_arr);
        $logMsg[]    = array($return_data, 'Response data:' . __LINE__, 'T', time());
        $logMsg[]    = array('', 'Finish process' . __LINE__, 'F', time());
        $logFolder   = "web/Log_confirm_payment_vtb_domestic/" . date("Y/m");
        $logObj      = SystemLog::getInstance($logFolder);
        $server_add  = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);
        //end log

        // tra ket qua lai cho Vietinbank
        header('Content-Type:application/json');
        header('HTTP/1.0 200 Success');
        echo $return_data;
    }

    /**
     * confirm payment vnpt_pay
     */
    public function actionConfirmPaymentVnptpay()
    {
        // khoi tao log
        $logMsg[]   = array('Start ConfirmPaymentVnptpay Log', 'Start proccess:', 'I', time());
        $logMsg[]   = array($_SERVER['REQUEST_URI'], 'Request URI' . __LINE__, 'T', time());
        $return_arr = array(
            'RESPONSE_CODE'       => '',
            'DESCRIPTION'         => '',
            'MERCHANT_SERVICE_ID' => '',
            'MERCHANT_ORDER_ID'   => '',
            'CREATE_DATE'         => '',
            'SECURE_CODE '        => '',
        );
        $vnpt_pay   = new VnptPay();

        //params request from vnpt pay
        $input_data = $_POST;
        if (!empty($input_data['data'])) {
            $raw_data_log = CJSON::encode($input_data);
            $logMsg[]     = array($raw_data_log, 'Raw data:' . __LINE__, 'T', time());
            $raw_data     = $input_data['data'];
            if (!is_array($raw_data)) {
                $raw_data = CJSON::decode($raw_data);
            }
            //check params
            if (
                isset($raw_data['ACTION']) && isset($raw_data['RESPONSE_CODE']) && isset($raw_data['MERCHANT_SERVICE_ID'])
                && isset($raw_data['MERCHANT_ORDER_ID']) && isset($raw_data['VNPTPAY_TRANSACTION_ID'])
                && isset($raw_data['AMOUNT']) && isset($raw_data['PAYMENT_METHOD'])
                && isset($raw_data['SECURE_CODE'])
            ) {
                $return_arr['MERCHANT_SERVICE_ID'] = $raw_data['MERCHANT_SERVICE_ID'];
                $return_arr['MERCHANT_ORDER_ID']   = $raw_data['MERCHANT_ORDER_ID'];
                $order_id                          = $raw_data['MERCHANT_ORDER_ID'];
                //                  $requestId    = $raw_data['VNPTPAY_TRANSACTION_ID'];
                $requestId = $raw_data['MERCHANT_ORDER_ID'];
                //get cache
                $key        = 'vnpt_pay_data_' . $order_id;
                $cache_data = Yii::app()->redis_vnpt_pay->get($key);

                if ($cache_data && !empty($cache_data['orders_data'])) {
                    $orders_data   = $cache_data['orders_data'];
                    $sim           = isset($orders_data->sim) ? $orders_data->sim : false;
                    $orders        = $orders_data->orders;
                    $order_details = $orders_data->order_details;
                    $order_state   = $orders_data->order_state;
                    $package  = isset($order_details->packages) ? $order_details->packages : false;
                    if (($sim && $orders && $order_details && $order_state) || $package) {
                        $orders->payment_method = WPaymentMethod::PM_VNPT_PAY;
                        //check secure_code
                        $arr_data_hash = array(
                            'ACTION'                 => $raw_data['ACTION'],
                            'RESPONSE_CODE'          => $raw_data['RESPONSE_CODE'],
                            'MERCHANT_SERVICE_ID'    => $raw_data['MERCHANT_SERVICE_ID'],
                            'MERCHANT_ORDER_ID'      => $raw_data['MERCHANT_ORDER_ID'],
                            'AMOUNT'                 => $raw_data['AMOUNT'],
                            'CURRENCY_CODE'          => isset($raw_data['CURRENCY_CODE']) ? $raw_data['CURRENCY_CODE'] : '',
                            'VNPTPAY_TRANSACTION_ID' => $raw_data['VNPTPAY_TRANSACTION_ID'],
                            'PAYMENT_METHOD'         => $raw_data['PAYMENT_METHOD'],
                            'PAY_DATE'               => isset($raw_data['PAY_DATE']) ? $raw_data['PAY_DATE'] : '',
                            'ADDITIONAL_INFO'        => isset($raw_data['ADDITIONAL_INFO']) ? $raw_data['ADDITIONAL_INFO'] : '',
                        );
                        $secure_code   = $vnpt_pay->createSecureCode($arr_data_hash, $vnpt_pay->secret_key, $log_secure);
                        $logMsg[]      = array($log_secure, 'string request data to hash :' . __LINE__, 'T');
                        $logMsg[]      = array($secure_code, 'SECURE_CODE createSecureCode():', 'T', time());
                        $logMsg[]      = array($raw_data['SECURE_CODE'], 'SECURE_CODE raw_data:', 'T', time());
                        if (($raw_data['RESPONSE_CODE'] == '00')
                            // && (strcmp($raw_data['SECURE_CODE'], $secure_code) == 0)
                        ) { //success

                            //set order_state
                            $order_state->order_id  = $orders->id;
                            $order_state->confirm   = WOrderState::CONFIRMED;
                            $order_state->paid      = WOrderState::PAID;
                            $order_state->delivered = '';

                            $version_api = null;
                            // nếu là thanh toán cho gói internet đặc thù 
                            if ($package) {
                                $version_api = '_v2';
                                $data = array(
                                    'orders'        => json_decode(json_encode($orders)),
                                    'order_details' => $orders_data->order_details,
                                    'order_state'   => json_decode(json_encode($order_state)),
                                );
                            } else {
                                $data = array(
                                    'sim'           => $sim->attributes,
                                    'orders'        => $orders->attributes,
                                    'order_details' => $orders_data->order_details,
                                    'order_state'   => $order_state->attributes,
                                );
                            }
                            $orderDataObj = new OrdersData();
                            if ($orderDataObj->updateOrderStatus($data, $version_api)) {
                                //success
                                $return_arr['RESPONSE_CODE'] = '00';
                                $logMsg[]                    = array('Success', '$orders_data->updateOrderStatus():' . __LINE__, 'T', time());
                                // clear transaction cache
                                Yii::app()->redis_vnpt_pay->delete($key);
                            } else {
                                $return_arr['RESPONSE_CODE'] = '99';
                                $logMsg[]                    = array('Fail', '$orders_data->updateOrderStatus():' . __LINE__, 'E', time());
                            }
                        } else {
                            $return_arr['RESPONSE_CODE'] = '97';
                            $logMsg[]                    = array('Fail', 'RESPONSE_CODE || verify SecureCode:' . __LINE__, 'E', time());
                        }
                        //insert log DB
                        $responseCode = $raw_data['RESPONSE_CODE'];
                        $urlRequest   = Yii::app()->controller->createAbsoluteUrl('receiver/confirmPaymentVnptpay');
                        $note         = VnptPay::getContentError($responseCode);
                        if ($responseCode == '00') {
                            $status_req = WTransactionRequest::REQUEST_SUCCESS;
                        } else {
                            $status_req = WTransactionRequest::REQUEST_FAIL;
                        }
                        WTransactionResponse::writeLog(WTransactionRequest::VNPT_PAY, $orders, $requestId, $urlRequest, $input_data, CJSON::encode($return_arr), WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, $status_req, $note, $logMsg);
                    } else {
                        //timeout
                        $return_arr['RESPONSE_CODE'] = '01';
                        $logMsg[]                    = array('empty', '$sim||$orders||$order_details||$order_state:' . __LINE__, 'E', time());
                    }
                } else {
                    //timeout
                    $return_arr['RESPONSE_CODE'] = '01';
                    $logMsg[]                    = array("raw_data requestId key:$requestId | cache request id :" . $cache_data['requestId'], 'redis cache_data:' . __LINE__, 'E', time());
                }
            } else {
                //error
                $return_arr['RESPONSE_CODE'] = '99';
                $logMsg[]                    = array('empty', 'ACTION||RESPONSE_CODE||MERCHANT_SERVICE_ID||MERCHANT_ORDER_ID||VNPTPAY_TRANSACTION_ID||AMOUNT||SECURE_CODE:' . __LINE__, 'E', time());
            }
        } else {
            //error
            $return_arr['RESPONSE_CODE'] = '99';
            $logMsg[]                    = array('empty', 'raw_data:' . __LINE__, 'E', time());
        }

        $return_arr['DESCRIPTION'] = VnptPay::getContentErrorResponse($return_arr['RESPONSE_CODE']);
        $return_arr['CREATE_DATE'] = date('YmdHis');
        if ($return_arr['RESPONSE_CODE'] == '00') {
            //create secure_code
            $secure_code_res            = $vnpt_pay->createSecureCode($return_arr, $vnpt_pay->secret_key, $log_secure);
            $logMsg[]                   = array($log_secure, 'string response data to hash :' . __LINE__, 'T');
            $logMsg[]                   = array($secure_code_res, 'SECURE_CODE response api confirm createSecureCode():', 'T', time());
            $return_arr['SECURE_CODE '] = $secure_code_res;
            //                $return_arr['urlResult']    = Yii::app()->controller->createAbsoluteUrl('checkout/message', array('t' => 1));
        }
        //            else {
        //                $return_arr['urlResult'] = Yii::app()->controller->createAbsoluteUrl('checkout/message', array('t' => 5));
        //            }
        $return_data = CJSON::encode($return_arr);
        $logMsg[]    = array($return_data, 'Response data:' . __LINE__, 'T', time());
        $logMsg[]    = array('', 'Finish process' . __LINE__, 'F', time());
        $logFolder   = "web/Log_confirm_payment_vnpt_pay/" . date("Y/m");
        $logObj      = SystemLog::getInstance($logFolder);
        $server_add  = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);
        //end log

        // tra ket qua lai cho vnpt_pay
        header('Content-Type:application/json');
        header('HTTP/1.0 200 Success');
        echo $return_data;
    }

    /**
     * customer click link from sms, email
     * check link (DB)->check redis ->checkout
     *
     * @param $token
     *
     * @throws CHttpException
     */
    public function actionCustomerLink($token)
    {
        $modelTokenLink = WTokenLinks::model()->find('id=:id AND status=:status', array(':id' => $token, ':status' => WTokenLinks::STATUS_SUCCESS));
        if ($modelTokenLink) {
            //get cache
            $key        = 'ktv_add_cart_' . $token;
            $cache_data = Yii::app()->redis_ktv->get($key);

            $orders       = isset($cache_data->orders) ? $cache_data->orders : array();
            $sim          = isset($cache_data->sim) ? $cache_data->sim : array();
            $session_cart = isset($cache_data->session_cart) ? $cache_data->session_cart : '';
            $sim_raw_data = isset($cache_data->sim_raw_data) ? $cache_data->sim_raw_data : array();
            if ($orders && $sim && $session_cart && $sim_raw_data) {
                $orders_data               = new OrdersData();
                $orders_data->operation    = OrdersData::OPERATION_BUYSIM;
                $orders_data->sim_raw_data = $sim_raw_data;
                $modelOrder                = new WOrders();
                $modelOrder->attributes    = $orders->attributes;
                $modelOrder->active_cod    = $orders->active_cod;
                $modelSim                  = new WSim();
                $modelSim->attributes      = $sim->attributes;
                $modelSim->term            = $sim->term;
                $modelSim->price_term      = $sim->price_term;
                $modelSim->raw_data        = $sim->raw_data;

                //set session
                $orders_data->sim                   = $modelSim;
                $orders_data->orders                = $modelOrder;
                Yii::app()->session['orders_data']  = $orders_data;
                Yii::app()->session['session_cart'] = $session_cart;
                //delete redis
                Yii::app()->redis_ktv->delete($key);
                $this->redirect($this->createUrl('checkout/checkout'));
            } else {
                throw new CHttpException(404, 'The requested page does not exist.');
            }
        } else {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
    }

    /**
     * response from Tiki
     * cache: from SimController/addtocart
     */
    public function actionConfirmPaymentIframe()
    {
        $raw_data = $_GET; //params response from tiki

        // khoi tao log
        $logMsg[]   = array('Start Confirm payment iframe Log', 'Start proccess:', 'I', time());
        $logMsg[]   = array($_SERVER['REQUEST_URI'], 'Request URI', 'T', time());
        $logMsg[]   = array(CJSON::encode($raw_data), 'Raw data:', 'T', time());
        $return_arr = array(
            'transaction_id' => '',
            'response_code'  => '99'
        );
        // xu ly cap nhat don hang
        if (isset($raw_data['response_code']) && isset($raw_data['transaction_id']) && isset($raw_data['order_id']) && !empty($raw_data['order_id'])) {
            $return_arr['transaction_id'] = $raw_data['transaction_id'];
            //get cache
            $key         = 'orders_data_iframe_' . $raw_data['order_id'];
            $orders_data = Yii::app()->redis_orders_data->get($key);

            if ($orders_data && isset($orders_data->sim) && isset($orders_data->orders) && isset($orders_data->order_details) && isset($orders_data->order_state)) {
                $sim           = $orders_data->sim;
                $orders        = $orders_data->orders;
                $order_details = $orders_data->order_details;
                $order_state   = $orders_data->order_state;

                if ($sim && $orders && $order_details && $order_state) {

                    //check response_code && order_id
                    if (($raw_data['response_code'] == '00')) {
                        if ($raw_data['order_id'] == $orders->id) {
                            //set order_state
                            $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                            $data = array(
                                'sim'           => $sim->attributes,
                                'orders'        => $orders->attributes,
                                'order_details' => $orders_data->order_details,
                                'order_state'   => $order_state->attributes,
                            );
                            if ($orders_data->updateOrderStatus($data)) { //success
                                $return_arr['response_code'] = '00';
                                Yii::app()->redis_orders_data->delete($key);
                                $logMsg[] = array('Success', '$orders_data->updateOrderStatus():', 'T', time());
                            } else { //fail
                                $return_arr['response_code'] = '99';
                                $logMsg[]                    = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                            }
                        } else {
                            $return_arr['response_code'] = '01';
                            $logMsg[]                    = array('cache_data: order_id', $orders->id, 'E', time());
                            $logMsg[]                    = array('response: order_id', $raw_data['order_id'], 'E', time());
                        }
                    } else { //thanh toan that bai
                        $return_arr['response_code'] = '01';
                        $logMsg[]                    = array('payment fail response_code: ', $raw_data['response_code'], 'E', time());
                    }
                    //insert log DB
                    $transaction_id = $raw_data['transaction_id'];
                    $response_code  = $raw_data['response_code'];
                    $urlRequest     = Yii::app()->controller->createAbsoluteUrl('receiver/confirmPaymentIframe');
                    $note           = '';
                    $query_string   = $_SERVER['QUERY_STRING'];
                    if ($response_code == '00') {
                        $status_req = WTransactionRequest::REQUEST_SUCCESS;
                    } else {
                        $status_req = WTransactionRequest::REQUEST_FAIL;
                    }
                    WTransactionResponse::writeLog(WTransactionRequest::TIKI, $orders, $transaction_id, $urlRequest, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note, $logMsg);
                } else {
                    $return_arr['response_code'] = '01';
                    $logMsg[]                    = array('empty', '$sim||$orders||$order_details||$order_state:', 'E', time());
                }
            } else { //qua han thanh toan
                $return_arr['response_code'] = '01';
                $logMsg[]                    = array("time out", 'redis cache_data:', 'E', time());
            }
        } else { //khong ton tai don hang
            $return_arr['response_code'] = '01';
            $logMsg[]                    = array('Invalid', 'request params:', 'E', time());
        }

        $return_data = CJSON::encode($return_arr);
        $logMsg[]    = array($return_data, 'Response data:', 'T', time());
        $logMsg[]    = array('', 'Finish process', 'F', time());
        $logFolder   = "web/Log_confirm_payment_iframe_response/" . date("Y/m");
        $logObj      = SystemLog::getInstance($logFolder);
        $server_add  = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);

        // return result
        header('Content-Type:application/json');
        switch ($return_arr['response_code']) {
            case '01':
                header('HTTP/1.0 400 Bad Request');
                break;
            case '99':
                header('HTTP/1.0 500 Server Busy');
                break;
            default:
                header('HTTP/1.0 200 Success');
        }
        echo $return_data;
    }

    /**
     * response from Tiki
     * cache: from ApisgwController/checkout
     */
    public function actionUpdateOrderStatus()
    {
        $raw_data = file_get_contents("php://input");
        // khoi tao log
        $logMsg[] = array('Start update order status Log', 'Start proccess:', 'I', time());
        $logMsg[] = array($_SERVER['REQUEST_URI'], 'Request URI', 'T', time());
        $logMsg[] = array($raw_data, 'Raw data:', 'T', time());

        $return_arr = array(
            'transaction_id' => '',
            'response_code'  => ''
        );
        if (Yii::app()->request->isPostRequest) {
            $response_code  = Yii::app()->request->getParam('response_code', '');
            $transaction_id = Yii::app()->request->getParam('transaction_id', '');
            $order_id       = Yii::app()->request->getParam('order_id', '');
            $secure         = Yii::app()->request->getParam('secure', '');
            // xu ly cap nhat don hang
            if ($response_code && $transaction_id && $order_id && $secure) {
                $arr_params  = array(
                    'response_code'  => $response_code,
                    'transaction_id' => $transaction_id,
                    'order_id'       => $order_id,
                );
                $secure_hash = $this->hashAllFields($arr_params);
                $logMsg[]    = array($secure, 'secure raw_data:' . __LINE__, 'T', time());
                $logMsg[]    = array($secure_hash, 'secure_hash hashAllFields():' . __LINE__, 'T', time());
                if ($secure == $secure_hash) {
                    $return_arr['transaction_id'] = $transaction_id;
                    //get cache
                    $key         = 'orders_data_api_' . $order_id;
                    $orders_data = Yii::app()->redis_orders_data->get($key);

                    if ($orders_data && isset($orders_data->sim) && isset($orders_data->orders) && isset($orders_data->order_details) && isset($orders_data->order_state)) {
                        $sim           = $orders_data->sim;
                        $orders        = $orders_data->orders;
                        $order_details = $orders_data->order_details;
                        $order_state   = $orders_data->order_state;

                        if ($sim && $orders && $order_details && $order_state) {

                            //check response_code && order_id
                            if (($response_code == '00')) {
                                if ($order_id == $orders->id) {
                                    $orders->payment_method = WPaymentMethod::PM_TIKI;
                                    //set order_state
                                    $order_state->setOrderState($orders, $order_state, WOrderState::CONFIRMED, WOrderState::PAID);

                                    $data = array(
                                        'sim'           => $sim->attributes,
                                        'orders'        => $orders->attributes,
                                        'order_details' => $orders_data->order_details,
                                        'order_state'   => $order_state->attributes,
                                    );
                                    if ($orders_data->updateOrderStatus($data)) { //success
                                        $return_arr['response_code'] = '00';
                                        Yii::app()->redis_orders_data->delete($key);
                                        $logMsg[] = array('Success', '$orders_data->updateOrderStatus():', 'T', time());
                                    } else { //fail
                                        $return_arr['response_code'] = '99';
                                        $logMsg[]                    = array('Fail', '$orders_data->updateOrderStatus():', 'E', time());
                                    }
                                } else {
                                    $return_arr['response_code'] = '01';
                                    $logMsg[]                    = array('cache_data: order_id', $orders->id, 'E', time());
                                    $logMsg[]                    = array('response: order_id', $order_id, 'E', time());
                                }
                            } else { //thanh toan that bai
                                $return_arr['response_code'] = '01';
                                $logMsg[]                    = array('payment fail response_code: ', $response_code, 'E', time());
                            }
                            //insert log DB
                            $urlRequest   = Yii::app()->controller->createAbsoluteUrl('receiver/updateOrderStatus');
                            $note         = '';
                            if ($response_code == '00') {
                                $status_req = WTransactionRequest::REQUEST_SUCCESS;
                            } else {
                                $status_req = WTransactionRequest::REQUEST_FAIL;
                            }
                            WTransactionResponse::writeLog(WTransactionRequest::TIKI, $orders, $transaction_id, $urlRequest, $raw_data, '', WTransactionRequest::TYPE_QUERY_PARAM, '', $status_req, $note, $logMsg);
                        } else {
                            $return_arr['response_code'] = '01';
                            $logMsg[]                    = array('empty', '$sim||$orders||$order_details||$order_state:', 'E', time());
                        }
                    } else { //qua han thanh toan
                        $return_arr['response_code'] = '01';
                        $logMsg[]                    = array("time out", 'redis cache_data:', 'E', time());
                    }
                } else {
                    $return_arr['response_code'] = '01';
                    $logMsg[]                    = array('secure not match', 'checksum secure:' . __LINE__, 'E', time());
                }
            } else { //khong ton tai don hang
                $return_arr['response_code'] = '01';
                $logMsg[]                    = array('Invalid', 'request params:', 'E', time());
            }
        } else {
            $return_arr['response_code'] = '01';
            $logMsg[]                    = array('Method not allowed', 'check method isPostRequest:' . __LINE__, 'E', time());
        }

        $return_data = CJSON::encode($return_arr);
        $logMsg[]    = array($return_data, 'Response data:', 'T', time());
        $logMsg[]    = array('', 'Finish process', 'F', time());
        $logFolder   = "web/Log_confirm_payment_api_checkout_response/" . date("Y/m");
        $logObj      = SystemLog::getInstance($logFolder);
        $server_add  = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);

        // return result
        header('Content-Type:application/json');
        switch ($return_arr['response_code']) {
            case '01':
                header('HTTP/1.0 400 Bad Request');
                break;
            case '99':
                header('HTTP/1.0 500 Server Busy');
                break;
            default:
                header('HTTP/1.0 200 Success');
        }
        echo $return_data;
    }

    /**
     * @param $array_param
     *
     * @return string
     */
    private function hashAllFields($array_param)
    {
        $dataCover = implode('', array_values($array_param));

        return md5($dataCover . Yii::app()->params->apisgw_secret_key);
    }

    public function actionTest($token = NULL)
    {
        $params         = $_GET;
        $response_code  = isset($params['response_code']) ? $params['response_code'] : '';
        $transaction_id = isset($params['transaction_id']) ? $params['transaction_id'] : '';
        $order_id       = isset($params['order_id']) ? $params['order_id'] : '';

        $arr_params = array(
            'response_code'  => $response_code,
            'transaction_id' => $transaction_id,
            'order_id '      => $order_id,
        );

        CVarDumper::dump($arr_params, 10, TRUE);
        CVarDumper::dump($this->hashAllFields($arr_params), 10, TRUE);
        die;
        /*$modelTokenLink = WTokenLinks::model()->find('id=:id AND status=:status', array(':id' => $token, ':status' => WTokenLinks::STATUS_SUCCESS));
            if ($modelTokenLink) {
                //get cache
                $key          = 'ktv_add_cart_' . $token;
                $orders_data  = Yii::app()->redis_ktv->get($key);
                $orders       = $orders_data->orders;
                $sim          = $orders_data->sim;
                $session_cart = $orders_data->session_cart;
                if ($orders && $sim) {
                    $modelOrder             = new WOrders();
                    $modelOrder->attributes = $orders->attributes;
                    $modelSim               = new WSim();
                    $modelSim->attributes   = $sim->attributes;

                    //set session
                    $orders_data->sim                   = $modelSim;
                    $orders_data->orders                = $modelOrder;
                    Yii::app()->session['orders_data']  = $orders_data;
                    Yii::app()->session['session_cart'] = $session_cart;
                    //delete redis
//                    Yii::app()->redis_ktv->delete($key);
                    CVarDumper::dump($orders_data, 10, TRUE);
                    die;
                } else {
                    echo 'order||sim not found';
                }
            } else {
                echo 'token link not found';
            }*/
    }
}
