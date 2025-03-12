<?php

    class WCategoryPackage extends CategoryPackage
    {
        const CATE_PACKAGE_ACTIVE   = 1;
        const CATE_PACKAGE_INACTIVE = 0;

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WCategoryPackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                'package' => array(self::HAS_MANY, 'WPackage', 'category_id'),
            );
        }

        /**
         * get list category package
         * list package by category_id
         *
         * @return static[]
         */
        public static function getAllCategoryPackage()
        {
            $criteria            = new CDbCriteria;
            $criteria->distinct  = TRUE;
            $criteria->with      = 'package';
            $criteria->condition = 't.status=:status';
            $criteria->params    = array(':status' => self::CATE_PACKAGE_ACTIVE);
            $criteria->order     = 't.sort_index';

            /*package*/
            $tempCriteria        = new CDbCriteria();
            $criteria->condition = 'package.status=:status';
            $criteria->params    = array(':status' => WPackage::PACKAGE_ACTIVE);

            $criteria->mergeWith($tempCriteria, 'AND');
            $results = self::model()->findAll($criteria);

            return $results;
        }
    }
