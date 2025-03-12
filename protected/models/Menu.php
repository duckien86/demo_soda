<?php

    /**
     * This is the model class for table "{{menu}}".
     *
     * The followings are the available columns in table '{{menu}}':
     *
     * @property string  $id
     * @property integer $parent_id
     * @property string  $name
     * @property string  $target_link
     * @property string  $icon
     * @property string  $positions
     * @property integer $sort_order
     * @property integer $status
     */
    class Menu extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{menu}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('parent_id, sort_order, status', 'numerical', 'integerOnly' => TRUE),
                array('name, target_link, icon', 'length', 'max' => 255),
                array('positions', 'length', 'max' => 20),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, parent_id, name, target_link, icon, positions, sort_order, status', 'safe', 'on' => 'search'),
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
                'parent_id'   => 'Parent',
                'name'        => 'Name',
                'target_link' => 'Target Link',
                'icon'        => 'Icon',
                'positions'   => 'Positions',
                'sort_order'  => 'Sort Order',
                'status'      => 'Status',
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
            $criteria->compare('parent_id', $this->parent_id);
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('target_link', $this->target_link, TRUE);
            $criteria->compare('icon', $this->icon, TRUE);
            $criteria->compare('positions', $this->positions, TRUE);
            $criteria->compare('sort_order', $this->sort_order);
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
         * @return Menu the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
