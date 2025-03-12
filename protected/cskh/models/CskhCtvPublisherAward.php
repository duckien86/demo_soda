<?php

    /**
     * This is the model class for table "{{publisher_award}}".
     *
     * The followings are the available columns in table '{{publisher_award}}':
     *
     * @property string  $id
     * @property string  $campaign_category_id
     * @property double  $amout
     * @property string  $publisher_id
     * @property string  $child_user_id
     * @property string  $transaction_id
     * @property string  $year_month
     * @property integer $year
     * @property integer $month
     * @property string  $note
     * @property string  $created_on
     */
    class CskhCtvPublisherAward extends CActiveRecord
    {
        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_publisher_award';
        }

        public $total_amount_award;
        public $commision_award;
        public $amount_sim;
        public $amount_package;
        public $start_date;
        public $end_date;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('campaign_category_id, amout, publisher_id, year_month', 'required'),
                array('year, month', 'numerical', 'integerOnly' => TRUE),
                array('amout', 'numerical'),
                array('campaign_category_id', 'length', 'max' => 11),
                array('publisher_id, child_user_id, transaction_id', 'length', 'max' => 128),
                array('year_month', 'length', 'max' => 12),
                array('note', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, campaign_category_id, amout, publisher_id, child_user_id, transaction_id, year_month, year, month, note, created_on', 'safe', 'on' => 'search'),
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
                'campaign_category_id' => 'Campaign Category',
                'amout'                => 'Amout',
                'publisher_id'         => 'Tên CTV',
                'child_user_id'        => 'Tên CTV trực thuộc',
                'transaction_id'       => 'transaction_id',
                'year_month'           => 'Year Month',
                'year'                 => 'Year',
                'month'                => 'Month',
                'note'                 => 'Note',
                'created_on'           => 'created_on',
                'amount_sim'           => 'Thưởng trên TB phát triển SIM',
                'amount_package'       => 'Thưởng trên TB phát triển gói',
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
            $criteria->compare('campaign_category_id', $this->campaign_category_id, TRUE);
            $criteria->compare('amout', $this->amout);
            $criteria->compare('publisher_id', $this->publisher_id, TRUE);
            $criteria->compare('child_user_id', $this->child_user_id, TRUE);
            $criteria->compare('transaction_id', $this->transaction_id, TRUE);
            $criteria->compare('year_month', $this->year_month, TRUE);
            $criteria->compare('year', $this->year);
            $criteria->compare('month', $this->month);
            $criteria->compare('note', $this->note, TRUE);
            $criteria->compare('created_on', $this->created_on, TRUE);

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
         * @return CskhCtvPublisherAward the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }

        /**
         * @param $type
         * Lấy tổng hoa hồng
         *
         * @return mixed
         */
        public function getDetailsAward($type, $post = 1, $user_id)
        {


            $criteria = new CDbCriteria();

            $criteria->select    = "t.child_user_id, t.publisher_id, 
                                    (SELECT SUM(t1.amout) FROM tbl_publisher_award t1 
                                     WHERE t1.created_on >='$this->start_date' and t1.created_on <='$this->end_date' 
                                     and t1.campaign_category_id =1 and t1.child_user_id = t.child_user_id) as amount_sim,
                                     (SELECT SUM(t1.amout) FROM tbl_publisher_award t1 
                                     WHERE t1.created_on >='$this->start_date' and t1.created_on <='$this->end_date' 
                                     and t1.campaign_category_id =2 and t1.child_user_id = t.child_user_id) as amount_package";
            $criteria->condition = "t.publisher_id ='" . $user_id . "'";
            $criteria->group     = "t.child_user_id";

            $data = new CActiveDataProvider('CskhCtvPublisherAward', array(
                'criteria'   => $criteria,
                'sort'       => array('defaultOrder' => 't.created_on asc'),
                'pagination' => array(
                    'params'   => array(
                        'CskhCtvActions[start_date]' => $this->start_date,
                        'CskhCtvActions[end_date]'   => $this->end_date,
                        "CskhCtvActions[type]"       => $type,
                    ),
                    'pageSize' => 30,
                ),
            ));

            return $data;
        }

    }
