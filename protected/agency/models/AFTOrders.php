<?php


class AFTOrders extends FTOrders
{

    CONST ORDER_CREATE_LABEL   = 'Chờ xác nhận';    // Đặt hàng.
    CONST ORDER_CONFIRM_LABEL  = 'Xác nhận';        // Xác nhận.
    CONST ORDER_APPROVED_LABEL = 'Phê duyệt';       //Phê duyệt.
    CONST ORDER_ASSIGNED_LABEL = 'Chờ nhập serial'; //Đã phân.
    CONST ORDER_COMPLETE_LABEL = 'Hoàn thành';      //Hoàn thành.
    CONST ORDER_RECEIVED_LABEL = 'Đã giao hàng';    //Đã giao hàng.
    CONST ORDER_JOIN_KIT_LABEL = 'Ghép kit';        //Đã giao hàng.

    CONST TYPE_SIM  = 1; //đơn hàng mua sim
    CONST TYPE_CARD = 2; //đơn hàng mua thẻ

    CONST OBJECT_FILE_SIM      = 'TOrdersFileSim';
    CONST OBJECT_FILE_ACCEPT_PAYMENT = 'TOrders';

    CONST ORDER_NORMAL = 1;
    CONST ORDER_FILE_SIM = 2;

    CONST PAYMENT_METHOD_COD        = 4; //Thanh toán trực tiếp
    CONST PAYMENT_METHOD_TRANSFER   = 11; //Chuyển khoản

    CONST FILE_LOG_SEND_MT = 'confirm_order';
    CONST FILE_LOG_SEND_CTV = 'confirm_order_ctv';

    CONST STORE_ID_DEFAULT = 34012;

    public $total;          //Tổng sản lương.
    public $total_renueve;  //Tổng doanh thu.
    public $package_name;   //Tên sản phẩm.
    public $contract_code;  //Mã hợp đồng.
    public $user_tourist;   //Mã doanh nghiệp.

    public $date;
    public $total_tourist;
    public $renueve_tourist;

    public $start_date;
    public $end_date;

    public $customer;
    public $card;
    public $company;
    public $customer_type;
    public $user_type;
    public $order_type;
    public $bundle;

    public $price;
    public $quantity;
    public $package_id;
    public $revenue;
    public $rose;
    public $campaign_category_id;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('contract_id, accepted_payment_files, status,user_id, data_status, type, order_type, payment_method', 'numerical', 'integerOnly' => TRUE),
            array('store_id,note, ward_code, address_detail, orderer_name, orderer_phone, receiver_name, code, contract_code, promo_code', 'length', 'max' => 255),
            array('district_code, province_code', 'length', 'max' => 100),
            array('total_success, total_fails', 'length', 'max' => 10),
            array('create_time, last_update, delivery_date, finish_date, start_date, 
                end_date, customer, card, customer_type, user_type, 
                price, quantity, package_id, package_name,
                revenue, total, rose, campaign_category_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, contract_id, create_time, last_update, delivery_date, 
                finish_date, code, note, ward_code, district_code, 
                province_code, address_detail, orderer_name, orderer_phone, receiver_name, 
                accepted_payment_files, total_success, total_fails, status, data_status, 
                type, start_date, end_date, contract_code, customer, 
                card, company, customer_type, promo_code, user_type, 
                order_type, payment_method, price, quantity, bundle', 'safe', 'on' => 'search'),

            array('customer, contract_id, accepted_payment_files, user_id', 'required', 'on' => 'order_card'),
            array('card' , 'validateCard', 'on' => 'order_card'),
        );
    }


    public function validateCard(){
        if(empty($this->card)){
            $this->addError('card', 'Không có dữ liệu thẻ');
            return false;
        }
        if(is_array($this->card)){
            foreach ($this->card as $value => $quantity){
                $remain = ACardStoreBusiness::getCardQuantityByValue($value, ACardStoreBusiness::CARD_NEW);
                if($remain < $quantity){
                    $errorMsg = Yii::t('adm/label', 'quantity_card_store_not_enough', array('{value}' => number_format($value,0,',','.')));
                    $this->addError('card', $errorMsg);
                    return false;
                }
            }
        }
        return true;
    }

    protected function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->create_time = date('Y-m-d H:i:s');
        }
        $this->last_update = date('Y-m-d H:i:s');

        if(empty($this->code)){
            $this->code = 'draft';
        }
        return true;
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
            'type'                   => 'Loại đơn hàng',
            'promo_code'             => 'Mã CTV',

            'contract_code'          => Yii::t('adm/label','contract_id'),
            'start_date'             => Yii::t('adm/label','start_date'),
            'end_date'               => Yii::t('adm/label','finish_date'),
            'customer'               => Yii::t('adm/label','customer'),
            'card'                   => Yii::t('adm/label','card'),
            'customer_type'          => 'ĐLTC / CTV',
            'order_type'             => 'Loại đơn hàng',
            'payment_method'         => 'Hình thức thanh toán',
            'store_id'               => 'Mã kho',
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
    public function search($type=1)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

