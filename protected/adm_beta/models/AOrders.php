<?php

    class AOrders extends Orders
    {
        const ORDER_PENDING  = 0;
        const ORDER_ACTIVE   = 1;
        const ORDER_INACTIVE = 8;
        const ORDER_COMPLETE = 10;

        const VINAPHONE_TELCO = 'VINAPHONE';

        const DELIVERY_TYPE_SHOP = 'delivery_shop';
        const DELIVERY_TYPE_HOME = 'delivery_home';

        const COD    = 1;
        const ONLINE = 2;

        const COD_PAYMENT_METHOD = 4;

        const SALEOFFICE_PERSION  = 1; //Người đại diện phòng bán hàng.
        const BRANDOFFICE_PERSION = 2; // Người đại diện điểm giao dịch.

        public $package;
        public $card;
        public $brand_offices;


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

        public $total_package;
        public $period;

        public $status_shipper;


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
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu",
                    'on'               => 'admin_complete,admin',
                ),

                array('full_name, phone_contact, district_code, province_code, address_detail, brand_offices', 'required', 'on' => 'register_sim'),
                array('phone_contact', 'required', 'on' => 'register_package', 'message' => Yii::t('web/portal', 'phone_contact_required')),
                array('phone_contact', 'required', 'on' => 'buy_card', 'message' => Yii::t('web/portal', 'phone_contact_buy_card')),
                array('phone_contact', 'required', 'on' => 'topup', 'message' => Yii::t('web/portal', 'phone_contact_topup')),
                array('phone_contact', 'msisdn_validation'),
                array('phone_contact', 'detectByTelco', 'on' => 'register_package, buy_card, topup'),
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, full_name, address_detail, sale_office_code, otp, brand_offices, sale_office_code, campaign_source, campaign_id', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('customer_note,note, campaign_id', 'length', 'max' => 500),
                array('last_update, receive_cash_date', 'safe'),
                array('create_date', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, receive_cash_by, receive_cash_date, invitation, create_date, sale_office_code, last_update, shipper_id,sim, delivery_type, payment_method, full_name, district_code, province_code, address_detail, phone_contact, customer_note, otp, campaign_source, campaign_id', 'safe', 'on' => 'search'),
            );
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
                'delivery_type'         => "Hình thức",
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
        public function search($post = FALSE, $excel = FALSE)
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
            $criteria->compare('t.phone_contact', $this->phone_contact, TRUE);
            if ($this->status_shipper != '') {
                if ($this->status_shipper != '3' && $this->status_shipper != '4' && $this->status_shipper != '5' && $this->status_shipper != '6') { // Không gửi trả.
                    $criteria->addCondition("so.status ='" . $this->status_shipper . "' and so.order_status !='2'");
                } else {
                    if ($this->status_shipper == '3') { // Gửi trả
                        $criteria->addCondition("os.confirm ='3'");
//                        $criteria->addCondition("so.order_status ='2'");
                    } else if ($this->status_shipper == '4') { // Hủy
                        $criteria->addCondition("os.confirm IN ('2')");
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

            $criteria->addCondition("t.payment_method !=''");
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

                return AOrders::model()->findAll($criteria);
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
                            "AOrders[delivery_type]"    => $this->delivery_type,
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
                            "AOrders[delivery_type]"    => $this->delivery_type,
                        ),
                        'pageSize' => 30,
                    ),
                ));
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
            $criteria->compare('t.phone_contact', $this->phone_contact, TRUE);
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
            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='$this->start_date' and t.create_date <='$this->end_date'");
            }

            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.phone_contact', $this->phone_contact, TRUE);
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

            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . " 00:00:00";
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . " 23:59:59";
            }
            $criteria = new CDbCriteria;

            $criteria->compare('t.id', $this->id, TRUE);

            $criteria->addCondition("(os.confirm =10 or os.paid=10) and os.delivered !=10 and t.delivery_type =2 and od.type='sim'");

            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='$this->start_date' and t.create_date <='$this->end_date'");
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
            $state               = AOrderState::model()->findAll($criteria);


            if ($state) {
                $state = AOrderState::model()->findAll($criteria)[count($state) - 1];
                if ($state->confirm == 10) {
                    $status_end = 'Đặt hàng thành công';
                } else if ($state->confirm == 1) {
                    $status_end = 'Khách hàng hủy';
                } else if ($state->confirm == 2 && $state->note = 'don hang bi huy do qua han') {
                    $status_end = 'Đơn hàng bị hủy do quá hạn';
                } else if ($state->confirm == 2 && $state->note = 'qua thoi gian xac nhan thanh toan') {
                    $status_end = 'Đơn hàng quá thời gian xác nhận thanh toán';
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

        public function getAllDeliveredType()
        {
            return array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );
        }

        public function getDeliveredTypeByType($id)
        {
            $data = array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );

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
                if ($sim->type == ASim::TYPE_PREPAID) {
                    return "Trả trước";
                } else if ($sim->type == ASim::TYPE_POSTPAID) {
                    return "Trả sau";
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
        public function getPeriodTime()
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
            return time();
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

            $logMsg[] = array(Yii::app()->params['socket_api_url'], 'URL: ' . __LINE__, 'T', time());
            $logMsg[] = array($str_json, 'Input: ' . __LINE__, 'T', time());

            //call api
            $response  = Utils::cUrlPostJson(Yii::app()->params['socket_api_url'], $str_json);
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
        public function getAllStatusReport()
        {

            $data = array(
                5 => "Chưa giao",
                1 => "Đã giao",
                3 => "Gửi trả",
                4 => "Hủy",
                6 => "Chưa phân công",
            );

            return $data;
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
        public static function checkOrdersSessionExists()
        {
            $flag = FALSE;
            if ((time() - Yii::app()->session['session_cart']) < Yii::app()->params['sessionTimeout']
                && isset(Yii::app()->session['orders_data'])
                && !empty(Yii::app()->session['orders_data'])
            ) {
                $flag = TRUE;
            }

            return $flag;
        }

        public static function unsetSession()
        {
            unset(Yii::app()->session['session_cart']);
            unset(Yii::app()->session['orders_data']);
        }
    }
