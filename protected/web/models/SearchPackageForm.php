<?php

    class SearchPackageForm extends CFormModel
    {
        public $key;
        public $msisdn;
        public $captcha;

        public $sortType;
        public $sortOrder;
        public $searchType;

        const SORT_PRICE         = 1;
        const SORT_SMS_INTERNAL  = 2;
        const SORT_SMS_EXTERNAL  = 3;
        const SORT_CALL_INTERNAL = 4;
        const SORT_CALL_EXTERNAL = 5;
        const SORT_DATA          = 6;

        const INFO_PROMOTION = 1;
        const INFO_PACKAGE_ALO = 2;
        const INFO_PACKAGE_ALO_MORE = 3;
        const INFO_PACKAGE_DATA = 4;
        const INFO_PACKAGE_DATA_MORE = 5;
        const INFO_MY_PACKAGE = 6;

        const SORT_DEFAULT = 'DEFAULT';
        const SORT_ASC     = 'ASC';
        const SORT_DESC    = 'DESC';

        public function rules()
        {
            return array(
                array('msisdn', 'required', 'on' => 'search_by_msisdn', 'message' => Yii::t('web/portal', 'error_msisdn_input')),
                array('msisdn', 'msisdn_validation', 'on' => 'search_by_msisdn'),
                array('msisdn', 'detectByTelco', 'on' => 'search_by_msisdn'),
                array('key', 'length', 'max' => 255),
                array('key, sortType, sortOrder, searchType', 'safe'),
            );
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            if ($this->msisdn) {
                $input = $this->msisdn;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('msisdn', Yii::t('web/portal', 'msisdn_validation'));
                }
            }

            return TRUE;
        }

        /**
         * @return bool
         */
        public function detectByTelco()
        {
            if ($this->msisdn) {
                $telco = Utils::detectTelcoByMsisdn($this->msisdn);
                if ($telco != WOrders::VINAPHONE_TELCO) {
                    $this->addError('msisdn', Yii::t('web/portal', 'error_msisdn_vinaphone'));
                }
            }

            return TRUE;
        }

        public static function getListSortType()
        {
            return array(
                ['id' => self::SORT_PRICE, 'name' => Yii::t('web/portal', 'package_sort_price')],
                ['id' => self::SORT_SMS_INTERNAL, 'name' => Yii::t('web/portal', 'package_sort_sms_internal')],
                ['id' => self::SORT_SMS_EXTERNAL, 'name' => Yii::t('web/portal', 'package_sort_sms_external')],
                ['id' => self::SORT_CALL_INTERNAL, 'name' => Yii::t('web/portal', 'package_sort_call_internal')],
                ['id' => self::SORT_CALL_EXTERNAL, 'name' => Yii::t('web/portal', 'package_sort_call_external')],
                ['id' => self::SORT_DATA, 'name' => Yii::t('web/portal', 'package_sort_data')],

            );
        }

        public static function getListSortOrder()
        {
            return array(
                ['id' => self::SORT_DEFAULT, 'name' => Yii::t('web/portal', 'default')],
                ['id' => self::SORT_ASC, 'name' => Yii::t('web/portal', 'asc')],
                ['id' => self::SORT_DESC, 'name' => Yii::t('web/portal', 'desc')],
            );
        }


        /*
         * Tra cứu thông tin gói cước
         */

        public static function getListSearch(){
            return array(
                ['id' => self::INFO_PROMOTION, 'name' => 'Khuyến mại, Khuyến nghị'],
                ['id' => self::INFO_PACKAGE_ALO, 'name' => 'Gói Alo'],
                ['id' => self::INFO_PACKAGE_ALO_MORE, 'name' => 'Gói Alo (more)'],
                ['id' => self::INFO_PACKAGE_DATA, 'name' => 'Gói DATA'],
                ['id' => self::INFO_PACKAGE_DATA_MORE, 'name' => 'Gói DATA (more)'],
                ['id' => self::INFO_MY_PACKAGE, 'name' => 'Thông tin gói cước của bạn'],

            );
        }
    }