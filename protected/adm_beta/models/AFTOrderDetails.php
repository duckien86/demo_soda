<?php


    class AFTOrderDetails extends FTOrderDetails
    {

        public $total;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, item_id', 'required'),
                array('order_id, item_id, quantity', 'numerical', 'integerOnly' => TRUE),
                array('price', 'length', 'max' => 10),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('order_id, item_id, quantity, price', 'safe', 'on' => 'search'),
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
                'order_id' => 'Mã đơn hàng',
                'item_id'  => 'Tên sản phẩm',
                'quantity' => 'Số lượng',
                'price'    => 'Đơn giá',
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
        public function search($order_id)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('order_id', $order_id);
            $criteria->compare('item_id', $this->item_id);
            $criteria->compare('quantity', $this->quantity);
            $criteria->compare('price', $this->price, TRUE);

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
         * @return AFTOrderDetails the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
