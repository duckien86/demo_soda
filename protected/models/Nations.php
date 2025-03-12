<?php

    /**
     * This is the model class for table "{{nations}}".
     *
     * The followings are the available columns in table '{{nations}}':
     *
     * @property string  $id
     * @property string  $name
     * @property string  $code
     * @property string  $info
     * @property string  $continent
     * @property integer $status
     * @property string  $telco_prepaid
     * @property string  $telco_postpaid
     */
    class Nations extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{nations}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('status', 'numerical', 'integerOnly' => TRUE),
                array('name, code', 'length', 'max' => 255),
                array('continent', 'length', 'max' => 10),
                array('info, telco_prepaid, telco_postpaid', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, name, code, info, continent, status, telco_prepaid, telco_postpaid', 'safe', 'on' => 'search'),
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
                'id'             => 'ID',
                'name'           => 'Name',
                'code'           => 'Code',
                'info'           => 'Info',
                'continent'      => 'Continent',
                'status'         => 'Status',
                'telco_prepaid'  => 'Telco Prepaid',
                'telco_postpaid' => 'Telco Postpaid',
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
            $criteria->compare('name', $this->name, TRUE);
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('info', $this->info, TRUE);
            $criteria->compare('continent', $this->continent, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('telco_prepaid', $this->telco_prepaid, TRUE);
            $criteria->compare('telco_postpaid', $this->telco_postpaid, TRUE);

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
         * @return Nations the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
