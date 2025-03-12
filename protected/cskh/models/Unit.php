<?php

    /**
     * This is the model class for table "cc_tbl_unit".
     *
     * The followings are the available columns in table 'cc_tbl_unit':
     *
     * @property integer $id
     * @property string  $name
     * @property string  $description
     * @property string  $district
     * @property integer $parent_id
     * @property integer $status
     * @property string  $create_date
     * @property string  $extra_params
     */
    class Unit extends ModelBase
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_unit';
        }

        const ACTIVE        = 1;
        const INACTIVE      = 0;
        const TEXT_ACTIVE   = 'ACTIVE';
        const TEXT_INACTIVE = 'INACTIVE';

        public $user_id;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, parent_id, status', 'numerical', 'integerOnly' => TRUE),
                array('name', 'required'),
                array('name', 'unique', 'className' => 'Unit', 'attributeName' => 'name', 'message' => 'Tên đơn vị này đã được sử dụng!'),
                array('name, district_code, province_code, brand_office_id', 'length', 'max' => 255),
                array('description', 'length', 'max' => 1000),
                array('extra_params', 'length', 'max' => 500),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, description, district, parent_id, status, create_date, extra_params', 'safe'),
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
                'id'              => 'ID',
                'name'            => 'Tên đơn vị',
                'description'     => 'Thông tin',
                'district'        => 'Quận/huyện',
                'parent_id'       => 'Trực thuộc',
                'status'          => 'Trạng thái',
                'create_date'     => 'Ngày tạo',
                'extra_params'    => 'Extra Params',
                'district_code'   => 'Quận huyện',
                'province_code'   => 'Tỉnh thành',
                'brand_office_id' => 'Điểm giao dịch',
                'type'            => 'Chương trình',
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

            $criteria->compare('id', $this->id);
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('district', $this->district, TRUE);
            $criteria->compare('parent_id', $this->parent_id);
            $criteria->compare('status', $this->status);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('extra_params', $this->extra_params, TRUE);

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
         * @return Unit the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        public function beforeSave()
        {
            $p = new CHtmlPurifier();

            $this->name        = $p->purify($this->name);
            $this->description = $p->purify($this->description);
            $this->description = $p->purify($this->description);

            return parent::beforeSave(); // TODO: Change the autogenerated stub
        }

        public function getUnit()
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "status ='" . self::ACTIVE . "' and (parent_id ='' OR parent_id is null)";
            $data                = Unit::model()->findAll($criteria);
            $return              = CHtml::listData($data, 'id', 'name');

            return $return;
        }

        //Lấy tên đơn vị.
        public function getUnitName($id)
        {
            if (!empty($id)) {
                $unit = Unit::model()->findByAttributes(array('id' => $id));

                return $unit->name;
            }
        }


        /**
         * Lấy tất cả tỉnh theo quyền.
         */
        public function getAllProvince()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Province::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else {
                if (isset(Yii::app()->user->id)) {
                    $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                    if ($user) {
                        if ($user->province_code != '') {
                            $criteria            = new CDbCriteria();
                            $criteria->condition = "code = '" . $user->province_code . "'";

                            $data = Province::model()->findAll($criteria);

                            return CHtml::listData($data, 'code', 'name');
                        }
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllDistrict()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = District::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->district_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "code = '" . $user->district_code . "'";

                        $data = District::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }

            }

            return $return;
        }

        /**
         * Lấy tất cả phường xã
         */
        public function getAllWard()
        {
            $return = array();
            if (SUPER_ADMIN || ADMIN) {
                $data = Ward::model()->findAll();

                return CHtml::listData($data, 'code', 'name');
            } else if (isset(Yii::app()->user->id)) {
                $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
                if ($user) {
                    if ($user->ward_code != '') {
                        $criteria            = new CDbCriteria();
                        $criteria->condition = "id = '" . $user->ward_code . "'";

                        $data = Ward::model()->findAll($criteria);

                        return CHtml::listData($data, 'code', 'name');
                    }
                }
            }

            return $return;
        }

        /**
         * Lấy tất cả phường xã
         */
        public function getAllBrandOffice()
        {
            $data = BrandOffices::model()->findAll();

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * @param $code
         *
         * @return string
         */
        public function getProvince($code = '')
        {
            $province = array();
            if ($code) {
                $province = Province::model()->find('code=:code', array(':code' => $code));
            }

            return ($province) ? CHtml::encode($province->name) : $code;
        }

        /**
         * @param $code
         *
         * @return string
         */
        public function getDistrict($code = '')
        {
            $district = array();
            if ($code) {
                $district = District::model()->find('code=:code', array(':code' => $code));
            }

            return ($district) ? CHtml::encode($district->name) : $code;
        }


    }
