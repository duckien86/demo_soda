<?php

    class WTokenLinks extends TokenLinks
    {
        const STATUS_SUCCESS = 10;
        const SEND_SMS       = 'sms';
        const SEND_EMAIL     = 'email';
        const SEND_OTHER     = 'other';

        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
