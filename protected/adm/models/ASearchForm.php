<?php

    class ASearchForm extends CFormModel
    {
        public $prefix_msisdn;
        public $suffix_msisdn;
        public $msisdn_type;
        public $msisdn_status;
        public $stock_id;

        /**
         * Declares the validation rules.
         */
        public function rules()
        {
            return array(
                array('suffix_msisdn', 'required'),
                array('prefix_msisdn, suffix_msisdn, msisdn_type, msisdn_status, stock_id', 'length', 'max' => 255)
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
                'prefix_msisdn' => Yii::t('adm/label', 'prefix_msisdn'),
                'suffix_msisdn' => Yii::t('adm/label', 'suffix_msisdn'),
                'msisdn_type'   => Yii::t('adm/label', 'msisdn_type'),
                'msisdn_status' => Yii::t('adm/label', 'msisdn_status'),
                'stock_id'      => Yii::t('adm/label', 'stock_id'),
            );
        }


        public static function getListMsisdnPrefixBySource($source){
            $data = array();
            if(empty($source)){
                $data = array(
                    '8491'  => '091',
//                    '8494'  => '094',
//                    '84123' => '0123',
//                    '84124' => '0124',
//                    '84125' => '0125',
//                    '84127' => '0127',
//                    '84129' => '0129',
                    '8488' => '088',
                    '8485' => '085',
                    '8482' => '082',
                );
            }else if($source == 'toanquoc'){
                $data = array(
                    '8491' => '091',
                    '8494' => '094',
                    '8488' => '088',
                    '8485' => '085',
                    '8484' => '084',
                    '8483' => '083',
                    '8482' => '082',
                    '8481' => '081',
                );
            }
            return $data;
        }
    }