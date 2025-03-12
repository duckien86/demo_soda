<?php

    class CskhTraffic extends Orders
    {
        public $status;
        public $item_id;
        public $ward_code;
        public $shipper_name;
        public $detail_id;
        public $start_date;
        public $end_date;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('start_date, end_date', 'required', 'on' => 'admin'),
                array('id, shipper_id, delivery_type, payment_method, district_code, province_code, ,ward_code', 'length', 'max' => 100),
                array('sso_id, promo_code, invitation, full_name, address_detail, otp, affiliate_transaction_id, affiliate_source', 'length', 'max' => 255),
                array('phone_contact', 'length', 'max' => 20),
                array('customer_note', 'length', 'max' => 500),
                array('last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, promo_code, invitation, create_date, last_update, shipper_id,ward_code, delivery_type, payment_method, full_name, district_code, province_code, address_detail, phone_contact, customer_note, otp, status, item_id, affiliate_transaction_id, affiliate_source', 'safe', 'on' => 'search'),
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
                'id'                       => Yii::t('adm/label', 'order_id'),
                'sso_id'                   => Yii::t('adm/label', 'sso_id'),
                'promo_code'               => Yii::t('adm/label', 'promo_code'),
                'invitation'               => Yii::t('adm/label', 'invitation'),
                'create_date'              => Yii::t('adm/label', 'create_date'),
                'last_update'              => Yii::t('adm/label', 'last_update'),
                'shipper_id'               => Yii::t('adm/label', 'shipper_id'),
                'delivery_type'            => Yii::t('adm/label', 'delivery_type'),
                'payment_method'           => Yii::t('adm/label', 'payment_method'),
                'personal_id'              => Yii::t('adm/label', 'personal_id'),
                'full_name'                => Yii::t('adm/label', 'full_name'),
                'birthday'                 => Yii::t('adm/label', 'birthday'),
                'district_code'            => Yii::t('adm/label', 'district'),
                'province_code'            => Yii::t('adm/label', 'province'),
                'address_detail'           => Yii::t('adm/label', 'address_detail'),
                'phone_contact'            => Yii::t('adm/label', 'phone_contact'),
                'customer_note'            => Yii::t('adm/label', 'customer_note'),
                'status'                   => Yii::t('adm/label', 'status'),
                'item_id'                  => Yii::t('adm/label', 'item_id'),
                'otp'                      => Yii::t('adm/label', 'otp'),
                'affiliate_transaction_id' => Yii::t('adm/label', 'affiliate_transaction_id'),
                'affiliate_source'         => Yii::t('adm/label', 'affiliate_source'),
                'start_date'               => "Ngày bắt đầu",
                'end_date'                 => "Ngày kết thúc",
                'ward_code'                => "Phường xã",
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

            $criteria       = new CDbCriteria;
            $criteria->with = array('detail');

            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.sso_id', $this->sso_id, TRUE);
            $criteria->compare('t.promo_code', $this->promo_code, TRUE);
            $criteria->compare('t.invitation', $this->invitation, TRUE);
            $criteria->compare("DATE_FORMAT(t.create_date, '%d/%m/%Y')", $this->create_date);
            $criteria->compare("DATE_FORMAT(t.last_update, '%d/%m/%Y')", $this->last_update);
            $criteria->compare('t.shipper_id', $this->shipper_id, TRUE);
            $criteria->compare('t.delivery_type', $this->delivery_type, TRUE);
            $criteria->compare('t.payment_method', $this->payment_method, TRUE);
            $criteria->compare('t.full_name', $this->full_name, TRUE);
            if (SUPER_ADMIN || ADMIN) {
                $criteria->compare('t.district_code', $this->district_code, TRUE);
                $criteria->compare('t.province_code', $this->province_code, TRUE);
                $criteria->compare('t.ward_code', $this->ward_code, TRUE);
            } else {
                if (Yii::app()->user->id) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user->province_code && $user->province_code != '') {
                        $criteria->compare('t.province_code', $user->province_code, TRUE);
                    }
                    if ($user->district_code && $user->district_code != '') {
                        $criteria->compare('t.district_code', $user->district_code, TRUE);
                    }
                    if ($user->ward_code && $user->ward_code != '') {
                        $criteria->compare('t.ward_code', $user->ward_code, TRUE);
                    }
                }
            }
            $criteria->compare('t.address_detail', $this->address_detail, TRUE);
            $criteria->compare('t.phone_contact', $this->phone_contact, TRUE);
            $criteria->compare('t.customer_note', $this->customer_note, TRUE);
            $criteria->compare('t.otp', $this->otp, TRUE);
            $criteria->compare('affiliate_transaction_id', $this->affiliate_transaction_id, TRUE);
            $criteria->compare('affiliate_source', $this->affiliate_source, TRUE);
            $criteria->join = "LEFT JOIN tbl_order_state os ON os.order_id = t.id";
            $criteria->addCondition("os.confirm =10");

            if ($this->start_date && $this->end_date) {
                $criteria->addCondition("t.create_date >='$this->start_date' and t.create_date <='$this->end_date'");

                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date DESC',
                    ),
                    'pagination' => array(
                        'params'   => array(
                            "CskhTraffic[start_date]"    => $this->start_date,
                            "CskhTraffic[end_date]"      => $this->end_date,
                            "CskhTraffic[province_code]" => $this->province_code,
                            "CskhTraffic[district_code]" => $this->district_code,
                            "CskhTraffic[ward_code]"     => $this->ward_code,
                        ),
                        'pageSize' => 10,
                    ),
                ));
            } else {
                return new CActiveDataProvider($this, array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.create_date DESC',
                    ),
                    'pagination' => array(
                        'pageSize' => 10,
                    ),
                ));
            }


        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AOrders the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function afterFind()
        {
            if (!$this->shipper_name) {
                $data = ShipperOrder::model()->findByAttributes(array('order_id' => $this->id));
                if ($data) {
                    $data_shipper = Shipper::model()->findByAttributes(array('id' => $data->shipper_id));
                    if ($data_shipper) {
                        $this->shipper_name = $data_shipper->full_name;
                    }

                }
            }
            parent::afterFind(); // TODO: Change the autogenerated stub
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

        /**
         * @param $shipper_id
         *
         * @return string
         */
        public function getShipperName($shipper_id)
        {
            $shipper = array();
            if ($shipper_id) {
                $shipper = CskhShipper::model()->find('id=:id', array(':id' => $shipper_id));
            }

            return ($shipper) ? CHtml::encode($shipper->full_name) : $shipper_id;
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
         * Lấy tất cả phường xã
         */
        public function getAllBrandOffice()
        {
            $data = BrandOffices::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * @param $code
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
         * Lấy hình giao dịch.
         */
        public function getDeliveryType($type)
        {
            $array = array(
                1 => 'tại nhà',
                2 => 'tai phong ban hang',
            );

            return $array[isset($type) ? $type : 1];
        }

        public function getShipper($id)
        {
            $result  = "Chưa được phân";
            $shipper = Shipper::model()->findByAttributes(array('id' => $id));
            if ($shipper) {
                return $shipper->username;
            }

            return $result;
        }
    }
