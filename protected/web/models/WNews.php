<?php

    class WNews extends News
    {
        CONST NEWS_ACTIVE   = 1;
        CONST NEWS_INACTIVE = 0;

        // Config for field 'hot'
        CONST POSITION_ALL              = 0; //Hiển thị ở tất cả
        CONST POSITION_NEWS             = 1; //Hiển thị ở vị trí tin tức (list)
        CONST POSITION_HOT_NEWS         = 2; // Hiện thị ở vị trí tin tức đặc sắc (slider)
        CONST POSITION_SUPPORT          = 3; // Hiện thị ở mục hỗ trợ
        CONST POSITION_SUPPORT_WEB      = 4; // Hiện thị ở mục hỗ trợ Web
        CONST POSITION_SUPPORT_SOCIAL   = 5; // Hiện thị ở mục hỗ trợ Social
        CONST POSITION_SUPPORT_CTV      = 6; // Hiện thị ở mục hỗ trợ CTV


        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return WNews the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * get list news by type: Hot || Normal
         *
         * @param      $type
         * @param bool $dataProvider
         * @param int  $limit
         * @param int  $offset
         *
         * @return static[]
         */
        public static function getListNewsByType($type, $dataProvider = FALSE, $limit = 0, $offset = 0)
        {
            $list_position_support = array(
                self::POSITION_SUPPORT_WEB,
                self::POSITION_SUPPORT_SOCIAL,
                self::POSITION_SUPPORT_CTV,
            );
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status';
            if($type != self::POSITION_ALL){
                $criteria->condition .= ' AND (t.hot=:type OR t.hot = '.self::POSITION_ALL;
            }else{
                $criteria->condition .= ' AND (t.hot=:type';
            }
            if(in_array($type, $list_position_support)){
                $criteria->condition .= ' OR t.hot = '.self::POSITION_SUPPORT;
            }
            $criteria->condition .= ')';

            $criteria->params    = array(':status' => self::NEWS_ACTIVE, ':type' => $type);
            if ($limit) {
                $criteria->limit = $limit;
            }
            if ($offset) {
                $criteria->offset = $offset;
            }
            $criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC, t.create_date DESC';
            if ($dataProvider) {
                $cache_key = 'getListNewsByType_DataProvider_' . $type . $limit . $offset;
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
                $cache_key = 'getListNewsByType_' . $type . $limit . $offset;
                $results   = Yii::app()->cache->get($cache_key);
                if (!$results) {
                    $results = self::model()->findAll($criteria);
                    Yii::app()->cache->set($cache_key, $results);
                }
            }
            return $results;
        }


        /**
         * get list related news
         *
         * @param WNews $model
         * @param int   $limit
         * @param int   $offset
         *
         * @return static[]
         */
        public static function getListRelatedNews($model, $dataProvider = FALSE, $limit = 0, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status AND t.categories_id=:cat_id AND t.id!=:id';
            $criteria->params    = array(
                ':status'   => self::NEWS_ACTIVE,
                ':cat_id'   => $model->categories_id,
                ':id'       => $model->id
            );

            if ($limit) {
                $criteria->limit = $limit;
            }
            if ($offset) {
                $criteria->offset = $offset;
            }
            $criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC, t.create_date DESC';
            if ($dataProvider) {
                $cache_key = 'getListRelatedNews_DataProvider_' . $model->id . $limit . $offset;
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
                $cache_key = 'getListNewsByType_' . $model->id . $limit . $offset;
                $results   = Yii::app()->cache->get($cache_key);
                if (!$results) {
                    $results = self::model()->findAll($criteria);
                    Yii::app()->cache->set($cache_key, $results);
                }
            }
            return $results;
        }


        /**
         * return total page
         * @param $type : normal/hot
         * @param $limit int : page size
         * @return static[]
         */
        public static function getNewsTotalPage($type,$limit){
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status AND t.hot=:type';
            $criteria->params    = array(':status' => self::NEWS_ACTIVE, ':type' => $type);
            $criteria->order = 't.sort_order IS NULL ASC, t.sort_order ASC, t.create_date DESC';

            $cache_key = 'getNewsTotalPage_' . $type . $limit;
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results = ceil(self::model()->count($criteria) / $limit);
                Yii::app()->cache->set($cache_key, $results);
            }
            return $results;
        }
    }
