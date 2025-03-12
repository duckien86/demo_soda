<?php

    /**
     * This is the model class for table "{{packages_nations}}".
     *
     * The followings are the available columns in table '{{packages_nations}}':
     *
     * @property string  $id
     * @property string  $package_id
     * @property string  $nation_code
     * @property integer $type
     */
    class PackagesNations extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{packages_nations}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('package_id, nation_code', 'length', 'max' => 255),
                array('type', 'numerical', 'integerOnly' => TRUE),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, package_id, nation_code, type', 'safe', 'on' => 'search'),
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
                'id'          => 'ID',
                'package_id'  => 'Package',
                'nation_code' => 'Nation Code',
                'type'        => 'Type',
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
            $criteria->compare('package_id', $this->package_id, TRUE);
            $criteria->compare('nation_code', $this->nation_code, TRUE);
            $criteria->compare('type', $this->type);

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
         * @return PackagesNations the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
