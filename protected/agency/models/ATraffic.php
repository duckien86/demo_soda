<?php

    class ATraffic extends Orders
    {
        public $status;
        public $item_id;
        public $item_name;
        public $ward_code;
        public $brand_office_id;
        public $shipper_name;
        public $detail_id;
        public $start_date;
        public $end_date;
        public $status_assign; // Trạng thái phân công.
        public $status_order;
        public $status_shipper; // Trạng thái giao vận
        public $time_left;
        public $note;
        public $note_state;
        public $input_type;

        public $amount_sim; // Tiền sim theo đơn hàng.
        public $amount_package; // Tiền gói cước theo đơn hàng.
        public $amount_term; // Tiền đặt cọc theo gói cước.
        public $amount_shipper; // Tiền phí vận chuyển.
        public $amount_rose; // Tiền hoa hồng.
        public $total_amount; // Tổng tiền.
        public $amount_receive; // Tổng tiền phải thu.

        public $sale_office_code;
        public $brand_offices_id;
        public $total_package;
        public $delivered_date;

        public $price_sim;
        public $price_package;
        public $price_term;
        public $price_ship;
        public $type_sim;

        public $total_revenue;
        public $receiver;

        const ASSIGN     = 1;
        const NOT_ASSIGN = 2;
        const ALL        = 0;


        const NOT_SHIP         = 0;
        const SHIPPED          = 1;
        const NOT_RECEIVED     = 1;
        const RECEIVED         = 2;
        const ORDER_RETURN     = 3;
        const CANCEL           = 4;
        const NOT_ASSIGN_ORDER = -1;

        const EXPIRE   = 0;
        const NOEXPIRE = 1;
        const WARNING  = 2;


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('start_date, end_date', 'required', 'on' => 'admin,admin_assign,,renueve_traffic'),
//                array('start_date, end_date', 'required', 'on' => 'admin_assign'),
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code, ,ward_code', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, full_name, address_detail, otp, affiliate_transaction_id, affiliate_source', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('customer_note', 'length', 'max' => 500),
                array('status_assign, status_shipper', 'length', 'max' => 20),
                array('last_update,sale_office_code, receive_cash_by, receive_cash_date, delivered_date,
                    price_sim, price_package, price_term, price_ship, total_revenue,
                    receiver, type_sim', 'safe'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, invitation, create_date, time_left, last_update, shipper_id,ward_code, status_assign, 
                delivery_type, payment_method, full_name, district_code, province_code, address_detail, phone_contact, 
                customer_note, otp, status, item_id, receive_cash_by, receive_cash_date, affiliate_transaction_id, affiliate_source', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                'detail' => array(self::HAS_MANY, 'AOrderDetails', 'order_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'                       => 'Mã ĐH',
                'sso_id'                   => Yii::t('adm/label', 'sso_id'),
                'promo_code'               => Yii::t('adm/label', 'promo_code'),
                'invitation'               => Yii::t('adm/label', 'invitation'),
                'create_date'              => 'Ngày đặt hàng',
                'last_update'              => Yii::t('adm/label', 'last_update'),
                'shipper_id'               => "NV giao vận",
                'delivery_type'            => Yii::t('adm/label', 'delivery_type'),
                'payment_method'           => Yii::t('adm/label', 'payment_method'),
                'personal_id'              => Yii::t('adm/label', 'personal_id'),
                'full_name'                => "Họ tên KH",
                'birthday'                 => Yii::t('adm/label', 'birthday'),
                'district_code'            => 'Phòng bán hàng',
                'province_code'            => 'TTKD',
                'address_detail'           => Yii::t('adm/label', 'address_detail'),
                'phone_contact'            => 'SĐT liên hệ',
                'customer_note'            => Yii::t('adm/label', 'customer_note'),
                'status'                   => Yii::t('adm/label', 'status'),
                'item_id'                  => Yii::t('adm/label', 'item_id'),
                'otp'                      => Yii::t('adm/label', 'otp'),
                'affiliate_transaction_id' => Yii::t('adm/label', 'affiliate_transaction_id'),
                'affiliate_source'         => Yii::t('adm/label', 'affiliate_source'),
                'start_date'               => "Ngày bắt đầu",
                'end_date'                 => "Ngày kết thúc",
                'ward_code'                => "Phường xã",
                'brand_office_id'          => "Điểm giao dịch",
                'status_assign'            => "Trạng thái phân công",
                'status_order'             => "Đơn hàng",
                'status_shipper'           => 'Trạng thái thu tiền',
                'time_left'                => 'Còn lại',
                'item_name'                => 'Số sim',
                'amount_sim'               => 'Tiền sim',
                'amount_package'           => 'Tiền gói',
                'amount_term'              => 'Tiền đặt cọc',
                'amount_shipper'           => 'Phí vận chuyển',
                'amount_rose'              => 'Hoa hồng',
                'total_amount'             => 'Tổng tiền',
                'amount_receive'           => 'Tiền phải thu',
                'note'                     => 'Lý do',
                'sale_office_code'         => 'Phòng BH',
                'brand_offices_id'         => 'Điểm giao dịch',
                'total_renueve'            => 'Tổng doanh thu',
                'receive_cash_date'        => 'Ngày thu',
                'receive_cash_by'          => 'Người  thu',
                'input_type'               => 'Hình thức',
                'delivered_date'           => 'Ngày hoàn tất'
            );
        }

        /**
         * @param bool $dataProvider
         *
         * @return ATraffic[] | CArrayDataProvider
         */
        public function search($dataProvider = TRUE)
        {
            $result = array();

            $criteria         = new CDbCriteria();
            $criteria->select = "
                t.*, 
                (SELECT create_date FROM tbl_order_state WHERE id = (SELECT max(id) FROM tbl_order_state WHERE order_id = t.id)) AS 'delivered_date',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'sim') AS 'price_sim',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'price_package',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'price_term') AS 'price_term',
                (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'price_ship') AS 'price_ship',
                (SELECT type FROM tbl_sim WHERE order_id = t.id) AS 'type_sim',
                CASE
                    WHEN (t.shipper_id IS NOT NULL AND t.shipper_id != '')
                    THEN (SELECT full_name FROM tbl_shipper WHERE id = t.shipper_id)
                    ELSE ''
                END AS 'shipper_name',
                CASE
                    WHEN (t.receive_cash_by IS NOT NULL AND t.receive_cash_by != '')
                    THEN (SELECT username FROM tbl_users WHERE id = t.receive_cash_by)
                    ELSE ''
                END AS 'receiver',
                CASE
                    WHEN (t.receive_cash_by IS NOT NULL AND t.receive_cash_by != '')
                    THEN 2
                    ELSE 1
                END AS 'status_shipper'
            ";

            if (!empty($this->start_date) && !empty($this->end_date)) {
                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

                $criteria->condition = "
                    t.id IN (SELECT order_id FROM tbl_order_state WHERE 
                        create_date >= '$start_date' AND create_date <= '$end_date'
                        AND delivered = 10
                    )
                    AND t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'sim')
                ";
            }else{
                $criteria->condition = "
                    t.id IN (SELECT order_id FROM tbl_order_state WHERE delivered = 10)
                    AND t.id IN (SELECT order_id FROM tbl_order_details WHERE type = 'sim')
                ";
            }

            if (!ADMIN && !SUPER_ADMIN) {
                if ( isset(Yii::app()->user->province_code)
                    && ( !isset(Yii::app()->user->sale_offices_id)
                        || empty(Yii::app()->user->sale_offices_id) )
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if ( isset(Yii::app()->user->province_code)
                    && isset(Yii::app()->user->sale_offices_id)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && ( !isset(Yii::app()->user->brand_offices_id)
                        || empty(Yii::app()->user->brand_offices_id) )
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if ( isset(Yii::app()->user->brand_offices_id)
                    && !empty(Yii::app()->user->brand_offices_id)
                ) {
                    $criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
                }

                $now = date('Y-m-d H:i:s');
                $criteria->addCondition("t.create_date <= '$now'");
            }

            if (!empty($this->province_code)) {
                $criteria->compare('t.province_code', $this->province_code, FALSE);
            }
            if (!empty($this->sale_office_code)) {
                $criteria->compare('t.sale_office_code', $this->sale_office_code, FALSE);
            }
            if (!empty($this->brand_offices_id)) {
                $criteria->compare('t.address_detail', $this->brand_offices_id, FALSE);
            }

            if(!empty($this->status_shipper)){
                if($this->status_shipper == self::NOT_RECEIVED){
                    $criteria->addCondition("t.receive_cash_by IS NULL OR t.receive_cash_by = ''");
                }else{
                    $criteria->addCondition("t.receive_cash_by IS NOT NULL AND t.receive_cash_by != ''");
                }
            }

            if ($this->delivery_type != '') {
                $criteria->addCondition("t.delivery_type ='" . $this->delivery_type . "'");
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method ='" . $this->payment_method . "'");
            }

            if (!empty($this->id)) {
                $criteria->compare('t.id', $this->id, TRUE);
            }

            $criteria->order = "t.create_date DESC";

//            $data_raw = ATraffic::model()->findAll($criteria);
//
//            if(!empty($data_raw)) {
//
//                $criteriaLogSim = new CDbCriteria();
//                $criteriaLogSim->select = "
//                    t.order_id,
//                    t.user_id,
//                    (SELECT username FROM tbl_users WHERE id = t.user_id) AS 'username'
//                ";
//
//                foreach ($data_raw as $order){
//                    if(empty($order->shipper_name)){
//                        $criteriaLogSim->condition = "t.order_id = '$order->id'";
//                        $logSim = ALogsSim::model()->find($criteriaLogSim);
//                        if($logSim){
//                            $order->shipper_name = $logSim->username;
//                        }
//                    }
//                }
//            }

//            $result = $data_raw;

            if($dataProvider){
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[start_date]"       => $this->start_date,
                            "ATraffic[end_date]"         => $this->end_date,
                            "ATraffic[province_code]"    => $this->province_code,
                            "ATraffic[sale_office_code]" => $this->sale_office_code,
                            "ATraffic[brand_offices_id]" => $this->brand_offices_id,
                            "ATraffic[status_shipper]"   => $this->status_shipper,
                            "ATraffic[delivery_type]"    => $this->delivery_type,
                            "ATraffic[payment_method]"   => $this->payment_method,
                            "ATraffic[id]"               => $this->id,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }else{
                return ATraffic::model()->findAll($criteria);
            }

        }

        public function search_assign($status_assign = '', $pagination = TRUE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria         = new CDbCriteria;
            $criteria->select = "t.*,IF (od.type='sim',od.item_name,'') as item_name";



            if ($this->id != '') {
                $criteria->compare('t.id', $this->id, TRUE);
            } else {
                $criteria->compare('t.sso_id', $this->sso_id, TRUE);
            }

            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('t.district_code', $this->district_code, TRUE);
                $criteria->compare('t.province_code', $this->province_code, TRUE);
                $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            } else {

                if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
                        || empty(Yii::app()->user->sale_offices_id))
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
                    $criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
                }

                $now = date('Y-m-d H:i:s');
                $criteria->addCondition("t.create_date < '$now'");
            }
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='" . $this->start_date . "' and t.create_date <='" . $this->end_date . "'");
            }
            $criteria->compare('t.brand_offices_id', $this->brand_offices_id, FALSE);
            $criteria->compare('t.sale_office_code', $this->sale_office_code, TRUE);
            $criteria->addCondition("t.payment_method != '' and t.delivery_type=1");
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            $criteria->addCondition("os.confirm = 10 and s.status !=10");
            $criteria->addCondition("t.shipper_id='' or t.shipper_id is NULL");

            //lọc filter status.
            $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                               INNER JOIN {{order_details}} od ON od.order_id = t.id
                               LEFT JOIN {{sim}} s ON s.order_id = t.id";
            
            $criteria->group = 't.id';

            if ($pagination) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[start_date]"       => $this->start_date,
                            "ATraffic[end_date]"         => $this->end_date,
                            "ATraffic[province_code]"    => $this->province_code,
                            "ATraffic[sale_office_code]" => $this->sale_office_code,
                            "ATraffic[ward_code]"        => $this->ward_code,
                            "ATraffic[status_assign]"    => $this->status_assign,
                        ),
                        'pageSize' => 100,
                    ),
                ));
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_date ASC',
                ),
                'pagination' => array(
                    'params'   => array(
                        "ATraffic[start_date]"       => $this->start_date,
                        "ATraffic[end_date]"         => $this->end_date,
                        "ATraffic[province_code]"    => $this->province_code,
                        "ATraffic[sale_office_code]" => $this->sale_office_code,
                        "ATraffic[ward_code]"        => $this->ward_code,
                        "ATraffic[status_assign]"    => $this->status_assign,
                    ),
                    'pageSize' => 9999999,
                ),
            ));

        }

        public function search_renueve_report($status_traffic = '', $pagination = TRUE, $excel = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            if ($this->start_date && $this->end_date) {
                $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria         = new CDbCriteria;
            $criteria->select = "t.*";

            $now = date('Y-m-d H:i:s');
            $criteria->addCondition("t.create_date < '$now'");

            if ($this->id != '') {
                $criteria->compare('t.id', $this->id, TRUE);
            } else {
                $criteria->compare('t.sso_id', $this->sso_id, TRUE);
            }
            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('t.district_code', $this->district_code, TRUE);
                $criteria->compare('t.province_code', $this->province_code, TRUE);
                $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            } else {
                if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
                        || empty(Yii::app()->user->sale_offices_id))
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
                    $criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
                }
            }
