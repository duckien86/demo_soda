<?php

    /**
     * This is the model class for table "{{backend_logs}}".
     *
     * The followings are the available columns in table '{{backend_logs}}':
     *
     * @property string $id
     * @property string $username
     * @property string $ipaddress
     * @property string $logtime
     * @property string $controller
     * @property string $action
     * @property string $detail
     */
    class ABackendLogs extends CActiveRecord
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{backend_logs}}';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('username, ipaddress, logtime', 'required'),
                array('username, ipaddress', 'length', 'max' => 50),
                array('controller, action, detail', 'length', 'max' => 255),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, ipaddress, logtime, controller, action, detail', 'safe', 'on' => 'search'),
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
                'id'         => 'ID',
                'username'   => 'Username',
                'ipaddress'  => 'Ipaddress',
                'logtime'    => 'Logtime',
                'controller' => 'Controller',
                'action'     => 'Action',
                'detail'     => 'Detail',
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
            $criteria->compare('username', $this->username, TRUE);
            $criteria->compare('ipaddress', $this->ipaddress, TRUE);
            $criteria->compare('logtime', $this->logtime, TRUE);
            $criteria->compare('controller', $this->controller, TRUE);
            $criteria->compare('action', $this->action, TRUE);
            $criteria->compare('detail', $this->detail, TRUE);

            return new CActiveDataProvider($this, array(
                'criteria'   => $criteria,
                'sort'       => array(
                    'defaultOrder' => 'logtime DESC',
                ),
                'pagination' => array(
                    'pageSize' => 50,
                ),
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         *
         * @param string $className active record class name.
         *
         * @return ABackendLogs the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
