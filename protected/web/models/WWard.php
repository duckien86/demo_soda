<?php

    class WWard extends Ward
    {
        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WWard the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public static function getListWardDistrict($district_code)
        {
            $key = 'data_getListWardDistrict_' . $district_code;

            $return = Yii::app()->cache->get($key);
            if (!$return) {
                $criteria            = new CDbCriteria();
                $criteria->join      = ' INNER JOIN tbl_sale_offices s ON s.ward_code=t.code';
                $criteria->condition = 't.district_code = :district_code';
                $criteria->params    = array(':district_code' => $district_code);
                $results             = self::model()->findAll($criteria);
                $return              = CHtml::listData($results, 'code', 'name');
                Yii::app()->cache->set($key, $return, Yii::app()->params->cache_timeout_config['location']);
            }

            return $return;

        }
        /**
         * @param $code string
         *
         * @return string
         */
        public static function getWardNameByCode($code, $cache = TRUE)
        {
            $cache_key = "backend_tbl_ward_codes";
            if($cache){
                $result = Yii::app()->cache->get($cache_key);
            }else{
                $result = null;
            }
            if(!$result){
                $result   =  CHtml::listData(WWard::model()->findAll(), 'code', 'name');
                if($cache){
                    Yii::app()->cache->set($cache_key, $result, 24*60*60);
                }
            }
            return $result[$code];
        }
    }
