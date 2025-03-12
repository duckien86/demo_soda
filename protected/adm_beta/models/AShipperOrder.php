<?php

    /**
     * This is the model class for table "{{shipper_order}}".
     *
     * The followings are the available columns in table '{{shipper_order}}':
     *
     * @property string  $id
     * @property string  $order_id
     * @property string  $shipper_id
     * @property string  $assign_date
     * @property string  $delivery_date
     * @property string  $finish_date
     * @property string  $ship_cost
     * @property string  $assign_by
     * @property string  $note
     * @property integer $status
     * @property integer $last_update
     */
    class AShipperOrder extends ShipperOrder
    {
        public $email;
        public $type_assign;
        public $total_renueve_date;
        public $total_renueve_order; // Tổng doanh thu đơn hàng
        public $total_order; // Tổng đơn hàng.
        public $total_shipper; // Tổng shipper.
        public $total_renueve_shipper; // Tổng shipper.
        public $rose_shipper; // Tổng shipper.

        public $shipper_old;

        const ASSIGN = 0;
        const CANCEL = 1;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('order_id, shipper_id, assign_by, note, order_status', 'length', 'max' => 255),
                array('ship_cost', 'length', 'max' => 10),
                array('assign_date, delivery_date, finish_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, shipper_id, assign_date, delivery_date, last_update, finish_date, ship_cost, assign_by, note, status', 'safe', 'on' => 'search'),
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
                'id'                    => 'ID',
                'order_id'              => 'Order',
                'shipper_id'            => 'Shipper',
                'assign_date'           => 'Assign Date',
                'delivery_date'         => 'Delivery Date',
                'finish_date'           => 'Finish Date',
                'ship_cost'             => 'Ship Cost',
                'assign_by'             => 'Assign By',
                'note'                  => 'Note',
                'status'                => 'Status',
                'last_update'           => 'LastUpdate',
                'total_order'           => 'Tổng đơn hàng',
                'total_shipper'         => 'Tổng shipper',
                'total_renueve_order'   => 'Tổng doanh thu đơn hàng',
                'total_renueve_shipper' => 'Doanh thu shipper',
                'rose_shippe'           => 'Tổng hoa hồng',
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

            $criteria->compare('id', $this->id, TRUE);
            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('shipper_id', $this->shipper_id, TRUE);
            $criteria->compare('assign_date', $this->assign_date, TRUE);
            $criteria->compare('delivery_date', $this->delivery_date, TRUE);
            $criteria->compare('finish_date', $this->finish_date, TRUE);
            $criteria->compare('ship_cost', $this->ship_cost, TRUE);
            $criteria->compare('assign_by', $this->assign_by, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('last_update', $this->last_update);

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
         * @return ShipperOrder the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {

            $criteria = new CDbCriteria();

            $criteria->condition = "id ='" . $this->order_id . "'";

            $data = new CActiveDataProvider('ATraffic', array(
                'criteria' => $criteria,
            ));

//            $email_content = Yii::app()->controller->renderPartial('_assignment_order_shipper', array('order_data' => $data), TRUE);
//            self::sendEmail($this->email, $email_content);

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        public function afterSave()
        {
            // Gửi thông báo nhận đơn hàng.
            $data = self::sendNotify($this->shipper_id, $this->order_id);
            if ($this->shipper_old != '') {
                $data = self::sendNotifyOld($this->shipper_old, $this->order_id, $this->shipper_id);
            }
            // Gửi thông báo nhận đơn hàng ny sms.
            $data_sms = self::sendSMS($this->shipper_id, $this->order_id);
            if ($this->shipper_old != '') {
                $data_sms = self::sendSMSOld($this->shipper_old, $this->order_id);
            }
            parent::afterSave(); // TODO: Change the autogenerated stub
        }

        /**
         * Gửi thông báo nhận hàng.
         *
         * @param $msisdn
         */
        public function sendNotify($shipper_id, $order_id)
        {
            if ($shipper_id) {
                //B1: Khởi tạo dữ liệu.
                $title = $description = "";
                //Lấy token của shipper.
                $token_shipper = AppTblDeviceShipper::model()->findByAttributes(array('user_id' => $shipper_id));
                $shipper       = AShipper::model()->findByAttributes(array('id' => $shipper_id));
                $order         = AOrders::model()->findByAttributes(array('id' => $order_id));

                $title       = "Phân công đơn hàng";
                $description = "Bạn có thông báo đơn hàng mới, mã đơn hàng '$order->id'!";


                // Gửi đơn hàng mới.
                // B2: Phân biệt request device.
                if (stristr($token_shipper->device_os, 'android') != FALSE) { // Device Android
                    $data_key = array(
                        'type'    => 1,
                        'title'   => $title,
                        'message' => $description,
                    );
                    $data     = array(
                        'registration_ids' =>
                            array(
                                0 => $token_shipper->token,
                            ),
                        'data'             => array(
                            'title'       => $title,
                            'description' => $description,
                            'content'     => CJSON::encode($data_key),
                        ),
                    );

                } else {
                    $data = array(
                        'registration_ids' =>
                            array(
                                0 => $token_shipper->token,
                            ),
                        'notification'     =>
                            array(
                                'body'     => $description,
                                'title'    => $title,
                                'type'     => '1',
                                'customId' => $description,
                            ),
                    );
                }

                $logFolder = "shipper/" . date("Y/m");
                $logObj    = SystemLog::getInstance($logFolder);
                $logObj->setLogFile(date('d') . 'send_noti_app' . '.log');
                $start_time = time();
                $logMsg     = array();
                $logMsg[]   = array('Start log send Notify Shipper New: ', '', 'I', $start_time);
                $logMsg[]   = array('Resquest assign order_id: ' . $this->order_id . ' to shipper: ' . $shipper->username, 'Respone:', 'T');
                $logMsg[]   = array('Resquest assign order_id: ' . $data, 'Respone:', 'T');

                $data_header = array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(CJSON::encode($data)),
                    'Authorization: key=AAAAPGdujeo:APA91bHowfma96eSo78gQ5rIlXjS9p9aHmZYEUOdhbexVGgPFZIM_Hy6UemKf3R3AhZkn9KH4T356QVALx90Afa4eavjy95HOIzUQOsHmR0LNwLXtzKo1QnJIStRBZbtCfqmW_t8gglM'
                );
                //Call api thông báo .
                $response = Utils::cUrlPostJson(Yii::app()->params['api_notify_assign_shipper'], CJSON::encode($data), TRUE, 15, $http_status, $data_header);

                //Lưu log gọi api.
                $logMsg[] = array('Data: ' . $response, 'Respone:', 'T');
                $logMsg[] = array('Finish log', 'Finish proccess-' . __LINE__, 'F', time());
                $logObj->processWriteLogs($logMsg);
                header('HTTP/1.1 200 OK');

                return $response;
            }
        }


        /**
         * Gửi thông báo nhận hàng.
         *
         * @param $msisdn
         */
        public function sendNotifyOld($shipper_id, $order_id, $shipper_new_id)
        {
            if ($shipper_id) {
                //B1: Khởi tạo dữ liệu.
                $title = $description = "";
                //Lấy token của shipper.
                $token_shipper = AppTblDeviceShipper::model()->findByAttributes(array('user_id' => $shipper_id));
                $shipper       = AShipper::model()->findByAttributes(array('id' => $shipper_id));
                $shipper_new   = AShipper::model()->findByAttributes(array('id' => $shipper_new_id));
                $order         = AOrders::model()->findByAttributes(array('id' => $order_id));


                $title       = "Hủy đơn hàng";
                $description = "Thông báo!`` Đơn hàng '$order->id' của khách hàng  '$order->full_name' đã được phân công lại cho '$shipper_new->username'!";
                // Gửi đơn hàng mới.
                // B2: Phân biệt request device.
                if (stristr($token_shipper->device_os, 'android') != FALSE) { // Device Android
                    $data_key = array(
                        'type'    => 1,
                        'title'   => $title,
                        'message' => $description,
                    );
                    $data     = array(
                        'registration_ids' =>
                            array(
                                0 => $token_shipper->token,
                            ),
                        'data'             => array(
                            'title'       => $title,
                            'description' => $description,
                            'content'     => CJSON::encode($data_key),
                        ),
                    );

                } else {
                    $data = array(
                        'registration_ids' =>
                            array(
                                0 => $token_shipper->token,
                            ),
                        'notification'     =>
                            array(
                                'body'     => $description,
                                'title'    => $title,
                                'type'     => '1',
                                'customId' => $description,
                            ),
                    );
                }

                $logFolder = "shipper/" . date("Y/m");
                $logObj    = SystemLog::getInstance($logFolder);
                $logObj->setLogFile(date('d') . 'send_noti_app' . '.log');
                $start_time = time();
                $logMsg     = array();
                $logMsg[]   = array('Start log send Notify Shipper Old: ', '', 'I', $start_time);
                $logMsg[]   = array('Resquest assign order_id: ' . $this->order_id . ' to shipper: ' . $shipper_new->username . ' shipper_old:' . $shipper->username, 'Respone:', 'T');
                $logMsg[]   = array('Resquest assign order_id: ' . $data, 'Respone:', 'T');

                $data_header = array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen(CJSON::encode($data)),
                    'Authorization: key=AAAAPGdujeo:APA91bHowfma96eSo78gQ5rIlXjS9p9aHmZYEUOdhbexVGgPFZIM_Hy6UemKf3R3AhZkn9KH4T356QVALx90Afa4eavjy95HOIzUQOsHmR0LNwLXtzKo1QnJIStRBZbtCfqmW_t8gglM'
                );
                //Call api thông báo .
                $response = Utils::cUrlPostJson(Yii::app()->params['api_notify_assign_shipper'], CJSON::encode($data), TRUE, 15, $http_status, $data_header);

                //Lưu log gọi api.
                $logMsg[] = array('Data: ' . $response, 'Respone:', 'T');
                $logMsg[] = array('Finish log', 'Finish proccess-' . __LINE__, 'F', time());
                $logObj->processWriteLogs($logMsg);
                header('HTTP/1.1 200 OK');

                return $response;
            }
        }

        /**
         * Gửi thông báo nhận hàng bằng sms.
         *
         * @param $msisdn
         */
        public function sendSMS($shipper_id, $order_id)
        {
            $shipper = AShipper::model()->findByAttributes(array('id' => $shipper_id));
            $msisdn  = $shipper->phone_2;
            // Send MT.
            //Lưu log gọi api.
            $mt_content = Yii::t('adm/mt_content', 'message');
            $logFolder  = "send_sms_shipper";
            if (self::sentMtVNP($msisdn, $mt_content, $logFolder)) {
                return TRUE;
            }

        }

        /**
         * Gửi thông báo nhận hàng bằng sms.
         *
         * @param $msisdn
         */
        public function sendSMSOld($shipper_old, $order_id)
        {
            $shipper = AShipper::model()->findByAttributes(array('id' => $shipper_old));
            $msisdn  = $shipper->phone_2;
            // Send MT.
            //Lưu log gọi api.
            $mt_content = Yii::t('adm/mt_content', 'message_cancel', array(
                '{order_id}' => $order_id,
            ));
            $logFolder  = "send_sms_shipper";
            if (self::sentMtVNP($msisdn, $mt_content, $logFolder)) {
                return TRUE;
            }

        }

        /**
         * @param $msisdn
         * @param $msgBody
         * @param $file_name
         *
         * @return bool
         */
        public static function sentMtVNP($msisdn, $msgBody, $file_name)
        {
            $logMsg   = array();
            $logMsg[] = array('Start Send MT ' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

            //send MT
            $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code);
            if ($flag) {
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T');
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            }
            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "shipper/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }

        public function getUserAssign()
        {
            if (isset($_GET['id'])) {
                $shipper_order = AShipperOrder::model()->findByAttributes(array('order_id' => $_GET['id']));
                if ($shipper_order->assign_by) {
                    $user = User::model()->findByAttributes(array('id' => $shipper_order->assign_by));
                    if ($user) {
                        return $user->username;
                    }
                }
            }

            return "";
        }

    }
