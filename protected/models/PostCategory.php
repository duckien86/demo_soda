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
    class PostCategory extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_post_category';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('sort_order, home_display, status', 'numerical', 'integerOnly' => TRUE),
                array('name, thumbnail, icon', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                array('parent_id', 'length', 'max' => 11),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, description, sort_order, home_display, thumbnail, status, icon, parent_id', 'safe', 'on' => 'search'),
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
                'name'         => 'Name',
                'description'  => 'Description',
                'sort_order'   => 'Sort Order',
                'home_display' => 'Home Display',
                'thumbnail'    => 'Thumbnail',
                'status'       => 'Status',
                'icon'         => 'icon',
                'parent_id'    => 'parent_id',
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
            $criteria->compare('parent_id', $this->parent_id, TRUE);

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
    }
