<?php

    class OtpForm extends CFormModel
    {
        public $msisdn;
        public $token;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                // name, email, subject and body are required
                array('msisdn', 'required', 'on' => 'getTokenKey'),
                array('token', 'required', 'on' => 'checkTokenKey'),
                array('msisdn', 'length', 'min' => 8, 'max' => 20),
                array('msisdn', 'numerical', 'integerOnly' => TRUE),
                array('token', 'length', 'max' => 255)
            );
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
         * @return bool|string
         */
        public static function getTokenKey()
        {
            if (preg_match("/^0[0-9]{9,10}$/i", Yii::app()->session['phone_contact']) == TRUE || preg_match("/^84[0-9]{9,11}$/i", Yii::app()->session['phone_contact']) == TRUE) {
                $token = (string)rand(1000, 9999);

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
            if ($this->token === Yii::app()->session['token_key'])
                return TRUE;
            else
                return FALSE;
        }

        public static function unsetSession()
        {
            unset(Yii::app()->session['token_key']);
            unset(Yii::app()->session['verify_number']);
            unset(Yii::app()->session['time_reset']);
            unset(Yii::app()->session['session_cart']);
            unset(Yii::app()->session['orders_data']);
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
            $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code);
            if ($flag) {
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            }

            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt/" . date("Y/m/d");

            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }

        public static function sentMail($from, $to, $msgBody, $file_name)
        {
            $logMsg   = array();
            $logMsg[] = array('Start Send mail ' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send mail
            $flag = Utils::sendEmail($from, $to, $from, '', $msgBody, 'application.adm.config');
            if ($flag) {
                $logMsg[] = array($from, 'From:' . __LINE__, 'T', time());
                $logMsg[] = array($to, 'Sent mail to ok:' . __LINE__, 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            } else {
                $logMsg[] = array($from, 'From:' . __LINE__, 'T', time());
                $logMsg[] = array($to, 'Sent mail to fail:' . __LINE__, 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            }

            $logFolder = "Log_send_mail/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }
    }