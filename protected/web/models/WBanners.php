<?php

    class WBanners extends Banners
    {
        CONST BANNER_ACTIVE   = 1;
        CONST BANNER_INACTIVE = 0;

        CONST TYPE_SLIDER  = 'slider';
        CONST TYPE_PACKAGE = 'package';
        CONST TYPE_RIGHT_SIDE = 'rightside';
        CONST TYPE_FIBER_SIDE = 'fiberslider';
        CONST TYPE_MYTV_SIDE = 'mytvslider';
        CONST TYPE_PACKAGE_SLIDER = "packageslider";

        /**
         * get list news by type: slider || package || rightside
         *
         * @param      $type
         * @param bool $dataProvider
         * @param int  $limit
         * @param int  $offset
         *
         * @return static[]
         */
        public static function getListBannerByType($type, $dataProvider = FALSE, $limit = 0, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status AND t.type=:type';
            $criteria->params    = array(':status' => self::BANNER_ACTIVE, ':type' => $type);
            if ($limit) {
                $criteria->limit = $limit;
            }
            if ($offset) {
                $criteria->offset = $offset;
            }
            $criteria->order = 'sort_order';
            if ($dataProvider) {
                $cache_key = 'getListBannerByType_DataProvider_' . $type . $limit . $offset;
                $results   = Yii::app()->cache->get($cache_key);
                if (!$results) {
                    $results = new CActiveDataProvider(self::model(), array(
                        'criteria'   => $criteria,
                        'sort'       => array(
                            'defaultOrder' => 't.sort_order',
                        ),
                        'pagination' => array(
                            'pageSize' => $limit,
                        )
                    ));
                    Yii::app()->cache->set($cache_key, $results);
                }
            } else {
                $cache_key = 'getListBannerByType_' . $type . $limit . $offset;
                $results   = Yii::app()->cache->get($cache_key);
                if (!$results) {
                    $results = self::model()->findAll($criteria);
                    Yii::app()->cache->set($cache_key, $results);
                }
            }
            return $results;
        }
    }
