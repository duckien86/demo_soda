<?php

    class AWarningToolController extends AController
    {
        public function actionIndex()
        {
            set_time_limit(99999);
            $data_input  = array(
                'prefix' => '8488',
                'search' => '',
            );
            $data_output = self::searchMsisdn($data_input);

            if ($data_output) {
                return TRUE;
            } else {

                $mt_content = Yii::t('adm/mt_content', 'message_warning_java_hanging');
                $msisdn     = array(
                    '0919222400',   // Chi
                );
                foreach ($msisdn as $value) {
                    self::sendSmsDefaukt($value, $mt_content);
                }
            }

            return TRUE;
        }

        public static function searchMsisdn($data)
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

            $logMsg[] = array($GLOBALS['config_common']['api']['hostname'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api

            $response = Utils::cUrlPostJson($GLOBALS['config_common']['api']['hostname'], $str_json, FALSE, 45, $http_code);
            if ($response) {
                $logMsg[]   = array($http_code, 'http_code: ' . __LINE__, 'T', time());
                $logMsg[]   = array($response, 'Output: ' . __LINE__, 'T', time());
                $logFolder  = "Log_search_sim_warning_java_hanging/" . date("Y/m/d");
                $logObj     = ATraceLog::getInstance($logFolder);
                $server_add = $_SERVER['SERVER_ADDR'] . '_';
                if (YII_DEBUG) {
                    $server_add = '';
                }
                $logObj->setLogFile('search.log');
                $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
                $logObj->processWriteLogs($logMsg);
                if ($http_code == 200) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }

            return FALSE;
        }

        /**
         * Send SMS deatail
         */
        public static function sendSmsDefaukt($msisdn, $mt_content)
        {

            $result   = FALSE;
            $logMsg   = array();
            $logMsg[] = array('Start Send MT warning Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $mt_content, $mtUrl, $http_code);
            if ($flag) {
                $result   = TRUE;
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($mt_content, 'msgBody:' . __LINE__, 'T', time());
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt_warning_java_hanging/" . date("Y/m/d");

            $logObj = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('waring.log');
            $logMsg[] = array('Search', 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $result;
        }

    }

