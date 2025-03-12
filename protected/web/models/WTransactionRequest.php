<?php

    class WTransactionRequest extends TransactionRequest
    {
        const REQUEST_SUCCESS = 1;
        const REQUEST_FAIL    = 0;

        const NAPAS               = 'napas';
        const NAPAS_QUERY_DR      = 'napas_query_dr';
        const VIETINBANK          = 'vietinbank';
        const VIETINBANK_QUERY_DR = 'vietinbank_query_dr';
        const VNPT_PAY            = 'vnpt_pay';
        const VNPT_PAY_QUERY_DR   = 'vnpt_pay_query_dr';
        const TIKI                = 'tiki';

        const TYPE_JSON        = 'json';
        const TYPE_XML         = 'xml';
        const TYPE_TEXT        = 'text';
        const TYPE_QUERY_PARAM = 'query_param';
        const TYPE_CSV         = 'csv';

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, partner, payment_method, transaction_id, request, create_date', 'required'),
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('order_id, partner, payment_method, transaction_id', 'length', 'max' => 255),
                array('request_data_type, response_data_type', 'length', 'max' => 20),
                array('response, note, endpoint', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('order_id, partner, payment_method, transaction_id, request, response, create_date, note, status, request_data_type, response_data_type, endpoint', 'safe', 'on' => 'search'),
            );
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return TransactionRequest the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param        $partner
         * @param        $orders
         * @param        $transaction_id
         * @param        $endpoint
         * @param        $request
         * @param string $response
         * @param string $request_data_type
         * @param string $response_data_type
         * @param string $status
         * @param string $note
         * @param array  $logMsg
         *
         * @return bool
         */
        public static function writeLog($partner, $orders, $transaction_id, $endpoint, $request, $response = '', $request_data_type = '', $response_data_type = '', $status = '', $note = '', &$logMsg = array())
        {
            $model = WTransactionRequest::model()->find('order_id=:order_id AND payment_method=:payment_method AND partner=:partner', array(
                ':order_id'       => $orders->id,
                ':payment_method' => $orders->payment_method,
                ':partner'        => $partner,
            ));
            if (!$model) {
                $model = new WTransactionRequest();
            }
            $model->order_id           = $orders->id;
            $model->partner            = $partner;
            $model->payment_method     = $orders->payment_method;
            $model->transaction_id     = $transaction_id;
            $model->request            = $request;
            $model->response           = $response;
            $model->create_date        = date('Y-m-d H:i:s', time());
            $model->status             = $status;
            $model->note               = $note;
            $model->request_data_type  = $request_data_type;
            $model->response_data_type = $response_data_type;
            $model->endpoint           = $endpoint;
            if ($model->validate() && $model->save()) {

                return TRUE;
            } else {
                $logMsg[] = array(CJSON::encode($model->getErrors()), 'writeLog tbl_transaction_response error:' . __LINE__, 'E', time());
            }

            return FALSE;
        }

        /**
         * @param $order_id
         *
         * @return array|mixed|null
         */
        public static function getRequestByOrderId($order_id)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'order_id = :order_id  AND (partner=:partner_napas OR partner=:partner_vtb)';
            $criteria->params    = array(
                ':order_id'      => $order_id,
                ':partner_napas' => self::NAPAS,
                ':partner_vtb'   => self::VIETINBANK,
            );
            $criteria->order     = 'create_date ASC';

            return self::model()->findAll($criteria);
        }

        /**
         * @param       $order_id
         * @param       $payment_method
         * @param array $logMsg
         *
         * @return bool
         */
        public static function getTransactionRequestQueryDr($order_id, &$payment_method, &$logMsg = array())
        {
            $orders = WOrders::model()->find('id=:id', array(':id' => $order_id));
            if ($orders) {
                //get all transaction request
                $transaction_req = WTransactionRequest::getRequestByOrderId($order_id);
                if ($transaction_req) {
                    foreach ($transaction_req as $request) {
                        //get all transaction response
                        $response = WTransactionResponse::getResponseByOrderId($order_id, $request->payment_method);
                        if ($response) {//response
                            if ($response->status == WTransactionRequest::REQUEST_SUCCESS) {//transaction success
                                $payment_method = $response->payment_method;
                                $logMsg[]       = array($payment_method, 'payment_method(tbl_transaction_response):' . __LINE__, 'T', time());

                                return TRUE;
                            }
                        } else {//not response->QueryDr
                            $orders->payment_method = $request->payment_method;//write log tbl_transaction_request
                            switch ($request->payment_method) {
                                case WPaymentMethod::PM_NAPAS_ATM:
                                case WPaymentMethod::PM_NAPAS_INT:
                                    $napas = new Napas();
                                    if ($napas->requestQueryDr($orders, $request, $logMsg)) {
                                        //payment success
                                        $payment_method = $request->payment_method;
                                    }
                                    break;
                                case WPaymentMethod::PM_VNPAY:
                                    break;
                                case WPaymentMethod::PM_VIETINBANK://external
                                    break;
                                case WPaymentMethod::PM_QR_CODE:
                                    break;
                                case WPaymentMethod::PM_VIETIN_ATM://internal
                                    $vietinbank = new Vietinbank();
                                    if ($vietinbank->requestQueryDrVietinAtm($orders, $request, $logMsg)) {
                                        //payment success
                                        $payment_method = $request->payment_method;
                                    }
                                    break;
                                default://cod
                                    break;
                            }
                            if ($payment_method) {
                                $logMsg[] = array($payment_method, 'payment_method(request queryDr):' . __LINE__, 'T', time());

                                return TRUE;
                            }
                        }
                    }
                } else {
                    $logMsg[] = array('tbl_transaction_request not found by order_id: '.$order_id, 'Error found :' . __LINE__, 'T', time());
                }
            } else {
                $logMsg[] = array($order_id, 'orders not found :' . __LINE__, 'T', time());
            }

            return FALSE;
        }
    }
