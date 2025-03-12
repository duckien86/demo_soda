<?php

    class ATokenLinks extends TokenLinks
    {
        const STATUS_SUCCESS = 10;
        const SEND_SMS       = 'sms';
        const SEND_EMAIL     = 'email';
        const SEND_OTHER     = 'other';

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, order_id, customer_msisdn, pre_order_msisdn, link', 'required'),
                array('id, order_id, customer_msisdn, customer_email, pre_order_msisdn, create_by, status', 'length', 'max' => 255),
                array('send_link_method', 'length', 'max' => 5),
                array('link', 'length', 'max' => 500),
                array('create_date, last_update', 'safe'),
                array('customer_msisdn', 'msisdn_validation'),
                array('customer_email', 'email'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, customer_msisdn, customer_email, pre_order_msisdn, send_link_method, link, create_by, create_date, last_update, status', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return bool
         */
        public function msisdn_validation()
        {
            if ($this->customer_msisdn) {
                $input = $this->customer_msisdn;
                if (preg_match("/^0[0-9]{9,10}$/i", $input) == TRUE || preg_match("/^84[0-9]{9,11}$/i", $input) == TRUE) {
                    return TRUE;
                } else {
                    $this->addError('customer_msisdn', Yii::t('cskh/label', 'msisdn_validation'));
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
                'id'               => Yii::t('cskh/label', 'id'),
                'order_id'         => Yii::t('cskh/label', 'order_id'),
                'customer_msisdn'  => Yii::t('cskh/label', 'customer_msisdn'),
                'customer_email'   => Yii::t('cskh/label', 'customer_email'),
                'pre_order_msisdn' => Yii::t('cskh/label', 'pre_order_msisdn'),
                'send_link_method' => Yii::t('cskh/label', 'lbl_send_link_method'),
                'link'             => Yii::t('cskh/label', 'link'),
                'create_by'        => Yii::t('cskh/label', 'create_by'),
                'create_date'      => Yii::t('cskh/label', 'create_date'),
                'last_update'      => Yii::t('cskh/label', 'last_update'),
                'status'           => Yii::t('cskh/label', 'status'),
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

            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.order_id', $this->order_id, TRUE);
            $criteria->compare('t.customer_msisdn', $this->customer_msisdn, TRUE);
            $criteria->compare('t.customer_email', $this->customer_email, TRUE);
            $criteria->compare('t.pre_order_msisdn', $this->pre_order_msisdn, TRUE);
            $criteria->compare('t.send_link_method', $this->send_link_method, TRUE);
            $criteria->compare('t.link', $this->link, TRUE);
            $criteria->compare('t.create_by', $this->create_by, TRUE);
            $criteria->compare("DATE_FORMAT(t.create_date, '%d/%m/%Y')", $this->create_date);
            $criteria->compare("DATE_FORMAT(t.last_update, '%d/%m/%Y')", $this->last_update);
            $criteria->compare('t.status', $this->status, TRUE);

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
         * @return TokenLinks the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
            if ($this->isNewRecord) {
                $this->create_date = date('Y-m-d H:i:s', time());
                $this->create_by   = Yii::app()->user->id;
            }
            $this->last_update = date('Y-m-d H:i:s', time());

            return TRUE;
        }

        public function getAllStatus()
        {
            return array(
                self::STATUS_SUCCESS => Yii::t('cskh/label', 'send_success'),
            );
        }

        /**
         * @param $status
         *
         * @return mixed
         */
        public function getStatusLabel($status)
        {
            $array_status = $this->getAllStatus();

            return isset($array_status[$status]) ? $array_status[$status] : $status;
        }

        /**
         * @return array
         */
        public function getArrayMethod()
        {
            $method = array(
                self::SEND_SMS   => Yii::t('cskh/label', 'sms'),
                self::SEND_EMAIL => Yii::t('cskh/label', 'email'),
                self::SEND_OTHER => Yii::t('cskh/label', 'other'),
            );
            if (empty($this->customer_email)) {
                unset($method[self::SEND_EMAIL]);
            }

            return $method;
        }
    }
