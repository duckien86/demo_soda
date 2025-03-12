<?php

    /**
     * This is the model class for table "tbl_orders".
     *
     * The followings are the available columns in table 'tbl_orders':
     *
     * @property int     $id
     * @property string  $code
     * @property integer $contract_id
     * @property string  $create_time
     * @property string  $last_update
     * @property string  $delivery_date
     * @property string  $finish_date
     * @property string  $promo_code
     * @property string  $note
     * @property string  $ward_code
     * @property string  $district_code
     * @property string  $province_code
     * @property string  $address_detail
     * @property string  $orderer_name
     * @property string  $orderer_phone
     * @property string  $receiver_name
     * @property integer $accepted_payment_files
     * @property string  $total_success
     * @property string  $total_fails
     * @property integer $status
     * @property integer $data_status
     * @property integer $type
     * @property integer $user_id
     * @property integer $owner_id
     * @property integer $payment_method
     * @property string  $store_id
     */
    class FTOrders extends CActiveRecord
    {

        CONST TYPE_NORMAL          = 1;
        CONST TYPE_WITH_FILE_SIM   = 2;
        CONST OBJECT_FILE_SIM      = 'TOrdersFileSim';
        CONST OBJECT_FILE_ACCEPT_PAYMENT = 'TOrders';

        CONST ORDER_DRAFTS      = -1;   // Nháp
        CONST ORDER_CREATE      = 0;    // Đặt hàng
        CONST ORDER_CONFIRM     = 1;    // Xác nhận.
        CONST ORDER_APPROVED    = 2;    //Phê duyệt.
        CONST ORDER_ASSIGNED    = 3;    //Chờ nhập serial.
        CONST ORDER_JOIN_KIT    = 5;    //Đang ghép kít.
        CONST ORDER_STOP        = 8;    //Tạm dừng
        CONST ORDER_COMPLETE    = 9;    //Hoàn thành.
        CONST ORDER_RECEIVED    = 10;   //Đã giao hàng.

        CONST ORDER_CARD_DRAFTS    = -1; // Nháp
        CONST ORDER_CARD_CREATE     = 0; // Đặt hàng
        CONST ORDER_CARD_CONFIRM    = 1; // Xác nhận / chờ xử lý
        CONST ORDER_CARD_PROCESSING = 5; // Đang xử lí
        CONST ORDER_CARD_FAIL       = 8; // Lỗi
        CONST ORDER_CARD_COMPLETE   = 9; // Hoàn thành
        CONST ORDER_CARD_RECEIVED  = 10; // Đã giao hàng

        CONST PAYMENT_METHOD_COD        = 4; //Thanh toán trực tiếp
        CONST PAYMENT_METHOD_TRANSFER   = 11; //Chuyển khoản

        CONST TYPE_SIM = 1;
        CONST TYPE_CARD = 2;

        CONST FILE_LOG_SEND_MT = 'confirm_order';
        CONST FILE_LOG_SEND_CTV = 'confirm_order_ctv';

        public $total; // tổng số lượng gói(package) đã sử dụng
        public $packages; // array TPackage

        public $contract_code;

        public $use_promo_code;

        public $file_sim;
        public $quantity;

        public $item_type;
        public $total_sim;
        public $total_package;
        public $rose; // hoa hồng

        CONST CAMPAIGN_CATEGORY_ID_SIM = 3;     //sim
        CONST CAMPAIGN_CATEGORY_ID_PACKAGE = 4; //gói
        CONST CAMPAIGN_CATEGORY_ID_CONSUME = 5; //tiêu dùng TKC

        public $campaign_category_id;
        public $rose_sim;
        public $rose_package;
        public $revenue;
        public $type_sim;
        public $type_package;
        public $msisdn;
        public $serial;
        public $package_name;
        public $package_code;


        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_orders';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('contract_id, accepted_payment_files, status, data_status, type', 'numerical', 'integerOnly' => TRUE),
                array('promo_code, note, ward_code, address_detail, orderer_name, orderer_phone, receiver_name', 'length', 'max' => 255),
                array('district_code, province_code', 'length', 'max' => 100),
                array('total_success, total_fails', 'length', 'max' => 10),
                array('create_time, last_update, delivery_date, finish_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, contract_id, create_time, last_update, delivery_date, finish_date, promo_code, note, ward_code, district_code, province_code, address_detail, orderer_name, orderer_phone, receiver_name, accepted_payment_files, total_success, total_fails, status, data_status, type', 'safe', 'on' => 'search'),
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
                'id'                     => 'ID',
                'code'                   => 'Code',
                'contract_id'            => 'Contract',
                'create_time'            => 'Create Time',
                'last_update'            => 'Last Update',
                'delivery_date'          => 'Delivery Date',
                'finish_date'            => 'Finish Date',
                'note'                   => 'Note',
                'promo_code'             => 'Promo Code',
                'ward_code'              => 'Ward Code',
                'district_code'          => 'District Code',
                'province_code'          => 'Province Code',
                'address_detail'         => 'Address Detail',
                'orderer_name'           => 'Orderer Name',
                'orderer_phone'          => 'Orderer Phone',
                'receiver_name'          => 'Receiver Name',
                'accepted_payment_files' => 'Accepted Payment Files',
                'total_success'          => 'Total Success',
                'total_fails'            => 'Total Fails',
                'status'                 => 'Status',
                'data_status'            => 'Data Status',
                'type'                   => 'Type',
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
            $criteria->compare('promo_code', $this->promo_code, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('address_detail', $this->address_detail, TRUE);
            $criteria->compare('orderer_name', $this->orderer_name, TRUE);
            $criteria->compare('orderer_phone', $this->orderer_phone, TRUE);
            $criteria->compare('receiver_name', $this->receiver_name, TRUE);
            $criteria->compare('accepted_payment_files', $this->accepted_payment_files);
            $criteria->compare('total_success', $this->total_success, TRUE);
            $criteria->compare('total_fails', $this->total_fails, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('data_status', $this->data_status);
            $criteria->compare('type', $this->type);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * @return CDbConnection the database connection used for this class
         */
        public function getDbConnection()
        {
            return Yii::app()->db_freedoo_tourist;
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return FTOrders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
