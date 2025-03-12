<?php

    /**
     * UserIdentity represents the data needed to identity a user.
     * It contains the authentication method that checks if the provided
     * data can identity the user.
     */
    class WUserIdentity extends CUserIdentity
    {
        /**
         * Authenticates a user.
         * The example implementation makes sure if the username and password
         * are both 'demo'.
         * In practical applications, this should be changed to authenticate
         * against some persistent user identity storage (e.g. database).
         *
         * @return boolean whether authentication succeeds.
         */
        public function authenticate()
        {
            if ($this->username != '' && $this->password != '') {

                $criteria = new CDbCriteria();
                $criteria->compare('username', $this->username);
                $users = WCustomers::model()->find($criteria);
                if ($users == NULL || !CPasswordHelper::verifyPassword($this->password, $users->password)) {
                    $this->errorCode = self::ERROR_USERNAME_INVALID;
                } else {
                    $this->errorCode = self::ERROR_NONE;
                }

                return !$this->errorCode;
            } else {
                $result = FALSE;
            }

            return $result;
        }

        /**
         * Authenticates a user.
         * The example implementation makes sure if the username and password
         * are both 'demo'.
         * In practical applications, this should be changed to authenticate
         * against some persistent user identity storage (e.g. database).
         *
         * @return boolean whether authentication succeeds.
         */
        public function authenticateSSO()
        {

            if ($this->username != '') {
                $criteria = new CDbCriteria();
                $criteria->compare('username', $this->username);
                $users = WCustomers::model()->find($criteria);

                if ($users) {
                    $this->setState('customer_id', $users->id);
                    $this->setState('username', $users->username);
                    $this->setState('customer_type', $users->customer_type);
                    $this->setState('sso_id', $users->sso_id);
                    $this->setState('is_admin', $users->level);
                    $sim_freedoo = '';
                    if ($users->customer_type >= 1) {
                        $sim = WSim::model()->find('msisdn=:msisdn AND status=:status', array(
                            ':msisdn' => CFunction::makePhoneNumberBasic($users->phone),
                            ':status' => WCustomers::SIM_FREEDOO,
                        ));

                        if ($sim) {
                            $sim_freedoo = WCustomers::SIM_FREEDOO;
                        }
                    }
                    $this->setState('sim_freedoo', $sim_freedoo);
                    $result = TRUE;
                } else {
                    $result = FALSE;
                }
            } else {
                $result = FALSE;
            }

            return $result;
        }

        /**
         * auto login with msisdn
         *
         * @return CActiveRecord
         */
        public function authenticateWithMsisdn()
        {
            // login by phone
            $criteria            = new CDbCriteria();
            $criteria->condition = '(phone=:phone)';
            $criteria->params    = array('phone' => $this->username);
            $users               = WCustomers::model()->find($criteria);

            if ($users) {
                $this->setState('customer_id', $users->id);
                $this->setState('username', $users->username);
                $this->setState('customer_type', $users->customer_type);
                $this->setState('sso_id', $users->sso_id);
                $this->setState('is_admin', $users->level);
                $sim_freedoo = '';
                if ($users->customer_type >= 1) {
                    $sim = WSim::model()->find('msisdn=:msisdn AND status=:status', array(
                        ':msisdn' => CFunction::makePhoneNumberBasic($users->phone),
                        ':status' => WCustomers::SIM_FREEDOO,
                    ));

                    if ($sim) {
                        $sim_freedoo = WCustomers::SIM_FREEDOO;
                    }
                }
                $this->setState('sim_freedoo', $sim_freedoo);
            }

            return $users;
        }
    }