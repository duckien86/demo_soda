<?php

class WPaymentMethod extends PaymentMethod
{
    const PAYMENT_METHOD_ACTIVE   = 1;
    const PAYMENT_METHOD_INACTIVE = 0;

    const PM_QR_CODE    = 1;
    const PM_NAPAS_ATM  = 2;
    const PM_NAPAS_INT  = 3;
    const PM_COD        = 4;
    const PM_AIRTIME    = 5;
    const PM_VIETINBANK = 6; //external
    const PM_VNPAY      = 7; //(khong su dung)
    const PM_OLPAY      = 8; //vietinbank internal(khong su dung)
    const PM_VIETIN_ATM = 9; //vietinbank internal.
    const PM_VNPT_PAY   = 10; //VNPT PAY.
    const PM_TIKI       = 11; //Tiki

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return WPaymentMethod the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public static function getAllPaymentMethod()
    {
        return array(
            self::PM_QR_CODE    => Yii::t('web/portal', 'pm_qr_code'),
            self::PM_NAPAS_ATM  => Yii::t('web/portal', 'pm_napas_atm'),
            self::PM_NAPAS_INT  => Yii::t('web/portal', 'pm_napas_int'),
            self::PM_COD        => Yii::t('web/portal', 'pm_cod'),
            self::PM_AIRTIME    => Yii::t('web/portal', 'pm_airtime'),
            self::PM_VIETINBANK => Yii::t('web/portal', 'pm_vietinbank'),
            self::PM_VNPAY      => Yii::t('web/portal', 'pm_vnpay'),
            self::PM_OLPAY      => Yii::t('web/portal', 'pm_vietinbank_atm'),
            self::PM_VIETIN_ATM => Yii::t('web/portal', 'pm_vietinbank_atm'),
            self::PM_VNPT_PAY   => Yii::t('web/portal', 'pm_vnpt_pay'),
        );
    }

    /**
     * @param $pm_method
     *
     * @return mixed
     */
    public static function getPaymentMethodLabel($pm_method)
    {
        $array = self::getAllPaymentMethod();

        return $array[$pm_method];
    }

    public static function paymentViaNapas($orders_data, $vpc_Amount, WLocationNapas $location_napas = NULL)
    {
        if ($GLOBALS['config_common']['order']['amount_napas'] > 0) {
            $vpc_Amount = $GLOBALS['config_common']['order']['amount_napas'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $vpc_Amount -= $GLOBALS['config_common']['order']['price_ship'];
        }

        $vpc_OrderInfo = $orders_data->orders->id; //order_id
        $province_code = $orders_data->orders->province_code; //province_code
        $province      = WProvince::model()->findByAttributes(array('code' => $province_code));
        if ($province) {
            $requestId = $province->vnp_province_id . '_' . time() . '.';
        } else {
            $requestId = $vpc_OrderInfo . '_' . time() . '.';
        }

        $napas = new Napas();

        if ($location_napas) {
            $requestId .= $location_napas->bank_account;
            if (!empty($location_napas->vpc_AccessCode)) {
                $napas->vpc_AccessCode = $location_napas->vpc_AccessCode;
            }
            if (!empty($location_napas->vpc_Merchant)) {
                $napas->vpc_Merchant = $location_napas->vpc_Merchant;
            }
            if (!empty($location_napas->secure_secret)) {
                $napas->secureSecret = $location_napas->secure_secret;
            }
            if (!empty($location_napas->end_point)) {
                $napas->end_point = $location_napas->end_point;
            }
        }

        if (isset($orders_data->orders->payment_method) && $orders_data->orders->payment_method == WPaymentMethod::PM_NAPAS_ATM) {
            $vpc_PaymentGateway = 'ATM';
        } else {
            $vpc_PaymentGateway = 'INT';
        }

        $napas->vpc_MerchTxnRef    = $requestId; //repeat the transaction with another credit card
        $napas->vpc_OrderInfo      = $vpc_OrderInfo;
        $napas->vpc_Command        = 'pay';
        $napas->vpc_Amount         = $vpc_Amount * 100;
        $napas->vpc_PaymentGateway = $vpc_PaymentGateway; //INT || ATM
        $napas->vpc_CardType       = ''; //SML
        $napas->req_ary_param      = array(
            'vpc_Amount'         => $napas->vpc_Amount,
            'vpc_Version'        => $napas->vpc_Version,
            'vpc_OrderInfo'      => $napas->vpc_OrderInfo,
            'vpc_Command'        => $napas->vpc_Command,
            'vpc_Currency'       => $napas->vpc_CurrencyCode,
            'vpc_Merchant'       => $napas->vpc_Merchant,
            'vpc_BackURL'        => $napas->vpc_BackURL,
            'vpc_ReturnURL'      => $napas->vpc_ReturnURL,
            'vpc_AccessCode'     => $napas->vpc_AccessCode,
            'vpc_MerchTxnRef'    => $napas->vpc_MerchTxnRef,
            'vpc_TicketNo'       => $napas->vpc_TicketNo, //ip
            'vpc_PaymentGateway' => $napas->vpc_PaymentGateway,
            'vpc_CardType'       => $napas->vpc_CardType,
            'vpc_Locale'         => $napas->vpc_Locale,
        );

        //set cache: redis_napas
        $key       = 'napas_data_' . $vpc_OrderInfo;
        $arr_cache = array(
            'requestId'   => $requestId,
            'orders_data' => $orders_data,
            'status'      => FALSE,
        );
        Yii::app()->redis_napas->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['napas']); //30'

        return $napas->createRequestUrl($orders_data->orders);
    }

