<?php

    class WTransactionResponse extends TransactionResponse
    {
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
         * @return WTransactionResponse the static model class
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
            $model = WTransactionResponse::model()->find('order_id=:order_id AND payment_method=:payment_method AND partner=:partner', array(
                ':order_id'       => $orders->id,
                ':payment_method' => $orders->payment_method,
                ':partner'        => $partner,
            ));
            if (!$model) {
                $model = new WTransactionResponse();
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
         * @param $payment_method
         *
         * @return array|mixed|null
         */
        public static function getResponseByOrderId($order_id, $payment_method)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'order_id = :order_id AND payment_method=:payment_method';
            $criteria->params    = array(':order_id' => $order_id, ':payment_method' => $payment_method);
            $criteria->order     = 'create_date ASC';//get oldest

            return self::model()->find($criteria);
        }

        /**
         * @param $create_date
         * @param $payment_method
         *
         * @return static[]
         */
        public static function getListResponseByDate($create_date, $payment_method)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'payment_method=:payment_method AND (DATE_FORMAT(create_date, "%Y-%m-%d")=:create_date) AND status=:status AND partner=:partner';
            $criteria->params    = array(
                ':payment_method' => $payment_method,
                ':create_date'    => $create_date,
                ':status'         => WTransactionRequest::REQUEST_SUCCESS,
                ':partner'        => WTransactionRequest::VIETINBANK
            );

            return self::model()->findAll($criteria);
        }
    }
