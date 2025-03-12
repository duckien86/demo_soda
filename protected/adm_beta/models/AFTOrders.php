<?php


    class AFTOrders extends FTOrders
    {

        CONST ORDER_CREATE_LABEL   = 'Chờ xác nhận'; // Đặt hàng.
        CONST ORDER_CONFIRM_LABEL  = 'Xác nhận'; // Xác nhận.
        CONST ORDER_APPROVED_LABEL = 'Phê duyệt'; //Phê duyệt.
        CONST ORDER_ASSIGNED_LABEL = 'Chờ nhập serial'; //Đã phân.
        CONST ORDER_COMPLETE_LABEL = 'Hoàn thành'; //Hoàn thành.
        CONST ORDER_RECEIVED_LABEL = 'Đã giao hàng'; //Đã giao hàng.
        CONST ORDER_JOIN_KIT_LABEL = 'Ghép kit'; //Đã giao hàng.

        public $total; //Tổng sản lương.
        public $total_renueve; //Tổng doanh thu.
        public $package_name; //Tên sản phẩm.
        public $contract_code; //Mã hợp đồng.
        public $user_tourist; //Mã doanh nghiệp.


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('contract_id, accepted_payment_files, status,user_id, data_status', 'numerical', 'integerOnly' => TRUE),
                array('note, ward_code, address_detail, orderer_name, orderer_phone, receiver_name, code', 'length', 'max' => 255),
                array('district_code, province_code', 'length', 'max' => 100),
                array('total_success, total_fails', 'length', 'max' => 10),
                array('create_time, last_update, delivery_date, finish_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, contract_id, create_time, last_update, delivery_date, finish_date, code, note, ward_code, district_code, province_code, address_detail, orderer_name, orderer_phone, receiver_name, accepted_payment_files, total_success, total_fails, status, data_status', 'safe', 'on' => 'search'),
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
                'id'                     => 'Mã đơn hàng',
                'code'                   => 'Mã đơn hàng',
                'contract_id'            => 'Mã hợp đồng',
                'create_time'            => 'Ngày đặt hàng',
                'last_update'            => 'Last Update',
                'delivery_date'          => 'Hạn giao hàng',
                'finish_date'            => 'Ngày giao hàng',
                'note'                   => 'Ghi chú (nếu có)',
                'ward_code'              => 'Phường xã',
                'district_code'          => 'Quận huyện',
                'province_code'          => 'Tỉnh thành',
                'address_detail'         => 'Địa chỉ liên hê',
                'orderer_name'           => 'Người đặt hàng',
                'orderer_phone'          => 'Số ĐT liên hệ',
                'receiver_name'          => 'Tên người nhận hàng',
                'accepted_payment_files' => 'Ủy nhiệm chi',
                'total_success'          => 'Tổng thành công',
                'total_fails'            => 'Tổng thất bại',
                'status'                 => 'Trạng thái',
                'data_status'            => 'Trạng thái dữ liệu',
                'user_id'                => 'Nhân viên phụ trách',
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
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('contract_id', $this->contract_id);
            $criteria->compare('create_time', $this->create_time, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('delivery_date', $this->delivery_date, TRUE);
            $criteria->compare('finish_date', $this->finish_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            if (!ADMIN && !SUPER_ADMIN) {
                if (isset(Yii::app()->user->vnp_province_id)) {
                    if (!empty(Yii::app()->user->vnp_province_id)) {
                        $criteria->addCondition("province_code ='" . Yii::app()->user->vnp_province_id . "'");
                    }
                }
            }
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('orderer_name', $this->orderer_name, TRUE);
            $criteria->compare('orderer_phone', $this->orderer_phone, TRUE);
            $criteria->compare('receiver_name', $this->receiver_name, TRUE);
            $criteria->compare('accepted_payment_files', $this->accepted_payment_files);
            $criteria->compare('total_success', $this->total_success, TRUE);
            $criteria->compare('total_fails', $this->total_fails, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('data_status', $this->data_status);
            $criteria->addCondition('status !=-1');

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_time DESC',
                ),
                'pagination' => array(
                    'pageSize' => 30,
                ),
            ));
        }


        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AFTOrders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy trạng thái đơn hàng doanh nghiệp.
         */
        public function getStatusOrders()
        {
            return array(
                self::ORDER_CREATE   => 'Chờ xác nhận',
                self::ORDER_CONFIRM  => 'Xác nhận',
                self::ORDER_APPROVED => 'Phê duyệt',
                self::ORDER_ASSIGNED => 'Chờ nhập serial',
                self::ORDER_STOP     => 'Tạm dừng',
                self::ORDER_COMPLETE => 'Hoàn thành',
                self::ORDER_RECEIVED => 'Đã giao hàng'
            );
        }

        /**
         * Lấy trạng thái đơn hàng doanh nghiệp.
         */
        public function getStatusUserActive($status)
        {
            $return = array();
            $data   = array(
                self::ORDER_CREATE   => 'Chờ xác nhận',
                self::ORDER_CONFIRM  => 'Xác nhận',
                self::ORDER_APPROVED => 'Phê duyệt',
                self::ORDER_ASSIGNED => 'Chờ nhập serial',
                self::ORDER_JOIN_KIT => 'Đang ghép kít',
                self::ORDER_STOP     => 'Tạm dừng',
                self::ORDER_COMPLETE => 'Hoàn thành',
                self::ORDER_RECEIVED => 'Đã giao hàng',
            );

            foreach ($data as $key => $value) {
                if (count($return) < 2) {
                    if ($status <= $key && $status != self::ORDER_JOIN_KIT && $key < 3) {
                        $return[$key] = $value;
                    } else if ($status == self::ORDER_JOIN_KIT
                        || $status == self::ORDER_STOP
                        || $status == self::ORDER_COMPLETE
                        || $status == self::ORDER_RECEIVED || $status == self::ORDER_ASSIGNED
                    ) {
                        $return[$status] = $data[$status];

                        return $return;
                    }
                } else {
                    break;
                }

            }


            return $return;
        }

        /**
         * Lấy trạng thái đơn hàng doanh nghiệp.
         */
        public static function getNameStatusOrders($status)
        {

            $data = array(
                self::ORDER_CREATE   => 'Chờ xác nhận',
                self::ORDER_CONFIRM  => 'Xác nhận',
                self::ORDER_APPROVED => 'Phê duyệt',
                self::ORDER_ASSIGNED => 'Chờ nhập serial',
                self::ORDER_STOP     => 'Tạm dừng',
                self::ORDER_COMPLETE => 'Hoàn thành',
                self::ORDER_RECEIVED => 'Đã giao hàng',
                self::ORDER_JOIN_KIT => 'Đang ghép KIT'
            );

            return isset($data[$status]) ? $data[$status] : '';
        }

        /**
         * Lấy tổng giá trị đơn hàng.
         */
        public static function getTotalOrders($order_id)
        {
            if ($order_id) {
                $criteria            = new CDbCriteria();
                $criteria->select    = "SUM(quantity*price) as total";
                $criteria->condition = "order_id ='$order_id'";
                $order_details       = AFTOrderDetails::model()->findAll($criteria);
                if ($order_details) {

                    if (isset($order_details[0]->total)) {
                        return $order_details[0]->total;
                    }
                }
            }

            return 0;
        }

        /**
         * Lấy trạng thái đơn hàng.
         */
        public static function getStatusOrder($order_id)
        {
            $return = 0;
            if ($order_id) {
                $orders = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                if ($orders) {
                    $return = $orders->status;
                }
            }

            return $return;
        }

        /**
         * @param $order_id
         *
         * @return int
         * Lấy tổng sim của đơn hàng
         */
        public static function getTotalSim($order_id)
        {
            $total = 0;
            if ($order_id) {
                $criteria            = new CDbCriteria();
                $criteria->select    = "SUM(quantity) as total";
                $criteria->condition = "order_id = '$order_id'";
                $order_details       = AFTOrderDetails::model()->findAll($criteria);

                if ($order_details) {
                    if (isset($order_details[0]->total)) {
                        if ($order_details[0]->total > 0) {
                            $total = $order_details[0]->total;
                        }
                    }
                }
            }

            return $total;
        }


        /**
         * @param $contract_id
         * Lấy danh sách đơn hàng theo hợp đồng.
         *
         * @return array
         */
        public function getOrdersByContract($contract_id)
        {
            $data = array();

            if ($contract_id) {
                $order = AFTOrders::model()->findAll('contract_id =:contract_id',
                    array(
                        ':contract_id' => $contract_id,
                    )
                );

                return CHtml::listData($order, 'id', 'code');
            }

            return $data;
        }

        public function getCodeOfOrders($id)
        {
            $orders = AFTOrders::model()->findByAttributes(array('id' => $id));
            if ($orders) {
                return $orders->code;
            }

            return "";
        }

    }