    public static function paymentViaVietinbank($orders_data, $amount, WLocationVietinbank $location_vietinbank = NULL)
    {
        //            date_default_timezone_set('Asia\Saigon');
        if ($GLOBALS['config_common']['order']['amount_vtb'] > 0) {
            $amount = $GLOBALS['config_common']['order']['amount_vtb'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $amount -= $GLOBALS['config_common']['order']['price_ship'];
        }

        $order_id   = $orders_data->orders->id; //order_id
        $vietinbank = new Vietinbank();

        if ($location_vietinbank) {
            if (!empty($location_vietinbank->access_key)) {
                $vietinbank->access_key = $location_vietinbank->access_key;
            }
            if (!empty($location_vietinbank->profile_id)) {
                $vietinbank->profile_id = $location_vietinbank->profile_id;
            }
            if (!empty($location_vietinbank->secret_key)) {
                $vietinbank->secretKey = $location_vietinbank->secret_key;
            }
            if (!empty($location_vietinbank->end_point)) {
                $vietinbank->end_point = $location_vietinbank->end_point;
            }
        }

        $vietinbank->unsigned_field_names = '';
        $vietinbank->amount               = $amount;
        $vietinbank->reference_number     = $order_id . '_' . rand(10000, 99999) . '_' . $location_vietinbank->qr_code_merchant_id;
        $vietinbank->signed_date_time     = gmdate("Y-m-d\TH:i:s\Z");
        $vietinbank->transaction_uuid     = uniqid();
        $vietinbank->req_ary_param        = array(
            'access_key'           => $vietinbank->access_key,
            'profile_id'           => $vietinbank->profile_id,
            'transaction_uuid'     => $vietinbank->transaction_uuid,
            'auth_trans_ref_no'    => $vietinbank->reference_number,
            'signed_field_names'   => $vietinbank->signed_field_names,
            'unsigned_field_names' => $vietinbank->unsigned_field_names,
            'signed_date_time'     => $vietinbank->signed_date_time,
            'locale'               => $vietinbank->locale,
            'transaction_type'     => $vietinbank->transaction_type,
            'reference_number'     => $vietinbank->reference_number,
            'amount'               => $vietinbank->amount,
            'currency'             => $vietinbank->currency,
        );
        $params_comma                     = $vietinbank->req_ary_param;
        $vietinbank->signed_field_names   = $vietinbank->commaSeparate(array_keys($params_comma));
        //add value $vietinbank->signed_field_names
        $vietinbank->req_ary_param['signed_field_names'] = $vietinbank->signed_field_names;
        //set cache: redis_vtb
        $key       = 'vietinbank_data_' . $vietinbank->reference_number;
        $arr_cache = array(
            'requestId'   => $vietinbank->reference_number,
            'orders_data' => $orders_data,
            'status'      => FALSE,
        );
        Yii::app()->redis_vtb->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['vietinbank']); //30'
        $vietinbank->createRequestUrl($orders_data->orders);

        return $vietinbank;
    }

