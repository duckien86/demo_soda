<?php

    class AOrdersData extends CommonOrdersData
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
        public $cskh_token_link;

        public $sim_agency;

        public $html_order;
        public $url        = '';
        public $log_folder = 'adm';

        const OPERATION_BUYSIM  = 'buysim';
        const OPERATION_TOPUP   = 'topup';
        const OPERATION_BUYCARD = 'buycard';
        const BUY_SIM_AGENCY    = 'simagency';
        
        function __construct()
        {
            $this->url = $GLOBALS['config_common']['api']['hostname'];

            parent::__construct();
        }

        public function searchMsisdn($data)
        {
            $type = 'web_search_msisdn_ktv';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response   = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);
            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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

        public function searchMsisdnAgency($data)
        {
            $type = 'web_search_msisdn';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response   = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);
            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {
                $status = $arr_response['status'];
                if (isset($status['code']) && $status['code'] == 1) {
                    return CJSON::decode($arr_response['data']);
                }
            }

            return FALSE;
        }

        public function buySim($data)
        {
            $type = 'web_checkout';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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

        public function registerPackage($data)
        {
            $type = 'web_register_package';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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

        public function updateOrderStatus($data)
        {
            $type = 'web_update_order_status';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'   => $type,
                'id'     => $id,
                'data'   => CJSON::encode($data),
                'reason' => $reason,
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start web_post_change process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
                    $sim->msisdn     = $sim_raw['msisdn'];
                    $sim->price      = $sim_raw['price'];
                    $sim->type       = $sim_raw['msisdn_type'];
                    $sim->term       = $sim_raw['term'];
                    $sim->price_term = $sim_raw['price_term'];
                    $sim->store_id   = (string)$sim_raw['store'];
                    $sim->raw_data   = $sim_raw;
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_cvqt process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_otp_send process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {

                return $arr_response['status'];
            }

            return FALSE;
        }

        public function dataRoamingConfirmRegisterIr($data)
        {
            $type = 'data_roaming_sent_otp_cvqt';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_sent_otp_cvqt process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_otp_register process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_register_package process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_check_remain_amount process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);

            return $arr_response;
        }

        public function dataRoamingCancelRx($data)
        {
            $type = 'data_roaming_rx_deregister';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_rx_deregister process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
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
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start data_roaming_deregister_cvqt process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array($this->url, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response = Utils::cUrlPostJson($this->url, $str_json, FALSE, 45, $http_code);

            $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
            $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder  = "/Log_call_api/" . date("Y/m/d");
            $logObj     = ATraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {

                return $arr_response['status'];
            }

            return FALSE;
        }


    }