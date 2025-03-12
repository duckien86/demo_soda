<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class AOtpForm extends CFormModel
    {
        public $otp;
        public $order_id;


        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {

            return array(
                // otp are required
                array('otp', 'required'),
                array('otp', 'authenticate'),
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'otp' => 'Mã xác nhận',
            );
        }

        /**
         * Authenticates the otp
         * This is the 'authenticate' validator as declared in rules().
         */
        public function authenticate($attribute, $params)
        {
            if (!$this->hasErrors()) {
                $user = AOrders::model()->findAllByAttributes(array('id' => $this->order_id, 'otp' => $this->$attribute));
                if (!$user) {
                    $this->addError($attribute,
                        'Bạn nhập sai số xác minh! Vui lòng nhập lại');
                }
            }
        }


    }
