<?php

    class WOrderState extends OrderState
    {
        const UNCONFIRMED = 0;
        const CONFIRMED   = 10;
        const UNPAID      = 0;
        const PAID        = 10;
        const UNDELIVERED = 0;
        const SHIPPING    = 1;
        const DELIVERED   = 10;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, create_date', 'required'),
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
                'id'          => 'ID',
                'order_id'    => 'Order',
                'confirm'     => 'Confirm',
                'paid'        => 'Paid',
                'delivered'   => 'Delivered',
                'create_date' => 'Create Date',
                'note'        => 'Note',
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
         * @return WOrderState the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getStatusOrder($order_id)
        {
            $criteria            = new CDbCriteria;
            $criteria->distinct  = TRUE;
            $criteria->alias     = 'os';
            $criteria->condition = 'order_id=:order_id';
            $criteria->params    = array(':order_id' => $order_id);
            $criteria->addCondition('os.id =
                                            (
                                               SELECT MAX(os2.id)
                                               FROM tbl_order_state as os2
                                               WHERE os2.order_id = "' . $order_id . '"
                                            )
                ');
            $criteria->limit = 1;
            $criteria->order = 'os.create_date DESC';
            $order_state     = self::model()->find($criteria);

            $status = '';
            if ($order_state) {
                if ($order_state->delivered == self::DELIVERED) {

                }
            }

            return $status;
        }

        /**
         * @return array
         */
        public function getArrayConfirm()
        {
            return array(
                self::UNCONFIRMED => Yii::t('web/portal', 'state_unconfirmed'),
                self::CONFIRMED   => Yii::t('web/portal', 'state_confirmed'),
            );
        }

        /**
         * @param $confirm
         *
         * @return mixed
         */
        public static function getConfirmLabel($confirm)
        {
            $array = self::getArrayConfirm();

            return (isset($array[$confirm])) ? $array[$confirm] : $confirm;
        }

        /**
         * @return array
         */
        public function getArrayPaid()
        {
            return array(
                self::PAID   => Yii::t('web/portal', 'state_paid'),
                self::UNPAID => Yii::t('web/portal', 'state_unpaid'),
            );
        }

        /**
         * @param $paid
         *
         * @return mixed
         */
        public static function getPaidLabel($paid)
        {
            $array = self::getArrayPaid();

            return (isset($array[$paid])) ? $array[$paid] : $paid;
        }

        /**
         * @return array
         */
        public function getArrayDelivered()
        {
            return array(
                self::DELIVERED   => Yii::t('web/portal', 'state_delivered'),
                self::SHIPPING    => Yii::t('web/portal', 'state_shipping'),
                self::UNDELIVERED => Yii::t('web/portal', 'state_undelivered'),
            );
        }

        /**
         * @param $delivered
         *
         * @return mixed
         */
        public static function getDeliveredLabel($delivered)
        {
            $array = self::getArrayDelivered();

            return (isset($array[$delivered])) ? $array[$delivered] : $delivered;
        }

        /**
         * @param WOrders     $modelOrder
         * @param WOrderState $order_state
         * @param string      $confirm
         * @param string      $paid
         * @param string      $delivered
         */
        public function setOrderState(WOrders $modelOrder, WOrderState &$order_state, $confirm = '', $paid = '', $delivered = '')
        {
            $order_state->order_id  = $modelOrder->id;
            $order_state->confirm   = $confirm;
            $order_state->paid      = $paid;
            $order_state->delivered = $delivered;
        }
    }
