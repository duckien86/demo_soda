<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class WLoginForm extends CFormModel
    {
        public $username;
        public $password;
        public $rememberMe;

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
                array('username, password', 'required', 'message' => Yii::t('common/LoginForm', 'error_field_blank')),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
//                array('password', 'authenticate'),
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'username'   => Yii::t('common/LoginForm', 'username'),
                'password'   => Yii::t('common/LoginForm', 'password'),
                'rememberMe' => Yii::t('common/LoginForm', 'rememberMe'),
            );
        }


        /**
         * Logs in the user using the given username and password in the model.
         *
         * @return boolean whether login is successful
         */
        public function login($t = '')
        {
            $user = NULL;

            if ($this->_identity === NULL) {
                $this->_identity = new WUserIdentity($this->username, $this->password);
                $user            = $this->_identity->authenticate($t);
            } else {
                $user = $this->_identity->authenticate();
            }
            if ($user) {
//                $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
//                Yii::app()->user->login($this->_identity, $duration);

                return $user;
            } else
                return FALSE;
        }

        /**
         * auto login with msisdn
         *
         * @return bool|null
         */
        public function loginWithMsisdn()
        {
            $user = NULL;
            if ($this->_identity === NULL) {
                $this->_identity = new WUserIdentity($this->username, $this->password);
                $user            = $this->_identity->authenticateWithMsisdn();
            } else {
                $user = $this->_identity->authenticateWithMsisdn();
            }

            if ($user) {
                $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
                Yii::app()->user->login($this->_identity, $duration);

                return $user;
            } else
                return FALSE;
        }


    }
