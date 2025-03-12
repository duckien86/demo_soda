<?php

    /**
     * This is the model class for table "{{news}}".
     *
     * The followings are the available columns in table '{{news}}':
     *
     * @property string  $id
     * @property integer $categories_id
     * @property string  $title
     * @property string  $slug
     * @property string  $short_des
     * @property string  $full_des
     * @property string  $thumbnail
     * @property string  $create_date
     * @property string  $last_update
     * @property integer $hot
     * @property integer $sort_order
     * @property integer $status
     */
    class News extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{news}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
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
                'id'            => 'ID',
                'categories_id' => 'Categories',
                'title'         => 'Title',
                'slug'          => 'Slug',
                'short_des'     => 'Short Des',
                'full_des'      => 'Full Des',
                'thumbnail'     => 'Thumbnail',
                'create_date'   => 'Create Date',
                'last_update'   => 'Last Update',
                'hot'           => 'Hot',
                'sort_order'    => 'Sort Order',
                'status'        => 'Status',
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
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('hot', $this->hot);
            $criteria->compare('sort_order', $this->sort_order);
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
         * @return News the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
