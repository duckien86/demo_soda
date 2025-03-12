<?php

    class CskhOrderState extends OrderState
    {
        const UNCONFIRMED     = 0;
        const CONFIRMED       = 10;
        const CANCEL_PASS     = 2;
        const CANCEL          = 3;
        const CANCEL_CUSTOMER = 1;
        const UNPAID          = 0;
        const PAID            = 10;
        const UNDELIVERED     = 0;
        const SHIPPING        = 1;
        const DELIVERED       = 10;


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('confirm', 'numerical', 'integerOnly' => TRUE),
                array('order_id, paid, delivered', 'length', 'max' => 255),
                array('note', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, confirm, paid, delivered, create_date, note', 'safe', 'on' => 'search'),
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
                'id'          => Yii::t('adm/label', 'id'),
                'order_id'    => Yii::t('adm/label', 'order_id'),
                'confirm'     => Yii::t('adm/label', 'confirm'),
                'paid'        => Yii::t('adm/label', 'paid'),
                'delivered'   => Yii::t('adm/label', 'delivered'),
                'create_date' => Yii::t('adm/label', 'create_date'),
                'note'        => Yii::t('adm/label', 'note'),
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
            $criteria->compare('confirm', $this->confirm);
            $criteria->compare('paid', $this->paid, TRUE);
            $criteria->compare('delivered', $this->delivered, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);

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
         * @return CskhOrderState the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @return array
         */
        public function getArrayConfirm()
        {
            return array(
                self::UNCONFIRMED     => '',
                self::CONFIRMED       => Yii::t('adm/label', 'state_confirmed'),
                self::CANCEL_PASS     => "Hủy vì ĐH quá hạn",
                self::CANCEL_CUSTOMER => "Hủy vì KH yêu cầu",
                self::CANCEL          => "Hủy vì lý do khác",
                self::CONFIRMED       => Yii::t('adm/label', 'state_confirmed'),
            );
        }

        /**
         * @param $confirm
         *
         * @return mixed
         */
        public function getConfirmLabel($confirm)
        {
            $array = $this->getArrayConfirm();

            return (isset($array[$confirm])) ? $array[$confirm] : $confirm;
        }

        /**
         * @return array
         */
        public function getArrayPaid()
        {
            return array(
                self::PAID   => Yii::t('adm/label', 'state_paid'),
                self::UNPAID => '_',
            );
        }

        /**
         * @param $paid
         *
         * @return mixed
         */
        public function getPaidLabel($paid)
        {
            $array = $this->getArrayPaid();

            return (isset($array[$paid])) ? $array[$paid] : $paid;
        }

        /**
         * @return array
         */
        public function getArrayDelivered()
        {
            return array(
                self::DELIVERED   => Yii::t('adm/label', 'state_delivered'),
                self::SHIPPING    => Yii::t('adm/label', 'state_shipping'),
                self::UNDELIVERED => '_',
            );
        }

        public function getDeliveryType($type)
        {
            $data = array(
                1 => 'Tại nhà',
                2 => 'Tại điểm giao dịch',
            );

            return isset($data[$type]) ? $data[$type] : '';
        }

        /**
         * @param $delivered
         *
         * @return mixed
         */
        public function getDeliveredLabel($delivered)
        {
            $array = $this->getArrayDelivered();

            return (isset($array[$delivered])) ? $array[$delivered] : $delivered;
        }

        /**
         * @param            $order_id
         * @param bool|FALSE $dataProvider
         * @param int        $limit
         * @param int        $offset
         *
         * @return array|CActiveDataProvider|mixed|null
         */
        public static function getListOrderState($order_id, $dataProvider = FALSE, $limit = 10, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'order_id=:order_id';
            $criteria->params    = array(':order_id' => $order_id);
            if ($dataProvider) {
                return new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array('defaultOrder' => 'id DESC, create_date DESC'),
                    'pagination' => array(
                        'pageSize' => $limit,
                    ),
                ));
            } else {
                $criteria->limit  = $limit;
                $criteria->offset = $offset;
                $criteria->order  = 'id DESC, create_date DESC';
                $results          = self::model()->findAll($criteria);

                return $results;
            }
        }

        /**
         * @param $order_id
         * Lấy chi tiết đơn hàng.
         *
         * @return CActiveDataProvider
         */
        public static function getDetailOrder($order_id)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.order_id=:order_id';
            $criteria->params    = array(':order_id' => $order_id);

            return new CActiveDataProvider('CskhOrderDetails', array(
                'criteria' => $criteria,
            ));

        }

        /**
         * Lấy tên của loại sản phẩm.
         */
        public static function getNameType($type)
        {
            $data = array(
                'sim'        => 'sim',
                'package'    => 'gói cước',
                'price_term' => 'Tiền đặt cọc',
                'price_ship' => 'Tiền ship',
            );

            return isset($data[$type]) ? $data[$type] : 'Chưa xác định';
        }

        /**
         * Lấy thể loại của sản phẩm trong chi tiết shipper.
         *
         * @param     $product
         * @param int $type =0: gói cước || type=1: sim
         */
        public function getTypeOfProduct($product, $type = 0)
        {
            if ($type = 0) {
                $package = Package::model()->findByAttributes(array('code' => $product));
                if ($package) {
                    return self::getType($package->type);
                }
            } else {
                $sim = Sim::model()->findByAttributes(array('msisdn' => $product));
                if ($sim) {
                    return self::getType($sim->type);
                }
            }

            return TRUE;
        }

        /**
         * Get name_type by type.
         *
         * @param $type
         */
        public function getType($type)
        {
            $return = array(
                APackage::PACKAGE_PREPAID  => 'Trả trước',
                APackage::PACKAGE_POSTPAID => 'Trả sau',
                APackage::PACKAGE_DATA     => 'Data',
                APackage::PACKAGE_SIMKIT   => 'Sim kit',
                APackage::PACKAGE_REDEEM   => 'Đổi quà',
            );

            return (isset($return[$type])) ? $return[$type] : '';
        }


    }
