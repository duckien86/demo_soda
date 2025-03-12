<?php

    /**
     * This is the model class for table "{{location_vnptpay}}".
     *
     * The followings are the available columns in table '{{location_vnptpay}}':
     *
     * @property string $id
     * @property string $merchant_service_id
     * @property string $service_id
     * @property string $agency_id
     * @property string $secret_key
     * @property string $end_point
     */
    class ALocationVnptpay extends LocationVnptpay
    {
        public static $MERCHANT_SERVICE_ID  = '';
        public static $SERVICE_ID           = '';
        public static $AGENCY_ID            = '';
        public static $SECRET_KEY           = '';
        public static $END_POINT            = '';

        public $province;
        public $province_code;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('province_code', 'required'),
                array('id, merchant_service_id, service_id, agency_id, end_point', 'length', 'max' => 255),
                array('secret_key', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, merchant_service_id, service_id, agency_id, secret_key, end_point, province', 'safe', 'on' => 'search'),
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
                'id'                  => Yii::t('adm/label','id'),
                'merchant_service_id' => Yii::t('adm/label','merchant_service_id'),
                'service_id'          => Yii::t('adm/label','service_id'),
                'agency_id'           => Yii::t('adm/label','agency_id'),
                'secret_key'          => Yii::t('adm/label','secret_key'),
                'end_point'           => Yii::t('adm/label','end_point'),

                'province'            => Yii::t('adm/label','province'),
                'province_code'       => Yii::t('adm/label','province'),
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

            $criteria->compare('t.id', $this->id, TRUE);
            $criteria->compare('t.merchant_service_id', $this->merchant_service_id, TRUE);
            $criteria->compare('t.service_id', $this->service_id, TRUE);
            $criteria->compare('t.agency_id', $this->agency_id, TRUE);
            $criteria->compare('t.secret_key', $this->secret_key, TRUE);
            $criteria->compare('t.end_point', $this->end_point, TRUE);

            $tempCriteria = new CDbCriteria();
            $tempCriteria->join = 'LEFT JOIN tbl_province p ON t.id = p.code';
            $criteria->compare('p.name', $this->province, true);

            $criteria->mergeWith($tempCriteria, 'AND');

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
         * @return LocationVnptpay the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
            $this->id = $this->province_code;
//            if(empty($this->merchant_service_id)){
//                $this->merchant_service_id = self::$MERCHANT_SERVICE_ID;
//            }
//            if(empty($this->service_id)){
//                $this->service_id = self::$SERVICE_ID;
//            }
//            if(empty($this->agency_id)){
//                $this->agency_id = self::$AGENCY_ID;
//            }
//            if(empty($this->secret_key)){
//                $this->secret_key = self::$SECRET_KEY;
//            }
//            if(empty($this->end_point)){
//                $this->end_point = self::$END_POINT;
//            }
            return true;
        }
    }
