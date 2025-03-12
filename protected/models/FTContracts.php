<?php

    /**
     * This is the model class for table "tbl_contracts".
     *
     * The followings are the available columns in table 'tbl_contracts':
     *
     * @property string  $id
     * @property integer $code
     * @property integer $user_id
     * @property string  $create_time
     * @property string  $last_update
     * @property string  $start_date
     * @property string  $finish_date
     * @property string  $note
     * @property integer $status
     * @property integer $create_by
     */
    class FTContracts extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_contracts';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('user_id, status, create_by', 'numerical', 'integerOnly' => TRUE),
                array('code, note', 'length', 'max' => 255),
                array('code, create_time, last_update, start_date, finish_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, code, user_id, create_time, last_update, start_date, finish_date, note, status, create_by', 'safe', 'on' => 'search'),
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
                'id'                   => 'ID',
                'code'                 => 'Code',
                'user_id'              => 'User',
                'create_time'          => 'Create Time',
                'last_update'          => 'Last Update',
                'start_date'           => 'Start Date',
                'finish_date'          => 'Finish Date',
                'note'                 => 'Note',
                'status'               => 'Status',
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
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('user_id', $this->user_id);
            $criteria->compare('create_time', $this->create_time, TRUE);
            $criteria->compare('last_update', $this->last_update, TRUE);
            $criteria->compare('start_date', $this->start_date, TRUE);
            $criteria->compare('finish_date', $this->finish_date, TRUE);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('create_by', $this->create_by, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria' => $criteria,
            ));
        }

        /**
         * @return CDbConnection the database connection used for this class
         */
        public function getDbConnection()
        {
            return Yii::app()->db_freedoo_tourist;
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return FTContracts the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
