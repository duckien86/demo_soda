<?php

    /**
     * This is the model class for table "{{category_package}}".
     *
     * The followings are the available columns in table '{{category_package}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $description
     * @property string  $thumbnail
     * @property integer $status
     * @property integer $sort_index
     */
    class CategoryPackage extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{category_package}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status, sort_index', 'numerical', 'integerOnly' => TRUE),
                array('name, description, thumbnail', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, description, thumbnail, status, sort_index', 'safe', 'on' => 'search'),
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
                'id'          => 'ID',
                'name'        => 'Name',
                'description' => 'Description',
                'thumbnail'   => 'Thumbnail',
                'status'      => 'Status',
                'sort_index'  => 'Sort Index',
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
            $criteria->compare('thumbnail', $this->thumbnail, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('sort_index', $this->sort_index);

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
         * @return CategoryPackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
