<?php

    /**
     * Tao ket noi thanh toan client voi Napas
     */
    class Napas
    {
        public $end_point;
        public $end_point_query_dr = 'https://payment.napas.com.vn/gateway/vpcdps';
//        public $end_point_query_dr = 'https://sandbox.napas.com.vn/gateway/vpcdps.api';//test
        public $vpc_Version    = '2.0';
        public $vpc_Command    = 'pay';
        public $vpc_AccessCode = 'V1IN3APHO5N7EON8LI0NE';
//        public $vpc_AccessCode = 'ECAFAB';//test
        public $vpc_MerchTxnRef;
        public $vpc_Merchant = 'VINAPHONEONLINE';
//        public $vpc_Merchant     = 'SMLTEST';//test
        public $vpc_OrderInfo;
        public $vpc_Amount;
        public $vpc_ReturnURL;
        public $vpc_BackURL;
        public $vpc_Locale       = 'vn';
        public $vpc_CurrencyCode = 'VND';
        public $vpc_TicketNo;
        public $vpc_PaymentGateway;
        public $vpc_CardType;
        public $vpc_SecureHash;
        public $vpc_User         = 'querydr';
        public $vpc_Password     = 'ecom123';
//        public $vpc_User          = 'usertest';//test
//        public $vpc_Password      = 'passtest';//test
        public $vpc_TransactionNo;
        public $request_template  = array(
            'vpc_Version'        => '',
            'vpc_Command'        => '',
            'vpc_AccessCode'     => '',
            'vpc_MerchTxnRef'    => '',
            'vpc_Merchant'       => '',
            'vpc_OrderInfo'      => '',
            'vpc_Amount'         => '',
            'vpc_ReturnURL'      => '',
            'vpc_BackURL'        => '',
            'vpc_Locale'         => '',
            'vpc_CurrencyCode'   => '',
            'vpc_TicketNo'       => '',
            'vpc_PaymentGateway' => '',
            'vpc_CardType'       => '',
            'vpc_SecureHash'     => '',
        );
        public $response_template = array(
            'vpc_Version'         => '',
            'vpc_Locale'          => '',
            'vpc_Command'         => '',
            'vpc_Merchant'        => '',
            'vpc_MerchTxnRef'     => '',
            'vpc_Amount'          => '',
            'vpc_CurrencyCode'    => '',
            'vpc_CardType'        => '',
            'vpc_OrderInfo'       => '',
            'vpc_ResponseCode'    => '',
            'vpc_TransactionNo'   => '',
            'vpc_BatchNo'         => '',
            'vpc_AcqResponseCode' => '',
            'vpc_Message'         => '',
            'vpc_AdditionalData'  => '',
            'vpc_SecureHash'      => '',
        );
        public $response_param;
        public $req_ary_param;
        public $secureSecret      = '198BE3F2E8C75A53F38C1C4A5B6DBA27';

        public function __construct()
        {
            $this->end_point     = 'https://sandbox.napas.com.vn/gateway/vpcpay.do?';
            $this->vpc_ReturnURL = Yii::app()->controller->createAbsoluteUrl('checkout/response');
            $this->vpc_BackURL   = Yii::app()->controller->createAbsoluteUrl('checkout/checkout');
            $this->vpc_TicketNo  = $_SERVER['REMOTE_ADDR'];
        }

        /**
         * @param $orders
         *
         * @return array
         */
        public function createRequestUrl($orders)
        {
            $this->vpc_SecureHash = $this->hashAllFields($this->req_ary_param);
            $this->req_ary_param  = array(
                'vpc_Amount'         => $this->vpc_Amount,
                'vpc_Version'        => $this->vpc_Version,
                'vpc_OrderInfo'      => $this->vpc_OrderInfo,
                'vpc_Command'        => $this->vpc_Command,
                'vpc_CurrencyCode'   => $this->vpc_CurrencyCode,
                'vpc_Merchant'       => $this->vpc_Merchant,
                'vpc_BackURL'        => $this->vpc_BackURL,
                'vpc_ReturnURL'      => $this->vpc_ReturnURL,
                'vpc_SecureHash'     => $this->vpc_SecureHash,//hashAllFields()
                'vpc_AccessCode'     => $this->vpc_AccessCode,
                'vpc_MerchTxnRef'    => $this->vpc_MerchTxnRef,
                'vpc_TicketNo'       => $this->vpc_TicketNo,
                'vpc_PaymentGateway' => $this->vpc_PaymentGateway,
                'vpc_CardType'       => $this->vpc_CardType,
                'vpc_Locale'         => $this->vpc_Locale,
            );
            $urlRequest           = CFunction::implodeParams($this->req_ary_param);
            $query_string         = $urlRequest;
            $urlRequest           = str_replace('?', '', $this->end_point) . '?' . $urlRequest;

            //log
            $logMsg[]   = array('Start Request Napas Log', 'Start proccess:', 'I', time());
            $logMsg[]   = array($urlRequest, 'Request URL', 'T', time());
            $logMsg[]   = array('', 'Finish proccess', 'F', time());
            $logFolder  = "web/Log_napas_request/" . date("Y/m");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');
            $logObj->processWriteLogs($logMsg);
            //end log

            //insert log DB
            WTransactionRequest::writeLog(WTransactionRequest::NAPAS, $orders, $this->vpc_MerchTxnRef, $this->end_point, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM);
            if (empty($urlRequest)) {
                Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'select_another_payment'));
                $urlRequest = Yii::app()->controller->createUrl('checkout/checkout2');
            }
            return array(
                'urlRequest' => $urlRequest,
                'msg'        => '',
            );
        }

        public function hashAllFields($array_param)
        {
            ksort($array_param);
            $dataCover = implode('', array_values($array_param));

            return substr(strtoupper(md5($this->secureSecret . $dataCover)), 0, 32);
        }

        /**
         * @param $orders
         * @param $response
         * @param $arr_param
         * @param $vpc_SecureHash
         *
         * @return bool
         */
        public static function checkVpcSecureHashResponse(WOrders $orders, $response, &$arr_param = '', &$vpc_SecureHash = '')
        {
            $flag           = FALSE;
            $province_code  = $orders->province_code;//province_code
            $napas          = new Napas();
            $location_napas = WLocationNapas::model()->find('id=:id', array(':id' => $province_code));
            if ($location_napas) {
                if (!empty($location_napas->secure_secret)) {
                    $napas->secureSecret = $location_napas->secure_secret;
                }
            }
            $arr_param = $response;
            unset($arr_param['vpc_SecureHash']);

            $napas->vpc_SecureHash = $napas->hashAllFields($arr_param);
            $vpc_SecureHash        = $napas->vpc_SecureHash;
            //compare vpc_SecureHash: response
            if (isset($response['vpc_SecureHash']) && $response['vpc_SecureHash'] == $napas->vpc_SecureHash) {
                $flag = TRUE;
            }

            return $flag;
        }

        /**
         * @return array
         */
        public static function arrayErrorCode()
        {
            return array(
                '0'  => 'Transaction success ',
                '1'  => 'Bank system reject (card closed, account closed) ',
                '3'  => 'Card expire ',
                '4'  => 'Limit exceeded (Wrong OTP, amount / time per day) ',
                '5'  => 'No reply from Bank ',
                '6'  => 'Bank Communication failure ',
                '7'  => 'Insufficient fund ',
                '8'  => 'Invalid checksum ',
                '9'  => 'Transaction type not support ',
                '10' => 'Other error ',
                '11' => 'Verify card is successful ',
                '12' => 'Your payment is unsuccessful. Transaction exceeds amount limit. ',
                '13' => 'You have been not registered online payment services. Please contact your bank. ',
                '14' => 'Invalid OTP (One time password) ',
                '15' => 'Invalid static password ',
                '16' => 'Incorrect Cardholder\'s name ',
                '17' => 'Incorrect card number ',
                '18' => 'Date of validity is incorrect (issue date) ',
                '19' => 'Date of validity is incorrect (expiration date) ',
                '20' => 'Unsuccessful transaction ',
                '21' => 'OTP (One time password) time out ',
                '22' => 'Unsuccessful transaction ',
                '23' => 'Your payment is not approved. Your card/account is ineligible for payment ',
                '24' => 'Your payment is unsuccessful. Transaction exceeds amount limit ',
                '25' => 'Transaction exceeds amount limit. ',
                '26' => 'Transactions awaiting confirmation from the bank ',
                '27' => 'You have entered wrong authentication information ',
                '28' => 'Your payment is unsuccessful. Transaction exceeds time limit ',
                '29' => 'Transaction failed. Please contact your bank for information. ',
                '30' => 'Your payment is unsuccessful. Amount is less than minimum limit. ',
                '31' => 'Orders not found ',
                '32' => 'Orders not to make payments ',
                '33' => 'Duplicate orders ',

                '34' => 'Transaction timeout',
            );
        }

        /**
         * @param $code
         *
         * @return mixed
         */
        public static function getContentError($code)
        {
            $array_error = self::arrayErrorCode();

            return (isset($array_error[$code])) ? $array_error[$code] : $code;
        }

        /**
         * call api queryDr(check status transaction)
         *
         * @param WOrders             $orders
         * @param WTransactionRequest $request
         * @param array               $logMsg
         *
         * @return bool
         */
        public function requestQueryDr(WOrders $orders, WTransactionRequest $request, &$logMsg = array())
        {
            $location_napas = WLocationNapas::model()->find('id=:id', array(':id' => $orders->province_code));
            if ($location_napas) {
                if (!empty($location_napas->vpc_AccessCode)) {
                    $this->vpc_AccessCode = $location_napas->vpc_AccessCode;
                }
                if (!empty($location_napas->vpc_Merchant)) {
                    $this->vpc_Merchant = $location_napas->vpc_Merchant;
                }
                if (!empty($location_napas->secure_secret)) {
                    $this->secureSecret = $location_napas->secure_secret;
                }
            }
            $this->vpc_MerchTxnRef = $request->transaction_id;
            $this->vpc_Version     = '2.1';
            $this->vpc_Command     = 'queryDR';
            $this->req_ary_param   = array(
                'vpc_Version'     => $this->vpc_Version,
                'vpc_Command'     => $this->vpc_Command,
                'vpc_AccessCode'  => $this->vpc_AccessCode,
                'vpc_Merchant'    => $this->vpc_Merchant,
                'vpc_MerchTxnRef' => $this->vpc_MerchTxnRef,
                'vpc_User'        => $this->vpc_User,
                'vpc_Password'    => $this->vpc_Password,
            );

            $logMsg[]                              = array(CJSON::encode($this->req_ary_param), 'arr_param_hashAllFields request:', 'T', time());
            $this->vpc_SecureHash                  = $this->hashAllFields($this->req_ary_param);
            $this->req_ary_param['vpc_SecureHash'] = $this->vpc_SecureHash;
            $urlRequest                            = CFunction::implodeParams($this->req_ary_param);
            $query_string                          = $urlRequest;
            $urlRequest                            = str_replace('?', '', $this->end_point_query_dr) . '?' . $urlRequest;
            //query string
            $response_raw = Utils::cUrlGet($urlRequest, 30, $http_code, TRUE, TRUE);

            //log
            $logMsg[] = array($urlRequest, 'Request queryDr URL(Napas):' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code:' . __LINE__, 'T', time());
            $logMsg[] = array($response_raw, 'Response queryDr:' . __LINE__, 'T', time());
            //end log

            //insert log DB: request queryDr
            WTransactionRequest::writeLog(WTransactionRequest::NAPAS_QUERY_DR, $orders, $this->vpc_MerchTxnRef, $this->end_point_query_dr, $query_string, $response_raw, WTransactionRequest::TYPE_QUERY_PARAM, WTransactionRequest::TYPE_QUERY_PARAM);

            //check Response QueryDr
            parse_str($response_raw, $arr_response);//parse query string

            if (isset($arr_response['vpc_TxnResponseCode']) && isset($arr_response['vpc_SecureHash'])) {
                $check_vpc_SecureHash = $this->checkVpcSecureHashResponse($orders, $arr_response, $arr_param, $vpc_SecureHash);
                $logMsg[]             = array(CJSON::encode($arr_param), 'arr_param_hashAllFields response:' . __LINE__, 'T', time());
                $logMsg[]             = array($check_vpc_SecureHash, 'checkVpcSecureHashResponse():' . __LINE__, 'T', time());

                if ($arr_response['vpc_TxnResponseCode'] == '0' && $check_vpc_SecureHash == TRUE) {
                    return TRUE;
                }
            }

            return FALSE;
        }
    }