<?php

    class AFTPackage extends FTPackage
    {
        CONST FT_PACKAGE_ACTIVE   = 1;
        CONST FT_PACKAGE_INACTIVE = 0;

        CONST FT_PACKAGE_TYPE_KHDN  = 1; //gói dành cho khách hàng doanh nghiệp
        CONST FT_PACKAGE_TYPE_SDL   = 2; //gói dành cho khách hàng sim du lịch
        CONST FT_PACKAGE_TYPE_CARD  = 3; //thẻ cào
        CONST FT_PACKAGE_TYPE_CTV   = 4; //gói dành cho CTV

        CONST BUNDLE = 1;
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('code', 'validate_unique'),
                array('name, code, price, type, status', 'required'),
                array('type, status, is_bundle', 'numerical', 'integerOnly' => TRUE),
                array('name, code, description', 'length', 'max' => 255),
                array('price', 'length', 'max' => 10),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, description, price, type, status, is_bundle', 'safe', 'on' => 'search'),
            );
        }


        public function validate_unique()
        {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.type = :type AND t.code = :code';
            $criteria->params = array(
                ':type' => $this->type,
                ':code' => $this->code,
            );
            if($this->id){
                $criteria->addCondition('t.id != '.$this->id);
            }
            $model = AFTPackage::model()->find($criteria);
            if($model){
                $this->addError('code', 'Mã gói này đã tồn tại');
                return false;
            }
            return true;
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
                'type'        => Yii::t('adm/label', 'type'),
                'status'      => Yii::t('adm/label', 'status'),
                'is_bundle'   => Yii::t('adm/label', 'is_bundle'),
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
            $criteria->compare('type', $this->type, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('is_bundle', $this->is_bundle);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 't.type ASC, t.id DESC'
                ),
                'pagination' => array(
                    'pageSize' => 30,
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
        public static function getListPackage($dataProvider = FALSE, $limit = 0, $offset = 0, $type = 0)
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
            if($type){
                $criteria->addCondition('t.type = '.$type);
            }
            $criteria->order = 't.price, t.name';
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

        /**
         * @return array
         */
        public static function getListType(){
            return array(
                self::FT_PACKAGE_TYPE_KHDN => Yii::t('adm/label', 'package_khdn'),
                self::FT_PACKAGE_TYPE_SDL => Yii::t('adm/label', 'package_sdl'),
                self::FT_PACKAGE_TYPE_CARD => Yii::t('adm/label', 'card'),
                self::FT_PACKAGE_TYPE_CTV => Yii::t('adm/label', 'package_ctv'),
            );
        }
        /**
         * @param $type int
         * @return string
         */
        public static function getTypeLabel($type){
            $list = self::getListType();
            return (isset($list[$type])) ? $list[$type] : $type;
        }

        public static function getPriceByCode($code)
        {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.code = :code';
            $criteria->params = array(
                ':code' => $code
            );

            $cache_key = 'AFTPackage_getPriceByCode_'.$code;
            $results   = Yii::app()->cache->get($cache_key);
            if(!$results){
                $model = AFTPackage::model()->find($criteria);
                if($model){
                    $results = $model->price;
                }
                Yii::app()->cache->set($cache_key, $results, 300);
            }
            return $results;
        }

        public static function getNameByCode($code)
        {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.code = :code';
            $criteria->params = array(
                ':code' => $code
            );

            $cache_key = 'AFTPackage_getNameByCode_'.$code;
            $results   = Yii::app()->cache->get($cache_key);
            if(!$results){
                $model = AFTPackage::model()->find($criteria);
                if($model){
                    $results = $model->name;
                }
                Yii::app()->cache->set($cache_key, $results, 300);
            }
            return $results;
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

        /**
         * @param $type int
         * @return static[]
         */
        public function getPackagesByType($type)
        {
            $criteria = new CDbCriteria();
            $criteria->condition = 't.type = :type';
            $criteria->params = array(
                ':type' => $type,
            );
            return AFTPackage::model()->findAll($criteria);
        }

    }
