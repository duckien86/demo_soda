<?php

    /**
     * This is the model class for table "cc_tbl_log".
     *
     * The followings are the available columns in table 'cc_tbl_log':
     *
     * @property integer $id
     * @property integer $user_id
     * @property string  $ip_adress
     * @property string  $action
     * @property string  $description
     * @property string  $create_date
     */
    class Log extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */

        public $user_action;

        public function tableName()
        {
            return 'cc_tbl_log';
        }


        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('user_id', 'required'),
                array('id, user_id, object_id', 'numerical', 'integerOnly' => TRUE),
                array('ip_adress, action', 'length', 'max' => 255),
                array('description', 'length', 'max' => 500),
                array('create_date', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, user_id, ip_adress, action, description, create_date', 'safe', 'on' => 'search'),
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

        public function afterFind()
        {
            if ($this->user_id) {
                $model = User::model()->findByAttributes(array('id' => $this->user_id));
                if ($model) {
                    $this->user_action = $model->username;
                }
            }

        }

        public function attributeLabels()
        {
            return array(
                'id'          => 'ID',
                'user_id'     => 'Người tác động',
                'user_action' => 'Người tác động',
                'ip_adress'   => 'Địa chỉ ip',
                'action'      => 'Hành động',
                'object_id'   => 'id tác động',
                'description' => 'Mô tả hành động',
                'create_date' => 'Ngày tác động',
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
            $criteria->compare('user_id', $this->user_id);
            $criteria->compare('ip_adress', $this->ip_adress, TRUE);
            $criteria->compare('action', $this->action, TRUE);
            $criteria->compare('description', $this->description, TRUE);
            $criteria->compare('create_date', $this->create_date, TRUE);

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
         * @return Log the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }


    }