    public static function paymentViaVietinbankVnpay($orders_data, $amount, WLocationVietinbank $location_vietinbank = NULL)
    {
        if ($GLOBALS['config_common']['order']['amount_vtb'] > 0) {
            $amount = $GLOBALS['config_common']['order']['amount_vtb'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $amount -= $GLOBALS['config_common']['order']['price_ship'];
        }

        $order_id = $orders_data->orders->id; //order_id

        $requestId = $order_id . '_' . time() . rand(1000, 9999);

        $vietinbank = new Vietinbank();
        if ($location_vietinbank) {
            if (!empty($location_vietinbank->vnp_TmnCode)) {
                $vietinbank->vnp_TmnCode = $location_vietinbank->vnp_TmnCode;
            }
            if (!empty($location_vietinbank->vnp_hashSecret)) {
                $vietinbank->vnp_hashSecret = $location_vietinbank->vnp_hashSecret;
            }
            if (!empty($location_vietinbank->vnp_end_point)) {
                $vietinbank->vnp_end_point = $location_vietinbank->vnp_end_point;
            }
        }

        $vietinbank->amount         = $amount;
        $vietinbank->vnp_Amount     = $amount * 100;
        $vietinbank->vnp_CreateDate = date('YmdHis');
        $vietinbank->vnp_TxnRef     = $requestId;
        $vietinbank->vnp_OrderInfo  = $order_id;
        $vietinbank->vnp_OrderType  = Vietinbank::BILLPAYMENT;

        $vietinbank->vnp_req_ary_param = array(
            'vnp_Amount'     => $vietinbank->vnp_Amount,
            'vnp_Command'    => $vietinbank->vnp_Command,
            'vnp_CreateDate' => $vietinbank->vnp_CreateDate,
            'vnp_CurrCode'   => $vietinbank->vnp_CurrCode,
            'vnp_IpAddr'     => $vietinbank->vnp_IpAddr,
            'vnp_Locale'     => $vietinbank->vnp_Locale,
            'vnp_OrderInfo'  => $vietinbank->vnp_OrderInfo,
            'vnp_OrderType'  => $vietinbank->vnp_OrderType,
            'vnp_ReturnUrl'  => $vietinbank->vnp_ReturnUrl,
            'vnp_TmnCode'    => $vietinbank->vnp_TmnCode, //Tham so nay lay tu VNPAY
            'vnp_TxnRef'     => $vietinbank->vnp_TxnRef,
            'vnp_Version'    => $vietinbank->vnp_Version,
        );

        //set cache: redis_vtb
        $key       = 'vnpay_data_' . $requestId;
        $arr_cache = array(
            'requestId'   => $requestId,
            'orders_data' => $orders_data,
            'status'      => FALSE,
        );
        Yii::app()->redis_vtb->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['vnpay']); //30'

        return $vietinbank->createRequestUrlVnpay($orders_data->orders);
    }

