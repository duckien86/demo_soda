<?php

    /**
     * This is the model class for table "{{ward}}".
     *
     * The followings are the available columns in table '{{ward}}':
     *
     * @property string $id
     * @property string $name
     * @property string $code
     * @property string $type
     * @property string $location
     * @property string $districtid
     * @property string $district_code
     * @property string $province_code
     */
    class Ward extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{ward}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('name', 'length', 'max' => 100),
                array('code, district_code, province_code', 'length', 'max' => 255),
                array('type, location, districtid', 'length', 'max' => 30),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, type, location, districtid, district_code, province_code', 'safe', 'on' => 'search'),
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
                'name'          => 'Name',
                'code'          => 'Code',
                'type'          => 'Type',
                'location'      => 'Location',
                'districtid'    => 'Districtid',
                'district_code' => 'District Code',
                'province_code' => 'Province Code',
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
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('type', $this->type, TRUE);
            $criteria->compare('location', $this->location, TRUE);
            $criteria->compare('districtid', $this->districtid, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);

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
         * @return Ward the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tất cả phường xã theo quận huyện
         */
        public function getWardByDistrict($ward_code)
        {
            $criteria = new CDbCriteria();

            $criteria->condition = "district_code = '" . $ward_code . "'";

            $data = Ward::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy phường xã theo mã
         *
         * @param $code
         */
        public function getWard($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "code = '" . $code . "'";

            $data = Ward::model()->find($criteria);

            return ($data) ? CHtml::encode($data->name) : $code;
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllWard()
        {

            $data = Ward::model()->findAll();

            return CHtml::listData($data, 'code', 'name');
        }
    }
