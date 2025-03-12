<?php

    class Cod
    {
        public $end_point;
        public $vpc_Version;
        public $vpc_Command;
        public $vpc_AccessCode;
        public $vpc_MerchTxnRef;
        public $vpc_Merchant;
        public $vpc_OrderInfo;
        public $vpc_Amount;
        public $vpc_ReturnURL;
        public $vpc_BackURL;
        public $vpc_Locale;
        public $vpc_CurrencyCode;
        public $vpc_TicketNo;
        public $vpc_PaymentGateway;
        public $vpc_CardType;
        public $vpc_SecureHash;
        public $secureSecret;

        public function __construct()
        {
            $this->end_point = Yii::app()->controller->createUrl('checkout/verifyTokenKey');
        }

        /**
         * @param $ordersObj
         * @param $simObj
         *
         * @return array
         */
        public function createRequestUrl($ordersObj, $simObj)
        {
            $token_key = $ordersObj->otp;
            $msg       = '';
            if ($token_key) {
                Yii::app()->session['verify_number'] = 1;
                Yii::app()->session['time_reset']    = time();
                Yii::app()->session['token_key']     = $token_key;

                $msg = 'Mã OTP: ' . Yii::app()->session['token_key'];//test
                //send MT token key
                $mt_content = Yii::t('web/mt_content', 'otp_sim_cod', array(
                    '{token_key}' => $token_key,
                    '{msisdn}'    => $simObj->msisdn,
                ));
                if (YII_DEBUG == TRUE) {
                    $urlRequest = $this->end_point;
                } else {
                    if (OtpForm::sentMtVNP($ordersObj->phone_contact, $mt_content, 'cod')) {
                        //send MT success
                        $urlRequest = $this->end_point;
                    } else {
                        $urlRequest = Yii::app()->controller->createUrl('checkout/message', array('t' => 9));
                    }
                }
            } else {//get token key fail
                $msg        = Yii::t('web/portal', 'get_token_key_fail');
                $urlRequest = Yii::app()->controller->createUrl('checkout/message', array('t' => 8));
            }

            return array(
                'urlRequest' => $urlRequest,
                'msg'        => $msg,
            );
        }
    }