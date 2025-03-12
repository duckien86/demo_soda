<?php

    class AFTReportForm extends CFormModel
    {
        public $start_date;
        public $end_date;
        public $user_tourist;
        public $status_order;
        public $order_id;
        public $contract_id;
        public $item_id;
        public $province_code;
        public $on_detail;
        public $order_type;

        public $user_tourist_ctv;
        public $order_id_ctv;

        CONST ORDER_NORMAL = 1;
        CONST ORDER_CTV    = 4;

        /**
         * Declares the validation rules.
         * The rules state that username and password are required,
         * and password needs to be authenticated.
         */
        public function rules()
        {
            return array(
                // username and password are required
                array('start_date, end_date', 'required'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                array('start_date, end_date, status_order, user_tourist, contract_id, 
                    order_id, order_type, order_id_ctv, user_tourist_ctv', 'safe'),
                // rememberMe needs to be a boolean
                // password needs to be authenticated
            );
        }

        /**
         * Declares attribute labels.
         */
        public function attributeLabels()
        {
            return array(
                'start_date'        => 'Ngày bắt đầu',
                'end_date'          => 'Ngày kết thúc',
                'status_order'      => 'Trạng thái',
                'user_tourist'      => 'Khách hàng',
                'order_id'          => 'Mã đơn hàng',
                'contract_id'       => 'Mã hợp đồng',
                'item_id'           => 'Sản phẩm',
                'province_code'     => 'TTKD',
                'order_type'        => 'Loại đơn hàng',
                'user_tourist_ctv'  => 'Khách hàng',
                'order_id_ctv'      => 'Mã đơn hàng',
            );
        }


    }


?>