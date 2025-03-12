<?php

    class AOrderDetails extends OrderDetails
    {
        public $total_receive;
        public $total_renueve;
        const TYPE_SIM        = 'sim';
        const TYPE_PACKAGE    = 'package';
        const TYPE_CARD       = 'card';
        const TYPE_TOPUP      = 'topup';
        const TYPE_PRICE_TERM = 'price_term';
        const TYPE_PRICE_SHIP = 'price_ship';
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, item_id', 'required'),
                array('quantity, status', 'numerical', 'integerOnly' => TRUE),
                array('order_id, item_id, item_name, type', 'length', 'max' => 255),
                array('price', 'length', 'max' => 10),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('order_id, item_id, item_name, quantity, price, type, status', 'safe', 'on' => 'search'),
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
                'order_id'      => Yii::t('adm/label', 'order_id'),
                'product_id'    => Yii::t('adm/label', 'product_id'),
                'quantity'      => Yii::t('adm/label', 'quantity'),
                'price'         => Yii::t('adm/label', 'price'),
                'status'        => Yii::t('adm/label', 'status'),
                'item_id'       => Yii::t('adm/label', 'item_id'),
                'item_name'     => Yii::t('adm/label', 'item_name'),
                'type'          => Yii::t('adm/label', 'item_type'),
                'total_renueve' => 'Tổng doanh thu',
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

            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('item_id', $this->item_id, TRUE);
            $criteria->compare('item_name', $this->item_name, TRUE);
            $criteria->compare('quantity', $this->quantity);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('type', $this->type, TRUE);
            $criteria->compare('status', $this->status);

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
         * @return AOrderDetails the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getItemPrice($order_id, $type = '')
        {
            $criteria = new CDbCriteria();

            if ($type != '') {

                $criteria->condition = "order_id ='" . $order_id . "' and type ='" . $type . "'";
                $orders              = AOrderDetails::model()->find($criteria);
                if ($orders) {
                    return $orders->price;
                }
            } else {
                $sim = ASim::model()->findByAttributes(array('order_id' => $order_id));
                if ($sim) {
                    $criteria->select = "SUM(price) as total_renueve";
                    if ($sim->type == ASim::TYPE_PREPAID) {
                        $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','package')";
                    }
                    if ($sim->type == ASim::TYPE_POSTPAID) {
                        $criteria->condition = "order_id ='" . $order_id . "' and type IN('sim','price_term')";
                    }
                    $orders = AOrderDetails::model()->findAll($criteria);
                    if ($orders) {
                        return $orders[0]->total_renueve;
                    }
                }
            }


            return 0;
        }

        public static function getSimKitByOrders($order_id)
        {
            if ($order_id) {
                $order_details = AOrderDetails::model()->findByAttributes(array('type' => 'sim', 'order_id' => $order_id));
                if ($order_details) {
                    return isset($order_details->item_name) ? $order_details->item_name : '';
                }
            }

            return "";
        }
        /**
         * @param ASim          $modelSim
         * @param AOrders       $modelOrder
         * @param AOrderDetails $order_details
         */
        public function setOrderDetailsPriceTerm(ASim $modelSim, AOrders $modelOrder, AOrderDetails &$order_details)
        {
            $order_details->order_id  = $modelOrder->id;
            $order_details->item_id   = 'price_term_' . $modelSim->msisdn;
            $order_details->item_name = 'price_term_' . $modelSim->msisdn;
            $order_details->price     = $modelSim->price_term;
            $order_details->quantity  = 1;
            $order_details->type      = AOrderDetails::TYPE_PRICE_TERM;
        }
        /**
         * @param AOrders       $modelOrder
         * @param AOrderDetails $order_details
         */
        public function setOrderDetailsPriceShip(AOrders $modelOrder, AOrderDetails &$order_details)
        {
            $order_details->order_id  = $modelOrder->id;
            $order_details->item_id   = 'price_ship_' . $modelOrder->id;
            $order_details->item_name = 'price_ship_' . $modelOrder->id;
            $order_details->price     = $modelOrder->price_ship;
            $order_details->quantity  = 1;
            $order_details->type      = AOrderDetails::TYPE_PRICE_SHIP;
        }
        /**
         * @param APackage      $modelPackage
         * @param AOrders       $modelOrder
         * @param AOrderDetails $order_details
         */
        public function setOrderDetailsPackage(APackage $modelPackage, AOrders $modelOrder, AOrderDetails &$order_details)
        {
            $order_details->order_id  = $modelOrder->id;
            $order_details->item_id   = $modelPackage->code;
            $order_details->item_name = $modelPackage->name;
            $order_details->price     = $modelPackage->price;
            $order_details->quantity  = 1;
            $order_details->type      = AOrderDetails::TYPE_PACKAGE;
        }


        /**
         * @param               $card_value
         * @param AOrders       $modelOrder
         * @param AOrderDetails $order_details
         */
        public function setOrderDetailsCard($card_value, AOrders $modelOrder, AOrderDetails &$order_details)
        {
            $order_details->order_id = $modelOrder->id;
            $order_details->price    = $card_value;
            $order_details->quantity = 1;
            $order_details->type     = AOrderDetails::TYPE_CARD;
        }
        /**
         * @param WSim          $modelSim
         * @param WOrders       $modelOrder
         * @param WOrderDetails $order_details
         */
        public function setOrderDetailsSim(ASim $modelSim, AOrders $modelOrder, AOrderDetails &$order_details)
        {
            $order_details->order_id  = $modelOrder->id;
            $order_details->item_name = $modelSim->msisdn;
            $order_details->price     = $modelSim->price;
            $order_details->quantity  = 1;
            $order_details->type      = AOrderDetails::TYPE_SIM;
        }
        public function getAllType()
        {
            return array(
                'sim'        => 'sim',
                'package'    => 'package',
                'price_term' => 'price_term',
                'price_ship' => 'price_ship',
            );
        }
        // kiểm tra có phải gói heyz không
        public static function checkHeyZ($order_id)
        {
            $order_details_package = AOrderDetails::model()->findByAttributes(array('order_id' => $order_id, 'type' => 'package'));
            if($order_details_package->item_id == 'SPS_PRODUCT_HEYZB30') { //
                return TRUE;
            }
            return false;
        }

    }
