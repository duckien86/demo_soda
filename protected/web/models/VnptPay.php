<?php

class VnptPay
{
    const ACTION_INIT    = 'INIT';
    const ACTION_CONFIRM = 'CONFIRM';

    const PAYMENT_METHOD = 'VNPTPAY';

    public $ACTION;
    public $VERSION             = '1.0.6';
    public $MERCHANT_SERVICE_ID = '470';
    public $AGENCY_ID           = '471'; // Mã đại lý
    //--->>>> Production
    // public $MERCHANT_SERVICE_ID = '622';
    // public $AGENCY_ID           = '623'; // Mã đại lý
    //--->>>> Production
    public $MERCHANT_ORDER_ID; //order_id
    public $SERVICE_ID          = '1';
    public $AMOUNT; // Số tiền thanh toán
    public $DEVICE              = '1'; // Mã thiết bị.
    public $LOCALE              = 'vi-VN'; // Ngôn ngữ.
    public $CURRENCY_CODE       = 'VND'; // Đơn vị tiền tệ.
    public $PAYMENT_METHOD; // Phương thức thanh toán
    public $DESCRIPTION; // Mô tả giao dịch.
    public $CREATE_DATE; // Thời gian giao dịch.
    public $CLIENT_IP; // IP của khách hàng.
    public $SECURE_CODE; // Chuỗi mã hóa của bản tin.

    public $req_ary_param;
    public $end_point  = 'https://sandboxpay.vnptmedia.vn/rest/payment/v1.0.6/init_m_merch';
    public $api_key    = '9f65fb4b986be53d398234870ee9475f';
    public $secret_key = 'bbb51a5c6171d575c02deda211f4e959';
    //--->>>> Production
    // public $end_point  = 'https://api.vnptpay.vn/rest/payment/v1.0.6/init_m_merch';
    // public $api_key    = 'a9f56ff1-067d-31d7-93f9-fb03b2786cd7';
    // public $secret_key = '21040f3658351a66251ac86da865fd4e';
    //--->>>> Production
    public function __construct()
    {
        $this->CLIENT_IP = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @param $orders
     *
     * @return array
     *
     */
    public function createRequestUrl($orders)
    {
        $log_secure          = '';
        $this->SECURE_CODE   = $this->createSecureCode($this->req_ary_param, $this->secret_key, $log_secure);
        $this->req_ary_param = array(
            'ACTION'              => $this->ACTION,
            'VERSION'             => $this->VERSION,
            'MERCHANT_SERVICE_ID' => $this->MERCHANT_SERVICE_ID,
            'AGENCY_ID'           => $this->AGENCY_ID,
            'MERCHANT_ORDER_ID'   => $this->MERCHANT_ORDER_ID,
            'SERVICE_ID'          => $this->SERVICE_ID,
            'AMOUNT'              => $this->AMOUNT,
            'DEVICE'              => $this->DEVICE,
            'LOCALE'              => $this->LOCALE,
            'CURRENCY_CODE'       => $this->CURRENCY_CODE,
            'PAYMENT_METHOD'      => $this->PAYMENT_METHOD,
            'DESCRIPTION'         => $this->DESCRIPTION,
            'CREATE_DATE'         => $this->CREATE_DATE,
            'CLIENT_IP'           => $this->CLIENT_IP,
            'SECURE_CODE'         => $this->SECURE_CODE,
        );
        $urlRequest          = $this->initPaymentSession($orders, $this->req_ary_param, $this->end_point, $log_secure);

        //log
        $logMsg[]   = array('Start Vnpt Pay Request Log', 'Start process:', 'I', time());
        $logMsg[]   = array($urlRequest, 'Request URL', 'T', time());
        $logMsg[]   = array('', 'Finish process', 'F', time());
        $logFolder  = "web/Log_vnpt_pay_request/" . date("Y/m");
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

    public function initPaymentSession($orders, $array_params, $url, $log_secure = '')
    {
        $requestId = isset($array_params['MERCHANT_ORDER_ID']) ? $array_params['MERCHANT_ORDER_ID'] : '';
        if (empty($requestId)) {
            $requestId = $orders->id . '_' . time() . rand(1000, 9999);
        }
        $start_time       = time();
        $start_time_micro = microtime();
        $logMsg[]         = array('Start initPaymentSession Log From IP:' . $_SERVER['REMOTE_ADDR'], 'Start process From:', 'I', $start_time);
        $logMsg[]         = array($log_secure, 'string request data to hash :' . __LINE__, 'T');

        $json_data   = CJSON::encode($array_params);
        $logMsg[]    = array($url, 'Request Url:' . __LINE__, 'T');
        $logMsg[]    = array($json_data, 'Request json:' . __LINE__, 'T');
        $redirectUrl = '';

        //set header
        $opt_header_arr = array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data),
            'Authorization: Bearer ' . $this->api_key,
        );
        $response_raw   = Utils::cUrlPostJson($url, $json_data, TRUE, 30, $http_code, $opt_header_arr);

        $arr_response  = CJSON::decode($response_raw);

        $arr_data_hash = $arr_response;
        //unset SECURE_CODE response
        if (isset($arr_data_hash['SECURE_CODE'])) {
            unset($arr_data_hash['SECURE_CODE']);
        }
        $secure_code = $this->createSecureCode($arr_data_hash, $this->secret_key, $log_secure_res);
        $logMsg[]    = array($log_secure_res, 'string response data to hash :' . __LINE__, 'T');
        $logMsg[]    = array($secure_code, 'SECURE_CODE createSecureCode():', 'T', time());
        $logMsg[]    = array($arr_response['SECURE_CODE'], 'SECURE_CODE raw_data:', 'T', time());
        if (
            !empty($arr_response['REDIRECT_URL'])
            && !empty($arr_response['RESPONSE_CODE']) && $arr_response['RESPONSE_CODE'] == '00'
            && (strcmp($arr_response['SECURE_CODE'], $secure_code) == 0)
        ) {
            $redirectUrl = $arr_response['REDIRECT_URL'];
        } else {
            $logMsg[] = array('Fail', 'verify SECURE_CODE || empty REDIRECT_URL || RESPONSE_CODE :' . __LINE__, 'T');
        }
        $finish_time       = time();
        $finish_time_micro = microtime();
        $logMsg[]          = array("http_code:$http_code |exec(ms):" . ($finish_time_micro - $start_time_micro), 'Status:' . __LINE__, 'T');
        $logMsg[]          = array($response_raw, 'raw data:' . __LINE__, 'T');
        $logMsg[]          = array('', 'Finish process-' . __LINE__, 'F', $finish_time);
        $logFolder         = "web/Log_vnpt_pay_init_payment/" . date("Y/m");
        $logObj            = TraceLog::getInstance($logFolder);
        $server_add        = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . date('d') . '.log');
        $logObj->processWriteLogs($logMsg);

