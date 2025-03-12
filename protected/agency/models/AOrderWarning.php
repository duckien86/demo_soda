<?php

    /**
     * This is the model class for table "{{order_warning}}".
     *
     * The followings are the available columns in table '{{order_warning}}':
     *
     * @property string  $id
     * @property string  $order_id
     * @property string  $create_date
     * @property integer $action_code
     * @property integer $status
     * @property string  $last_update
     */
    class AOrderWarning extends OrderWarning
    {

        CONST SHOW = 1;
        CONST HIDE = 0;

        public $province_code;
        public $sale_office_code;
        public $brand_offices_id;
        public $start_date;
        public $end_date;
        public $delivery_type;
        public $status_shipper;
        public $payment_method;
        public $full_name;
        public $phone_contact;
        public $district_code;
        public $customer_note;


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, create_date, action_code, status, last_update', 'required'),
                array('action_code, status', 'numerical', 'integerOnly' => TRUE),
                array('order_id', 'length', 'max' => 15),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, create_date, action_code, status, last_update, status_shipper.
                        province_code, sale_office_code,brand_offices_id,start_date,end_date,delivery_type ', 'safe', 'on' => 'search'),
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
                'id'               => 'ID',
                'order_id'         => 'Mã đơn hàng',
                'create_date'      => 'Ngày đặt hàng',
                'action_code'      => 'Mức độ cảnh báo',
                'status'           => 'Trạng thái',
                'last_update'      => 'Ngày cập nhật',
                'province_code'    => 'TTKD',
                'sale_office_code' => 'Phòng bán hàng',
                'brand_offices_id' => 'Điểm giao dịch',
                'delivery_type'    => 'Hình thức nhận hàng',
                'status_shipper'   => 'Trạng thái giao vận',
                'start_date'       => 'Ngày bắt đầu',
                'end_date'         => 'Ngày kết thúc',
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
        public function search($post = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }

            $criteria = new CDbCriteria;
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("o.create_date >='$this->start_date' and o.create_date <='$this->end_date'");
            }
            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.order_id', $this->order_id, TRUE);
            $criteria->compare('t.action_code', $this->action_code);
            $criteria->compare('t.status', $this->status);
            $criteria->compare('t.last_update', $this->last_update, TRUE);
            if ($post) {
                if ($this->province_code != '') {
                    $criteria->compare('o.province_code', $this->province_code);
                }
                if ($this->sale_office_code != '') {
                    $criteria->compare('o.sale_office_code', $this->sale_office_code);
                }
                if ($this->brand_offices_id != '') {
                    $criteria->compare('o.address_detail', $this->brand_offices_id, FALSE);
                }
            }
            $criteria->join = "INNER JOIN {{orders}} o ON o.id = t.order_id";

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_date ASC',
                ),
                'pagination' => array(
                    'params'   => array(
                        "AOrderWarning[sale_office_code]" => isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '',
                        "AOrderWarning[province_code]"    => isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '',
                        "AOrderWarning[brand_offices_id]" => isset(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '',
                        "AOrderWarning[start_date]"       => $this->start_date,
                        "AOrderWarning[end_date]"         => $this->end_date,
                        "AOrderWarning[phone_contact]"    => $this->phone_contact,
                        "AOrderWarning[status_shipper]"   => $this->status_shipper,
                    ),
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
         * @return OrderWarning the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy cảnh báo
         */
        public function getActionCode($action_code)
        {
            $data = array(
                1 => 'Sim đã khai báo nhưng chưa đktt',
                2 => 'Sim kèm gói đã khai báo sim và đã đktt nhưng chưa gán gói',
                3 => 'Quá 48h nhưng sim chưa khởi tạo',
                4 => 'Chưa khởi tạo sim',
                5 => 'Sim đã khởi tạo nhưng chưa dktt trong vòng 72h',
            );

            return isset($data[$action_code]) ? $data[$action_code] : $action_code;
        }

        /**
         * Lấy cảnh báo
         */
        public function getAllActionCode()
        {
            $data = array(
                1 => 'Sim đã khai báo nhưng chưa đktt',
                2 => 'Sim kèm gói đã khai báo sim và đã đktt nhưng chưa gán gói',
                3 => 'Quá 48h nhưng sim chưa khởi tạo',
                4 => 'Chưa khởi tạo sim',
                5 => 'Sim đã khởi tạo nhưng chưa dktt trong vòng 72h',
            );

            return $data;
        }

        public function getAllDeliveredType()
        {
            return array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );
        }

    }
