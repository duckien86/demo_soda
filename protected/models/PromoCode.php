<?php

    /**
     * This is the model class for table "{{promo_code}}".
     *
     * The followings are the available columns in table '{{promo_code}}':
     *
     * @property integer $id
     * @property string  $code
     * @property integer $bonus_point
     * @property string  $discount_percent
     * @property string  $on_event
     * @property string  $start_date
     * @property string  $end_date
     */
    class PromoCode extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{promo_code}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id', 'required'),
                array('id, bonus_point', 'numerical', 'integerOnly' => TRUE),
                array('code, on_event', 'length', 'max' => 255),
                array('discount_percent', 'length', 'max' => 10),
                array('start_date, end_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, code, bonus_point, discount_percent, on_event, start_date, end_date', 'safe', 'on' => 'search'),
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
                'code'             => 'Code',
                'bonus_point'      => 'Bonus Point',
                'discount_percent' => 'Discount Percent',
                'on_event'         => 'On Event',
                'start_date'       => 'Start Date',
                'end_date'         => 'End Date',
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
            $criteria->compare('code', $this->code, TRUE);
            $criteria->compare('bonus_point', $this->bonus_point);
            $criteria->compare('discount_percent', $this->discount_percent, TRUE);
            $criteria->compare('on_event', $this->on_event, TRUE);
            $criteria->compare('start_date', $this->start_date, TRUE);
            $criteria->compare('end_date', $this->end_date, TRUE);

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
         * @return PromoCode the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
