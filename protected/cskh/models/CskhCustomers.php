<?php

    /**
     * This is the model class for table "{{customers}}".
     *
     * The followings are the available columns in table '{{customers}}':
     *
     * @property string  $id
     * @property string  $sso_id
     * @property string  $phone
     * @property string  $username
     * @property string  $email
     * @property string  $birthday
     * @property string  $address
     * @property integer $bonus_point
     * @property string  $create_time
     * @property string  $last_update
     * @property string  $token_key
     * @property string  $full_name
     * @property string  $extra_info
     * @property integer $status
     */
    class CskhCustomers extends ModelBase
    {
        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return '{{customers}}';
        }

        public $info;
        public $orders;

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('phone, username, create_time, status', 'required'),
                array('bonus_point, status', 'numerical', 'integerOnly' => TRUE),
                array('sso_id, email, token_key, info, full_name', 'length', 'max' => 255),
                array('phone', 'length', 'max' => 50),
                array('username', 'length', 'max' => 100),
                array('address', 'length', 'max' => 1000),
                array('birthday, last_update, extra_info', 'safe'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, sso_id, phone, username, email, birthday, address, bonus_point, create_time, last_update, token_key, full_name, extra_info, status', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            return array(
                'orders' => array(self::HAS_MANY, 'CskhOrders', 'sso_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'          => 'ID',
                'sso_id'      => 'Sso',
                'phone'       => 'Phone',
                'username'    => 'Username',
                'email'       => 'Email',
                'birthday'    => 'Birthday',
                'address'     => 'Address',
                'bonus_point' => 'Bonus Point',
                'create_time' => 'Create Time',
                'last_update' => 'Last Update',
                'token_key'   => 'Token Key',
                'full_name'   => 'Full Name',
                'extra_info'  => 'Extra Info',
                'status'      => 'Status',
                'info'        => 'Nhập thông tin tìm kiếm',
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

            $criteria->select    = "t.*, o.id as orders";
            $criteria->condition = " t.username = '$this->info' or t.phone ='$this->info'";
            $criteria->join      = " INNER JOIN {{orders}} o on o.sso_id = t.id";


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
         * @return CskhCustomers the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }
    }
