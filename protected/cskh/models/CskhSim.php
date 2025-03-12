<?php

    class CskhSim extends Sim
    {
        CONST TYPE_PREPAID  = 1;
        CONST TYPE_POSTPAID = 2;

        public $term;
        public $price_term;
        public $raw_data;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('msisdn, price, type, full_name, store_id', 'required'),
                array('type, status', 'numerical', 'integerOnly' => TRUE),
                array('id, personal_id', 'length', 'max' => 100),
                array('serial_number, msisdn, short_description, description, personal_id_create_place, full_name', 'length', 'max' => 255),
                array('price, store_id', 'length', 'max' => 10),
                array('personal_id', 'checkFormat'),
                array('birthday', 'checkMaxBirthday'),
                array('personal_id_create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, serial_number, msisdn, short_description, description, price, type, personal_id, personal_id_create_date, personal_id_create_place, full_name, birthday, status, store_id', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkFormat($attribute, $params)
        {
            $pattern = '/^([0-9]{9}|[0-9]{12})$/';
            if ($this->$attribute && !preg_match($pattern, $this->$attribute)) {
                $this->addError($attribute, Yii::t('web/portal', 'format_personal_id'));
            }
        }

        /**
         * @param $attribute
         * @param $params
         */
        public function checkMaxBirthday($attribute, $params)
        {
            if ($this->$attribute) {
                $value    = date('Y', strtotime(str_replace('/', '-', $this->$attribute)));
                $max_year = date('Y') - 15;
                if ($value > $max_year) {
                    $this->addError($attribute, Yii::t('web/portal', 'error_max_birthday'));
                }
            }
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
                'id'                       => Yii::t('web/portal', 'id'),
                'serial_number'            => Yii::t('web/portal', 'serial_number'),
                'msisdn'                   => Yii::t('web/portal', 'msisdn'),
                'short_description'        => Yii::t('web/portal', 'short_description'),
                'description'              => Yii::t('web/portal', 'description'),
                'price'                    => Yii::t('web/portal', 'price'),
                'type'                     => Yii::t('web/portal', 'type'),
                'personal_id'              => Yii::t('web/portal', 'personal_id'),
                'personal_id_create_date'  => Yii::t('web/portal', 'personal_id_create_date'),
                'personal_id_create_place' => Yii::t('web/portal', 'personal_id_create_place'),
                'full_name'                => Yii::t('web/portal', 'full_name'),
                'birthday'                 => Yii::t('web/portal', 'birthday'),
                'status'                   => Yii::t('web/portal', 'status'),
                'store_id'                 => Yii::t('web/portal', 'store_id'),
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
            $criteria->compare('msisdn', $this->msisdn, TRUE);
            $criteria->compare('short_description', $this->short_description, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('personal_id', $this->personal_id, TRUE);
            $criteria->compare('personal_id_create_date', $this->personal_id_create_date, TRUE);
            $criteria->compare('personal_id_create_place', $this->personal_id_create_place, TRUE);
            $criteria->compare('full_name', $this->full_name, TRUE);
            $criteria->compare('birthday', $this->birthday, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('store_id', $this->store_id, TRUE);

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
         * @return WSim the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getTypeSim($order_id)
        {
            if ($order_id) {
                $sim = CskhSim::model()->findByAttributes(array('order_id' => $order_id));
                if ($sim) {
                    if ($sim->type == 1) {
                        return "Trả trước";
                    } else {
                        return "Trả sau";
                    }

                }
            }

            return "";
        }
    }
