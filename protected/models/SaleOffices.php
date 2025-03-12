<?php

    /**
     * This is the model class for table "{{sale_offices}}".
     *
     * The followings are the available columns in table '{{sale_offices}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $ward_code
     * @property string  $district_code
     * @property string  $province_code
     * @property string  $code
     * @property integer $location_type
     * @property integer $agency_id
     */
    class SaleOffices extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{sale_offices}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('location_type', 'numerical', 'integerOnly' => TRUE),
                array('name, ward_code, district_code, province_code, code, agency_id', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, ward_code, district_code, province_code, code, location_type, agency_id', 'safe', 'on' => 'search'),
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
                'ward_code'     => 'Ward Code',
                'district_code' => 'District Code',
                'province_code' => 'Province Code',
                'code'          => 'Code',
                'location_type' => 'Location Type',
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
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('location_type', $this->location_type);

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
         * @return SaleOffices the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy phòng bán hàng theo code
         *
         * @param $code
         */
        public function getSaleOffices($code)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "code = '" . $code . "'";

            $data = SaleOffices::model()->find($criteria);
//            if (isset($data->name) && !empty($data->name)) {
//                if (!Yii::app()->redis_backend->get('be_static_sale_office_' . $code)) {
//                    Yii::app()->redis_backend->set('be_static_sale_office_' . $code, $data->name);
//                }
//            }
            return ($data) ? CHtml::encode($data->name) : "";
        }

        public function getSaleOfficesByOrder($order_id)
        {

            $orders = Orders::model()->findByAttributes(array('id' => $order_id));
            if ($orders->sale_office_code) {
                $sale_office_code = SaleOffices::model()->findByAttributes(array('code' => $orders->sale_office_code));
                if ($sale_office_code) {
                    return isset($sale_office_code->name) ? $sale_office_code->name : '';
                }
            }

            return "";
        }

        /**
         * Lấy phòng bán hàng theo code
         *
         * @param $code
         */
        public function getSaleOfficesId($code)
        {

            if ($code) {
                $criteria            = new CDbCriteria();
                $criteria->condition = "code = '" . $code . "'";

                $data = SaleOffices::model()->find($criteria);
            }

            return ($data) ? CHtml::encode($data->name) : '';
        }

        /**
         * Lấy tất cả quận huyện
         */
        public function getAllSaleOffices()
        {
            $criteria = new CDbCriteria();
            if (!SUPER_ADMIN && !ADMIN) {

                if (isset(Yii::app()->user->sale_offices_id) && Yii::app()->user->sale_offices_id != "") {
                    $criteria->condition = "code = '" . Yii::app()->user->sale_offices_id . "'";
                } else {
                    if (isset(Yii::app()->user->province_code)) {
                        $criteria->condition = "province_code ='" . Yii::app()->user->province_code . "'";
                    } else {
                        $criteria->condition = "province_code =''";
                    }
                }
            }

            $data = SaleOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }


        /**
         * Lấy tất cả quận huyện theo phường xã.
         */
        public function getSaleOfficesByWard($ward_code)
        {
            $criteria = new CDbCriteria();

            $criteria->condition = "ward_code = '" . $ward_code . "'";

            $data = SaleOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'name');
        }

        /**
         * Lấy tất cả quận huyện theo phường xã.
         */
        public function getSaleOfficesByProvince($province_code)
        {
            $criteria = new CDbCriteria();
            if (!SUPER_ADMIN && !ADMIN && !USER_NOT_LOCATE) {
                if (isset(Yii::app()->user->sale_offices_id) && Yii::app()->user->sale_offices_id != "") {
                    $criteria->condition = "code = '" . Yii::app()->user->sale_offices_id . "'";
                } else {
                    if (isset(Yii::app()->user->province_code)) {
                        $criteria->condition = "province_code ='" . Yii::app()->user->province_code . "'";
                    } else {
                        $criteria->condition = "province_code =''";
                    }
                }
            } else {
                $criteria->condition = "province_code ='" . $province_code . "'";
            }


            $data = SaleOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy tất cả quận huyện theo phường xã.
         */
        public function getSaleOfficesByProvinceCode($province_code)
        {

            $criteria = new CDbCriteria();

            $criteria->condition = "province_code = '" . $province_code . "'";

            $data = SaleOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        public function getSaleOfficesByOrderId($id)
        {
            $orders = AOrders::model()->findByAttributes(array('id' => $id));
            $data   = array();
            if ($orders) {
                if (!empty($orders->province_code)) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "province_code = '" . $orders->province_code . "'";

                    $data = SaleOffices::model()->findAll($criteria);
                }
            }
            $data_old = array(
                $orders->sale_office_code => SaleOffices::model()->findByAttributes(array('code' => $orders->sale_office_code))->name,
            );
            $data     = CHtml::listData($data, 'code', 'name');
            $data     = $data + $data_old;
            return $data;
        }

    }
