<?php

    class CreateSimForm extends CFormModel
    {
        public $serial_number;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                // name, email, subject and body are required
                array('serial_number', 'required'),
//                array('serial_number', 'checkSerial'),
            );
        }

        /**
         * Declares customized attribute labels.
         * If not declared here, an attribute would have a label that is
         * the same as its name with the first letter in upper case.
         */
        public function attributeLabels()
        {
            return array(
                'serial_number' => 'Nhập 10 số serial',
            );
        }


    }