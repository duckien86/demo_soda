<?php

    class AFTContractsDetails extends FTContractsDetails
    {
        CONST TYPE_PERCENT = 1;
        CONST TYPE_VALUE   = 2;

        public $type;
        public $price_discount;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('contract_id, item_id', 'required'),
                array('contract_id, item_id, quantity, price_discount_percent, price_discount_amount', 'numerical', 'integerOnly' => TRUE),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('contract_id, item_id, quantity, price_discount_percent, price_discount_amount', 'safe', 'on' => 'search'),
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
                'contract_id'            => Yii::t('adm/label', 'contracts'),
                'item_id'                => Yii::t('adm/label', 'product'),
                'quantity'               => Yii::t('adm/label', 'quantity'),
                'price_discount_percent' => Yii::t('adm/label', 'price_discount_percent'),
                'price_discount_amount'  => Yii::t('adm/label', 'price_discount_amount'),
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

            $criteria->compare('contract_id', $this->contract_id);
            $criteria->compare('item_id', $this->item_id);
            $criteria->compare('quantity', $this->quantity);
            $criteria->compare('price_discount_percent', $this->price_discount_percent);
            $criteria->compare('price_discount_amount', $this->price_discount_amount);

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
         * @return AFTContractsDetails the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $order_id
         * @param $item_id
         * Lấy giá||tỷ lệ triết khấu.
         */
        public static function getDiscount($order_id, $item_id)
        {
            $result = 0;
            if ($order_id && $item_id) {
                $contract_id = AFTOrders::model()->findByAttributes(array('id' => $order_id));
                if ($contract_id) {
                    if (isset($contract_id->contract_id)) {
                        if (!empty($contract_id->contract_id)) {
                            $contract_details = AFTContractsDetails::model()->findByAttributes(
                                array('contract_id' => $contract_id->contract_id, 'item_id' => $item_id)
                            );
                            if ($contract_details) {
                                if (!empty($contract_details->price_discount_percent)) {
                                    $result = $contract_details->price_discount_percent;
                                } else {
                                    $result = $contract_details->price_discount_amount;
                                }
                            }
                        }
                    }

                }
            }

            return $result;
        }

        /**
         * @param $details
         *
         * @return bool
         */
        public function validateContractDetails($details)
        {
            if ($details) {
                foreach ($details as $key => $item) {
                    $quantity = (int)preg_replace('/\./', '', $item['quantity']);
                    if ($quantity > 0) {
                        $package = AFTPackage::model()->find('id=:id', array(':id' => $key));
                        if ($package) {
                            return TRUE;
                        }
                    }
                }
            }

            return FALSE;
        }

        /**
         * @param AFTContracts $contract
         * @param              $details
         */
        public function setContractDetails(AFTContracts $contract, $details)
        {
            foreach ($details as $key => $item) {
                $quantity       = (int)preg_replace('/\./', '', $item['quantity']);
                $price_discount = (int)preg_replace('/\./', '', $item['price_discount']);
                if ($quantity > 0) {
                    $package = AFTPackage::model()->find('id=:id', array(':id' => $key));
                    if ($package) {
                        $modelDetails = AFTContractsDetails::model()->find('contract_id=:contract_id AND item_id=:item_id',
                            array(':contract_id' => $contract->id, ':item_id' => $package->id)
                        );
                        if (!$modelDetails) {
                            $modelDetails              = new AFTContractsDetails();
                            $modelDetails->contract_id = $contract->id;
                            $modelDetails->item_id     = $key;
                        }
                        $modelDetails->quantity = $quantity;
                        if ($item['type'] == self::TYPE_PERCENT) {
                            $modelDetails->price_discount_percent = $price_discount;
                            $modelDetails->price_discount_amount  = 0;
                        } else {
                            $modelDetails->price_discount_amount  = $price_discount;
                            $modelDetails->price_discount_percent = 0;
                        }
                        $modelDetails->save();
                    }
                }
            }
        }

        /**
         * @param      $contract_id
         * @param bool $dataProvider
         *
         * @return array|mixed|null
         */
        public static function getListDetailsByContractId($contract_id, $dataProvider = FALSE)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'contract_id=:contract_id';
            $criteria->params    = array(':contract_id' => $contract_id);

            if ($dataProvider) {
                return new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    )
                ));
            } else {
                return self::model()->findAll($criteria);
            }
        }

        /**
         * @param $contract_id
         *
         * @return array|mixed|null
         */
        public function getArrayDetailsByContractId($contract_id)
        {
            $results = array();
            $details = self::getListDetailsByContractId($contract_id);
            if ($details) {
                foreach ($details as $item) {
                    if ($item->price_discount_amount > 0) {
                        $price_discount = $item->price_discount_amount;
                        $type           = AFTContractsDetails::TYPE_VALUE;
                    } else {
                        $price_discount = ($item->price_discount_percent) ? $item->price_discount_percent : 0;
                        $type           = AFTContractsDetails::TYPE_PERCENT;
                    }
                    $data_item['quantity']       = $item->quantity;
                    $data_item['type']           = $type;
                    $data_item['price_discount'] = $price_discount;
                    $results[$item->item_id]     = $data_item;
                }
            }

            return $results;
        }

        /**
         * @param $item_id
         *
         * @return bool|string
         */
        public function getPackageNameById($item_id)
        {
            $package = AFTPackage::model()->find('id=:id', array(':id' => $item_id));
            if ($package) {
                return CHtml::encode($package->name);
            }

            return FALSE;
        }

        /**
         * @param $item_id
         *
         * @return bool|string
         */
        public function getPackagePriceById($item_id)
        {
            $package = AFTPackage::model()->find('id=:id', array(':id' => $item_id));
            if ($package) {
                return number_format(CHtml::encode($package->price), 0, '', '.') . 'đ';
            }

            return FALSE;
        }

        /**
         * @param $item_id
         * @param $quantity
         * @param $percent
         * @param $amount
         *
         * @return string
         */
        public function getAmountDetail($item_id, $quantity, $percent, $amount)
        {
            $total   = 0;
            $package = AFTPackage::model()->find('id=:id', array(':id' => $item_id));
            if ($package && $quantity) {
                if ($percent > 0) {
                    $total = (int)(($package->price - ($package->price * $percent / 100)) * $this->quantity);
                } else {
                    $total = (int)(($package->price - $amount) * $this->quantity);
                }
            }

            return number_format($total, 0, '', '.') . 'đ';
        }
    }
