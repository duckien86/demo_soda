<?php

    /**
     * This is the model class for table "{{commission}}".
     *
     * The followings are the available columns in table '{{commission}}':
     *
     * @property string $id
     * @property string $order_id
     * @property string $item_id
     * @property string $item_type
     * @property string $create_date
     * @property string $commission_earned
     */
    class Commission extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{commission}}';
        }

        public $invitation;
        public $type;
        public $total_renueve;
        public $total_sim;
        public $total_package;
        public $create_date_sell;
        public $customer_name;
        public $msisdn;
        public $package_name;
        public $package_price;
        public $package_group;
        public $phone_contact;
        public $config_commision_code;
        public $total;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, item_id, item_type', 'length', 'max' => 255),
                array('commission_earned', 'length', 'max' => 10),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, item_id, item_type, create_date, commission_earned', 'safe', 'on' => 'search'),
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
                'id'                => 'ID',
                'order_id'          => 'Order',
                'item_id'           => 'Item',
                'item_type'         => 'Item Type',
                'create_date'       => 'Create Date',
                'commission_earned' => 'Commission Earned',
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
            $criteria->compare('item_id', $this->item_id, TRUE);
            $criteria->compare('item_type', $this->item_type, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('commission_earned', $this->commission_earned, TRUE);

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
         * @return Commission the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
