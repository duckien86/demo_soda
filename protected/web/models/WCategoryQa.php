<?php

    /**
     * This is the model class for table "{{category_qa}}".
     *
     * The followings are the available columns in table '{{category_qa}}':
     *
     * @property string  $id
     * @property string  $name
     * @property integer $status
     */
    class WCategoryQa extends CategoryQa
    {

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('name', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, status', 'safe', 'on' => 'search'),
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
                'id'     => 'ID',
                'name'   => 'Name',
                'status' => 'Status',
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
         * @return CategoryQa the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tất danh sách danh mục .
         */
        public static function getAllCateQa()
        {
            $data = WCategoryQa::model()->findAll();

            return $data;
        }

        /**
         * Lấy tên danh mục theo id.
         */
        public static function getCateQa($id)
        {
            $data = WCategoryQa::model()->findByAttributes(array('id' => $id));

            return !empty($data->name) ? $data->name : '';
        }
    }