//
            if ($start_date && $end_date) {
                $criteria->addCondition("so.assign_date >='" . $start_date . "' and so.assign_date <='" . $end_date . "'");
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }

            if ($this->shipper_id != '') {
                $criteria->addCondition("so.shipper_id = '" . $this->shipper_id . "'");
            }
            if ($this->status_shipper == ATraffic::SHIPPED) {

                $criteria->addCondition("so.status='1' AND so.order_status !='2' AND os.confirm NOT IN('1','2','3')");
            } else if ($this->status_shipper == ATraffic::RECEIVED) {

                $criteria->addCondition("so.status='2'");
            } else if ($this->status_shipper == ATraffic::ORDER_RETURN) {

                $criteria->addCondition("os.confirm='3'");
            } else if ($this->status_shipper == 4) { //Hủy

                $criteria->addCondition("os.confirm IN ('2')");
            } else if ($this->status_shipper == 5) { //Chưa giao
                $criteria->addCondition("so.status='0' AND os.confirm NOT IN('1','2','3') AND so.order_status ='0'");
            }

            $criteria->compare('t.address_detail', $this->brand_office_id, FALSE);
            $criteria->compare('t.sale_office_code', $this->sale_office_code, TRUE);
            $criteria->addCondition("t.payment_method != '' and t.delivery_type =1");

            //lọc filter status.
            if ($this->status_shipper != '') {
                $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            }
            $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                               INNER JOIN {{order_details}} od ON od.order_id = t.id
                               LEFT JOIN {{shipper_order}} so ON so.order_id = t.id";

            $criteria->group = 't.id';

            if ($pagination) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date DESC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[start_date]"       => $this->start_date,
                            "ATraffic[end_date]"         => $this->end_date,
                            "ATraffic[province_code]"    => $this->province_code,
                            "ATraffic[sale_office_code]" => $this->sale_office_code,
                            "ATraffic[ward_code]"        => $this->ward_code,
                            "ATraffic[status_shipper]"   => $this->status_shipper,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }

            if ($excel) {
                $criteria->order = 't.create_date DESC';
                $data            = AOrders::model()->findAll($criteria);

                return $data;
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_date DESC',
                ),
                'pagination' => array(
                    'params'   => array(
                        "ATraffic[start_date]"       => $this->start_date,
                        "ATraffic[end_date]"         => $this->end_date,
                        "ATraffic[province_code]"    => $this->province_code,
                        "ATraffic[sale_office_code]" => $this->sale_office_code,
                        "ATraffic[ward_code]"        => $this->ward_code,
                        "ATraffic[status_assign]"    => $this->status_assign,
                    ),
                    'pageSize' => 9999999,
                ),
            ));

        }

        /**
         * @param string $status
         * Lấy doanh thu tổng quan
         *
         * @return \___PHPSTORM_HELPERS\static|int
         */
        public function getTotalRenueveByStatus($status = '', $package = FALSE, $shipper = FALSE)
        {

            $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
            $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

            $criteria = new CDbCriteria();
            if ($package == TRUE && $shipper == FALSE) {
                $criteria->select = "COUNT(DISTINCT t.id) as total, IF (od.type ='package',SUM(od.price),0) as total_renueve";
            } else if ($shipper == TRUE && $package == FALSE) {
                $criteria->select = "COUNT(DISTINCT t.id) as total, IF (od.type ='price_ship',SUM(od.price),0) as total_renueve";
            } else {
                $criteria->select = "COUNT(DISTINCT t.id) as total, SUM(od.price) as total_renueve";
            }

            $now = date('Y-m-d H:i:s');
            $criteria->addCondition("t.create_date < '$now'");

            if ($start_date && $end_date) {
                $criteria->condition = "so.assign_date >='" . $start_date . "' and so.assign_date <='" . $end_date . "'";
            }
            if ($status == ATraffic::SHIPPED) {

                $criteria->addCondition("so.status='1' AND so.order_status !='2' AND os.confirm NOT IN('1','2','3')");
            } else if ($status == ATraffic::RECEIVED) {
                $criteria->addCondition("so.status='2'");
            } else if ($status == ATraffic::ORDER_RETURN) {
                $criteria->addCondition("os.confirm='3'");
            } else if ($status == 4) { //Hủy

                $criteria->addCondition("os.confirm IN ('2')");
            } else if ($status == 5) { //Chưa giao
                $criteria->addCondition("so.status='0' AND os.confirm NOT IN('1','2','3')  AND so.order_status ='0'");
            }

            if ($this->province_code != '') {
                $criteria->addCondition("t.province_code = '" . $this->province_code . "'");
            }
            if ($this->sale_office_code != '') {
                $criteria->addCondition("t.sale_office_code = '" . $this->sale_office_code . "'");
            }
            if ($this->brand_offices_id != '') {
                $criteria->addCondition("t.address_detail = '" . $this->brand_office_id . "'");
            }
            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method = '" . $this->payment_method . "'");
            }
            if ($this->shipper_id != '') {
                $criteria->addCondition("so.shipper_id = '" . $this->shipper_id . "'");
            }
            if ($package == TRUE && $shipper == FALSE) {
                $criteria->addCondition("s.type=2 AND od.type IN('package')");
            } else if ($shipper == TRUE && $package == FALSE) {
                $criteria->addCondition("od.type IN('price_ship')");
            }
            $criteria->addCondition("t.payment_method != '' and t.delivery_type =1");
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            if ($package == TRUE && $shipper == FALSE) {
                $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                               INNER JOIN {{order_details}} od ON od.order_id = t.id
                               INNER JOIN {{shipper_order}} so ON so.order_id = t.id
                               LEFT JOIN {{sim}} s ON s.order_id = t.id";
            } else {
                $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                               INNER JOIN {{order_details}} od ON od.order_id = t.id
                               INNER JOIN {{shipper_order}} so ON so.order_id = t.id";
            }
            $total = AOrders::model()->findAll($criteria)[0];

            return isset($total) ? $total : 0;

        }


        public function search_assign_change($status_assign = '', $pagination = TRUE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
            $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

            $criteria         = new CDbCriteria;
            $criteria->select = "t.*,IF (od.type='sim',od.item_name,'') as item_name";

            $now = date('Y-m-d H:i:s');
            $criteria->addCondition("t.create_date < '$now'");

            if ($this->id != '') {
                $criteria->compare('t.id', $this->id, TRUE);
            } else {
                $criteria->compare('t.sso_id', $this->sso_id, TRUE);
            }
            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('t.district_code', $this->district_code, TRUE);
                $criteria->compare('t.province_code', $this->province_code, TRUE);
                $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            } else {
                if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
                        || empty(Yii::app()->user->sale_offices_id))
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
                    $criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
                }
            }
            $criteria->compare('t.address_detail', $this->brand_office_id, FALSE);
            $criteria->compare('t.sale_office_code', $this->sale_office_code, TRUE);
            $criteria->addCondition("t.payment_method != '' and t.delivery_type =1 and s.status !=10");
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            $criteria->addCondition("t.shipper_id !='' AND so.status='" . self::NOT_SHIP . "' AND os.confirm IN (10)");
            //lọc filter status.
            $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                               INNER JOIN {{order_details}} od ON od.order_id = t.id
                               INNER JOIN {{shipper_order}} so ON so.order_id = t.id
                               LEFT JOIN {{sim}} s ON s.order_id = t.id";


            $criteria->group = 't.id';

            if ($pagination) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[start_date]"       => $this->start_date,
                            "ATraffic[end_date]"         => $this->end_date,
                            "ATraffic[province_code]"    => $this->province_code,
                            "ATraffic[sale_office_code]" => $this->sale_office_code,
                            "ATraffic[ward_code]"        => $this->ward_code,
                            "ATraffic[status_assign]"    => $this->status_assign,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_date DESC',
                ),
                'pagination' => array(
                    'params'   => array(
                        "ATraffic[start_date]"       => $this->start_date,
                        "ATraffic[end_date]"         => $this->end_date,
                        "ATraffic[province_code]"    => $this->province_code,
                        "ATraffic[sale_office_code]" => $this->sale_office_code,
                        "ATraffic[ward_code]"        => $this->ward_code,
                        "ATraffic[status_assign]"    => $this->status_assign,
                    ),
                    'pageSize' => 9999999,
                ),
            ));

        }

        public function search_return($status_assign = '', $pagination = TRUE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria         = new CDbCriteria;
            $criteria->select = "t.*,IF (od.type='sim',od.item_name,'') as item_name, os.note as note_state";

            $now = date('Y-m-d H:i:s');
            $criteria->addCondition("t.create_date < '$now'");

            if ($this->id != '') {
                $criteria->compare('t.id', $this->id, TRUE);
            } else {
                $criteria->compare('t.sso_id', $this->sso_id, TRUE);
            }
            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('t.district_code', $this->district_code, TRUE);
                $criteria->compare('t.province_code', $this->province_code, TRUE);
                $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            } else {
                if (Yii::app()->user->province_code && (!isset(Yii::app()->user->sale_offices_id)
                        || empty(Yii::app()->user->sale_offices_id))
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if (Yii::app()->user->province_code && isset(Yii::app()->user->sale_offices_id)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && (!isset(Yii::app()->user->brand_offices_id) || empty(Yii::app()->user->brand_offices_id))
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if (isset(Yii::app()->user->brand_offices_id) && !empty(Yii::app()->user->brand_offices_id)) {
                    $criteria->compare("t.address_detail", Yii::app()->user->brand_offices_id, FALSE);
                }
            }
            $criteria->compare('t.address_detail', $this->brand_office_id, TRUE);
            $criteria->compare('t.sale_office_code', $this->sale_office_code, TRUE);
            $criteria->addCondition("t.payment_method != '' and t.delivery_type =1");
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            $criteria->addCondition("so.order_status =2 AND os.confirm =3 and s.status !=10");
            //lọc filter status.
            $criteria->join  = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                                INNER JOIN {{order_details}} od ON od.order_id = t.id
                                INNER JOIN {{shipper_order}} so ON so.order_id = t.id
                                LEFT JOIN {{sim}} s ON s.order_id = t.id";
            $criteria->group = 't.id';

            if ($pagination) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date DESC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "ATraffic[start_date]"       => $this->start_date,
                            "ATraffic[end_date]"         => $this->end_date,
                            "ATraffic[province_code]"    => $this->province_code,
                            "ATraffic[sale_office_code]" => $this->sale_office_code,
                            "ATraffic[ward_code]"        => $this->ward_code,
                            "ATraffic[status_assign]"    => $this->status_assign,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.create_date DESC',
                ),
                'pagination' => array(
                    'params'   => array(
                        "ATraffic[start_date]"       => $this->start_date,
                        "ATraffic[end_date]"         => $this->end_date,
                        "ATraffic[province_code]"    => $this->province_code,
                        "ATraffic[sale_office_code]" => $this->sale_office_code,
                        "ATraffic[ward_code]"        => $this->ward_code,
                        "ATraffic[status_assign]"    => $this->status_assign,
                    ),
                    'pageSize' => 9999999,
                ),
            ));

        }

        /**
         * @param string $className
         * @return ATraffic
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $type = sim || package || price_term
         */
        public function getRenueveByType($type = '', $order_id, $all = FALSE)
        {
            $state = Sim::model()->findByAttributes(array('order_id' => $order_id));
            if ($type != '') {
                $detail = AOrderDetails::model()->findByAttributes(array('type' => $type, 'order_id' => $order_id));

            } else {
                $criteria = new CDbCriteria();

                $criteria->select = "sum(price) as total_receive";
                if ($all) {
                    if (isset($state) && $state->type == ASim::TYPE_POSTPAID) {
                        $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','price_term')";
                    } else {
                        $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package','price_term')";
                    }
                } else {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package')";
                }


                $detail = AOrderDetails::model()->findAll($criteria)[0]->total_receive;

                return $detail;

            }

            return isset($detail->price) ? $detail->price : 0;
        }


        /**
         * @param $order_id
         * Lấy trạng thái giao vận
         *
         * @return int|string
         */
        public function getStatus($order_id)
        {
            $status = '';
            if ($order_id) {
                $shipper_order = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                if ($shipper_order) {
                    if ($shipper_order->shipper_id != '') {
                        $status = $shipper_order->status;
                    } else {
                        $status = -1;
                    }
                } else {
                    $status = -1;
                }
            }

            return $status;
        }

        public function getTimeLeft($create_date)
        {
            $time_left = array(
                'time'   => 0,
                'status' => self::NOEXPIRE, // 0: Qúa hạn, 1: Còn hạn, 2: Cảnh báo
            );
            if ($create_date) {
                $sub_time = strtotime(date('Y-m-d H:i:s')) - strtotime($create_date);
                $days     = floor($sub_time / (24 * 60 * 60));
                $hour     = floor($sub_time / (60 * 60));
                $min      = date('i', $sub_time);
                if ($hour > 48) {
                    $time_left['time']   = "Quá hạn (" . ($hour - 48) . "h " . $min . " min)";
                    $time_left['status'] = self::EXPIRE;
                } else {
                    if ($hour == 0) {
                        $time_left['time']   = "47h " . (60 - $min) . "min";
                        $time_left['status'] = self::NOEXPIRE;
                    } else {
                        if ($hour > 36) {
                            $time_left['time']   = (48 - $hour) . "h " . (60 - $min) . "min";
                            $time_left['status'] = self::WARNING;
                        } else {
                            $time_left['time']   = (48 - $hour) . "h " . (60 - $min) . "min";
                            $time_left['status'] = self::NOEXPIRE;
                        }
                    }

                }
            }

            return $time_left;
        }

        // Tính thời gian phân công còn lại.
        public function getTimeLeftAssign($create_date)
        {
            $time_left = array(
                'time'   => 0,
                'status' => self::NOEXPIRE, // 0: Qúa hạn, 1: Còn hạn
            );
            if ($create_date) {
                $sub_time = strtotime(date('Y-m-d H:i:s')) - strtotime($create_date);
                $days     = floor($sub_time / (24 * 60 * 60));
                $hour     = floor($sub_time / (60 * 60));
                $min      = date('i', $sub_time);
                if ($hour > 36) {
                    $time_left['time']   = "Quá hạn (" . ($hour - 36) . "h" . $min . " min)";
                    $time_left['status'] = self::EXPIRE;
                } else {
                    if ($hour == 0) {
                        $time_left['time'] = "35" . (60 - $min) . "min";
                    } else {
                        $time_left['time'] = (36 - $hour) . "h " . (60 - $min) . "min";
                    }
                    $time_left['status'] = self::NOEXPIRE;
                }
            }

            return $time_left;
        }

        /**
         * @param string $type     //Loại doanh thu
         * @param string $order_id //Mã đơn hàng
         *
         */
        public function getRenuveTraffic($type = 'sim', $order_id)
        {
            $renueve = 0;
            if ($order_id) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "order_id='" . $order_id . "'";
                $order_detail        = AOrderDetails::model()->findAll($criteria);
                if (!empty($order_detail)) {
                    foreach ($order_detail as $key => $detail) {
                        if ($detail->type == $type) {
                            $renueve = $detail->price;
                        }
                    }
                }
            }

            return $renueve;
        }

        public function getNote($order_id)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = " id =(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = '$order_id')";
            $state               = AOrderState::model()->findAll($criteria);
            if ($state) {
                return $state->note;
            }

            return "";
        }

        /**
         * get list status
         *
         * @return array
         */
        public function getAllStatus()
        {
            return array(
                CskhOrderState::UNDELIVERED => Yii::t('adm/label', 'order_pending'),
                CskhOrderState::DELIVERED   => Yii::t('adm/label', 'order_complete'),
            );
        }

        /**
         * get label status
         *
         * @param $status
         *
         * @return mixed
         */
        public function getStatusLabel($status)
        {
            $array_status = $this->getAllStatus();

            return $array_status[$status];
        }

        public function getAllDeliveredType()
        {
            return array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );
        }

        /**
         * @param $shipper_id
         *
         * @return string
         */
        public function getShipperName($shipper_id)
        {
            if ($shipper_id) {
                $shipper = AShipper::model()->find('id=:id', array(':id' => $shipper_id));
                if ($shipper) {
                    return ($shipper) ? CHtml::encode($shipper->full_name) : $shipper_id;
                }
            }

            return $shipper_id;

        }

        public function getTrafficShipperName()
        {
            $result = '';
            if(!empty($this->shipper_id)){
                $cache_key = "ATraffic_shipper_id_$this->shipper_id";

                $result = Yii::app()->cache->get($cache_key);
                if(!$result){
                    $shipper = AShipper::model()->find('id=:id', array(':id' => $this->shipper_id));

                    $result = ($shipper) ? $shipper->full_name : $this->shipper_id;
                    Yii::app()->cache->set($cache_key, $result, 60*10);
                }
            }else{
                $logSim = ALogsSim::model()->findByAttributes(array('order_id' => $this->id));
                if ($logSim) {
                    $user   = User::model()->findByAttributes(array('id' => $logSim->user_id));
                    $result = $user->username;
                }
            }
            return $result;
        }

        /**
         * @param $sso_id
         *
         * @return string
         */
        public function getUsername($sso_id)
        {
            $customer = array();
            if ($sso_id) {
                $customer = Customers::model()->find('sso_id=:sso_id', array(':sso_id' => $sso_id));
            }

            return ($customer) ? CHtml::encode($customer->username) : $sso_id;
        }

        /**
         * Lấy tất cả tỉnh theo quyền.
         */
        public function getAllProvince()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Province::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else {
                if (isset(Yii::app()->user->id)) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->province_code != '') {
                            $criteria            = new CDbCriteria();
                            $criteria->condition = "code = '" . $user->province_code . "'";

                            $data = Province::model()->findAll($criteria);

                            return CHtml::listData($data, 'code', 'name');
                        }
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllDistrict()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = District::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->district_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "code = '" . $user->district_code . "'";

                        $data = District::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }

            }

            return $return;
        }

        /**
         * Lấy tất cả phường xã
         */
        public function getAllWard()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Ward::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->ward_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "id = '" . $user->ward_code . "'";

                        $data = Ward::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả điểm giao dịch.
         */
        public function getAllBrandOffice()
        {
            $data = BrandOffices::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * @param $code
         * Lấy tỉnh thành theo code
         *
         * @return string
         */
        public function getProvince($code)
        {
            $province = array();
            if ($code) {
                $province = Province::model()->find('code=:code', array(':code' => $code));
            }

            return ($province) ? CHtml::encode($province->name) : $code;
        }

        /**
         * @param $code
         * Lấy quận huyện theo code
         *
         * @return string
         */
        public function getDistrict($code)
        {

            $district = array();
            if ($code) {
                $district = District::model()->find('code=:code', array(':code' => $code));
            }

            return ($district) ? CHtml::encode($district->name) : $code;
        }

        /**
         * Lấy quận huyện theo tỉnh
         *
         * @param $code
         */
        public function getDistrictByProvince($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "province_code = '" . $code . "'";

            $data = District::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }


        /**
         * @param $code
         * Lấy quận huyện theo code
         *
         * @return string
         */
        public function getWard($code)
        {
            $ward = array();
            if ($code) {
                $ward = AWard::model()->find('code=:code', array(':code' => $code));
            }

            return ($ward) ? CHtml::encode($ward->name) : $code;
        }

        /**
         * Lấy hình thức giao dịch.
         */
        public function getDeliveryType($type)
        {
            $array = array(
                1 => 'tại nhà',
                2 => 'tai phong ban hang',
            );

            return $array[isset($type) ? $type : 1];
        }

        public function getShipper($order_id)
        {
            $result        = "";
            $shipper_order = ShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
            if ($shipper_order) {
                $shipper = Shipper::model()->findByAttributes(array('id' => $shipper_order->shipper_id));

                return $shipper->username;
            }

            return $result;
        }

        public function getAllShipper()
        {
            $data = AShipper::model()->findAll();

            return CHtml::listData($data, 'id', 'username');
        }

        /**
         * Lấy dữ liệu trạng thái assign.
         */
        public function getStatusAssign()
        {
            return array(
                self::ALL        => "Tất cả",
                self::ASSIGN     => "Phân công",
                self::NOT_ASSIGN => "Chưa phân công",
            );
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getAllStatusTraffic()
        {

            $data = array(
                -1             => "Chưa phân công",
                self::NOT_SHIP => "Chưa giao",
                self::SHIPPED  => "Đã giao",
                self::RECEIVED => "Đã thu",
            );

            return $data;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getAllStatusTrafficAdmin()
        {

            $data = array(
                self::SHIPPED  => "Chưa thu",
                self::RECEIVED => "Đã thu",
            );

            return $data;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getAllStatusTrafficAdminByid($id)
        {
            $status = 0;
            $orders = AOrders::model()->findByAttributes(array('id' => $id));
            if ($orders) {
                if (!empty($orders->receive_cash_by) && !empty($orders->receive_cash_date)) {
                    $status = 'Đã thu';
                } else {
                    $status = 'Chưa thu';
                }
            }

            return $status;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getAllStatusReport()
        {

            $data = array(
                5                  => "Chưa giao",
                self::SHIPPED      => "Đã giao",
                self::RECEIVED     => "Đã thu",
                self::ORDER_RETURN => "Gửi trả",
                self::CANCEL       => "Hủy",
            );

            return $data;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getStatusAdmin($id)
        {
            $data = array(
                self::SHIPPED  => "Chưa thu",
                self::RECEIVED => "Đã thu",

            );

            return isset($data[$id]) ? $data[$id] : $id;
        }

        public function getAllStatusAdmin()
        {

            $data = array(
                self::NOT_RECEIVED  => "Chưa thu",
                self::RECEIVED => "Đã thu",

            );

            return $data;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getStatusTraffic($status)
        {
            $data = array(
                self::NOT_SHIP => "Chưa giao",
                self::SHIPPED  => "Đã giao",
                self::RECEIVED => "Đã thu",
            );

            return isset($data[$status]) ? $data[$status] : "Chưa phân công";
        }

        public function getAllPaymentMethod()
        {
            return array(
                1 => 'QR CODE',
                2 => 'THẺ ATM NỘI ĐỊA',
                3 => 'THẺ ATM QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK',
            );

        }

        public static function getPaymentMethod($payment_method)
        {
            $data = array(
                1 => 'QR CODE',
                2 => 'THẺ ATM NỘI ĐỊA',
                3 => 'THẺ ATM QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK',
            );

            return isset($data[$payment_method]) ? $data[$payment_method] : '';
        }


        public function getPriceShip($order_id)
        {
            $order_details = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'price_ship'));
            if ($order_details) {
                return $order_details->price;
            }

            return 0;
        }

        public function getAllPriceShip()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria = new CDbCriteria();
            if ($this->start_date && $this->end_date) {
                $criteria->condition = "";
            }

            return 0;
        }

        public function getTitleRenueve($id)
        {
            $data = array(
                1 => 'Tổng đơn hàng đã giao',
                2 => 'Tổng đơn hàng đã thu',
                5 => 'Tổng đơn hàng chưa giao',
                3 => 'Tổng đơn hàng gửi trả',
                4 => 'Tổng đơn hàng bị hủy',
            );

            return isset($data[$id]) ? $data[$id] : 0;
        }

        /**
         * @param       $records
         * @param array $columns
         * Lấy cột footer Tổng của tbgridView.
         *
         * @return array
         */
        public function getTotal($records, $columns = array())
        {
            $total = array();


            foreach ($records as $record) {

                foreach ($columns as $column) {
                    if (!isset($total[$column])) $total[$column] = 0;
                    $total[$column] += $record[$column];
                }
            }

            return $total;
        }

        public function getTrafficTotalRevenue()
        {
            $total_revenue = 0;
            if($this->type_sim == ASim::TYPE_PREPAID){
                $total_revenue = $this->price_sim + $this->price_term + $this->price_package;
            }else if($this->type_sim == ASim::TYPE_POSTPAID){
                $total_revenue = $this->price_sim + $this->price_term;
            }else{
                $total_revenue = $this->price_sim + $this->price_package;
            }
            return $total_revenue;
        }


    }
