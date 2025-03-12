<?php

    /**
     * This is the model class for table "{{order_warning}}".
     *
     * The followings are the available columns in table '{{order_warning}}':
     *
     * @property string  $id
     * @property string  $order_id
     * @property string  $create_date
     * @property integer $action_code
     * @property integer $status
     * @property string  $last_update
     */
    class OrderWarning extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{order_warning}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('order_id, create_date, action_code, status, last_update', 'required'),
                array('action_code, status', 'numerical', 'integerOnly' => TRUE),
                array('order_id', 'length', 'max' => 15),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, order_id, create_date, action_code, status, last_update', 'safe', 'on' => 'search'),
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
                'order_id'    => 'Mã đơn hàng',
                'create_date' => 'Ngày đặt hàng',
                'action_code' => 'Mức độ cảnh báo',
                'status'      => 'Trạng thái',
                'last_update' => 'Ngày cập nhật',
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
            $criteria->compare('order_id', $this->order_id, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('action_code', $this->action_code);
            $criteria->compare('status', $this->status);
            $criteria->compare('last_update', $this->last_update, TRUE);

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
         * @return OrderWarning the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
