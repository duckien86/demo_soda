<?php

    class AFTActions extends FTActions
    {
        const ORDER_REJECTED_STATUS   = -1; //Từ chối
        const ORDER_PROCESSING_STATUS = 0; //Đang xử lý
        const ORDERING_STATUS         = 1; //(Đơn hàng đã đặt và đang chờ xử lý)
        const ORDER_CANCEL_STATUS     = 2; //Đã huỷ (KH Huỷ đơn hàng)
        const ORDER_PAIED_STATUS      = 3; //Đã thanh toán (Hoàn thành)
        const ORDER_ORTHER_STATUS     = 99; //Đã thanh toán (Hoàn thành)

        const CATEGORY_SIM          = 1;     //sim
        const CATEGORY_DATA_PACKAGE = 2; //gói cước

        //Kiểu thuê bao,gói cước
        const SIM_TRA_TRUOC = 1;
        const SIM_TRA_SAU   = 2;
        const GOI_CUOC_DATA = 3;
        const GOI_VAS       = 4;

        public $start_date;
        public $end_date;
        public $input_type;
        public $info_search;
        public $status_change;

        public $total_commision;
        public $total_renueve;
        public $total_order;

        public $price_sim;
        public $price_package;
        public $agency_id;

        public $bundle;

        public $active_time;

        const PRE_PAID  = 1;
        const POST_PAID = 2;
        const DATA      = 3;
        const VAS       = 4;
        const SIMKIT    = 5;
        const REDEEM    = 6;

        CONST CAMPAIGN_CATEGORY_ID_SIM = 3;     //sim
        CONST CAMPAIGN_CATEGORY_ID_PACKAGE = 4; //gói
        CONST CAMPAIGN_CATEGORY_ID_CONSUME = 5; //tiêu dùng TKC

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('start_date, end_date', 'required'),
                array(
                    'end_date',
                    'compare',
                    'compareAttribute' => 'start_date',
                    'operator'         => '>=',
                    'allowEmpty'       => FALSE,
                    'message'          => "Ngày kết thúc phải lớn hơn ngày bắt đầu"
                ),
                array('info_search, start_date, end_date', 'required'),
//                array('action_id, transaction_id, publisher_id, amount, action_status, created_on, order_code, product_name', 'required'),
                array('campaign_id, campaign_category_id, action_status', 'numerical', 'integerOnly' => TRUE),
                array('merchant_id, amount, type, price, price_discount, total_money', 'numerical'),
                array('action_id, transaction_id, publisher_id, click_id', 'length', 'max' => 128),
                array('inviter_code', 'length', 'max' => 255),
                array('order_code, product_name', 'length', 'max' => 1024),
                array('client_name, client_email, client_address, product_id, personal_photo_font_url, personal_photo_behind_url', 'length', 'max' => 512),
                array('client_mobile', 'length', 'max' => 20),
                array('quantity, sale_offices_id, yearmonth', 'length', 'max' => 11),
                array('personal_id', 'length', 'max' => 60),
                array('order_time, note, extra_params, package_code, msisdn, 
                    bundle, active_time', 'safe'),
                array('short_link_id', 'length', 'max' => 10),
                array('utm_source', 'length', 'max' => 255),
                array('package_code, vnp_province_id', 'length', 'max' => 255),
                array('msisdn', 'length', 'max' => 20),
                array('utm_medium, utm_campaign, utm_content', 'length', 'max' => 512),
                array('commissions_type', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('action_id, transaction_id, merchant_id, publisher_id, campaign_id, click_id,inviter_code,  campaign_category_id, amount,type, action_status, created_on, order_code, client_name, client_email, client_address, client_mobile, order_time, note, quantity, product_name, package_code,msisdn,  product_id, price, price_discount, total_money, personal_id, personal_photo_font_url, personal_photo_behind_url, extra_params,sale_offices_id, vnp_province_id, commissions_type, yearmonth', 'safe', 'on' => 'search'),
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
                'action_id'                 => 'action_id',
                'transaction_id'            => 'transaction_id',
                'merchant_id'               => 'merchant_id',
                'publisher_id'              => 'ID CTV/ĐLTC',
                'campaign_id'               => 'Campaign ID',
                'click_id'                  => 'Click ID',
                'inviter_code'              => 'Mã giới thiệu',
                'campaign_category_id'      => 'Danh mục',
                'amount'                    => 'Hoa hồng',
                'action_status'             => 'Trạng thái',
                'created_on'                => 'Ngày tạo',
                'order_code'                => 'Mã ĐH',
                'client_name'               => 'Tên khách hàng',
                'client_email'              => 'Email',
                'client_address'            => 'Địa chỉ',
                'client_mobile'             => 'Điện thoại',
                'order_time'                => 'Ngày mua',
                'note'                      => 'Ghi chú',
                'quantity'                  => 'Số lượng',
                'product_name'              => 'Tên sản phẩm',
                'package_code'              => 'Mã gói',
                'msisdn'                    => 'Sô thuê bao',
                'product_id'                => 'Product ID',
                'price'                     => 'Giá gốc',
                'price_discount'            => 'Giá mua',
                'total_money'               => 'Tổng tiền',
                'personal_id'               => 'Số CMT/căn cước',
                'personal_photo_font_url'   => 'Ảnh mặt trước CMT',
                'personal_photo_behind_url' => 'Ảnh mặt sau CMT',
                'extra_params'              => 'Extra Params',
                'type'                      => 'Loại thuê bao',
                'bundle'                    => 'Loại gói',

                'short_link_id'    => 'Link rút gọn',
                'utm_source'       => 'utm_source',
                'utm_medium'       => 'utm_medium',
                'utm_campaign'     => 'utm_campaign',
                'utm_content'      => 'utm_content',
                'sale_offices_id'  => 'ID phòng bán hàng',
                'vnp_province_id'  => 'Mã trung tâm kinh doanh',
                'commissions_type' => 'Kiểu hoa hồng',
                'yearmonth'        => 'Hoa hồng gia hạn tháng',
                'start_date'       => 'Ngày bắt đầu',
                'end_date'         => 'Ngày kêt thúc',
                'input_type'       => 'Chọn tiêu chí tra cứu',
                'info_search'      => 'Thông tin tra cứu',
                'active_time'      => 'Ngày kích hoạt',
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

            $criteria->compare('action_id', $this->action_id, TRUE);
            $criteria->compare('transaction_id', $this->transaction_id, TRUE);
            $criteria->compare('merchant_id', $this->merchant_id, TRUE);
            $criteria->compare('publisher_id', $this->publisher_id, TRUE);
            $criteria->compare('campaign_id', $this->campaign_id);
            $criteria->compare('click_id', $this->click_id, TRUE);
            $criteria->compare('inviter_code', $this->inviter_code, TRUE);
            $criteria->compare('campaign_category_id', $this->campaign_category_id);
            $criteria->compare('amount', $this->amount);
            $criteria->compare('type', $this->type);
            $criteria->compare('action_status', $this->action_status);
            $criteria->compare('created_on', $this->created_on, TRUE);
            $criteria->compare('order_code', $this->order_code, TRUE);
            $criteria->compare('client_name', $this->client_name, TRUE);
            $criteria->compare('client_email', $this->client_email, TRUE);
            $criteria->compare('client_address', $this->client_address, TRUE);
            $criteria->compare('client_mobile', $this->client_mobile, TRUE);
            $criteria->compare('order_time', $this->order_time, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('quantity', $this->quantity, TRUE);
            $criteria->compare('product_name', $this->product_name, TRUE);
            $criteria->compare('package_code', $this->package_code, TRUE);
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('product_id', $this->product_id, TRUE);
            $criteria->compare('price', $this->price);
            $criteria->compare('price_discount', $this->price_discount);
            $criteria->compare('total_money', $this->total_money);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_photo_font_url', $this->personal_photo_font_url, TRUE);
            $criteria->compare('personal_photo_behind_url', $this->personal_photo_behind_url, TRUE);
            $criteria->compare('extra_params', $this->extra_params, TRUE);

            $criteria->compare('short_link_id', $this->short_link_id, TRUE);
            $criteria->compare('utm_source', $this->utm_source, TRUE);
            $criteria->compare('utm_medium', $this->utm_medium, TRUE);
            $criteria->compare('utm_campaign', $this->utm_campaign, TRUE);
            $criteria->compare('utm_content', $this->utm_content, TRUE);
            $criteria->compare('sale_offices_id', $this->sale_offices_id, TRUE);
            $criteria->compare('vnp_province_id', $this->vnp_province_id, TRUE);
            $criteria->compare('commissions_type', $this->commissions_type, TRUE);
            $criteria->compare('yearmonth', $this->yearmonth, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 'created_on DESC'),
                'pagination' => array(
                    'pageSize' => 20
                )
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AFTActions the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getAllType()
        {
            return array(
                self::ORDER_REJECTED_STATUS,
                self::ORDER_PROCESSING_STATUS,
                self::ORDERING_STATUS,
                self::ORDER_CANCEL_STATUS,
                self::ORDER_PAIED_STATUS,
                self::ORDER_ORTHER_STATUS,
            );
        }

        public static function getAllActionStatus()
        {
            return array(
                array('id' => self::ORDERING_STATUS, 'name' => 'Đặt hàng'),
                array('id' => self::ORDER_CANCEL_STATUS, 'name' => 'Hủy'),
                array('id' => self::ORDER_PAIED_STATUS, 'name' => 'Thành công'),
                array('id' => self::ORDER_ORTHER_STATUS, 'name' => 'Khác'),
            );
        }

        public static function getAllActionStatusNameById($id)
        {
            $arr  = self::getAllActionStatus();
            $name = 'Khác';
            foreach ($arr as $it) {
                if ($id == $it['id']) {
                    $name = $it['name'];
                    break;
                }
            }

            return $name;
        }

        public static function getAllActionStatusName($action_status)
        {
            $arr = self::getAllActionStatus();
            $str = $action_status . ' - ' . 'Khác';
            foreach ($arr as $it) {
                if ($it['id'] == $action_status) {
                    $str = $action_status . ' - ' . $it['name'];;
                    break;
                }
            }

            return $str;
        }

        public static function getAllProductType()
        {
            return array(
                array('id' => self::SIM_TRA_TRUOC, 'name' => 'Trả trước'),
                array('id' => self::SIM_TRA_SAU, 'name' => 'Trả sau'),
                array('id' => self::GOI_CUOC_DATA, 'name' => 'Gói data'),
                array('id' => self::GOI_VAS, 'name' => 'VAS'),
            );
        }


        public static function getTypeNameById($id)
        {
            $arr = self::getAllProductType();
            $str = 'Khác';
            foreach ($arr as $it) {
                if ($it['id'] == $id) {
                    $str = $it['name'];;
                    break;
                }
            }

            return $str;
        }

        /**
         * @param $type
         * Lấy tổng sản lượng
         *
         * @return mixed
         */
        public function getTotal($type, $user_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "distinct order_code";
            $criteria->condition = "type ='" . $type . "' and action_status =3 and publisher_id='" . $user_id . "'";
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }
            $data = AFTActions::model()->count($criteria);

            return $data;
        }

        /**
         * @param $type
         * Lấy tổng doanh thu
         *
         * @return mixed
         */
        public function getRenueve($type, $user_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "SUM(total_money) as total_renueve";
            $criteria->condition = "type ='" . $type . "' and action_status =3 and publisher_id='" . $user_id . "'";
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }
            $data = isset(AFTActions::model()->findAll($criteria)[0]->total_renueve) ? AFTActions::model()->findAll($criteria)[0]->total_renueve : 0;

            return $data;
        }

        /**
         * @param $type
         * Lấy tổng hoa hồng
         *
         * @return mixed
         */
        public function getCommision($type, $user_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "SUM(amount) as total_commision";
            $criteria->condition = "type ='" . $type . "' and action_status =3 and publisher_id='" . $user_id . "'";
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }
            $data = isset(AFTActions::model()->findAll($criteria)[0]->total_commision) ? AFTActions::model()->findAll($criteria)[0]->total_commision : 0;

            return $data;
        }

        /**
         * @param $type
         * Lấy tổng hoa hồng
         *
         * @return mixed
         */
        public function getCtv2($inviter_code)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "Count(user_id) as ctv_2";
            $criteria->condition = "inviter_code ='" . $inviter_code . "'";

            $data = isset(CskhCtvUsers::model()->findAll($criteria)[0]->ctv_2) ? CskhCtvUsers::model()->findAll($criteria)[0]->ctv_2 : 0;

            return $data;
        }

        /**
         * @param $type
         * Lấy tổng hoa hồng
         *
         * @return mixed
         */
        public function getCommisionAward($user_id)
        {
            $criteria = new CDbCriteria();

            $criteria->select    = "SUM(amout) as commision_award";
            $criteria->condition = "publisher_id ='" . $user_id . "'";

            $data = isset(CskhCtvPublisherAward::model()->findAll($criteria)[0]->commision_award) ? CskhCtvPublisherAward::model()->findAll($criteria)[0]->commision_award : 0;

            return $data;
        }


        /**
         * @param $type
         * Lấy tổng hoa hồng
         *
         * @return mixed
         */
        public function getDetailsCommision($type, $user_id, $post = 1)
        {
            $criteria = new CDbCriteria();
            if (!Yii::app()->cache->get('start_date_commisson_ctv_' . $type)
                && !Yii::app()->cache->get('end_date_commisson_ctv_' . $type)
                && !Yii::app()->cache->get('user_id_commisson_ctv_' . $type)
                && !Yii::app()->cache->get('type_commisson_ctv_' . $type)
            ) {
                Yii::app()->cache->set('start_date_commisson_ctv_' . $type, $this->start_date);
                Yii::app()->cache->set('end_date_commisson_ctv_' . $type, $this->end_date);
                Yii::app()->cache->set('user_id_commisson_ctv_' . $type, $user_id);
                Yii::app()->cache->set('type_commisson_ctv_' . $type, $type);
            }
            $criteria->select = "order_code, msisdn, type, order_time, amount, product_name, total_money";

            $criteria->condition = "campaign_category_id ='" . $type . "' and action_status =3 and publisher_id ='" . $user_id . "'";
            if ($this->start_date != '' && $this->end_date != '') {
                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }

            if ($post == 2) {
                $criteria->condition = "1=1";
                if ($this->order_code != '') {
                    $criteria->addCondition("order_code ='" . $this->order_code . "'");
                }
                if (Yii::app()->cache->get('start_date_commisson_ctv_' . $type)
                    && Yii::app()->cache->get('end_date_commisson_ctv_' . $type)
                    && Yii::app()->cache->get('user_id_commisson_ctv_' . $type)
                    && Yii::app()->cache->get('type_commisson_ctv_' . $type)
                ) {
                    $criteria->addCondition("created_on >='" . Yii::app()->cache->get('start_date_commisson_ctv_' . $type) . "' 
                    and created_on <='" . Yii::app()->cache->get('end_date_commisson_ctv_' . $type) . "' 
                    and campaign_category_id ='" . Yii::app()->cache->get('type_commisson_ctv_' . $type) . "' 
                    and action_status =3 and publisher_id ='" . Yii::app()->cache->get('user_id_commisson_ctv_' . $type) . "'");
                }

            }

            $data = new CActiveDataProvider('AFTActions', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.created_on asc'),
                'pagination' => array(
                    'params'   => array(
                        'AFTActions[start_date]'  => $this->start_date,
                        'AFTActions[end_date]'    => $this->end_date,
                        "AFTActions[type]"        => $type,
                        "AFTActions[info_search]" => $this->info_search,
                        "AFTActions[input_type]"  => $this->input_type,
                    ),
                    'pageSize' => 30,
                ),

            ));

            return $data;
        }



        /**
         * Lấy tên hình thức.
         */
        public static function getType($type)
        {
            $data = array(
                self::PRE_PAID  => 'Trả trước',
                self::POST_PAID => 'Trả sau',
                self::DATA      => 'Data',
                self::VAS       => 'Vas',
                self::SIMKIT    => 'Simkit',
                self::REDEEM    => 'Đổi quà',
            );

            return (isset($data[$type])) ? $data[$type] : '';
        }
    }
