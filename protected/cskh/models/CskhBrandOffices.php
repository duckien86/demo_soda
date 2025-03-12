<?php

    class CskhBrandOffices extends BrandOffices
    {
        public static function getListBrandOffices($province_code = '', $district_code = '')
        {
            $criteria = new CDbCriteria();
            if ($province_code && $district_code) {
                $criteria->condition = '(district_code = :district_code OR district_code = "" OR district_code IS NULL) ';
                $criteria->condition .= ' AND (province_code = :province_code) ';
                $criteria->params = array(':province_code' => $province_code, ':district_code' => $district_code);
            }

            $results = self::model()->findAll($criteria);

            return CHtml::listData($results, 'id', 'name');
        }
    }
