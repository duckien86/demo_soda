<?php

    /**
     * This is the model class for table "{{payment_method}}".
     *
     * The followings are the available columns in table '{{payment_method}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $logo
     * @property string  $description
     * @property string  $config_param
     * @property integer $status
     */
    class PaymentMethod extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{payment_method}}';
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
                array('name', 'length', 'max' => 50),
                array('logo', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                array('config_param', 'length', 'max' => 2000),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, logo, description, config_param, status', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'name'         => 'Name',
                'logo'         => 'Logo',
                'description'  => 'Description',
                'config_param' => 'Config Param',
                'status'       => 'Status',
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
            $criteria->compare('logo', $this->logo, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('config_param', $this->config_param, TRUE);
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
         * @return PaymentMethod the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
