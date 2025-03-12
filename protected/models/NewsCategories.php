<?php

    /**
     * This is the model class for table "{{news_categories}}".
     *
     * The followings are the available columns in table '{{news_categories}}':
     *
     * @property string  $id
     * @property integer $parent_id
     * @property string  $title
     * @property string  $slug
     * @property string  $thumbnail
     * @property integer $sort_order
     * @property string  $create_date
     * @property string  $last_update
     * @property integer $in_home_page
     * @property integer $status
     */
    class NewsCategories extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{news_categories}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
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
                'id'           => 'ID',
                'parent_id'    => 'Parent',
                'title'        => 'Title',
                'slug'         => 'Slug',
                'thumbnail'    => 'Thumbnail',
                'sort_order'   => 'Sort Order',
                'create_date'  => 'Create Date',
                'last_update'  => 'Last Update',
                'in_home_page' => 'In Home Page',
                'status'       => 'Status',
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
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('in_home_page', $this->in_home_page);
            $criteria->compare('status', $this->status);

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
         * @return NewsCategories the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
