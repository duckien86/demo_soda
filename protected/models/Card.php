<?php

    /**
     * This is the model class for table "{{card}}".
     *
     * The followings are the available columns in table '{{card}}':
     *
     * @property string $id
     * @property string $serial_number
     * @property string $pin_number
     * @property string $price
     * @property string $price_discount
     * @property string $exp_date
     */
    class Card extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{card}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('serial_number, pin_number', 'length', 'max' => 255),
                array('price, price_discount', 'length', 'max' => 10),
                array('exp_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, serial_number, pin_number, price, price_discount, exp_date', 'safe', 'on' => 'search'),
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
                'id'             => 'ID',
                'serial_number'  => 'Serial Number',
                'pin_number'     => 'Pin Number',
                'price'          => 'Price',
                'price_discount' => 'Price Discount',
                'exp_date'       => 'Exp Date',
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
            $criteria->compare('serial_number', $this->serial_number, TRUE);
            $criteria->compare('pin_number', $this->pin_number, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('price_discount', $this->price_discount, TRUE);
            $criteria->compare('exp_date', $this->exp_date, TRUE);

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
         * @return Card the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
