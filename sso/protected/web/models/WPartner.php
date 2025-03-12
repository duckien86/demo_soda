<?php

    /**
     * This is the model class for table "{{partner}}".
     *
     * The followings are the available columns in table '{{partner}}':
     *
     * @property integer $id
     * @property string  $description
     * @property string  $phone
     * @property string  $email
     * @property string  $created_at
     * @property integer $status
     * @property string  $cp_id
     * @property string  $aes_key
     * @property string  $return_url
     * @property string  $name
     */
    class WPartner extends Partner
    {
        /**
         * @return string the associated database table name
         */


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('description', 'length', 'max' => 500),
                array('phone', 'length', 'max' => 12),
                array('email, cp_id, name', 'length', 'max' => 255),
                array('aes_key', 'length', 'max' => 32),
                array('return_url', 'length', 'max' => 1000),
                array('created_at', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, description, phone, email, created_at, status, cp_id, aes_key, return_url, name', 'safe', 'on' => 'search'),
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
                'description' => 'Description',
                'phone'       => 'Phone',
                'email'       => 'Email',
                'created_at'  => 'Created At',
                'status'      => 'Status',
                'cp_id'       => 'Cp',
                'aes_key'     => 'Aes Key',
                'return_url'  => 'Return Url',
                'name'        => 'Name',
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

            $criteria->compare('id', $this->id);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('phone', $this->phone, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('created_at', $this->created_at, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('cp_id', $this->cp_id, TRUE);
            $criteria->compare('aes_key', $this->aes_key, TRUE);
            $criteria->compare('return_url', $this->return_url, TRUE);
            $criteria->compare('name', $this->name, TRUE);

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
         * @return Partner the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
