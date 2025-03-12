<?php

    /**
     * LoginForm class.
     * LoginForm is the data structure for keeping
     * user login form data. It is used by the 'login' action of 'SiteController'.
     */
    class RecanForm extends CFormModel
    {
        public $msisdn; //Số thuê bao.


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
                array('msisdn', 'required'),
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'msisdn' => 'Số thuê bao',
            );
        }

       

    }
