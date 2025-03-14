<?php

    class ASubscriptionType extends SubscriptionType
    {
        CONST POSTPAID = 'postpaid';
        CONST PREPAID  = 'prepaid';

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name, type, description', 'required'),
                array('name, type', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, type, description', 'safe', 'on' => 'search'),
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
                'id'          => Yii::t('adm/label', 'id'),
                'name'        => Yii::t('adm/label', 'name'),
                'type'        => Yii::t('adm/label', 'subscription_type'),
                'description' => Yii::t('adm/label', 'description'),
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
            $criteria->compare('type', $this->type, TRUE);
            $criteria->compare('description', $this->description, TRUE);

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
         * @return ASubscriptionType the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @return array
         */
        public function getAllSubscriptionType()
        {
            return array(
                self::POSTPAID => Yii::t('adm/label', 'postpaid'),
                self::PREPAID  => Yii::t('adm/label', 'prepaid'),
            );
        }

        /**
         * @param $type
         *
         * @return mixed
         */
        public function getSubscriptionType($type)
        {
            $array_type = $this->getAllSubscriptionType();

            return (isset($array_type[$type])) ? $array_type[$type] : $type;
        }
    }
