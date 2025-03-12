<?php

    class WNations extends Nations
    {
        CONST NATION_ACTIVE   = 1;
        CONST NATION_INACTIVE = 0;

        CONST ASIA       = 'asia';
        CONST EUROPE     = 'europe';
        CONST OCEANIA    = 'oceania';
        CONST AMERICA    = 'america';
        CONST AFRICA     = 'africa';
        CONST ANTARCTICA = 'antarctica';

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
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'             => Yii::t('web/portal', 'id'),
                'name'           => Yii::t('web/portal', 'nation_name'),
                'code'           => Yii::t('web/portal', 'nation_code'),
                'info'           => Yii::t('web/portal', 'nation_info'),
                'continent'      => Yii::t('web/portal', 'continent'),
                'status'         => Yii::t('web/portal', 'status'),
                'telco_prepaid'  => Yii::t('web/portal', 'telco_prepaid'),
                'telco_postpaid' => Yii::t('web/portal', 'telco_postpaid'),
            );
        }

        /**
         * @return array
         */
        public function arrayContinent()
        {
            return array(
                self::ASIA       => Yii::t('web/portal', 'asia'),
                self::AFRICA     => Yii::t('web/portal', 'africa'),
                self::AMERICA    => Yii::t('web/portal', 'america'),
                self::ANTARCTICA => Yii::t('web/portal', 'antarctica'),
                self::EUROPE     => Yii::t('web/portal', 'europe'),
                self::OCEANIA    => Yii::t('web/portal', 'oceania'),
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

        public static function getListNationByPackageId($package_id, $type, $dataProvider = TRUE, $limit = 0, $offset = 0)
        {
            $criteria = new CDbCriteria();
            $criteria->distinct  = TRUE;
            $criteria->join      = ' INNER JOIN tbl_packages_nations pn ON pn.nation_code=t.code';
            $criteria->join      .= ' INNER JOIN tbl_package p ON p.id=pn.package_id';
            $condition           = 't.status=:status AND pn.package_id=:package_id AND pn.type=:type';
            $params              = array(':status' => self::NATION_ACTIVE, ':package_id' => $package_id, ':type' => $type);
            $criteria->condition = $condition;
            $criteria->params    = $params;

            if ($limit) {
                $criteria->limit = $limit;
            }
            if ($offset) {
                $criteria->offset = $offset;
            }
            $criteria->order = 't.name';
            if ($dataProvider) {
//                $cache_key = 'getListPackageRoaming_DataProvider_' . $type . $limit . $offset;
//                $results   = Yii::app()->cache->get($cache_key);
//                if (!$results) {
                $results = new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.name',
                    ),
                    'pagination' => array(
                        'pageSize' => 100,
                    )
                ));
//                    Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
//                }
            } else {
//                $cache_key = 'getListPackageRoaming_' . $type . $limit . $offset;
//                $results   = Yii::app()->cache->get($cache_key);
//                if (!$results) {
                $results = self::model()->findAll($criteria);
//                    Yii::app()->cache->set($cache_key, $results, Yii::app()->params->cache_timeout_config['package']);
//                }
            }

            return $results;
        }
    }
