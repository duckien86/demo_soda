<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class UserLoginCskh extends CFormModel
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
                    if (isset($users->username) && isset($users->phone) && !empty($users->phone)) {
                        $users->phone = self::makePhoneNumberStandard($users->phone);
                        if ($users->phone != $this->$attribute) {
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
            $mt_content = Yii::t('cskh/mt_content', 'otp_login_cskh', array(
                '{otp_login}' => $otp,
            ));
            $otp_form   = new OtpForm();
            if ($otp_form->sentMtVNP($msisdn, $mt_content, 'login_cskh')) {
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
