<?php

    /**
     * This is the model class for table "tbl_ob_history".
     *
     * The followings are the available columns in table 'tbl_ob_history':
     *
     * @property string  $id
     * @property string  $user_id
     * @property integer $cskh_user_id
     * @property string  $created_on
     * @property integer $status_old
     * @property integer $status_new
     * @property integer $note
     */
    class CskhObHistory extends CActiveRecord
    {
        public $start_date;
        public $end_date;

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_ob_history';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('cskh_user_id, status_old, status_new', 'numerical', 'integerOnly' => TRUE),
                array('user_id', 'length', 'max' => 255),
                array('note', 'length', 'max' => 2000),
                array('created_on', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, cskh_user_id, created_on, status_old, status_new', 'safe', 'on' => 'search'),
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
                'user_id'      => 'User',
                'cskh_user_id' => 'Người OB',
                'created_on'   => 'Created On',
                'status_old'   => 'Status Old',
                'status_new'   => 'Status New',
                'start_date'   => 'Ngày bắt đầu',
                'end_date'     => 'Ngày kết thúc',
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
            $criteria->compare('user_id', $this->user_id, TRUE);

            $criteria->compare('created_on', $this->created_on, TRUE);
            $criteria->compare('status_old', $this->status_old);
            $criteria->compare('status_new', $this->status_new);
            if ($this->start_date && $this->end_date) {
                $this->start_date = date("Y-m-d", strtotime(str_replace('/', '-', $this->start_date))) . ' 00:00:00';
                $this->end_date   = date("Y-m-d", strtotime(str_replace('/', '-', $this->end_date))) . ' 23:59:59';

                $criteria->addCondition("created_on >='$this->start_date' and created_on <='$this->end_date'");
            }
            if ($this->cskh_user_id != '') {
                $criteria->addCondition("cskh_user_id ='$this->cskh_user_id'");
            }

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
        }

        /**
         * @return CDbConnection the database connection used for this class
         */
        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return CskhObHistory the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
