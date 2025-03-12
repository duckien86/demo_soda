<?php

    /**
     * This is the model class for table "{{user_member}}".
     *
     * The followings are the available columns in table '{{user_member}}':
     *
     * @property string  $id
     * @property string  $username
     * @property string  $password
     * @property string  $fullname
     * @property integer $status
     * @property string  $created_date
     * @property string  $lastest_login
     * @property string  $ip
     * @property string  $mobile
     * @property string  $email
     * @property string  $created_by
     * @property string  $recoveryCode
     * @property string  $recoveryTime
     * @property string  $activeCode
     * @property string  $activeTime
     * @property integer $role
     * @property string  $access_key
     * @property string  $secret_key
     */
    class CskhCtvUserMember extends CActiveRecord
    {
        //

        public $confirmPassword;
        public $initialPassword;

        const STATUS_ACTIVE   = 1;
        const STATUS_INACTIVE = 0;
        const STATUS_BLOCKED  = -1;

        const ROLE_USER       = 1;
        const ACCOUNTING_USER = 2;

        //
        const TYPE_CTV = 1;

        public function getDbConnection()
        {
            return Yii::app()->db_affiliates;
        }

        /**
         * @return string the associated database table name
         */
        public function tableName()
        {
            return 'tbl_user_member';
        }

        /**
         * @return array validation rules for model attributes.
         */
        public function rules()
        {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(
                array('id, username, password', 'required'),
                array('status, role', 'numerical', 'integerOnly' => TRUE),
                array('id, username, password, fullname, mobile, email', 'length', 'max' => 255),
                array('ip', 'length', 'max' => 50),
                array('created_by', 'length', 'max' => 128),
                array('recoveryCode, activeCode, access_key, secret_key', 'length', 'max' => 100),
                array('recoveryTime, activeTime', 'length', 'max' => 11),
                array('created_date, lastest_login', 'safe'),
                array('username', 'unique', 'message' => 'Tên đăng nhập đã tồn tại!'),
                array('email', 'unique', 'message' => 'Email đã tồn tại!'),
                array('email', 'email', 'message' => 'Email không đúng định dạng!'),
                array('mobile', 'match', 'pattern' => '/^([+]?[0-9 ]+)$/'),
                // The following rule is used by search().
                // @todo Please remove those attributes that should not be searched.
                array('id, username, password, fullname, status, created_date, lastest_login, ip, mobile, email, created_by, recoveryCode, recoveryTime, activeCode, activeTime, role, access_key, secret_key', 'safe', 'on' => 'search'),
            );
        }

        /**
         * @return array relational rules.
         */
        public function relations()
        {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(//                'users' => array(self::HAS_ONE, 'Users', 'user_id'),
            );
        }

        /**
         * @return array customized attribute labels (name=>label)
         */
        public function attributeLabels()
        {
            return array(
                'id'            => 'ID',
                'username'      => 'Username',
                'password'      => 'Password',
                'fullname'      => 'Fullname',
                'status'        => 'Status',
                'created_date'  => 'Created Date',
                'lastest_login' => 'Lastest Login',
                'ip'            => 'Ip',
                'mobile'        => 'Mobile',
                'email'         => 'Email',
                'created_by'    => 'Created By',
                'recoveryCode'  => 'Recovery Code',
                'recoveryTime'  => 'Recovery Time',
                'activeCode'    => 'Active Code',
                'activeTime'    => 'Active Time',
                'role'          => 'Role',
                'access_key'    => 'Access Key',
                'secret_key'    => 'Secret Key',
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
            $criteria->compare('password', $this->password, TRUE);
            $criteria->compare('fullname', $this->fullname, TRUE);
            $criteria->compare('status', $this->status);
            $criteria->compare('created_date', $this->created_date, TRUE);
            $criteria->compare('lastest_login', $this->lastest_login, TRUE);
            $criteria->compare('ip', $this->ip, TRUE);
            $criteria->compare('mobile', $this->mobile, TRUE);
            $criteria->compare('email', $this->email, TRUE);
            $criteria->compare('created_by', $this->created_by, TRUE);
            $criteria->compare('recoveryCode', $this->recoveryCode, TRUE);
            $criteria->compare('recoveryTime', $this->recoveryTime, TRUE);
            $criteria->compare('activeCode', $this->activeCode, TRUE);
            $criteria->compare('activeTime', $this->activeTime, TRUE);
            $criteria->compare('role', $this->role);
            $criteria->compare('access_key', $this->access_key, TRUE);
            $criteria->compare('secret_key', $this->secret_key, TRUE);

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
         * @return CskhCtvUserMember the static model class
         */
        public static function model($className = __CLASS__)
        {
            return parent::model($className);
        }


        public static function getStatus($user_id)
        {
            if ($user_id) {
                $user_member = CskhCtvUserMember::model()->find('id=:id', array(':id' => $user_id));
                if ($user_member) {
                    return $user_member->status;
                }
            }

            return "Chưa xác định";
        }
    }
