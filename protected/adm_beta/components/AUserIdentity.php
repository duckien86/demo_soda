<?php

    /**
     * class Backend User Indentity
     *
     * @author thaitv
     */
    class AUserIdentity extends CUserIdentity
    {
        private $_id;
        private $_group_id;

        /**
         * Authenticates a user.
         *
         * @return boolean whether authentication succeeds.
         */
        public function authenticate()
        {
            $user = ASystemUser::model()->find('LOWER(username)=?', array(strtolower($this->username)));
            //var_dump($user); exit();
            if ($user === NULL)
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            else if (!$user->validatePassword($this->password, Yii::app()->params->hashkey))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id       = $user->id;
                $this->_group_id = $user->group_id;
                $this->username  = $user->username;
                $this->setState('role', $user->group_id);
                $this->setState('cp_code', $user->cp_code);
                $this->setState('username', $user->username);
                $this->errorCode = self::ERROR_NONE;
                //update login

            }

            return $this->errorCode == self::ERROR_NONE;
        }

        /**
         * @return integer the ID of the user record
         */
        public function getId()
        {
            return $this->_id;
        }
    }

?>