<?php

    /**
     * This is the model class for table "sc_tbl_hobbies".
     *
     * The followings are the available columns in table 'sc_tbl_hobbies':
     *
     * @property integer $id
     * @property string  $name
     * @property string  $icon_image
     * @property string  $status
     * @property integer $index_order
     */
    class Hobbies extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_hobbies';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, index_order', 'numerical', 'integerOnly' => TRUE),
                array('name, icon_image, status', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, icon_image, status, index_order', 'safe', 'on' => 'search'),
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
                'name'        => 'Name',
                'icon_image'  => 'Icon Image',
                'status'      => 'Status',
                'index_order' => 'Index Order',
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
            $criteria->compare('icon_image', $this->icon_image, TRUE);
            $criteria->compare('status', $this->status, TRUE);
            $criteria->compare('index_order', $this->index_order);

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
         * @return Hobbies the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
