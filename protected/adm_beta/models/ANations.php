<?php

    class ANations extends Nations
    {
        CONST NATION_ACTIVE   = 1;
        CONST NATION_INACTIVE = 0;

        CONST ASIA       = 'asia';
        CONST EUROPE     = 'europe';
        CONST OCEANIA    = 'oceania';
        CONST AMERICA    = 'america';
        CONST AFRICA     = 'africa';
        CONST ANTARCTICA = 'antarctica';

        public $package_id;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name, code, status', 'required'),
                array('code', 'unique'),
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('name, code', 'length', 'max' => 255),
                array('continent', 'length', 'max' => 10),
                array('info, telco_prepaid, telco_postpaid', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, info, continent, status, telco_prepaid, telco_postpaid', 'safe', 'on' => 'search'),
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
                'id'             => Yii::t('adm/label', 'id'),
                'name'           => Yii::t('adm/label', 'name'),
                'code'           => Yii::t('adm/label', 'nation_code'),
                'info'           => Yii::t('adm/label', 'nation_info'),
                'continent'      => Yii::t('adm/label', 'continent'),
                'status'         => Yii::t('adm/label', 'status'),
                'telco_prepaid'  => Yii::t('adm/label', 'telco_prepaid'),
                'telco_postpaid' => Yii::t('adm/label', 'telco_postpaid'),
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
            $criteria->compare('info', $this->info, TRUE);
            $criteria->compare('continent', $this->continent, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('telco_prepaid', $this->telco_prepaid, TRUE);
            $criteria->compare('telco_postpaid', $this->telco_postpaid, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria, 'sort' => array(
                    'defaultOrder' => 't.name',
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return Nations the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * array nations (code, name)
         *
         * @return array
         */
        public static function getArrayNations()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'status=:status';
            $criteria->params    = array(':status' => self::NATION_ACTIVE);
            $nations             = self::model()->findAll($criteria);

            return CHtml::listData($nations, 'code', 'name');
        }

        /**
         * @return array
         */
        public function arrayContinent()
        {
            return array(
                self::ASIA       => Yii::t('adm/label', 'asia'),
                self::AFRICA     => Yii::t('adm/label', 'africa'),
                self::AMERICA    => Yii::t('adm/label', 'america'),
                self::ANTARCTICA => Yii::t('adm/label', 'antarctica'),
                self::EUROPE     => Yii::t('adm/label', 'europe'),
                self::OCEANIA    => Yii::t('adm/label', 'oceania'),
            );
        }

        /**
         * @param $continent
         *
         * @return mixed
         */
        public function getContinentLabel($continent)
        {
            $array = $this->arrayContinent();

            return (isset($array[$continent])) ? $array[$continent] : $continent;
        }

        public static function listNation($package_id)
        {
            $criteria            = new CDbCriteria;
            $criteria->condition = 't.status=:status';
            $criteria->params    = array(':status' => self::NATION_ACTIVE);
            $nations             = self::model()->findAll($criteria);

            if ($nations) {
                foreach ($nations as $key => $item) {
                    $item['package_id'] = $package_id;//add package_id->item: display grid view
                }
            }

            return new CArrayDataProvider($nations, array(
                'keyField'   => FALSE,
                'pagination' => array(
                    'pageSize' => 100,
                ),
            ));
//            return new CActiveDataProvider(self::model(), array(
//                'criteria'   => $criteria,
//                'sort'       => array(
//                    'defaultOrder' => 't.name',
//                ),
//                'pagination' => array(
//                    'pageSize' => 50,
//                )
//            ));
        }

        public function checkActive($package_id, $nation_code, $type)
        {
            $package_nation = APackagesNations::model()->find('nation_code=:nation_code AND package_id=:package_id AND type=:type',
                array(
                    ':nation_code' => $nation_code,
                    ':package_id'  => $package_id,
                    ':type'        => $type,
                ));
            if ($package_nation) {
                return TRUE;
            }

            return FALSE;
        }
    }
