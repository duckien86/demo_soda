<?php

    class AOrders extends Orders
    {
        const ORDER_PENDING  = 0;
        const ORDER_ACTIVE   = 1;
        const ORDER_INACTIVE = 8;
        const ORDER_COMPLETE = 10;

        const DELIVERY_TYPE_CODE  = 1;
        const DELIVERY_TYPE_BRAND = 2;

        const VINAPHONE_TELCO = 'VINAPHONE';

        const DELIVERY_TYPE_SHOP = 'delivery_shop';
        const DELIVERY_TYPE_HOME = 'delivery_home';

        const COD    = 1;
        const ONLINE = 2;

        const COD_PAYMENT_METHOD = 4;

        const SALE_OFFICE_PERSON  = 1; //Người đại diện phòng bán hàng.
        const BRAND_OFFICE_PERSON = 2; // Người đại diện điểm giao dịch.

        public $package;
        public $card;
        public $brand_offices;

        public $item_id;
        public $price;
        public $price_card;
        public $card_type;
        public $sim_freedoo;

        public $renueve_order;

        public $total_renueve_date;
        public $total_order;

        public $order_search;
        public $input_type;

        public $assign_date;
        public $order_date;
        public $finish_date;

        public $status_state;
        public $time_left;
        public $item_name;

        public $status_end;

        public $sale_offices_id;
        public $brand_offices_id;
        public $campaign_id;

        public $total;
        public $type;
        public $phone_adm; // Số điện thoại admin phòng bán hàng.

        public $sim;
        public $note;
        public $total_renueve;

        public $start_date;
        public $end_date;

        public $type_sim;
        public $type_package;

        public $total_sim;
        public $total_package;
        public $period;

        public $status_shipper;
        public $price_ship;

        public $serial_number;

        public $channel;
        public $is_pre_order;
        
        public $user_id;

        public $package_register_date;

        CONST CHANNEL_CTV = 3;
        CONST CHANNEL_DLTC = 4;


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
                array('full_name, phone_contact, address_detail', 'required', 'on' => 'register_sim'),
                array('phone_contact', 'required', 'on' => 'register_package', 'message' => Yii::t('web/portal', 'phone_contact_required')),
                array('phone_contact', 'required', 'on' => 'buy_card', 'message' => Yii::t('web/portal', 'phone_contact_buy_card')),
                array('phone_contact', 'required', 'on' => 'topup', 'message' => Yii::t('web/portal', 'phone_contact_topup')),
                array('phone_contact', 'msisdn_validation'),
                array('phone_contact', 'checkInfoPhone', 'on' => 'register_package, buy_card, topup'),
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code, agency_contract_id', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, full_name, address_detail, sale_office_code, otp, brand_offices, sale_office_code, campaign_source, campaign_id', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('customer_note,note, campaign_id', 'length', 'max' => 500),
                array('last_update, receive_cash_date, channel, affiliate_source, status_shipper, 
                        total_sim, total_package, type_sim, type_package, package,
                        is_pre_order', 'safe'),
                array('create_date', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, receive_cash_by, receive_cash_date, 
                        invitation, create_date, sale_office_code, last_update, shipper_id, 
                        delivery_type, payment_method, full_name, district_code, province_code, 
                        address_detail, phone_contact, customer_note, otp, campaign_source, 
                        campaign_id, status_shipper, brand_offices_id, period, channel,
                        affiliate_source, sim, type_sim, type_package, agency_contract_id', 'safe', 'on' => 'search'),

                array('start_date, end_date', 'checkDateRequire', 'on' => 'search'),
                array('end_date', 'checkDate', 'on' => 'admin_complete, search'),
            );
        }

        public function checkDate()
        {
            if(!empty($this->start_date) && !empty($this->end_date)){
                $start_date = date('Y-m-d', strtotime(str_replace('/','-',$this->start_date)));
                $end_date = date('Y-m-d', strtotime(str_replace('/','-',$this->end_date)));
                if($end_date < $start_date){
                    $this->addError('end_date', 'Ngày kết thúc phải lớn hơn ngày bắt đầu');
                    return FALSE;
                }
            }
            return TRUE;
        }

        public function checkDateRequire($attribute,$params)
        {
            if(!empty($this->period)){
                return TRUE;
            }
            if(empty($this->$attribute)){
                $this->addError($attribute, $this->getAttributeLabel($attribute). ' chưa được chọn');
                RETURN FALSE;
            }
            RETURN TRUE;
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            if ($this->phone_contact) {
                $input = $this->phone_contact;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('phone_contact', Yii::t('web/portal', 'msisdn_validation'));
                }
            }
        }

        /**
         * @return bool
         */
        public function detectByTelco()
        {
            if ($this->phone_contact) {
                $telco = Utils::detectTelcoByMsisdn($this->phone_contact);
                if ($telco != self::VINAPHONE_TELCO) {
                    $this->addError('phone_contact', Yii::t('web/portal', 'error_msisdn_vinaphone'));
                }
            }

            return TRUE;
        }

        /**
         * @return bool
         */
        public function checkInfoPhone()
        {
            $msisdn = $this->phone_contact;
            $data_input = array(
                'so_tb' => $msisdn
            );
            $data_output = Utils::getInfoPhone($data_input);
            if($data_output['code']== -1){
                $this->addError('phone_contact', Yii::t('web/portal', 'error_msisdn_vinaphone'));
            }
            return TRUE;
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
                'id'                    => "Mã ĐH",
                'sso_id'                => Yii::t('web/portal', 'sso_id'),
                'promo_code'            => Yii::t('web/portal', 'promo_code'),
                'invitation'            => Yii::t('web/portal', 'invitation'),
                'create_date'           => 'Thời gian mua hàng',
                'last_update'           => Yii::t('web/portal', 'last_update'),
                'shipper_id'            => Yii::t('web/portal', 'shipper_id'),
                'delivery_type'         => "Hình thức nhận hàng",
                'payment_method'        => Yii::t('web/portal', 'payment_method'),
                'personal_id'           => Yii::t('web/portal', 'personal_id'),
                'full_name'             => "Người nhận",
                'district_code'         => Yii::t('web/portal', 'district'),
                'province_code'         => Yii::t('web/portal', 'province'),
                'address_detail'        => 'Địa chỉ',
                'phone_contact'         => 'SĐT liên hệ',
                'customer_note'         => Yii::t('web/portal', 'customer_note'),
                'status'                => Yii::t('web/portal', 'status'),
                'otp'                   => Yii::t('web/portal', 'otp'),
                'package'               => Yii::t('web/portal', 'list_package'),
                'transaction_office'    => Yii::t('web/portal', 'transaction_office'),
                'order_search'          => 'Thông tin tìm kiếm',
                'assign_date'           => 'Ngày nhận đơn',
                'order_date'            => 'Ngày giao hàng',
                'finish_date'           => 'Ngày hoàn thành',
                'status_state'          => 'Trạng thái đơn hàng',
                'input_type'            => 'Chọn tiêu chí tìm kiếm',
                'brand_offices'         => 'Điểm giao dịch',
                'time_left'             => 'Thời gian còn lại',
                'photo_order_board_url' => 'Ảnh chụp phiếu đăng ký/hợp đồng',
                'sale_office_code'      => 'Phòng bán hàng',
                'brand_offices_id'      => 'Điểm giao dịch',
                'status_end'            => 'Trạng thái',
                'sim'                   => 'Số thuê bao',
                'total_renueve'         => 'Tổng doanh thu',
                'start_date'            => 'Ngày bắt đầu',
                'end_date'              => 'Ngày kết thúc',
                'period'                => 'Thời gian còn lại',
                'status_shipper'        => 'Trạng thái giao vận',
                'receive_cash_by'       => 'Người thu tiền',
                'receive_cash_date'     => 'Ngày thu tiền',
                'item_name'             => 'Số sim',
                'phone_adm'             => 'Số điện thoại admin PBH',
                'campaign_source'       => 'campaign source',
                'campaign_id'           => 'campaign id',
                'note'                  => 'Ghi chú',
                'agency_contract_id'    => 'Mã hợp đồng ĐLTC',

                'channel'               => 'Kênh bán',
                'affiliate_source'      => 'ĐLTC',
                'is_pre_order'          => 'Loại đơn hàng',
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
         * @return CActiveDataProvider | static[]
         * based on the search/filter conditions.
         */
        public function search($dataProvider = TRUE, $returnCriteria = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;
            $criteria->select = "
                    t.*, 
                    (SELECT msisdn FROM tbl_sim WHERE order_id = t.id) as 'sim',
                    (SELECT type FROM tbl_sim WHERE order_id = t.id) as 'type_sim',
                    (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') as 'package',
                    (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'sim') as 'total_sim',
                    (SELECT price FROM tbl_order_details WHERE order_id = t.id AND type = 'package') as 'total_package'
                    ";

            $criteria->addCondition("t.payment_method IS NOT NULL AND t.payment_method != ''");
            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

            $warning_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +36H'));
            $deadline_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +48H'));

            if (!empty($this->period)) {
                switch ($this->period){
                    case 1:
                        $criteria->addCondition("t.create_date >= '$warning_date' AND t.create_date <= '$deadline_date'");
                        break;
                    case 2:
                        $criteria->addCondition("t.create_date > '$deadline_date'");
                        break;
                    case 3:
                        $criteria->addCondition("t.create_date < '$warning_date'");
                        break;
                }
            } else {
                if(empty($this->id) && empty($this->sim) && empty($this->phone_contact)){
                    if(!empty($this->start_date) && !empty($this->end_date)){
                        $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                        $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

                        $criteria->addCondition("t.create_date >='$start_date' and t.create_date <='$end_date'");
                    }
                }
            }

            if (!ADMIN && !SUPER_ADMIN) {
                if ( isset(Yii::app()->user->province_code)
                    && empty(Yii::app()->user->sale_offices_id) 
                ) {
                    $criteria->compare('t.province_code', Yii::app()->user->province_code);
                } else if ( isset(Yii::app()->user->province_code)
                    && !empty(Yii::app()->user->sale_offices_id)
                    && empty(Yii::app()->user->brand_offices_id) 
                ) {
                    $criteria->compare('t.sale_office_code', Yii::app()->user->sale_offices_id);
                } else if ( !empty(Yii::app()->user->brand_offices_id)
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

            if (!empty($this->delivery_type)) {
                $criteria->compare('t.delivery_type', $this->delivery_type, FALSE);
            }

            if (!empty($this->status_shipper)) {

                switch ($this->status_shipper){
                    case 1: // Đã giao, không gửi trả.
                        $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_shipper_order WHERE status = 1 AND order_status != 2)");
                        break;
                    case 3: // Gửi trả
                        $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_state WHERE (confirm = 3)
                                AND id = (SELECT max(id) FROM tbl_order_state))");
                        break;
                    case 4: // Hủy
                        $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_state WHERE (confirm = 2)
                                AND id = (SELECT max(id) FROM tbl_order_state))");
                        break;
                    case 5: // Chưa giao
                        $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_shipper_order WHERE status = 0 AND order_status != 2)");
                        $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_state WHERE (confirm NOT IN (1,2))
                                AND id = (SELECT max(id) FROM tbl_order_state))");
                        break;
                    case 6: // Chưa phân công
                        $criteria->addCondition("t.shipper_id = '' OR t.shipper_id IS NULL AND t.delivery_type = 1");
                        break;
                }
            }

            if(!empty($this->channel)){
                if($this->channel == self::CHANNEL_DLTC){
                    $criteria->addCondition("
                            (t.affiliate_source IS NOT NULL AND t.affiliate_source != '')
                            AND (t.promo_code IS NULL OR t.promo_code = '')
                        ");
                    if(!empty($this->affiliate_source)){
                        $criteria->compare("t.affiliate_source", $this->affiliate_source, FALSE);
                    }
                }else if($this->channel == self::CHANNEL_CTV){
                    $criteria->addCondition("t.promo_code IS NOT NULL AND t.promo_code != ''");
                    if(!empty($this->promo_code)){
                        $criteria->compare("t.promo_code", $this->promo_code, FALSE);
                    }
                }
            }

            if(!empty($this->is_pre_order)){
                if($this->is_pre_order == 2){
                    $criteria->addCondition("t.pre_order_date IS NOT NULL AND t.pre_order_date != ''");
                }else{
                    $criteria->addCondition("t.pre_order_date IS NULL OR t.pre_order_date = ''");
                }
            }

            if(!empty($this->id)){
                $criteria->compare('t.id', $this->id, TRUE);
            }
            if(!empty($this->phone_contact)){
                AOrders::comparePhone($this->phone_contact, $criteria, 't.phone_contact');
            }
            if(!empty($this->sim)){
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_sim WHERE order_id IS NOT NULL AND msisdn LIKE '%$this->sim%')");
            }

            $criteria->order = 't.create_date ASC';

            if(!ADMIN && !SUPER_ADMIN){
                $province_code = (!empty($this->province_code))
                    ? $this->province_code
                    : isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '';

                $sale_office_code = (!empty($this->sale_office_code))
                    ? $this->sale_office_code
                    : isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '';

                $brand_offices_id = (!empty($this->brand_offices_id))
                    ? $this->brand_offices_id
                    : ( !empty(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '' );
            }else{
                $province_code = $this->province_code;
                $sale_office_code = $this->sale_office_code;
                $brand_offices_id = $this->brand_offices_id;
            }
            if ($returnCriteria) {
                return $criteria;
            }

            if($dataProvider){
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[province_code]"    => $province_code,
                            "AOrders[sale_office_code]" => $sale_office_code,
                            "AOrders[brand_offices_id]" => $brand_offices_id,
                            "AOrders[delivery_type]"    => $this->delivery_type,
                            "AOrders[period]"           => $this->period,
                            "AOrders[status_shipper]"   => $this->status_shipper,

                            "AOrders[channel]"          => $this->channel,
                            "AOrders[affiliate_source]" => $this->affiliate_source,
                            "AOrders[promo_code]"       => $this->promo_code,
                            "AOrders[is_pre_order]"     => $this->is_pre_order,

                            "AOrders[id]"               => $this->id,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }else{
                return AOrders::model()->findAll($criteria);
            }
        }

        public function search_tool($post = FALSE, $excel = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $warning_date  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +36H'));
            $deadline_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +48H'));


            $criteria         = new CDbCriteria;
            $criteria->select = 't.*';

            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

            if ($this->start_date && $this->end_date) {
                if ($this->period != '') {
                    if ($this->period == 1) { //Từ 36 đến 48 tiếng.
                        $criteria->addCondition("t.create_date >'$deadline_date' and t.create_date <'$warning_date'");
                    } else if ($this->period == 2) { // Qúa 48 tiếng.
                        $criteria->addCondition("t.create_date <'$deadline_date'");
                    } else {
                        $criteria->addCondition("t.create_date >'$warning_date'");
                    }
                } else {
                    $criteria->addCondition("t.create_date >='$this->start_date' and t.create_date <='$this->end_date'");
                }
            }
            $criteria->compare('t.id', $this->id, TRUE);
            if(!empty($this->phone_contact)){
                AOrders::comparePhone($this->phone_contact, $criteria, 't.phone_contact');
            }
            if ($this->status_shipper != '') {
                if ($this->status_shipper != '3' && $this->status_shipper != '4' && $this->status_shipper != '5' && $this->status_shipper != '6') { // Không gửi trả.
                    $criteria->addCondition("so.status ='" . $this->status_shipper . "' and so.order_status !='2'");
                } else {
                    if ($this->status_shipper == '3') { // Gửi trả
                        $criteria->addCondition("os.confirm ='3'");
                    } else if ($this->status_shipper == '4') { // Hủy
                        $criteria->addCondition("os.confirm IN ('1','2')");
                    } else if ($this->status_shipper == '5') { // Chưa giao
                        $criteria->addCondition("os.confirm NOT IN ('1','2') and so.status = 0 and so.order_status !='2'");
                    } else if ($this->status_shipper == '6') {
                        $criteria->addCondition("t.shipper_id ='' OR t.shipper_id is NULL and t.delivery_type= 1");
                    }
                }
            }
            if ($post) {
                $criteria->compare('od.item_name', $this->sim, TRUE);
            }
            if (!ADMIN && !SUPER_ADMIN) {
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
            if ($post) {
                if ($this->province_code != '') {
                    $criteria->compare('t.province_code', $this->province_code);
                }
                if ($this->sale_office_code != '') {
                    $criteria->compare('t.sale_office_code', $this->sale_office_code);
                }
                if ($this->brand_offices_id != '') {
                    $criteria->compare('t.address_detail', $this->brand_offices_id, FALSE);
                }
            }
            if ($this->delivery_type) {
                $criteria->addCondition("t.delivery_type ='$this->delivery_type'");
            }

            if ($post) {
                $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");

                $criteria->join = "INNER JOIN {{order_details}} od ON od.order_id =t.id
                                       INNER JOIN {{order_state}} os ON os.order_id =t.id
                                       LEFT JOIN {{shipper_order}} so ON so.order_id = t.id";
            }

            $criteria->group = 't.id';
            $criteria->limit = 10000;
            if ($excel) {
                $criteria->order = 't.create_date ASC';

                return Orders::model()->findAll($criteria);
            }
            if (!ADMIN && !SUPER_ADMIN && !$post) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '',
                            "AOrders[province_code]"    => isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '',
                            "AOrders[brand_offices_id]" => isset(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '',
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                            "AOrders[period]"           => $this->period,
                            "AOrders[status_shipper]"   => $this->status_shipper,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            } else {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => $this->sale_office_code,
                            "AOrders[province_code]"    => $this->province_code,
                            "AOrders[brand_offices_id]" => $this->brand_offices_id,
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                            "AOrders[period]"           => $this->period,
                            "AOrders[status_shipper]"   => $this->status_shipper,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }
        }

        /**
         * @param bool $post
         * @param bool $excel
         *
         * @return CActiveDataProvider|static[]
         * Đơn hàng sim kèm gói
         */
        public function searchChange($post = FALSE, $excel = FALSE, $pagination = TRUE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria         = new CDbCriteria;
            $criteria->select = "t.*,IF (od.type='sim',od.item_name,'') as item_name";

            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

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
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='" . $this->start_date . "' and t.create_date <='" . $this->end_date . "'");
            }

            $criteria->compare('t.brand_offices_id', $this->brand_offices_id, FALSE);
            $criteria->compare('t.sale_office_code', $this->sale_office_code, TRUE);
            $criteria->addCondition("t.payment_method != '' and t.delivery_type=1");
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            $criteria->addCondition("os.confirm = 10");
            $criteria->addCondition("t.shipper_id='' or t.shipper_id is NULL");

            //lọc filter status.
            $criteria->join = "INNER JOIN {{order_state}} os ON os.order_id = t.id
                                   INNER JOIN {{order_details}} od ON od.order_id = t.id";

            $criteria->group = 't.id';

            if ($pagination) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[province_code]"    => $this->province_code,
                            "AOrders[sale_office_code]" => $this->sale_office_code,
                            "AOrders[ward_code]"        => $this->ward_code,
                        ),
                        'pageSize' => 30,
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
                        "AOrders[start_date]"       => $this->start_date,
                        "AOrders[end_date]"         => $this->end_date,
                        "AOrders[province_code]"    => $this->province_code,
                        "AOrders[sale_office_code]" => $this->sale_office_code,
                        "AOrders[ward_code]"        => $this->ward_code,
                    ),
                    'pageSize' => 9999999,
                ),
            ));
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
        public function search_recycle($post = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria         = new CDbCriteria;
            $criteria->select = 't.*';

            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='$this->start_date' and t.create_date <='$this->end_date'");
            }

            $criteria->compare('t.id', $this->id, TRUE);
            if(!empty($this->phone_contact)){
                AOrders::comparePhone($this->phone_contact, $criteria, 't.phone_contact');
            }
            if ($post) {
                $criteria->compare('od.item_name', $this->sim, TRUE);
            }
            if (!ADMIN && !SUPER_ADMIN) {
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
            if ($post) {
                if ($this->province_code != '') {
                    $criteria->compare('t.province_code', $this->province_code);
                }
                if ($this->sale_office_code != '') {
                    $criteria->compare('t.sale_office_code', $this->sale_office_code);
                }
                if ($this->brand_offices_id != '') {
                    $criteria->compare('t.address_detail', $this->brand_offices_id, FALSE);
                }
            }
            if ($this->delivery_type) {
                $criteria->addCondition("t.delivery_type ='$this->delivery_type'");
            }
            $criteria->addCondition("t.payment_method ='' OR t.payment_method is null");
            if ($post) {
                $criteria->join = "INNER JOIN {{order_details}} od ON od.order_id =t.id";
            }

            $criteria->group = 't.id';
            $criteria->limit = 10000;

            if (!ADMIN && !SUPER_ADMIN && !$post) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '',
                            "AOrders[province_code]"    => isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '',
                            "AOrders[brand_offices_id]" => isset(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '',
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            } else {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => $this->sale_office_code,
                            "AOrders[province_code]"    => $this->province_code,
                            "AOrders[brand_offices_id]" => $this->brand_offices_id,
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }
        }

        /**
         * Tìm kiếm chi tiết.
         *
         * @param string $start_date
         * @param string $end_date
         * @param string $shipper_id
         *
         * @return CActiveDataProvider
         */
        public function search_detail($start_date = '', $end_date = '', $shipper_id = '')
        {
            $criteria         = new CDbCriteria;
            $criteria->select = "t.*,od.*,so.assign_date as assign_date, so.delivery_date as order_date, so.finish_date as finish_date, sum(od.price) as renueve_order";

            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

            if ($start_date != '' && $end_date != '') {
                $criteria->addCondition("t.create_date >='$start_date' and t.create_date<= '$end_date' and t.shipper_id='" . $shipper_id . "'");
            } else {
                $criteria->addCondition("t.shipper_id='" . $shipper_id . "'");
            }

            $criteria->join  = "INNER JOIN {{order_details}} od ON od.order_id=t.id
                                    INNER JOIN {{shipper_order}} so ON so.order_id = t.id";
            $criteria->group = "t.id";

            if ($shipper_id != '') {
                return new CActiveDataProvider('AOrders', array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'params'   => array(
                            "id"         => $shipper_id,
                            "start_date" => $start_date,
                            "end_date"   => $end_date,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            } else {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'pageSize' => 30,
                    ),
                ));
            }
        }

        public function search_complete($post = FALSE)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");
            $criteria->addCondition("(os.confirm =10 or os.paid=10) and os.delivered !=10 and t.delivery_type =2 and od.type='sim'");

            if(!empty($this->id)){
                $criteria->compare('t.id', $this->id, TRUE);
            }else{
                if ($this->start_date && $this->end_date) {
                    $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                    $end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

                    $criteria->addCondition("t.create_date >='$start_date' and t.create_date <='$end_date'");
                }
            }

            $criteria->join = "LEFT JOIN tbl_order_state os ON os.order_id = t.id LEFT JOIN tbl_order_details od ON t.id= od.order_id";
            $criteria->addCondition("os.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.id)");
            if (!ADMIN && !SUPER_ADMIN) {

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
                $criteria->addCondition("t.create_date <= '$now'");
            }
            if ($post) {
                if ($this->province_code != '') {
                    $criteria->compare('t.province_code', $this->province_code);
                }
                if ($this->sale_office_code != '') {
                    $criteria->compare('t.sale_office_code', $this->sale_office_code);
                }
                if ($this->brand_offices_id != '') {
                    $criteria->compare('t.address_detail', $this->brand_offices_id, FALSE);
                }
            }
            $criteria->addCondition("t.payment_method !=''");

            if(!empty($this->phone_contact)){
                AOrders::comparePhone($this->phone_contact, $criteria, 't.phone_contact');
            }

            if (!ADMIN && !SUPER_ADMIN && !$post) {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => isset(Yii::app()->user->sale_offices_id) ? Yii::app()->user->sale_offices_id : '',
                            "AOrders[province_code]"    => isset(Yii::app()->user->province_code) ? Yii::app()->user->province_code : '',
                            "AOrders[brand_offices_id]" => isset(Yii::app()->user->brand_offices_id) ? Yii::app()->user->brand_offices_id : '',
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            } else {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date ASC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "AOrders[sale_office_code]" => $this->sale_office_code,
                            "AOrders[province_code]"    => $this->province_code,
                            "AOrders[brand_offices_id]" => $this->brand_offices_id,
                            "AOrders[start_date]"       => $this->start_date,
                            "AOrders[end_date]"         => $this->end_date,
                            "AOrders[phone_contact]"    => $this->phone_contact,
                            "AOrders[sim]"              => $this->sim,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            }
        }


        public static function getStatus($order_id)
        {
            $status_end          = "Khởi tạo";
            $criteria            = new CDbCriteria();
            $criteria->condition = " id =(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = '$order_id')";
            $state               = AOrderState::model()->find($criteria);
            if ($state) {
                if ($state->confirm == 10) {
                    $status_end = 'Đặt hàng thành công';
                } else if ($state->confirm == 1) {
                    $status_end = 'Khách hàng hủy';
                } else if ($state->confirm == 2 && $state->note == 'don hang bi huy do qua han') {
                    $status_end = 'Đơn hàng bị hủy do quá hạn';
                } else if ($state->confirm == 2 && $state->note == 'qua thoi gian xac nhan thanh toan') {
                    $status_end = 'Đơn hàng quá thời gian xác nhận thanh toán';
                } else if($state->confirm == 2 && strpos($state->note, 'huy don hang boi') !== false) {
                    $status_end = 'Đơn hàng hủy theo yêu cầu';
                } else if ($state->confirm == 2) {
                    $status_end = 'Đơn hàng bị hủy do quá hạn';
                } else if ($state->confirm == 3) {
                    $status_end = "Gửi trả";
                }
                if ($state->paid == 10) {
                    $status_end = "Đã thanh toán";
                }
                if ($state->delivered == 10) {
                    $status_end = "Hoàn thành";
                }
            }

            return $status_end;
        }

        /**
         * @param $order_id
         * Lấy trạng thái giao vận
         *
         * @return int|string
         */
        public function getTrafficStatus($order_id)
        {
            $status = '';
            if ($order_id) {
                $shipper_order = AShipperOrder::model()->findByAttributes(array('order_id' => $order_id));
                $criteria      = new CDbCriteria();

                $criteria->condition = "t.id=(SELECT max(os2.id) FROM tbl_order_state os2  WHERE os2.order_id = t.order_id) 
                                            and t.order_id ='" . $order_id . "'";

                $order_state = AOrderState::model()->findAll($criteria);

                if ($shipper_order && $order_state) {
                    if ($shipper_order->shipper_id != '' && $shipper_order->order_status != '2') {
                        $status = $shipper_order->status;
                    } else if ($shipper_order->order_status == '2' && $order_state[0]->confirm == '2') {
                        $status = ATraffic::CANCEL; // Hủy
                    } else if ($shipper_order->order_status == '2' && $order_state[0]->confirm == '3') {
                        $status = ATraffic::ORDER_RETURN; // Gửi trả
                    } else {
                        $status = -1;
                    }
                } else {
                    $status = -1;
                }
            }

            return $status;
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WOrders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * get list status
         *
         * @return array
         */
        public function getAllStatus()
        {
            return array(
                'confirm'   => 'Xác nhận',
                'paid'      => 'Đã thanh toán',
                'delivered' => 'Đã nhận',
            );
        }

        /**
         * get list status
         *
         * @return array
         */
        public function getSearchOrderBox()
        {
            return array(
                'order_id'      => 'Mã đơn hàng',
                'phone_contact' => 'SĐT Liên hệ',
                'sim'           => 'Số thuê bao',
            );
        }

        /**
         * Lấy danh sách kiểu thanh toán.
         */
        public function getDeliveredType($id)
        {
            $result = "";
            switch ($id) {
                case 1:
                    $result = "qr_code";
                    break;
                case 2:
                    $result = "napas_atm";
                    break;
                case 3:
                    $result = "napas_in";
                    break;
                case 4:
                    $result = "cod";
                    break;
                default:
                    // chuỗi câu lệnh
                    break;

            }

            return $result;
        }

        public static function getAllDeliveredType()
        {
            return array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );
        }

        public static function getDeliveredTypeByType($id)
        {
            $data = AOrders::getAllDeliveredType();

            return isset($data[$id]) ? $data[$id] : 0;
        }

        public function getDeliveredTypeByOrder($id)
        {
            $orders = AOrders::model()->findByAttributes(array('id' => $id));
            if ($orders) {
                return $orders->delivery_type;
            }

            return 1;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public function getStatusTraffic($status)
        {
            $data = array(
                ATraffic::NOT_SHIP     => "Chưa giao",
                ATraffic::SHIPPED      => "Đã giao",
                ATraffic::RECEIVED     => "Đã thu",
                ATraffic::ORDER_RETURN => "Gửi trả",
                ATraffic::CANCEL       => "Hủy",
            );

            return isset($data[$status]) ? $data[$status] : "Chưa phân công";
        }

        public function getTypeSimByOrder($order_id)
        {
            if ($order_id) {
                $sim = ASim::model()->findByAttributes(array('order_id' => $order_id));
                if (isset($sim)) {
                    if ($sim->type == ASim::TYPE_PREPAID) {
                        return "Trả trước";
                    } else if ($sim->type == ASim::TYPE_POSTPAID) {
                        return "Trả sau";
                    }
                }
            }

            return "";
        }

        public function getTypeSimOperation($order_id)
        {
            if ($order_id) {
                $sim = ASim::model()->findByAttributes(array('order_id' => $order_id));
                if ($sim->type == ASim::TYPE_PREPAID) {
                    return 1;
                } else if ($sim->type == ASim::TYPE_POSTPAID) {
                    return 2;
                }
            }

            return "";
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

        /**
         * @param $shipper_id
         *
         * @return string
         */
        public function getShipperName($shipper_id)
        {
            $shipper = array();
            if ($shipper_id) {
                $shipper = Shipper::model()->find('id=:id', array(':id' => $shipper_id));
            }

            return ($shipper) ? CHtml::encode($shipper->full_name) : $shipper_id;
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
        public function getAllBrandOffice()
        {
            $data = BrandOffices::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * @param $province_code
         *
         * @return string
         */
        public function getProvince($province_code)
        {
            $province = array();
            if ($province_code) {
                $province = Province::model()->find('code=:code', array(':code' => $province_code));
            }

            return ($province) ? CHtml::encode($province->name) : $province_code;
        }

        /**
         * @param $district_code
         *
         * @return string
         */
        public function getDistrict($district_code)
        {
            $district = array();
            if ($district_code) {
                $district = District::model()->find('code=:code', array(':code' => $district_code));
            }

            return ($district) ? CHtml::encode($district->name) : $district_code;
        }

        /**
         * @param $ward_code
         *
         * @return string
         */
        public function getWard($ward_code)
        {
            $ward = array();
            if ($ward_code) {
                $ward = Ward::model()->find('code=:code', array(':code' => $ward_code));
            }

            return ($ward) ? CHtml::encode($ward->name) : $ward_code;
        }

        /**
         * @param $ward_code
         *
         * @return string
         */
        public function getBrandOffice($band_office_id)
        {
            $Office_id = array();
            if ($band_office_id) {
                $Office_id = Ward::model()->find('id=:id', array(':id' => $band_office_id));
            }

            return ($Office_id) ? CHtml::encode($Office_id->name) : $Office_id;
        }

        /**
         * @return array
         * Lấy khoảng thời gian truy vấn
         */
        public static function getPeriodTime()
        {
            return array(
                3 => 'Dưới 36h',
                1 => '36h đến 48h',
                2 => 'Trên 48h',

            );
        }

        /**
         * @param $customer_id
         * @param $dataProvider
         * @param $limit
         * @param $offset
         *
         * @return array|CActiveDataProvider|mixed|null
         */
        public static function getOrdersByCustomer($customer_id, $dataProvider = FALSE, $limit = 10, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->with      = array('detail');
            $criteria->condition = 'sso_id=:sso_id';
            $criteria->params    = array(':sso_id' => $customer_id);
            if ($dataProvider) {
                return new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 'last_update DESC'),
                    'pagination' => array(
                        'pageSize' => $limit,
                    ),
                ));
            } else {
                $criteria->limit  = $limit;
                $criteria->offset = $offset;
                $criteria->order  = 'last_update DESC';
                $results          = self::model()->findAll($criteria);

                return $results;
            }
        }

        public function generateOrderId()
        {
            return Utils::generateRandomString(11, TRUE);
        }

        public function getListOrder($data)
        {
            $type = 'web_search_order';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);
            $logMsg[]  = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[]  = array($str_json, 'Input: ' . __LINE__, 'T', time());

            $response = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);

            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('web_search_order.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            $arr_response      = CJSON::decode($response);
            $arr_data_response = CJSON::decode($arr_response['data']);
            if (isset($arr_data_response['lists'])) {
                $data_return = $arr_data_response['lists'];

                return $data_return;
            }

            return FALSE;
        }

        public function checkRoaming($data)
        {
            $type = 'app_check_sub_post_register';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'      => $type,
                'user_name' => 'centech1',
                'id'        => $id,
                'data'      => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);
            $logMsg[]  = array(Yii::app()->params['socket_api_app'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[]  = array($str_json, 'Input: ' . __LINE__, 'T', time());

            $response = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);

            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('web_search_order.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status'])) {
                $data_return = $arr_response['status'];

                return $data_return;
            }

            return FALSE;
        }

        public function getOrderDetail($data)
        {

            $type = 'web_get_order_detail_ssoid';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type' => $type,
                'id'   => $id,
                'data' => CJSON::encode($data),
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response  = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);
            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('web_get_order_detail_ssoid.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            $arr_response = CJSON::decode($response);
            if (isset($arr_response['status']) && isset($arr_response['data']) && !empty($arr_response['data'])) {
                $status = $arr_response['status'];
                if (isset($status['code']) && $status['code'] == 1) {
                    return CJSON::decode($arr_response['data']);
                }
            }

            return FALSE;
        }

        /**
         * Lấy danh sách đơn hàng theo sản phẩm sim
         */
        public function getListOrderBySim($sim)
        {
            $criteria            = new CDbCriteria();
            $criteria->select    = "DISTINCT order_id, t.*";
            $criteria->condition = "od.item_name = '" . $sim . "'";
            $criteria->join      = "INNER JOIN {{order_details}} od ON od.order_id =t.id";
            $data                = AOrders::model()->findAll($criteria);

            return $data;
        }

        //Lấy dữ liệu lịch sử đơn hàng giả lập.
        public function getOrderHistory($data)
        {
            $result = array();
            $type   = array(
                'confirm'   => 'confirm',
                'paid'      => 'paid',
                'delivered' => 'delivered',
            );
            foreach ($type as $key_type => $value_type) {
                $result_key = array();
                foreach ($data as $key => $value) {
                    if ($value[$key_type] == 10) {
                        $result_key['status']      = $key_type;
                        $result_key['create_date'] = $value['create_date'];
                    } else {
                        $result_key['status']      = $key_type;
                        $result_key['create_date'] = 'Chưa xác định';
                    }
                }
                $result[] = $result_key;
            }
            $result = array_values($result);

            return $result;
        }

        /**
         * @param $id
         * Lấy thông tin shipper theo đơn hàng.
         *
         * @return CActiveDataProvider
         */
        public static function getShipperDetail($id)
        {
            $shipper = Orders::model()->findByAttributes(array('id' => $id));
            if ($shipper) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "id='" . $shipper->shipper_id . "'";

                return new CActiveDataProvider("AShipper", array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));
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
                $logMsg[] = array("msisdn:{$msisdn}", 'SentMT ok:' . __LINE__, 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T');
            } else {
                $logMsg[] = array("msisdn:{$msisdn}", "SentMT Fail:", 'T', time());
                $logMsg[] = array($msgBody, 'msgBody:' . __LINE__, 'T', time());
            }
            $logMsg[] = array($mtUrl, 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($http_code, 'http_code: ' . __LINE__, 'T', time());

            $logFolder = "Log_send_mt/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile($file_name . '.log');
            $logMsg[] = array($file_name, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            return $flag;
        }

        /**
         * @return bool|string
         */
        public static function getTokenKey()
        {
            $token = (string)rand(100000, 999999);

            return $token;
        }


        public static function checkPrePackage($data, $security, $username)
        {
            $type = 'app_register_pre_package';
            $id   = Yii::app()->request->csrfToken;

            $logMsg   = array();
            $logMsg[] = array('Start ' . $type . ' Log', 'Start process:' . __LINE__, 'I', time());
            $logMsg[] = array($id, 'id: ' . __LINE__, 'T', time());

            $arr_param = array(
                'type'      => $type,
                'user_name' => $username,
                'id'        => 'id_backend_vsb',
                'data'      => $data,
                'security'  => $security,
            );
            $str_json  = CJSON::encode($arr_param);

            $logMsg[] = array(Yii::app()->params['socket_api_app'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response  = Utils::cUrlPostJson(Yii::app()->params['socket_api_app'], $str_json);
            $logMsg[]  = array($response, 'Output: ' . __LINE__, 'T', time());
            $logFolder = "Log_call_api/" . date("Y/m/d");
            $logObj    = ATraceLog::getInstance($logFolder);
            $logObj->setLogFile('app_register_pre_package.log');
            $logMsg[] = array($type, 'Finish process-' . __LINE__, 'F', time());
            $logObj->processWriteLogs($logMsg);

            //decode output
            $arr_response = CJSON::decode($response);
            if ($arr_response) {
                return $arr_response;
            }

            return FALSE;
        }

        public static function getPaymentMethod($payment_method)
        {
            $data = array(
                1 => 'QR CODE',
                2 => 'THẺ ATM NỘI ĐỊA',
                3 => 'THẺ ATM QUỐC TẾ',
                4 => 'COD',
                6 => 'VIETINBANK',
                7 => 'VNPAY',
                8 => 'OLPAY',
                9 => 'VIETINBANK NỘI ĐỊA',
                10 => 'VNPT PAY',
            );


            return isset($data[$payment_method]) ? $data[$payment_method] : '';
        }

        public static function getAllPaymentMethod($code = TRUE)
        {
            if (!$code) {
                $data = array(
                    1 => 'QR CODE',
                    2 => 'THẺ ATM NỘI ĐỊA',
                    3 => 'THẺ ATM QUỐC TẾ',
                    6 => 'VIETINBANK QUỐC TẾ',
                    7 => 'VNPAY',
                    8 => 'OLPAY',
                    9 => 'VIETINBANK NỘI ĐỊA'
                );
            }

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

            return $data;
        }

        public static function getPaymentMethodOperation()
        {
            $data = array(
                ''  => '',
                '1' => 'QR CODE',
                '2' => 'THẺ ATM NỘI ĐỊA',
                '3' => 'THẺ ATM QUỐC TẾ',
                '4' => 'COD',
                '6' => 'VIETINBANK QUỐC TẾ',
                '7' => 'VNPAY',
                '8' => 'OLPAY',
                '9' => 'VIETINBANK NỘI ĐỊA'
            );

            return $data;
        }

        /**
         * Lấy trạng thái giao hàng.
         */
        public static function getAllStatusReport()
        {
            return array(
                5 => "Chưa giao",
                1 => "Đã giao",
                3 => "Gửi trả",
                4 => "Hủy",
                6 => "Chưa phân công",
            );
        }

        /**
         * Lấy chi tiết sim
         *
         * @param $order_id
         */
        public function getSim($order_id)
        {
            if ($order_id) {
                $sim = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'sim'));
                if ($sim) {
                    return $sim->item_name;
                }
            }

            return "";
        }

        /**
         * Lấy tổng doanh thu của đơn hàng.
         */
        public
        function getTotalRenueveOrder($order_id)
        {

            if ($order_id) {
                $sim = ASim::model()->findByAttributes(array('order_id' => $order_id));

                $criteria         = new CDbCriteria();
                $criteria->select = "SUM(price) as total_renueve";
                if (isset($sim) && $sim->type == ASim::TYPE_POSTPAID) {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','price_term')";
                } else if (isset($sim) && $sim->type == ASim::TYPE_PREPAID) {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package')";
                } else {
                    $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package','price_term')";
                }
                $order_detail = AOrderDetails::model()->findAll($criteria)[0];

                return $order_detail->total_renueve;

            }

            return 0;
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

        /**
         * Gửi thông báo nhận hàng bằng sms.
         *
         * @param $msisdn
         */
        public static function sendSMS($order_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "u.phone as phone_adm";
            $criteria->condition = "t.id ='" . $order_id . "'";
            $criteria->join      = " INNER JOIN {{users}} u ON u.sale_offices_id = t.sale_office_code
                                   INNER JOIN {{authassignment}} a ON a.userid = u.id";
            $orders              = AOrders::model()->findAll($criteria);
            if ($orders) {
                // Send MT.
                //Lưu log gọi api.
                foreach ($orders as $key => $value) {

                    $mt_content = Yii::t('adm/mt_content', 'message');
                    $logFolder  = "send_sms_change_order";
                    if (self::sentMtVNP($value->phone_adm, $mt_content, $logFolder)) {
                        return TRUE;
                    }
                }
            }

            return TRUE;
        }

        /**
         * @param $order_id
         * Check tồn tại trung tâm kinh doanh
         */
        public function checkExistProvince($order_id)
        {

            if ($order_id) {
                $orders = AOrders::model()->findByAttributes(array('id' => $order_id));
                if (isset($orders->province_code) && !empty($orders->province_code)) {
                    return TRUE;
                }
            }

            return FALSE;
        }

        /**
         * @param $order_id
         * Lấy ngày kích hoạt sim
         */
        public function getActiveSimDate($order_id)
        {
            $date    = '';
            $sim_log = ALogsSim::model()->findByAttributes(array('order_id' => $order_id, 'status' => 10));
            if ($sim_log) {
                $date = $sim_log->create_date;
            }

            return $date;
        }

        /**
         * check session order
         *
         * @return bool
         */
        public static function checkOrdersSessionExists($type = '')
        {
            $flag = FALSE;
            if ($type == AOrdersData::BUY_SIM_AGENCY){
                if (
                    (time() - Yii::app()->session['session_cart']) < Yii::app()->params['sessionTimeout']
                    && isset(Yii::app()->session['orders_data'])
                    && !empty(Yii::app()->session['orders_data'])
                    && isset(Yii::app()->session['orders_data']->sim_agency)
                ) {
                    $flag = TRUE;
                }
            }else {
                if ((time() - Yii::app()->session['session_cart']) < Yii::app()->params['sessionTimeout']
                    && isset(Yii::app()->session['orders_data'])
                    && !empty(Yii::app()->session['orders_data'])
                ) {
                    $flag = TRUE;
                }
            }

            return $flag;
        }

        public static function unsetSession()
        {
            unset(Yii::app()->session['session_cart']);
            unset(Yii::app()->session['orders_data']);
        }

        public static function getOrderPhoneContact($order_id)
        {
            $cache_key = 'getOrderPhoneContact_' . $order_id;
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $order   = AOrders::model()->findByPk($order_id);
                $results = ($order) ? $order->phone_contact : '';
                Yii::app()->cache->set($cache_key, $results, 60 * 5);
            }

            return $results;
        }

        /**
         * @param $orders_data
         *
         * @return int
         */
        public function getOrderAmount($orders_data)
        {
            $modelOrderNew                = new AOrders();
            $modelSimNew                  = new ASim();
            $modelOrderNew->delivery_type = $orders_data->orders->delivery_type;
            $modelSimNew->msisdn          = $orders_data->sim->msisdn;
            $modelSimNew->type            = $orders_data->sim->type;
            // tinh gia
            $amount = $this->calculatePrice($modelOrderNew, $modelSimNew, $orders_data->sim->raw_data, $orders_data->package, $orders_data);

            return $amount;
        }

        /**
         * @param WOrders $modelOrder
         * @param WSim    $modelSim
         * @param         $sim_raw_data
         * @param         $modelPackage
         * @param         $orders_data
         *
         * @return int
         */
        public function calculatePrice(AOrders &$modelOrder, ASim &$modelSim, $sim_raw_data, $modelPackage, $orders_data)
        {
            $modelSim->price_term = (int)$sim_raw_data['price_term'];
            $modelSim->price      = (int)$sim_raw_data['price'];
            $amount               = 0;
            // tinh phi ship
            $modelOrder->price_ship = 0;
            if (isset($modelOrder->delivery_type)) {
                if ($modelOrder->delivery_type == AOrders::DELIVERY_TYPE_HOME) {
                    $amount                 = $GLOBALS['config_common']['order']['price_ship'];
                    $modelOrder->price_ship = $GLOBALS['config_common']['order']['price_ship'];
                } else {
                    $modelOrder->price_ship = 0;
                }
            }
            // tinh phi sim tra truoc || tra sau
            if (isset($modelSim->type)) {
    //                if ($modelSim->type != $sim_raw_data['msisdn_type']) {
    //                    if ($modelSim->type == WSim::TYPE_PREPAID) {
    //                        $amount          += (int)$sim_raw_data['price'] - (int)Yii::app()->params->prepaid_postpaid_price;
    //                        $modelSim->price = (int)$sim_raw_data['price'] - (int)Yii::app()->params->prepaid_postpaid_price;
    //                    } else {
    //                        $amount          += (int)$sim_raw_data['price'] + (int)Yii::app()->params->prepaid_postpaid_price;
    //                        $modelSim->price = (int)$sim_raw_data['price'] + (int)Yii::app()->params->prepaid_postpaid_price;
    //                    }
    //                } else {
    //                    $amount          += (int)$sim_raw_data['price'];
    //                    $modelSim->price = (int)$sim_raw_data['price'];
    //                }
                //get WSim info from raw data
                $this->getSimInRawData($modelSim->msisdn, $modelSim->type, $modelSim->price, $orders_data->sim_raw_data, $modelSim);
                $amount += (int)$modelSim->price;
            } else {
                $amount += (int)$sim_raw_data['price'];
                $modelSim->price = (int)$sim_raw_data['price'];
                $modelSim->type  = $sim_raw_data['msisdn_type'];
            }

            // tinh goi cuoc va phi cam ket
            if ($modelPackage) {
                //check price_discount
                if ($modelPackage->price_discount > 0) {
                    $modelPackage->price = $modelPackage->price_discount;
                } elseif ($modelPackage->price_discount == -1) {
                    $modelPackage->price = 0;
                }

                if (isset($sim_raw_data['price_term']) && $sim_raw_data['price_term'] > 0) {
                    $amount += (int)$sim_raw_data['price_term'];
    //                    $modelPackage->price = 0;//comment 17.01.2018
                    /*if ($sim_raw_data['price_term'] <= Yii::app()->params->min_free_price_term) {
                        $amount               += (int)$modelPackage->price;
                        $modelSim->price_term = 0;
                    } else {
                        if ($sim_raw_data['price_term'] > (int)$modelPackage->price) {
                            $amount              += (int)$sim_raw_data['price_term'];
                            $modelPackage->price = 0;
                        } else {
                            $amount               += (int)$modelPackage->price;
                            $modelSim->price_term = 0;
                        }
                    }*/
                } else {
                    if ($modelSim->type == ASim::TYPE_POSTPAID) {
    //                        $modelPackage->price = 0;//comment 17.01.2018
                    } else {
                        $amount += (int)$modelPackage->price;
                    }
                }
            } else { // neu ko mua kem goi cuoc thi cong them tien cam ket
                $amount += (int)$sim_raw_data['price_term'];
            }

            return $amount;
        }

        /**
         * @param $msisdn
         * @param $sim_type
         * @param $price
         * @param $sim_raw_data_arr
         * @param $sim
         */
        public function getSimInRawData($msisdn, $sim_type, $price, $sim_raw_data_arr, &$sim)
        {
            foreach ($sim_raw_data_arr as $sim_raw) {
                if ((isset($sim_raw['msisdn']) && $sim_raw['msisdn'] == $msisdn)
                    && (isset($sim_raw['msisdn_type']) && $sim_type == $sim_raw['msisdn_type'])
                    && (isset($sim_raw['price']) && $price == $sim_raw['price'])
                ) {
                    $sim->msisdn     = $sim_raw['msisdn'];
                    $sim->price      = $sim_raw['price'];
                    $sim->type       = $sim_raw['msisdn_type'];
                    $sim->term       = $sim_raw['term'];
                    $sim->price_term = $sim_raw['price_term'];
                    $sim->store_id   = (string)$sim_raw['store'];
                    $sim->raw_data   = $sim_raw;
                }
            }
        }

        public function getCardFreeDooDetails()
        {
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';
            }

            $criteria = new CDbCriteria();

            $criteria->select    = "t.id, od.type, od.item_id, od.price, t.phone_contact, t.payment_method";
            $criteria->condition = "ot.delivered = 10 AND od.type IN ('card','topup') 
                                        AND ot.create_date >='$this->start_date' 
                                        AND ot.create_date <='$this->end_date'";

            if ($this->payment_method != '') {
                $criteria->addCondition("t.payment_method ='$this->payment_method'");
            }
            if ($this->price_card != '') {
                $criteria->addCondition("od.item_id ='$this->price_card'");
            }
            if ($this->card_type != '') {
                $criteria->addCondition("od.type ='$this->card_type'");
            }
            if ($this->sim_freedoo != '') {
                if ($this->sim_freedoo == Report::FREEDOO_TYPE) {
                    $criteria->addCondition('s.msisdn is not null');
                } else {
                    $criteria->addCondition('s.msisdn is null');
                }
            }
            $criteria->join = "INNER JOIN tbl_order_details od ON od.order_id = t.id
                                   INNER JOIN tbl_order_state ot ON ot.order_id = t.id
                                   LEFT JOIN tbl_sim s ON s.msisdn = t.phone_contact";

    //            $criteria->group = "od.item_id";

            $data = AOrders::model()->findAll($criteria);

            return $data;
        }


        /**
         * Hoa hồng tạm tính Sim
         */
        public function getRoseSimProvisional()
        {
            $rose = 0;
            if(!empty($this->affiliate_source)){
                $rose = 14500;
            }else if(!empty($this->promo_code)){
                $rose = 13000;
            }
            return $rose;
        }

        /**
         * Hoa hồng tạm tính Gói
         */
        public function getRosePackageProvisional()
        {
            $rose = 0;
            $commision = APackageCommision::getPackageCommisionByCode($this->package);
            if($commision){
                if(!empty($this->affiliate_source)){
                    $rose = $this->total_package * $commision->percent_agency / 100;
                }else if(!empty($this->promo_code)){
                    $rose = $this->total_package * $commision->percent_collaborator / 100;
                }

            }
            return $rose;
        }

        /**
         * @param $phone string
         * @param $criteria CDbCriteria
         * @param $fieldName string
         */
        public static function comparePhone($phone, &$criteria, $fieldName)
        {
            $found = false;

            $list_arr_number_compare = array(
                array('0120', '84120', '070', '8470'),
                array('0121', '84121', '079', '8479'),
                array('0122', '84122', '077', '8477'),
                array('0126', '84126', '076', '8476'),
                array('0128', '84128', '078', '8478'),
                array('0124', '84124', '084', '8484'),
                array('0127', '84127', '081', '8481'),
                array('0129', '84129', '082', '8482'),
                array('0123', '84123', '083', '8483'),
                array('0125', '84125', '085', '8485'),
                array('0169', '84169', '039', '8439'),
                array('0168', '84168', '038', '8438'),
                array('0167', '84167', '037', '8437'),
                array('0166', '84166', '036', '8436'),
                array('0165', '84165', '035', '8435'),
                array('0164', '84164', '034', '8434'),
                array('0163', '84163', '033', '8433'),
                array('0162', '84162', '032', '8432'),
            );

            foreach ($list_arr_number_compare as $arr_number_compare){
                if(Utils::checkPrefix($arr_number_compare, $phone)) {
                    $found = true;
                    $condition = '';
                    $first = true;
                    foreach ($arr_number_compare as $prefix){
                        if($first){
                            $first = false;
                            $condition = "$fieldName LIKE '".Utils::changePrefix($prefix,$phone)."%'";
                        }else{
                            $condition .= " OR $fieldName LIKE '".Utils::changePrefix($prefix,$phone)."%'";
                        }
                    }
                    $criteria->addCondition($condition);
                    break;
                }
            }
            if(!$found){
                $criteria->compare("$fieldName", $phone, TRUE);
            }

        }

        public static function checkAgencyContract($agency_contract_id)
        {
            if(isset(Yii::app()->user->agency)){
                $contract = AAgencyContract::model()->findByPk($agency_contract_id);
                if($contract && $contract->agency_id == Yii::app()->user->agency){
                    return TRUE;
                }
            }
            return FALSE;
        }

        /**
         * @param $phone_contact
         *
         * @return static
         */
        public static function checkPhoneContactOfAgency($phone_contact){
            $criteria = new CDbCriteria;
            $criteria->select = 't.id, t.affiliate_source';
            $criteria->join = 'JOIN {{order_state}} os ON t.id = os.order_id 
                               JOIN {{order_details}} od ON t.id = od.order_id';
            $criteria->condition = 'os.delivered=:delivered 
                                    AND od.type=:type 
                                    AND  t.affiliate_source=:agency  
                                    AND od.item_name =:phone_contact 
                                    AND os.id = (SELECT MAX(id) from {{order_state}} as n_os WHERE n_os.order_id = t.id)';
            $criteria->params = array(
                ':delivered' => 10,
                ':type' => 'sim',
                ':agency' => Yii::app()->user->agency,
                ':phone_contact' =>$phone_contact,
            );
            $order = AOrders::model()->find($criteria);
            return $order;
        }

        /**
         * lấy tất cả gói thuộc agency
         * @return array
         */
        public function getAllPackage()
        {
            $data = (new APackage)->getPackageByAgency(false);

            return CHtml::listData($data, 'code', 'name');
        }
        
        
        /*
        * Lấy ra chi tiết đơn hàng gói đơn lẻ
        */

        public function searchPackageSingle($dataProvider = TRUE, $returnCriteria = FALSE)
        {
            $criteria = new CDbCriteria();
            $criteria->select = "t.*,
            (SELECT item_id FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_id',
            (SELECT item_name FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'item_name',
            (SELECT create_date FROM tbl_order_state WHERE order_id = t.id AND delivered = 10) AS 'package_register_date',
            (SELECT sum(price) FROM tbl_order_details WHERE order_id = t.id AND type = 'package') AS 'total_renueve'
        ";
            $criteria->addCondition("t.agency_contract_id IN (SELECT id FROM tbl_agency_contract WHERE agency_id = '".Yii::app()->user->agency."')");

            if (!empty($this->id) || !empty($this->phone_contact)) {
                if (!empty($this->id)) {
                    $criteria->compare('t.id', $this->id, FALSE);
                }
                if (!empty($this->phone_contact)) {
                    $msisdn = CFunction::makePhoneNumberBasic($this->phone_contact);
                    $msisdn_standard = CFunction::makePhoneNumberStandard($this->phone_contact);
                    $criteria->addCondition("t.phone_contact LIKE '%$msisdn%' OR t.phone_contact LIKE '%$msisdn_standard%'");
                }
            } else {
                if ($this->start_date && $this->end_date) {
                    $start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                    $end_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";

                    $criteria->addCondition("t.create_date >= '$start_date' AND t.create_date <= '$end_date'");
                }
            }

            $criteria->addCondition("
            t.id IN (
                SELECT 
                    order_id 
                FROM 
                    tbl_order_details od 
                INNER JOIN tbl_package p ON od.item_id = p.code
                WHERE 
                    od.type = 'package'
                    AND p.type NOT IN ('" . ReportForm::SIMKIT . "',
                        '" . ReportForm::FLEXIBLE_SMS_INT . "',
                        '" . ReportForm::FLEXIBLE_SMS_EXT . "',
                        '" . ReportForm::FLEXIBLE_CALL_EXT . "',
                        '" . ReportForm::FLEXIBLE_CALL_INT . "',
                        '" . ReportForm::FLEXIBLE_DATA . "'
                    )
            )
            AND t.id NOT IN (SELECT order_id FROM tbl_order_details WHERE type IN('sim','esim','price_term','price_ship'))
        ");

            if (!empty($this->item_id)) {
                $criteria->addCondition("t.id IN (SELECT order_id FROM tbl_order_details WHERE item_id = '$this->item_id')");
            }

            $criteria->order = "t.create_date DESC";

            if ($returnCriteria) {
                return $criteria;
            }
            
            if ($dataProvider) {
                return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'params' => array(
                            "AOrders[start_date]" => $this->start_date,
                            "AOrders[end_date]" => $this->end_date,
                            "AOrders[item_id]" => $this->item_id,
                            "AOrders[id]" => $this->id,
                            "AOrders[phone_contact]" => $this->phone_contact,
                        ),
                        'pageSize' => 30,
                    ),
                ));
            } else {
                return AOrders::model()->findAll($criteria);
            }

        }
    }
