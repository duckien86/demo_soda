<?php

    class AProvince extends Province
    {
        CONST PROVINCE_ACTIVE   = 1;
        CONST PROVINCE_INACTIVE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('napas_id, vietinbank_id, status', 'numerical', 'integerOnly' => TRUE),
                array('name, office_name, office_address, vnp_province_id', 'length', 'max' => 255),
                array('code', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, office_name, office_address, napas_id, vietinbank_id, status, vnp_province_id', 'safe', 'on' => 'search'),
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
                'id'              => Yii::t('adm/label', 'id'),
                'name'            => Yii::t('adm/label', 'province'),
                'code'            => Yii::t('adm/label', 'code_number'),
                'office_name'     => Yii::t('adm/label', 'office_name'),
                'office_address'  => Yii::t('adm/label', 'office_address'),
                'napas_id'        => Yii::t('adm/label', 'napas_id'),
                'vietinbank_id'   => Yii::t('adm/label', 'vietinbank_id'),
                'status'          => Yii::t('adm/label', 'status'),
                'vnp_province_id' => Yii::t('adm/label', 'vnp_province_id'),
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
            $criteria->compare('office_name', $this->office_name, TRUE);
            $criteria->compare('office_address', $this->office_address, TRUE);
            $criteria->compare('napas_id', $this->napas_id);
            $criteria->compare('vietinbank_id', $this->vietinbank_id);
            $criteria->compare('status', $this->status);
            $criteria->compare('vnp_province_id', $this->vnp_province_id);

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
         * @return AProvince the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy danh sách các tỉnh thành chưa có tài khoản Vietinbank
         */
        public static function getAvailabilityProvinceForLocationVietinbank()
        {
            $criteria            = new CDbCriteria();
            $criteria->join      = 'LEFT JOIN tbl_location_vietinbank lv ON t.code = lv.id';
            $criteria->condition = 'lv.id is NULL OR lv.id = ""';

            return AProvince::model()->findAll($criteria);
        }

        /**
         * Lấy danh sách các tỉnh thành chưa có tài khoản Napas
         */
        public static function getAvailabilityProvinceForLocationNapas()
        {
            $criteria            = new CDbCriteria();
            $criteria->join      = 'LEFT JOIN tbl_location_napas ln ON t.code = ln.id';
            $criteria->condition = 'ln.id is NULL OR ln.id = ""';

            return AProvince::model()->findAll($criteria);
        }

        /**
         * Lấy danh sách các tỉnh thành chưa có tài khoản VNPT Pay
         */
        public static function getAvailabilityProvinceForLocationVNPTPay()
        {
            $criteria            = new CDbCriteria();
            $criteria->join      = 'LEFT JOIN tbl_location_vnptpay lv ON t.code = lv.id';
            $criteria->condition = 'lv.id is NULL OR lv.id = ""';

            return AProvince::model()->findAll($criteria);
        }



        /**
         * @param $code string
         * @param $cache bool
         *
         * @return string
         */
        public static function getProvinceNameByCode($code, $cache = TRUE)
        {
            $cache_key = "backend_tbl_province_codes";
            if($cache){
                $result = Yii::app()->cache->get($cache_key);
            }else{
                $result = null;
            }
            if(!$result){
                $result   =  CHtml::listData(AProvince::model()->findAll(), 'code', 'name');
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 24*60*60);
                }
            }
            return $result[$code];
        }
        /**
         * @return string
         */
        public static function getVnpProviceId($code)
        {
            $vnpCode = '';
            $criteria = new CDbCriteria();
            $criteria->condition = "t.code = :code";
            $criteria->params = array(
                ':code' => $code
            );
            $model = AProvince::model()->find($criteria);
            if($model){
                $vnpCode = $model->vnp_province_id;
            }
            return $vnpCode;
        }

        /**
         * @param $package_id
         *
         * @return array
         */
        public static function getListProvinceByPackageId($package_id)
        {
            $criteria            = new CDbCriteria();
            $criteria->distinct  = TRUE;
            $criteria->join      = ' INNER JOIN tbl_packages_province pp ON pp.province_code=t.code';
            $criteria->join      .= ' INNER JOIN tbl_package p ON p.id=pp.package_id';
            $condition           = 't.status=:status AND pp.package_id=:package_id';
            $params              = array(':status' => self::PROVINCE_ACTIVE, ':package_id' => $package_id);
            $criteria->condition = $condition;
            $criteria->params    = $params;
            $province            = self::model()->findAll($criteria);

            return CHtml::listData($province, 'code', 'name');
        }

        public static function getListProvince($status = FALSE)
        {
            $criteria = new CDbCriteria();

            if ($status) {//check status
                $criteria->condition = 'status = :status';
                $criteria->params    = array(':status' => self::PROVINCE_ACTIVE);
            }
            $province = self::model()->findAll($criteria);

            return CHtml::listData($province, 'code', 'name');
        }

        public static function getProvinceVnpByCode($code)
        {
            $province = Province::model()->find('vnp_province_id=:code', array(':code' => $code));
            return ($province) ? CHtml::encode($province->code) : $code;
        }

        /**
         * Lấy tất cả tỉnh
         */
        public function getAllProvinceVnpTourist()
        {
            $criteria = new CDbCriteria();

            if (!SUPER_ADMIN && !ADMIN && !USER_NOT_LOCATE) {

                if (isset(Yii::app()->user->province_code) && Yii::app()->user->province_code != "") {
                    $criteria->condition = "code = '" . Yii::app()->user->province_code . "'";
                } else {
                    $criteria->condition = "code =''";
                }
            }
            $criteria->addCondition("t.vnp_province_id IS NOT NULL AND TRIM(t.vnp_province_id) != '' AND t.code != '99'");
            $data = Province::model()->findAll($criteria);

            return CHtml::listData($data, 'vnp_province_id', 'name');
        }
    }
