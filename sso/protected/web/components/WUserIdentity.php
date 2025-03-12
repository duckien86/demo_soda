<?php

    /**
     * UserIdentity represents the data needed to identity a user.
     * It contains the authentication method that checks if the provided
     * data can identity the user.
     */
    class WUserIdentity extends CUserIdentity
    {
        public $phone;

        /**
         * Authenticates a user.
         * The example implementation makes sure if the username and password
         * are both 'demo'.
         * In practical applications, this should be changed to authenticate
         * against some persistent user identity storage (e.g. database).
         *
         * @return boolean whether authentication succeeds.
         */
        public function authenticate($t = '')
        {


            $criteria            = new CDbCriteria();
            $start_time          = date('h:i:s');
            $criteria->condition = '(username=:username OR phone =:phone)';
            $criteria->params    = array(':phone' => CFunction_MPS::makePhoneNumberStandard($this->username), ':username' => $this->username);
            $users               = WUsers::model()->find($criteria);
            if ($t == 4) {
                $end_time = date('h:i:s');
                CVarDumper::dump($start_time, 10, TRUE);
                CVarDumper::dump($end_time, 10, TRUE);
                die();
            }
            if ($users == NULL || !CPasswordHelper::verifyPassword($this->password, $users->password)) {

                $this->errorCode = self::ERROR_USERNAME_INVALID;
            } else {
                $this->errorCode = self::ERROR_NONE;
            }

            if ($this->errorCode == self::ERROR_NONE) {

                return $users;
            }

            return !$this->errorCode;
        }

        /*Add more information: SignUp,LoginFacebookSuccess,LoginGplusSuccess*/
        public function authenticateSetState()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = '(email=:email OR msisdn=:msisdn)';
            $criteria->params    = array('email' => $this->username, 'msisdn' => $this->username);
            $users               = WCustomers::model()->find($criteria);

            if ($users) {
                $this->setState('user_id', $users->id);
                $this->setState('msisdn', $users->msisdn);
                $this->setState('credit', intval($users->credit));

                return $users;
            } else return FALSE;
        }

        /**
         * auto login with msisdn
         *
         * @return bool|static
         */
        public function authenticateWithMsisdn()
        {
            // login by username
            $criteria            = new CDbCriteria();
            $criteria->condition = '(msisdn=:msisdn)';
            $criteria->params    = array('msisdn' => $this->username);
            $users               = WCustomers::model()->find($criteria);

            if ($users) {
                $this->setState('user_id', $users->id);
                $this->setState('msisdn', $users->msisdn);
                $this->setState('credit', intval($users->credit));
//                $this->setState('avatar', $users->avatar);
//                $this->setState('full_name', $users->first_name. ' '. $users->last_name);
//                $this->setState('gold_point', $users->eBank->gold_point);
//                $this->setState('silver_point', $users->eBank->silver_point);
                return $users;

            } else return FALSE;
        }
    }