<?php

    /**
     * This is the model class for table "{{order_state}}".
     *
     * The followings are the available columns in table '{{order_state}}':
     *
     * @property string  $id
     * @property string  $order_id
     * @property integer $confirm
     * @property string  $paid
     * @property string  $delivered
     * @property string  $create_date
     * @property string  $note
     */
    class OrderState extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{order_state}}';
        }

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
         * @return OrderState the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
