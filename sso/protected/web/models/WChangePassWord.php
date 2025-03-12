<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class WChangePassWord extends CFormModel
    {
        public  $old_password;
        public  $new_password;
        public  $re_new_password;
        public  $phone;
        public  $user_id;
        private $_identity;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('old_password, new_password, re_new_password', 'required'),
                array('phone, user_id', 'required'),
                array('phone', 'authenticateMsisdn'),
                array('old_password', 'checkExist'),
                array('re_new_password', 'compare', 'compareAttribute' => 'new_password'),
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'username'        => 'Tên đăng nhập',
                'password'        => 'Mật khẩu',
                'email'           => 'Email',
                'verifyCode'      => 'Mã xác nhận',
                'phone'           => 'Số điện thoại',
                'old_password'    => 'Mật khẩu cũ',
                'new_password'    => 'Mật khẩu mới',
                're_new_password' => 'Mật khẩu xác nhận',
            );
        }


        public function checkExist($attribute, $params)
        {
            if ($this->$attribute) {
                $password = CPasswordHelper::hashPassword($this->$attribute);
                $user     = Users::model()->findByAttributes(array('phone' => $this->phone, 'id' => $this->user_id));
                if (!$user && !CPasswordHelper::verifyPassword($this->$attribute, $user->password)) {
                    $this->addError('old_password', 'Mật khẩu của bạn chưa đúng!!');
                }
            }
        }

        public function authenticateMsisdn($attribute, $params)
        {
            if ($this->$attribute) {
                $this->$attribute = self::makePhoneNumberStandard($this->$attribute);
                if (preg_match("/^84[0-9]{9,11}$/i", $this->$attribute) === 0) {
                    $this->addError($attribute,
                        'Số điện thoại không đúng định dạng! Vui lòng nhập lại!');

                }
                $user = Users::model()->findByAttributes(array('phone' => $this->$attribute, 'id' => $this->user_id));
                if (!$user) {
                    $this->addError($attribute,
                        'Đây không phải số của bạn!');
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


    }
