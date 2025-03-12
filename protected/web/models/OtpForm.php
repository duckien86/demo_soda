<?php

    class OtpForm extends CFormModel
    {
        const VINAPHONE_TELCO = 'VINAPHONE';

        public $msisdn;
        public $token;
        public $captcha;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                // name, email, subject and body are required
                array('msisdn', 'required', 'on' => 'getTokenKey', 'message' => 'Vui lòng nhập số điện thoại'),
                array('token', 'required', 'on' => 'checkTokenKey', 'message' => 'Vui lòng nhập mã OTP'),
                array('token', 'required', 'on' => 'checkTokenKeyApi'),
//                array('msisdn', 'length', 'min' => 8, 'max' => 20),
                array('msisdn', 'numerical', 'integerOnly' => TRUE),
                array('token', 'length', 'max' => 255),
                array('msisdn', 'msisdn_validation'),
                array('msisdn', 'required', 'on' => 'getTokenKeyRoaming', 'message' => 'Vui lòng nhập số điện thoại'),
                array('msisdn', 'checkInfoPhone', 'on' => 'getTokenKeyRoaming'),
                array('captcha', 'verifyCaptcha', 'on' => 'getTokenKeyRoaming'),
            );
        }

        public function checkInfoPhone()
        {
            $msisdn = $this->msisdn;
            $data_input = array(
                'so_tb' => $msisdn
            );
            $data_output = Utils::getInfoPhone($data_input);
            if($data_output['code']== -1){
                $this->addError('msisdn', Yii::t('web/portal', 'error_msisdn_vinaphone'));
            }
            return TRUE;
        }
        /**
         * @param $attribute
         * @param $params
         */
        public function verifyCaptcha($attribute, $params)
        {
            if (!Utils::googleVerify(Yii::app()->params->secret_key)) {
                $msg = Yii::t('web/portal', 'captcha_error');
                $this->addError($attribute, $msg);
            }
        }

        /**
         * Declares customized attribute labels.
         * If not declared here, an attribute would have a label that is
         * the same as its name with the first letter in upper case.
         */
        public function attributeLabels()
        {
            return array(
                'msisdn' => 'Nhập số điện thoại',
                'token'  => 'Nhập mã xác nhận',
            );
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            if ($this->msisdn) {
                $input = $this->msisdn;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('msisdn', Yii::t('web/portal', 'msisdn_validation'));
                }
            }
        }

        /**
         * @return bool
         */
        public function detectByTelco()
        {
            if ($this->msisdn) {
                $telco = Utils::detectTelcoByMsisdn($this->msisdn);
                if ($telco != self::VINAPHONE_TELCO) {
                    $this->addError('msisdn', Yii::t('web/portal', 'error_msisdn_vinaphone'));
                }
            }

            return TRUE;
        }

        /**
         * @param $phone_contact
         *
         * @return bool|string
         */
        public static function getTokenKey($phone_contact, $max_length = 6)
        {
            if (preg_match("/^0[0-9]{9,10}$/i", $phone_contact) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $phone_contact) == TRUE) {
                if($max_length == 4){
                    $token = (string)rand(1000, 9999);
                }else{
                    $token = (string)rand(100000, 999999);
                }
                return $token;
            } else {
                return FALSE;
            }
        }

        /**
         * check token key
         *
         * @return bool
         */
        public function checkTokenKey()
        {
            if ($this->token == Yii::app()->session['token_key'])
                return TRUE;
            else
                return FALSE;
        }

        /**
         * check token_key via api
         *
         * @param $token_key
         *
         * @return bool
         */
        public function checkTokenKeyApi($token_key)
        {
            if ($this->token == $token_key)
                return TRUE;
            else
                return FALSE;
        }

        public static function unsetSession($remove_keep = FALSE)
        {
            if ($remove_keep === TRUE) {
                //remove keep msisdn
                if (isset(Yii::app()->session['orders_data'])) {
                    $orders_data = Yii::app()->session['orders_data'];
                    $modelSim    = $orders_data->sim;
                    if ($modelSim && isset($modelSim->msisdn) && !empty($modelSim->msisdn) && isset($modelSim->store_id) && !empty($modelSim->store_id)) {
                        //call api
                        $data_input = array(
                            'so_tb' => $modelSim->msisdn,
                            'store' => $modelSim->store_id,
                        );

                        $orders_data->removeKeepMsisdn($data_input, 'otp-unset-session');
                    }
                }
            }
            unset(Yii::app()->session['token_key']);
            unset(Yii::app()->session['verify_number']);
            unset(Yii::app()->session['time_reset']);
            unset(Yii::app()->session['session_cart']);
            unset(Yii::app()->session['orders_data']);
            unset(Yii::app()->session['current_qr_code']);
            unset(Yii::app()->session['delivery_type']);
        }

        public static function unsetSessionHtmlOrder()
        {
            unset(Yii::app()->session['html_card_order']);
            unset(Yii::app()->session['message_card_order']);
            unset(Yii::app()->session['html_order']);
            unset(Yii::app()->session['message_order']);
            unset(Yii::app()->session['html_pack_order']);
            unset(Yii::app()->session['message_pack_order']);
            unset(Yii::app()->session['packageSubmitting']);
            unset(Yii::app()->session['msg_code']);
        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $file_name
         *
         * @return bool
         */
        public static function sentMtVNP($msisdn, $msgBody, $file_name)
        {
            $logMsg   = array();
            $logMsg[] = array('Start Send MT ' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code, $rs);
            if ($flag) {
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($rs, 'rawData: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder  = "web/Log_send_mt/" . date("Y/m/d");
            $logObj     = TraceLog::getInstance($logFolder);
            $server_add = $_SERVER['SERVER_ADDR'] . '_';
            if (YII_DEBUG) {
                $server_add = '';
            }
            $logObj->setLogFile($server_add . $file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }

        public static function unsetCookie()
        {
            unset(Yii::app()->request->cookies['package_cache_key']);
            unset(Yii::app()->request->cookies['package_flexible_cache_key']);
            unset(Yii::app()->request->cookies['package_roaming_cache_key']);
        }

        public static function unsetCookieUtmSource()
        {
//            unset(Yii::app()->request->cookies['utm_source']);
//            unset(Yii::app()->request->cookies['aff_sid']);
            unset(Yii::app()->request->cookies['campaign_source']);
            unset(Yii::app()->request->cookies['campaign_id']);
        }

        public static function unsetCookieHtml()
        {
            unset(Yii::app()->request->cookies['html_package_cache_key']);
        }

    }