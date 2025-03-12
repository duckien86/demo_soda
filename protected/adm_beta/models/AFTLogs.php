<?php


    class AFTLogs extends FTLogs
    {

        public $active_by;
        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('object_name, object_id', 'required'),
                array('object_id, active_by', 'numerical', 'integerOnly' => TRUE),
                array('object_name', 'length', 'max' => 255),
                array('data_json_before, data_json_after, create_time', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, object_name, object_id, data_json_before, data_json_after, create_time', 'safe', 'on' => 'search'),
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
                'id'               => 'ID',
                'object_name'      => 'Object Name',
                'object_id'        => 'Object',
                'data_json_before' => 'Data Json Before',
                'data_json_after'  => 'Data Json After',
                'create_time'      => 'Create Time',
                'active_by'        => 'Người tác động',
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
        public function search($order_id)
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria = new CDbCriteria;

            $criteria->compare('id', $this->id);
            $criteria->compare('object_name', 'AFTOrders', TRUE);
            $criteria->compare('object_id', $order_id);
            $criteria->compare('data_json_before', $this->data_json_before, TRUE);
            $criteria->compare('data_json_after', $this->data_json_after, TRUE);
            $criteria->compare('create_time', $this->create_time, TRUE);
            $criteria->compare('active_by', $this->active_by, TRUE);

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
         * @return AFTLogs the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
