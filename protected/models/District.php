<?php

    /**
     * This is the model class for table "{{district}}".
     *
     * The followings are the available columns in table '{{district}}':
     *
     * @property string $id
     * @property string $name
     * @property string $code
     * @property string $province_code
     */
    class District extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{district}}';
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
                array('code', 'length', 'max' => 255),
                array('province_code', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, province_code', 'safe', 'on' => 'search'),
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
         * @return District the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy quận huyện theo tỉnh
         *
         * @param $code
         */
        public function getDistrict($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "code = '" . $code . "'";

            $data = District::model()->find($criteria);

            return ($data) ? CHtml::encode($data->name) : $code;
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllDistrict()
        {
            $criteria = new CDbCriteria();

            if (!SUPER_ADMIN && !ADMIN) {
                if (isset(Yii::app()->user->district_code)) {
                    if (Yii::app()->user->district_code != "") {
                        $criteria->condition = "code = '" . Yii::app()->user->district_code . "'";
                    } else {
                        $criteria->condition = "code =''";
                    }
                }
            }
            $data = District::model()->findAll($criteria);


            return CHtml::listData($data, 'code', 'name');
        }


        /**
         * Lấy tất cả quận huyện theo tỉnh
         */
        public function getDistrictByProvince($province_code)
        {
            $criteria = new CDbCriteria();

            $criteria->condition = "province_code = '" . $province_code . "'";

            $data = District::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }
    }
