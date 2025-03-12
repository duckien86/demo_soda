<?php

    class WProvince extends Province
    {
        const PROVINCE_ACTIVE = 1;

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WProvince the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
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

        /*
         * List province fiber
         */
        public static function getListProvinceFiber($status = FALSE)
        {
            $criteria = new CDbCriteria();

            if ($status) {//check status
                $criteria->condition = 'status = :status';
                $criteria->params    = array(':status' => self::PROVINCE_ACTIVE);
            }
            $province = self::model()->findAll($criteria);

            return CHtml::listData($province, 'code', 'name');
        }

        public static function getListProvincePackage(){
            $criteria = new CDbCriteria();
            $criteria->select = "*";
            $criteria->condition = "code IN (SELECT DISTINCT province_code FROM tbl_packages_province)";
            $data = self::model()->findAll($criteria);
//            CVarDumper::dump($data,10,true);die();

            return $data;
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

        public static function getFiberProvinceIdByProvinceCode($province_code){
            $criteria            = new CDbCriteria();
            $criteria->select = "t.fiber_province_id";
            $criteria->condition = "t.code = $province_code";
            $data = self::model()->findAll($criteria);
            return $data;
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
                $result   =  CHtml::listData(WProvince::model()->findAll(), 'code', 'name');
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 24*60*60);
                }
            }
            return $result[$code];
        }
    }
