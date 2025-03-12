<?php

    class AWard extends Ward
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
                array('code, district_code, province_code', 'length', 'max' => 255),
                array('type, location, districtid', 'length', 'max' => 30),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, type, location, districtid, district_code, province_code', 'safe', 'on' => 'search'),
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
                'type'          => 'Type',
                'location'      => 'Location',
                'districtid'    => 'Districtid',
                'district_code' => 'District Code',
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
            $criteria->compare('type', $this->type, TRUE);
            $criteria->compare('location', $this->location, TRUE);
            $criteria->compare('districtid', $this->districtid, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
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
         * @return AWard the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getListWardDistrict($district_code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'district_code = :district_code';
            $criteria->params    = array(':district_code' => $district_code);

            $results = self::model()->findAll($criteria);

            return CHtml::listData($results, 'id', 'name');
        }

        /**
         * @param $code string
         *
         * @return string
         */
        public static function getWardNameByCode($code, $cache = TRUE)
        {
            $cache_key = "backend_tbl_ward_codes";
            if($cache){
                $result = Yii::app()->cache->get($cache_key);
            }else{
                $result = null;
            }
            if(!$result){
                $result   =  CHtml::listData(AWard::model()->findAll(), 'code', 'name');
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 24*60*60);
                }
            }
            return $result[$code];
        }
    }
