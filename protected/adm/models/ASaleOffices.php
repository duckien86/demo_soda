<?php

    class ASaleOffices extends SaleOffices
    {
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name, province_code, district_code, ward_code, code', 'required'),
                array('location_type', 'numerical', 'integerOnly' => TRUE),
                array('name, ward_code, district_code, province_code, code, agency_id', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, ward_code, district_code, province_code, code, location_type, agency_id', 'safe', 'on' => 'search'),
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
                'name'          => 'Tên phòng bán hàng',
                'ward_code'     => 'Mã phường xã',
                'district_code' => 'Mã quận huyện',
                'province_code' => 'Mã tỉnh',
                'code'          => 'Mã phòng bán hàng',
                'location_type' => 'Location Type',
                'agency_id'     => 'ĐTBL',
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
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('location_type', $this->location_type);

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
         * @return ASaleOffices the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tên phòng bán hàng theo code
         * @param $code string
         * @param $cache bool
         * @return string
         */
        public static function getSaleOfficesNameByCode($code, $cache = TRUE)
        {
            $result = '';
            $cache_key = "ASaleOffices_getSaleOfficesNameByCode_$code";
            if($cache){
                $result = Yii::app()->cache->get($cache_key);
            }
            if(!$result){

                $criteria = new CDbCriteria();
                $criteria->condition = "t.code = :code";
                $criteria->params = array(
                    ':code' => $code
                );

                $model = ASaleOffices::model()->find($criteria);
                $result = ($model) ? $model->name : '';
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 60*5);
                }
            }
            return $result;
        }
    }
