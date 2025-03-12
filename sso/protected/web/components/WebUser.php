<?php

    /**
     * Add information to Yii::app()->user by extending CWebUser
     */
    class WebUser extends CWebUser
    {
        private $_model;

        //  private $_model;
        public function init()
        {

            parent::init();
        }

        /**
         * get Msisdn
         */
        public function getMsisdn()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->msisdn;
        }

        /**
         * get username
         */
        public function getUsername()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->username;
        }

        /**
         * get password
         */
        public function getPassword()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->password;
        }

        /**
         * get Email
         */
        public function getEmail()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->email;
        }

        /**
         * get birthday
         */
        public function getBirthday()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->birthday;
        }

        /**
         * get address
         */
        public function getAddress()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->address;
        }

        /**
         * get package_code
         */
        public function getPackage_Code()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->package_code;
        }

        /**
         * get create_time
         */
        public function getCreate_Time()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->create_time;
        }

        /**
         * get last_update
         */
        public function getLast_Update()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->last_update;
        }

        /**
         * get expire_date
         */
        public function getExpire_Date()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->expire_date;
        }

        /**
         * get nation
         */
        public function getNation()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->nation;
        }

        /**
         * get languages_id
         */
        public function getLanguages_Id()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->languages_id;
        }

        /**
         * get token_key
         */
        public function getToken_Key()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->token_key;
        }

        /**
         * get full_name
         */
        public function getFull_Name()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->full_name;
        }

        /**
         * get extra_info
         */
        public function getExtra_Info()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->extra_info;
        }

        /**
         * get status
         */
        public function getStatus()
        {
            $users = $this->loadUser(Yii::app()->user->id);

            return $users->status;
        }

        /**
         * Load user model.
         */
        protected function loadUser($id = NULL)
        {
            if ($this->_model === NULL) {
                if ($id !== NULL)
                    $this->_model = WCustomers::model()->findByAttributes(array('msisdn' => $id));
            }

            return $this->_model;
        }
    }