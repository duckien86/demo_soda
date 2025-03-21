<?php

    /**
     * This is the model class for table "{{order_details}}".
     *
     * The followings are the available columns in table '{{order_details}}':
     *
     * @property string  $order_id
     * @property string  $item_id
     * @property string  $item_name
     * @property integer $quantity
     * @property string  $price
     * @property string  $type
     * @property integer $status
     */
    class OrderDetails extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{order_details}}';
        }

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
                'order_id'  => 'Order',
                'item_id'   => 'Item',
                'item_name' => 'Item Name',
                'quantity'  => 'Quantity',
                'price'     => 'Price',
                'type'      => 'Type',
                'status'    => 'Status',
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
         * @return OrderDetails the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
