<?php

    /**
     * This is the model class for table "sc_tbl_post_category".
     *
     * The followings are the available columns in table 'sc_tbl_post_category':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $description
     * @property integer $sort_order
     * @property integer $home_display
     * @property string  $thumbnail
     * @property integer $status
     * @property string  $icon
     * @property string  $parent_id
     */
    class APostCategory extends PostCategory
    {
        const  ACTIVE         = 1;
        const  INACTIVE       = 0;
        const  POST_CATE_HOME = 1;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('sort_order, home_display, status, parent_id', 'numerical', 'integerOnly' => TRUE),
                array('name, thumbnail, icon', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, description, sort_order, home_display, thumbnail, status, icon', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'name'         => 'Danh mục',
                'description'  => 'Mô tả',
                'sort_order'   => 'Thứ tự hiện thị',
                'home_display' => 'Hiện thị trang chủ',
                'thumbnail'    => 'Thumbnail',
                'status'       => 'Trạng thái',
                'icon'         => 'icon',
                'parent_id'    => 'Menu cha',
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
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare('home_display', $this->home_display);
            $criteria->compare('thumbnail', $this->thumbnail, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('icon', $this->icon, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return PostCategory the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy danh sách menu cha.
         *
         * @return array
         */
        public function getParentCategory()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'status=:status AND parent_id=0';
            $criteria->params    = array(':status' => self::ACTIVE);
            $results             = CHtml::listData(self::model()->findAll($criteria), 'id', 'name');

            return $results;
        }

        /**
         * @param $id
         * Lấy tên menu.
         *
         * @return string
         */
        public function getMenu($id)
        {
            $data = APostCategory::model()->findByAttributes(array('id' => $id));

            return ($data && $data->name) ? $data->name : $id;
        }

        public function getShowHome($key)
        {
            $data = array(
                0 => 'Ẩn',
                1 => 'Hiển thị',
            );

            return (isset($data[$key])) ? $data[$key] : $key;
        }

        /**
         * @param int $limit
         * @param int $offset
         *
         * @return array
         */
        public static function getListPostCategory($limit = 5, $offset = 0)
        {
            $cache_key = 'data_getListPostCategory';
            $results   = Yii::app()->cache->get($cache_key);
            if (!$results) {
                $results             = array();
                $criteria            = new CDbCriteria();
                $criteria->condition = 't.status=:status AND t.home_display=:home_display AND t.parent_id=0';
                $criteria->params    = array(':status' => self::ACTIVE, ':home_display' => self::POST_CATE_HOME);

                if ($limit) {
                    $criteria->limit = $limit;
                }
                if ($offset) {
                    $criteria->offset = $offset;
                }
                $criteria->order = 't.sort_order';

                $array_parent = self::model()->findAll($criteria);
                foreach ($array_parent as $key => $item) {
                    $temp['category'] = $item;
                    $temp['sub_cate'] = self::getPostCategoryByParentId($item->id);
                    if ($temp) {
                        array_push($results, $temp);
                    }
                }

                Yii::app()->cache->set($cache_key, $results);
            }

            return $results;
        }

        public static function getPostCategoryByParentId($parent_id, $limit = 0, $offset = 0)
        {
            $cache_key = 'getPostCategoryByParentId_' . $parent_id;
            $results   = Yii::app()->cache->get($cache_key);

            if (!$results) {
                $criteria            = new CDbCriteria();
                $criteria->condition = 't.status=:status AND t.home_display=:home_display AND t.parent_id=:parent_id';
                $criteria->params    = array(':status' => self::ACTIVE, ':home_display' => self::POST_CATE_HOME, ':parent_id' => $parent_id);

                if ($limit) {
                    $criteria->limit = $limit;
                }
                if ($offset) {
                    $criteria->offset = $offset;
                }
                $criteria->order = 't.sort_order';
                $results         = self::model()->findAll($criteria);

                Yii::app()->cache->set($cache_key, $results);
            }

            return $results;
        }
    }
