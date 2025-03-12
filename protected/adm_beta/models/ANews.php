<?php

    class ANews extends News
    {
        CONST NEWS_ACTIVE   = 1;
        CONST NEWS_INACTIVE = 0;

        CONST POSITION_ALL            = 0; //Hiển thị ở tất cả
        CONST POSITION_NEWS           = 1; //Hiển thị ở vị trí tin tức (list)
        CONST POSITION_HOT_NEWS       = 2; // Hiện thị ở vị trí tin tức đặc sắc (slider)
        CONST POSITION_SUPPORT        = 3; // Hiện thị ở mục hỗ trợ
        CONST POSITION_SUPPORT_WEB    = 4; // Hiện thị ở mục hỗ trợ Web
        CONST POSITION_SUPPORT_SOCIAL = 5; // Hiện thị ở mục hỗ trợ Social

        public $old_file;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('categories_id, title, full_des', 'required'),
                array('categories_id, hot, sort_order, status', 'numerical', 'integerOnly' => TRUE),
                array('title, thumbnail', 'length', 'max' => 255),
                array('slug', 'length', 'max' => 500),
                array('short_des', 'length', 'max' => 1000),
                array('full_des, create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, categories_id, title, slug, short_des, full_des, thumbnail, create_date, last_update, hot, sort_order, status', 'safe', 'on' => 'search'),
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
                'id'            => Yii::t('adm/label', 'id'),
                'categories_id' => Yii::t('adm/label', 'categories_id'),
                'title'         => Yii::t('adm/label', 'title'),
                'slug'          => Yii::t('adm/label', 'slug'),
                'short_des'     => Yii::t('adm/label', 'short_des'),
                'full_des'      => Yii::t('adm/label', 'full_des'),
                'thumbnail'     => Yii::t('adm/label', 'thumbnail'),
                'create_date'   => Yii::t('adm/label', 'create_date'),
//                'last_update'   => Yii::t('adm/label', 'last_update'),
                'last_update'   => Yii::t('adm/label', 'publish_date'),
                'hot'           => Yii::t('adm/label', 'position'),
                'sort_order'    => Yii::t('adm/label', 'sort_order'),
                'status'        => Yii::t('adm/label', 'status'),
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
            $criteria->compare('categories_id', $this->categories_id);
            $criteria->compare('title', $this->title, TRUE);
            $criteria->compare('slug', $this->slug, TRUE);
            $criteria->compare('short_des', $this->short_des, TRUE);
            $criteria->compare('full_des', $this->full_des, TRUE);
            $criteria->compare('thumbnail', $this->thumbnail, TRUE);
            $criteria->compare("DATE_FORMAT(t.create_date, '%d/%m/%Y')", $this->create_date);
            $criteria->compare("DATE_FORMAT(t.last_update, '%d/%m/%Y')", $this->last_update);
            $criteria->compare('hot', $this->hot);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort'     => array(
                    'defaultOrder' => 't.create_date DESC',
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return ANews the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
            if ($this->isNewRecord) {
                $this->create_date = date('Y-m-d H:i:s', time());
            }

            if (empty($this->last_update) || !strtotime($this->last_update)) {
                $this->last_update = date('Y-m-d H:i:s');
            } else {
                $this->last_update = date('m-d-Y H:i:s', strtotime($this->last_update));
                $this->last_update = date('Y-m-d H:i:s', strtotime($this->last_update));
            }

            return TRUE;
        }

        /**
         * @param $images
         *
         * @return string
         */
        public function getImageUrl($images)
        {
            $dir_root = Yii::app()->params->upload_dir_path;

            return CHtml::image($dir_root . $images, '', array("width" => "80px", "height" => "50px", "title" => $this->title));
        }

        /**
         * delete exists
         *
         * @param $image
         */
        public function deleteImages($image)
        {
            $dir_root = '/../';
            if ($image) {
                unlink(realpath(Yii::app()->getBasePath() . $dir_root . $image));
            }
        }

        /**
         * Get News Categories title by categories_id
         *
         * @return string
         */
        public function getNewsCategoriesTitle()
        {
            $model = '';
            if ($this->categories_id) {
                $model = ANewsCategories::model()->find('id=:id', array(':id' => $this->categories_id));
            }

            return ($model) ? CHtml::encode($model->title) : $this->categories_id;
        }

        /**
         * @return string
         */
        public function getStatusLabel()
        {
            return ($this->status == self::NEWS_ACTIVE) ? Yii::t('adm/label', 'active') : Yii::t('adm/label', 'inactive');
        }

        /**
         * @return array
         */
        public static function getListPosition()
        {
            return array(
                self::POSITION_ALL            => Yii::t('adm/label', 'all'),
                self::POSITION_NEWS           => Yii::t('adm/label', 'position_news'),
                self::POSITION_HOT_NEWS       => Yii::t('adm/label', 'position_hot_news'),
                self::POSITION_SUPPORT        => Yii::t('adm/label', 'position_support'),
                self::POSITION_SUPPORT_WEB    => Yii::t('adm/label', 'position_support_web'),
                self::POSITION_SUPPORT_SOCIAL => Yii::t('adm/label', 'position_support_social'),
            );
        }

        /**
         * @param $value int
         *
         * @return mixed
         */
        public static function getLabelPosition($value)
        {
            $data = self::getListPosition();

            return (isset($data[$value])) ? $data[$value] : $value;
        }


        /**
         * @param      $type
         * @param bool $dataProvider
         * @param int  $limit
         * @param int  $offset
         *
         * @return array|CActiveDataProvider|mixed|null
         */
        public static function getListNewsByType($type, $dataProvider = FALSE, $limit = 0, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status';
            if ($type != self::POSITION_ALL) {
                $criteria->condition .= ' AND (t.hot=:type OR t.hot = ' . self::POSITION_ALL;
            } else {
                $criteria->condition .= ' AND (t.hot=:type';
            }
            if ($type == self::POSITION_SUPPORT_WEB || $type == self::POSITION_SUPPORT_SOCIAL) {
                $criteria->condition .= ' OR t.hot = ' . self::POSITION_SUPPORT;
            }
            $criteria->condition .= ')';

            $criteria->params = array(':status' => self::NEWS_ACTIVE, ':type' => $type);
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
    }
