<?php

    class AFTPackage extends FTPackage
    {
        CONST FT_PACKAGE_ACTIVE   = 1;
        CONST FT_PACKAGE_INACTIVE = 0;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('code', 'unique'),
                array('name, code, price, status', 'required'),
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('name, code, description', 'length', 'max' => 255),
                array('price', 'length', 'max' => 10),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, description, price, status', 'safe', 'on' => 'search'),
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
                'id'          => Yii::t('adm/label', 'id'),
                'name'        => Yii::t('adm/label', 'product_name'),
                'code'        => Yii::t('adm/label', 'product_code'),
                'description' => Yii::t('adm/label', 'description'),
                'price'       => Yii::t('adm/label', 'product_price'),
                'status'      => Yii::t('adm/label', 'status'),
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
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('price', $this->price, TRUE);
            $criteria->compare('status', $this->status);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.id desc'),
                'pagination' => array(
                    'pageSize' => 50,
                )
            ));
        }


        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return AFTPackage the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * Lấy tên sản phẩm
         *
         * @param $id
         *
         * @return string
         */
        public static function getNameProduct($id)
        {
            $result  = '';
            $product = AFTPackage::model()->findByAttributes(array('id' => $id));
            if ($product) {
                $result = $product->name;
            }

            return $result;
        }

        /**
         * @param bool $dataProvider
         * @param int  $limit
         * @param int  $offset
         *
         * @return array|CActiveDataProvider|mixed|null
         */
        public static function getListPackage($dataProvider = FALSE, $limit = 6, $offset = 0)
        {
            $criteria            = new CDbCriteria();
            $criteria->condition = 't.status=:status';
            $criteria->params    = array(':status' => self::FT_PACKAGE_ACTIVE);

            if ($limit) {
                $criteria->limit = $limit;
            }
            if ($offset) {
                $criteria->offset = $offset;
            }
            $criteria->order = 't.name';
            if ($dataProvider) {
                $results = new CActiveDataProvider(self::model(), array(
                    'criteria'   => $criteria,
                    'sort'       => array(
                        'defaultOrder' => 't.name',
                    ),
                    'pagination' => array(
                        'pageSize' => $limit,
                    )
                ));
            } else {
                $results = self::model()->findAll($criteria);
            }

            return $results;
        }

        /**
         * @return string
         */
        public function getStatusLabel()
        {
            return ($this->status == self::FT_PACKAGE_ACTIVE) ? Yii::t('adm/label', 'active') : Yii::t('adm/label', 'inactive');
        }


        public static function getPriceByCode($name)
        {
            if ($name) {
                $packages = AFTPackage::model()->findByAttributes(array('code' => $name));
                if ($packages) {
                    return $packages->price;
                }
            }

            return "";
        }

        public static function getNameByCode($code)
        {
            if ($code) {
                $packages = AFTPackage::model()->findByAttributes(array('code' => $code));
                if ($packages) {
                    return $packages->name;
                }
            }

            return "";
        }

        /**
         * Lấy tất cả sản phẩm
         */
        public function getAllPackage()
        {
            $criteria = new CDbCriteria();

            $data = AFTPackage::model()->findAll($criteria);

            return CHtml::listData($data, 'id', 'name');
        }

    }
