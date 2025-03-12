<?php

    class Vietinbank
    {
        // Url connection.
        const URL_TEST = "https://testsecureacceptance.cybersource.com/pay";
        const URL_LIVE = "https://secureacceptance.cybersource.com/pay";

        //external: the quoc te
        //ordertype
        const TOPUP       = 'topup';
        const BILLPAYMENT = 'billpayment';
        const FASHION     = 'fashion';
//        public $end_point        = 'https://testsecureacceptance.cybersource.com/pay';//link test
        public $end_point = 'https://secureacceptance.cybersource.com/pay';
//        public $secretKey        = 'f7aaaa893e39460b9d0363b4db5f0d9b873ff37ae8764f81ae6a5707b737f0cf2b699cc0e32e4927a92ac6a3c2dace686ef75d0fbcf641fdaa6142dfb27ecfc87599cc08d8c94f4e92a537c0a5db498d0d2976476a6d40f5b476d32d2e524b19d8acec9e603f4254836de8a37c76c095c0bf32a7f52e43d8a79560c3d1ac4f54';
        public $secretKey = '4b766d937b334bd5810801df7ddb3b7d8e544b02a3a54628b90cf6293ff09f73201622fe2829407f88c7ba0619cc5af7d4f6fbf5548443c2a6bdc59fea4f5cb01de615088dec4256b4052c59b54154bbd57688b488244affb2ed7fbfba31d3f5f81dbf94946042be89eb584c9f9866dfd598215509b742d484d0525b5cd9df62';
        public $signed_date_time;
//        public $access_key       = '45fe023f074a3a7e9f7b7dcc78446952';
        public $access_key = '4a4c017100233e719b068a23ef0c432c';
//        public $profile_id       = 'F3E2E7FA-07DA-426F-A7FC-9C25B0D1B77B';
        public $profile_id       = '7DF70835-E46F-4D61-8EDB-6EE6FCCAA637';
        public $transaction_uuid;
        public $signed_field_names;
        public $unsigned_field_names;
        public $locale           = 'vi-VN';
        public $amount;
        public $currency         = 'VND';
        public $reference_number;
        public $transaction_type = 'sale';//sale
        public $signature;
        public $bill_to_address_city;
        public $bill_to_address_country;
        public $bill_to_address_line1;
        public $bill_to_address_line2;
        public $bill_to_company_name;
        public $bill_to_email;
        public $bill_to_forename;
        public $bill_to_surname;
        public $bill_to_phone;
        public $ship_to_address_city;
        public $ship_to_address_country;
        public $ship_to_address_line1;
        public $ship_to_address_line2;
        public $ship_to_company_name;
        public $ship_to_forename;
        public $ship_to_phone;
        public $ship_to_surname;
        public $response_param;
        public $request_param;
        //End external(the quoc te)

        //vnpay(khong su dung)
        public $vnp_end_point      = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html?";
        public $vnp_TmnCode        = "2QXUI4J4"; //VNPAY will send to you
        public $vnp_hashSecret     = 'FFHTWVDIICFNOYJGOULCUVOEJTZTECLD';
        public $vnp_Amount;
        public $vnp_Command        = 'pay';
        public $vnp_CreateDate;
        public $vnp_CurrCode       = 'VND';
        public $vnp_IpAddr;
        public $vnp_Locale         = 'vn';//en
        public $vnp_OrderInfo;
        public $vnp_OrderType;
        public $vnp_ReturnUrl;
        public $vnp_TxnRef;
        public $vnp_Version        = '2.0.0';
        public $vnp_BankCode;
        public $vnp_SecureHash;
        public $vnp_SecureHashType = 'MD5';
        //End vnpay

        /*internal(the noi dia)*/
        public $requestId;
        public $serviceCode  = 'VINAPHONE';
        public $providerId   = '050';
        public $merchantId   = '500';
        public $currencyCode = 'VND';//USD
        public $providerCustId;
        public $payMethod    = 'CARD';//QRPAY ||CARD || IPAY
        public $goodsType;
        public $billNo;
        public $remark;
        public $transTime;          //yyyyMMddHHmmss
        public $clientIP;
        public $channel      = 'WEB';      //MOBILE || WEB || POS || DESKTOP
        public $version      = '1.0';
        public $language     = 'vi';//en
        public $addInfo;
        public $mac;
        public $preseve1;
        public $preseve2;
        public $preseve3;
        public $pSignature;//signData = requestId + providerId + merchant + amount + currencyCode + transTime + clientIP + goodsType + billNo + remark + channel + version + language+addInfo + mac + preseve1 + preseve2 + preseve3
        public $pEnd_point   = 'http://192.168.6.156:9008/directpay/init-session/v1';
        //query
        public $queryTransactionId;
        public $queryType = '01';
        /*End internal (the noi dia)*/
        //freedoo: private key: create pSignature
        public $pPrivateKey;
        //vietinbank: public key: verify pSignature
        public $pPublicKey;

        //array request
        public $req_ary_param;
        public $vnp_req_ary_param;


        const ACCEPT  = 'ACCEPT';
        const REJECT  = 'REJECT';
        const DECLINE = 'DECLINE';


        public function __construct()
        {
            $this->vnp_ReturnUrl = Yii::app()->controller->createAbsoluteUrl('checkout/return');
            $this->vnp_IpAddr    = $_SERVER['REMOTE_ADDR'];
            $this->clientIP      = $_SERVER['REMOTE_ADDR'];
            $ds                  = DIRECTORY_SEPARATOR;
            $this->pPrivateKey   = Yii::app()->getBasePath() . $ds . 'web' . $ds . 'key' . $ds . 'fd_private_key.pem';
            $this->pPublicKey    = Yii::app()->getBasePath() . $ds . 'web' . $ds . 'key' . $ds . 'vtb_public_key.pem';
        }

        /**
         * create URL payment via Vietinbank cybersource
         *
         * @param $orders
         *
         */
        public function createRequestUrl($orders)
        {
//            $this->unsigned_field_names = $this->stringFields(array_keys($this->req_ary_param));
            $this->signature = $this->sign($this->req_ary_param);

            $this->req_ary_param = array(
                'access_key'           => $this->access_key,
                'profile_id'           => $this->profile_id,
                'transaction_uuid'     => $this->transaction_uuid,
                'auth_trans_ref_no'    => $this->reference_number,
                'signed_field_names'   => $this->signed_field_names,//commaSeparate
                'unsigned_field_names' => $this->unsigned_field_names,
                'signed_date_time'     => $this->signed_date_time,
                'locale'               => $this->locale,
                'transaction_type'     => $this->transaction_type,
                'reference_number'     => $this->reference_number,
                'amount'               => $this->amount,
                'currency'             => $this->currency,
                'signature'            => $this->signature,//sign all fields
            );

            $query_string = CFunction::implodeParams($this->req_ary_param);
//            $urlRequest   = str_replace('?', '', $this->end_point) . '?' . $query_string;
            $urlRequest = $this->end_point;

            //log
            $logMsg[]   = array('Start Vietinbank Request Log', 'Start process:', 'I', time());
            $logMsg[]   = array($urlRequest, 'Request URL', 'T', time());
            $logMsg[]   = array($query_string, 'array params', 'T', time());
            $logMsg[]   = array('', 'Finish process', 'F', time());
            $logFolder  = "web/Log_vietinbank_request/" . date("Y/m");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');
            $logObj->processWriteLogs($logMsg);
            //end log

            //insert log DB
            WTransactionRequest::writeLog(WTransactionRequest::VIETINBANK, $orders, $this->transaction_uuid, $this->end_point, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM);
        }

        /**
         * create URL payment via VNPay
         *
         * @param $orders
         *
         * @return array
         */
        public function createRequestUrlVnpay($orders)
        {
            $this->vnp_SecureHash = $this->hashAllFields($this->vnp_req_ary_param);

            $this->vnp_req_ary_param = array(
                'vnp_Amount'         => $this->vnp_Amount,
                'vnp_Command'        => $this->vnp_Command,
                'vnp_CreateDate'     => $this->vnp_CreateDate,
                'vnp_CurrCode'       => $this->vnp_CurrCode,
                'vnp_IpAddr'         => $this->vnp_IpAddr,
                'vnp_Locale'         => $this->vnp_Locale,
                'vnp_OrderInfo'      => $this->vnp_OrderInfo,
                'vnp_OrderType'      => $this->vnp_OrderType,
                'vnp_ReturnUrl'      => $this->vnp_ReturnUrl,
                'vnp_TmnCode'        => $this->vnp_TmnCode, //Tham so nay lay tu VNPAY
                'vnp_TxnRef'         => $this->vnp_TxnRef,
                'vnp_Version'        => $this->vnp_Version,
                'vnp_SecureHashType' => $this->vnp_SecureHashType,//hashAllFields()
                'vnp_SecureHash'     => $this->vnp_SecureHash,//hashAllFields()
            );
            $urlRequest              = CFunction::implodeParams($this->vnp_req_ary_param);
            $query_string            = $urlRequest;
            $urlRequest              = str_replace('?', '', $this->vnp_end_point) . '?' . $urlRequest;

            //log
            $logMsg[]   = array('Start VNPAY Request Log', 'Start process:', 'I', time());
            $logMsg[]   = array($urlRequest, 'Request URL', 'T', time());
            $logMsg[]   = array('', 'Finish process', 'F', time());
            $logFolder  = "web/Log_vnpay_request/" . date("Y/m");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');
            $logObj->processWriteLogs($logMsg);
            //end log

            //insert log DB
            WTransactionRequest::writeLog(WTransactionRequest::VIETINBANK, $orders, $this->vnp_TxnRef, $this->vnp_end_point, $query_string, '', WTransactionRequest::TYPE_QUERY_PARAM);

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
            $i         = 0;
            $dataCover = "";
            foreach ($array_param as $key => $value) {
                if ($i == 1) {
                    $dataCover .= '&' . $key . "=" . $value;
                } else {
                    $dataCover .= $key . "=" . $value;
                    $i         = 1;
                }
            }

            return md5($this->vnp_hashSecret . $dataCover);
        }

        /**
         * @param $params
         *
         * @return string
         */
        public function sign($params)
        {
            return $this->signData($this->buildDataToSign($params), $this->secretKey);
        }

        /**
         * @param $data
         * @param $secretKey
         *
         * @return string
         */
        public function signData($data, $secretKey)
        {
            return base64_encode(hash_hmac('sha256', $data, $secretKey, TRUE));
        }

        /**
         * @param $params
         *
         * @return string
         */
        public function buildDataToSign($params)
        {
            $signedFieldNames = explode(",", $params["signed_field_names"]);
            $dataToSign       = '';
            foreach ($signedFieldNames as $field) {
                $dataToSign[] = $field . "=" . $params[$field];
            }

            return $this->commaSeparate($dataToSign);
        }

        /**
         * @param $dataToSign
         *
         * @return string
         */
        public function commaSeparate($dataToSign)
        {
            return implode(",", $dataToSign);
        }

        /**
         * @param $dataToSign
         *
         * @return string
         */
        public function stringFields($dataToSign)
        {
            return implode("", $dataToSign);
        }

        /**
         * @param      $orders
         * @param      $merchantId   | ex : VNP001
         * @param      $merchantName | ex : VinaPhone eShop
         * @param      $terminalId   | ex : 0001
         * @param      $productId    | ex : SIM|...
         * @param      $amount       | ex : 100000
         * @param      $transactionDate
         * @param null $requestId    | default : orderid+microtime()+rand(1000,9999)
         *
         * Example :
         * {"requestId" : "1234","merchantId" : "VNP001","merchantName" : "VinaPhone eShop","terminalId" :
         * "0001","productId" : "SIM","orderId" :
         * "143520","amount" : 200000,"transactionDate": "20170711134009"}
         *
         * @return bool|mixed
         */
        public static function getQRCodeData($orders, $merchantId, $merchantName, $terminalId, $productId, $amount, $transactionDate, &$requestId = NULL)
        {
            $url_qr_code = $GLOBALS['config_common']['vietinbank']['url_qr_code'];
            $orderId     = $orders->id;
            $logFolder   = "web/Log_getQRCodeData/" . date("Y/m");
            $logObj      = SystemLog::getInstance($logFolder);
            $server_add  = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');

            $start_time       = time();
            $start_time_micro = microtime();
            $logMsg           = array();
            $logMsg[]         = array('Start Log From IP:' . $_SERVER['REMOTE_ADDR'], 'Start proccess From:', 'I', $start_time);

            if (empty($requestId)) {
                $requestId = $orderId . str_replace('.', '', microtime(TRUE)) . rand(1000, 9999);
            }
            $request_arr = array(
                "requestId"       => $requestId,
                "merchantId"      => $merchantId,
                "merchantName"    => $merchantName,
                "terminalId"      => $terminalId,
                "productId"       => $productId,
                "orderId"         => $orderId,
                "amount"          => $amount,
                "transactionDate" => $transactionDate,
            );
            $json_data   = CJSON::encode($request_arr);
            $logMsg[]    = array($url_qr_code, 'Request Url:' . __LINE__, 'T');
            $logMsg[]    = array($json_data, 'Request json:' . __LINE__, 'T');
            $response    = '';
            $http_code   = '';
            $index       = 0;
            do {
                $response_raw = Utils::cUrlPostJson($url_qr_code, $json_data, TRUE, 30, $http_code);
                $qr_code_arr  = CJSON::decode($response_raw);
                $response     = (isset($qr_code_arr['qrData']) && !empty($qr_code_arr['qrData'])) ? $qr_code_arr['qrData'] : FALSE;
                $index++;
            } while ((empty($response) || $http_code != 200) && $index < 3);
            $finish_time       = time();
            $finish_time_micro = microtime();
            $logMsg[]          = array("http_code:$http_code |exec(ms):" . ($finish_time_micro - $start_time_micro), 'Status:' . __LINE__, 'T');
            $logMsg[]          = array($response_raw, 'raw data:' . __LINE__, 'T');
            $logMsg[]          = array('', 'Finish process-' . __LINE__, 'F', $finish_time);
            $logObj->processWriteLogs($logMsg);

            //insert log DB
            $note = "http_code: $http_code";
            WTransactionRequest::writeLog(WTransactionRequest::VIETINBANK, $orders, $requestId, $url_qr_code, $json_data, $response_raw, WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, '', $note);

            if ($http_code == 200) {
                return $response;
            }

            return FALSE;
        }

        /**
         * @param $code
         *
         * @return mixed
         */
        public static function getErrorCode($code, $payment_method)
        {
            switch ($payment_method) {
                case WPaymentMethod::PM_VNPAY:
                    $array_error = self::arrayErrorCodeVnpay();
                    break;
                case WPaymentMethod::PM_VIETIN_ATM://the noi dia
                    $array_error = self::arrayErrorCodeVietinAtm();
                    break;
                default: //PM_VIETINBANK=6: the quoc te
                    $array_error = self::arrayErrorCode();
                    break;
            }

            return (isset($array_error[$code])) ? $array_error[$code] : $code;
        }

        /**
         * the quoc te
         *
         * @return array
         */
        public static function arrayErrorCode()
        {
            return array(
                'ACCEPT' => '0',
                'REJECT' => '1',
                'ERROR'  => '20',
            );
        }

        /**
         * vnpay
         *
         * @return array
         */
        public static function arrayErrorCodeVnpay()
        {
            return array(
                '01' => 'Giao dịch đã tồn tại',
                '02' => 'Merchant không hợp lệ (kiểm tra lại vnp_TmnCode)',
                '03' => 'Dữ liệu gửi sang không đúng định dạng',
                '04' => 'Khởi tạo GD không thành công do Website đang bị tạm khóa',
                '08' => 'Giao dịch không thành công do: Hệ thống Ngân hàng đang bảo trì. Xin quý khách tạm thời không thực hiện giao dịch bằng thẻ/tài khoản của Ngân hàng này.',
                '05' => 'Giao dịch không thành công do: Quý khách nhập sai mật khẩu thanh toán quá số lần quy định. Xin quý khách vui lòng thực hiện lại giao dịch',
                '06' => 'Giao dịch không thành công do Quý khách nhập sai mật khẩu xác thực giao dịch (OTP). Xin quý khách vui lòng thực hiện lại giao dịch.',
                '07' => 'Trừ tiền thành công. Giao dịch bị nghi ngờ (liên quan tới lừa đảo, giao dịch bất thường). Đối với giao dịch này cần merchant xác nhận thông qua merchant admin: Từ chối/Đồng ý giao dịch',
                '12' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng bị khóa',
                '09' => 'Giao dịch không thành công do: Thẻ/Tài khoản của khách hàng chưa đăng ký dịch vụ InternetBanking tại ngân hàng.',
                '10' => 'Giao dịch không thành công do: Khách hàng xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
                '11' => 'Giao dịch không thành công do: Đã hết hạn chờ thanh toán. Xin quý khách vui lòng thực hiện lại giao dịch.',
                '24' => 'Giao dịch không thành công do: Khách hàng hủy giao dịch',
                '51' => 'Giao dịch không thành công do: Tài khoản của quý khách không đủ số dư để thực hiện giao dịch.',
                '65' => 'Giao dịch không thành công do: Tài khoản của Quý khách đã vượt quá',
                '75' => 'Ngân hàng thanh toán đang bảo trì',
                '99' => 'Các lỗi khác',
            );
        }

        /**
         * @param        $orders_data
         * @param        $response
         * @param string $arr_param
         * @param string $vnp_SecureHash
         *
         * @return bool
         */
        public static function checkVnpSecureHashReturn($orders_data, $response, &$arr_param = '', &$vnp_SecureHash = '')
        {
            $flag                = FALSE;
            $province_code       = $orders_data->orders->province_code;//province_code
            $vietinbank          = new Vietinbank();
            $location_vietinbank = WLocationVietinbank::model()->find('id=:id', array(':id' => $province_code));
            if ($location_vietinbank) {
                if (!empty($location_vietinbank->vnp_hashSecret)) {
                    $vietinbank->vnp_hashSecret = $location_vietinbank->vnp_hashSecret;
                }
            }
            $arr_param = $response;
            unset($arr_param['vnp_SecureHashType']);
            unset($arr_param['vnp_SecureHash']);

            $vietinbank->vnp_SecureHash = $vietinbank->hashAllFields($arr_param);
            $vnp_SecureHash             = $vietinbank->vnp_SecureHash;
            //compare vnp_SecureHash: response
            if (isset($response['vnp_SecureHash']) && $response['vnp_SecureHash'] == $vietinbank->vnp_SecureHash) {
                $flag = TRUE;
            }

            return $flag;
        }

        /**
         * create URL payment via Vietinbank atm
         *
         * @param $orders
         *
         * @return array
         */
        public function createRequestUrlVietinAtm($orders)
        {
            $this->pSignature = $this->createSignature($this->req_ary_param, $this->pPrivateKey);

            $this->req_ary_param = array(
                'requestId'      => $this->requestId,
                'serviceCode'    => $this->serviceCode,
                'providerId'     => $this->providerId,
                'merchantId'     => $this->merchantId,
                'amount'         => $this->amount,
                'currencyCode'   => $this->currencyCode,
                'providerCustId' => $this->providerCustId,
                'payMethod'      => $this->payMethod,
                'goodsType'      => $this->goodsType,
                'billNo'         => $this->billNo,
                'remark'         => $this->remark,
                'transTime'      => $this->transTime,
                'clientIP'       => $this->clientIP,
                'channel'        => $this->channel,
                'version'        => $this->version,
                'language'       => $this->language,
                'addInfo'        => $this->addInfo,
                'mac'            => $this->mac,
                'preseve1'       => $this->preseve1,
                'preseve2'       => $this->preseve2,
                'preseve3'       => $this->preseve3,
                'signature'      => $this->pSignature,
            );

            $urlRequest = $this->initPaymentSession($orders, $this->req_ary_param, $this->pEnd_point);

            //log
            $logMsg[]   = array('Start Vietinbank(ATM) Internal Request Log', 'Start process:', 'I', time());
            $logMsg[]   = array($urlRequest, 'Request URL', 'T', time());
            $logMsg[]   = array('', 'Finish process', 'F', time());
            $logFolder  = "web/Log_vietin_atm_request/" . date("Y/m");
            $logObj     = SystemLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');
            $logObj->processWriteLogs($logMsg);
            //end log

            if (empty($urlRequest)) {
                Yii::app()->user->setFlash('danger', Yii::t('web/portal', 'select_another_payment'));
                $urlRequest = Yii::app()->controller->createUrl('checkout/checkout2');
            }

            return array(
                'urlRequest' => $urlRequest,
                'msg'        => '',
            );
        }

        /**
         * init payment session Vietinbank
         *
         * @param $orders
         * @param $array_params
         * @param $url
         *
         * @return string
         */
        public function initPaymentSession($orders, $array_params, $url)
        {
            $requestId = isset($array_params['requestId']) ? $array_params['requestId'] : '';
            if (empty($requestId)) {
//                $requestId = $orders->id . '_' . time() . rand(1000, 9999);
                $requestId = time() . rand(1, 9);
            }
            $start_time       = time();
            $start_time_micro = microtime();
            $logMsg           = array();
            $logMsg[]         = array('Start initPaymentSession Log From IP:' . $_SERVER['REMOTE_ADDR'], 'Start process From:', 'I', $start_time);

            $json_data   = CJSON::encode($array_params);
            $logMsg[]    = array($url, 'Request Url:' . __LINE__, 'T');
            $logMsg[]    = array($json_data, 'Request json:' . __LINE__, 'T');
            $redirectUrl = '';
//            $http_code   = '';
//            $index       = 0;
//            do {
            $response_raw = Utils::cUrlPostJson($url, $json_data, TRUE, 30, $http_code);
            $arr_response = CJSON::decode($response_raw);
            if (!empty($arr_response['redirectUrl']) && !empty($arr_response['signature'])
                && !empty($arr_response['responseCode']) && $arr_response['responseCode'] == '00'
                && $this->verifySignature($arr_response, $this->pPublicKey, $arr_response['signature'], $logMsg)
                //check signature
            ) {
                $redirectUrl = $arr_response['redirectUrl'];
            } else {
                $logMsg[] = array('Fail', 'verifySignature:' . __LINE__, 'T');
            }
//                $index++;
//            } while ((empty($redirectUrl) || $http_code != 200) && $index < 3);
            $finish_time       = time();
            $finish_time_micro = microtime();
            $logMsg[]          = array("http_code:$http_code |exec(ms):" . ($finish_time_micro - $start_time_micro), 'Status:' . __LINE__, 'T');
            $logMsg[]          = array($response_raw, 'raw data:' . __LINE__, 'T');
            $logMsg[]          = array('', 'Finish process-' . __LINE__, 'F', $finish_time);
            $logFolder         = "web/Log_vietin_atm_init_payment/" . date("Y/m");
            $logObj            = TraceLog::getInstance($logFolder);
            $server_add        = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . date('d') . '.log');
            $logObj->processWriteLogs($logMsg);

            //insert log DB
            $note = "vietinbank_init_payment_session http_code: $http_code";
            WTransactionRequest::writeLog(WTransactionRequest::VIETINBANK, $orders, $requestId, $url, $json_data, $response_raw, WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, '', $note);

            return $redirectUrl;
        }

        /**
         * ma loi thanh toan vietinbank noi dia
         *
         * @return array
         */
        public static function arrayErrorCodeVietinAtm()
        {
            return array(
                '00' => 'Thanh cong',
                '01' => 'Sai thong tin Merchant ID/ Provider ID',
                '02' => 'The o trang thai khong hop le',
                '03' => 'The chua duoc dang ky dich vu Thanh toan Truc tuyen',
                '04' => 'Khong xac thuc duoc thong tin chu the',
                '05' => 'Tai khoan gan voi The khong du so du de thanh toan',
                '06' => 'Tai khoan gan voi The o trang thai khong thuc hien duoc giao dịch',
                '07' => 'Giao dich bi gui trung lap',
                '08' => 'Ma giao dich bo trong hoac sai quy dinh',
                '20' => 'Vuot han muc thanh toan cho phep can xac thuc OTP',
                '21' => 'So tien vuot qua han muc toi thieu lan giao dich',
                '22' => 'So tien vuot qua han muc toi da lan giao dich',
                '23' => 'So tien vuot qua han muc toi da ngay giao dich',
                '24' => 'Tai khoan dich vu khong ton tai',
                '25' => 'Tai khoan dich vu khong o trang thai Active',
                '26' => 'So tien thanh toan khong hop le sai format',
                '27' => 'Tai khoan dich vu khong du so du giao dich',
                '37' => 'Khong tao duoc giao dich thanh toan',
                '40' => 'Giao dich da tru tien thanh cong phia VTB',
                '41' => 'Giao dich da duoc tru tien nhung chua thanh cong',
                '42' => 'Giao dich ton tai nhung chua thanh cong',
                '43' => 'Giao dich khong ton tai tren he thong VTB',
                '44' => 'Sai tham so van tin dich vu',
                '50' => 'Khong tim thay giao dich yeu cau Hoan tien',
                '51' => 'So tien yeu cau Hoan tien khong hop le',
                '52' => 'Giao dich Hoan tien khong thanh cong',
                '53' => 'Da ton tai giao dich hoan tien trung so refund id',
                '54' => 'Khong tao duoc giao dich hoan tien',
                '55' => 'Cancel giao dich',
                '90' => 'Sai tham so API',
                '91' => 'Sai checksum',
                '92' => 'Khong lay duoc thong tin tham so dich vu',
                '93' => 'Sai chu ky dien tu',
                '94' => 'Kenh bi han che giao dich',
                '95' => 'IP bi han che giao dich',
                '96' => 'Loi luu du lieu vao DB',
                '99' => 'Loi he thong',
            );
        }

        /**
         * @param $array_param
         * @param $src_private_key
         *
         * @return string
         */
        public function createSignature($array_param, $src_private_key)
        {
            $data        = implode('', array_values($array_param));
            $private_key = openssl_pkey_get_private("file://" . $src_private_key);

            openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA1);
            $signature = trim(Utils::safe_b64encode($signature, FALSE));

            return $signature;
        }

        /**
         * @param $array_param
         * @param $src_public_key
         * @param $signature
         * @param $logMsg
         *
         * @return int
         */
        public function verifySignature($array_param, $src_public_key, $signature, &$logMsg = array())
        {
            unset($array_param['signature']);
            $logMsg[] = array(CJSON::encode($array_param), 'verifySignature data:' . __LINE__, 'T', time());

            $data       = implode('', array_values($array_param));
            $public_key = openssl_pkey_get_public("file://" . $src_public_key);

            $verify = openssl_verify($data, Utils::safe_b64decode($signature, FALSE), $public_key, OPENSSL_ALGO_SHA1);

            return $verify;
        }

        /**
         * call api queryDr: check status transaction(the ATM noi dia)
         *
         * @param WOrders             $orders
         * @param WTransactionRequest $request
         * @param array               $logMsg
         *
         * @return bool
         */
        public function requestQueryDrVietinAtm(WOrders $orders, WTransactionRequest $request, &$logMsg = array())
        {
            $location_vietinbank = WLocationVietinbank::model()->find('id=:id', array(':id' => $orders->province_code));
            if ($location_vietinbank) {
                if (!empty($location_vietinbank->pServiceCode)) {
                    $this->serviceCode = $location_vietinbank->pServiceCode;
                }
                if (!empty($location_vietinbank->pProviderId)) {
                    $this->providerId = $location_vietinbank->pProviderId;
                }
                if (!empty($location_vietinbank->pMerchantId)) {
                    $this->merchantId = $location_vietinbank->pMerchantId;
                }
            }
            $this->end_point          = 'http://192.168.6.156:9008/directpay/inq-trx/v1';
            $this->requestId          = time() . rand(1, 9);
            $this->queryTransactionId = $request->transaction_id;
            $this->transTime          = date('YmdHis');
            $this->addInfo            = '';
            $this->req_ary_param      = array(
                'requestId'          => $this->requestId,
                'serviceCode'        => $this->serviceCode,
                'providerId'         => $this->providerId,
                'merchantId'         => $this->merchantId,
                'queryTransactionId' => $this->queryTransactionId,
                'queryType'          => $this->queryType,
                'transTime'          => $this->transTime,
                'channel'            => $this->channel,
                'version'            => $this->version,
                'language'           => $this->language,
                'addInfo'            => $this->addInfo,
            );

            $logMsg[]                         = array(CJSON::encode($this->req_ary_param), 'arr_param_createSignature request:', 'T', time());
            $this->pSignature                 = $this->createSignature($this->req_ary_param, $this->pPrivateKey);
            $this->req_ary_param['signature'] = $this->pSignature;

            $json_data = CJSON::encode($this->req_ary_param);
            //log
            $logMsg[] = array($this->end_point, 'Request queryDr URL(Vietinbank ATM):' . __LINE__, 'T', time());
            $logMsg[] = array($json_data, 'Request json(POST):' . __LINE__, 'T');

            $response_raw = Utils::cUrlPostJson($this->end_point, $json_data, TRUE, 30, $http_code);
            $arr_response = CJSON::decode($response_raw);

            $logMsg[] = array($http_code, 'http_code:' . __LINE__, 'T', time());
            $logMsg[] = array($response_raw, 'Response queryDr:' . __LINE__, 'T', time());
            //end log

            //insert log DB: request queryDr
            $note = "vietinbank ATM queryDr http_code: $http_code";
            WTransactionRequest::writeLog(WTransactionRequest::VIETINBANK_QUERY_DR, $orders, $this->requestId, $this->end_point, $json_data, $response_raw, WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, '', $note);

            if (!empty($arr_response['responseCode']) && $arr_response['responseCode'] == '00'
                && !empty($arr_response['signature'])
                && $this->verifySignature($arr_response, $this->pPublicKey, $arr_response['signature'], $logMsg)
                //check signature
            ) {
                return TRUE;
            } else {
                $logMsg[] = array('Fail', 'verifySignature:' . __LINE__, 'T');
            }

            return FALSE;
        }

        /**
         * @param       $private_key
         * @param array $transaction_response
         * @param array $data_file
         */
        public function convertDataChecking($private_key, $transaction_response = array(), &$data_file = array())
        {
            if ($transaction_response) {
                foreach ($transaction_response as $response) {
                    //convert url request from tbl_transaction_response
                    $data_response = array();
                    if ($response->request) {
                        switch ($response->request_data_type) {
                            case WTransactionRequest::TYPE_JSON:
                                $data_response = CJSON::decode($response->request);
                                break;
                            case WTransactionRequest::TYPE_QUERY_PARAM:
                                parse_str($response->request, $data_response);
                                break;
                            default:
                                break;
                        }
                    }
                    //convert url request from tbl_transaction_request
                    $data_request  = array();
                    $trans_request = WTransactionRequest::model()->find('order_id=:order_id',
                        array(':order_id' => $response->order_id));
                    if ($trans_request && $trans_request->request) {
                        switch ($trans_request->request_data_type) {
                            case WTransactionRequest::TYPE_JSON:
                                $data_request = CJSON::decode($trans_request->request);
                                break;
                            case WTransactionRequest::TYPE_QUERY_PARAM:
                                parse_str($trans_request->request, $data_request);
                                break;
                            default:
                                break;
                        }
                    }
                    if ($data_response && $data_request && !empty($data_request['requestId'])
                        && !empty($data_request['merchantId']) && !empty($data_response['bankTransactionId'])
                        && isset($data_request['amount']) && isset($data_request['transTime'])
                    ) {
                        $data = array(
                            'RecordType'       => '0002',    //detail record
                            'RcReconcile'      => '00',      //success
                            'MsgType'          => '1210',    //payment
                            'CurCode'          => 'VND',
                            'Amount'           => $data_request['amount'],
                            'TranId'           => $data_request['requestId'],
                            'RefundId'         => '',
                            'TranDate'         => date('d/m/Y H:i:s', strtotime($data_request['transTime'])),
                            'MerchantId'       => $data_request['merchantId'],
                            'BankTrxSeq'       => $data_response['bankTransactionId'],
                            'BankResponseCode' => isset($data_response['BankResponseCode']) ? $data_response['BankResponseCode'] : '',
                            'CardNumber'       => isset($data_response['CardNumber']) ? $data_response['CardNumber'] : '',
                        );

                        $check_sum        = md5(implode('', $data) . $private_key);
                        $data['CheckSum'] = $check_sum;

                        $data_file[] = implode('|', $data);//add to file
                    }
                }
            }
        }
    }