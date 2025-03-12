<?php

    /**
     * This is the model class for table "{{brand_offices}}".
     *
     * The followings are the available columns in table '{{brand_offices}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $address
     * @property string  $ward_code
     * @property string  $district_code
     * @property string  $province_code
     * @property string  $hotline
     * @property string  $descriptions
     * @property integer $head_office
     * @property string  $status
     * @property string  $agency_id
     */
    class BrandOffices extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{brand_offices}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('head_office, status', 'numerical', 'integerOnly' => TRUE),
                array('name, address, ward_code, district_code, province_code, hotline, descriptions, agency_id', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, address, ward_code, district_code, province_code, hotline, descriptions, head_office, status, agency_id', 'safe', 'on' => 'search'),
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
                'address'       => 'Address',
                'ward_code'     => 'Ward Code',
                'district_code' => 'District Code',
                'province_code' => 'Province Code',
                'hotline'       => 'Hotline',
                'descriptions'  => 'Descriptions',
                'head_office'   => 'Head Office',
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
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('address', $this->address, TRUE);
            $criteria->compare('ward_code', $this->ward_code, TRUE);
            $criteria->compare('district_code', $this->district_code, TRUE);
            $criteria->compare('province_code', $this->province_code, TRUE);
            $criteria->compare('hotline', $this->hotline, TRUE);
            $criteria->compare('descriptions', $this->descriptions, TRUE);
            $criteria->compare('head_office', $this->head_office);
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
         * @return BrandOffices the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy điểm giao dịch theo code
         *
         * @param $code
         */
        public function getBrandOffices($id)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = "id = '" . $id . "'";

            $data = BrandOffices::model()->find($criteria);
//            if (isset($data->name) && !empty($data->name)) {
//                if (!Yii::app()->redis_backend->get('be_static_brand_office_' . $id)) {
//                    Yii::app()->redis_backend->set('be_static_brand_office_' . $id, $data->name);
//                }
//            }

            return ($data) ? CHtml::encode($data->name) : "";
        }

        /**
         * Lấy tất cả điểm giao dịch
         */
        public function getAllBrandOffices()
        {
            $criteria = new CDbCriteria();

            if (!SUPER_ADMIN && !ADMIN) {
                if (isset(Yii::app()->user->brand_offices_id) && Yii::app()->user->brand_offices_id != "") {
                    $criteria->condition = "id = '" . Yii::app()->user->brand_offices_id . "'";
                } else {
                    if (isset(Yii::app()->user->sale_offices_id) && Yii::app()->user->sale_offices_id != "") {
                        $criteria->condition = "head_office ='" . Yii::app()->user->sale_offices_id . "'";
                    } else {
                        $criteria->condition = "id =''";
                    }
                }
            }
            $data = BrandOffices::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'name');
        }


        /**
         * Lấy tất cả điểm giao dịch theo phòng bán hàng.
         */
        public function getBrandOfficesBySale($sale_id)
        {

            $sale = SaleOffices::model()->findByAttributes(array('id' => $sale_id));
            if ($sale) {
                $criteria = new CDbCriteria();

                $criteria->condition = "head_office = '" . $sale->code . "'";

                $data = BrandOffices::model()->findAll($criteria);

                return CHtml::listData($data, 'id', 'name');
            }
        }

        /**
         * Lấy tất cả điểm giao dịch theo phòng bán hàng.
         */
        public function getBrandOfficesBySaleCode($sale_code)
        {

            if ($sale_code) {
                $criteria = new CDbCriteria();
                if (!ADMIN && !SUPER_ADMIN && !USER_NOT_LOCATE) {
                    if (isset(Yii::app()->user->brand_offices_id)) {
                        $criteria->condition = "id = '" . Yii::app()->user->brand_offices_id . "'";
                    } else {
                        $criteria->condition = "head_office = '" . $sale_code . "'";
                    }
                } else {
                    $criteria->condition = "head_office = '" . $sale_code . "'";
                }
                $data = BrandOffices::model()->findAll($criteria);

                return CHtml::listData($data, 'id', 'name');
            }
        }

        public function getBrandOfficesByOrder($id)
        {
            if ($id) {
                $orders = Orders::model()->findByAttributes(array('id' => $id));
                if (!empty($orders->ward_code) || !empty($orders->address_detail)) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "ward_code ='" . $orders->ward_code . "' OR id ='" . $orders->address_detail . "'";
                    $data                = BrandOffices::model()->find($criteria);

                    return isset($data->name) ? $data->name : '';
                }
            }

            return "";
        }

        public function getBrandOfficesByOrderId($id)
        {

            $orders = AOrders::model()->findByAttributes(array('id' => $id));
            $data   = array();
            if ($orders) {
                if (!empty($orders->sale_office_code)) {
                    $criteria = new CDbCriteria();

                    $criteria->condition = "head_office = '" . $orders->sale_office_code . "'";

                    $data = BrandOffices::model()->findAll($criteria);
                }
            }

            if ($orders->delivery_type == 1) {
                return $orders->address_detail;
            } else {
                $return   = CHtml::listData($data, 'id', 'name');
                $data_old = array(
                    $orders->address_detail => BrandOffices::model()->findByAttributes(array('id' => $orders->address_detail))->name,
                );
                $return   = $return + $data_old;

                return $return;
            }
        }
    }
