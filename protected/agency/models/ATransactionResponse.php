<?php

    /**
     * This is the model class for table "{{transaction_response}}".
     *
     * The followings are the available columns in table '{{transaction_response}}':
     *
     * @property string  $order_id
     * @property string  $partner
     * @property string  $payment_method
     * @property string  $transaction_id
     * @property string  $request
     * @property string  $response
     * @property string  $create_date
     * @property string  $note
     * @property integer $status
     * @property string  $request_data_type
     * @property string  $response_data_type
     * @property string  $endpoint
     */
    class ATransactionResponse extends TransactionResponse
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
                array('order_id, partner, payment_method, transaction_id, request_data_type, response_data_type', 'length', 'max' => 255),
                array('response, note, endpoint', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('order_id, partner, payment_method, transaction_id, request, response, create_date, note, status, request_data_type, response_data_type, endpoint', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array();
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'order_id'           => 'Order',
                'partner'            => 'Partner',
                'payment_method'     => 'Payment Method',
                'transaction_id'     => 'Transaction',
                'request'            => 'Request',
                'response'           => 'Response',
                'create_date'        => 'Create Date',
                'note'               => 'Note',
                'status'             => 'Status',
                'request_data_type'  => 'Request Data Type',
                'response_data_type' => 'Response Data Type',
                'endpoint'           => 'Endpoint',
            );
        }

        /**
         * Retrieves a list of models based on the current search/filter conditions.
         *
         * Typical usecase:
         * - Initialize the model fields with values from filter form.
         * - Execute this method to get CActiveDataProvider instance which will filter
         * models according to data in model fields.
         * - Pass data provider to CGridView, CListView or any similar widget.
         *
         * @return CActiveDataProvider the data provider that can return the models
         * based on the search/filter conditions.
         */
        public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('partner', $this->partner, TRUE);
            $criteria->compare('payment_method', $this->payment_method, TRUE);
            $criteria->compare('transaction_id', $this->transaction_id, TRUE);
            $criteria->compare('request', $this->request, TRUE);
            $criteria->compare('response', $this->response, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('request_data_type', $this->request_data_type, TRUE);
            $criteria->compare('response_data_type', $this->response_data_type, TRUE);
            $criteria->compare('endpoint', $this->endpoint, TRUE);

            $criteria->addCondition("t.order_id IN (SELECT order_id FROM tbl_agency_order WHERE agency_id = '".Yii::app()->user->agency."')");

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return TransactionResponse the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getPaymentMethod($payment_method)
        {
            $data = array(
                1 => 'QR CODE',
                2 => 'THẺ ATM NỘI ĐỊA',
                3 => 'THẺ ATM QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK QUỐC TẾ',
                7 => 'VNPAY',
                8 => 'OLPAY',
                9 => 'VIETINBANK NỘI ĐỊA'
            );


            return isset($data[$payment_method]) ? $data[$payment_method] : '';
        }
    }