    public function paymentViaQRCode($orders_data, $amount, WLocationVietinbank $location_vietinbank = NULL)
    {
        if ($GLOBALS['config_common']['order']['amount_qrcode'] > 0) {
            $amount = $GLOBALS['config_common']['order']['amount_qrcode'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $amount -= $GLOBALS['config_common']['order']['price_ship'];
        }

        $orders     = $orders_data->orders;
        $merchantId = ''; //VNP001

        if ($location_vietinbank) {
            $merchantId = $location_vietinbank->qr_code_merchant_id;
        }

        //get QR code
        $merchantName    = 'VinaPhone eShop';
        $terminalId      = '0001';
        $productId       = 'SIM';
        $transactionDate = date('YmdHis');

        $qr_code = Vietinbank::getQRCodeData($orders, $merchantId, $merchantName, $terminalId, $productId, $amount, $transactionDate, $request_id);

        if ($qr_code) {
            $arr_cache = array(
                'requestId'   => $request_id,
                'orders_data' => $orders_data,
                'status'      => FALSE,
            );
            //key cache: redis_vtb_qr
            $key = 'qr_data_' . $orders->id;
            Yii::app()->redis_vtb_qr->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['qr_code']); //30'
            Yii::app()->session['current_qr_code'] = $qr_code;

            $urlRequest = Yii::app()->controller->createUrl('checkout/qrCode');
        } else {
            Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'error_qrcode'));
            $urlRequest = Yii::app()->controller->createUrl('checkout/checkout2');
        }

        return array(
            'urlRequest' => $urlRequest,
            'msg'        => '',
        );
    }

    public static function paymentViaVietinAtm($orders_data, $amount, WLocationVietinbank $location_vietinbank = NULL)
    {
        if ($GLOBALS['config_common']['order']['amount_vtb'] > 0) {
            $amount = $GLOBALS['config_common']['order']['amount_vtb'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $amount -= $GLOBALS['config_common']['order']['price_ship'];
        }

        $order_id = $orders_data->orders->id; //order_id

        //            $requestId  = $order_id . '_' . time() . rand(1000, 9999);
        $requestId  = time() . rand(1, 9);
        $vietinbank = new Vietinbank();

        if ($location_vietinbank) {
            if (!empty($location_vietinbank->pServiceCode)) {
                $vietinbank->serviceCode = $location_vietinbank->pServiceCode;
            }
            if (!empty($location_vietinbank->pProviderId)) {
                $vietinbank->providerId = $location_vietinbank->pProviderId;
            }
            if (!empty($location_vietinbank->pMerchantId)) {
                $vietinbank->merchantId = $location_vietinbank->pMerchantId;
            }
            if (!empty($location_vietinbank->pEnd_point)) {
                $vietinbank->pEnd_point = $location_vietinbank->pEnd_point;
            }
        }

        $vietinbank->requestId      = $requestId;
        $vietinbank->amount         = $amount;
        $vietinbank->providerCustId = $orders_data->orders->phone_contact;
        $vietinbank->goodsType      = '';
        $vietinbank->billNo         = $order_id;
        $vietinbank->remark         = $order_id;
        $vietinbank->transTime      = date('YmdHis');
        $vietinbank->addInfo        = '';
        $vietinbank->mac            = '';
        $vietinbank->preseve1       = '';
        $vietinbank->preseve2       = '';
        $vietinbank->preseve3       = '';

        $vietinbank->req_ary_param = array(
            'requestId'      => $vietinbank->requestId,
            'serviceCode'    => $vietinbank->serviceCode,
            'providerId'     => $vietinbank->providerId,
            'merchantId'     => $vietinbank->merchantId,
            'amount'         => $vietinbank->amount,
            'currencyCode'   => $vietinbank->currencyCode,
            'providerCustId' => $vietinbank->providerCustId,
            'payMethod'      => $vietinbank->payMethod,
            'goodsType'      => $vietinbank->goodsType,
            'billNo'         => $vietinbank->billNo,
            'remark'         => $vietinbank->remark,
            'transTime'      => $vietinbank->transTime,
            'clientIP'       => $vietinbank->clientIP,
            'channel'        => $vietinbank->channel,
            'version'        => $vietinbank->version,
            'language'       => $vietinbank->language,
            'addInfo'        => $vietinbank->addInfo,
            'mac'            => $vietinbank->mac,
            'preseve1'       => $vietinbank->preseve1,
            'preseve2'       => $vietinbank->preseve2,
            'preseve3'       => $vietinbank->preseve3,
        );

        //set cache: redis_vtb
        $key       = 'vietin_atm_data_' . $requestId;
        $arr_cache = array(
            'requestId'   => $requestId,
            'orders_data' => $orders_data,
            'status'      => FALSE,
        );
        Yii::app()->redis_vtb->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['vietin_atm']); //30'

        return $vietinbank->createRequestUrlVietinAtm($orders_data->orders);
    }

    public static function paymentViaVnptPay($orders_data, $amount, WLocationVnptpay $location_vnptpay = NULL)
    {
        if ($GLOBALS['config_common']['order']['amount_vnpt_pay'] > 0) {
            $amount = $GLOBALS['config_common']['order']['amount_vnpt_pay'];
        }

        //price ship
        $delivery_type = isset($orders_data->orders->delivery_type) ? $orders_data->orders->delivery_type : ''; //delivery_type
        if ($delivery_type == WOrders::DELIVERY_TYPE_HOME) {
            $amount -= $GLOBALS['config_common']['order']['price_ship'];
        }
        // CVarDumper::dump($orders_data, 10, true);
        $orders_data =  Utils::aryToObj($orders_data);
        // CVarDumper::dump($orders_data->orders->id, 10, true);
        // die;
        $vnpt_pay      = new VnptPay();
        $order_id      = $orders_data->orders->id; //order_id
        $province_code = $orders_data->orders->province_code; //province_code
        $province      = WProvince::model()->findByAttributes(array('code' => $province_code));
        if ($province) {
            $vnpt_pay->DESCRIPTION = 'Thanh toan don hang ' . $province->vnp_province_id . ' ' . $order_id;
        } else {
            $vnpt_pay->DESCRIPTION = 'Thanh toan don hang ' . $order_id;
        }

        //            $requestId = $order_id . '_' . time() . rand(1000, 9999);
        //            $requestId = time() . rand(1, 9);
        $requestId = $order_id;

        if ($location_vnptpay) {
            if (!empty($location_vnptpay->merchant_service_id)) {
                $vnpt_pay->MERCHANT_SERVICE_ID = $location_vnptpay->merchant_service_id;
            }
            if (!empty($location_vnptpay->agency_id)) {
                $vnpt_pay->AGENCY_ID = $location_vnptpay->agency_id;
            }
            if (!empty($location_vnptpay->service_id)) {
                $vnpt_pay->SERVICE_ID = $location_vnptpay->service_id;
            }
            if (!empty($location_vnptpay->end_point)) {
                $vnpt_pay->end_point = $location_vnptpay->end_point;
            }
        }

        $vnpt_pay->ACTION            = VnptPay::ACTION_INIT;
        $vnpt_pay->MERCHANT_ORDER_ID = $order_id;
        $vnpt_pay->AMOUNT            = $amount;
        $vnpt_pay->PAYMENT_METHOD    = VnptPay::PAYMENT_METHOD;
        $vnpt_pay->CREATE_DATE       = date('YmdHis');

        $vnpt_pay->req_ary_param = array(
            'ACTION'              => $vnpt_pay->ACTION,
            'VERSION'             => $vnpt_pay->VERSION,
            'MERCHANT_SERVICE_ID' => $vnpt_pay->MERCHANT_SERVICE_ID,
            'AGENCY_ID'           => $vnpt_pay->AGENCY_ID,
            'MERCHANT_ORDER_ID'   => $vnpt_pay->MERCHANT_ORDER_ID,
            'SERVICE_ID'          => $vnpt_pay->SERVICE_ID,
            'AMOUNT'              => $vnpt_pay->AMOUNT,
            'DEVICE'              => $vnpt_pay->DEVICE,
            'LOCALE'              => $vnpt_pay->LOCALE,
            'CURRENCY_CODE'       => $vnpt_pay->CURRENCY_CODE,
            'PAYMENT_METHOD'      => $vnpt_pay->PAYMENT_METHOD,
            'DESCRIPTION'         => $vnpt_pay->DESCRIPTION,
            'CREATE_DATE'         => $vnpt_pay->CREATE_DATE,
            'CLIENT_IP'           => $vnpt_pay->CLIENT_IP,
        );

        //set cache: redis_vnpt_pay
        $key       = 'vnpt_pay_data_' . $requestId;
        $arr_cache = array(
            'requestId'   => $requestId,
            'orders_data' => $orders_data,
            'status'      => FALSE,
        );
        Yii::app()->redis_vnpt_pay->set($key, $arr_cache, Yii::app()->params->cache_timeout_config['vnpt_pay']); //30'

        return $vnpt_pay->createRequestUrl($orders_data->orders);
    }

    /**
     * @param            $operation
     * @param            $orders
     * @param bool|FALSE $error_code
     * @param int        $amount
     *
     * @return string
     */
    public static function getMessageByOperation($operation, $orders, $error_code = FALSE, $amount = 0)
    {
        switch ($operation) {
            case OrdersData::OPERATION_BUYSIM:
                if ($error_code) {
                    $msg = Yii::t('web/portal', 'update_order_success', array('{order_id}' => $orders->id));
                } else {
                    $msg = Yii::t('web/portal', 'update_order_fail');
                }
                break;
            case OrdersData::OPERATION_BUYCARD:
                if ($error_code) {
                    $msg = Yii::t('web/portal', 'buy_card_success', array('{msisdn}' => $orders->phone_contact));
                } else {
                    $msg = Yii::t('web/portal', 'buy_card_fail');
                }
                break;
            case OrdersData::OPERATION_TOPUP:
                if ($error_code) {
                    $msg = Yii::t('web/portal', 'topup_success', array(
                        '{msisdn}' => $orders->phone_contact,
                        '{amount}' => $amount,
                    ));
                } else {
                    $msg = Yii::t('web/portal', 'topup_fail');
                }
                break;
            default:
                $msg = Yii::t('web/portal', 'error_payment');
        }

        return $msg;
    }

    /**
     * check payment method accept
     *
     * @param                          $payment_method
     * @param WLocationVietinbank|NULL $location_vietinbank
     * @param WLocationNapas|NULL      $location_napas
     * @param WLocationVnptpay|NULL    $location_vnptpay
     *
     * @return bool
     */
    public function checkPaymentMethodAccept($payment_method, WLocationVietinbank $location_vietinbank = NULL, WLocationNapas $location_napas = NULL, WLocationVnptpay $location_vnptpay = NULL)
    {
        if ($payment_method) {
            switch ($payment_method) {
                case WPaymentMethod::PM_QR_CODE:
                    if ($location_vietinbank && $location_vietinbank->qr_code_merchant_id) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_NAPAS_ATM:
                case WPaymentMethod::PM_NAPAS_INT:
                    if ($location_napas && $location_napas->bank_account) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_VIETINBANK: //the quoc te
                    if ($location_vietinbank && $location_vietinbank->access_key && $location_vietinbank->profile_id && $location_vietinbank->secret_key) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_VNPAY:
                    if ($location_vietinbank && $location_vietinbank->vnp_TmnCode && $location_vietinbank->vnp_hashSecret) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_OLPAY:
                    if ($location_vietinbank && $location_vietinbank->olpay_merchantId && $location_vietinbank->olpay_providerId) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_VIETIN_ATM:
                    if ($location_vietinbank && $location_vietinbank->pMerchantId && $location_vietinbank->pProviderId && $location_vietinbank->pServiceCode) {
                        return TRUE;
                    }
                    break;
                case WPaymentMethod::PM_VNPT_PAY:
                    if ($location_vnptpay && $location_vnptpay->agency_id) {
                        return TRUE;
                    }
                    break;
                default: //cod
                    return TRUE;
                    break;
            }
        }

        return FALSE;
    }
}
