<?php

    /**
     * This is the model class for table "{{token_links}}".
     *
     * The followings are the available columns in table '{{token_links}}':
     *
     * @property string $id
     * @property string $order_id
     * @property string $customer_msisdn
     * @property string $customer_email
     * @property string $pre_order_msisdn
     * @property string $send_link_method
     * @property string $link
     * @property string $create_by
     * @property string $create_date
     * @property string $last_update
     * @property string $status
     */
    class TokenLinks extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{token_links}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id', 'required'),
                array('id, order_id, customer_msisdn, customer_email, pre_order_msisdn, create_by, status', 'length', 'max' => 255),
                array('send_link_method', 'length', 'max' => 5),
                array('link', 'length', 'max' => 500),
                array('create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, customer_msisdn, customer_email, pre_order_msisdn, send_link_method, link, create_by, create_date, last_update, status', 'safe', 'on' => 'search'),
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
                'id'               => 'ID',
                'order_id'         => 'Order',
                'customer_msisdn'  => 'Customer Msisdn',
                'customer_email'   => 'Customer Email',
                'pre_order_msisdn' => 'Pre Order Msisdn',
                'send_link_method' => 'Send Link Method',
                'link'             => 'Link',
                'create_by'        => 'Create By',
                'create_date'      => 'Create Date',
                'last_update'      => 'Last Update',
                'status'           => 'Status',
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
            $criteria->compare('customer_msisdn', $this->customer_msisdn, TRUE);
            $criteria->compare('customer_email', $this->customer_email, TRUE);
            $criteria->compare('pre_order_msisdn', $this->pre_order_msisdn, TRUE);
            $criteria->compare('send_link_method', $this->send_link_method, TRUE);
            $criteria->compare('link', $this->link, TRUE);
            $criteria->compare('create_by', $this->create_by, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('status', $this->status, TRUE);

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
    }
