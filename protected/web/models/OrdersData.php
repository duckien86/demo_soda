<?php

class OrdersData extends CommonOrdersData
{
    public $orders;
    public $order_details;
    public $order_state;
    public $sim;
    public $package;
    public $package_flexible;
    public $card;
    public $session_cart;
    public $operation;
    public $sim_raw_data;
    public $change_sim_type = FALSE;
    public $otp_form;
    public $package_sim_kit;
    public $sim_type; //sim vật lý :0 || esim: 1

    public $html_order;
    public $url = '';
    public $url_fiber = '';
    const OPERATION_BUYSIM = 'buysim';
    const OPERATION_TOPUP = 'topup';
    const OPERATION_BUYCARD = 'buycard';

    function __construct()
    {
        if (!Yii::app()->user->isGuest && Yii::app()->user->username == 'minhphuong') {
            $this->url = $GLOBALS['config_common']['api']['hostname_beta'];
        } else {
            $this->url = $GLOBALS['config_common']['api']['hostname'];
            $this->url_fiber = $GLOBALS['config_common']['api']['host_fiber'];
        }

        parent::__construct();
    }

    public function searchMsisdn($data, $type = 'web_search_msisdn')
    {
        $ip = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?: getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR') ?: getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');


        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'ip' => $ip,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status']) && isset($arr_response['data']) && !empty($arr_response['data'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                $data = CJSON::decode($arr_response['data']);
                if (isset($data['list']) && !empty($data['list'])) {
                    return $data['list'];
                }
            }
        }

        return FALSE;
    }

    public function addToCart($data)
    {
        $type = 'web_add_msisdn_to_cart';
        if (WAffiliateManager::checkApiCheckout()) {
            $type = 'web_add_msisdn_to_cart_pre';
        }
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code'])) {
                if ($status['code'] == 1) {
                    return CJSON::decode($arr_response['data']);
                } else {
                    $status['msg'] = !empty($status['msg']) ? CFunction::vn_str_filter($status['msg']) : "NO msg";
                    return $status['code'] . ' | ' . $status['msg'];
                }
            }
        }

        return FALSE;
    }

    public function buySim($data, $api_type = FALSE)
    {

        $type = 'web_checkout';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        $count = 0;
        //call api
        do {
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);
            $arr_response = CJSON::decode($response);
            $count++;
        } while (isset($arr_response['status']['code']) && $arr_response['status']['code'] != 1 && $count < 3);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if ($api_type) {
                return $status;
            }
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            } else {
                if (!empty($status['msg']) && $status['msg'] == 'STK-1234') {
                    Yii::app()->session['msg_code'] = $status['msg'];
                    return FALSE;
                }
            }
        }

        return FALSE;
    }

    public function registerPackage($data)
    {
        $type = 'web_register_package';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public function buyCard($data)
    {
        $type = 'web_buy_card';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function topup($data)
    {
        $type = 'web_topup';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function updateOrderStatus($data, $version = null)
    {
        $type = 'web_update_order_status' . $version;
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function checkCouponCode($data)
    {
        $type = 'web_check_coupon_code';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                unset(Yii::app()->request->cookies['utm_source']);
                unset(Yii::app()->request->cookies['aff_sid']);
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * get list order by customer
     *
     * @param $data
     *
     * @return mixed
     */
    public function getListOrder($data)
    {
        $type = 'web_get_history_order_by_ssoid';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status']) && isset($arr_response['data']) && !empty($arr_response['data'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                $data = CJSON::decode($arr_response['data']);
                if (isset($data['listOrder']) && !empty($data['listOrder'])) {
                    return $data['listOrder'];
                }
            }
        }

        return FALSE;
    }

    public function getOrderDetail($data)
    {
        $type = 'web_get_order_detail_ssoid';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status']) && isset($arr_response['data']) && !empty($arr_response['data'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return CJSON::decode($arr_response['data']);
            }
        }

        return FALSE;
    }

    public function removeKeepMsisdn($data, $reason = 'unknown')
    {
        $type = 'web_remove_keep';
        if (WAffiliateManager::checkApiCheckout()) {
            $type = 'web_remove_keep_pre';
        }
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
            'reason' => $reason,
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function getListPackage($data)
    {
        $type = 'web_check_package';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        //decode output
        $arr_response = CJSON::decode($response);
        if (isset($arr_response['data']) && !empty($arr_response['data'])) {
            $data = CJSON::decode($arr_response['data']);
            if (isset($data['list']) && !empty($data['list'])) {
                return $data['list'];
            }
        }

        return FALSE;
    }

    public function cancelPackage($data)
    {
        $type = 'web_remove_package';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public function registerPackageFlexible($data)
    {
        $type = 'web_register_packages_ff';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public function checkDiscountPricePackage($data)
    {
        $type = 'web_check_ctkm';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status']) && isset($arr_response['data']) && !empty($arr_response['data'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                $data = CJSON::decode($arr_response['data']);
                if (isset($data['result'])) {
                    return $data['result'];
                }
            }
        }

        return FALSE;
    }

    public function changePackage($data)
    {
        $type = 'web_post_change';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start web_post_change process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    /**
     * check msisdn in session Yii::app()->session['orders_data']['sim_raw_data']
     *
     * @param $msisdn
     * @param $sim_type
     * @param $price
     * @param $sim_raw_data_arr
     * @param $sim
     *
     * @return bool
     */
    public function checkSimInRawData($msisdn, $sim_type, $price, $sim_raw_data_arr, &$sim)
    {
        foreach ($sim_raw_data_arr as $sim_raw) {
            if ((isset($sim_raw['msisdn']) && $sim_raw['msisdn'] == $msisdn)
                && (isset($sim_raw['msisdn_type']) && $sim_type == $sim_raw['msisdn_type'])
                && (isset($sim_raw['price']) && $price == $sim_raw['price'])
            ) {
                $sim->msisdn = $sim_raw['msisdn'];
                $sim->price = $sim_raw['price'];
                $sim->type = $sim_raw['msisdn_type'];
                $sim->term = $sim_raw['term'];
                $sim->price_term = $sim_raw['price_term'];
                $sim->store_id = (string)$sim_raw['store'];
                $sim->raw_data = $sim_raw;
                if ($sim_raw['term'] == 0 && $sim_raw['price_term'] == 0) {
                    $this->change_sim_type = TRUE;
                }

                return TRUE;
            }
        }

        return FALSE;
    }


    /**
     * check dang su dung dich vu chuyen vung quoc te
     *
     * @param $data
     *
     * @return bool
     */
    public function dataRoamingCheckIr($data)
    {
        $type = 'data_roaming_check_cvqt';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_cvqt process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function dataRoamingSendOtp($data)
    {
        $type = 'data_roaming_otp_send';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_otp_send process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    //confirm va dk Ir
    public function dataRoamingConfirmRegisterIr($data)
    {
        $type = 'data_roaming_sent_otp_cvqt';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_sent_otp_cvqt process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    public function dataRoamingVerifyRegisterIr($data)
    {
        $type = 'data_roaming_check_otp_register';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_otp_register process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    public function dataRoamingRegisterRx($data)
    {
        $type = 'data_roaming_register_package';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_register_package process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    public function dataRoamingSearchRx($data)
    {
        $type = 'data_roaming_check_remain_amount';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_remain_amount process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);

        return $arr_response;
    }

    public function dataRoamingCancelRx($data)
    {
        $type = 'data_roaming_rx_deregister';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_rx_deregister process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    public function dataRoamingCancelIr($data)
    {
        $type = 'data_roaming_deregister_cvqt';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_deregister_cvqt process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {

            return $arr_response['status'];
        }

        return FALSE;
    }

    public function searchPackage($data)
    {
        $type = 'web_get_package_promote';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start web_get_package_promote process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);

        return $arr_response;
    }


    /*
     * 1.	Tra cứu thông tin khuyến mại, khuyến nghị
     */
    public function searchPromotionPackage($data)
    {
        $type = 'web_get_km_kn';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start web_get_km_kn process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);

        return $arr_response;
    }


    public function registerPrepaidToPostPaid($data)
    {
        $type = 'web_prepaid_to_postpaid';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];

            return $status;
        }

        return FALSE;
    }

    public function getprovince()
    {
        $type = 'gettinhs';
        $name = 'get_provinces';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $name . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        //$response = Utils::cUrlGetFiber($this->url_fiber . $type, $token, 45, $http_code, FALSE, FALSE, TRUE);
        //$logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $name . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);
        $response = '{
                    "errorCode": 0,
                    "Message": "Thành công",
                    "Data": [
                    {
                        "tinh_id": "21",
                        "tentinh": "Hà Nội"
                    },
                    {
                    "tinh_id": "26",
                    "tentinh": "Hải phòng"
                    }
                        ]
                  }';
        $arr_response = CJSON::decode($response);

        return $arr_response;
    }

    public function getlistfiber($data)
    {
        $id = Yii::app()->request->csrfToken;
        $type = 'getdichvumy_vnpt';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);
        //        $response = '{
        //                        "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                            "dichvu_id": "12",
        //                            "ten_dichvu": "MegaEyes"
        //                            },
        //                            {
        //                            "dichvu_id": "1",
        //                            "ten_dichvu": "Cố định"
        //                            },
        //                            {
        //                            "dichvu_id": "2",
        //                            "ten_dichvu": "Di động"
        //                            }
        //                        ]
        //                        }';
        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    public function getlistdistrict($data)
    {
        $type = 'getquans';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);
        //        $response = '{
        //                        "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                                "quan_id": "13",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Bạch Long Vỹ"
        //   	                        },
        //                            {
        //                                "quan_id": "7",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Cát Hải"
        //                            },
        //                            {
        //                                "quan_id": "12",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Vĩnh Bảo"
        //                            },
        //                            {
        //                                "quan_id": "11",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Tiên Lãng"
        //                            },
        //                            {
        //                                "quan_id": "9",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Kiến Thuỵ"
        //                            },
        //                            {
        //                                "quan_id": "6",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện An Lão"
        //                            },
        //                            {
        //                                "quan_id": "5",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện An Dương"
        //                            },
        //                            {
        //                                "quan_id": "10",
        //                                "ma_quan": "",
        //                                "ten_quan": "Huyện Thuỷ Nguyên"
        //                            },
        //                            {
        //                                "quan_id": "17",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Dương Kinh"
        //                            },
        //                            {
        //                                "quan_id": "8",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Đồ Sơn"
        //                            },
        //                            {
        //                                "quan_id": "2",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Kiến An"
        //                            },
        //                            {
        //                                "quan_id": "16",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Hải An"
        //                            },
        //                            {
        //                                "quan_id": "3",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Lê Chân"
        //                            },
        //                            {
        //                                "quan_id": "4",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Ngô Quyền"
        //                            },
        //                            {
        //                                "quan_id": "1",
        //                                "ma_quan": "",
        //                                "ten_quan": "Quận Hồng Bàng"
        //                            },
        //                            ]
        //                            }
        //                        ';
        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    /*
     * Lấy ra danh sách phường xã
     */
    public function getlistwards($data)
    {
        $type = 'getphuongs';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '{
        //                       "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                                "phuong_id": "425",
        //                                "ma_phuong": "",
        //                                "ten_phuong": "Phường Dư Hàng Kênh"
        //   	                        },
        //                            {
        //                                "phuong_id": "31",
        //                                "ma_phuong": "",
        //                                "ten_phuong": "Phường Vĩnh Niệm"
        //                            }
        //                            ]
        //                            }
        //
        //                        ';
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    public function getliststreet($data)
    {
        $type = 'getphos';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '
        //                        {
        //                        "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                                "pho_id": "5726",
        //                                "ten_pho": "Đường Dương Đình Nghệ"
        //   	                        },
        //                            {
        //                                "pho_id": "4650",
        //                                "ten_pho": "Khu 1a"
        //                            }]
        //                            }
        //                         ';
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    public function getlistap($data)
    {
        $type = 'getaps';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '
        //                        {
        //                        "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                                "ap_id": "5726",
        //                                "ten_ap": "ap 1"
        //   	                        },
        //                            {
        //                                "ap_id": "4650",
        //                                "ten_ap": "ap 2"
        //                            }]
        //                            }
        //                         ';
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    public function getlistkhu($data)
    {
        $type = 'getkhus';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '
        //                        {
        //                        "errorCode": 0,
        //                        "Message": "Thành công",
        //                        "Data": [
        //                            {
        //                                "khu_id": "5726",
        //                                "ten_khu": "khu 1"
        //   	                        },
        //                            {
        //                                "khu_id": "4650",
        //                                "ten_khu": "khu 2"
        //                            }]
        //                            }
        //                         ';
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    /*
     * Lấy ra danh sách loại hình thuê bao
     */
    public function getlisttype($data)
    {
        $type = 'getloaihinhs';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '{
        //                            "errorCode": 0,
        //                            "Message": "Thành công",
        //                            "Data": [
        //                                {
        //                                    "loaitb_id": "14",
        //                                    "ten_loaihinh": "ISDN 2B+D cáp quang"
        //                                },
        //                                {
        //                                    "loaitb_id": "15",
        //                                    "ten_loaihinh": "ISDN 30B+D cáp đồng"
        //                                }
        //                                ]
        //                        }';
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);
        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    /*
     * Call API tiếp nhận form đăng kí fier
     */
    public function receive($data)
    {

        $type = 'TiepNhanYeuCauMyVNPT';
        $name = 'requirement accepted';
        $token = 'API-CSKH-ECB5835C0D98B09884AC5B811799D02F60D235655AF57A1976523B04155B3DE6';
        $logMsg = array();
        $logMsg[] = array('Start ' . $name . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($token, 'erp_token: ' . __LINE__, 'T', time());

        $str_json = CJSON::encode($data);
        $logMsg[] = array($this->url_fiber . $type, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJsonFiber($this->url_fiber . $type, $token, $str_json, 45, FALSE, $http_code, TRUE);
        //        $response = '{
        //                        "errorCode": 0,
        //                        "Message": "Lỗi: Địa danh chưa được gán nhân viên quản lý",
        //                        "Data": {
        //                        null
        //                            }
        //                      }
        //                    ';
        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $name . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    /*
     * API checkout fiber
     */
    public function checkoutfiber($data)
    {
        $id = Yii::app()->request->csrfToken;
        $type = 'web_checkout_order';
        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());
        $arr_param = array(
            'id' => $id,
            'type' => $type,
            'data' => CJSON::encode($data)
        );
        $str_json = CJSON::encode($arr_param);
        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);
        $response = '{
                        "errorCode": 0,
                        "Message": "Thành công", 
                      }
                    ';
        $arr_response = CJSON::decode($response);
        return $arr_response;
    }

    /**
     * check dang su dung dich vu chuyen vung quoc te
     *
     * @param $data
     *
     * @return bool
     */
    public function dataRoamingCheckVnptMember($data, $return_code = FALSE)
    {
        $type = 'data_roaming_check_member';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_cvqt process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        if (isset($arr_response['status'])) {
            $status = $arr_response['status'];
            if ($return_code) {
                return $status;
            }
            if (isset($status['code']) && $status['code'] == 1) {
                return TRUE;
            }
        }

        return FALSE;
    }

    public static function getCheckMemberMsg($data, $package_name = '')
    {
        $result = Yii::t('web/portal', 'register_fail');
        $msg = !empty($data['msg']) ? $data['msg'] : '';
        $msg_arr = explode('|', $msg);
        $default_msg = Yii::t('web/portal', 'register_fail');
        if (!empty($msg) && !empty($msg_arr[0])) {
            switch ($msg_arr[0]) {
                case "20001":
                    $result = "Đăng ký không thành công.
                    Tài khoản chính của Quý khách không đủ để đăng ký gói cước, xin vui lòng nạp thêm tiền để tiếp tục mua gói. 
                    Chi tiết xin vui lòng liên hệ: +8424.3773.1857 (miễn phí khi đang chuyển vùng Quốc tế) 
                    hoac 18001091 (Khi đang ở trong nước).";
                    break;
                case "00021":
                    $result = "Đăng ký không thành công. 
                        Thuê bao Qúy khách đang có gói cước $msg_arr[1] nên không đăng ký được gói cước  $msg_arr[2]. 
                        Chi tiết xin vui lòng liên hệ: +8424.3773.1857 (miễn phí khi đang chuyển vùng Quốc tế) 
                        hoac 18001091 (Khi đang ở trong nước).";
                    break;
                default:
                    $result = $default_msg;
            }
        } else {
            $result = $default_msg;
        }
        return $result;
    }

    public function checkPhoneKHDN($data)
    {
        $type = 'web_check_khdn';
        $id = Yii::app()->request->csrfToken;

        $logMsg = array();
        $logMsg[] = array('Start ' . $type . ' Log', 'Start web_check_khdn process:' . __LINE__, 'I', time());
        $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

        $arr_param = array(
            'type' => $type,
            'id' => $id,
            'data' => CJSON::encode($data),
        );
        $str_json = CJSON::encode($arr_param);

        $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

        //call api
        $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());
        $logMsg[] = array($response, 'Output: ' . __LINE__, 'T', time());
        $logFolder = "web/Log_call_api/" . date("Y/m/d");
        $logObj = TraceLog::getInstance($logFolder);
        $server_add = $_SERVER['SERVER_ADDR'] . '_';
        if (YII_DEBUG) {
            $server_add = '';
        }
        $logObj->setLogFile($server_add . $type . '.log');
        $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        $arr_response = CJSON::decode($response);
        return $arr_response;
    }
}
