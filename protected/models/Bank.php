<?php

    /**
     * This is the model class for table "{{bank}}".
     *
     * The followings are the available columns in table '{{bank}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $code
     * @property string  $logo
     * @property string  $description
     * @property string  $payment_gateway
     * @property integer $status
     */
    class Bank extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{bank}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('name, code, logo', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                array('payment_gateway', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, logo, description, payment_gateway, status', 'safe', 'on' => 'search'),
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
                'id'              => 'ID',
                'name'            => 'Name',
                'code'            => 'Code',
                'logo'            => 'Logo',
                'description'     => 'Description',
                'payment_gateway' => 'Payment Gateway',
                'status'          => 'Status',
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
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('logo', $this->logo, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('payment_gateway', $this->payment_gateway, TRUE);
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
         * @return Bank the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