//        $criteria = new CDbCriteria;
//
//        $criteria->compare('t.id', $this->id, TRUE);
//        $criteria->compare('t.code', $this->code, TRUE);
////            $criteria->compare('t.contract_id', $this->contract_id);
////            $criteria->compare('t.create_time', $this->create_time, TRUE);
//        $criteria->compare('t.last_update', $this->last_update, TRUE);
//        $criteria->compare('t.delivery_date', $this->delivery_date, TRUE);
//        $criteria->compare('t.finish_date', $this->finish_date, TRUE);
//        $criteria->compare('t.note', $this->note, TRUE);
//        $criteria->compare('t.ward_code', $this->ward_code, TRUE);
//        $criteria->compare('t.district_code', $this->district_code, TRUE);
//        if (!ADMIN && !SUPER_ADMIN) {
//            if (isset(Yii::app()->user->vnp_province_id)) {
//                if (!empty(Yii::app()->user->vnp_province_id)) {
//                    $criteria->addCondition("province_code ='" . Yii::app()->user->vnp_province_id . "'");
//                }
//            }
//        }
//        $criteria->compare('t.address_detail', $this->address_detail, TRUE);
//        $criteria->compare('t.orderer_name', $this->orderer_name, TRUE);
//        $criteria->compare('t.orderer_phone', $this->orderer_phone, TRUE);
//        $criteria->compare('t.receiver_name', $this->receiver_name, TRUE);
//        $criteria->compare('t.accepted_payment_files', $this->accepted_payment_files);
//        $criteria->compare('t.total_success', $this->total_success, TRUE);
//        $criteria->compare('t.total_fails', $this->total_fails, TRUE);
//        $criteria->compare('t.status', $this->status);
//        $criteria->compare('t.data_status', $this->data_status);
//        $criteria->compare('t.type', AFTOrders::TYPE_SIM);
//
//        $criteria->addCondition('t.status !=-1');
//        $criteria->addCondition('t.contract_id !=-1');
//
//        if($this->start_date && $this->end_date){
//
//            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
//            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
//
//            $criteria->addCondition("t.create_time >= '$this->start_date' AND t.create_time <= '$this->end_date'");
//        }
//
//        if(!empty($this->contract_code) || !empty($this->customer)){
//            $criteria->join = "INNER JOIN tbl_contracts c ON t.contract_id = c.id INNER JOIN tbl_users u ON c.user_id = u.id";
//            $criteria->compare('c.code',$this->contract_code, TRUE);
//            $criteria->compare('u.username',$this->customer, TRUE);
//        }

        $criteria = new CDbCriteria();
        $criteria->join = "INNER JOIN tbl_contracts c ON t.contract_id = c.id INNER JOIN tbl_users u ON c.user_id = u.id";
        $criteria->select = 't.*, u.username as customer, u.company as company, u.user_type as user_type';
        $criteria->compare('t.id',$this->id, FALSE);
        $criteria->compare('t.code', $this->code, TRUE);
        $criteria->compare('t.promo_code', $this->promo_code, TRUE);
        $criteria->compare('t.province_code', $this->province_code, TRUE);
        $criteria->compare('t.status', $this->status, TRUE);
        $criteria->compare('t.type', AFTOrders::TYPE_SIM, FALSE);

        if (!ADMIN && !SUPER_ADMIN && !PBH_DN) {
            if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
                    || empty(Yii::app()->user->sale_offices_id))
            ) {
                $criteria->compare('t.province_code', Yii::app()->user->province_code);
            } else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
                && !empty(Yii::app()->user->sale_offices_id)
                && (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
            ) {
                $criteria->join.= ' INNER JOIN vnpt_online.tbl_sale_offices s ON s.ward_code = t.ward_code';
                $criteria->group = 't.id';
                $criteria->compare('s.code', Yii::app()->user->sale_offices_id, FALSE);
                $criteria->compare('u.user_type', AFTUsers::USER_TYPE_CTV, FALSE);
            }
        }

        if($this->customer){
            $criteria->compare('u.username', $this->customer, TRUE);
            $criteria->compare('u.company',$this->customer, TRUE, 'OR');
        }

