<?php

    /**
     * This is the model class for table "cc_tbl_ob".
     *
     * The followings are the available columns in table 'cc_tbl_ob':
     *
     * @property integer $id
     * @property string  $publisher_id
     * @property string  $created_date
     * @property integer $ob_status
     * @property integer $user_id
     */
    class CskhOb extends CActiveRecord
    {

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'cc_tbl_ob';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('ob_status, user_id', 'numerical', 'integerOnly' => TRUE),
                array('publisher_id', 'length', 'max' => 255),
                array('created_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, publisher_id, created_date, ob_status, user_id', 'safe', 'on' => 'search'),
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
                'id'           => 'ID',
                'publisher_id' => 'Publisher',
                'created_date' => 'Created Date',
                'ob_status'    => 'Ob Status',
                'user_id'      => 'User',
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
            $criteria->compare('publisher_id', $this->publisher_id, TRUE);
            $criteria->compare('created_date', $this->created_date, TRUE);
            $criteria->compare('ob_status', $this->ob_status);
            $criteria->compare('user_id', $this->user_id);

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
         * @return CskhOb the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
