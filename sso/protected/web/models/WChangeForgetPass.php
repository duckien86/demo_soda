<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class WChangeForgetPass extends CFormModel
    {
        public  $new_password;
        public  $re_new_password;
        public  $user_id;
        private $_identity;
        public  $otp;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('new_password, re_new_password', 'required'),
                array('otp', 'checkOtp'),
                array('new_password', 'authenticatePassword'),
                array('re_new_password', 'compare', 'compareAttribute' => 'new_password'),
                array('user_id', 'safe'),
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

        public function authenticatePassword($attribute, $params)
        {
            if ($this->$attribute) {
                if ((preg_match("/[0-9]{8,31}$/i", $this->$attribute) === 1 && preg_match("/[A-Za-z]{8,31}$/i", $this->$attribute) === 0)
                    || (preg_match("/[A-Za-z]{8,31}$/i", $this->$attribute) === 1 && preg_match("/[0-9]{8,31}$/i", $this->$attribute) === 0)
                ) {
                    $this->addError($attribute,
                        'Mật khẩu tối thiểu 8 ký tự, bao gồm chữ và số!');
                }

            }
        }

        public function checkOtp($attribute, $params)
        {

            if ($this->$attribute) {
                if (!empty($this->user_id)) {


                    $users = WUsers::model()->findByAttributes(array('id' => $this->user_id));

                    if ($users) {
                        if (isset($users->otp)) {

                            if ($this->$attribute == $users->otp) {
                                return TRUE;
                            } ELSE {
                                $this->addError($attribute,
                                    'Mã otp chưa chính xác');
                            }
                        }
                    }
                }

            }



        }


    }
