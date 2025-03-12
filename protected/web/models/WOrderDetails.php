<?php

class WOrderDetails extends OrderDetails
{
    const TYPE_SIM = 'sim';
    const TYPE_PACKAGE = 'package';
    const TYPE_CARD = 'card';
    const TYPE_TOPUP = 'topup';
    const TYPE_PRICE_TERM = 'price_term';
    const TYPE_PRICE_SHIP = 'price_ship';
    const TYPE_ESIM = 'esim';
    
    public $total_revenue;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order_id, item_id, quantity, price', 'required', 'on' => 'register_package, register_sim'),
            array('order_id, quantity', 'required', 'on' => 'buy_card, topup'),
            array('price', 'required', 'message' => Yii::t('web/portal', 'choose_card_value'), 'on' => 'buy_card, topup'),
            array('price', 'checkPrice', 'on' => 'buy_card, topup'),
            array('quantity, status', 'numerical', 'integerOnly' => TRUE),
            array('order_id, item_id, item_name, type', 'length', 'max' => 255),
            array('price', 'length', 'max' => 10),
            array('quantity', 'minQuantity', 'on' => 'buy_card, topup'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('order_id, item_id, item_name, quantity, price, type, status', 'safe', 'on' => 'search'),
            array('total_revenue', 'safe'),
        );
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkPrice($attribute, $params)
    {
        if ($this->$attribute < 0) {
            $this->addError($attribute, Yii::t('web/portal', 'choose_card_value'));
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function minQuantity($attribute, $params)
    {
        if ($this->$attribute < 0) {
            $this->addError($attribute, Yii::t('web/portal', 'min_quantity'));
        }
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
            'order_id' => Yii::t('web/portal', 'order_id'),
            'item_id' => Yii::t('web/portal', 'item_id'),
            'item_name' => Yii::t('web/portal', 'item_name'),
            'quantity' => Yii::t('web/portal', 'quantity'),
            'price' => Yii::t('web/portal', 'price'),
            'type' => Yii::t('web/portal', 'type_detail'),
            'status' => Yii::t('web/portal', 'status'),
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
     * @return WOrderDetails the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param            $order_id
     * @param bool|FALSE $dataProvider
     *
     * @return array|CActiveDataProvider|mixed|null
     */
    public static function getOrderDetailsByOrderId($order_id, $dataProvider = FALSE)
    {
        $criteria = new CDbCriteria;
        $criteria->distinct = TRUE;
        $criteria->condition = 'order_id=:order_id';
        $criteria->params = array(':order_id' => $order_id);
        if ($dataProvider) {
            $results = new CActiveDataProvider(self::model(), array(
                'criteria' => $criteria,
            ));
        } else {
            $results = self::model()->findAll($criteria);
        }

        return $results;
    }

    /**
     * @param WSim $modelSim
     * @param WOrders $modelOrder
     * @param WOrderDetails $order_details
     */
    public function setOrderDetailsSim(WSim $modelSim, WOrders $modelOrder, WOrderDetails &$order_details)
    {
        $esim = Yii::app()->session['orders_data']->sim_type;
        $order_details->order_id = $modelOrder->id;
        $order_details->item_name = $modelSim->msisdn;
        $order_details->price = $modelSim->price;
        $order_details->quantity = 1;
        if (!$esim) {
            $order_details->type = WOrderDetails::TYPE_SIM;
        } elseif ($esim) {
            $order_details->type = WOrderDetails::TYPE_ESIM;
        }
    }

    /**
     * @param WSim $modelSim
     * @param WOrders $modelOrder
     * @param WOrderDetails $order_details
     */
    public function setOrderDetailsPriceTerm(WSim $modelSim, WOrders $modelOrder, WOrderDetails &$order_details)
    {
        $order_details->order_id = $modelOrder->id;
        $order_details->item_id = 'price_term_' . $modelSim->msisdn;
        $order_details->item_name = 'price_term_' . $modelSim->msisdn;
        $order_details->price = $modelSim->price_term;
        $order_details->quantity = 1;
        $order_details->type = WOrderDetails::TYPE_PRICE_TERM;
    }

    /**
     * @param WOrders $modelOrder
     * @param WOrderDetails $order_details
     */
    public function setOrderDetailsPriceShip(WOrders $modelOrder, WOrderDetails &$order_details)
    {
        $order_details->order_id = $modelOrder->id;
        $order_details->item_id = 'price_ship_' . $modelOrder->id;
        $order_details->item_name = 'price_ship_' . $modelOrder->id;
        $order_details->price = $modelOrder->price_ship;
        $order_details->quantity = 1;
        $order_details->type = WOrderDetails::TYPE_PRICE_SHIP;
    }

    /**
     * @param WPackage $modelPackage
     * @param WOrders $modelOrder
     * @param WOrderDetails $order_details
     */
    public function setOrderDetailsPackage(WPackage $modelPackage, WOrders $modelOrder, WOrderDetails &$order_details)
    {
        $order_details->order_id = $modelOrder->id;
        $order_details->item_id = $modelPackage->code;
        $order_details->item_name = $modelPackage->name;
        $order_details->price = $modelPackage->price;
        $order_details->quantity = 1;
        $order_details->type = WOrderDetails::TYPE_PACKAGE;
    }

    /**
     * @param         $package_code
     *  $package_code = array ('FFDVI1', 'FFDVO2', 'FFDSI1', 'FFDD1',.. )
     * @param WOrders $modelOrder
     * @param         $details
     * @param         $by_code
     */
    public static function setOrderDetailsPackageFlexible($package_code, WOrders $modelOrder, &$details, $by_code = TRUE)
    {
        foreach ($package_code as $value) {
            $order_details = new WOrderDetails();
            if ($by_code) {
                $modelPackage = WPackage::model()->find('code=:code', array(':code' => $value));
            } else {
                $modelPackage = WPackage::model()->find('id=:id', array(':id' => $value));
            }
            if ($modelPackage) {
                //check price_discount
                if ($modelPackage->price_discount > 0) {
                    $modelPackage->price = $modelPackage->price_discount;
                } elseif ($modelPackage->price_discount == -1) {
                    $modelPackage->price = 0;
                }
                $order_details->order_id = $modelOrder->id;
                $order_details->item_id = $modelPackage->code;
                $order_details->item_name = $modelPackage->name;
                $order_details->price = $modelPackage->price;
                $order_details->quantity = 1;
                $order_details->type = WOrderDetails::TYPE_PACKAGE;
            }

            $details[] = $order_details->attributes;
        }
    }

    /**
     * @param               $card_value
     * @param WOrders $modelOrder
     * @param WOrderDetails $order_details
     */
    public function setOrderDetailsCard($card_value, WOrders $modelOrder, WOrderDetails &$order_details)
    {
        $order_details->order_id = $modelOrder->id;
        $order_details->price = $card_value;
        $order_details->quantity = 1;
        $order_details->type = WOrderDetails::TYPE_CARD;
    }

    public static function getTypeLabel($type)
    {
        $arr_type = array(
            self::TYPE_SIM => Yii::t('web/portal', 'type_sim'),
            self::TYPE_PACKAGE => Yii::t('web/portal', 'type_package'),
            self::TYPE_CARD => Yii::t('web/portal', 'type_card'),
            self::TYPE_PRICE_SHIP => Yii::t('web/portal', 'type_price_ship'),
            self::TYPE_PRICE_TERM => Yii::t('web/portal', 'type_price_term'),
        );

        return (isset($arr_type[$type])) ? $arr_type[$type] : $type;
    }
}
