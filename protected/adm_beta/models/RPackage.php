<?php

    class RPackage extends Package
    {
        CONST PACKAGE_ACTIVE   = 1;
        CONST PACKAGE_INACTIVE = 0;

        const PACKAGE_PREPAID   = 1;
        const PACKAGE_POSTPAID  = 2;
        const PACKAGE_DATA      = 3;
        const PACKAGE_VAS       = 4;
        const PACKAGE_SIMKIT    = 5;
        const PACKAGE_REDEEM    = 6;
        const FLEXIBLE_CALL_INT = 7;
        const FLEXIBLE_CALL_EXT = 8;
        const FLEXIBLE_SMS_INT  = 9;
        const FLEXIBLE_SMS_EXT  = 10;
        const FLEXIBLE_DATA     = 11;

        const VIP_USER = 1;

        const PERIOD_1  = 1;
        const PERIOD_7  = 7;
        const PERIOD_30 = 30;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, name, code, type, short_description', 'required'),
                array('type, status, vip_user', 'numerical', 'integerOnly' => TRUE),
                array('id', 'length', 'max' => 100),
                array('name, code, short_description, thumbnail_1, thumbnail_2, thumbnail_3', 'length', 'max' => 255),
                array('price, price_discount, commission_rate_publisher, commission_rate_agency', 'length', 'max' => 10),
                array('period', 'length', 'max' => 11),
                array('extra_params', 'length', 'max' => 500),
                array('description', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, short_description, description, price, type, extra_params, status, thumbnail_1, thumbnail_2, thumbnail_3, point, sort_index, category_id, period, commission_rate_publisher, commission_rate_agency, home_display, vip_user', 'safe', 'on' => 'search'),
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
                'id'                => Yii::t('adm/label', 'id'),
                'name'              => Yii::t('adm/label', 'name'),
                'code'              => Yii::t('adm/label', 'package_code'),
                'short_description' => Yii::t('adm/label', 'short_description'),
                'description'       => Yii::t('adm/label', 'description'),
                'price'             => Yii::t('adm/label', 'price') . " (VNĐ)",
                'type'              => Yii::t('adm/label', 'package_type'),
                'extra_params'      => Yii::t('adm/label', 'extra_params'),
                'status'            => Yii::t('adm/label', 'status'),
                'vip_user'          => Yii::t('adm/label', 'vip_user'),
                'period'            => Yii::t('adm/label', 'period'),
                'price_discount'    => 'Số tiền khuyến mãi (VNĐ)',
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
            $criteria->compare('short_description', $this->short_description, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('type', $this->type);
            $criteria->compare('extra_params', $this->extra_params, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('vip_user', $this->vip_user);
            $criteria->compare('period', $this->period);

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
         * @return APackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function getAllPackageType()
        {
            return array(
                self::PACKAGE_PREPAID   => Yii::t('adm/label', 'package_prepaid'),
                self::PACKAGE_POSTPAID  => Yii::t('adm/label', 'package_postpaid'),
                self::PACKAGE_DATA      => Yii::t('adm/label', 'package_data'),
                self::PACKAGE_VAS       => Yii::t('adm/label', 'package_vas'),
                self::PACKAGE_SIMKIT    => Yii::t('adm/label', 'package_simkit'),
                self::PACKAGE_REDEEM    => Yii::t('adm/label', 'package_redeem'),
                self::FLEXIBLE_CALL_INT => Yii::t('adm/label', 'flexible_call_int'),
                self::FLEXIBLE_CALL_EXT => Yii::t('adm/label', 'flexible_call_ext'),
                self::FLEXIBLE_SMS_INT  => Yii::t('adm/label', 'flexible_sms_int'),
                self::FLEXIBLE_SMS_EXT  => Yii::t('adm/label', 'flexible_sms_ext'),
                self::FLEXIBLE_DATA     => Yii::t('adm/label', 'flexible_data'),
            );
        }

        /**
         * @param $type
         *
         * @return mixed
         */
        public function getPackageType($type)
        {
            $array_type = $this->getAllPackageType();

            return (isset($array_type[$type])) ? $array_type[$type] : $type;
        }

        /**
         * @return string
         */
        public function getStatusLabel()
        {
            return ($this->status == self::PACKAGE_ACTIVE) ? Yii::t('adm/label', 'active') : Yii::t('adm/label', 'inactive');
        }

        public static function getArrayPackagePeriod()
        {
            return array(
                self::PERIOD_1  => Yii::t('adm/label', 'package_day'),
                self::PERIOD_7  => Yii::t('adm/label', 'package_week'),
                self::PERIOD_30 => Yii::t('adm/label', 'package_month'),
            );
        }

        public function getPackagePeriodLabel($period)
        {
            $array_period = $this->getArrayPackagePeriod();

            return (isset($array_period[$period])) ? $array_period[$period] : $period;
        }
    }
