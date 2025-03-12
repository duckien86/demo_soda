<?php

    class APackagesNations extends PackagesNations
    {
        const PACKAGE_PREPAID  = 1;
        const PACKAGE_POSTPAID = 2;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('package_id, nation_code', 'length', 'max' => 255),
                array('package_id+nation_code+type', 'application.extensions.validators.uniqueMultiColumnValidator', 'caseSensitive' => TRUE),
                array('type', 'numerical', 'integerOnly' => TRUE),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, package_id, nation_code, type', 'safe', 'on' => 'search'),
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
                'package_id'  => Yii::t('adm/label', 'package_id'),
                'nation_code' => Yii::t('adm/label', 'package_nation_code'),
                'type'        => Yii::t('adm/label', 'type'),
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
            $criteria->compare('package_id', $this->package_id, TRUE);
            $criteria->compare('nation_code', $this->nation_code, TRUE);
            $criteria->compare('type', $this->type);

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
         * @return PackagesNations the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param        $package_id
         * @param        $list_nation
         * @param string $action
         */
        public static function insertPackageNations($package_id, $list_nation, $action = 'create')
        {
            if ($action == 'update') {
                APackagesNations::model()->deleteAll('package_id=:package_id', array(':package_id' => $package_id));
            }
            if ($list_nation) {
                foreach ($list_nation as $nation_code) {
                    $packages_nations              = new APackagesNations();
                    $packages_nations->package_id  = $package_id;
                    $packages_nations->nation_code = $nation_code;
                    $packages_nations->save();
                }
            }
        }
    }
