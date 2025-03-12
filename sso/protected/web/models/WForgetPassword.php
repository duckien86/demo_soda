<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class WForgetPassword extends CFormModel
    {
        public  $email;
        public  $phone;
        public  $verifyCode;
        public  $select_box;
        public  $input_text;
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
                array('select_box', 'required'),
                array('input_text', 'checkRequired'),

            );
        }


        public function checkRequired($attribute, $params)
        {
            if ($this->select_box) {
                if ($this->select_box == 'email') {
                    if (empty($this->input_text)) {
                        $this->addError($attribute,
                            'Email không được phép rỗng!');

                        return FALSE;
                    } else if (!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
                        $this->addError($attribute,
                            'Email không đúng định dạng!');
                    }
                } else {
                    if ($this->select_box == 'phone') {
                        if (empty($this->input_text)) {
                            $this->addError($attribute,
                                'Số điện thoại không được phép rỗng!');

                            return FALSE;
                        } else if (!self::authenticateMsisdn($this->input_text)) {
                            $this->addError('input_text',
                                'Số điện thoại không đúng định dạng!');
                        } else {
                            $criteria = new CDbCriteria();
                            $standard_phone = self::makePhoneNumberStandard($this->input_text);
                            $criteria->condition ="phone ='$this->input_text' or phone ='$standard_phone'";
                            $users = WUsers::model()->find($criteria);
                            if (!$users) {
                                $this->addError('input_text',
                                    'Số điện thoại này chưa được đăng ký!');
                            }
                        }
                    }
                }
            }
        }


        public function authenticateMsisdn($attribute)
        {
            if ($attribute) {
                $attribute = self::makePhoneNumberStandard($attribute);
                if (preg_match("/^84[0-9]{9,11}$/i", $attribute) === 0) {
                    return FALSE;
                }
            }

            return TRUE;
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

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'email'      => 'Email',
                'select_box' => 'Loại thông tin',
                'phone'      => 'Số điện thoại',
                'verifyCode' => 'Mã xác nhận',
            );
        }


    }
