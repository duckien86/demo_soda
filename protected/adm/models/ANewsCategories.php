<?php

    class ANewsCategories extends NewsCategories
    {
        CONST NEWS_CATE_ACTIVE   = 1;
        CONST NEWS_CATE_INACTIVE = 0;
        CONST HOMEPAGE           = 1;
        CONST SOCIAL             = 0;

        public $old_file;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('title', 'required'),
                array('parent_id, sort_order, in_home_page, status', 'numerical', 'integerOnly' => TRUE),
                array('title', 'length', 'max' => 255),
                array('slug, thumbnail', 'length', 'max' => 500),
                array('create_date, last_update', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, parent_id, title, slug, thumbnail, sort_order, create_date, last_update, in_home_page, status', 'safe', 'on' => 'search'),
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
                'id'           => Yii::t('adm/label', 'id'),
                'parent_id'    => Yii::t('adm/label', 'parent_id_cate'),
                'title'        => Yii::t('adm/label', 'title'),
                'slug'         => Yii::t('adm/label', 'slug'),
                'thumbnail'    => Yii::t('adm/label', 'thumbnail'),
                'sort_order'   => Yii::t('adm/label', 'sort_order'),
                'create_date'  => Yii::t('adm/label', 'create_date'),
                'last_update'  => Yii::t('adm/label', 'last_update'),
                'in_home_page' => Yii::t('adm/label', 'in_home_page'),
                'status'       => Yii::t('adm/label', 'status'),
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
            $criteria->compare('parent_id', $this->parent_id);
            $criteria->compare('title', $this->title, TRUE);
            $criteria->compare('slug', $this->slug, TRUE);
            $criteria->compare('thumbnail', $this->thumbnail, TRUE);
            $criteria->compare('sort_order', $this->sort_order);
            $criteria->compare("DATE_FORMAT(t.create_date, '%d/%m/%Y')", $this->create_date);
            $criteria->compare("DATE_FORMAT(t.last_update, '%d/%m/%Y')", $this->last_update);
            $criteria->compare('in_home_page', $this->in_home_page);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
                'sort'       => array(
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
         * @return ANewsCategories the static model class
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
            $this->last_update = date('Y-m-d H:i:s', time());

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

            return CHtml::image($dir_root . $images, $this->title, array("width" => "80px", "height" => "50px", "title" => $this->title));
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
         * @param null $id
         *
         * @return array
         */
        public static function getParentCategories($id = NULL)
        {
            $criteria = new CDbCriteria();
            if ($id) {
                $criteria->condition = 't.id != :id AND t.parent_id != :id AND status=:status';
                $criteria->params    = array(':id' => $id, ':status' => self::NEWS_CATE_ACTIVE);
            } else {
                $criteria->condition = 'status=:status';
                $criteria->params    = array(':status' => self::NEWS_CATE_ACTIVE);
            }

            $results = CHtml::listData(self::model()->findAll($criteria), 'id', 'title');

            return $results;
        }

        /**
         * @return array
         */
        public static function getAllCategories()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 'status=:status';
            $criteria->params    = array(':status' => self::NEWS_CATE_ACTIVE);
            $results             = CHtml::listData(self::model()->findAll($criteria), 'id', 'title');

            return $results;
        }

        /**
         * Get News Categories title by id
         *
         * @return string
         */
        public function getNewsCategoriesTitle()
        {
            $model = '';
            if ($this->parent_id) {
                $model = self::model()->find('id=' . $this->parent_id);
            }

            return ($model) ? CHtml::encode($model->title) : $this->parent_id;
        }

        /**
         * @param $value
         *
         * @return mixed
         */
        public function getLabelHomepage($value)
        {
            $data = array(
                self::SOCIAL   => 'Cộng đồng',
                self::HOMEPAGE => 'Trang chủ',
            );

            return (isset($data[$value])) ? $data[$value] : $value;
        }
    }
