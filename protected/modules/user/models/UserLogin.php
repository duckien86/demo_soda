<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class UserLogin extends CFormModel
    {
        public $username;
        public $password;
        public $rememberMe;
        public $otp;
        public $phone;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('username, password, phone', 'required'),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
                array('phone', 'authenticateMsisdn'),
                array('phone', 'authenExistMsisdn'),

                array('password', 'authenticate'),

            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'rememberMe' => UserModule::t("Remember me next time"),
                'username'   => UserModule::t("username or email"),
                'password'   => UserModule::t("password"),
                'phone'      => UserModule::t("phone"),
                'otp'        => UserModule::t("token_key"),
            );
        }


        public function apiLogin($username, $password)
        {
            $type = 'app_login';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $milliseconds = round(microtime(TRUE) * 1000);
            //array
            $security = array(
                'token'         => NULL,
                'time'          => $milliseconds,// milisecon
//                'pass'          => $password,
                'data_checksum' => NULL,
            );
            //JSON security.
            $security = CJSON::encode($security);
            //Mã hóa.
            $security = $this->encrypt_noreplace($security, $password, MCRYPT_RIJNDAEL_128);


            $arr_param = array(
                'type'      => $type,
                'id'        => 'id_backend_vsb',
                'data'      => NULL,
                'user_name' => $username,
                'security'  => $security,
            );


            $str_json = CJSON::encode($arr_param);

            $logMsg[] = array(Yii::app()->params['socket_api_app'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            $response     = Utils::cUrlPostJson(Yii::app()->params['socket_api_app'], $str_json);
            $logMsg[]     = array($response, 'Output: ' . __LINE__, 'T', time());
            $arr_response = CJSON::decode($response);

            $security = $this->decrypt_noreplace($arr_response['security'], $password, MCRYPT_RIJNDAEL_128);
//            CVarDumper::dump($security,10,true);die();
            $logMsg[] = array($security, 'ParseReponse: ' . __LINE__, 'T', time());
            $token    = '';
            if ($security) {
                $token = CJSON::decode($security);
                $token = $token['token'];
            }


            $logMsg[]  = array($token, 'Token: ' . __LINE__, 'T', time());
            $logFolder = "adm/Log_call_api/" . date("Y/m/d");
            $logObj    = TraceLog::getInstance($logFolder);
            $logObj->setLogFile($type . '.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            return $token;
        }

        public function safe_b64encode_noreplace($string)
        {
            $data = base64_encode($string);

//            $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

            return $data;
        }

        public function safe_b64decode_noreplace($data)
        {
//            $data = str_replace(array('-', '_'), array('+', '/'), $string);
            $mod4 = strlen($data) % 4;
            if ($mod4) {
                $data .= substr('====', $mod4);
            }

            return base64_decode($data);
        }

        public function encrypt_noreplace($encrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $encrypted = $this->safe_b64encode_noreplace(mcrypt_encrypt($algorithm, $key, $encrypt, MCRYPT_MODE_ECB, $iv));

            return $encrypted;
        }


        public function decrypt_noreplace($decrypt, $key, $algorithm)
        {
            $iv        = mcrypt_create_iv(mcrypt_get_iv_size($algorithm, MCRYPT_MODE_ECB), MCRYPT_RAND);
            $decrypted = mcrypt_decrypt($algorithm, $key, $this->safe_b64decode_noreplace($decrypt), MCRYPT_MODE_ECB, $iv);

            return $decrypted;
        }

        /**
         * Authenticates the password.
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticate($attribute, $params)
        {
            if (!$this->hasErrors())  // we only want to authenticate when no input errors
            {
                $identity = new UserIdentity($this->username, $this->password);

                $identity->authenticate();
                switch ($identity->errorCode) {
                    case UserIdentity::ERROR_NONE:
                        break;
                    case UserIdentity::ERROR_EMAIL_INVALID:
                        $this->addError("username", UserModule::t("Email is incorrect."));
                        break;
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $this->addError("username", UserModule::t("Username is incorrect."));
                        break;
                    case UserIdentity::ERROR_STATUS_NOTACTIV:
                        $this->addError("status", UserModule::t("You account is not activated."));
                        break;
                    case UserIdentity::ERROR_STATUS_BAN:
                        $this->addError("status", UserModule::t("You account is blocked."));
                        break;
                    case UserIdentity::ERROR_PASSWORD_INVALID:
                        $this->addError("password", UserModule::t("Password is incorrect."));
                        break;
                }
//                }
            }
        }


        /**
         * @param $attribute
         *  validate số điện thoại
         * @param $params
         */
        public function authenticateMsisdn($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);
                if (preg_match("/^84[0-9]{9,11}$/i", $this->$attribute) === 0) {
                    $this->addError($attribute,
                        'Số điện thoại không đúng định dạng! Vui lòng nhập lại');
                }
            }
        }

        /**
         * Check xem số điện thoại có phải là của tài khoản đăng nhập ko
         *
         * @param $attribute
         * @param $params
         */
        public function authenExistMsisdn($attribute, $params)
        {

            if ($this->$attribute) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);
                if ($this->username && $this->password) {
                    $users = User::model()->findByAttributes(array('username' => $this->username));
                    if (isset($users->username) && isset($users->phone_2) && !empty($users->phone_2)) {
                        $users->phone_2 = self::makePhoneNumberStandard($users->phone_2);
                        if ($users->phone_2 != $this->$attribute) {
                            $this->addError($attribute,
                                'Số điện thoại không phải là số người dùng đăng nhập');
                        }
                    } else {
                        $this->addError($attribute,
                            'Số điện thoại không phải là số người dùng đăng nhập');
                    }
                }
            }
        }

        /**
         * @param $otp
         * @param $msisdn
         * Gửi tin nhắn xác thực mã OTP.
         *
         * @return bool
         */
        public function sendMT($otp, $msisdn)
        {
            // Send MT.
            $mt_content = Yii::t('cskh/mt_content', 'otp_login', array(
                '{otp_login}' => $otp,
            ));
            $otp_form   = new OtpForm();
            if ($otp_form->sentMtVNP($msisdn, $mt_content, 'login_adm')) {
                return TRUE;
            }


            return TRUE;
        }

        /**
         * @param $phoneNumber
         * Đổi đầu số điện thoại từ 09->84
         *
         * @return string
         */
        public static function makePhoneNumberStandard($phoneNumber)
        {
            $phoneNumberStandard = '';
            if ($phoneNumber != '') {
                if (substr($phoneNumber, 0, 1) == '0') {
                    $phoneNumberStandard = substr($phoneNumber, 1, strlen($phoneNumber));
                } else if (substr($phoneNumber, 0, 2) == '84') {
                    $phoneNumberStandard = substr($phoneNumber, 2, strlen($phoneNumber));
                }
                $phoneNumberStandard = '84' . $phoneNumberStandard;
            }

            return $phoneNumberStandard;
        }

        /**
         * @param int $lengthChars
         * Gen mã tự sinh
         *
         * @return bool|string
         */
        public static function genTokenKey($lengthChars = 32, $numberOnly = FALSE)
        {
            if ($lengthChars <= 0) {
                return FALSE;
            } else {
                $alphaString  = 'abcdefghijklmnopqrstuvwxyz';
                $numberString = '1234567890';

                if($numberOnly){
                    $shuffleString = $numberString;
                }else{
                    $shuffleString = $alphaString . $numberString;
                }

                $randomString  = substr(str_shuffle($shuffleString), 0, $lengthChars);

                return $randomString;
            }
        }

    }
