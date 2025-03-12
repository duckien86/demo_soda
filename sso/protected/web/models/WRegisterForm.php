<?php

    /**
     * RegisterForm class.
     * RegisterForm is the data structure for keeping
     * user registration form data. It is used by the 'register' action of 'SiteController'.
     */
    class WRegisterForm extends CFormModel
    {
        public $username;
        public $password;
        public $email;
        public $fullname;
        public $confirm_password;
        public $verifyCode;
        public $phone;
        public $agree;

        private $_identity;


        /**
         * Declares the validation rules.
         * The rules state that username, password & email are required,
         * and username & email needs to be unique.
         */
        public function rules()
        {

            return array(
                // username and password are required
                array('username, password, email, phone', 'required'),
                array('username', 'length', 'min' => 5, 'max' => 25, 'tooShort' => '{attribute} không được ngắn hơn 5 ký tự', 'tooLong' => '{attribute} không được dài quá 25 ký tự'),
                array('username', 'match', 'pattern' => '/^([a-z0-9_\.-])+$/', 'message' => "Chỉ chấp nhận từ a->z, 0->9 và ký tự _ , ký tự - và dấu chấm"),
                array('password, fullname, confirm_password', 'length', 'max' => 128),
                array('password, confirm_password', 'length', 'min' => 8),
                array(
                    'password',
                    'match',
                    'not'     => TRUE,
                    'pattern' => '/[^0-9a-zA-Z]/',
                    'message' => 'Mật khẩu không chứa ký tự đặc biệt!',
                ),
                array('password', 'authenticatePassword'),
                array('agree', 'authenticateRule'),
                array('phone', 'authenticateMsisdn'),
                array('phone', 'unique', 'className' => 'Users', 'attributeName' => 'phone', 'message' => 'Số điện thoại này đã được đăng ký!'),
                array('confirm_password', 'compare', 'compareAttribute' => 'password'),
                array('email', 'email'),
                array('email', 'unique', 'className' => 'Users', 'attributeName' => 'email', 'message' => 'Email đã được sử dụng!'),
                array('username', 'unique', 'className' => 'Users', 'attributeName' => 'username', 'message' => 'Tên đăng nhập đã được sử dụng!'),
            );

        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'username'   => 'Tên đăng nhập',
                'password'   => 'Mật khẩu',
                'email'      => 'Email',
                'verifyCode' => 'Mã xác nhận',
                'phone'      => 'Số điện thoại',
            );
        }

        public function validate($attributes = NULL, $clearErrors = TRUE, $recreateValidators = TRUE)
        {

            if ($clearErrors)
                $this->clearErrors();

            if ($this->beforeValidate()) {
                foreach ($this->getValidators(NULL, $recreateValidators) as $validator)
                    $validator->validate($this, $attributes);
                $this->afterValidate();

                return !$this->hasErrors();
            } else
                return FALSE;
        }

        public function getValidators($attribute = NULL, $recreateValidators = FALSE)
        {
            if (parent::getValidators() === NULL || $recreateValidators) {
                $_validators = $this->createValidators();
            } else
                $_validators = parent::getValidators();

            $validators = array();
            $scenario   = $this->getScenario();
            foreach ($_validators as $validator) {
                if ($validator->applyTo($scenario)) {
                    if ($attribute === NULL || in_array($attribute, $validator->attributes, TRUE))
                        $validators[] = $validator;
                }
            }

            return $validators;
        }

        public function authenticateMsisdn($attribute, $params)
        {
            if ($this->$attribute) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);
                if (preg_match("/^84[0-9]{9,11}$/i", $this->$attribute) === 0) {
                    $this->addError($attribute,
                        'Số điện thoại không đúng định dạng!');
                }
            }
        }

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

        public function authenticateRule($attribute, $params)
        {
            if ($this->$attribute) {
                if ($this->$attribute != 1) {
                    $this->addError($attribute,
                        "Bạn phải đồng ý với điều khoản của Vinaphone!");
                }
            }
        }

        public function authenticatePassword($attribute, $params)
        {
            if ($this->$attribute) {
                if (strlen($this->$attribute) < 8) {
                    $this->addError($attribute,
                        'Mật khẩu tối thiểu 8 ký tự!');
                } else {
                    if (preg_match("/(([0-9]{1,30})([A-Za-z]{1,30}))|(([A-Za-z]{1,30})([0-9]{1,30}))$/", $this->$attribute) === 0) {
                        $this->addError($attribute,
                            'Mật khẩu phải bao gồm chữ và số!');
                    }
                }

            }
        }

        /**
         * @param $msisdn
         * Check mạng đầu số
         *
         * @return mixed|string
         */
        public static function detectTelcoByMsisdn($msisdn)
        {
            $shortcode_telco = array(
                'VIETTEL'      => array('96', '97', '98', '162', '163', '164', '165', '166', '167', '168', '169',),
                'MOBIFONE'     => array('90', '93', '120', '121', '122', '126', '128',),
                'VINAPHONE'    => array('91', '94', '123', '124', '125', '127', '129', '88'),
                'VIETNAMOBILE' => array('92', '188',),
                'BEELINE'      => array('993', '994', '995', '996', '99',),
                'SFONE'        => array('95',),
            );
            $return          = 'UNKNOW_TELCO';
            if ($msisdn) {

//                $msisdn = self::makePhoneNumberStandard($msisdn);

                if (preg_match("/^84[0-9]{9,11}$/i", $msisdn) == TRUE) {
                    //lấy 3 số sau 84
                    $pre_code = preg_replace('/^84(\d\d\d).*/', '$1', $msisdn);

                    //ktra chính xác sđt đầu 08,09 = 10 số
                    if ((substr($pre_code, 0, 1) == 8 || substr($pre_code, 0, 1) == 9) && strlen($msisdn) >= 10) {
                        $pre_code = substr($pre_code, 0, 2);
                    }

                    $arr_by_short_code = array();
                    foreach ($shortcode_telco as $telco => $row) {
                        foreach ((array)$row as $srow) {
                            $arr_by_short_code[$srow] = $telco;
                        }
                    }
                    $return = isset($arr_by_short_code[$pre_code]) ? $arr_by_short_code[$pre_code] : $return;
                }
            }

            return $return;
        }


    }