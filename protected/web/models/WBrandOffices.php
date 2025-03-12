<?php

    class WBrandOffices extends BrandOffices
    {
        const BRAND_OFFICE_ACTIVE = 1;

        public static function getBrandOfficesByWard($ward_code = '')
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'ward_code = :ward_code';
            $criteria->params    = array(':ward_code' => $ward_code);
            $results             = self::model()->find($criteria);

            return $results->code;
        }

        public static function getListBrandOfficesByDistrict($district_code = '')
        {
            $key = 'data_getListBrandOfficesByDistrict_' . $district_code;

            $return = Yii::app()->cache->get($key);
            if (!$return) {
                $criteria            = new CDbCriteria();
                $criteria->condition = 'district_code = :district_code AND status=:status';
                $criteria->params    = array(':district_code' => $district_code, ':status' => self::BRAND_OFFICE_ACTIVE);

                $criteria->addCondition("agency_id IS NULL OR agency_id = ''");

                $results = self::model()->findAll($criteria);
                $return  = CHtml::listData($results, 'id', 'name');
                Yii::app()->cache->set($key, $return, Yii::app()->params->cache_timeout_config['location']);
            }

            return $return;
        }
    }