        //insert log DB
        $note = "vnpt_pay_init_payment http_code: $http_code";
        WTransactionRequest::writeLog(WTransactionRequest::VNPT_PAY, $orders, $requestId, $url, $json_data, $response_raw, WTransactionRequest::TYPE_JSON, WTransactionRequest::TYPE_JSON, '', $note);

        return $redirectUrl;
    }

    /**
     * @param        $params
     * @param        $secretKey
     * @param string $str_data
     *
     * @return string
     */
    public function createSecureCode($params, $secretKey, &$str_data = '')
    {
        $data = array_values($params);
        //Separate |
        $str_data = implode("|", $data);
        $str_data .= '|' . $secretKey;

        return hash('sha256', $str_data);
    }

    /**
     * @return array
     */
    public static function arrayErrorCode()
    {
        return array(
            '00' => 'Thanh cong',
            '01' => 'Giao dich that bai',
            '02' => 'Du lieu khong dung dinh dang',
            '03' => 'Ma giao dich da ton tai',
            '04' => 'Time out',
            '05' => 'Khong tim thay du lieu',
            '06' => 'Loi he thong',
            '07' => 'Chu ky khong dung',
            '08' => 'Merchant service dang bi khoa',
            '09' => 'Merchant service khong ton tai',
            '96' => 'He thong dang bao tri',
            '99' => 'Loi khong xac dinh',
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
     * @return array
     */
    public static function arrayErrorCodeResponse()
    {
        return array(
            '00' => 'Thanh cong',
            '01' => 'Khong tim thay giao dich',
            '02' => 'Giao dich da confirm',
            '08' => 'He thong ban hoac timeout',
            '97' => 'Chu ky khong hop le',
            '99' => 'Cac loi khac',
        );
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public static function getContentErrorResponse($code)
    {
        $array_error = self::arrayErrorCodeResponse();

        return (isset($array_error[$code])) ? $array_error[$code] : $code;
    }
}
