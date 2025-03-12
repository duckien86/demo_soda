<?php

    class SearchOrderForm extends WOrders
    {
        public $from_date;
        public $to_date;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                array('id, phone_contact', 'required', 'on' => 'search_order'),
                array('id, phone_contact', 'length', 'max' => 255),
                array('from_date, to_date', 'safe'),
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
                'id'            => Yii::t('web/portal', 'order_id'),
                'from_date'     => Yii::t('web/portal', 'from_date'),
                'to_date'       => Yii::t('web/portal', 'to_date'),
                'phone_contact' => Yii::t('web/portal', 'phone_contact'),
            );
        }
    }