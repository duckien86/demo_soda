<?php

    class WDistrict extends District
    {
        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WDistrict the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getListDistrictByProvince($province_code)
        {

            $key = 'data_province_getListDistrictByProvince_' . $province_code;

            $return = Yii::app()->cache->get($key);
            if (!$return) {
                $criteria            = new CDbCriteria();
                $criteria->condition = 'province_code = :province_code';
                $criteria->params    = array(':province_code' => $province_code);

                $results = self::model()->findAll($criteria);
                $return  = CHtml::listData($results, 'code', 'name');
                Yii::app()->cache->set($key, $return, Yii::app()->params->cache_timeout_config['location']);
            }

            return $return;
        }

        public static function getListDistrictByFiberProvinceId($fiber_province_id)
        {

            $key = 'data_province_getListDistrictByFiberProvinceId_' . $fiber_province_id;

            $return = Yii::app()->cache->get($key);
            if (!$return) {
                $criteria            = new CDbCriteria();
                $criteria->condition = 'province_code = :province_code';
                $criteria->params    = array(':province_code' => $fiber_province_id);

                $results = self::model()->findAll($criteria);
                $return  = CHtml::listData($results, 'code', 'name');
                Yii::app()->cache->set($key, $return, Yii::app()->params->cache_timeout_config['location']);
            }

            return $return;
        }

        public static function getFiberDistrictIdByDistrictCode($district_code){
            $criteria            = new CDbCriteria();
            $criteria->select = "t.fiber_district_id";
            $criteria->condition = "t.code = $district_code";
            $data = self::model()->findAll($criteria);
            return $data;
        }

        /**
         * @param $code string
         *
         * @return string
         */
        public static function getDistrictNameByCode($code, $cache = TRUE)
        {
            $cache_key = "backend_tbl_district_codes";
            if($cache){
                $result = Yii::app()->cache->get($cache_key);
            }else{
                $result = null;
            }
            if(!$result){
                $result   =  CHtml::listData(WDistrict::model()->findAll(), 'code', 'name');
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 24*60*60);
                }
            }
            return $result[$code];

        }
    }
