<?php


    class ACommissionPackageByDate extends CommissionPackageByDate
    {

        public $total_order;
        public $total_renueve;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id', 'required'),
                array('type, total, total_income, status, affiliate_channel', 'numerical', 'integerOnly' => TRUE),
                array('id, item_id', 'length', 'max' => 11),
                array('amount', 'length', 'max' => 10),
                array('order_province_code', 'length', 'max' => 255),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, create_date, type, total, total_income, status, amount, affiliate_channel, order_province_code, item_id', 'safe', 'on' => 'search'),
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
                'id'                  => 'ID',
                'create_date'         => 'Ngày tạo log',
                'type'                => 'Nhóm gói',
                'total'               => 'Sản lượng',
                'total_income'        => 'Tổng doanh thu',
                'status'              => 'Trạng thái',
                'amount'              => 'Hoa hồng',
                'affiliate_channel'   => 'Kênh bán hàng',
                'order_province_code' => 'Mã TTKD',
                'item_id'             => 'Gói',
                'total_order'         => 'Tổng sản lượng',
                'total_renueve'       => 'Tổng doanh thu',
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
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('total', $this->total);
            $criteria->compare('total_income', $this->total_income);
            $criteria->compare('status', $this->status);
            $criteria->compare('amount', $this->amount, TRUE);
            $criteria->compare('affiliate_channel', $this->affiliate_channel);
            $criteria->compare('order_province_code', $this->order_province_code, TRUE);
            $criteria->compare('item_id', $this->item_id, TRUE);

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
         * @return CommissionPackageByDate the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
