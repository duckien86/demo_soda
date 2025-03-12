<?php

    /**
     * This is the model class for table "{{province}}".
     *
     * The followings are the available columns in table '{{province}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $code
     * @property string  $office_name
     * @property string  $office_address
     * @property integer $napas_id
     * @property integer $vietinbank_id
     * @property integer $status
     * @property string  $vnp_province_id
     */
    class Province extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{province}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('napas_id, vietinbank_id, status', 'numerical', 'integerOnly' => TRUE),
                array('name, office_name, office_address, vnp_province_id', 'length', 'max' => 255),
                array('code', 'length', 'max' => 50),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, office_name, office_address, napas_id, vietinbank_id, status, vnp_province_id', 'safe', 'on' => 'search'),
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
                'name'            => 'Name',
                'code'            => 'Code',
                'office_name'     => 'Office Name',
                'office_address'  => 'Office Address',
                'napas_id'        => 'Napas',
                'vietinbank_id'   => 'Vietinbank',
                'status'          => 'Status',
                'vnp_province_id' => 'VNP Province',
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
            $criteria->compare('office_name', $this->office_name, TRUE);
            $criteria->compare('office_address', $this->office_address, TRUE);
            $criteria->compare('napas_id', $this->napas_id);
            $criteria->compare('vietinbank_id', $this->vietinbank_id);
            $criteria->compare('status', $this->status);
            $criteria->compare('vnp_province_id', $this->vnp_province_id);

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
         * @return Province the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tất cả tỉnh
         */
        public function getAllProvince()
        {
            $criteria = new CDbCriteria();

            if (!SUPER_ADMIN && !ADMIN && !USER_NOT_LOCATE) {

                if (isset(Yii::app()->user->province_code) && Yii::app()->user->province_code != "") {
                    $criteria->condition = "code = '" . Yii::app()->user->province_code . "'";
                } else {
                    $criteria->condition = "code =''";
                }
            }
            $data = Province::model()->findAll($criteria);

            return CHtml::listData($data, 'code', 'name');
        }

        /**
         * Lấy tất cả tỉnh
         */
        public function getAllProvinceVnp()
        {
            $criteria = new CDbCriteria();

            if (!SUPER_ADMIN && !ADMIN && !USER_NOT_LOCATE) {

                if (isset(Yii::app()->user->province_code) && Yii::app()->user->province_code != "") {
                    $criteria->condition = "code = '" . Yii::app()->user->province_code . "'";
                } else {
                    $criteria->condition = "code =''";
                }
            }
            $data = Province::model()->findAll($criteria);

            return CHtml::listData($data, 'vnp_province_id', 'name');
        }

        public function getProvinceByOrder($order_id)
        {
            if ($order_id) {
                $orders = Orders::model()->findByAttributes(array('id' => $order_id));
                if ($orders) {
                    if (isset($orders->province_code)) {
                        $province = Province::model()->find('code=:code', array(':code' => $orders->province_code));

                        return ($province) ? CHtml::encode($province->name) : $orders->province_code;
                    }
                }

                return "";
            }
        }

        /**
         * @param $code
         *
         * @return string
         */
        public function getProvince($code)
        {
            $province = array();
            if ($code) {
                $province = Province::model()->find('code=:code', array(':code' => $code));
//                if (isset($province->name) && !empty($province->name)) {
//                    if (!Yii::app()->redis_backend->get('be_static_province_' . $code)) {
//                        Yii::app()->redis_backend->set('be_static_province_' . $code, $province->name);
//                    }
//                }

                return ($province) ? CHtml::encode($province->name) : $code;
            }

        }

        public function getProvinceVnp($code)
        {
            $province = array();
            if ($code) {
                $province = Province::model()->find('vnp_province_id=:code', array(':code' => $code));
//                if (isset($province->name) && !empty($province->name)) {
//                    if (!Yii::app()->redis_backend->get('be_static_province_' . $code)) {
//                        Yii::app()->redis_backend->set('be_static_province_' . $code, $province->name);
//                    }
//                }

                return ($province) ? CHtml::encode($province->name) : $code;
            }

        }

        /**
         * @param $code
         *
         * @return string
         */
        public static function getProvinceVnpByCode($code){
            if ($code) {
                $province = Province::model()->find('code=:code', array(':code' => $code));
            }
            return ($province) ? CHtml::encode($province->vnp_province_id) : $code;
        }
    }
