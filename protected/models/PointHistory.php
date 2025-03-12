<?php

    /**
     * This is the model class for table "sc_tbl_point_history".
     *
     * The followings are the available columns in table 'sc_tbl_point_history':
     *
     * @property string  $id
     * @property string  $event
     * @property string  $description
     * @property integer $amount
     * @property integer $amount_before
     * @property string  $create_date
     * @property string  $note
     * @property string  $sso_id
     */
    class PointHistory extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'sc_tbl_point_history';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('sso_id', 'required'),
                array('amount, amount_before', 'numerical', 'integerOnly' => TRUE),
                array('id', 'length', 'max' => 20),
                array('event, description, note', 'length', 'max' => 255),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, event, description, amount, amount_before, create_date, note', 'safe', 'on' => 'search'),
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
                'event'         => 'Event',
                'description'   => 'Description',
                'amount'        => 'Amount',
                'amount_before' => 'Amount Before',
                'create_date'   => 'Create Date',
                'note'          => 'Note',
                'sso_id'        => 'sso_id',
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
            $criteria->compare('event', $this->event, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('amount', $this->amount);
            $criteria->compare('amount_before', $this->amount_before);
            $criteria->compare('create_date', $this->create_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);

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
         * @return PointHistory the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
