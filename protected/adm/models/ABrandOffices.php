<?php

    class ABrandOffices extends BrandOffices
    {
        CONST BRAND_OFFICE_ACTIVE   = 1;
        CONST BRAND_OFFICE_INACTIVE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name, address, province_code, district_code, ward_code, hotline', 'required'),
                array('head_office, status', 'numerical', 'integerOnly' => TRUE),
                array('name, address, ward_code, district_code, province_code, hotline, descriptions, agency_id', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, address, ward_code, district_code, province_code, hotline, descriptions, head_office, status, agency_id', 'safe', 'on' => 'search'),
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
                'id'            => Yii::t('adm/label', 'id'),
                'name'          => Yii::t('adm/label', 'name'),
                'address'       => Yii::t('adm/label', 'address'),
                'ward_code'     => Yii::t('adm/label', 'ward_code'),
                'district_code' => Yii::t('adm/label', 'district_code'),
                'province_code' => Yii::t('adm/label', 'province_code'),
                'hotline'       => Yii::t('adm/label', 'hotline'),
                'descriptions'  => Yii::t('adm/label', 'descriptions'),
                'head_office'   => Yii::t('adm/label', 'head_office'),
                'status'        => Yii::t('adm/label', 'status'),
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
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('hotline', $this->hotline, TRUE);
            $criteria->compare('descriptions', $this->descriptions, TRUE);
            $criteria->compare('head_office', $this->head_office);
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
         * @return ABrandOffices the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getListBrandOfficesByDistrict($district_code = '')
        {
            $key = 'data_getListBrandOfficesByDistrict_' . $district_code;

            $return = Yii::app()->cache->get($key);
            if (!$return) {
                $criteria            = new CDbCriteria();
                $criteria->condition = 'district_code = :district_code AND status=:status';
                $criteria->params    = array(':district_code' => $district_code, ':status' => self::BRAND_OFFICE_ACTIVE);

                $results = self::model()->findAll($criteria);
                $return  = CHtml::listData($results, 'id', 'name');
                Yii::app()->cache->set($key, $return, Yii::app()->params->cache_timeout_config['location']);
            }

            return $return;
        }

        public static function getBrandOfficeNameByCode($code)
        {
            $criteria = new CDbCriteria();
            $criteria->condition = "t.id = :code";
            $criteria->params = array(
                ':code' => $code
            );
            $cache_key = "ABrandOffices_getBrandOfficeNameByCode_$code";
            $result = Yii::app()->cache->get($cache_key);
            if(!$result){
                $model = ABrandOffices::model()->find($criteria);
                $result = ($model) ? $model->name : '';
            }
            return $result;
        }

    }
