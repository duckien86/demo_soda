<?php

    class CommonOrdersData
    {
        public $orders;
        public $order_details;
        public $order_state;
        public $sim;
        public $package;
        public $package_flexible;
        public $card;
        public $session_cart;
        public $operation;
        public $sim_raw_data;
        public $change_sim_type = FALSE;
        public $otp_form;
        public $package_sim_kit;

        public $html_order;
        public $url = '';

        const OPERATION_BUYSIM  = 'buysim';
        const OPERATION_TOPUP   = 'topup';
        const OPERATION_BUYCARD = 'buycard';

        function __construct()
        {
            if (!Yii::app()->user->isGuest && Yii::app()->user->username == 'minhphuong') {
                $this->url = $GLOBALS['config_common']['api']['hostname_beta'];
            } else {
                $this->url = $GLOBALS['config_common']['api']['hostname'];
            }
        }

        public function setCommonModel()
        {
            if ($this->orders) {
                $orders             = new Orders();
                $orders->attributes = $this->orders->attributes;
                $orders->active_cod = $this->orders->active_cod;//active payment method cod
                $this->orders       = $orders;
            }

            if ($this->sim) {
                $sim             = new Sim();
                $sim->attributes = $this->sim->attributes;
                $sim->term       = $this->sim->term;
                $sim->price_term = $this->sim->price_term;
                $sim->raw_data   = $this->sim->raw_data;
                $this->sim       = $sim;
            }
        }
    }