//        if($this->customer_type){
//            if($this->customer_type == self::CUSTOMER_DLTC){
//                $criteria->addCondition('u.user_type != '.AFTUsers::USER_TYPE_CTV);
//            }
//            if($this->customer_type == self::CUSTOMER_CTV){
//                $criteria->addCondition('u.user_type = '.AFTUsers::USER_TYPE_CTV);
//            }
//        }

        if($this->order_type){

            if($this->order_type == self::ORDER_NORMAL){
                $criteria->addCondition("t.id NOT IN (SELECT f.object_id FROM tbl_files f WHERE f.object = '".AFTFiles::OBJECT_FILE_SIM."')");
            }
            if($this->order_type == self::ORDER_FILE_SIM){
                $criteria->addCondition("t.id IN (SELECT f.object_id FROM tbl_files f WHERE f.object = '".AFTFiles::OBJECT_FILE_SIM."')");
            }
        }

        if($this->start_date && $this->end_date){
            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            $criteria->addCondition("t.create_time >= '$this->start_date' AND t.create_time <= '$this->end_date'");
        }

        if(!empty($this->contract_code) || !empty($this->customer)){
            $criteria->compare('c.code',$this->contract_code, TRUE);
            $criteria->compare('u.username',$this->customer, TRUE);
        }
        return new CActiveDataProvider($this, array(
            'criteria'   => $criteria,
            'sort'       => array(
                'defaultOrder' => 't.create_time DESC',
            ),
            'pagination' => array(
                'pageSize' => 10,
            )
        ));
    }

    public function searchExport()
    {
// @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('t.code', $this->code, TRUE);
        $criteria->compare('t.type', AFTOrders::TYPE_CARD);
        $criteria->compare('t.status', $this->status);
        $criteria->addCondition('t.status !=-1');

        if($this->start_date && $this->end_date){

            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

            $criteria->addCondition("t.create_time >= '$this->start_date' AND t.create_time <= '$this->end_date'");
        }

        if(!empty($this->contract_code) || !empty($this->company)){
            $criteria->join = "INNER JOIN tbl_contracts c ON t.contract_id = c.id INNER JOIN tbl_users u ON c.user_id = u.id";
            $criteria->compare('c.code',$this->contract_code, TRUE);
            $criteria->compare('u.company',$this->company, TRUE);
        }

        return new CActiveDataProvider($this, array(
            'criteria'   => $criteria,
            'sort'       => array(
                'defaultOrder' => 't.create_time DESC',
            ),
            'pagination' => array(
                'pageSize' => 20,
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
                if ($status <= $key && $status != self::ORDER_JOIN_KIT && $key <= self::ORDER_APPROVED) {
                    $return[$key] = $value;
                } else if ($status == self::ORDER_JOIN_KIT
                    || $status == self::ORDER_STOP
                    || $status == self::ORDER_COMPLETE
                    || $status == self::ORDER_RECEIVED
                    || $status == self::ORDER_ASSIGNED
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

    public static function getListStatusOrderSim(){
        return array(
            self::ORDER_CREATE   => 'Chờ xác nhận',
            self::ORDER_CONFIRM  => 'Xác nhận',
            self::ORDER_APPROVED => 'Phê duyệt',
            self::ORDER_ASSIGNED => 'Chờ nhập serial',
            self::ORDER_JOIN_KIT => 'Đang ghép KIT',
            self::ORDER_STOP     => 'Tạm dừng',
            self::ORDER_COMPLETE => 'Hoàn thành',
            self::ORDER_RECEIVED => 'Đã giao hàng',
        );
    }

    public function getListStatusOrderSimRevenue(){
        return array(
            self::ORDER_JOIN_KIT => 'Đang ghép KIT',
            self::ORDER_STOP     => 'Tạm dừng',
            self::ORDER_COMPLETE => 'Hoàn thành',
            self::ORDER_RECEIVED => 'Đã giao hàng',
        );
    }

    public static function getStatusLabelOrderSim($status)
    {
        $data = self::getListStatusOrderSim();
        return (isset($data[$status])) ? $data[$status] : $status;
    }

    public static function getStatusClassOrderSim($status){
        $class = '';
        switch ($status){
            case self::ORDER_CREATE:
                $class = 'text-secondary';
                break;
            case self::ORDER_CONFIRM:
            case self::ORDER_APPROVED:
                $class = 'text-info';
                break;
            case self::ORDER_ASSIGNED:
                $class = 'text-primary';
                break;
            case self::ORDER_JOIN_KIT:
                $class = 'text-warning';
                break;
            case self::ORDER_STOP:
                $class = 'text-danger';
                break;
            case self::ORDER_COMPLETE:
            case self::ORDER_RECEIVED:
                $class = 'text-success';
                break;
        }
        return $class;
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
     * @param $contract_id integer
     * @param $listData boolean
     * Lấy danh sách đơn hàng theo hợp đồng.
     *
     * @return array
     */
    public function getOrdersByContract($contract_id, $listData = TRUE)
    {
        $data = array();

        if ($contract_id) {
            $order = AFTOrders::model()->findAll('contract_id =:contract_id',
                array(
                    ':contract_id' => $contract_id,
                )
            );

            if($listData){
                $data = CHtml::listData($order, 'id', 'code');
            }else{
                $data = $order;
            }
        }

        return $data;
    }

    public function getCodeOfOrders($id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.code';
        $criteria->condition = 't.id = :id';
        $criteria->params = array(
            ':id' => $id
        );

        $cache_key = 'AFTOrders_getCodeOfOrders_'.$id;
        $results   = Yii::app()->cache->get($cache_key);
        if(!$results){
            $model = AFTOrders::model()->find($criteria);
            if($model){
                $results = $model->code;
            }
            Yii::app()->cache->set($cache_key, $results, 300);
        }
        return $results;
    }


    public static function getStatusOrderCard($status){
        $data = self::getListStatusOrderCard();
        return (isset($data[$status])) ? $data[$status] : $status;
    }

    public static function getListStatusOrderCard(){
        return array(
            self::ORDER_CARD_CREATE     => "Chờ xác nhận",
            self::ORDER_CARD_CONFIRM    => "Chờ xử lý",
            self::ORDER_CARD_PROCESSING => "Đang xử lý",
            self::ORDER_CARD_FAIL       => "Tạm dừng",
            self::ORDER_CARD_COMPLETE   => "Hoàn thành",
            self::ORDER_CARD_RECEIVED   => "Đã giao hàng",
        );
    }

    public static function getActiveStatusOrderCard($status){
        switch ($status){
            case self::ORDER_CARD_CREATE:
                return "Chờ xác nhận";
            case self::ORDER_CARD_CONFIRM:
                return "Chờ xử lý";
            case self::ORDER_CARD_PROCESSING:
                return "Đang xử lý";
            case self::ORDER_CARD_FAIL:
                return "Tạm dừng";
            case self::ORDER_CARD_COMPLETE:
                return "Hoàn thành";
            case self::ORDER_CARD_RECEIVED:
                return "Đã giao hàng";
            default:
                return $status;
        }
    }

    public static function getActiveStatusClassOrderCard($status){
        switch ($status){
            case self::ORDER_CARD_CREATE:
                return "btn btn-info";
            case self::ORDER_CARD_CONFIRM:
                return "btn btn-warning";
            case self::ORDER_CARD_PROCESSING:
                return "btn btn-primary";
            case self::ORDER_CARD_FAIL:
                return "btn btn-danger";
            case self::ORDER_CARD_COMPLETE:
            case self::ORDER_CARD_RECEIVED:
                return "btn btn-success";
            default:
                return "";
        }
    }

    public static function getListOrderByContract($contract_id, $type =null, $status = null){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.contract_id = :contract_id';
        $criteria->params = array(
            ':contract_id'  => $contract_id,
        );
        if(!empty($type)){
            $criteria->addCondition('t.type = '.$type);
        }
        if(!empty($status)){
            $criteria->addCondition('t.status = '.$status);
        }

        return AFTOrders::model()->findAll($criteria);
    }

    /**
     * @param $order_id
     * @param $value
     * @return int
     */
    public static function getOrderTotalCard($order_id, $value)
    {
        $total = 0;
        $card = AFTPackage::model()->findByAttributes(array('price' => $value, 'type' => AFTPackage::FT_PACKAGE_TYPE_CARD));
        $criteria = new CDbCriteria();
        $criteria->condition = 't.order_id = :order_id AND t.item_id = :item_id';
        $criteria->params = array(
            ':order_id' => $order_id,
            ':item_id'  => $card->id,
        );
        $model = AFTOrderDetails::model()->find($criteria);
        if($model){
            $total = $model->quantity * $model->price;
        }
        return $total;
    }

    public static function getOrderCardQuantity($order_id, $value)
    {
        $quantity = 0;
        $card = AFTPackage::model()->findByAttributes(array('price' => $value, 'type' => AFTPackage::FT_PACKAGE_TYPE_CARD));
        $criteria = new CDbCriteria();
        $criteria->condition = 't.order_id = :order_id AND t.item_id = :item_id';
        $criteria->params = array(
            ':order_id' => $order_id,
            ':item_id'  => $card->id,
        );
        $model = AFTOrderDetails::model()->find($criteria);
        if($model){
            $quantity = $model->quantity;
        }
        return $quantity;
    }

    /**
     * @param $order_id
     * @return AFTOrderDetails[]
     */
    public static function getListCardByOrder($order_id)
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'INNER JOIN tbl_package p ON t.item_id = p.id';
        $criteria->select = 'p.price as raw_price, t.*';
        $criteria->condition = 't.order_id = :order_id';
        $criteria->params = array(
            ':order_id' => $order_id
        );
        $criteria->order = 'p.price ASC';

        return AFTOrderDetails::model()->findAll($criteria);
    }

    public function getFileSimVisible()
    {
        $user = AFTUsers::getUserByContract($this->contract_id);
        if($user && $user->user_type == AFTUsers::USER_TYPE_CTV){
            return false;
        }else{
            return true;
        }
    }


    public static function getListPayment()
    {
        return array(
            self::PAYMENT_METHOD_COD        => "Thanh toán trực tiếp",
            self::PAYMENT_METHOD_TRANSFER   => "Chuyển khoản",
        );
    }

    public static function getPaymentLabel($payment)
    {
        $data = self::getListPayment();
        return (isset($data[$payment])) ? $data[$payment] : $payment;
    }

    public static function getOrderCode($order_id)
    {
        $criteria = new CDbCriteria();
        $criteria->select = 't.code';
        $criteria->condition = 't.id = :id';
        $criteria->params = array(
            ':id' => $order_id
        );
        $model = AFTOrders::model()->find($criteria);
        return ($model) ? $model->code : '';
    }

    /**
     * gửi sms cho khách hàng và admin
     * @return bool
     */
    public function sendNotification()
    {
        $contract_code = AFTContracts::getContractCode($this->contract_id);
        if ($this->province_code) {
            $this->province_code = AProvince::getProvinceVnpByCode($this->province_code);
        }
        $list_admin_online = self::getListAdminOnline();
//        $list_admin_ttkd = self::getListAdminTTKD($this->province_code);
//        $list_admin_pbh = self::getListAdminPBH($this->ward_code);

        $user = AFTUsers::getUserByContract($this->contract_id);
        if($user && $user->user_type == AFTUsers::USER_TYPE_CTV){
            // nội dung sms cho admin
            $msg_admin = Yii::t('tourist/mt_content','message_approve_order_ctv_admin', array(
                '{order_code}' => $this->code,
            ));
        }else{
            // nội dung sms cho admin
            $msg_admin = Yii::t('tourist/mt_content','message_approve_order_admin', array(
                '{order_code}' => $this->code,
                '{contract_code}' => $contract_code,
            ));
        }


        if(!YII_DEBUG){
            foreach ($list_admin_online as $admin){
                $msisdn = (!empty($admin['phone_2'])) ? $admin['phone_2'] : $admin['phone'];
                if(!empty($msisdn)){
                    for($i=0; $i<3; $i++){
                        if($this->sentSMS($msisdn, $msg_admin, AFTOrders::FILE_LOG_SEND_MT)){
                            break;
                        }
                    }
                }
            }
//            // gửi sms cho admin_ttkd
//            foreach ($list_admin_ttkd as $admin){
//                $msisdn = (!empty($admin['phone_2'])) ? $admin['phone_2'] : $admin['phone'];
//                if(!empty($msisdn)){
//                    for($i=0; $i<3; $i++){
//                        if($this->sentSMS($msisdn, $msg_admin, AFTOrders::FILE_LOG_SEND_MT)){
//                            break;
//                        }
//                    }
//                }
//            }
//            // gửi sms cho admin_pbh
//            foreach ($list_admin_pbh as $admin){
//                $msisdn = (!empty($admin['phone_2'])) ? $admin['phone_2'] : $admin['phone'];
//                if(!empty($msisdn)){
//                    for($i=0; $i<3; $i++){
//                        if($this->sentSMS($msisdn, $msg_admin, AFTOrders::FILE_LOG_SEND_MT)){
//                            break;
//                        }
//                    }
//                }
//            }
        }
        return true;
    }


    /**
     * @param $msisdn
     * @param $msgBody
     * @param $file_name
     * @return bool
     */
    public function sentSMS($msisdn, $msgBody,$file_name)
    {
        $logMsg   = array();
        $logMsg[] = array('Start Send MT tourist order' . $file_name . ' Log', 'Start process:' . __LINE__, 'I', time());

        //send MT
        $flag = Utils::sentMtVNP($msisdn, $msgBody, $mtUrl, $http_code, $rs);
        if ($flag) {
            $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T');
            $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
        } else {
            $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T');
            $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
        }

        $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
        $logMsg[] = array($rs, 'rawData: ' . __LINE__, 'T', time());
        $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

        $logFolder  = "Log_send_mt_tourist/" . date("Y/m/d") . $file_name;

        $logObj     = ATraceLog::getInstance($logFolder);
        $logObj->setLogFile($this->code . '.log');
        $logMsg[] = array($this->code, 'Finish process-' . __LINE__, 'F', time());
        $logObj->processWriteLogs($logMsg);

        return $flag;
    }

    public static function sentMtVNP($msisdn, $msgBody, &$api_url, &$http_code = '', &$rs = '')
    {
        $msisdn = CFunction_MPS::makePhoneNumberStandard($msisdn);
        $mtseq  = time() . rand(1000, 9999);

        $smsMtRequest = array(
            'username'   => 'freedoo01',
            'password'   => 'CentEch2o17FREEdoo',
            'dest'       => $msisdn,
            'msgtype'    => 'Text',
            'cpid'       => '',
            'src'        => 'FREEDOO',
            'procresult' => 0,
            'mtseq'      => $mtseq,
            'msgbody'    => $msgBody,
            'serviceid'  => '',
        );

        $api_url = str_replace('?', '', $GLOBALS['config_common']['api']['sms_gw']) . '?' . http_build_query($smsMtRequest);

        $rs = Utils::cUrlGet($api_url, 10, $http_code);
        if ($http_code == '200' || $rs == '200') {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Lấy danh sách các tài khoản admin cần gửi sms khi đặt hàng (fix)
     * @return array | null
     */
    public static function getListAdminOnline()
    {
        $list_user = array(
            'admin',
            'giangpq',
            'admin_minhlt',
            'admin_hanhdt',
            'chidtk'
        );
        $arr_user = "'".implode("','",$list_user)."'";

        $command = Yii::app()->db->createCommand("SELECT t.id, t.username, t.email, t.phone, t.phone_2 FROM tbl_users t
            WHERE t.username IN ($arr_user) AND t.status = 1");

        if($result = $command->queryAll()) {
            return $result;
        }else{
            return null;
        }
    }

    public static function getListAdminTTKD($province_code)
    {
        $itemname = 'TTKD';
        $command = Yii::app()->db->createCommand("SELECT t.id, t.username, t.email, t.phone, t.phone_2 FROM tbl_users t
            INNER JOIN tbl_authassignment a ON t.id = a.userid
            WHERE t.province_code = :province_code AND a.itemname = :itemname AND t.status = 1")
            ->bindParam(':province_code', $province_code)
            ->bindParam(':itemname', $itemname);

        if($result = $command->queryAll()) {
            return $result;
        }else{
            return null;
        }
    }

    public static function getListAdminPBH($ward_code)
    {
        $sale_offices_code = 0;
        $sale_offices = SaleOffices::model()->findByAttributes(array('ward_code' => $ward_code));
        if($sale_offices){
            $sale_offices_code = $sale_offices->code;

            $itemname = 'PBH_DN';
            $command = Yii::app()->db->createCommand("SELECT t.id, t.username, t.email, t.phone, t.phone_2 FROM tbl_users t
            INNER JOIN tbl_authassignment a ON t.id = a.userid
            WHERE t.sale_offices_id  = :sale_offices_id AND a.itemname = :itemname AND t.status = 1")
                ->bindParam(':sale_offices_id', $sale_offices_code)
                ->bindParam(':itemname', $itemname);

            if($result = $command->queryAll()) {
                return $result;
            }else{
                return null;
            }
        }
        return null;
    }


    public function getOrdersCtvByUser($user_id, $listData = TRUE)
    {
        $data = array();
        $contract = AFTContracts::model()->find('user_id =:user_id',
            array(
                ':user_id' => $user_id,
            )
        );
        if($contract){
            $result = AFTOrders::getListOrderByContract($contract->id, AFTOrders::TYPE_SIM);

            if($listData){
                $data = CHtml::listData($result, 'id', 'code');
            }else{
                $data = $result;
            }
        }

        return $data;
    }

    public static function getOrderCodeById($id)
    {
        $cache_key = "AFTOrders_getOrderCodeById_$id";
        $result = Yii::app()->cache->get($cache_key);
        if($result){
            return $result;
        }
        $model = AFTOrders::model()->findByPk($id);
        if($model){
            $result = $model->code;
        }

        Yii::app()->cache->set($cache_key, $result, 60*60*24);
        return $result;
    }

}
