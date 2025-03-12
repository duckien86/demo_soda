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
     */
    class CskhShipperOrder extends ShipperOrder
    {
        public $email;
        public $type_assign;

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
                array('order_id, shipper_id, assign_by, note', 'length', 'max' => 255),
                array('ship_cost', 'length', 'max' => 10),
                array('assign_date, delivery_date, finish_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, shipper_id, assign_date, delivery_date, finish_date, ship_cost, assign_by, note, status', 'safe', 'on' => 'search'),
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
                'id'            => 'ID',
                'order_id'      => 'Order',
                'shipper_id'    => 'Shipper',
                'assign_date'   => 'Assign Date',
                'delivery_date' => 'Delivery Date',
                'finish_date'   => 'Finish Date',
                'ship_cost'     => 'Ship Cost',
                'assign_by'     => 'Assign By',
                'note'          => 'Note',
                'status'        => 'Status',
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

            $data = new CActiveDataProvider('CskhTraffic', array(
                'criteria' => $criteria,
            ));

            $email_content = Yii::app()->controller->renderPartial('_assignment_order_shipper', array('order_data' => $data), TRUE);
            self::sendEmail($this->email, $email_content);

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        /**
         * Gửi mail thông báo nhận hàng.
         *
         * @param $email
         */
        public function sendEmail($email, $email_content)
        {
            if ($email) {
                if (Utils::sendEmail('Phân công đơn hàng', $email, '', '', $email_content, 'cskh.config')) {
                    return TRUE;
                }
            }

            return FALSE;
        }

        public function afterSave()
        {
            $data = self::sendNotify($this->shipper_id);

            //Lưu log gọi api.
            $logFolder = "sendNotify_shipper/" . date("Y/m");
            $logObj    = SystemLog::getInstance($logFolder);
            $logObj->setLogFile(date('d') . '.log');
            $start_time = time();
            $logMsg     = array();
            $logMsg[]   = array('Start log send Notify Shipper: ' . $data, 'Start proccess From:', 'I', $start_time);
            $logMsg[]   = array('Finish log', 'Finish proccess-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);
            header('HTTP/1.1 200 OK');

            parent::afterSave(); // TODO: Change the autogenerated stub
        }

        /**
         * Gửi thông báo nhận hàng.
         *
         * @param $msisdn
         */
        public function sendNotify($shipper_id)
        {
            if ($shipper_id) {
                //B1: Khởi tạo dữ liệu.
                $title = $description = "";
                //Lấy token của shipper.
                $token_shipper = AppTblDeviceShipper::model()->findByAttributes(array('user_id' => $shipper_id));
                if ($this->type_assign == self::ASSIGN) {
                    $title       = "Phân công đơn hàng";
                    $description = "Bạn có thông báo đơn hàng mới!";
                } else {
                    $title       = "Hủy đơn hàng";
                    $description = "Bạn vừa bị hủy đơn hàng mới!";
                }


                // B2: Phân biệt request device.
                if (stristr($token_shipper->device_os, 'android') != FALSE) { // Device Android
                    $data_key = array(
                        'type'    => 1,
                        'title'   => $title,
                        'message' => $description,
                    );
                    $data     = array(
                        'registration_ids' => ["$token_shipper->token"],
                        'data'             => array(
                            'title'       => $title,
                            'description' => $description,
                            'content'     => CJSON::encode($data_key),
                        ),
                    );
                } else {
                    $data = array( // Device IOS
                        'registration_ids' => ["$token_shipper->token"],
                        'notification'     => array(
                            'body'     => '[' . $description . ']',
                            'title'    => '[' . $title . ']',
                            'type'     => '1',
                            'customId' => '[' . $description . ']',

                        )
                    );
                }
                //Call api thông báo .
                $response = Utils::cUrlPostJson(Yii::app()->params['api_notify_assign_shipper'], CJSON::encode($data), TRUE, 15, $http_status, TRUE);

                return $response;
            }
        }

        public function getUserAssign()
        {
            if (isset($_GET['id'])) {
                $shipper_order = CskhShipperOrder::model()->findByAttributes(array('order_id' => $_GET['id']));
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
