<?php


    class ACommisionsSimDetails extends CommisionsSimDetails
    {
        public $total_order;
        public $total_renueve;

        public $item_sim_type;

        public $total;
        public $revenue;
        public $rose;

        public $active_time;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_status, item_term, affiliate_channel, sub_type', 'numerical', 'integerOnly' => TRUE),
                array('order_id, order_province_code, order_note, phone_customer, item_id, item_name, item_price, affiliate_click_id, affiliate_username, affiliate_province_code', 'length', 'max' => 255),
                array('item_price_term, amount', 'length', 'max' => 10),
                array('order_create_date, create_date, item_sim_type, 
                    total, revenue, rose, active_time', 'safe'
                ),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, order_province_code, order_status, order_note, order_create_date, phone_customer, item_id, item_name, item_price, item_term, item_price_term, amount, create_date, affiliate_click_id, affiliate_channel, affiliate_username, affiliate_province_code, sub_type', 'safe', 'on' => 'search'),
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
                'id'                      => 'ID',
                'order_id'                => 'Mã ĐH',
                'order_province_code'     => 'TTKD',
                'order_status'            => 'Trạng thái',
                'order_note'              => 'Lý do',
                'order_create_date'       => 'Ngày đặt hàng',
                'phone_customer'          => 'Điện thoại khách hàng',
                'item_id'                 => 'Item',
                'item_name'               => 'Số thuê bao',
                'item_price'              => 'Tiền sim',
                'item_term'               => 'Tiền đặt cọc',
                'item_price_term'         => 'Tiền đặt cọc',
                'amount'                  => 'Hoa hồng bán sim',
                'create_date'             => 'Ngày tạo log',
                'affiliate_click_id'      => 'TransID',
                'affiliate_channel'       => 'Kênh bán hàng',
                'affiliate_username'      => 'Affiliate Username',
                'affiliate_province_code' => 'Affiliate Province Code',
                'sub_type'                => 'Hình thức',

                'active_time'             => 'Thời gian kích hoạt',
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
            $criteria->compare('order_province_code', $this->order_province_code, TRUE);
            $criteria->compare('order_status', $this->order_status);
            $criteria->compare('order_note', $this->order_note, TRUE);
            $criteria->compare('order_create_date', $this->order_create_date, TRUE);
            $criteria->compare('phone_customer', $this->phone_customer, TRUE);
            $criteria->compare('item_id', $this->item_id, TRUE);
            $criteria->compare('item_name', $this->item_name, TRUE);
            $criteria->compare('item_price', $this->item_price, TRUE);
            $criteria->compare('item_term', $this->item_term);
            $criteria->compare('item_price_term', $this->item_price_term, TRUE);
            $criteria->compare('amount', $this->amount, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('affiliate_click_id', $this->affiliate_click_id, TRUE);
            $criteria->compare('affiliate_channel', $this->affiliate_channel);
            $criteria->compare('affiliate_username', $this->affiliate_username, TRUE);
            $criteria->compare('affiliate_province_code', $this->affiliate_province_code, TRUE);
            $criteria->compare('sub_type', $this->sub_type);

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
         * @return CommisionsSimDetails the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
