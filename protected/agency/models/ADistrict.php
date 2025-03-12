<?php


    class ADistrict extends District
    {

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name', 'length', 'max' => 100),
                array('code', 'length', 'max' => 255),
                array('province_code', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, province_code', 'safe', 'on' => 'search'),
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
                'id'            => 'ID',
                'name'          => 'Name',
                'code'          => 'Code',
                'province_code' => 'Province Code',
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
            $criteria->compare('province_code', $this->province_code, TRUE);

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
         * @return ADistrict the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $province_code
         *
         * @return array
         */
        public static function getListDistrictByProvince($province_code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'province_code = :province_code';
            $criteria->params    = array(':province_code' => $province_code);

            $results = self::model()->findAll($criteria);
            return CHtml::listData($results, 'code', 'name');
        }

        /**
         * @param $code
         *
         * @return string
         */
        public function getDistrict($code)
        {
            $district = array();
            if ($code) {
                $district = ADistrict::model()->find('code=:code', array(':code' => $code));
            }

            return ($district) ? CHtml::encode($district->name) : $code;
        }

        /**
         * @param $code string
         *
         * @return string
         */
        public static function getDistrictNameByCode($code)
        {
            $cache_key = "ADistrict_getDistrictNameByCode_$code";
            $result = Yii::app()->cache->get($cache_key);

            if(!$result){
                $criteria            = new CDbCriteria();
                $criteria->condition = 't.code = :code';
                $criteria->params    = array(
                    ':code' => $code
                );

                $model = ADistrict::model()->find($criteria);
                $result = ($model) ? $model->name : '';
                Yii::app()->cache->set($cache_key, $result, 60*5);
            }
            return $result;
        }
    }
