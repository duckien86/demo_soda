<?php

    class ACategoryQa extends CategoryQa
    {

        const ACTIVE   = 1;
        const INACTIVE = 0;

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
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'     => 'ID',
                'name'   => 'Danh mục',
                'status' => 'Trạng thái',
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
         * Lấy tất danh sách danh mục .
         */
        public static function getAllCateQa()
        {
            $data = ACategoryQa::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * Lấy tên danh mục theo id.
         */
        public static function getCateQa($id)
        {
            $data = ACategoryQa::model()->findByAttributes(array('id' => $id));

            return !empty($data->name) ? $data->name : '';
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
         * Viet hoa trang thai
         */
        public static function getStatus($id)
        {
            $data = array(
                self::INACTIVE => 'ẨN',
                self::ACTIVE   => 'KÍCH HOẠT',
            );

            return isset($data[$id]) ? $data[$id] : '';
        }


    }
